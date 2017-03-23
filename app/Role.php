<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Role extends Model {

    protected $table = "role";
    protected $primaryKey = "id_role";
    protected $fillable = ["role"];

    public function getRoleMenuPermiso($id) {

        if (!is_null($id)) {
            // update
            $roleMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.nombre_menu', 'role_permiso.visualizar', 'role_permiso.agregar', 'role_permiso.editar', 'role_permiso.eliminar')
                    ->leftJoin('role_permiso', function($leftJoin)use($id) {
                        $leftJoin->on('menu.id_menu', '=', 'role_permiso.id_menu');
                        $leftJoin->where('role_permiso.id_role', '=', $id);
                    })
                    ->orderBy('order', 'asc')
                    ->get();
        } else {
            //create
            $roleMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.nombre_menu', 'menu.visualizar', 'menu.agregar', 'menu.editar', 'menu.eliminar')
                    ->orderBy('order', 'asc')
                    ->get();
        }
        return $roleMenuPermiso;
    }

    public function getRoleSubMenuPermiso($id) {


        if (!is_null($id)) {
            // update
            $roleMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.slug', 'menu.nombre_menu', 'role_permiso.visualizar', 'role_permiso.agregar', 'role_permiso.editar', 'role_permiso.eliminar')
                    ->leftJoin('role_permiso', function($leftJoin)use($id) {
                        $leftJoin->on('menu.id_menu', '=', 'role_permiso.id_menu');
                        $leftJoin->where('role_permiso.id_role', '=', $id);
                    })
                    ->where('menu.id_menu_parent', '=', 0)
                    ->orderBy('order', 'asc')
                    ->get();
        } else {
            //create
            $roleMenuPermiso = DB::table('menu')
                    ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.nombre_menu', 'menu.slug', 'menu.visualizar', 'menu.agregar', 'menu.editar', 'menu.eliminar')
                    ->where('menu.id_menu_parent', '=', 0)
                    ->orderBy('order', 'asc')
                    ->get();
        }

        $menu = array();
        foreach ($roleMenuPermiso as $row) {

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
                        ->select('menu.id_menu', 'menu.id_menu_parent', 'menu.nombre_menu', 'menu.slug', 'role_permiso.visualizar', 'role_permiso.agregar', 'role_permiso.editar', 'role_permiso.eliminar')
                        ->leftJoin('role_permiso', function($leftJoin)use($id) {
                            $leftJoin->on('menu.id_menu', '=', 'role_permiso.id_menu');
                            $leftJoin->where('role_permiso.id_role', '=', $id);
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
            
            foreach ($usersSubMains as $subRow) {
                $submenuItem = new stdClass();               
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
