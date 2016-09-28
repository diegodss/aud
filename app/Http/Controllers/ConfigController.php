<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Config;

class ConfigController extends Controller {

    public function __construct() {

        $this->controller = "config";
        $this->title = "Configuraciones";
        $this->subtitle = "del sistema";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        return $this->edit(1, false);
        //return View::make('config.index', array());
    }

    public function create() {

        return View::make('config.create', $returnData);
    }

    public function store(Request $request) {

    }

    public function show($id) {

        $config = Config::find($id);

        $returnData['config'] = $config;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Config";
        return View::make('config.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $config = Config::find($id);

        $returnData['config'] = $config;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Configuraciones";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('config.edit', $returnData);
        } else {
            return View::make('config.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'email_compromiso_atrasado' => 'required',
            'dias_alerta_compromiso_atrasado_1' => 'required',
        ]);

        $configUpdate = $request->all();
        $configUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $config = Config::find($id);
        $config->update($configUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        return View::make('config.delete', $returnData);
    }

    public function destroy($id) {

    }

}
