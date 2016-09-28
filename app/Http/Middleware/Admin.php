<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Session;
use App\Menu;
use App\UsuarioPermiso;
use Log;

class Admin {

    protected $auth;

    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        // saca el primer parametro de la URI
        $uri = $request->path();
        $uri = explode("/", $uri);
        $uri = $uri[0];

        $id_usuario = $this->auth->user()->id;

        $menu = Menu::where('slug', '=', $uri)->first();
        //Log::info($uri);
        $usuarioPermiso = null;
        if ($menu) {
            $usuarioPermiso = UsuarioPermiso::where('id_usuario', '=', $id_usuario)
                    ->where('id_menu', '=', $menu->id_menu)
                    ->first();
            //Log::info($usuarioPermiso);
        }
        if (!$usuarioPermiso) {

            Session::flash('message-error', 'Sin privilegios');
            return redirect()->to('home');
        }

        return $next($request);
    }

}
