<?php

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


// Ruta personalizada USUARIO
Route::post('/api/register', [UserController::class, 'register']);


// /*************RUTAS PARA USUARIOS********/
// Utilizando rutas automatica usuario 
Route::resource('/api/user', UserController::class);
