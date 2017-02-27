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

class PlanillaSeguimientoController extends Controller {

    public function __construct() {

        $this->controller = "planilla_seguimiento";
        $this->title = "Planilla de Seguimiento";
        $this->subtitle = "Reporteria";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function setViewVariables() {

        $form = new \stdClass();
        $form->condicion = "";
        $form->estado = "";
        $form->nomenclatura = "";
        $form->division = "";
        $form->subsecretaria = "";
        $form->plazo_comprometido_inicio = "";
        $form->plazo_comprometido_fin = "";
        $this->form = $form;
    }

    public function importExcel() {
        set_time_limit(0);
        $path = base_path() . '/public/import' . '/';
//$file = $path . "modelo_para_import_ra.xlsx";
//$file = $path . "modelo_para_import_2016_12_07.xlsx";
        $file = $path . "modelo_para_import_ra_2017.xlsx";
//$file = $path . "modelo_para_import-51.xlsx";

        Excel::load($file, function ($reader) {

//print_r($reader);
            $reader->each(function($sheet) {

                $title = $sheet->getTitle();
                foreach ($sheet as $row) {
                    $this->insertPlanillaSeguimientoImport($row);
                }
            });
        });
    }

    public function setIdCompromisoPadre() {
        $psi = PlanillaSeguimientoImport::reprogramado()->get();
        print_r("<table border='1'>");

        $x = 0;
        foreach ($psi as $psiRow) {
            $x++;
            $line = $psiRow->observacion;
            $line = str_replace("°", "", $line);
            $line = str_replace("º", "", $line);

            print_r("<tr>");
            print_r("<td>");
            print_r($x . " - " . $psiRow->correlativo_interno);
            print_r("</td>");
            print_r("<td>");
            print_r($line);
            print_r("</td>");
            print_r("<td>");

            $compromiso = Compromiso::getIdByCorrelativoInterno($psiRow->correlativo_interno)->first();

            $line = str_replace("Proviene de la Reprog_Correl_", "", $line);
            $line = str_replace("_", " ", $line);

            $findme = 'N';
            $pos = strpos($line, $findme);

            if ($pos === false) {

                $var = explode(" ", $line);
//if (is_int($var[1])) {

                if (isset($var[1])) {
                    $var[1] = (int) end($var);
                } elseif (is_integer(trim($var[0])) === false) {

                    $var[1] = $var[0];
                } else {

                    $pos = strpos($line, "_");

                    if ($pos === false) {
// print_r($var[1]);

                        /* $pos = strpos($line, "da vez. Correlativo");
                          if ($pos === false) {

                          $pos = strpos($line, "porviene Correlativo");
                          if ($pos === false) {

                          } else {
                          $var = explode("porviene Correlativo", $line);
                          }
                          } else {
                          $var = explode("da vez. Correlativo", $line);
                          } */
                    } else {
                        $var = explode("_", $line);
//    print_r("diego");
                    }
                }

//}
            } else {
                $var = explode($findme, $line);
//  print_r($var[1]);
            }
//print_r($var);

            $correlativo_padre = (int) $var[1];
            print_r($correlativo_padre);

            $compromiso_padre = Compromiso::getIdByCorrelativoInterno($correlativo_padre)->get();
//print_r("." . count($compromiso_padre));

            if (count($compromiso_padre) >= 1) {

                $compromiso_padre = $compromiso_padre[0];
//Log::debug($compromiso_padre[0]);
                $compromiso->id_compromiso_padre = $compromiso_padre->id_compromiso;
                $compromiso->save();
            }
            print_r("</td>");
            print_r("</tr>");
        }
        print_r("</table>");
        return "false";
    }

    public function procesaExcel() {

        $psi = PlanillaSeguimientoImport::getProcesoAuditado()->get();

        foreach ($psi as $psiRow) {

            if ($psiRow->subsecretaria == "SSP") {
                $ds_subsecretaria = "Salud Pública";
            } else if ($psiRow->subsecretaria == "Ambas") {
                $ds_subsecretaria = "Ambas";
            } else if ($psiRow->subsecretaria == "") {
                $ds_subsecretaria = "";
            } else {
                $ds_subsecretaria = "Redes Asistenciales";
            }

// -------------- ADD PROCESO AUDITADO ----------------
            $proceso_auditado = new \App\ProcesoAuditado;
            $proceso_auditado->nombre_proceso_auditado = $psiRow->proceso;
            $proceso_auditado->fecha = $psiRow->fecha_informe;
            $proceso_auditado->ano = $psiRow->ano;
//$proceso_auditado->nomenclatura = $psiRow->nomenclatura;
            $numero_informe = explode(" ", $psiRow->n_informe);

            $proceso_auditado->objetivo_auditoria = $psiRow->objetivo_auditoria;
            $proceso_auditado->actividad_auditoria = $psiRow->actividad_auditoria;
            $proceso_auditado->codigo_caigg = $psiRow->codigo_caigg;
            $proceso_auditado->proceso_transversal = $psiRow->proceso_transversal;
            $proceso_auditado->tipo_informe = $psiRow->tipo_informe;

//Log::info($psiRow);
//print_r(count($numero_informe));
            if (count($numero_informe) >= 2) {

                $n = $numero_informe[1];
                $n = str_replace("N°", "", $n);
                $n = str_replace("Nº", "", $n);
                $n = str_replace("Nº", "", $n);
                $n = str_replace("Nº ", "", $n);
                $n = str_replace("N\u00ba", "", $n);
                $n = trim($n);
                $test = "";
                if (isset($numero_informe[2])) {
                    $n = trim($numero_informe[2]); // && is_int(trim($numero_informe[2])
                    $test = $numero_informe[2];
                }
//Log::info("\n" . $n . " - " . $numero_informe[1] . " $ " . $test . "<br>");
                $proceso_auditado->numero_informe = $n;
                $proceso_auditado->numero_informe_unidad = $numero_informe[0];
            } else {
                $proceso_auditado->numero_informe = $numero_informe[0];
                $proceso_auditado->numero_informe_unidad = "";
            }

//Log::error($proceso_auditado);
            $proceso_auditado->usuario_registra = 1;
            $proceso_auditado->save();
//--------------- ADD AREA PROCESO AUDITADO -----------------
            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
            $area_proceso_auditado->tabla = 'ministerio';
            $area_proceso_auditado->id_tabla = 0;
            $area_proceso_auditado->descripcion = "Ministerio de Salud";
            $area_proceso_auditado->usuario_registra = 1;
            $area_proceso_auditado->save();

            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
            $area_proceso_auditado->tabla = 'subsecretaria';
            $area_proceso_auditado->id_tabla = 0;
            $area_proceso_auditado->descripcion = $ds_subsecretaria;
            $area_proceso_auditado->usuario_registra = 1;
            $area_proceso_auditado->save();

            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
            $area_proceso_auditado->tabla = 'division';
            $area_proceso_auditado->id_tabla = 0;
            $area_proceso_auditado->descripcion = $psiRow->division;
            $area_proceso_auditado->usuario_registra = 1;
            $area_proceso_auditado->save();

            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
            $area_proceso_auditado->tabla = 'departamento';
            $area_proceso_auditado->id_tabla = 0;
            $area_proceso_auditado->descripcion = $psiRow->area_auditada;
            $area_proceso_auditado->usuario_registra = 1;
            $area_proceso_auditado->save();

// --------- ADD RELACION PROCESO AUDITOR ------------------
            $relProcesoAuditor = new RelProcesoAuditor();
            $relProcesoAuditor->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
            $relProcesoAuditor->id_auditor = Auditor::getIdByNombreAuditor(trim($psiRow->nombre_auditor));
            $relProcesoAuditor->jefatura_equipo = true;
            $relProcesoAuditor->usuario_registra = 1;
            $relProcesoAuditor->save();
//Log::debug($psiRow->nombre_auditor);
//Log::debug($relProcesoAuditor);
        }

// ---------- OBTIENE TODOS LOS REGISTROS INSERTADOS -------------------
        $proceso_auditado = ProcesoAuditado::all();

        foreach ($proceso_auditado as $proceso_auditado_row) {

            $numero_informe = $proceso_auditado_row->numero_informe_unidad . " Nº" . $proceso_auditado_row->numero_informe;
            print_r("--------- (" . $proceso_auditado_row->id_proceso_auditado . ") " . $proceso_auditado_row->nombre_proceso_auditado . " " . $numero_informe . "---- <br>");

            $busqueda["proceso"] = $proceso_auditado_row->nombre_proceso_auditado;
            $busqueda["fecha_informe"] = $proceso_auditado_row->fecha;
            $busqueda["ano"] = $proceso_auditado_row->ano;

//$busqueda["nomenclatura"] = $proceso_auditado_row->nomenclatura; // quitando reprogramado
            $busqueda["division"] = $proceso_auditado_row->getDivision($proceso_auditado_row->id_proceso_auditado);
            $busqueda["area_auditada"] = $proceso_auditado_row->getAreaAuditada($proceso_auditado_row->id_proceso_auditado);
//$busqueda["nombre_auditor"] = nombre_auditor;
//DB::enableQueryLog();
            $psi_g = PlanillaSeguimientoImport::busqueda($busqueda);

//Log::error($busqueda);
//Log::error(DB::getQueryLog());
            $a = 0;
            foreach ($psi_g as $psi_g_row) {
                $a++;
//Log::debug($psi_g_row);
                print_r($a . " Hallazgo: " . $psi_g_row->n_informe . "=" . $psi_g_row->descripcion_del_hallazgo . " <br>");

                $hallazgo = new \App\Hallazgo();
                $hallazgo->id_proceso_auditado = $proceso_auditado_row->id_proceso_auditado;
                $hallazgo->nombre_hallazgo = $psi_g_row->descripcion_del_hallazgo;
                $hallazgo->recomendacion = $psi_g_row->descripcion_recomendacion;
                $hallazgo->criticidad = $psi_g_row->criticidad;
                $hallazgo->usuario_registra = 1;
                $hallazgo->save();
//Log::debug($hallazgo);

                if ($psi_g_row->estado != "En Suscripción") {

                    $compromiso = new \App\Compromiso;
                    $compromiso->id_hallazgo = $hallazgo->id_hallazgo;
                    $compromiso->nomenclatura = $psi_g_row->nomenclatura;
                    $compromiso->nombre_compromiso = $psi_g_row->descripcion_compromiso;
                    $compromiso->responsable = $psi_g_row->responsable;
                    $compromiso->plazo_estimado = $psi_g_row->plazo_estimado == "--" ? $psi_g_row->plazo_que_compromete_auditado : $psi_g_row->plazo_estimado;
                    $compromiso->plazo_comprometido = $psi_g_row->plazo_que_compromete_auditado;
                    $compromiso->correlativo_interno = $psi_g_row->correlativo_interno;
                    $compromiso->usuario_registra = 1;
                    $compromiso->save();
//Log::debug($compromiso);

                    $seguimiento = new \App\Seguimiento;
                    $seguimiento->id_compromiso = $compromiso->id_compromiso;
                    $diferencia = $psi_g_row->diferencia;
                    if ($psi_g_row->diferencia == "#VALUE!") {
                        $diferencia = 0;
                    }
                    $seguimiento->diferencia_tiempo = $diferencia;
                    $seguimiento->porcentaje_avance = $psi_g_row->avance;
                    $seguimiento->condicion = $psi_g_row->condicion;
                    $seguimiento->estado = $psi_g_row->estado;
                    $seguimiento->fecha_ingreso = $proceso_auditado_row->fecha;
                    $seguimiento->usuario_registra = 1;
                    $seguimiento->save();

                    $mv = explode(PHP_EOL, $proceso_auditado_row->medios_de_verificacion);
                    foreach ($mv as $mv_unit) {
                        $medio_verificacion = new \App\MedioVerificacion();
                        $medio_verificacion->id_compromiso = $compromiso->id_compromiso;
                        $medio_verificacion->descripcion = $mv_unit;
                        $medio_verificacion->documento_adjunto = $mv_unit;
                        $medio_verificacion->usuario_registra = 1;
                        $medio_verificacion->save();
                    }
//Log::debug($seguimiento);
                }
            }
// quitando reprogramado

            $nomenclatura = "vacio";
            if (isset($psi_g_row->nomenclatura)) {
                $nomenclatura = $psi_g_row->nomenclatura;
            }

// $psi_g_row->grabado = 1;
// $psi_g_row->save();

            $proceso_auditado_row->nomenclatura = $nomenclatura;
            $proceso_auditado_row->cantidad_hallazgo = $a;
            $proceso_auditado_row->save();

//$this->setIdCompromisoPadre();
            print_r(" <br><br>");
        }
    }

    public function formataFecha($fecha) {

        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $ano = substr($fecha, 0, 4);
        $novafecha = $dia . "-" . $mes . "-" . $ano;
        return $novafecha;
    }

    public function insertPlanillaSeguimientoImport($row) {

        $psi = new PlanillaSeguimientoImport();
        $psi->correlativo_interno = trim($row["correlativo_interno"]);
        $psi->nomenclatura = trim($row["nomenclatura"]);
        $psi->ano = trim($row["ano"]);
        $psi->subsecretaria = trim($row["subsecretaria"]);

        $psi->objetivo_auditoria = trim($row["objetivo_auditoria"]);
        $psi->actividad_auditoria = trim($row["actividad_auditoria"]);
        $psi->codigo_caigg = trim($row["codigo_caigg"]);
        $psi->proceso_transversal = trim($row["proceso_transversal"]);
        $psi->tipo_informe = trim($row["tipo_informe"]);

        $psi->division = trim($row["division"]);
        $psi->area_auditada = trim($row["area_auditada"]);
        $psi->n_informe = trim($row["n_informe"]);
        $psi->fecha_informe = $this->formataFecha(trim($row["fecha_informe"]));
        $psi->proceso = trim($row["proceso"]);
        $psi->nombre_auditor = trim(strtolower($row["nombre_auditor"]));
        $psi->descripcion_del_hallazgo = trim($row["descripcion_del_hallazgo"]);
        $psi->descripcion_recomendacion = trim($row["descripcion_recomendacion"]);
        $psi->responsable = trim($row["responsable"]);
        $psi->criticidad = trim($row["criticidad"]);
        $psi->descripcion_compromiso = trim($row["descripcion_compromiso"]);
        $psi->plazo_estimado = $this->formataFecha(trim($row["plazo_estimado"]));
        $psi->plazo_que_compromete_auditado = $this->formataFecha(trim($row["plazo_que_compromete_auditado"]));
        $psi->diferencia = trim($row["diferencia"]);
        $psi->avance = trim($row["avance"]);
        $psi->condicion = trim($row["condicion"]);
        $psi->estado = trim($row["estado"]);
        $psi->medios_de_verificacion = trim($row["medios_de_verificacion"]);
        $psi->observacion = trim($row["observacion"]);
        $psi->save();
    }

    public function index(Request $request) {


        $this->setViewVariables();
        DB::enableQueryLog();

        $returnData['nomenclatura'] = config('collection.nomenclatura');
        $returnData['estado'] = config('collection.estado');
        $returnData['condicion'] = config('collection.condicion');
        $returnData['division'] = CentroResponsabilidad::division()->lists('nombre_centro_responsabilidad', 'nombre_centro_responsabilidad')->all();
        $returnData['subsecretaria'] = Subsecretaria::active()->lists('nombre_subsecretaria', 'nombre_subsecretaria')->all();

        $busqueda = $this->setBusqueda();
        $returnData['form'] = $this->form;

        $planillaSeguimiento = PlanillaSeguimiento::busqueda($busqueda);
        // Log::info($planillaSeguimiento->count());
        Session::put('busqueda', $busqueda); // para imprimir excel
        $returnData['busqueda'] = $busqueda;

        Log::info(count($busqueda));
        $returnData['planillaSeguimiento'] = $planillaSeguimiento;

        $returnData["graficoCondicion"] = $this->getGraficoCondicion($busqueda);
        $returnData["graficoEstado"] = $this->getGraficoEstado($busqueda);

        $planillaSeguimientoColumnSize = $this->getColumnSize();
        $returnData['planillaSeguimientoColumnSize'] = $planillaSeguimientoColumnSize;

        //
        Log::error(DB::getQueryLog());

        $camposTabla = PlanillaSeguimiento::getTableColumns();
        $returnData['camposTabla'] = $camposTabla;

        $columna = $this->setColumna($camposTabla);
        Session::put('columna', $columna);
        $returnData['columna'] = $columna;

        $returnData['planillaSeguimientoTableSize'] = $this->setTableSize($columna, $planillaSeguimientoColumnSize);

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('planilla_seguimiento.index', $returnData);
    }

    public function downloadMedioVerificacion() {

        $fechaActual = date("d") . "-" . date("m") . "-" . date("Y");
        $zipFileName = "planilla_seguimiento_" . $fechaActual . ".zip";
        $zipper = new \Chumper\Zipper\Zipper;
        $pathMv = base_path() . config('system.folder_mv');

        $busqueda = Session::get('busqueda');
        $planillaSeguimiento = PlanillaSeguimiento::busqueda($busqueda, "id", false);
        $addedFiled = 0;
        foreach ($planillaSeguimiento as $linea) {

            $path = $pathMv . $linea["id"] . '/';
            $pathAdd = glob($path);
            //Log::info($pathAdd);
            if (count($pathAdd) > 0) {
                $addedFiled += count($pathAdd);
                $zipper->make('mv/compromiso/' . $zipFileName)->folder($linea["id"])->add($pathAdd);
                //Log::info($addedFiled);
            }
        }
        $zipper->close();
        $public_dir = public_path() . '/mv';
        $filetopath = $pathMv . '/' . $zipFileName;
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );

        if ($addedFiled > 0) {
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        } else {
            $returnData["mensaje"] = "Ningun archivo encontrado";
            return view::make('planilla_seguimiento.mensaje', $returnData);
        }
    }

