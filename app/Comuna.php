<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Comuna extends Model {

    //
    protected $table = "comuna";
    protected $primaryKey = "id_comuna";
    protected $fillable = [
        "nombre_comuna"
        , "id_region"
        , "cod_comuna_deis"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1)->orderby('nombre_comuna', 'ASC');
        ;
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_comuna', 'ilike', '%' . $value . '%')
                        ->orWhere('cod_comuna_deis', 'ilike', '%' . $value . '%')
        ;
    }

    public function region() {
        return $this->belongsTo('App\Region', 'id_region');
    }

}
