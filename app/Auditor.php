<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Auditor extends Model {

    //
    protected $table = "auditor";
    protected $primaryKey = "id_auditor";
    protected $fillable = [
        "nombre_auditor"
        , "rut_completo"
        , "fono_anexo"
        , "celular"
        , "email"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_auditor', 'ilike', '%' . $value . '%');
    }

    public function equipo_auditor() {
        return $this->belongsToMany('App\EquipoAuditor', 'rel_auditor_equipo', 'id_auditor', 'id_equipo_auditor');
    }

}
