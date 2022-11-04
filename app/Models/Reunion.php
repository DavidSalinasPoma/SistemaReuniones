<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'reunions';


    // relacion de muchos a uno inversa(muchos a uno)
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'usuarios_id'); // Recibe a users
    }
}
