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
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_proceso', 'ilike', '%' . $value . '%')
                        ->orWhere('responsable_proceso', 'ilike', '%' . $value . '%')
        ;
    }

}
