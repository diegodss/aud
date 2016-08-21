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
/*
  Route::get("/auditoria_prototipo", function() {
  ob_start();
  require("/auditoria_prototipo/1.php");
  return ob_get_clean();
  });
  / */
// ----------------------- Route::auth(); ----------------------------
// vendor\laravel\framework\src\Illuminate\Routing
// Authentication Routes...
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

// Registration Routes...
Route::get('register', 'Auth\AuthController@showRegistrationForm');
Route::post('register', 'Auth\AuthController@register');
// Password Reset Routes...


Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');

Route::resource('/proceso_auditado', 'ProcesoAuditadoController');
Route::get('/proceso_auditado/delete/{id}', 'ProcesoAuditadoController@delete');
Route::get('/proceso_auditado/busqueda/{id}', 'ProcesoAuditadoController@busqueda'); // http://localhost/auditoria/public/proceso_auditado/busqueda/filtro
Route::post('/proceso_auditado/confirmar/{id}', 'ProcesoAuditadoController@confirmar');
Route::post('/proceso_auditado/form/{id}', 'ProcesoAuditadoController@form');
//Route::post('/form-handler', 'formController@formHandle');
Route::get('/proceso_auditado/filtro/', 'ProcesoAuditadoController@filtro');



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
    Route::get('/organismo/get/json/', 'OrganismoController@ajaxOrganismo');

    Route::resource('/subsecretaria', 'SubsecretariaController');
    Route::get('/subsecretaria/delete/{id}', 'SubsecretariaController@delete');
    Route::get('/subsecretaria/get/json/', 'SubsecretariaController@ajaxSubsecretaria');

    Route::resource('/centro_responsabilidad', 'CentroResponsabilidadController');
    Route::get('/centro_responsabilidad/delete/{id}', 'CentroResponsabilidadController@delete');
    Route::get('/centro_responsabilidad/get/json/', 'CentroResponsabilidadController@ajaxCentroResponsabilidad');
//Route::get('/gabinete/{tipo}', 'CentroResponsabilidadController@index');

    Route::resource('/departamento', 'DepartamentoController');
    Route::get('/departamento/delete/{id}', 'DepartamentoController@delete');
    Route::get('/departamento/get/json/', 'DepartamentoController@ajaxDepartamento');

    Route::resource('/servicio_salud', 'ServicioSaludController');
    Route::get('/servicio_salud/delete/{id}', 'ServicioSaludController@delete');

    Route::resource('/establecimiento', 'EstablecimientoController');
    Route::get('/establecimiento/delete/{id}', 'EstablecimientoController@delete');
    Route::get('/establecimiento/get/json/', 'EstablecimientoController@ajaxEstablecimiento');

    Route::resource('/subsecretaria', 'SubsecretariaController');
    Route::get('/subsecretaria/delete/{id}', 'SubsecretariaController@delete');

    Route::resource('/comuna', 'ComunaController');
    Route::get('/comuna/delete/{id}', 'ComunaController@delete');
    Route::get('/comuna/get/json/', 'ComunaController@ajaxComuna');

    Route::resource('/region', 'RegionController');
    Route::get('/region/delete/{id}', 'RegionController@delete');

    Route::resource('/unidad', 'UnidadController');
    Route::get('/unidad/delete/{id}', 'UnidadController@delete');
    Route::get('/unidad/get/json/', 'UnidadController@ajaxUnidad');

    Route::resource('/auditor', 'AuditorController');
    Route::get('/auditor/delete/{id}', 'AuditorController@delete');

    Route::resource('/proceso', 'ProcesoController');
    Route::get('/proceso/delete/{id}', 'ProcesoController@delete');
});