    public function excel() {
//http://www.maatwebsite.nl/laravel-excel/docs/export
        $fechaActual = date("d") . "-" . date("m") . "-" . date("Y");
        $filename = "planilla_seguimiento_" . $fechaActual;

        $columna = Session::get('columna');
        $busqueda = Session::get('busqueda');
        $planillaSeguimiento = PlanillaSeguimiento::busqueda($busqueda, null, false);

        $x = 0;
        foreach ($planillaSeguimiento as $linea) {
            $x++;
            foreach ($columna as $rowColumna) {
                $excelData[$x][$rowColumna] = $linea[$rowColumna];
                $excelData[$x] = replace_key_function($excelData[$x], 'ano', 'año');
            }
        }

        //Log::info($excelData);

        Excel::create($filename, function($excel)use($excelData, $fechaActual) {
            $titlePage = "PLANILLA DE SEGUIMIENTO";
            $excel->sheet('Planilla_Seguimiento_' . $fechaActual, function($sheet) use($excelData, $titlePage) {

                //$sheet->fromArray(array('Titlo'));
                //$sheet->fromArray($excelData);
                $sheet->loadView('layouts.excel', array('nombre_hoja' => 'Planilla_Seguimiento', 'titulo' => $titlePage, 'datos' => $excelData));
            });
        })->export('xls');
    }

