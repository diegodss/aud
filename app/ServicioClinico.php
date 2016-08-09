<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class ServicioClinico extends Model {

    //
    protected $table = "servicio_clinico";
    protected $primaryKey = "id_servicio_clinico";
    protected $fillable = [
        "nombre_servicio_clinico"
        , "id_establecimiento"
        , "descripcion"
        , "nombre_jefatura_servicio"
        , "fono_jefatura"
        , "email_jefatura"
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
        return $query->where('nombre_servicio_clinico', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_jefatura_servicio', 'ilike', '%' . $value . '%')
        ;
    }

    public function centro_responsabilidad() {
        return $this->belongsTo('App\CentroResponsabilidad', 'id_centro_responsabilidad');
    }
    public function establecimiento() {
        return $this->belongsTo('App\Establecimiento', 'id_establecimiento');
    }	

}
