<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Proceso extends Model {

//
    protected $table = "proceso";
    protected $primaryKey = "id_proceso";
    protected $fillable = [
        "nombre_proceso"
        , "descripcion"
        , "responsable_proceso"
        , "fono_responsable_proceso"
        , "email_responsable_proceso"
        , "nombre_contacto"
        , "fono_contacto"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1)->orderby('nombre_proceso', 'ASC');
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_proceso', 'ilike', '%' . $value . '%')
                        ->orWhere('responsable_proceso', 'ilike', '%' . $value . '%')
        ;
    }

    public static function getIdByNombreProceso($value) {
        DB::enableQueryLog();
        $db = DB::table('proceso')
                ->select('id_proceso')
                ->whereRaw("lower(nombre_proceso) = lower('" . trim($value) . "')")
                ->first();
        if (is_object($db)) {
            $id_proceso = $db->id_proceso;
        } else {
            /*
              $db1 = DB::table('proceso')
              ->select('id_proceso')
              ->where('nombre_proceso', 'ilike', '%' . trim($value) . '%')
              ->first();
              if (is_object($db1)) {
              $id_proceso = $db1->id_proceso;
              } else {
              print_r("<span style='color:#ff0033'>OJO ACA: (" . $value . ") </span>");
              $id_proceso = 0;
              } */
            print_r("<span style='color:#ff0033'>OJO ACA: (" . $value . ") </span>");
            $id_proceso = 0;
        }
        Log::error(DB::getQueryLog());
        return $id_proceso;
    }

}
