<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UsuarioInactivado;
use App\Mail\UsuarioReactivado;


class C_UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

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

    //actualizar datos e imagen del usuario
    public function update(Request $request, $id)
    {
        $uid = auth()->user()->id;
        $user = User::findOrFail($uid);
        $destino = public_path("storage\\" . $user->imagen);
        if ($user == null) {
            return response()->json([
                'message' => 'No se encontró el registro'
            ], 404);
        } else {

            if ($request->hasFile('imagen')) {
                if (File::exists($destino)) {
                    File::delete($destino);
                }
                $user->imagen = $request->file('imagen')->store('public/usuarios');
            }
            $user->nombre = $request->nombre;
            $user->apellidos = $request->apellidos;
            $user->email = $request->email;
            $user->save();
            if ($user->save()) {
                return response()->json([
                    'message' => 'Usuario actualizado correctamente'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No se pudo actualizar el usuario'
                ], 404);
            }
        }
    }

    //actualizar imagen del usuario
    public function updateImagen(Request $request, $id)
    {
        $uid = auth()->user()->id;
        $user = User::findOrFail($uid);
        $destino = public_path("storage\\" . $user->imagen);
        if ($user == null) {
            return response()->json([
                'message' => 'No se encontró el registro'
            ], 404);
        } else {

            if ($request->hasFile('imagen')) {
                if (File::exists($destino)) {
                    File::delete($destino);
                }
                $user->imagen = $request->file('imagen')->store('public/usuarios');
                $user->imagen = substr($user->imagen, 16);
            }
            $user->save();
            if ($user->save()) {
                return response()->json([
                    'message' => 'Usuario actualizado correctamente'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No se pudo actualizar el usuario'
                ], 404);
            }
        }
    }

    public function getImagenById(Request $request, $id)
    {

        $imagen = $request->id;

        //path de donde se encuentra la imagen public/storage/usuarios/id.extension
        $path = storage_path("app/public/usuarios/" . $imagen);

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            return response()->file(storage_path("app/public/usuarios/default.png"));
        }
    }

    //actualizar solo datos
    public function updateDatos(Request $request, $id)
    {
        $uid = auth()->user()->id;
        $user = User::findOrFail($uid);
        $user->nombre = $request->nombre;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;
        $user->save();
        if ($user->save()) {
            return response()->json([
                'message' => 'Usuario actualizado correctamente'
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se pudo actualizar el usuario'
            ], 404);
        }
    }

    public function inactivar(Request $request, $id)
    {
        //$this->autorizarAdmin(auth()->user());
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $user = User::find($id);
        if (User::find($id) == null) {
            return reponse()->json([
                'message' => 'No se encontró el registro'
            ], 404);
        } else {

            if ($user->estado == 0) {
                return response()->json([
                    'message' => 'El usuario ya se encuentra inactivo'
                ], 404);
            }

            $user->estado = 0;
            $user->save();

            // envio de correo UsuarioInactivado
            Mail::to($user->email)->send(new UsuarioInactivado($user));

            return response()->json([
                'message' => 'Usuario inactivado correctamente'

            ], 200);
        }
    }

    public function reactivar(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $user = User::find($id);
        if (User::find($id) == null) {
            return reponse()->json([
                'message' => 'No se encontró el registro'
            ], 404);
        } else {

            if ($user->estado == 1) {
                return response()->json([
                    'message' => 'El usuario ya se encuentra activo'
                ], 404);
            }

            $user->estado = 1;
            $user->save();

            // envio de correo UsuarioReactivado
            Mail::to($user->email)->send(new UsuarioReactivado($user));

            return response()->json([
                'message' => 'Usuario reactivado correctamente'

            ], 200);
        }
    }

    public function showAllUTE()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $datos = User::select('users.id as Id', 'nombre as Nombre', 'apellidos as Apellidos', 'estado as Estado', 'TB_Categoria.descripcion as Categoria', 'email as Email')
            ->join('TB_Categoria', 'TB_Categoria.user_id', '=', 'users.id')
            ->where('rol', 'UTE')
            ->paginate(6);

        if (count($datos) > 0) {
            // Convertir valor numérico del estado a texto legible
            foreach ($datos as $dato) {
                if ($dato->Estado == 1) {
                    $dato->Estado = 'Activo';
                } else {
                    $dato->Estado = 'Inactivo';
                }
            }

            return response()->json([

                'UTE' => $datos,

                'UTE' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron UTEs registrados',
            ], 404);
        }
    }

    //metodo que me muestre el nombre y apelleidoa de los usuarios con rol UTE y que esten activos

    public function showAllUTEActivos()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $datos = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS Nombre"))
            ->where('rol', 'UTE')
            ->where('estado', 1)
            ->get();

        if ($datos->count() > 0) {
            return response()->json([
                'UTE' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron registros',
            ], 404);
        }
    }

    public function showAllUTEInactivos()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $datos = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS Nombre"))
            ->where('rol', 'UTE')
            ->where('estado', 0)
            ->get();

        if ($datos->count() > 0) {
            return response()->json([
                'UTE' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron registros',
            ], 404);
        }
    }

    public function showAllUTEactivosSinCategoria()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $datos = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS Nombre"))
            ->where('rol', 'UTE')
            ->where('estado', 1)
            ->whereNotIn('id', DB::table('TB_Categoria')->select('user_id'))
            ->get();

        if ($datos->count() > 0) {
            return response()->json([
                'UTE' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron registros',
            ], 404);
        }
    }

    public function mostrar_ciudadano()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $datos = User::select('users.id as Id', 'nombre as Nombre', 'apellidos as Apellidos', 'estado as Estado', 'email as Email')
            ->where('rol', 'Ciudadano')
            ->paginate(6);

        if (count($datos) > 0) {
            // Convertir valor numérico del estado a texto legible
            foreach ($datos as $dato) {
                if ($dato->Estado == 1) {
                    $dato->Estado = 'Activo';
                } else {
                    $dato->Estado = 'Inactivo';
                }
            }

            return response()->json([

                'Ciudadano' => $datos,

                'Ciudadano' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron ciudadanos registrados',
            ], 404);
        }
    }

    //busqueda de usuarios por nombre sensibles a mayusculas y minusculas
    public function search(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $nombre = $request->nombre;
        $datos = User::select('users.id as Id', 'nombre as Nombre', 'apellidos as Apellidos', 'estado as Estado', 'email as Email')
            ->where('rol', 'Ciudadano')
            ->where('nombre', 'LIKE', '%' . $nombre . '%')
            ->get();

        if (count($datos) > 0) {
            // Convertir valor numérico del estado a texto legible
            foreach ($datos as $dato) {
                if ($dato->Estado == 1) {
                    $dato->Estado = 'Activo';
                } else {
                    $dato->Estado = 'Inactivo';
                }
            }

            return response()->json([

                'Ciudadano' => $datos,

                'Ciudadano' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron ciudadanos registrados',
            ], 404);
        }
    }

    //busqueda de usuarios ute por nombre sensibles a mayusculas y minusculas
    public function searchUTE(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $nombre = $request->nombre;
        $datos = User::select('users.id as Id', 'nombre as Nombre', 'apellidos as Apellidos', 'estado as Estado', 'TB_Categoria.descripcion as Categoria', 'email as Email')
            ->join('TB_Categoria', 'TB_Categoria.user_id', '=', 'users.id')
            ->where('rol', 'UTE')
            ->where('nombre', 'LIKE', '%' . $nombre . '%')
            ->get();

        if (count($datos) > 0) {
            // Convertir valor numérico del estado a texto legible
            foreach ($datos as $dato) {
                if ($dato->Estado == 1) {
                    $dato->Estado = 'Activo';
                } else {
                    $dato->Estado = 'Inactivo';
                }
            }

            return response()->json([

                'UTE' => $datos,

                'UTE' => $datos,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se encontraron UTEs registrados',
            ], 404);
        }
    }
}
