<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Menu;
use App\Role;
use App\RolePermiso;
use App\User;
use App\UsuarioPermiso;

class MenuController extends Controller {

    public function __construct() {

        $this->controller = "menu";
        $this->title = "Menus";
        $this->subtitle = "Gestion de menus";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Menu); // (Menu::with('nombre_menu'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_menu', 'ID', true)->style("width:80px");
        $grid->add('nombre_menu', 'Menu', true);
        $grid->add('id_menu_parent', 'Menu Principal')->cell(function( $value, $row) {
            return $row->id_menu_parent == 0 ? "Si" : "No";
        });

        $grid->row(function ($row) {
            if ($row->cell('id_menu_parent')->value == "Si") {
                $row->style("background-color: #eee");
            }
        });

        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_menu', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('menu.index', $returnData);
    }

    public function create() {

        $menu = new Menu;
        $returnData['menu'] = $menu;

        $menu_parent = Menu::parent()->lists('nombre_menu', 'id_menu')->all();
        $returnData['menu_parent'] = $menu_parent;

        $returnData["roles"] = Role::lists('role', 'id_role');

        $select_si_no = array("0" => "No", "1" => "Si");
        $returnData['select_si_no'] = $select_si_no;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Menu";

        return View::make('menu.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_menu' => 'required',
            'id_menu_parent' => 'required',
            'item_menu' => 'required',
            'slug' => 'required',
            'visualizar' => 'required',
            'agregar' => 'required',
            'editar' => 'required',
            'eliminar' => 'required'
        ]);

        $menu = $request->all();
        $menu["order"] = $request->order == "" ? 0 : $request->order;
        $menu["fl_status"] = $request->exists('fl_status') ? true : false;
        $menu_new = Menu::create($menu);

        $role_request = $request->id_role;
        if (is_array($role_request)) {
            foreach ($role_request as $id_role) {

                $rolePermiso = New RolePermiso;
                $rolePermiso->id_role = $id_role;
                $rolePermiso->id_menu = $menu_new->id_menu;
                $rolePermiso->visualizar = $menu_new->visualizar;
                $rolePermiso->agregar = $menu_new->agregar;
                $rolePermiso->editar = $menu_new->editar;
                $rolePermiso->eliminar = $menu_new->eliminar;
                $rolePermiso->save();

                $usuarios = User::where("id_role", $id_role)->get();
                //Log::debug(json_decode(json_encode($usuarios, true)));
                foreach ($usuarios as $ususario) {

                    $usuarioPermiso = New UsuarioPermiso;
                    $usuarioPermiso->id_usuario = $ususario->id;
                    $usuarioPermiso->id_menu = $menu_new->id_menu;
                    $usuarioPermiso->visualizar = $menu_new->visualizar;
                    $usuarioPermiso->agregar = $menu_new->agregar;
                    $usuarioPermiso->editar = $menu_new->editar;
                    $usuarioPermiso->eliminar = $menu_new->eliminar;
                    $usuarioPermiso->save();
                }
            }
        }




        $mensage_success = trans('message.saved.success');

        if ($menu["modal"] == "sim") {
            return $menu_new; //redirect()->route('menu.index')
        } else {
            return redirect()->route('menu.edit', $menu_new->id_menu)
                            ->with('message', $mensage_success)
                            ->with('controller', $this->controller);
        }
    }

    public function show($id) {

        $menu = Menu::find($id);
        $returnData['menu'] = $menu;

        $menu_parent = Menu::parent()->lists('nombre_menu', 'id_menu')->all();
        $returnData['menu_parent'] = $menu_parent;

        $select_si_no = array("0" => "No", "1" => "Si");
        $returnData['select_si_no'] = $select_si_no;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Menu";
        return View::make('menu.show', $returnData);
    }

    public function edit($id) {

        $menu = Menu::find($id);
        $returnData['menu'] = $menu;

        $menu_parent = Menu::parent()->lists('nombre_menu', 'id_menu')->all();
        $returnData['menu_parent'] = $menu_parent;

        $select_si_no = array("0" => "No", "1" => "Si");
        $returnData['select_si_no'] = $select_si_no;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Menu";

        return View::make('menu.edit', $returnData);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_menu' => 'required',
            'id_menu_parent' => 'required',
            'slug' => 'required'
        ]);

        $menuUpdate = $request->all();
        $menuUpdate["order"] = $request->order == "" ? 0 : $request->order;
        $menuUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $menu = Menu::find($id);
        $menu->update($menuUpdate);

        $mensage_success = trans('message.saved.success');

        return redirect()->route('menu.edit', $id)
                        ->with('message', $mensage_success)
                        ->with('controller', $this->controller);
    }

    public function delete($id) {

        $menu = Menu::find($id);

        $returnData['menu'] = $menu;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Menu";
        return View::make('menu.delete', $returnData);
    }

    public function destroy($id) {
        Menu::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_menu' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_menu/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_menu' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
