<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UsuarioRequest;
Use App\Menu;
use App\Usuario;
use App\UsuarioPermiso;
use App\Role;
use App\RolePermiso;
use App\Auditor;

class UsuarioController extends Controller {

    public function __construct() {

        $this->controller = "usuario";
        $this->title = "Usuarios";
        $this->subtitle = "Gestion de usuarios";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }


        $filter = \DataFilter::source(Usuario::with('role'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id', 'ID', true)->style("width:80px");
        $grid->add('name', 'Usuario', true);
        $grid->add('email', 'E-mail', true);
        $grid->add('role.role', 'Perfil', true);
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Usuario";
        $returnData['controller'] = $this->controller;

        return View::make('usuario.index', $returnData);
    }

    public function create() {

        $usuario = New Usuario;
        $returnData['usuario'] = $usuario;

        $usuarioMenuPermiso = $usuario->getUsuarioMenuPermiso(null);
        $role = Role::lists('role', 'id_role');

        $auditor = Auditor::active()->lists('nombre_auditor', 'id_auditor')->all();
        $returnData['auditor'] = $auditor;

        $active_directory = array("0" => "No", "1" => "Si");

        $returnData['usuarioMenuPermiso'] = $usuarioMenuPermiso;
        $returnData['role'] = $role;
        $returnData['active_directory'] = $active_directory;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Create Usuario";
        return View::make('usuario.create', $returnData)->withModel($usuario);
    }

    public function store(UsuarioRequest $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $usuario = array(
            "name" => $request['name']
            , "email" => $request['email']
            , "password" => bcrypt($request['password'])
            , "id_role" => $request['id_role']
            , "id_auditor" => $request['id_auditor']
            , "active_directory" => $request['active_directory']
            , "active_directory_user" => $request['active_directory_user']
            , "tipo_acceso" => $request['tipo_acceso']
            , "usuario_registra" => $request['usuario_registra']
            , "usuario_modifica" => $request['usuario_modifica']
        );

        $usuario_new = Usuario::create($usuario);

        $this->usuarioPermiso($usuario_new->id, $request);

        $mensage_success = trans('message.saved.success');
        return redirect()->route('usuario.edit', $usuario_new->id)
                        ->with('message', $mensage_success)
                        ->with('controller', $this->controller);
    }

    public function show($id) {

        $usuario = Usuario::find($id);
        $usuarioMenuPermiso = $usuario->getUsuarioMenuPermiso(null);
        $role = Role::lists('role', 'id_role');

        $auditor = Auditor::active()->lists('nombre_auditor', 'id_auditor')->all();
        $returnData['auditor'] = $auditor;

        $active_directory = array("0" => "No", "1" => "Si");

        $returnData['usuario'] = $usuario;
        $returnData['usuarioMenuPermiso'] = $usuarioMenuPermiso;
        $returnData['role'] = $role;
        $returnData['active_directory'] = $active_directory;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Usuario";
        return View::make('usuario.show', $returnData);
    }

    public function edit($id) {

        $usuario = Usuario::find($id);
        $usuarioMenuPermiso = $usuario->getUsuarioMenuPermiso($id);
        $role = Role::lists('role', 'id_role');

        $auditor = Auditor::active()->lists('nombre_auditor', 'id_auditor')->all();
        $returnData['auditor'] = $auditor;

        $active_directory = array("0" => "No", "1" => "Si");

        $returnData['usuario'] = $usuario;
        $returnData['usuarioMenuPermiso'] = $usuarioMenuPermiso;
        $returnData['role'] = $role;
        $returnData['active_directory'] = $active_directory;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Usuario";

        return View::make('usuario.edit', $returnData);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);

        $usuarioUpdate = array(
            "name" => $request['name']
            , "email" => $request['email']
            , "id_role" => $request['id_role']
            , "id_auditor" => $request['id_auditor']
            , "active_directory" => $request['active_directory']
            , "active_directory_user" => $request['active_directory_user']
            , "tipo_acceso" => $request['tipo_acceso']
            , "usuario_registra" => $request['usuario_registra']
            , "usuario_modifica" => $request['usuario_modifica']
        );

        if ($request['password'] != "") {
            $usuarioUpdate["password"] = bcrypt($request['password']);
        }

        $usuarioUpdate["fl_status"] = $request->exists('fl_status') ? true : false;

        $usuarioPermiso = UsuarioPermiso::where('id_usuario', '=', $id);
        $usuarioPermiso->delete();

        $this->usuarioPermiso($id, $request);

        $usuario = Usuario::find($id);
        $usuario->update($usuarioUpdate);

        $mensage_success = trans('message.saved.success');
        return redirect()->route('usuario.edit', $id)
                        ->with('message', $mensage_success)
                        ->with('controller', $this->controller);
    }

    public function usuarioPermiso($id, $request) {

        foreach ($request->input('id_menu') as $i) {

            $visualizar = $request->input('visualizar' . $i);
            $agregar = $request->input('agregar' . $i);
            $editar = $request->input('editar' . $i);
            $eliminar = $request->input('eliminar' . $i);

            $lineacheckeada = is_null($visualizar) && is_null($agregar) && is_null($editar) && is_null($eliminar);

            if (!$lineacheckeada) {

                $usuarioPermiso = New UsuarioPermiso;
                $usuarioPermiso->id_usuario = $id;
                $usuarioPermiso->id_menu = $i;
                $usuarioPermiso->visualizar = $visualizar;
                $usuarioPermiso->agregar = $agregar;
                $usuarioPermiso->editar = $editar;
                $usuarioPermiso->eliminar = $eliminar;

                $usuarioPermiso->save();
                unset($usuarioPermiso);
            }
        }
    }

    public function delete($id) {

        $usuario = Usuario::find($id);

        $returnData['usuario'] = $usuario;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Usuario";
        return View::make('usuario.delete', $returnData);
    }

    public function destroy($id) {
        Usuario::find($id)->delete();
        return redirect($this->controller);
    }

    public function tipoAccesoCustomizado($id_role) {

        $usuarios = Usuario::where('tipo_acceso', 'Customizado')->where('id_role', $id_role);

        $grid = "";
        if ($usuarios->count() > 0) {

            $alerta = "<br><div class='alert alert-warning'>";
            $alerta .= "    <h4><i class='icon fa fa-warning'></i> Atención</h4>";
            $alerta .= "    Los seguientes usuarios tienen acceso customizado con base en este role y seran reseteados:";
            $alerta .= "</div>";

            $grid = \DataGrid::source($usuarios);
            $grid->add('id', 'ID')->style("width:40px");
            $grid->add('name', 'Usuario');
            $grid->add('email', 'E-mail');

            $input = '<input class="form-control_none" id="saltar_customizados" name="saltar_customizados" type="checkbox" value="1">';
            $input .= " No actualizar los usuarios con perfiles customziados. ";


            $grid = $alerta . " " . $grid . " " . $input;
        }
        return $grid;
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
