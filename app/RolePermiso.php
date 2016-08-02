<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermiso extends Model {

    protected $table = "role_permiso";
    protected $primaryKey = "id_role_permiso";
    protected $fillable = ["id_role", "id_menu", "visualizar", "agregar", "editar", "eliminar"];

}
