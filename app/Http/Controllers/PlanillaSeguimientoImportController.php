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
use App\Proceso;

class PlanillaSeguimientoImportController extends Controller {

    public function __construct() {

        $this->controller = "planilla_seguimiento_import";
        $this->title = "Importación de Planilla de Seguimiento";
        $this->subtitle = "Reporteria";

        //$this->middleware('auth');
        //$this->middleware('admin');
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
        $file = $path . "modelo_para_import_ra_2017.xlsx";
        $file = $path . "modelo_para_import_ssp_2017.xlsx";
        // $file = $path . "modelo_para_import_all_2017.xlsx";
//$file = $path . "modelo_para_import-51.xlsx";

        Excel::load($file, function ($reader) {

//print_r($reader);
            $reader->each(function($sheet) {

                $title = $sheet->getTitle();
                $i = 1;
                foreach ($sheet as $row) {
                    $result = $this->insertPlanillaSeguimientoImport($row);
                    print_r($i++ . ": " . $result->correlativo_interno . " - " . $result->n_informe . "<br>");
                }
            });
        });
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
        $psi->avance = trim((float) $row["avance"] * 100);
        $psi->condicion = trim($row["condicion"]);
        $psi->estado = trim($row["estado"]);
        $psi->medios_de_verificacion = trim($row["medios_de_verificacion"]);
        $psi->observacion = trim($row["observacion"]);
        $psi->proveniente = trim($row["proveniente"]);
        $psi->reprogramado = trim($row["reprogramado"]);
        $psi->save();

        return $psi;
    }

    public function setIdCompromisoPadre() {

        $psi = PlanillaSeguimientoImport::reprogramado()->get();
        //Log::info($psi);




        print_r("<table border='1'>");

        print_r("<tr>");
        print_r("<td>i++ - correlativo_interno</td>");
        print_r("<td>reprogramado</td>");
        print_r("<td>id_compromiso_padre</td>");
        print_r("</tr>");
        $x = 0;
        foreach ($psi as $psiRow) {
            $x++;

            if ($psiRow->subsecretaria == "SSP") {
                $ds_subsecretaria = "Salud Publica";
            } else {
                $ds_subsecretaria = "Redes Asistenciales";
            }

            print_r("<tr>");
            print_r("<td>");
            print_r($x . " - " . $psiRow->correlativo_interno);
            print_r("</td>");
            print_r("<td>");
            print_r($psiRow->reprogramado);
            print_r("</td>");
            print_r("<td>");

            if (strpos($psiRow->reprogramado, ",") === false) {
                $reprogramado = $psiRow->reprogramado;
            } else {
                $reprogramado_array = explode(",", $psiRow->reprogramado);
                foreach ($reprogramado_array as $reprogramado_id) {
                    $reprogramado = trim($reprogramado_id); // con esta logica el ultimo sera el valido.
                }
            }
            $compromiso_actualizar = Compromiso::getIdByCorrelativoInterno($reprogramado, $ds_subsecretaria)->first();
            $compromiso_padre = Compromiso::getIdByCorrelativoInterno($psiRow->correlativo_interno, $ds_subsecretaria)->first();

            if (count($compromiso_actualizar) >= 1) {

                print_r("ID " . $compromiso_actualizar->id_compromiso_padre);
                $compromiso_actualizar->id_compromiso_padre = $compromiso_padre->id_compromiso;
                $compromiso_actualizar->save();
            }
            print_r("</td>");
            print_r("</tr>");
        }
        print_r("</table>");
        return "false";
    }

