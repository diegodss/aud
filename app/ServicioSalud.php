<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class ServicioSalud extends Model {

    //
    protected $table = "servicio_salud";
    protected $primaryKey = "id_servicio_salud";
    protected $fillable = [
        "nombre_servicio"
        , "id_servicio_salud"
        , "id_subsecretaria"
        , "rut_completo"
        , "codigo_servicio_salud"
        , "tipo_servicio_salud"
        , "descripcion"
        , "nombre_director"
        , "fono_director"
        , "email_director"
        , "nombre_contacto"
        , "fono_contacto"
        , "email_contacto"
        , "seremi"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_servicio_salud', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_director', 'ilike', '%' . $value . '%');
    }

    public function scopeServicioSalud($query) {
        return $query->where('fl_status', 1)->where('seremi', 'false');
    }

    public function scopeSeremi($query) {
        return $query->where('fl_status', 1)->where('seremi', 'true');
    }

    public function subsecretaria() {
        return $this->belongsTo('App\Subsecretaria', 'id_subsecretaria');
    }

}
