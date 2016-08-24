<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Compromiso extends Model {

    //
    protected $table = "compromiso";
    protected $primaryKey = "id_compromiso";
    protected $fillable = [
        "id_hallazgo"
        , "nombre_compromiso"
        , "plazo_comprometido"
        , "plazo_estimado"
        , "responsable"
        , "fono_responsable"
        , "email_responsable"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_compromiso', 'ilike', '%' . $value . '%')
                        ->orWhere('responsable', 'ilike', '%' . $value . '%')
        ;
    }

    public function hallazgo() {
        return $this->belongsTo('App\Hallazgo', 'id_hallazgo');
    }

}
