<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class C_UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show()
    {
        if (User::all()->isEmpty()) {
            return response()->json(['message' => 'No hay registros'], 404);
        } else {
            $str_user = User::all();
            return response ($str_user,200);
        }
    }
    public function edit(Request $str_request, $vint_id)
    {

        $str_user = User::find($vint_id);
        if (User::find($vint_id) == null) {
            return response()->json(['message' => 'No se encontro el registro'], 404);
        } else {
            $str_user->update($str_request->all());
            return response($str_user, 200);
        }
    }
      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function update(Request $str_request, $vint_id)
    {
        $str_campos=[
            'nombre' => 'required|string|max:30',
            'apellidos' => 'required|string|max:70',
            'correo' => 'required|string|max:100|unique:users|email',
            'password' => 'required|string|max:100',
        ];
        return reponse ()->json([
            'required' => 'El :attribute es requerido'], 404);

        if ($str_request->hasFile('imagen')) {
            $str_campos=['imagen'=>'required|max:10000|mimes:jpeg,png,jpg'];
           
        }

        $this->validate($str_request,$str_campos,$str_mensaje);

        $str_datosUser=request()->except(['_token','_method']);

        if ($str_request->hasFile('imagen')) {
            $str_user=User::findOrFail($vint_id);

            Storage::delete('public/'.$str_user->imagen);

            $str_datosUser['imagen']=$str_request->file('imagen')->store('uploads','public');
        }

        User::where('id','=',$vint_id)->update($str_datosUser);

        $str_user=User::findOrFail($vint_id);

         return response($str_user, 200);



    }

    public function inactivar(Request $str_request,$vint_id)
    {
        $str_user = User::find($vint_id);
        if (User::find($vint_id) == null) {
            return reponse()->json([
                'message' => 'No se encontro el registro'], 404);
        } else {
            $str_user->estado = 0;
            $str_user->save();
            $str_user->update($str_request->all());
            //retornar mensaje y datos del usuario
            return response()->json([
                'message' => 'Usuario inactivado correctamente'
                
            ], 200);
        }
    
    }
    
}