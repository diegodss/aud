<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role_permiso extends Model
{
    protected $table = "rolepermiso";
	protected $primaryKey = "id_role_permiso";
	protected $fillable = ["id_role","id_menu","visualizar","agregar","editar","eliminar"];
}
