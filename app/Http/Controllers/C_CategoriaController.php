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

        $categorias = C_Categoria::all();

        if ($categorias->count() > 0) {
    
            $categorias_info = [];
    
            foreach ($categorias as $categoria) {
                $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
                    ->where('id', $categoria->user_id)
                    ->where('rol', 'UTE')
                    ->value('nombre_completo');
                $categorias_info[] = [
                    'id' => $categoria->id,
                    'descripcion' => $categoria->descripcion,
                    'color' => $categoria->color,
                    'ute' => $ute,
                ];
            }
    
            return response()->json([
                'message' => 'Categorias encontradas',
                'categorias' => $categorias_info,
            ], 200);
    
        } else {
            return response()->json([
                'message' => 'No se encuentran categorias',
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

            // Validar los datos del formulario
            $validatedData = $request->validate([
                'descripcion' => 'required',
                'color' => 'required',
                'ute' => 'required',
            ]);

            // Buscar el UTE por su nombre y apellidos (nombre y apellidos son únicos)
            $ute = User::where(DB::raw("CONCAT(nombre, ' ', apellidos)"), $request->ute)->where('rol', 'UTE')->firstOrFail();
         
            $categoria = C_Categoria::where('id', $id)->update([
                'descripcion' => $request->descripcion,
                'color' => $request->color,
                'user_id' => $ute->id,
            ]);

            if ($categoria) {
                return response()->json([
                    'message' => 'Categoria actualizada correctamente',
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
    public function getCategoria($id)
    {
        $categoria = C_Categoria::find($id);
        if (!$categoria) {
            return response()->json([
                'message' => 'La categoría no existe',
            ], 404);
        }
    
        $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
            ->where('id', $categoria->user_id)
            ->where('rol', 'UTE')
            ->value('nombre_completo');
    
        return response()->json([
            'id' => $categoria->id,
            'descripcion' => $categoria->descripcion,
            'color' => $categoria->color,
            'ute' => $ute,
        ], 200);
    }
    
}
