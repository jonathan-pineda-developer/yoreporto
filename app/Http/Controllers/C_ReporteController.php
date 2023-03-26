<?php

namespace App\Http\Controllers;

use App\Mail\NuevoReporte;
use App\Mail\RechazoReporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

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

    return response()->json([
      'message' => 'Reporte creado correctamente',
      'reporte' => $reporte
    ], 201);
  }
  //metodo uoload para subir imagen
  public function upload(Request $request){
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
       $destino = public_path("storage\\". $reporte->imagen);
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

        if(file_exists($path)){
            return response()->file($path);
        }else{
            return response()->file(storage_path("app/public/reportes/default.png"));
        }
    }
//eliminar todos los reportes
  public function deleteAll()
  {
    $reportes = C_Reporte::all();
    if (count($reportes) > 0) {
      foreach ($reportes as $reporte) {
        $reporte->delete();
      }
      return response()->json([
        'message' => 'Reportes eliminados correctamente',
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
    $uid = auth()->user()->id;
    $reportes = C_Reporte::where('user_id', $uid)->get();
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

  //mostrar todos los reportes con fecha de creado, nombre de usuario, nombre de categoria y estado

  public function showAll()
  {
    $usuario = C_Reporte::select('nombre as Ciudadano', 'TB_Categoria.descripcion as Categoria', 'TB_Categoria.user_id as UTE a cargo', 'TB_Reporte.estado as Estado', 'TB_Reporte.created_at as Fecha de creacion', 'TB_Reporte.updated_at as Fecha de actualizacion/finalizacion')
      ->join('users', 'users.id', '=', 'TB_Reporte.user_id')
      ->join('TB_Categoria', 'TB_Categoria.id', '=', 'TB_Reporte.categoria_id')
      ->get();
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
  public function showAllReportes()
  {
    $reportes = C_Reporte::all();
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
    // traer la categoria nueva de la base en base a la descripcion que viene en el request  
    $categoriaNueva = C_Categoria::where('descripcion', $request->descripcion)->first();
    $reporte->categoria_id = $categoriaNueva->id;
    $reporte->save();

    // email a ute a cargo de la nueva categoria
    $categoria = C_Categoria::find($reporte->categoria_id);
    $userUTE = User::find($categoria->user_id);
    Mail::to($userUTE->email)->send(new NuevoReporte($categoria->descripcion));

    return response()->json([
      'message' => 'Categoria actualizada correctamente',
    ], 200);
  }

  // metodo para cambiar el estado del reporte a aceptado
  public function aceptarReporte(Request $request, $id)
  {
    $uuid = auth()->user()->id;
    $reporte = C_Reporte::find($id);
    $reporte->estado = "Aceptado";
    $reporte->save();

    return response()->json([
      'message' => 'Estado actualizado correctamente, el reporte ha sido aceptado',
    ], 200);
  }

  // metodo para cambiar el estado del reporte a rechazado y enviar un email al usuario que creo el reporte con el motivo del rechazo 
  public function rechazarReporte(Request $request, $id)
  {
    // rechazo del reporte
    $reporte = C_Reporte::find($id);
    $reporte->estado = "Rechazado";
    $reporte->save();

    // ute a cargo de la categoria del reporte
    $categoria = C_Categoria::find($reporte->categoria_id);
    $userUTE = User::find($categoria->user_id);

    // email al usuario que creo el reporte
    $user = User::find($reporte->user_id);
    Mail::to($user->email)->send(new RechazoReporte($request->motivo, $reporte->titulo, $user, $userUTE));

    return response()->json([
      'message' => 'Estado actualizado correctamente, el reporte ha sido rechazado',
    ], 200);
  }
}
