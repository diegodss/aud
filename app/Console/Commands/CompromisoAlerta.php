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

/**
 * Description of CompromisoAlerta
 *
 * @author Diego
 */
class CompromisoAlerta extends Command { /** * The name and signature of the console command. * * @var string */

    protected $signature = 'compromiso:alerta';
    protected $description = 'Comando para actualizar y notificar compromisos vencidos';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $vencidos = DB::table('vw_compromiso_atrasado')->get();

        foreach ($vencidos as $compromiso) {

            $seguimientoAnterior = Seguimiento::find($compromiso->id_seguimiento);
            $seguimientoAnterior->update(["fl_status" => false]);

            $seguimiento = new Seguimiento();
            $seguimiento->id_compromiso = $compromiso->id_compromiso;
            $seguimiento->diferencia_tiempo = 0;
            $seguimiento->estado = "Vencido";
            $seguimiento->condicion = $compromiso->condicion;
            $seguimiento->porcentaje_avance = $compromiso->porcentaje_avance;
            $seguimiento->save();

            $data["auditor"] = "Pedro henrique";

            Mail::send('email.compromiso_alerta_auditor', $data, function ($message) {
                $message->to('diegodss@gmail.com', 'example_name')->subject('Welcome!');
            });
        }

        Log::info("compromisos actualizados");
        /*

          select updated_at from compromiso
          where
          to_date("plazo_comprometido" , 'DD/MM/YYYY') >= to_date('21-09-2016' , 'DD/MM/YYYY')

          $schedule->command('email:send --force')
          ->everyMinute()
          ->sendOutputTo('Hello')
          ->emailOutputTo('testing@gmail.com');
          $plazo_comprometido_inicio = $dt[0];
          $plazo_comprometido_fin = $dt[1];

          $query->whereRaw("to_date(\"plazo_comprometido\" , 'DD/MM/YYYY') >= to_date('" . $plazo_comprometido_inicio . "' , 'DD/MM/YYYY')  ");
          $query->whereRaw("to_date(\"plazo_comprometido\" , 'DD/MM/YYYY') <= to_date('" . $plazo_comprometido_fin . "' , 'DD/MM/YYYY')  ");

          Reglas de negocio programadas:
         *
          Se compromiso.plazo_comprometido > data actual + 1dia
          Enviar correo para admin del sistema
          Enviar correo para auditado
          Grabar nuevo seguimiento vencido
         *

         *
         *
         *          */
    }

}
