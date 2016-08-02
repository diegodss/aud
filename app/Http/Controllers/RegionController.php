<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Auth;

class RegionController extends Controller
{
	protected $contorller;
	
	public function __construct() {
			
			$this->controller = "region";
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
