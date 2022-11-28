<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SuperAdmin
        $user = new User();
        $user->nombres = "David";
        $user->apellidos = "Salinas Poma";
        $user->usuario = "d@gmail.com";
        $user->password = "96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e";
        $user->estado = 1;
        $user->rol = 1;
        $user->descripcion = "Funcionario del GADC cochabamba";
        $user->save();

        // SuperAdmin
        $user = new User();
        $user->nombres = "Rodrigo";
        $user->apellidos = "Pinto Crispin";
        $user->usuario = "pinto@gmail.com";
        $user->password = "96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e";
        $user->estado = 1;
        $user->rol = 0;
        $user->descripcion = "Funcionario del GADC cochabamba";
        $user->save();
    }
}
