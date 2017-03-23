<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Hallazgo extends Model {

    //
    protected $table = "hallazgo";
    protected $primaryKey = "id_hallazgo";
    protected $fillable = [
        "nombre_hallazgo"
        , "id_proceso_auditado"
        , "recomendacion"
        , "criticidad"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1)->orderby('nombre_hallazgo', 'ASC');
        ;
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_hallazgo', 'ilike', '%' . $value . '%')
                        ->orWhere('recomendacion', 'ilike', '%' . $value . '%')
        ;
    }

    public function proceso_auditado() {
        return $this->belongsTo('App\ProcesoAuditado', 'id_proceso_auditado');
    }

    public static function getByIdProcesoAuditado($id_proceso_auditado) {
        $db = DB::table('hallazgo')
                ->select('hallazgo.id_hallazgo', 'nombre_hallazgo', 'recomendacion', 'criticidad', 'estado', 'c.id_compromiso', 'c.id_compromiso_padre')
                ->selectRaw('(SELECT count(*) FROM compromiso WHERE id_compromiso=c.id_compromiso_padre) as cantidad_reprogramado')
                ->leftJoin('compromiso as c', 'c.id_hallazgo', '=', 'hallazgo.id_hallazgo')
                ->leftJoin('seguimiento as s', function($q) {
                    $q->on('c.id_compromiso', '=', 's.id_compromiso')
                    ->on('s.fl_status', '=', \DB::raw("true"));
                })
                ->where('id_proceso_auditado', $id_proceso_auditado);
        return $db;
    }

    /**/

    public static function getByIdProcesoAuditadoNovo($id_proceso_auditado, $id_compromiso = null) {
        \DB::enableQueryLog();
//'nombre_hallazgo', 'recomendacion', 'criticidad',
        $db = DB::table('hallazgo')
                ->select('hallazgo.id_hallazgo', 'estado', 'c.id_compromiso', 'c.id_compromiso_padre')
                ->selectRaw('(SELECT count(*) FROM compromiso WHERE id_compromiso=c.id_compromiso_padre) as cantidad_reprogramado')
                ->leftJoin('compromiso as c', 'c.id_hallazgo', '=', 'hallazgo.id_hallazgo')
                ->leftJoin('seguimiento as s', function($q) {
            $q->on('c.id_compromiso', '=', 's.id_compromiso');
            $q->on('s.fl_status', '=', \DB::raw("true"));
        });
        $db = $db->where('c.id_compromiso_padre', $id_compromiso);
        if ($id_compromiso == 0 or is_null($id_compromiso)) {
            $db = $db->where('id_proceso_auditado', $id_proceso_auditado);
        }


        //Log::error(\DB::getQueryLog());
        //Log::error("Vamos Con: " . $id_compromiso_padre);

        return $db;
    }

    public static function getByIdCompromiso($id_compromiso) {
        \DB::enableQueryLog();

        $db = DB::table('hallazgo')
                ->select('hallazgo.id_hallazgo', 'nombre_hallazgo', 'recomendacion', 'criticidad', 'estado', 'c.id_compromiso', 'c.id_compromiso_padre')
                ->selectRaw('(SELECT count(*) FROM compromiso WHERE id_compromiso=c.id_compromiso_padre) as cantidad_reprogramado')
                ->leftJoin('compromiso as c', 'c.id_hallazgo', '=', 'hallazgo.id_hallazgo')
                ->leftJoin('seguimiento as s', function($q) {
            $q->on('c.id_compromiso', '=', 's.id_compromiso');
            $q->on('s.fl_status', '=', \DB::raw("true"));
        });
        $db = $db->where('c.id_compromiso', $id_compromiso);

        //Log::error(\DB::getQueryLog());
        //Log::error("Vamos Con: " . $id_compromiso_padre);

        return $db;
    }

    public static function getCantidadHallazgoDb($id_proceso_auditado) {
        $db = DB::table('hallazgo')
                ->selectRaw('count(*) as cuanditad_hallazgo_db')
                ->where('id_proceso_auditado', $id_proceso_auditado)
                ->first();
        return $db->cuanditad_hallazgo_db;
    }

    public function scopeProcesoAuditado($query) {
        return $query->join('proceso_auditado AS pa', 'pa.id_proceso_auditado', '=', 'hallazgo.id_proceso_auditado');
    }

}
