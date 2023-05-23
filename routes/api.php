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
// SIN USO
//cambiarContraseniaOlvido
Route::put('/cambiar_contrasenia_olvido/{id}', [C_AuthController::class, 'cambiarContraseniaOlvido']);
//actualizar imagen del reporte
Route::post('/actualizar_imagen/reportes/{id}', [C_ReporteController::class, 'updateImagenReporte'])->middleware('jwt.verify');
// eliminar reportes
Route::delete('/deleteAll', [C_ReporteController::class, 'deleteAll']);

// RUTAS PUBLICAS
// rutas de autentificacion
Route::post('/registro', [C_AuthController::class, 'registro']);
Route::post('/login', [C_AuthController::class, 'login']);
Route::post('/verificar_codigo', [C_AuthController::class, 'verificarCodigoDobleFactor']);
Route::post('/reenviar_codigo/{id}', [C_AuthController::class, 'reenviarCodigoDobleFactor']);
Route::post('/recuperar_contrasenia', [C_AuthController::class, 'solicitudRecuperacionContrasenia']);
Route::put('/cambiar_contrasenia/{id}', [C_AuthController::class, 'cambiarContrasena']);
Route::post('/reenviar_codigo_email', [C_AuthController::class, 'reenviarCodigoDobleFactorEmail']);
Route::put('/olvido_contrasenia/{id}', [C_AuthController::class, 'olvidoContrasenia']);
// ruta para login/registro de google
Route::post('/google', [C_AuthController::class, 'googleSignIn']);
Route::get('/usuarios/getImagenById/{id}', [C_UserController::class, 'getImagenById']);
// ruta reportes para mapa principal
Route::get('/aceptados_finalizados', [C_ReporteController::class, 'showReportesAceptadosFinalizados']);

//categorias
Route::get('/mostrar_categoria', [C_CategoriaController::class, 'mostrar']);
//renew
Route::get('/renew', [C_AuthController::class, 'renew'])->middleware('jwt.verify');

Route::group(['middleware' => ['jwt.verify', 'usuario.activo']], function () {

    // Auth / perfil
    Route::put('cambiar_contraseniaperfil/{id}', [C_AuthController::class, 'cambiarContrasena']);
    Route::post('/actualizar/{id}', [C_UserController::class, 'update']);
    Route::post('/actualizar_imagen/usuarios/{id}', [C_UserController::class, 'updateImagen']);
    Route::post('/actualizar_datos/{id}', [C_UserController::class, 'updateDatos']);

    // Reportes
    Route::post('/crear_reporte', [C_ReporteController::class, 'store']);
    Route::get('/user_reportes', [C_ReporteController::class, 'showByUserId']);
    Route::get('/reportes', [C_ReporteController::class, 'showAll']);
    Route::get('/mostrar_reportes', [C_ReporteController::class, 'showAllReportes']);
    Route::get('/reportes_estado/{estado}', [C_ReporteController::class, 'showReportesByEstado']);
    Route::get('/reportes/getImagenById/{id}', [C_ReporteController::class, 'getImagenReportesById']);
    Route::get('/detalles_reportes/{id}', [C_ReporteController::class, 'showReporte']);
    Route::get('/reportes_estado_ute/{estado}', [C_ReporteController::class, 'showReportesByEstadoUTE']);
    Route::post('/upload', [C_ReporteController::class, 'upload']);
    Route::post('/imgur', [C_ReporteController::class, 'uploadImgur']);
    Route::get('/showById/{id}', [C_ReporteController::class, 'showById']);

    // Categorias
    Route::get('/mostrar_categoria/{id}', [C_CategoriaController::class, 'getCategoria']);
    Route::post('/crear_categoria', [C_CategoriaController::class, 'store']);
    Route::post('/actualizar_categoria/{id}', [C_CategoriaController::class, 'update']);
    Route::delete('/eliminar_categoria/{id}', [C_CategoriaController::class, 'destroy']);

    // UTEs
    Route::put('/aceptar_reporte/{id}', [C_ReporteController::class, 'aceptarReporte']);
    Route::put('/rechazar_reporte/{id}', [C_ReporteController::class, 'rechazarReporte']);
    Route::put('/finalizar_reporte/{id}', [C_ReporteController::class, 'finalizarReporte']);
    Route::put('/actualizar_categoria_reporte/{id}', [C_ReporteController::class, 'updateCategoria']);
    Route::get('/mostrar_justificacion/{id}', [C_ReporteController::class, 'showJustificacion']);

    // Administrador
    Route::get('/mostrar_usuarios_UTE', [C_UserController::class, 'showAllUTE']);
    Route::post('/registro_UTE', [C_AuthController::class, 'registro_UTE']);
    Route::get('/mostrar_reportes_UTE', [C_ReporteController::class, 'showByUTEId']);
    // Administrador con reportes y estadisticas
    Route::get('/mostrar_estadisticas', [C_AdministradorController::class, 'estadistica']);
    Route::get('/generar_pdf', [C_AdministradorController::class, 'generarPDF']);
    Route::get('/mostrar_bitacora', [C_AdministradorController::class, 'mostrarBitacora']);
    Route::get('/filtro_bitacora/{params}', [C_AdministradorController::class, 'getFiltroBitacora']);
    // Administrador con usuarios
    Route::put('/inactivar/{id}', [C_UserController::class, 'inactivar']);
    Route::put('/reactivar/{id}', [C_UserController::class, 'reactivar']);
    Route::get('/mostrar_usuarios', [C_UserController::class, 'show']);
    Route::get('/mostrar_usuario/{id}', [C_UserController::class, 'showById']);
    Route::get('/mostrar_ciudadanos', [C_UserController::class, 'mostrar_ciudadano']);
    Route::get('/buscar_usuario/{nombre}', [C_UserController::class, 'search']);
    Route::get('/buscar_ute/{nombre}', [C_UserController::class, 'searchUTE']);
    Route::get('/mostrar_utes', [C_UserController::class, 'showAllUTE']);
    Route::get('/mostrar_ute_activos', [C_UserController::class, 'showAllUTEActivos']);
    Route::get('/mostrar_ute_inactivos', [C_UserController::class, 'showAllUTEInactivos']);
    Route::get('/mostrar_ute_activos_sin_categoria', [C_UserController::class, 'showAllUTEactivosSinCategoria']);
    Route::get('/mostrar_usarios_activos', [C_AdministradorController::class, 'total_usuarios']);
});
