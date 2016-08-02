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

        $filter = \DataFilter::source(new \App\Usuario); // (Usuario::with('email'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id', 'ID', true)->style("width:80px");
        $grid->add('name', 'Usuario', true);
        $grid->add('email', 'E-mail', true);
        /*
          $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
          return $row->fl_status ? "Sí" : "No";
          }); */
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id', 'asc');
        $grid->paginate($itemsPage);
        /* $grid->row(function ($row) {
          if ($row->cell('fl_status')->value == "No") {
          $row->style("color:#cccccc");
          }
          });
         */
        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('usuario.index', $returnData);
    }

    public function create() {

        $usuario = New Usuario;
        $usuarioMenuPermiso = $usuario->getUsuarioMenuPermiso(null);
        $role = Role::lists('role', 'id_role');

        $auditor = array("1" => "Diego", "2" => "Pepito Perez Sanches");
        $active_directory = array("0" => "No", "1" => "Si");

        $returnData['usuarioMenuPermiso'] = $usuarioMenuPermiso;
        $returnData['role'] = $role;
        $returnData['auditor'] = $auditor;
        $returnData['active_directory'] = $active_directory;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('usuario.create', $returnData)->withModel($usuario);
    }

    public function store(UsuarioRequest $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        //$usuario = $request->all();

        $usuario = array(
            "name" => $request['name']
            , "email" => $request['email']
            , "password" => bcrypt($request['password'])
            , "id_auditor" => $request['id_auditor']
            , "id_role" => $request['id_role']
            , "active_directory" => $request['active_directory']
            , "active_directory_user" => $request['active_directory_user']
            , "usuario_registra" => $request['usuario_registra']
            , "usuario_modifica" => $request['usuario_modifica']
        );

        Log::info($usuario);
        $usuario_new = Usuario::create($usuario);

        $id_menuArray = $request->input('id_menu');

        foreach ($id_menuArray as $i) {

            $visualizar = $request->input('visualizar' . $i);
            $agregar = $request->input('agregar' . $i);
            $editar = $request->input('editar' . $i);
            $eliminar = $request->input('eliminar' . $i);

            $lineacheckeada = is_null($visualizar) && is_null($agregar) && is_null($editar) && is_null($eliminar);

            if (!$lineacheckeada) {

                $usuarioPermiso = New UsuarioPermiso;
                $usuarioPermiso->id_usuario = $usuario_new->id;
                $usuarioPermiso->id_menu = $i;
                $usuarioPermiso->visualizar = $visualizar;
                $usuarioPermiso->agregar = $agregar;
                $usuarioPermiso->editar = $editar;
                $usuarioPermiso->eliminar = $eliminar;
                Log::info('salvando: ' . $usuarioPermiso);

                $usuarioPermiso->save();
                unset($usuarioPermiso);
            }
        }

        $mensage_success = trans('message.saved.success');
//return redirect($this->controller);
        return redirect()->route('usuario.index')
                        ->with('success', $mensage_success);
    }

    public function show($id) {

        $usuario = Usuario::find($id);
        $usuarioMenuPermiso = $usuario->getUsuarioMenuPermiso(null);
        $role = Role::lists('role', 'id_role');

        $auditor = array("1" => "Diego", "2" => "Pepito Perez Sanches");
        $active_directory = array("0" => "No", "1" => "Si");

        $returnData['usuario'] = $usuario;
        $returnData['usuarioMenuPermiso'] = $usuarioMenuPermiso;
        $returnData['role'] = $role;
        $returnData['auditor'] = $auditor;
        $returnData['active_directory'] = $active_directory;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('usuario.show', $returnData);
    }

    public function edit($id) {

        $usuario = Usuario::find($id);
        $usuarioMenuPermiso = $usuario->getUsuarioMenuPermiso($id);
        $role = Role::lists('role', 'id_role');

        $auditor = array("1" => "Diego", "2" => "Pepito Perez Sanches");
        $active_directory = array("0" => "No", "1" => "Si");

        Log::info('user: ' . $usuario);

        $returnData['usuario'] = $usuario;
        $returnData['usuarioMenuPermiso'] = $usuarioMenuPermiso;
        $returnData['role'] = $role;
        $returnData['auditor'] = $auditor;
        $returnData['active_directory'] = $active_directory;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('usuario.edit', $returnData);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);

        //$usuarioUpdate = $request->all();

        $usuarioUpdate = array(
            "name" => $request['name']
            , "email" => $request['email']
            , "password" => bcrypt($request['password'])
            , "id_auditor" => $request['id_auditor']
            , "id_role" => $request['id_role']
            , "active_directory" => $request['active_directory']
            , "active_directory_user" => $request['active_directory_user']
            , "usuario_registra" => $request['usuario_registra']
            , "usuario_modifica" => $request['usuario_modifica']
        );

        $organismoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;

        $usuarioPermiso = UsuarioPermiso::where('id_usuario', '=', $id);
        $usuarioPermiso->delete();

        $id_menuArray = $request->input('id_menu');

        foreach ($id_menuArray as $i) {

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
                Log::info('salvando: ' . $usuarioPermiso);

                $usuarioPermiso->save();
                unset($usuarioPermiso);
            }
        }

        $usuario = Usuario::find($id);
        $usuario->update($usuarioUpdate);
//return redirect($this->controller);

        $mensage_success = trans('message.saved.success');
        return redirect()->route('usuario.index')
                        ->with('success', $mensage_success);
    }

    public function delete($id) {

        $usuario = Usuario::find($id);
//return view('usuario.delete',compact('usuario', 'title', 'subtitle'));

        $returnData['usuario'] = $usuario;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('usuario.delete', $returnData);
    }

    public function destroy($id) {
        Usuario::find($id)->delete();
        return redirect($this->controller);
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
