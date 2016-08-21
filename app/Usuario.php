<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Usuario extends Model {

    //
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = ["name", "email", "password", "id_auditor", "id_role", "active_directory", "active_directory_user", "usuario_registra", "usuario_modifica"];

    public function getUsuarioMenuPermiso_not_in_use($id) {


        if (!is_null($id)) {
            // update
            $usuarioMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.nombre_menu', 'usuario_permiso.visualizar', 'usuario_permiso.agregar', 'usuario_permiso.editar', 'usuario_permiso.eliminar')
                    ->leftJoin('usuario_permiso', function($leftJoin)use($id) {
                        $leftJoin->on('menu.id_menu', '=', 'usuario_permiso.id_menu');
                        $leftJoin->where('usuario_permiso.id_usuario', '=', $id);
                    })
                    ->where('menu.id_menu_parent', '=', 0)
                    ->get();
        } else {
            //create
            $usuarioMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.nombre_menu', 'menu.visualizar', 'menu.agregar', 'menu.editar', 'menu.eliminar')
                    ->where('menu.id_menu_parent', '=', 0)
                    ->get();
        }
        return $usuarioMenuPermiso;
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('name', 'like', '%' . $value . '%')
                        ->orWhere('email', 'like', '%' . $value . '%')
        /*
          ->orWhere('body', 'like', '%' . $value . '%')
          ->orWhereHas('author', function ($q) use ($value) {
          $q->whereRaw(" CONCAT(firstname, ' ', lastname) like ?", array("%" . $value . "%"));
          })->orWhereHas('categories', function ($q) use ($value) {
          $q->where('name', 'like', '%' . $value . '%');
          }) */;
    }

    public function getUsuarioMenuPermiso($id) {


        if (!is_null($id)) {
            // update
            $usuarioMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.slug', 'menu.nombre_menu', 'usuario_permiso.visualizar', 'usuario_permiso.agregar', 'usuario_permiso.editar', 'usuario_permiso.eliminar')
                    ->leftJoin('usuario_permiso', function($leftJoin)use($id) {
                        $leftJoin->on('menu.id_menu', '=', 'usuario_permiso.id_menu');
                        $leftJoin->where('usuario_permiso.id_usuario', '=', $id);
                    })
                    ->where('menu.id_menu_parent', '=', 0)
                    ->get();
        } else {
            //create
            $usuarioMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.nombre_menu', 'menu.slug', 'menu.visualizar', 'menu.agregar', 'menu.editar', 'menu.eliminar')
                    ->where('menu.id_menu_parent', '=', 0)
                    ->get();
        }

        //Log::error( $usuarioMenuPermiso);
        //return $usuarioMenuPermiso;

        $menu = array();
        foreach ($usuarioMenuPermiso as $row) {

            $menuItem = new stdClass();
            $menuItem->id_menu = $row->id_menu;
            $menuItem->id_menu_parent = $row->id_menu_parent;
            $menuItem->slug = $row->slug;
            $menuItem->nombre_menu = $row->nombre_menu;

            $menuItem->visualizar = $row->visualizar;
            $menuItem->agregar = $row->agregar;
            $menuItem->editar = $row->editar;
            $menuItem->eliminar = $row->eliminar;
            $id_menu = $row->id_menu;

            $menu[] = $menuItem;

            if (!is_null($id)) {
                // update
                $usersSubMains = DB::table('menu')
                        ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.nombre_menu', 'menu.slug', 'usuario_permiso.visualizar', 'usuario_permiso.agregar', 'usuario_permiso.editar', 'usuario_permiso.eliminar')
                        ->leftJoin('usuario_permiso', function($leftJoin)use($id) {
                            $leftJoin->on('menu.id_menu', '=', 'usuario_permiso.id_menu');
                            $leftJoin->where('usuario_permiso.id_usuario', '=', $id);
                        })
                        ->where('menu.id_menu_parent', '=', $menuItem->id_menu)
                        ->get();
            } else {
                //create
                $usersSubMains = DB::table('menu')
                        ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.nombre_menu', 'menu.slug', 'menu.visualizar', 'menu.agregar', 'menu.editar', 'menu.eliminar')
                        ->where('menu.id_menu_parent', '=', $menuItem->id_menu)
                        ->get();
            }
            //Log::error($usersSubMains);
            foreach ($usersSubMains as $subRow) {
                $submenuItem = new stdClass();
                //$submenuItem->id_menu_parent = $subRow->id_menu_parent;
                $submenuItem->id_menu = $subRow->id_menu;
                $submenuItem->id_menu_parent = $subRow->id_menu_parent;
                $submenuItem->nombre_menu = $subRow->nombre_menu;
                $submenuItem->slug = $subRow->slug;
                $submenuItem->visualizar = $subRow->visualizar;
                $submenuItem->agregar = $subRow->agregar;
                $submenuItem->editar = $subRow->editar;
                $submenuItem->eliminar = $subRow->eliminar;



                $menu[] = $submenuItem;
            }
        }

        return $menu;
    }

}
