<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Ministerio extends Model {

    //
    protected $table = "ministerio";
    protected $primaryKey = "id_ministerio";
    protected $fillable = [
        "nombre_ministerio"
        , "descripcion"
        , "nombre_ministro"
        , "nombre_contacto"
        , "fono_contacto"
        , "email_contacto"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_ministerio', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_ministro', 'ilike', '%' . $value . '%')
        ;
    }

    public static function getNombreById($id) {
        $db = DB::table('ministerio')
                ->select('nombre_ministerio')
                ->where('id_ministerio', $id)
                ->get();
        $rs = $db[0];
        return $rs->nombre_ministerio;
    }

}
