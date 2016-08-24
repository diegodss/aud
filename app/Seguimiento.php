<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Seguimiento extends Model {

    //
    protected $table = "seguimiento";
    protected $primaryKey = "id_seguimiento";
    protected $fillable = [
        "diferencia_tiempo"
        , "id_compromiso"
        , "porcentaje_avance"
        , "estado"
        , "condicion"
        , "razon_no_cumplimiento"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('diferencia_tiempo', 'ilike', '%' . $value . '%')
                        ->orWhere('estado', 'ilike', '%' . $value . '%')
        ;
    }

    public function compromiso() {
        return $this->belongsTo('App\Compromiso', 'id_compromiso');
    }

}