    public function excel_no() {


        /*
          header("Content-Type: application/xls;
          ");
          header("Content-Disposition: attachment;
          filename = $filename.xls");
          header("Pragma: no-cache");
          header("Expires: 0");
         */
        $excel = "";
        $excel .= "Planilla de Seguimiento " . $fechaActual;
        $excel .= "\n";
        $excel .= "\n";
        $columna = Session::get('columna');
        $planillaSeguimiento = Session::get('planillaSeguimiento');

        $excel .= "<table border = '1'>";
        $excel .= "<tr>";

        foreach ($columna as $rowColumna) {
            $excel .= "<td>";
            $excel .= $rowColumna;
            //$excel .= "\t";
            $excel .= "</td>";
        }
        //$excel .= "\n";
        $excel .= "</tr>";

        foreach ($planillaSeguimiento as $linea) {
            $excel .= "<tr>";
            foreach ($columna as $rowColumna) {
                $excel .= "<td>";
                $excel .= $linea[$rowColumna];
                //$excel .= "\t";
                $excel .= "</td>";
            }
            //$excel .= "\n";
            $excel .= "</tr>";
        }
        $excel .= "</table>";
        //$excel = chr(255) . chr(254) . mb_convert_encoding($excel, 'UTF-16LE', 'UTF-8');
        return $excel;
    }