    public function setIdCompromisoPadreObs() {
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

        //Obtiene las lineas para importar
        $psi = PlanillaSeguimientoImport::getProcesoAuditado()->get();

        ///Log::info(json_decode(json_encode($psi, true)));
        foreach ($psi as $psiRow) {

            if ($psiRow->subsecretaria == "SSP") {
                $ds_subsecretaria = "Salud Publica";
            } else if ($psiRow->subsecretaria == "Ambas") {
                $ds_subsecretaria = "Ambas";
            } else if ($psiRow->subsecretaria == "") {
                $ds_subsecretaria = "test";
            } else {
                $ds_subsecretaria = "Redes Asistenciales";
            }

// -------------- ADD PROCESO AUDITADO ----------------
            $proceso_auditado = new \App\ProcesoAuditado;
            $proceso_auditado->nombre_proceso_auditado = $psiRow->proceso;
            $proceso_auditado->fecha = $psiRow->fecha_informe;
            $proceso_auditado->ano = $psiRow->ano;

            $proceso_auditado->objetivo_auditoria = $psiRow->objetivo_auditoria;
            $proceso_auditado->actividad_auditoria = $psiRow->actividad_auditoria;
            $proceso_auditado->codigo_caigg = $psiRow->codigo_caigg;
            $id_proceso = Proceso::getIdByNombreProceso($psiRow->proceso_transversal);

            $proceso_auditado->id_proceso = $id_proceso;
            $proceso_auditado->tipo_informe = $psiRow->tipo_informe;

            $numero_informe = explode(" ", $psiRow->n_informe);
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



            if (strpos($psiRow->nombre_auditor, ",") === false) {

                $relProcesoAuditor = new RelProcesoAuditor();
                $relProcesoAuditor->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
                $relProcesoAuditor->id_auditor = Auditor::getIdByNombreAuditor(trim($psiRow->nombre_auditor));
                $relProcesoAuditor->jefatura_equipo = true;
                $relProcesoAuditor->usuario_registra = 1;
                $relProcesoAuditor->save();
            } else {


                $auditores = explode(",", $psiRow->nombre_auditor);

                foreach ($auditores as $nombre_au) {

                    $relProcesoAuditor = new RelProcesoAuditor();
                    $relProcesoAuditor->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
                    $relProcesoAuditor->id_auditor = Auditor::getIdByNombreAuditor(trim($nombre_au));
                    $relProcesoAuditor->jefatura_equipo = true;
                    $relProcesoAuditor->usuario_registra = 1;
                    $relProcesoAuditor->save();
                }
            }



//Log::debug($psiRow->nombre_auditor);
//Log::debug($relProcesoAuditor);
        }

// ---------- OBTIENE TODOS LOS REGISTROS INSERTADOS -------------------
        $proceso_auditado = ProcesoAuditado::ProcesoAuditadoAuditor()->get();

        //Log::info(json_decode(json_encode($proceso_auditado, true)));

        $insertados = array();
        $b = 0;
        foreach ($proceso_auditado as $proceso_auditado_row) {


            $numero_informe = $proceso_auditado_row->numero_informe_unidad . " Nº" . $proceso_auditado_row->numero_informe;
            print_r("======================= Count: " . $b++ . ", ID: " . $proceso_auditado_row->id_proceso_auditado . ", " . $numero_informe . " =============================== <br>");



            $busqueda["proceso"] = $proceso_auditado_row->nombre_proceso_auditado;
            $busqueda["fecha_informe"] = $proceso_auditado_row->fecha;
            $busqueda["ano"] = $proceso_auditado_row->ano;

            $subsecretaria = $proceso_auditado_row->getSubsecretaria($proceso_auditado_row->id_proceso_auditado);
            if ($subsecretaria == "Salud Publica") {
                $ds_subsecretaria = "SSP";
            } else {
                $ds_subsecretaria = "SRA";
            }

            $busqueda["nombre_auditor"] = $proceso_auditado_row->nombre_auditor;
            $busqueda["subsecretaria"] = $ds_subsecretaria;

//$busqueda["nomenclatura"] = $proceso_auditado_row->nomenclatura; // quitando reprogramado
            $busqueda["division"] = $proceso_auditado_row->getDivision($proceso_auditado_row->id_proceso_auditado);
            $busqueda["area_auditada"] = $proceso_auditado_row->getAreaAuditada($proceso_auditado_row->id_proceso_auditado);




            $proceso = Proceso::where("id_proceso", $proceso_auditado_row->id_proceso)->first();
            if (is_object($proceso)) {
                $proceso_transversal = $proceso->nombre_proceso;
            } else {
                $proceso_transversal = "";
            }
            $busqueda["proceso_transversal"] = $proceso_transversal;
            $busqueda["objetivo_auditoria"] = $proceso_auditado_row->objetivo_auditoria;
            $busqueda["actividad_auditoria"] = $proceso_auditado_row->actividad_auditoria;
            $busqueda["tipo_informe"] = $proceso_auditado_row->tipo_informe;
            $busqueda["codigo_caigg"] = $proceso_auditado_row->codigo_caigg;


            //print_r($busqueda);
            //
            //
//$busqueda["nombre_auditor"] = nombre_auditor;
            /* referencia */
            //DB::enableQueryLog();
            $psi_g = PlanillaSeguimientoImport::busqueda($busqueda);

//Log::error($busqueda);/* referencia */
            // Log::error(DB::getQueryLog());
            $a = 0;
            foreach ($psi_g as $psi_g_row) {
                $a++;

                $pode_inserir = true;


                if (in_array($psi_g_row->correlativo_interno, $insertados)) {
                    print_r("<BR>****************** REPETIDO: " . $psi_g_row->correlativo_interno . " ****************** <BR>");
                    //$pode_inserir = false;
                }


//Log::debug($psi_g_row);
                print_r(" <br>" . $a . " " . $psi_g_row->n_informe . " = Correlativo: " . $psi_g_row->correlativo_interno);

                if ($pode_inserir) {

                    $hallazgo = new \App\Hallazgo();
                    $hallazgo->id_proceso_auditado = $proceso_auditado_row->id_proceso_auditado;
                    $hallazgo->nombre_hallazgo = $psi_g_row->descripcion_del_hallazgo;
                    $hallazgo->recomendacion = $psi_g_row->descripcion_recomendacion;
                    $hallazgo->criticidad = $psi_g_row->criticidad;
                    $hallazgo->usuario_registra = 1;
                    $hallazgo->save();
//Log::debug($hallazgo);
                    // $psi_g_row->estado != "En Suscripción" && SSP UPDATE
                    //if ($psi_g_row->descripcion_compromiso != "NO SE PRESENTA COMPROMISOS" ) {
                    if ("A" == "A") {


                        $fechaActual = date("d") . "-" . date("m") . "-" . date("Y");



                        if (trim($psi_g_row->plazo_estimado) == "--") {
                            $plazo_estimado = $psi_g_row->plazo_que_compromete_auditado;
                        } else {
                            $plazo_estimado = $psi_g_row->plazo_estimado;
                        }
                        $plazo_comprometido = $psi_g_row->plazo_que_compromete_auditado == "--" ? $psi_g_row->plazo_estimado : $psi_g_row->plazo_que_compromete_auditado;

                        /* Comentado porque no deberia estar vacio
                          if (($plazo_estimado = "--") && ($plazo_comprometido == "--")) {
                          $plazo_estimado = date("d") . "/" . date("m") . "/" . date("Y");
                          $plazo_comprometido = date("d") . "/" . date("m") . "/" . date("Y");
                          }
                         */
                        print_r(" <br> Plazo " . $plazo_estimado);
                        $compromiso = new \App\Compromiso;
                        $compromiso->id_hallazgo = $hallazgo->id_hallazgo;
                        $compromiso->nomenclatura = $psi_g_row->nomenclatura;
                        $compromiso->nombre_compromiso = $psi_g_row->descripcion_compromiso;
                        $compromiso->responsable = $psi_g_row->responsable;
                        $compromiso->plazo_estimado = $plazo_estimado;
                        $compromiso->plazo_comprometido = $plazo_comprometido;
                        $compromiso->correlativo_interno = $psi_g_row->correlativo_interno;
                        $compromiso->usuario_registra = 1;
                        $compromiso->save();
                        $insertados[] = $psi_g_row->correlativo_interno;
//Log::debug($compromiso);

                        $seguimiento = new \App\Seguimiento;
                        $seguimiento->id_compromiso = $compromiso->id_compromiso;
                        $diferencia = $psi_g_row->diferencia;
                        if ($psi_g_row->diferencia == "#VALUE!" || $psi_g_row->diferencia == "") {
                            $diferencia = 0;
                        }
                        $seguimiento->diferencia_tiempo = $diferencia;
                        $seguimiento->porcentaje_avance = $psi_g_row->avance;
                        $seguimiento->condicion = $psi_g_row->condicion;
                        $seguimiento->estado = $psi_g_row->estado;
                        $seguimiento->fecha_ingreso = $proceso_auditado_row->fecha;
                        $seguimiento->usuario_registra = 1;
                        $seguimiento->save();

                        /*
                          $mv = explode(PHP_EOL, $proceso_auditado_row->medios_de_verificacion);
                          foreach ($mv as $mv_unit) {
                          $medio_verificacion = new \App\MedioVerificacion();
                          $medio_verificacion->id_compromiso = $compromiso->id_compromiso;
                          $medio_verificacion->descripcion = $mv_unit;
                          $medio_verificacion->documento_adjunto = $mv_unit;
                          $medio_verificacion->usuario_registra = 1;
                          $medio_verificacion->save();
                          } */
//Log::debug($seguimiento);
                    } else {
                        print_r("<span style='color:#ff0000'>: no agregado </span>");
                    }
                } // pode inserir
            }

            $proceso_auditado_row->cantidad_hallazgo = $a;
            $proceso_auditado_row->save();

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

    public function index(Request $request) {

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('planilla_seguimiento_import.index', $returnData);
    }

}
