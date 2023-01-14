<?php

namespace App\Http\Controllers;

use App\Mail\NuevoReporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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

    $user->cantidad_reportes = $user->cantidad_reportes + 1;
    $user->save();


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

    if ($request->hasFile('imagen')) {
      $file = $request->file('imagen');
      $name = time() . $file->getClientOriginalName();
      $file->move(public_path() . '/images/', $name);
      $reporte->imagen = $name;
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

  // cambiar la categoria de un reporte
  public function update(Request $request, $id)
  {
    $reporte = C_Reporte::find($id);
    $reporte->categoria_id = $request->categoria_id;
    $reporte->save();

    // email a ute a cargo de la nueva categoria
    $categoria = C_Categoria::find($reporte->categoria_id);
    $userUTE = User::find($categoria->user_id);
    Mail::to($userUTE->email)->send(new NuevoReporte($categoria->descripcion));

    return response()->json([
      'message' => 'Categoria actualizada correctamente',
      'reporte' => $reporte
    ], 200);
  }
}
