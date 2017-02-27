<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Unidad extends Model {

    //
    protected $table = "unidad";
    protected $primaryKey = "id_unidad";
    protected $fillable = [
        "nombre_unidad"
        , "id_departamento"
        , "descripcion"
        , "nombre_jefatura_unidad"
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
        return $query->where('fl_status', 1)->orderby('nombre_unidad', 'ASC');
        ;
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_unidad', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_jefatura_unidad', 'ilike', '%' . $value . '%')
        ;
    }

    public function departamento() {
        return $this->belongsTo('App\Departamento', 'id_departamento');
    }

}
