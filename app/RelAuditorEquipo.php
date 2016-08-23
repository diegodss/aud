<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class RelAuditorEquipo extends Model {

    //
    protected $table = "rel_auditor_equipo";
    protected $primaryKey = "id_rel_auditor_equipo";
    protected $fillable = [
        "id_equipo_auditor"
        , "id_auditor"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

}
