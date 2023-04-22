<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\C_AuthController;
use App\Http\Controllers\C_UserController;
use App\Http\Controllers\C_ReporteController;
use App\Http\Controllers\C_CategoriaController;
use App\Http\Controllers\C_AdministradorController;

// drive
use App\Providers\DriveServiceProvider;

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
Route::post('verificar_codigo', [C_AuthController::class, 'verificarCodigoDobleFactor']);
Route::post('reenviar_codigo/{id}', [C_AuthController::class, 'reenviarCodigoDobleFactor']);
Route::post('olvido_contraseña', [C_AuthController::class, 'solicitudRecuperacionContrasenia']);
Route::put('cambiar_contrasenia/{id}', [C_AuthController::class, 'cambiarContrasenia']);
//olvidoContrasenia
Route::put('olvido_contrasenia/{id}', [C_AuthController::class, 'olvidoContrasenia'])->middleware('jwt.verify');
//cambiarContraseniaOlvido
Route::put('cambiar_contrasenia_olvido/{id}', [C_AuthController::class, 'cambiarContraseniaOlvido']);
//reenviarCodigoDobleFactorEmail
Route::post('reenviar_codigo_email', [C_AuthController::class, 'reenviarCodigoDobleFactorEmail']);

//rutas del reporte
//crear reporte y validar jwt
Route::post('crear_reporte', [C_ReporteController::class, 'store'])->middleware('jwt.verify');
//actualizar imagen del reporte
Route::post('actualizar_imagen/reportes/{id}', [C_ReporteController::class, 'updateImagenReporte'])->middleware('jwt.verify');
//obtener reportes
Route::get('user_reportes', [C_ReporteController::class, 'showByUserId'])->middleware('jwt.verify');
Route::get('reportes', [C_ReporteController::class, 'showAll']);
Route::get('mostrar_reportes', [C_ReporteController::class, 'showAllReportes']);
Route::get('aceptadosFinalidasos', [C_ReporteController::class, 'showReportesAceptadosoFinalizados']);
Route::get('reportes_estado', [C_ReporteController::class, 'showReportesByEstado']);
Route::get('/reportes/getImagenById/{id}', [C_ReporteController::class, 'getImagenReportesById']);
//delete all
Route::delete('deleteAll', [C_ReporteController::class, 'deleteAll']);
//metodo upload
Route::post('upload', [C_ReporteController::class, 'upload']);

//obtener reportes por id
Route::get('showById/{id}', [C_ReporteController::class, 'showById']);


Route::get('/mostrar_usuarios', [C_UserController::class, 'show']);
Route::get('/mostrar_usuario/{id}', [C_UserController::class, 'showById']);
//mostrar utes activos
Route::get('/mostrar_ute_activos', [C_UserController::class, 'showAllUTEActivos']);
Route::post('/actualizar/{id}', [C_UserController::class, 'update'])->middleware('jwt.verify');
Route::post('/actualizar_imagen/usuarios/{id}', [C_UserController::class, 'updateImagen'])->middleware('jwt.verify');
Route::get('/usuarios/getImagenById/{id}', [C_UserController::class, 'getImagenById']);
//agregar ruta de traer imagen de reporte

Route::post('/actualizar_datos/{id}', [C_UserController::class, 'updateDatos'])->middleware('jwt.verify');
Route::put('/inactivar/{id}', [C_UserController::class, 'inactivar']);
Route::put('/actualizar_categoria_reporte/{id}', [C_ReporteController::class, 'updateCategoria'])->middleware('jwt.verify');
Route::put('/aceptar_reporte/{id}', [C_ReporteController::class, 'aceptarReporte'])->middleware('jwt.verify');
Route::put('/rechazar_reporte/{id}', [C_ReporteController::class, 'rechazarReporte'])->middleware('jwt.verify');

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
Route::get('/mostrar_reportes_UTE', [C_ReporteController::class, 'showByUTEId'])->middleware('jwt.verify');

//renew
Route::get('renew', [C_AuthController::class, 'renew'])->middleware('jwt.verify');

//rutas para el admin

Route::get('/mostrar_estadisticas/{mes?}/{anio?}', [C_AdministradorController::class, 'estadistica']);
Route::get('/mostrar_bitacora', [C_AdministradorController::class, 'mostrarBitacora']);


// drive test 

Route::post('/drive', function (Request $request) {

    // Obtenemos el archivo que se subió
    $file = $request->file('imagen')->getRealPath();

    // Generamos un nombre único para el archivo
    $filename = uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension();

    // Escribimos el archivo en Google Drive
    Storage::disk('google')->put($filename, file_get_contents($file));

    // Devolvemos la URL del archivo subido
    return Response ::json([
        'message' => 'Archivo subido correctamente',
        'url' => Storage::disk('google')->url($filename)
    ]);
});
