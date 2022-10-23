<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\C_AuthController;
use App\Http\Controllers\C_UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// rutas de autentificacion
Route::post('registro', [C_AuthController::class, 'registro']);
Route::post('login', [C_AuthController::class, 'login']);

Route::get('/mostrar_usuarios', [C_UserController::class, 'show']);
Route::put('/actualizar/{id}', [C_UserController::class, 'edit']);
Route::put('/inactivar/{id}', [C_UserController::class, 'inactivar']);
// rutas protegidas

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('/', function () {
        return "soy una ruta segura";
    })->name("ruta-segura");
});
