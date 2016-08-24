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

    public static function getAuditorById($id_equipo_auditor) {
        $db = DB::table('equipo_auditor AS ea')
                ->join('rel_auditor_equipo AS rae', 'rae.id_equipo_auditor', '=', 'ea.id_equipo_auditor')
                ->join('auditor AS a', 'a.id_auditor', '=', 'rae.id_auditor')
                ->select('a.id_auditor', 'a.nombre_auditor')
                ->where('ea.id_equipo_auditor', $id_equipo_auditor);
        return $db;
    }

}
