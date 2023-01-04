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
            $user = User::all();
            return response()->json($user, 200);
        }
    }

    //mostrar un usuario por id 
    public function showById($id)
    {
        if (User::find($id) == null) {
            return response()->json(['message' => 'No hay registros'], 404);
        } else {
            $user = User::find($id);
            return response()->json($user, 200);
        }
    }

    public function edit(Request $request, $id)
    {

        $user = User::find($id);
        if (User::find($id) == null) {
            return response()->json(['message' => 'No se encontro el registro'], 404);
        } else {
            $user->update($request->all());
            return response()->json(
                [
                    'message' => 'Registro actualizado',
                ],
                200
            );
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $campos = [
            'nombre' => 'required|string|max:30',
            'apellidos' => 'required|string|max:70',
            'email' => 'required|string|max:100|unique:users|email',
            'password' => 'required|string|max:100',
        ];
        return reponse()->json([
            'required' => 'El :attribute es requerido'
        ], 404);

        if ($request->hasFile('imagen')) {
            $campos = ['imagen' => 'max:10000|mimes:jpeg,png,jpg'];
        }

        $this->validate($request, $campos, $mensaje);

        $datosUser = request()->except(['_token', '_method']);

        if ($request->hasFile('imagen')) {
            $user = User::findOrFail($id);

            Storage::delete('public/' . $user->imagen);

            $datosUser['imagen'] = $request->file('imagen')->store('uploads', 'public');
        }

        User::where('id', '=', $id)->update($datosUser);

        $user = User::findOrFail($id);
    }

    public function inactivar(Request $str_request, $vint_id)
    {
        $str_user = User::find($vint_id);
        if (User::find($vint_id) == null) {
            return reponse()->json([
                'message' => 'No se encontro el registro'
            ], 404);
        } else {
            $str_user->estado = 0;
            $str_user->save();
            return response()->json([
                'message' => 'Usuario inactivado correctamente'

            ], 200);
        }
    }

    //UTE
    // public function showAllUTE()
    // {
    //     if (DB::table('users')->where('rol', 'UTE')->get()->isEmpty()) {
    //         return response()->json(['message' => 'No hay registros'], 404);
    //     } else {
    //         $user = DB::table('users')->where('rol', 'UTE')->get();
    //         return response ()->json($user, 200);
    //     }
    // }  

    //mostrar usuarios con rol UTE

    public function showAllUTE()
    {
        $datos = User::select('nombre as Nombre', 'apellidos as Apellidos', 'rol as Rol', 'TB_Categoria.descripcion as Categoria')

            ->join('TB_Categoria', 'TB_Categoria.user_id', '=', 'users.id')
            ->where('rol', 'UTE')
            ->get();
        $ute = User::all();
        if (count($ute) > 0) {
            return response()->json([

                $datos,

            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron reportes',
            ], 404);
        }
    }
}
