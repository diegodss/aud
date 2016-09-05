<?php


Route::get('/ticketform', 'TicketFormController@form_public');
Route::resource('/ticketform', 'TicketFormController');
Route::get('/datos_establecimiento', 'TicketFormController@datos_establecimiento');

Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

//Route::get('/ticket', 'ticket\TicketController@form_public');


// Registration Routes...
Route::get('register', 'Auth\AuthController@showRegistrationForm');
Route::post('register', 'Auth\AuthController@register');
// Password Reset Routes...


Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');





//--------------------------Fin de  Route::auth(); --------------------------

Route::group(['middleware' => ['auth']], function() {


    Route::resource('/ticket', 'TicketController');
    Route::get('/ticket/delete/{id}', 'TicketController@delete');
    
    Route::resource('/derivacion', 'DerivacionController');
    Route::get('/derivacion/delete/{id}', 'DerivacionController@delete');


    Route::resource('/usuario', 'UsuarioController');
    Route::get('/usuario/delete/{id}', 'UsuarioController@delete');

    Route::resource('/role', 'RoleController');
    Route::get('/role/delete/{id}', 'RoleController@delete');

    Route::get('/role/get/json/', 'RoleController@ajaxRole');

    Route::resource('/menu', 'MenuController');
    Route::get('/menu/delete/{id}', 'MenuController@delete');

    Route::resource('/institucion', 'InstitucionController');
    Route::get('/institucion/delete/{id}', 'InstitucionController@delete');
	
    Route::resource('/establecimiento', 'EstablecimientoController');
    Route::get('/establecimiento/delete/{id}', 'EstablecimientoController@delete');	

    Route::resource('/nnas', 'NnasController');
    Route::get('/nnas/delete/{id}', 'NnasController@delete');		
	
    Route::get('/nna', 'NnaController@index');
    Route::get('nnacreate',  'NnaController@create');
    Route::post('nna/create', ['as' => 'nna.store', 'uses' => 'NnaController@store']);
    Route::get('nna', ['as' => 'nna.index', 'uses' => 'NnaController@index']);

    Route::get('/instituciones', 'InstitucionesController@index');

    Route::get('nna/catastro/{id}', 'CatastroController@index');

    Route::patch('catastro/{id}', ['as' => 'catastro.update', 'uses' => 'CatastroController@update']);
    Route::get('nna/catastro/{id}', ['as' => 'catastro.index', 'uses' => 'CatastroController@index']);
    Route::get('nna/datos_comuna', 'CatastroController@datos_comuna');
    Route::get('nna/datos_sename', 'CatastroController@datos_sename');
    Route::get('nna/datos_trastorno', 'CatastroController@datos_trastorno');
    Route::get('nna/datos_grid_trastornos', 'CatastroController@datos_grid_trastornos');
    Route::get('nna/agrega_trastorno', 'CatastroController@agrega_trastorno');
    Route::get('nna/datos_grid_vacunas', 'CatastroController@datos_grid_vacunas');
    Route::get('nna/datos_provincia', 'CatastroController@datos_provincia');
    Route::get('nna/datos_grid_enfermedadcronicas', 'CatastroController@datos_grid_enfermedadcronicas');
    Route::get('nna/agrega_enfermedadcronica', 'CatastroController@agrega_enfermedadcronica');
    Route::get('nna/datos_grid_naneas', 'CatastroController@datos_grid_naneas');
    Route::get('nna/agrega_naneas', 'CatastroController@agrega_naneas');
    Route::get('nna/datos_establecimiento_salud', 'CatastroController@datos_establecimiento_salud');
    Route::get('nna/datos_hospitales', 'CatastroController@datos_hospitales');
	Route::get('nna/elimina_trastorno', 'CatastroController@elimina_trastorno');
    Route::get('nna/datos_nna', 'NnaController@datos_nna');

  //  Route::get('procesa', ['as' => 'procesa.index', 'uses' => 'ProcesaController@index']);
  //  Route::get('procesa/procesa', ['as' => 'procesa.procesa', 'uses' => 'ProcesaController@datos_nna']);
	

});

Route::get('/', 'TicketFormController@form_public');