    public function setBusqueda() {
        $busqueda = array();
        if ($_GET) {

            if (isset($_GET["division"]) && $_GET["division"] != "") {
                $busqueda["division"] = $_GET["division"];
                $this->form->division = $_GET["division"];
            }
            if (isset($_GET["subsecretaria"]) && $_GET["subsecretaria"] != "") {
                $busqueda["subsecretaria"] = $_GET["subsecretaria"];
                $this->form->subsecretaria = $_GET["subsecretaria"];
            }

            if (isset($_GET["condicion"]) && $_GET["condicion"] != "") {
                $busqueda["condicion"] = $_GET["condicion"];
                $this->form->condicion = $_GET["condicion"];
            }
            if (isset($_GET["estado"]) && $_GET["estado"] != "") {
                $busqueda["estado"] = $_GET["estado"];
                $this->form->estado = $_GET["estado"];
            }

            if (isset($_GET["nomenclatura"]) && $_GET["nomenclatura"] != "") {
                $busqueda["nomenclatura"] = $_GET["nomenclatura"];
                $this->form->nomenclatura = $_GET["nomenclatura"];
            }

            if (isset($_GET["plazo_comprometido_inicio"]) && isset($_GET["plazo_comprometido_fin"]) && $_GET["plazo_comprometido_inicio"] != "" && $_GET["plazo_comprometido_fin"] != "") {
                $busqueda["plazo_comprometido"] = $_GET["plazo_comprometido_inicio"] . "|" . $_GET["plazo_comprometido_fin"];
                $this->form->plazo_comprometido_inicio = $_GET["plazo_comprometido_inicio"];
                $this->form->plazo_comprometido_fin = $_GET["plazo_comprometido_fin"];
            }
        }
        return $busqueda;
    }

