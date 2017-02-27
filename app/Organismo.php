<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Organismo extends Model {

    //
    protected $table = "organismo";
    protected $primaryKey = "id_organismo";
    protected $fillable = ["id_ministerio", "nombre_organismo", "descripcion", "fl_status", "usuario_registra", "usuario_modifica"];

    public function scopeActive($query) {
        return $query->where('fl_status', true)->orderby('nombre_organismo', 'ASC');
        ;
    }

    public function ministerio() {
        return $this->belongsTo('App\Ministerio', 'id_ministerio');
    }

}
