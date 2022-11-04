<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\C_AuthController;
use App\Http\Controllers\C_UserController;
use App\Http\Controllers\C_ReporteController;

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

//rutas del reporte
Route::post('crear_reporte', [C_ReporteController::class, 'store']);

Route::get('/mostrar_usuarios', [C_UserController::class, 'show']);
Route::get('/mostrar_usuario/{id}', [C_UserController::class, 'showById']);
Route::put('/actualizar/{id}', [C_UserController::class, 'edit']);
Route::put('/inactivar/{id}', [C_UserController::class, 'inactivar']);
// ruta para login de google
Route::post('google', [C_AuthController::class, 'googleSignIn']);
// rutas protegidas

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('/', function () {
        return "soy una ruta segura";
    })->name("ruta-segura");
});

//rutas categoria
Route::get('/categorias', 'App\Http\Controllers\C_CategoriaController@index');
Route::post('/categorias', 'App\Http\Controllers\C_CategoriaController@store');
Route::put('/categorias/{id}', 'App\Http\Controllers\C_CategoriaController@update');
Route::put('/categorias/{id}', 'App\Http\Controllers\C_CategoriaController@destroy');
