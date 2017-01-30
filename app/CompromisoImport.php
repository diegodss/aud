<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class CompromisoImport extends Model {

    //
    protected $table = "compromiso_import";
    protected $primaryKey = "compromiso_import";
    protected $fillable = [
        "descripcion"
        , "observacion"
        , "documento_adjunto"
        , "total"
        , "tipo_import"
        , "fl_status"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

}
