<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Config extends Model {

    //
    protected $table = "config";
    protected $primaryKey = "id_config";
    protected $fillable = [
        "email_compromiso_atrasado"
        , "dias_alerta_compromiso_atrasado_1"
        , "dias_alerta_compromiso_atrasado_2"
        , "dias_alerta_compromiso_atrasado_3"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

}