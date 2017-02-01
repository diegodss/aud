<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Task;
use App\User;
use DB;
use Log;
use App\Seguimiento;
use App\Config;

/**
 * Description of CompromisoAlerta
 *
 * @author Diego
 */
class CompromisoAlertaAVencer extends Command { /** * The name and signature of the console command. * * @var string */

    protected $signature = 'compromiso:alerta_a_vencer';
    protected $description = 'Comando para notificar compromisos a vencer';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $config = Config::first();
        /*
          $config->template_compromiso_atrasado;
          $config->email_compromiso_atrasado;
          $config->dias_alerta_compromiso_atrasado_1;
          $config->dias_alerta_compromiso_atrasado_2;
          $config->dias_alerta_compromiso_atrasado_3;
         */
        $total_alerta = array(
            $config->dias_alerta_compromiso_atrasado_1,
            $config->dias_alerta_compromiso_atrasado_2,
            $config->dias_alerta_compromiso_atrasado_3
        );

        foreach ($total_alerta as $dia_alerta) {

            // Log::info("alerta  " . $dia_alerta);

            $alerta_1 = DB::select(" SELECT c.id_compromiso,
                a.nombre_auditor
                , a.email as email_auditor
                ,   (pa.numero_informe_unidad || ' Nº'::text) || pa.numero_informe AS numero_informe
                , pa.fecha
                , h.nombre_hallazgo
                , c.nombre_compromiso
                , c.plazo_comprometido
            FROM
                compromiso c
                INNER JOIN seguimiento s ON s.id_compromiso = c.id_compromiso AND s.fl_status = true
                INNER JOIN hallazgo h ON (h.id_hallazgo=c.id_hallazgo)
                INNER JOIN proceso_auditado pa ON (pa.id_proceso_auditado = h.id_proceso_auditado)
                INNER JOIN rel_proceso_auditor rpa ON (rpa.id_proceso_auditado = pa.id_proceso_auditado)
                INNER JOIN auditor a ON (a.id_auditor = rpa.id_auditor)
            WHERE
                (to_date(c.plazo_comprometido, 'DD/MM/YYYY'::text)- interval '" . $dia_alerta . "' day)::date = now()::date
                AND (s.estado <> ALL (ARRAY['Finalizado'::text, 'Vencido'::text, 'Reprogramado'::text]));");


            foreach ($alerta_1 as $compromiso) {

                //Log::info("compromisos " . $compromiso->numero_informe);

                /**/
                $mensaje = $config->template_compromiso_atrasado;
                $mensaje = str_replace('{nombre_auditor}', $compromiso->nombre_auditor, $mensaje);
                $mensaje = str_replace('{plazo_comprometido}', $compromiso->plazo_comprometido, $mensaje);
                $mensaje = str_replace('{numero_informe}', $compromiso->numero_informe, $mensaje);
                $mensaje = str_replace('{fecha}', $compromiso->fecha, $mensaje);
                $mensaje = str_replace('{hallazgo}', $compromiso->nombre_hallazgo, $mensaje);
                $mensaje = str_replace('{compromiso}', $compromiso->nombre_compromiso, $mensaje);

                Log::info($mensaje);

                $data["nombre_auditor"] = $compromiso->nombre_auditor;
                $data["plazo_comprometido"] = $compromiso->plazo_comprometido;
                $data["numero_informe"] = $compromiso->numero_informe;
                $data["fecha"] = $compromiso->fecha;
                $data["nombre_hallazgo"] = $compromiso->nombre_hallazgo;
                $data["nombre_compromiso"] = $compromiso->nombre_compromiso;

                $data["mensaje"] = $mensaje;
                $email_auditor = $compromiso->email_auditor;
                $nombre_auditor = $compromiso->nombre_auditor;
                $email_compromiso_atrasado = $config->email_compromiso_atrasado;
                $asunto = $config->asunto_compromiso_atrasado;
                $asunto = str_replace('{dias}', $dia_alerta, $asunto);
                //Mail::raw('email.compromiso_alerta_auditor', $data,
                //Mail::raw($mensaje, function ($message)use($email_auditor, $nombre_auditor, $email_compromiso_atrasado, $asunto) {
                Mail::send('email.compromiso_alerta_auditor', $data, function ($message)use($email_auditor, $nombre_auditor, $email_compromiso_atrasado, $asunto) {
                    $message->to($email_auditor, $nombre_auditor)
                            ->cc($email_compromiso_atrasado)
                            ->subject($asunto);
                });
            }
        }

        // Log::info("compromisos actualizados");
    }

}
