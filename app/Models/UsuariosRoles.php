<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosRoles extends Model
{
    use HasFactory;

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'usuarios_roles';

    // relacion de muchos a uno inversa(muchos a uno)
    public function user()
    {
        return $this->belongsTo('App\Models\Users', 'usuarios_id'); // Recibe a usuarios
    }
    // relacion de muchos a uno inversa(muchos a uno)
    public function rol()
    {
        return $this->belongsTo('App\Models\Roles', 'roles_id'); // Recibe a Roles
    }
}
