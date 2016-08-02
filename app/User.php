<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\usuario_permiso;
use Log;
use DB;
use \stdClass;

class User extends Authenticatable {
    /**
     * The attributes that are mass assignable.
     *
     * @var array

      protected $fillable = [
      'name', 'email', 'password',
      ];
     */

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getMenuAcceso() {

        $usersMains = DB::table('usuario_permiso')
                ->join('menu', function ($join) {
                    $join->on('usuario_permiso.id_menu', '=', 'menu.id_menu')
                    ->where('usuario_permiso.id_usuario', '=', $this->id)
                    ->where('usuario_permiso.visualizar', '=', 1)
                    ->where('menu.id_menu_parent', '=', 0)
                    ->where('menu.item_menu', '=', 1);
                })
                ->orderBy('order', 'asc')
                ->get();



        return $usersMains;
        /*
          $menu = array();
          foreach ($usersMains as $row) {

          $menuItem = new stdClass();
          $menuItem->id_menu = $row->id_menu;
          $menuItem->id_menu_parent = $Row->id_menu_parent;
          $menuItem->slug = $row->slug;
          $menuItem->menu = $row->menu;

          $id_menu = $row->id_menu;

          $menu[] = $menuItem;
          $usersSubMains = DB::table('usuario_permiso')
          ->join('menu', function ($join)use($id_menu) {
          $join->on('usuario_permiso.id_menu', '=', 'menu.id_menu')
          ->where('usuario_permiso.id_usuario', '=', $this->id)
          ->where('usuario_permiso.visualizar', '=', 1)
          ->where('menu.id_menu_parent', '=', $id_menu)
          ->where('menu.item_menu', '=', 1);
          })
          ->get();
          foreach ($usersSubMains as $subRow) {
          $submenuItem = new stdClass();
          $submenuItem->id_menu_parent = $subRow->id_menu_parent;
          $submenuItem->id_menu = $subRow->id_menu;
          $submenuItem->slug = $subRow->slug;
          $submenuItem->menu = $subRow->menu;

          $menu[] = $submenuItem;
          }
          // Log::error( $menu);
          }

          return $menu; */
    }

    public function getSubMenuAcceso($id_menu) {

        $usersMains = DB::table('usuario_permiso')
                ->join('menu', function ($join)use($id_menu) {
                    $join->on('usuario_permiso.id_menu', '=', 'menu.id_menu')
                    ->where('usuario_permiso.id_usuario', '=', $this->id)
                    ->where('usuario_permiso.visualizar', '=', 1)
                    ->where('menu.id_menu_parent', '=', $id_menu)
                    ->where('menu.item_menu', '=', 1);
                })
                ->orderBy('order', 'asc')
                ->get();

        return $usersMains;
    }

    function permiso($moduloAction) {


        $id_usuario = $this->id;
//		$usuarioPermiso = UsuarioPermiso::where('id_usuario','=', $id_usuario)->get();
        $usuarioPermiso = DB::table('vw_usuario_permiso')->where('id_usuario', '=', $id_usuario)->get();

        /*
          [2016-07-08 19:19:07] local.ERROR: [{"id_usuarioPermiso":"1","id_usuario":1,"id_menu":1,"agregar":1,"editar":1,"eliminar":1},{"id_usuarioPermiso":"2","id_usuario":1,"id_menu":2,"agregar":1,"editar":1,"eliminar":1},{"id_usuarioPermiso":"3","id_usuario":1,"id_menu":3,"agregar":1,"editar":1,"eliminar":1}]

         */


        //Log::error($usuarioPermiso);

        $moduloAction = explode("-", $moduloAction);
        $modulo = $moduloAction[0];
        $action = $moduloAction[1];

        $permisoAntiga = array(
            "region" => array(
                "create" => 1,
                "update" => 1,
                "delete" => 1
            ),
            "comuna" => array(
                "create" => 1,
                "update" => 1,
                "delete" => 1
            ),
            "ministerio" => array(
                "create" => 1,
                "update" => 1,
                "delete" => 1
            )
        );

        $permiso = array();
        foreach ($usuarioPermiso as $up) {
            $permiso[$up->slug] = array(
                "index" => $up->visualizar,
                "create" => $up->agregar,
                "update" => $up->editar,
                "delete" => $up->eliminar,
                "store" => $up->agregar,
                "show" => 1,
                "edit" => $up->editar,
                "destroy" => $up->eliminar
            );
        }



        $checkModulo = $permiso[$modulo][$action];
        return $checkModulo;
    }

}
