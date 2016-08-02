<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\RoleRequest;
Use App\Menu;
use App\Role;
use App\RolePermiso;

class RoleController extends Controller {

    public function __construct() {
        $this->controller = "role";
        $this->title = "Roles";
        $this->subtitle = "Gestion de roles";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Role); // (Usuario::with('email'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_role', 'ID', true)->style("width:80px");
        $grid->add('role', 'role', true);
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_role', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('role.index', $returnData);
    }

    public function create() {

        $role = New Role;

        $roleMenuPermiso = $role->getRoleSubMenuPermiso(null);
        $role = Role::lists('role', 'id_role');

        $auditor = array("1" => "Diego", "2" => "Pepito Perez Sanches");
        $active_directory = array("0" => "No", "1" => "Si");

        $returnData['roleMenuPermiso'] = $roleMenuPermiso;
        $returnData['role'] = $role;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('role.create', $returnData)->withModel($role);
    }

    public function store(RoleRequest $request) {

        $this->validate($request, [
            'role' => 'required',
        ]);

        $role = $request->all();
        $role_new = Role::create($role);
        //Log::info($id_role);
        $id_menuArray = $request->input('id_menu');

        foreach ($id_menuArray as $i) {

            $visualizar = $request->input('visualizar' . $i);
            $agregar = $request->input('agregar' . $i);
            $editar = $request->input('editar' . $i);
            $eliminar = $request->input('eliminar' . $i);

            $lineacheckeada = is_null($visualizar) && is_null($agregar) && is_null($editar) && is_null($eliminar);

            if (!$lineacheckeada) {

                $rolePermiso = New RolePermiso;
                $rolePermiso->id_role = $role_new->id_role;
                $rolePermiso->id_menu = $i;
                $rolePermiso->visualizar = $visualizar;
                $rolePermiso->agregar = $agregar;
                $rolePermiso->editar = $editar;
                $rolePermiso->eliminar = $eliminar;
                Log::info('salvando: ' . $rolePermiso);

                $rolePermiso->save();
                unset($rolePermiso);
            }
        }
        $mensage_success = trans('message.saved.success');
        //return redirect($this->controller);
        return redirect()->route('role.index')
                        ->with('success', $mensage_success);
    }

    public function show($id) {

        return view('role.show', compact('role'));

        $role = Role::find($id);
        $roleMenuPermiso = $role->getRoleSubMenuPermiso(null);

        $returnData['roleMenuPermiso'] = $roleMenuPermiso;
        $returnData['role'] = $role;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('role.show', $returnData);
    }

    public function edit($id) {

        $role = Role::find($id);
        $roleMenuPermiso = $role->getRoleSubMenuPermiso($id);

        Log::info($roleMenuPermiso);
        $returnData['roleMenuPermiso'] = $roleMenuPermiso;
        $returnData['role'] = $role;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('role.edit', $returnData);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'role' => 'required',
        ]);

        $roleUpdate = $request->all();
        $rolePermiso = RolePermiso::where('id_role', '=', $id);
        $rolePermiso->delete();

        $id_menuArray = $request->input('id_menu');

        foreach ($id_menuArray as $i) {

            $visualizar = $request->input('visualizar' . $i);
            $agregar = $request->input('agregar' . $i);
            $editar = $request->input('editar' . $i);
            $eliminar = $request->input('eliminar' . $i);

            $lineacheckeada = is_null($visualizar) && is_null($agregar) && is_null($editar) && is_null($eliminar);

            if (!$lineacheckeada) {

                $rolePermiso = New RolePermiso;
                $rolePermiso->id_role = $id;
                $rolePermiso->id_menu = $i;
                $rolePermiso->visualizar = $visualizar;
                $rolePermiso->agregar = $agregar;
                $rolePermiso->editar = $editar;
                $rolePermiso->eliminar = $eliminar;
                Log::info('salvando: ' . $rolePermiso);

                $rolePermiso->save();
                unset($rolePermiso);
            }
        }

        $role = Role::find($id);
        $role->update($roleUpdate);
        //return redirect($this->controller);

        $mensage_success = trans('message.saved.success');
        return redirect()->route('role.index')
                        ->with('success', $mensage_success);
    }

    public function delete($id) {
        $role = Role::find($id);
        $returnData['role'] = $role;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('role.delete', $returnData);
    }

    public function destroy($id) {
        Role::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_role' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_role/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_role' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    function ajaxRole(Request $request) {

        $id_role = $request->input('id_role');
        //$role = Role::where('id_role','=',$id_role)->get();
        $role = New Role;
        $roleMenuPermiso = $role->getRoleMenuPermiso($id_role);
        return $roleMenuPermiso;
    }

}
