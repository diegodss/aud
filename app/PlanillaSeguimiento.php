<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class PlanillaSeguimiento extends Model {

    //
    protected $table = "vw_planilla_seguimiento";
    protected $primaryKey = "id_proceso_auditado";

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nomenclatura', 'ilike', '%' . $value . '%')
                        ->orWhere('ano', '=', $value)
                        ->orWhere('area_auditada', 'ilike', '%' . $value . '%');
    }

}
