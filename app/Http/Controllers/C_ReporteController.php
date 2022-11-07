<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;

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
  
    

    $mensaje=[
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
      'titulo' => $reporte->titulo,
      'descripcion' => $reporte->descripcion,
      'imagen' => $reporte->imagen,
      'categoria_id' => $reporte->categoria_id,
      'user_id' => $reporte->user_id,
      'latitud' => $reporte->latitud,
      'longitud' => $reporte->longitud,
  

    ]
    );

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
   $usuario = C_Reporte::select('nombre as usuario','TB_Categoria.descripcion as categoria', 'TB_Reporte.estado', 'TB_Reporte.created_at', 'TB_Reporte.updated_at')
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


}






        
        
