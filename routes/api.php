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
Route::post('recuperar_contrasenia', [C_AuthController::class, 'solicitudRecuperacionContrasenia']);
Route::put('cambiar_contrasenia/{id}', [C_AuthController::class, 'cambiarContrasena']);
Route::put('cambiar_contraseniaperfil/{id}', [C_AuthController::class, 'cambiarContrasena'])->middleware('jwt.verify');
//olvidoContrasenia
Route::put('olvido_contrasenia/{id}', [C_AuthController::class, 'olvidoContrasenia']);
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
Route::get('user_reportes', [C_ReporteController::class, 'showByUserId'])->middleware('jwt.verify', 'usuario.activo');
Route::get('reportes', [C_ReporteController::class, 'showAll']);
Route::get('mostrar_reportes', [C_ReporteController::class, 'showAllReportes']);
Route::get('aceptados', [C_ReporteController::class, 'showReportesAceptados']);
Route::get('reportes_estado/{estado}', [C_ReporteController::class, 'showReportesByEstado']);
Route::get('/reportes/getImagenById/{id}', [C_ReporteController::class, 'getImagenReportesById']);
Route::get('detalles_reportes/{id}', [C_ReporteController::class, 'showReporte']);
//showReportesByEstadoUTE
Route::get('reportes_estado_ute/{estado}', [C_ReporteController::class, 'showReportesByEstadoUTE'])->middleware('jwt.verify');
//delete all
Route::delete('deleteAll', [C_ReporteController::class, 'deleteAll']);
//metodo upload
Route::post('upload', [C_ReporteController::class, 'upload']);
// imgur img upload
Route::post('/imgur', [C_ReporteController::class, 'uploadImgur']);

//obtener reportes por id
Route::get('showById/{id}', [C_ReporteController::class, 'showById']);


Route::get('/mostrar_usuarios', [C_UserController::class, 'show']);
Route::get('/mostrar_usuario/{id}', [C_UserController::class, 'showById']);
Route::get('/mostrar_ciudadanos', [C_UserController::class, 'mostrar_ciudadano']);
//buscar por nombre de usuario
Route::get('/buscar_usuario/{nombre}', [C_UserController::class, 'search']);
Route::get('/buscar_ute/{nombre}', [C_UserController::class, 'searchUTE']);
//mostrar utes activos
Route::get('/mostrar_utes', [C_UserController::class, 'showAllUTE']);
Route::get('/mostrar_ute_activos', [C_UserController::class, 'showAllUTEActivos']);
Route::get('/mostrar_ute_inactivos', [C_UserController::class, 'showAllUTEInactivos']);
Route::get('/mostrar_ute_activos_sin_categoria', [C_UserController::class, 'showAllUTEactivosSinCategoria']);
Route::post('/actualizar/{id}', [C_UserController::class, 'update'])->middleware('jwt.verify');
Route::post('/actualizar_imagen/usuarios/{id}', [C_UserController::class, 'updateImagen'])->middleware('jwt.verify');
Route::get('/usuarios/getImagenById/{id}', [C_UserController::class, 'getImagenById']);
//agregar ruta de traer imagen de reporte

Route::post('/actualizar_datos/{id}', [C_UserController::class, 'updateDatos'])->middleware('jwt.verify');
Route::put('/inactivar/{id}', [C_UserController::class, 'inactivar']);
Route::put('/reactivar/{id}', [C_UserController::class, 'reactivar']);
Route::put('/actualizar_categoria_reporte/{id}', [C_ReporteController::class, 'updateCategoria'])->middleware('jwt.verify');
Route::put('/aceptar_reporte/{id}', [C_ReporteController::class, 'aceptarReporte'])->middleware('jwt.verify');
Route::put('/rechazar_reporte/{id}', [C_ReporteController::class, 'rechazarReporte'])->middleware('jwt.verify');
Route::put('/finalizar_reporte/{id}', [C_ReporteController::class, 'finalizarReporte'])->middleware('jwt.verify');


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
Route::post('actualizar_categoria/{id}', [C_CategoriaController::class, 'update']);
Route::delete('eliminar_categoria/{id}', [C_CategoriaController::class, 'destroy']);
//get categoria by id
Route::get('mostrar_categoria/{id}', [C_CategoriaController::class, 'getCategoria']);


//rutas UTEs
Route::get('/mostrar_usuarios_UTE', [C_UserController::class, 'showAllUTE']);
Route::post('/registro_UTE', [C_AuthController::class, 'registro_UTE']);
Route::get('/mostrar_reportes_UTE', [C_ReporteController::class, 'showByUTEId'])->middleware('jwt.verify');

//renew
Route::get('renew', [C_AuthController::class, 'renew'])->middleware('jwt.verify');

//rutas para el admin

Route::get('/mostrar_estadisticas', [C_AdministradorController::class, 'estadistica']);
Route::get('/generar_pdf', [C_AdministradorController::class, 'generarPDF']);
Route::get('/mostrar_bitacora', [C_AdministradorController::class, 'mostrarBitacora']);
Route::get('/filtro_bitacora/{params}', [C_AdministradorController::class, 'getFiltroBitacora']);

//contar ciudadanos
Route::get('/mostrar_usarios_activos', [C_AdministradorController::class, 'total_usuarios']);

// drive img upload
//Route::post('/drive', [C_ReporteController::class, 'uploadDrive']);

//mostrar justificacion del reporte
Route::get('/mostrar_justificacion/{id}', [C_ReporteController::class, 'showJustificacion']);
// drive img upload
//Route::post('/drive', [C_ReporteController::class, 'uploadDrive']);
