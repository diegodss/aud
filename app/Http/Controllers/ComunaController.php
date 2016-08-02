<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ComunaController extends Controller
{
	protected $contorller;
	
	public function __construct() {
			
			$this->controller = "comuna";
			$this->middleware('auth');
			$this->middleware('admin');
	}

	
	public function index() {
		
		$btnActualizar = "";
		if (auth()->user()->can('userAction', $this->controller . '-update')) {
			$btnActualizar = "Actualizar";
		}

		return view( $this->controller . '.index', compact('btnActualizar') );
	}

}
