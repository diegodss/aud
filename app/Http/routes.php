<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

// ----------------------- Route::auth(); ----------------------------
// vendor\laravel\framework\src\Illuminate\Routing
// Authentication Routes...
$this->get('login', 'Auth\AuthController@showLoginForm');
$this->post('login', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');

// Registration Routes...
//$this->get('register', 'Auth\AuthController@showRegistrationForm');
//$this->post('register', 'Auth\AuthController@register');
// Password Reset Routes...

$this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\PasswordController@reset');

//--------------------------Fin de  Route::auth(); --------------------------

Route::group(['middleware' => ['auth']], function() {

    Route::get('/home', 'HomeController@index');
    Route::get('/', 'HomeController@index');

    Route::resource('/usuario', 'UsuarioController');
    Route::get('/usuario/delete/{id}', 'UsuarioController@delete');

    Route::resource('/role', 'RoleController');
    Route::get('/role/delete/{id}', 'RoleController@delete');

    Route::get('/role/get/json/', 'RoleController@ajaxRole');

    Route::resource('/menu', 'MenuController');
    Route::get('/menu/delete/{id}', 'MenuController@delete');

    Route::resource('/ministerio', 'MinisterioController');
    Route::get('/ministerio/delete/{id}', 'MinisterioController@delete');

    Route::resource('/organismo', 'OrganismoController');
    Route::get('/organismo/delete/{id}', 'OrganismoController@delete');

    Route::resource('/subsecretaria', 'SubsecretariaController');
    Route::get('/subsecretaria/delete/{id}', 'SubsecretariaController@delete');

    Route::resource('/centro_responsabilidad', 'CentroResponsabilidadController');
    Route::get('/centro_responsabilidad/delete/{id}', 'CentroResponsabilidadController@delete');
//Route::get('/gabinete/{tipo}', 'CentroResponsabilidadController@index');

    Route::resource('/departamento', 'DepartamentoController');
    Route::get('/departamento/delete/{id}', 'DepartamentoController@delete');

    Route::resource('/servicio_salud', 'ServicioSaludController');
    Route::get('/servicio_salud/delete/{id}', 'ServicioSaludController@delete');

    Route::resource('/servicio_clinico', 'ServicioClinicoController');
    Route::get('/servicio_clinico/delete/{id}', 'ServicioClinicoController@delete');

    Route::resource('/establecimiento', 'EstablecimientoController');
    Route::get('/establecimiento/delete/{id}', 'EstablecimientoController@delete');

    Route::resource('/subsecretaria', 'SubsecretariaController');
    Route::get('/subsecretaria/delete/{id}', 'SubsecretariaController@delete');

    Route::resource('/comuna', 'ComunaController');
    Route::get('/comuna/delete/{id}', 'ComunaController@delete');

    Route::resource('/region', 'RegionController');
    Route::get('/region/delete/{id}', 'RegionController@delete');

    Route::resource('/unidad', 'UnidadController');
    Route::get('/unidad/delete/{id}', 'UnidadController@delete');

    Route::resource('/auditor', 'AuditorController');
    Route::get('/auditor/delete/{id}', 'AuditorController@delete');

    Route::resource('/proceso', 'ProcesoController');
    Route::get('/proceso/delete/{id}', 'ProcesoController@delete');
});
