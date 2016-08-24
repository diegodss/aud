<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class RelProcesoAuditor extends Model {

    //
    protected $table = "rel_proceso_auditor";
    protected $primaryKey = "id_rel_proceso_auditor";
    protected $fillable = [
        "id_proceso_auditado"
        , "id_auditor"
        , "jefatura_equipo"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

}
