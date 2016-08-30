<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class CentroResponsabilidad extends Model {

    //
    protected $table = "centro_responsabilidad";
    protected $primaryKey = "id_centro_responsabilidad";
    protected $fillable = [
        "nombre_centro_responsabilidad"
        , "id_subsecretaria"
        , "tipo"
        , "fono_jefatura"
        , "descripcion"
        , "nombre_jefatura"
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
        return $query->where('fl_status', 1);
    }

    public function scopeGabinete($query) {
        return $query->where('fl_status', 1)->where('tipo', 'gabinete');
    }

    public function scopeSeremi($query) {
        return $query->where('fl_status', 1)->where('tipo', 'seremi');
    }

    public function scopeDivision($query) {
        return $query->where('fl_status', 1)->where('tipo', 'division');
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_centro_responsabilidad', 'ilike', '%' . $value . '%')
                        ->orWhere('tipo', 'ilike', '%' . $value . '%');
    }

    public function subsecretaria() {
        return $this->belongsTo('App\Ministerio', 'id_subsecretaria');
    }

    public static function getNombreById($id) {
        $db = DB::table('centro_responsabilidad')
                        ->select('nombre_centro_responsabilidad')
                        ->where('id_centro_responsabilidad', $id)->get();
        $rs = $db[0];
        return $rs->nombre_centro_responsabilidad;
    }

}
