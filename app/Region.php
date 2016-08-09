<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Region extends Model {

    //
    protected $table = "region";
    protected $primaryKey = "id_region";
    protected $fillable = [
        "nombre_region"
        , "orden_region"
        , "fl_status"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_region', 'ilike', '%' . $value . '%');
    }

}
