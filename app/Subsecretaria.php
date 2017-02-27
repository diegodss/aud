<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Subsecretaria extends Model {

    //
    protected $table = "subsecretaria";
    protected $primaryKey = "id_subsecretaria";
    protected $fillable = [
        "nombre_subsecretaria"
        , "id_ministerio"
        , "rut_completo"
        , "descripcion"
        , "nombre_subsecretario_a"
        , "nombre_contacto"
        , "fono_contacto"
        , "email_contacto"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    /*
      public function __construct() {
      $this->fl_status = 1;
      }
     */

    public function scopeActive($query) {
        return $query->where('fl_status', 1)->orderby('nombre_subsecretaria', 'ASC');
        ;
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_subsecretaria', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_subsecretario_a', 'ilike', '%' . $value . '%')
        ;
    }

    public function ministerio() {
        return $this->belongsTo('App\Ministerio', 'id_ministerio');
    }

    public static function getNombreById($id) {
        $db = DB::table('subsecretaria')
                        ->select('nombre_subsecretaria')
                        ->where('id_subsecretaria', $id)->get();
        $rs = $db[0];
        return $rs->nombre_subsecretaria;
    }

}
