<?php

namespace App\Http\Controllers;

use App\Models\C_Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Mail\CategoriaUTE;
use Illuminate\Support\Facades\Mail;

class C_CategoriaController extends Controller
{
    //metodo para crear una categoria
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'descripcion' => 'required',
            'color' => 'required',
            'ute' => 'required',
        ]);

        // Buscar el UTE por su nombre y apellidos (nombre y apellidos son únicos)
        $ute = User::where(DB::raw("CONCAT(nombre, ' ', apellidos)"), $request->ute)->where('rol', 'UTE')->firstOrFail();

        // Crear y guardar la categoría
        $categoria = C_Categoria::create([
            'user_id' => $ute->id,
            'descripcion' => $request->descripcion,
            'color' => $request->color,
        ]);

        // Guardar la categoría
        if ($categoria->save()) {

            // Enviar correo al UTE notificándole a que categoría fue asignado
            Mail::to($ute->email)->send(new CategoriaUTE($ute, $categoria));

            return response()->json([
                'message' => 'Categoria creada correctamente',
                "categoria" => $categoria
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error al crear la categoria',
            ], 400);
        }
    }

    //metodo para mostrar todas las categorias

    public function mostrar()
    {
        //si hay categorias
        if (C_Categoria::count() > 0) {
            $categorias = C_Categoria::all();
            return response()->json([
                'categorias' => $categorias,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No hay categorias registradas',
            ], 400);
        }
    }

    //metodo para eliminar una categoria
    public function destroy(Request $requeset, $id)
    {
        //si se encuentra la categoria con el id
        if (C_Categoria::where('id', $id)->exists()) {

            $categoria = C_Categoria::find($id);
            $categoria->user_id = "";
            $categoria->save();
            $categoria->delete();

            if ($categoria) {
                return response()->json([
                    'message' => 'Categoria eliminada correctamente',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error al eliminar la categoria',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'No se encuentra la categoria',
            ], 400);
        }
    }

    //metodo para actualizar una categoria

    public function update(Request $request, $id)
    {
        //si se encuentra la categoria con el id
        if (C_Categoria::where('id', $id)->exists()) {
            $categoria = C_Categoria::find($id);
            $categoria->descripcion = $request->descripcion;

            $mensaje = [
                'required' => 'El campo :attribute es requerido',
                'descripcion.max' => 'El campo descripcion no debe ser mayor a 100 caracteres',
                'color.max' => 'El campo color no debe ser mayor a 7 caracteres, ejemplo: #ffffff',
                'user_id.max' => 'El campo user_id no debe ser mayor a 36 caracteres',
            ];

            $this->validate($request, [
                'descripcion' => 'required|string|max:100',
                'color' => 'required|string|max:7',
                'user_id' => 'required|string|max:36',
            ], $mensaje);

            $categoria = C_Categoria::where('id', $id)->update(
                [
                    'id' => $categoria->id,
                    'descripcion' => $categoria->descripcion,
                    'color' => $categoria->color,
                    'user_id' => $categoria->user_id,
                ]
            );

            //si la categoria se actualiza correctamente
            if ($categoria) {
                return response()->json([
                    'message' => 'Categoria actualizada correctamente',
                    "categoria" => $categoria
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error al actualizar la categoria',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'No se encuentra la categoria',
            ], 400);
        }
    }
}
