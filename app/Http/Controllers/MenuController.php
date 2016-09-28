<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Menu;

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
        $grid->add('id_menu_parent', 'Menu Padre', true);
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
            'slug' => 'required'
        ]);

        $menu = $request->all();
        $menu["fl_status"] = $request->exists('fl_status') ? true : false;
        $menu_new = Menu::create($menu);

        $mensage_success = trans('message.saved.success');

        if ($menu["modal"] == "sim") {
            Log::info($menu);
            return $menu_new; //redirect()->route('menu.index')
        } else {/*
          return redirect()->route('menu.index')
          ->with('success', $mensage_success); */
            return $this->edit($menu_new->id_menu, true);
        }
        //
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

    public function edit($id, $show_success_message = false) {

        $menu = Menu::find($id);
        $returnData['menu'] = $menu;

        $menu_parent = Menu::parent()->lists('nombre_menu', 'id_menu')->all();
        $returnData['menu_parent'] = $menu_parent;

        $select_si_no = array("0" => "No", "1" => "Si");
        $returnData['select_si_no'] = $select_si_no;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Menu";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('menu.edit', $returnData);
        } else {
            return View::make('menu.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_menu' => 'required',
            'id_menu_parent' => 'required',
            'slug' => 'required'
        ]);

        $menuUpdate = $request->all();
        $menuUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $menu = Menu::find($id);
        $menu->update($menuUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('menu.index')
          ->with('success', $mensage_success); */
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