    public function setTableSize($columna, $planillaSeguimientoColumnSize) {
        $planillaSeguimientoTableSize = 0;
        foreach ($columna as $rowColumna) {
            $planillaSeguimientoTableSize += $planillaSeguimientoColumnSize[$rowColumna];
        }
        return $planillaSeguimientoTableSize + 100;
    }

    public function setColumna($camposTabla) {
        if (isset($_GET["columna"])) {
            $columna = $_GET["columna"];
        } else {
            foreach ($camposTabla as $row) {
                $columna[] = $row->column_name;
            }
        }

        return $columna;
    }

    public function getGraficoEstado($busqueda) {

        $graficoEstado = PlanillaSeguimiento::busqueda($busqueda, 'estado');

        $graficoEstadoArray = "[";
        $i = 1;
        $graficoEstadoArray .= "['Opening Move', 'Percentage'], ";
        foreach ($graficoEstado as $row) {

            $comma = count($graficoEstado) == $i++ ? "" : ", ";
            $graficoEstadoArray .= "['" . $row->estado . "', " . $row->total . "]" . $comma;
        }
        $graficoEstadoArray .= "]";
        return $graficoEstadoArray;
    }

    public function getGraficoCondicion($busqueda) {

        $graficoCondicion = PlanillaSeguimiento::busqueda($busqueda, 'condicion');
        $graficoCondicionArray = "[";
        $i = 1;
        foreach ($graficoCondicion as $row) {

            $comma = count($graficoCondicion) == $i++ ? "" : ", ";
            //Log::error(count($graficoCondicion) . " == " . $i++ . " " . $comma);
            $graficoCondicionArray .= "['" . $row->condicion . "', " . $row->total . "]" . $comma;
        }
        $graficoCondicionArray .= "]";
        return $graficoCondicionArray;
    }

