<?php

namespace App\Http\Controllers;

use App\Mail\NuevoReporte;
use App\Mail\RechazoReporte;
use App\Mail\ReporteACiudadano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;
use App\Observers\ReporteObserver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

use Illuminate\Pagination\LengthAwarePaginator;

class C_ReporteController extends Controller
{

  //metodo para agregar reporte

  public function store(Request $request)
  {
    $uid = auth()->user()->id;
    $y = $request->input('longitud');
    $reporte = new C_Reporte();
    $reporte->titulo = $request->titulo;
    $reporte->descripcion = $request->descripcion;
    $reporte->imagen = $request->imagen;
    $reporte->categoria_id = $request->categoria_id;
    $reporte->user_id = $uid;
    $reporte->latitud = $request->latitud;
    $reporte->longitud = $request->longitud;

    // usuario que emite el reporte
    $user = User::find($reporte->user_id);

    // validacion de reportes para usuarios ciudadanos
    if ($user->rol == 'Ciudadano') {
      if ($user->cantidad_reportes >= 3) {
        return response()->json([
          'message' => 'No puedes emitir mas reportes, el limite es de 3 reportes por usuario al mes',
        ], 400);
      }
    }


    $mensaje = [
      'required' => 'El campo :attribute es requerido',
      'titulo.max' => 'El campo titulo no debe ser mayor a 50 caracteres',
      'descripcion.max' => 'El campo descripcion no debe ser mayor a 255 caracteres',
      //'imagen.mimes' => 'El campo imagen debe ser de tipo jpeg, jpg, png o gif',
      'imagen.max' => 'El campo imagen no debe ser mayor a 10MB',
    ];

    $this->validate($request, [
      'titulo' => 'required|string|max:50',
      'descripcion' => 'required|string|max:255',
      // 'imagen' => 'mimes:jpeg,jpg,png,gif|max:10000',
      'categoria_id' => 'required',
    ], $mensaje);

    $user->cantidad_reportes = $user->cantidad_reportes + 1;
    $user->save();

    if ($request->hasFile('imagen')) {
      $file = $request->file('imagen')->store('public/reportes');
      $reporte->imagen = $file;
      $reporte->imagen = substr($reporte->imagen, 16);
    }

    $reporte = C_Reporte::create(
      [
        'id' => Str::uuid()->toString(),
        'titulo' => $reporte->titulo,
        'descripcion' => $reporte->descripcion,
        'imagen' => $reporte->imagen,
        'categoria_id' => $reporte->categoria_id,
        'user_id' => $reporte->user_id,
        'latitud' => $reporte->latitud,
        'longitud' => $reporte->longitud,
      ]
    );

    $categoria = C_Categoria::find($reporte->categoria_id);
    // email a ute a cargo
    $userUTE = User::find($categoria->user_id);
    Mail::to($userUTE->email)->send(new NuevoReporte($categoria->descripcion));

    // envio de emual a ciudadano con ReporteACiudadano
    Mail::to($user->email)->send(new ReporteACiudadano($reporte, $user));

    return response()->json([
      'message' => 'Reporte creado correctamente',
      'reporte' => $reporte
    ], 201);
  }
  //metodo uoload para subir imagen
  public function upload(Request $request)
  {
    $file = $request->file('imagen')->store('public/reportes');
    $file = substr($file, 16);
    return response()->json([
      'message' => 'Imagen subida correctamente',
      'imagen' => $file
    ], 201);
  }

