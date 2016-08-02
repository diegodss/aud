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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/region', 'RegionController@index');
Route::get('/comuna', 'ComunaController@index');
Route::get('/ministerio', 'MinisterioController@index');

Route::resource('/usuario', 'UsuarioController');
Route::get('/usuario/delete/{id}', 'UsuarioController@delete');

Route::resource('/role', 'RoleController');
Route::get('/role/delete/{id}', 'RoleController@delete');

Route::get('/role/get/json/', 'RoleController@ajaxRole');

Route::resource('/ministerio', 'MinisterioController');
Route::get('/ministerio/delete/{id}', 'MinisterioController@delete');

Route::resource('/organismo', 'OrganismoController');
Route::get('/organismo/delete/{id}', 'OrganismoController@delete');