    public function getColumnSize() {

        $arrayColumnSize["id"] = "100";
        $arrayColumnSize["nomenclatura"] = "100";
        $arrayColumnSize["ano"] = "40";
        $arrayColumnSize["subsecretaria"] = "100";
        $arrayColumnSize["division"] = "200";
        $arrayColumnSize["area_auditada"] = "200";
        $arrayColumnSize["numero_informe"] = "100";
        $arrayColumnSize["fecha"] = "130";
        $arrayColumnSize["proceso"] = "200";
        $arrayColumnSize["auditor"] = "200";
        $arrayColumnSize["hallazgo"] = "200";
        $arrayColumnSize["recomendacion"] = "200";
        $arrayColumnSize["responsable"] = "200";
        $arrayColumnSize["criticidad"] = "100";
        $arrayColumnSize["compromiso"] = "200";
        $arrayColumnSize["plazo_estimado"] = "100";
        $arrayColumnSize["plazo_comprometido"] = "100";
        $arrayColumnSize["diferencia_tiempo"] = "100";
        $arrayColumnSize["porcentaje_avance"] = "100";
        $arrayColumnSize["condicion"] = "100";
        $arrayColumnSize["estado"] = "100";
        $arrayColumnSize["descripcion"] = "100";
        $arrayColumnSize["observacion"] = "100";
        $arrayColumnSize["correlativo_interno"] = "50";
        return $arrayColumnSize;
    }

}