  //actualizar imagen del reporte
  public function updateImagenReporte(Request $request, $id)
  {

    $reporte = C_Reporte::findOrFail($id);
    $destino = public_path("storage\\" . $reporte->imagen);
    if ($reporte == null) {
      return response()->json([
        'message' => 'No se encontro el registro'
      ], 404);
    } else {

      if ($request->hasFile('imagen')) {
        if (File::exists($destino)) {
          File::delete($destino);
        }
        $reporte->imagen = $request->file('imagen')->store('public/reportes');
        $reporte->imagen = substr($reporte->imagen, 16);
      }
      $reporte->save();
      if ($reporte->save()) {
        return response()->json([
          'message' => 'Imagen actualizada correctamente',
          'reporte' => $reporte
        ], 200);
      } else {
        return response()->json([
          'message' => 'No se pudo actualizar el usuario'
        ], 404);
      }
    }
  }
  public function getImagenReportesById(Request $request, $id)
  {

    $imagen = $request->id;

    //path de donde se encuentra la imagen public/storage/usuarios/id.extension
    $path = storage_path("app/public/reportes/" . $imagen);

    if (file_exists($path)) {
      return response()->file($path);
    } else {
      return response()->file(storage_path("app/public/reportes/default1.png"));
    }
  }
  //MOSTRAR LOS REPORTES QUE PERTECEN A CADA CATEGORÍA DE UTE
  public function showByUTEId()
  {
    //obtener la categoria de la ute logueada
    $perPage = 5;
    $uid = auth()->user()->id;
    $categoria = C_Categoria::where('user_id', $uid)->get();
    $reportes = C_Reporte::where('categoria_id', $categoria[0]->id)->paginate($perPage);

    if (count($reportes) > 0) {
      return response()->json([
        'reportes' => $reportes
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }


  //mostrar reportes que tiene un usuario logueado
  public function showByUserId()
  {
    $perPage = 6;
    $uid = auth()->user()->id;
    $reportes = C_Reporte::with('categoria')->where('user_id', $uid)->paginate($perPage);
    if ($reportes->total()  > 0) {
      return response()->json([
        'reportes' => $reportes
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }

  //mostrar todos los reportes con fecha de creado, nombre de usuario, nombre de categoria y estado

  public function showAll()
  {
    $perPage = 10;
    $usuario = C_Reporte::select('nombre as Ciudadano', 'TB_Categoria.descripcion as Categoria', 'TB_Categoria.user_id as UTE a cargo', 'TB_Reporte.estado as Estado', 'TB_Reporte.created_at as Fecha de creacion', 'TB_Reporte.updated_at as Fecha de actualizacion/finalizacion')
      ->join('users', 'users.id', '=', 'TB_Reporte.user_id')
      ->join('TB_Categoria', 'TB_Categoria.id', '=', 'TB_Reporte.categoria_id')
      ->paginate($perPage);;
    $reportes = C_Reporte::all();

    if (count($reportes) > 0) {
      return response()->json([

        $usuario,

      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }

  //mostrar todos los cmapos de la table reportes
  //mostrar todos los cmapos de la table reportes
  public function showAllReportes()
  {
    $perPage = 10;
    $reportes = C_Reporte::with('categoria')->paginate($perPage);

    if ($reportes->total() > 0) {
      return response()->json([
        'reportes' => $reportes
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }

  //obtener reporte por id
  public function showById($id)
  {
    $reporte = C_Reporte::find($id);
    if ($reporte == null) {
      return response()->json([
        'message' => 'No se encontro el reporte',
      ], 404);
    } else {
      return response()->json([
        'reporte' => $reporte
      ], 200);
    }
  }

  // cambiar la categoria de un reporte
  public function updateCategoria(Request $request, $id)
  {
    $reporte = C_Reporte::find($id);
    // traer la categoria nueva de la base en base a la descripcion que viene en el request (VERSIOON ANTERIOR)
    // $categoriaNueva = C_Categoria::where('descripcion', $request->descripcion)->first();
    $categoriaNueva = C_Categoria::find($request->categoria_id);

    $reporte->categoria_id = $categoriaNueva->id;
    $reporte->save();

    // email a ute a cargo de la nueva categoria
    $categoria = C_Categoria::find($reporte->categoria_id);
    $userUTE = User::find($categoria->user_id);
    Mail::to($userUTE->email)->send(new NuevoReporte($categoria->descripcion));

    $user_id = auth()->user()->id;
    $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
      ->where('id', $user_id)
      ->where('rol', 'UTE')
      ->value('nombre_completo');
    $reporte_id = $reporte->id;
    $modified_at = $reporte->updated_at;


    $operation = 'Cambio de categoría';

    DB::table('TB_Bitacora')->insert([
      'operation' => $operation,
      'ute' => $ute,
      'reporte_id' => $reporte_id,
      'modified_at' => $modified_at,
    ]);

    if ($reporte->save()) {
      return response()->json([
        'message' => 'Se ha cambiado la categoría del reporte exitósamente',
      ], 200);
    } else {
      return response()->json([
        'message' => 'Error inesperado, vuelva a intentarlo',
      ], 400);
    }
  }

  // metodo para cambiar el estado del reporte a aceptado
  public function aceptarReporte(Request $request, $id)
  {

    $reporte = C_Reporte::find($id);
    if ($reporte->estado === "Aceptado") {
      return response()->json([
          'message' => 'El reporte ya se encuentra aceptado.',
      ], 400);
  }

    $reporte->estado = "Aceptado";
    $reporte->save();

    $user_id = auth()->user()->id;
    $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
      ->where('id', $user_id)
      ->where('rol', 'UTE')
      ->value('nombre_completo');
    $reporte_id = $reporte->id;
    $modified_at = $reporte->updated_at;

    // Determinar el valor de la operación en función del estado del reporte
    if ($reporte->estado === 'Aceptado') {
      $operation = 'Aceptado';
    } elseif ($reporte->estado === 'Rechazado') {
      $operation = 'Rechazado';
    } elseif ($reporte->estado === 'Finalizado') {
      $operation = 'Finalizado';
    } else {
      $operation = 'Cambio de categoría';
    }

    DB::table('TB_Bitacora')->insert([
      'operation' => $operation,
      'ute' => $ute,
      'reporte_id' => $reporte_id,
      'modified_at' => $modified_at,
    ]);

    if ($reporte->save()) {
      return response()->json([
        'message' => 'Reporte aceptado',
      ], 200);
    } else {
      return response()->json([
        'message' => 'Error inesperado, vuelva a intentarlo',
      ], 400);
    }
  }

  // metodo para cambiar el estado del reporte a rechazado y enviar un email al usuario que creo el reporte con el motivo del rechazo
  public function rechazarReporte(Request $request, $id)
  {
    // rechazo del reporte
    $reporte = C_Reporte::find($id);
        // Verificar si el reporte ya está rechazado
        if ($reporte->estado === "Rechazado") {
          return response()->json([
              'message' => 'El reporte ya se encuentra rechazado.',
          ], 400);
      }
    $reporte->estado = "Rechazado";
    $reporte->save();

    // ute a cargo de la categoria del reporte
    $categoria = C_Categoria::find($reporte->categoria_id);
    $userUTE = User::find($categoria->user_id);

    // email al usuario que creo el reporte
    $user = User::find($reporte->user_id);
    Mail::to($user->email)->send(new RechazoReporte($request->motivo, $reporte->titulo, $user, $userUTE, $reporte->id));

    $user_id = auth()->user()->id;
    $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
      ->where('id', $user_id)
      ->where('rol', 'UTE')
      ->value('nombre_completo');
    $reporte_id = $reporte->id;
    $modified_at = $reporte->updated_at;

    // Determinar el valor de la operación en función del estado del reporte
    if ($reporte->estado === 'Aceptado') {
      $operation = 'Aceptado';
    } elseif ($reporte->estado === 'Rechazado') {
      $operation = 'Rechazado';
    } elseif ($reporte->estado === 'Finalizado') {
      $operation = 'Finalizado';
    } else {
      $operation = 'Cambio de categoría';
    }

    DB::table('TB_Bitacora')->insert([
      'operation' => $operation,
      'justificacion' => $request->motivo,
      'ute' => $ute,
      'reporte_id' => $reporte_id,
      'modified_at' => $modified_at,
    ]);

    if ($reporte->save()) {
      return response()->json([
        'message' => 'Reporte rechazado',
      ], 200);
    } else {
      return response()->json([
        'message' => 'Error inesperado, vuelva a intentarlo',
      ], 400);
    }
  }

  // metodo que retorna los reportes que tienen el estado Aceptado o Finalizado
  public function showReportesAceptados()
  {
    $reportes = C_Reporte::where('estado', 'Aceptado')->get();

    if (count($reportes) > 0) {
      return response()->json([
        'reportes' => $reportes
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }

  // metodo que retorna los reportes en base al estado que venga en el request
  public function showReportesByEstado(Request $request)
  {
    $perPage = 5;
    $reportes = C_Reporte::where('estado', $request->estado)->paginate($perPage);

    if (count($reportes) > 0) {
      return response()->json([
        'reportes' => $reportes
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }
  //mostrar los reportes por estado y MOSTRAR LOS REPORTES QUE PERTECEN A CADA CATEGORÍA DE UTE
  public function showReportesByEstadoUTE(Request $request)
  {
    $perPage = 5;
    $user_id = auth()->user()->id;
    $categoria = C_Categoria::where('user_id', $user_id)->first();
    $reportes = C_Reporte::where('estado', $request->estado)->where('categoria_id', $categoria->id)->paginate($perPage);

    if (count($reportes) > 0) {
      return response()->json([
        'reportes' => $reportes
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontraron reportes',
      ], 404);
    }
  }

  /*
  public function uploadDrive(Request $request)
  {

    // Obtenemos el archivo que se subió
    $file = $request->file('imagen')->getRealPath();

    // Generamos un nombre único para el archivo
    $filename = uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension();

    // Escribimos el archivo en Google Drive
    Storage::disk('google')->put($filename, file_get_contents($file));

    // Devolvemos la URL del archivo subido
    return response()->json([
      'message' => 'Archivo subido correctamente',
      'url' => Storage::disk('google')->url($filename),
    ], 200);
  }
  */

  //funcion para mostrar la justificacion de la tabla bitacora usando el id del reporte y que sea el ultimo registro de ese reporte

  public function showJustificacion($id)
  {
    $registro = DB::table('TB_Bitacora')->where('reporte_id', $id)->latest('id')->first();
  
    if ($registro) {
      return response()->json([
        'justificacion' => $registro->justificacion
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontró justificación',
      ], 400);
    }
  }

  public function finalizarReporte(Request $request, $id)
  {
    $reporte = C_Reporte::find($id);
    if ($reporte->estado === "Finalizado") {
      return response()->json([
          'message' => 'El reporte ya se se encuentra finalizado.',
      ], 400);
  }
    $reporte->estado = "Finalizado";
    $reporte->save();

    $user_id = auth()->user()->id;
    $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
      ->where('id', $user_id)
      ->where('rol', 'UTE')
      ->value('nombre_completo');
    $reporte_id = $reporte->id;
    $modified_at = $reporte->updated_at;

    // Determinar el valor de la operación en función del estado del reporte
    if ($reporte->estado === 'Aceptado') {
      $operation = 'Aceptado';
    } elseif ($reporte->estado === 'Rechazado') {
      $operation = 'Rechazado';
    } elseif ($reporte->estado === 'Finalizado') {
      $operation = 'Finalizado';
    } else {
      $operation = 'Cambio de categoría';
    }

    DB::table('TB_Bitacora')->insert([
      'operation' => $operation,
      'ute' => $ute,
      'reporte_id' => $reporte_id,
      'modified_at' => $modified_at,
    ]);

    if ($reporte->save()) {
      return response()->json([
        'message' => 'Reporte finalizado',
      ], 200);
    } else {
      return response()->json([
        'message' => 'Error inesperado, vuelva a intentarlo',
      ], 400);
    }
  }

  // metodo para buscar un reporte por su id
  public function showReporteById($id)
  {
    $reporte = C_Reporte::find($id);
    if ($reporte != null) {
      return response()->json([
        'reporte' => $reporte
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontró el reporte',
      ], 404);
    }
  }

  //metodo uoload para subir imagen
  public function uploadImgur(Request $request)
  {
    //guardar imagen en storage
    $client = new Client();
    $response = $client->request('POST', 'https://api.imgur.com/3/image', [
      'headers' => [
        'Authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID')
      ],
      'form_params' => [
        'image' => base64_encode(file_get_contents($request->file('imagen')->path()))
      ]
    ]);
    $data = json_decode($response->getBody());

    $path = $data->data->link;
    return response()->json([
      'message' => 'Imagen subida correctamente',
      'nombre' => $path
    ], 200);
  }
  public function showReporte($id)
  {
    $reporte = C_Reporte::find($id);
    if ($reporte != null) {
      return response()->json([
        'reporte' => $reporte
      ], 200);
    } else {
      return response()->json([
        'message' => 'No se encontró reporte',
      ], 404);
    }
  }
}
