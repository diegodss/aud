<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\PlanillaSeguimiento;
use App\CentroResponsabilidad;
use App\Subsecretaria;
use App\PlanillaSeguimientoImport;
use App\ProcesoAuditado;
use App\Auditor;
use App\AreaProcesoAuditado;
use App\RelProcesoAuditor;
use Session;
use Excel;
use File;
use App\Compromiso;
use App\CompromisoNomenclatura;
use App\CompromisoImport;

class CompromisoImportController extends Controller {

    public function __construct() {

        $this->controller = "compromiso_import";
        $this->title = "Actualización de PMG a NO PMG";
        $this->subtitle = "Actualización en bulk";

        $this->middleware('auth');
        // $this->middleware('admin');
    }

    public function index($errorMsg = null, $returnData = null) {

        $returnData["errorMsg"] = $errorMsg;
        $path = base_path() . config('system.folder_import') . '/';
        $files = File::files($path);
        $returnData['files'] = $files;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Upload de proceso_auditado";

        return View::make('compromiso_import.upload', $returnData);
    }

    public function read($fileName) {

        set_time_limit(0);
        $path = base_path() . config('system.folder_import') . '/';
        $file = $path . $fileName; //"Consolidado_Productos_MINSAL_alimento.xlsx";

        $total = 0;
        Excel::load($file, function ($reader)use(&$total) {

            $reader->each(function($sheet)use(&$total) {

                $title = $sheet->getTitle();
                foreach ($sheet as $row) {

                    if ($title === "Minsal") {

                        Log::info($row);
                        if ($row["nomenclatura"] == "PMG" || $row["nomenclatura"] == "NO PMG") {
                            $compromiso = Compromiso::find($row["correlativo_interno"]);

                            /* Verifica si nomenclatura actual es distinta de nueva nomenclatura */
                            if ($compromiso->nomenclatura != $row["nomenclatura"]) {
                                $compromiso_nomenclatura = New \App\CompromisoNomenclatura();
                                $compromiso_nomenclatura->id_compromiso = $row["correlativo_interno"];
                                $compromiso_nomenclatura->nomenclatura = $compromiso->nomenclatura;
                                $compromiso_nomenclatura->save();
                            }
                            $compromiso->nomenclatura = $row["nomenclatura"];
                            $compromiso->save();
                            $total++;
                        }

                        /* =========================================================== */
                        /*
                          $correlativo_interno = $row["correlativo_interno"]; // 674
                          $nomenclatura = $row["correlativo_interno"];  //"PMG G";
                          // Rescata el proceso auditado
                          $proceso_auditado = ProcesoAuditado::getByCorrelativoInterno($row["correlativo_interno"]);

                          // Genera una linea para historico
                          if (is_object($proceso_auditado)) {
                          $pan = New ProcesoAuditadoNomenclatura();
                          $pan->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
                          $pan->nomenclatura = $proceso_auditado->nomenclatura;
                          $pan->save();
                          }
                          // Actualiza la nomenclatura actual
                          $proceso_auditado->nomenclatura = $row["correlativo_interno"];
                          $proceso_auditado->save();

                          Log::info($proceso_auditado);
                          Log::info($pan);

                         */
                        /* =========================================================== */
                    }
                }
            });
        });

        $compromiso_import = New CompromisoImport();
        $compromiso_import->documento_adjunto = $file;
        $compromiso_import->descripcion = $fileName;
        $compromiso_import->total = $total;
        $compromiso_import->tipo_import = "Cambio de PMG";
        $compromiso_import->usuario_registra = 1; // TODO Auth::user()->id;
        $compromiso_import->save();

        $returnData["total"] = $total;

        return $this->index(null, $returnData);
    }

    public function upload(Request $request) {

        $messages = [
            'documento_adjunto.*.mimetypes' => 'Por favor informe un archivo en formato XLS o XLSX'
        ];

        $this->validate($request, [
            'documento_adjunto.*' => 'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ], $messages);


        $errorMsg = null;
        if (isset($request->documento_adjunto)) {

            foreach ($request->documento_adjunto as $file) {

                if (is_object($file)) {

                    $id = round(microtime(true) * 1000);

                    $fechaActual = date("Y") . "-" . date("m") . "-" . date("d");
                    $fileName = $fechaActual . "-" . $file->getClientOriginalName();
                    $fileName = str_replace(".", "-" . $id . ".", $fileName);

                    $path = base_path() . config('system.folder_import') . '/';
                    if (!File::exists($path)) {
                        $result = File::makeDirectory($path, 0775);
                    }
                    $documento_adjunto = $path . $fileName;
                    $file->move($path, $fileName);
                    $validFile = $this->validaDocumentoUpload($fileName);
                    //Log::info($validFile);
                    if (!$validFile["resultado"]) {
                        $errorMsg = $validFile["mensaje"];
                        File::delete($file);
                        //return $this->excelFormUpload($errorMsg);
                    }
                }
            }
        }

        return $this->index($errorMsg);
    }

    public function validaDocumentoUpload($fileName) {

        // try {
        $errorMsg = null;
        $retorno = true;
        $path = base_path() . config('system.folder_import') . '/';
        $file = $path . $fileName;

        Excel::load($file, function ($reader)use(&$retorno, &$errorMsg) {

            //print_r($reader);
            $sheet_name = array();
            $reader->each(function($sheet)use(&$sheet_name, &$retorno, &$errorMsg) {

                $title = $sheet->getTitle();
                $sheet_name[] = $title;

                if ($title === "Minsal") {
                    $firstrow = $sheet->first()->toArray();
                    $columnaProducto = $this->getColumnaProducto();

                    foreach ($columnaProducto as $columna) {
                        if (!array_key_exists($columna, $firstrow)) {
                            $retorno = false;
                            $errorMsg[] = "La hoja  " . $title . " indicada no posee la columna     " . $columna;
                        }
                    }
                }
            });
            /* Comentado, porque como hay 1 hoja, este metodo no funciona
              if (!in_array("Minsal", $sheet_name)) {
              $errorMsg[] = "El archivo debe contener una hoja llamada Minsal";
              $retorno = false;
              Log::info($errorMsg);
              } */
        });
        return array('resultado' => $retorno, 'mensaje' => $errorMsg);
    }

    public function getColumnaProducto() {
        $columnaProducto = array(
            "correlativo_interno"
            , "nomenclatura"
        );
        return $columnaProducto;
    }

    public function archivos_importados() {

        $proceso_auditado_import = ProcesoAuditadoImport::all();

        $grid = \DataGrid::source($medio_verificacion);
        $grid->add('id_proceso_auditado_import', 'ID')->style("width:80px");
        $grid->add('descripcion', 'descripcion');
        $grid->add('documento_adjunto', 'Link')->cell(function( $value, $row) {
            $documento_adjunto = str_replace(config('system.local_path'), url('/') . "/", $row->documento_adjunto);
            $link = "<a href='" . $documento_adjunto . "' target='_blank'>visualizar</a>";
            return $link;
        })->style("width:90px; text-align:center");
        return $grid;
    }

    public function tutorial(Request $request) {

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Tutorial para formato de archivo de upload";
        $returnData['controller'] = $this->controller;

        return View::make('compromiso_import.tutorial', $returnData);
    }

    public function create() {

    }

    public function store(Request $request) {

    }

    public function show($id) {

    }

    public function edit($id, $show_success_message = false) {

    }

    public function update($id, Request $request) {

    }

    public function delete($id) {

    }

    public function destroy($id) {

    }

}
