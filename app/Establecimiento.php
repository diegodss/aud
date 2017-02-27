<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Establecimiento extends Model {

    //
    protected $table = "establecimiento";
    protected $primaryKey = "id_establecimiento";
    protected $fillable = [
        "nombre_establecimiento"
        , "id_servicio_salud"
        , "id_comuna"
        , "codigo_establecimiento"
        , "tipo_establecimiento"
        , "descripcion"
        , "nombre_director"
        , "fono_director"
        , "email_director"
        , "nombre_contacto"
        , "fono_contacto"
        , "email_contacto"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1)->orderby('nombre_establecimiento', 'ASC');
        ;
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_establecimiento', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_director', 'ilike', '%' . $value . '%')
        ;
    }

    public function servicio_salud() {
        return $this->belongsTo('App\ServicioSalud', 'id_servicio_salud');
    }

}
