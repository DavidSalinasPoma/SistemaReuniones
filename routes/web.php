<?php

use App\Http\Controllers\ReunionesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// End Point REGISTRO DE USUARIO
Route::post('/api/register', [UserController::class, 'register']);

// End Point LOGIN
Route::post('/api/login', [UserController::class, 'login']);


Route::group(['middleware' => ['api.auth']], function () {

    // /*************RUTAS PARA USUARIOS********/
    // Utilizando rutas automatica usuario 
    Route::resource('/api/user', UserController::class);
    Route::delete('/api/user/altausuario/{id}', [UserController::class, 'altaUsuario']);

    // /*************RUTAS PARA USUARIOS********/
    // Utilizando rutas automatica usuario 
    Route::resource('/api/reuniones', ReunionesController::class);
    Route::post('/api/reuniones/buscarReuniones', [ReunionesController::class, 'buscarReuniones']);
    Route::post('/api/reuniones/buscarReunionesFechas', [ReunionesController::class, 'buscarReunionesFechas']);
});
