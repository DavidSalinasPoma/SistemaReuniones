<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;



    // 1.- Indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'roles';


    // Es una relacion de UNO a MUCHOS
    public function usuarios_roles()
    {
        return $this->hasMany('App\Models\UsuarioRol'); // se dirige hacia Usuarios_Roles
    }
}
