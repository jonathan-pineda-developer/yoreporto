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

    //Rutas de registro
Route::post('registro', [C_AuthController::class, 'registro'])->name("registro");
Route::post('/registro_UTE', [C_AuthController::class, 'registro_UTE'])->name("registro_UTE");
    //Rutas de Login
Route::post('google', [C_AuthController::class, 'googleSignIn'])->name("google");
Route::post('login', [C_AuthController::class, 'login'])->name("login");
    //Ruta de recuperación de contraseña
Route::post('recuperar_contrasenia', [C_AuthController::class, 'solicitudRecuperacionContrasenia']);
    //Ruta Reportes aceptados
Route::get('aceptados', [C_ReporteController::class, 'showReportesAceptados']);

//Rutas protegidas
Route::group(['middleware' => ['jwt.verify', 'usuario.activo']], function () {
// Rutas de autentificacion
        //renew token
    Route::get('renew', [C_AuthController::class, 'renew'])->name("renew_token");
        //códigode verificación
    Route::post('verificar_codigo', [C_AuthController::class, 'verificarCodigoDobleFactor'])->name("verificar_codigo");
    Route::post('reenviar_codigo/{id}', [C_AuthController::class, 'reenviarCodigoDobleFactor']);
    Route::post('reenviar_codigo_email', [C_AuthController::class, 'reenviarCodigoDobleFactorEmail']);
    Route::post('verificar_codigo_email', [C_AuthController::class, 'verificarCodigoDobleFactorEmail']);
        //contraseña
    Route::put('cambiar_contrasenia/{id}', [C_AuthController::class, 'cambiarContrasena']);
    Route::put('cambiar_contraseniaperfil/{id}', [C_AuthController::class, 'cambiarContrasena']);
    Route::put('olvido_contrasenia/{id}', [C_AuthController::class, 'olvidoContrasenia']);
    Route::put('cambiar_contrasenia_olvido/{id}', [C_AuthController::class, 'cambiarContraseniaOlvido']);

//Rutas reporte
    //crear reporte
    Route::post('crear_reporte', [C_ReporteController::class, 'store'])->name("crear_reporte");
        //metodo upload
    Route::post('upload', [C_ReporteController::class, 'upload']);
        // imgur img upload
    Route::post('/imgur', [C_ReporteController::class, 'uploadImgur']);
    Route::post('actualizar_imagen/reportes/{id}', [C_ReporteController::class, 'updateImagenReporte'])->name("actualizar_imagen_reporte");
    // métodos de mostrar reportes
    Route::get('user_reportes', [C_ReporteController::class, 'showByUserId'])->name("user_reportes");
    Route::get('reportes', [C_ReporteController::class, 'showAll']);
    Route::get('mostrar_reportes', [C_ReporteController::class, 'showAllReportes']);
    Route::get('/reportes/getImagenById/{id}', [C_ReporteController::class, 'getImagenReportesById']);
    Route::get('detalles_reportes/{id}', [C_ReporteController::class, 'showReporte']);
        //mostrar justificacion del reporte
    Route::get('/mostrar_justificacion/{id}', [C_ReporteController::class, 'showJustificacion']);
        //Ciuadadanos por estado
    Route::get('reportes_estado/{estado}', [C_ReporteController::class, 'showReportesByEstado']);
        //UTEs por estado
    Route::get('reportes_estado_ute/{estado}', [C_ReporteController::class, 'showReportesByEstadoUTE']);
    //métodos de actualizar reportes
        //categoría
    Route::put('/actualizar_categoria_reporte/{id}', [C_ReporteController::class, 'updateCategoria']);
        //estado
    Route::put('/aceptar_reporte/{id}', [C_ReporteController::class, 'aceptarReporte']);
    Route::put('/rechazar_reporte/{id}', [C_ReporteController::class, 'rechazarReporte']);
    Route::put('/finalizar_reporte/{id}', [C_ReporteController::class, 'finalizarReporte']);

//Rutas de categorias
    Route::post('crear_categoria', [C_CategoriaController::class, 'store']);
    Route::post('actualizar_categoria/{id}', [C_CategoriaController::class, 'update']);
        //mostrar
    Route::get('mostrar_categoria', [C_CategoriaController::class, 'mostrar']);
    Route::get('mostrar_categoria/{id}', [C_CategoriaController::class, 'getCategoria']);
        //eliminar
    Route::delete('eliminar_categoria/{id}', [C_CategoriaController::class, 'destroy']);
    Route::delete('deleteAll', [C_ReporteController::class, 'deleteAll']);

//Rutas de usuario
        //agregar ruta de traer imagen de reporte
    Route::get('/usuarios/getImagenById/{id}', [C_UserController::class, 'getImagenById']);
        //mostrar usuarios
    Route::get('/mostrar_usuarios', [C_UserController::class, 'show']);
    Route::get('/mostrar_usuario/{id}', [C_UserController::class, 'showById']);
    Route::get('/buscar_usuario/{nombre}', [C_UserController::class, 'search']);
            //ciudadanos
    Route::get('/mostrar_ciudadanos', [C_UserController::class, 'mostrar_ciudadano']);
    Route::get('/mostrar_usarios_activos', [C_AdministradorController::class, 'total_usuarios']);
            //UTEs
    Route::get('/mostrar_utes', [C_UserController::class, 'showAllUTE']);
    Route::get('/mostrar_ute_activos', [C_UserController::class, 'showAllUTEActivos']);
    Route::get('/mostrar_ute_inactivos', [C_UserController::class, 'showAllUTEInactivos']);
    Route::get('/mostrar_ute_activos_sin_categoria', [C_UserController::class, 'showAllUTEactivosSinCategoria']);
    Route::get('/mostrar_usuarios_UTE', [C_UserController::class, 'showAllUTE']);
    Route::get('/mostrar_reportes_UTE', [C_ReporteController::class, 'showByUTEId']);
    Route::get('/buscar_ute/{nombre}', [C_UserController::class, 'searchUTE']);
        //actualizar usuario
    Route::post('/actualizar/{id}', [C_UserController::class, 'update']);
    Route::post('/actualizar_imagen/usuarios/{id}', [C_UserController::class, 'updateImagen']);
    Route::post('/actualizar_datos/{id}', [C_UserController::class, 'updateDatos']);
        //inactivar y reactivar usuario
    Route::put('/inactivar/{id}', [C_UserController::class, 'inactivar']);
    Route::put('/reactivar/{id}', [C_UserController::class, 'reactivar']);

//Rutas de administrador
        //estadísticas
    Route::get('/mostrar_estadisticas', [C_AdministradorController::class, 'estadistica']);
    Route::get('/generar_pdf', [C_AdministradorController::class, 'generarPDF']);
        //bitácora
    Route::get('/mostrar_bitacora', [C_AdministradorController::class, 'mostrarBitacora']);
    Route::get('/filtro_bitacora/{params}', [C_AdministradorController::class, 'getFiltroBitacora']);
});

// drive img upload
//Route::post('/drive', [C_ReporteController::class, 'uploadDrive']);
