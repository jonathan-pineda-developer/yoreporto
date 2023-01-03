<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\C_AuthController;
use App\Http\Controllers\C_UserController;
use App\Http\Controllers\C_ReporteController;
use App\Http\Controllers\C_CategoriaController;

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
//crear reporte y validar jwt
Route::post('crear_reporte', [C_ReporteController::class, 'store'])->middleware('jwt.verify');
//obtener reportes
Route::get('user_reportes', [C_ReporteController::class, 'showByUserId'])->middleware('jwt.verify');
Route::get('reportes', [C_ReporteController::class, 'showAll']);

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
//Route::post('crear_categoria', [C_CategoriaController::class, 'crear_Categoria']);
Route::get('mostrar_categoria', [C_CategoriaController::class, 'mostrar']);
Route::post('crear_categoria', [C_CategoriaController::class, 'store']);
Route::put('actualizar_categoria/{id}', [C_CategoriaController::class, 'update']);
Route::delete('eliminar_categoria/{id}', [C_CategoriaController::class, 'destroy']);


//rutas UTEs
Route::get('/mostrar_usuarios_UTE', [C_UserController::class, 'showAllUTE']);

Route::post('/registro_UTE', [C_AuthController::class, 'registro_UTE']);

//renew
Route::get('renew', [C_AuthController::class, 'renew'])->middleware('jwt.verify');
