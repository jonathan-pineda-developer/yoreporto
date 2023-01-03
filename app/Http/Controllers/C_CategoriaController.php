<?php

namespace App\Http\Controllers;

use App\Models\C_Categoria;
use Illuminate\Http\Request;
use Validator;

class C_CategoriaController extends Controller
{
   //metodo para crear una categoria

    public function store(Request $request)
    {
        $categoria = new C_Categoria();
        $categoria->descripcion = $request->descripcion;
    
        $mensaje = [
            'required' => 'El campo :attribute es requerido',
            'descripcion.max' => 'El campo descripcion no debe ser mayor a 100 caracteres',
        ];
    
        $this->validate($request, [
            'descripcion' => 'required|string|max:100',
        ], $mensaje);
    
        $categoria = C_Categoria::create(
            [
                'id' => $categoria->id,
                'descripcion' => $categoria->descripcion,
            ]
        );
    
        //si la categoria se crea correctamente

        if ($categoria) {
            return response()->json([
                
                'message' => 'Categoria creada correctamente',
                'categoria'=> $categoria
               
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

    public function destroy($id)
    {
        //si se encuentra la categoria con el id

        if (C_Categoria::where('id', $id)->exists()) {
        $categoria = C_Categoria::find($id);
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
            ];
    
            $this->validate($request, [
                'descripcion' => 'required|string|max:100',
            ], $mensaje);
    
            $categoria = C_Categoria::where('id', $id)->update(
                [
                    'id' => $categoria->id,
                    'descripcion' => $categoria->descripcion,
                ]
            );
    
            //si la categoria se actualiza correctamente
    
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
    
}
