<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioPermiso extends Model {

    protected $table = "usuario_permiso";
    protected $primaryKey = "id_usuario_permiso";
    protected $fillable = ["id_usuario", "id_menu", "visualizar", "agregar", "editar", "eliminar"];

}
