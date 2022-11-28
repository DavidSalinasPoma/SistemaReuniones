<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'apellidos',
        'usuario',
        'password',
        'estado',
        'descripcion',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Es una relacion de UNO a MUCHOS
    public function usuario_role()
    {
        return $this->hasMany('App\Models\UsuariosRoles'); // se dirige hacia Usuarios_Roles
    }

    // Es una relacion de UNO a MUCHOS
    public function reunion()
    {
        return $this->hasMany('App\Models\Reunion'); // se dirige hacia Reuniones
    }
}
