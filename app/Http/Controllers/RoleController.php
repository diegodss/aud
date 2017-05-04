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
use App\User;
use App\Usuario;
use App\UsuarioPermiso;

class RoleController extends Controller {

    public function __construct() {
        $this->controller = "role";
        $this->title = "Perfiles";
        $this->subtitle = "Gestion de perfiles";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Role);
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
        $returnData['role'] = $role;

        $roleMenuPermiso = $role->getRoleSubMenuPermiso(null);
        $returnData['roleMenuPermiso'] = $roleMenuPermiso;

        $returnData["action"] = "create";
        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Crear Role";

        return View::make('role.create', $returnData)->withModel($role);
    }

    public function store(RoleRequest $request) {

        $this->validate($request, [
            'role' => 'required',
        ]);

        $role = $request->all();
        $role_new = Role::create($role);

        $this->rolePermiso($role_new->id_role, $request);

        $mensage_success = trans('message.saved.success');

        return redirect()->route('role.edit', $role_new->id_role)
                        ->with('message', $mensage_success)
                        ->with('controller', $this->controller);
    }

    public function show($id) {

        $role = Role::find($id);
        $returnData['role'] = $role;

        $roleMenuPermiso = $role->getRoleSubMenuPermiso(null);
        $returnData['roleMenuPermiso'] = $roleMenuPermiso;

        $returnData["action"] = "show";
        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Role";
        return View::make('role.show', $returnData);
    }

    public function edit($id) {


        $role = Role::find($id);
        $returnData['role'] = $role;

        $roleMenuPermiso = $role->getRoleSubMenuPermiso($id);
        $returnData['roleMenuPermiso'] = $roleMenuPermiso;

        $returnData["action"] = "edit";
        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Role";

        return View::make('role.edit', $returnData);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'role' => 'required',
        ]);

        $roleUpdate = $request->all();
        $rolePermiso = RolePermiso::where('id_role', '=', $id);
        $rolePermiso->delete();

        $this->rolePermiso($id, $request);

        $role = Role::find($id);
        $role->update($roleUpdate);

        if ($request->aplicar_role_usuario == "1") {
            $saltar_customizados = isset($request->saltar_customizados) ? $request->saltar_customizados : "0";
            $this->actualizaUsuarios($id, $saltar_customizados);
        }

        $mensage_success = trans('message.saved.success');
        return redirect()->route('role.edit', $id)
                        ->with('message', $mensage_success)
                        ->with('controller', $this->controller);
    }

    public function actualizaUsuarios($id_role, $saltar_customizados) {

        $role_permiso = RolePermiso::where("id_role", $id_role);
        if ($saltar_customizados == "1") {
            $usuarios = User::where("id_role", $id_role)->where("tipo_acceso", "Role")->get();
        } else {
            $usuarios = User::where("id_role", $id_role)->get();
        }

        foreach ($usuarios as $ususario) {

            $usuario_permiso = UsuarioPermiso::where("id_usuario", $ususario->id);
            $usuario_permiso->delete();

            foreach ($role_permiso->get() as $rp) {
                $usuarioPermiso = New UsuarioPermiso;
                $usuarioPermiso->id_usuario = $ususario->id;
                $usuarioPermiso->id_menu = $rp->id_menu;
                $usuarioPermiso->visualizar = $rp->visualizar;
                $usuarioPermiso->agregar = $rp->agregar;
                $usuarioPermiso->editar = $rp->editar;
                $usuarioPermiso->eliminar = $rp->eliminar;

                $usuarioPermiso->save();
                unset($usuarioPermiso);
            }

            $user = Usuario::find($ususario->id);
            $user->tipo_acceso = "Role";
            $user->save();
        }
    }

    public function rolePermiso($id, $request) {

        foreach ($request->input('id_menu') as $i) {

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
                $rolePermiso->save();
                unset($rolePermiso);
            }
        }
    }

    public function delete($id) {
        $role = Role::find($id);
        $returnData['role'] = $role;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Role";
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
        $role = New Role;
        $roleMenuPermiso = $role->getRoleMenuPermiso($id_role);
        return $roleMenuPermiso;
    }

}
