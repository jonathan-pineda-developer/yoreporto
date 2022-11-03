<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\C_Reporte;

class C_ReporteController extends Controller
{

//metodo para agregar reporte

public function store(Request $request)
{
  $uid = auth()->user()->id;
  $reporte = new C_Reporte();
    $reporte->titulo = $request->titulo;
    $reporte->descripcion = $request->descripcion;
    $reporte->imagen = $request->imagen;
    $reporte->categoria_id = $request->categoria_id;
    $reporte->user_id = $uid;
    $reporte->save();

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
    ]
  );

    return response()->json([
        'message' => 'Reporte creado correctamente',
        'reporte' => $reporte
    ], 201);
//   $uid = auth()->user()->id;
//   $reporte = new C_Reporte();
//   $reporte->user_id = $uid;
//   $reporte->titulo = $request->titulo;
//   $reporte->descripcion = $request->descripcion;
//  // $reporte->imagen = $request->imagen;
//   $reporte->categoria_id= $request->categoria_id;
//   $reporte->save();
//   return response()->json([
//     'ok' => true,
//     'status' => 'success',
//     'message' => 'Reporte creado con exito',
//     'data' => $reporte
//   ], 200);

    
}

}



        
        
