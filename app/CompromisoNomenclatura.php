<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class CompromisoNomenclatura extends Model {

//
    protected $table = "compromiso_nomenclatura";
    protected $primaryKey = "id_compromiso_nomenclatura";
    protected $fillable = [
        "id_compromiso"
        , "nomenclatura"
    ];
    public $timestamps = false;

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->setCreatedAt($model->freshTimestamp());
        });
    }

}
