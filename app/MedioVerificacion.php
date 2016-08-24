<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class MedioVerificacion extends Model {

    //
    protected $table = "medio_verificacion";
    protected $primaryKey = "id_medio_verificacion";
    protected $fillable = [
        "id_compromiso"
        , "descripcion"
        , "observacion"
        , "documento_adjunto"
        , "fl_status"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public static function getByIdCompromiso($id_compromiso) {
        $db = DB::table('medio_verificacion')
                ->where('id_compromiso', $id_compromiso);
        return $db;
    }

}
