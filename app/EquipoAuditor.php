<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class EquipoAuditor extends Model {

    //
    protected $table = "equipo_auditor";
    protected $primaryKey = "id_equipo_auditor";
    protected $fillable = [
        "nombre_equipo_auditor"
        , "fl_status"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_equipo_auditor', 'ilike', '%' . $value . '%');
    }

    public function auditor() {
        return $this->belongsToMany('App\Auditor', 'rel_auditor_equipo', 'id_equipo_auditor', 'id_auditor');
        //return $this->belongsToMany('App\Auditor', 'rel_auditor_equipo');
    }

}
