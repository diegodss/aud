<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Menu extends Model {

    //
    protected $table = "menu";
    protected $primaryKey = "id_menu";
    protected $fillable = [
        "nombre_menu"
		,"id_menu_parent"
        , "order"
        , "item_menu"
        , "link"
        , "slug"
        , "visualizar"
        , "agregar"
        , "editar"
        , "eliminar"		
       // , "usuario_registra"
       // , "usuario_modifica"
    ];

    public function scopeParent($query) {
        return $query->where('id_menu_parent', 0);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_menu', 'ilike', '%' . $value . '%')
                        ->orWhere('item_menu', 'ilike', '%' . $value . '%')
        ;
    }


}
