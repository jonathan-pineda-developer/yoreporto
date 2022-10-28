<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

require_once '../vendor/autoload.php';

class C_AuthController extends Controller
{

    public function googleSignIn(Request $request)
    {
        $client = new \Google_Client(['client_id' => env('GOOGLE_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($request->token);
        if ($payload) {
            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
            return response()->json([
                'ok' => true,
                'status' => 'success',
                'message' => 'Login Success',
                'data' => $payload
            ], 200);
        } else {
            // Invalid ID token
            return response()->json([
                'status' => 'error',
                'message' => 'Login Failed',
                'data' => null
            ], 401);
        }
    }

    public function registro(Request $request)
    {

        // validacion del request
        $this->validate($request, [
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'correo' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:16',
            //  'imagen' => 'string|max:100000|mimes:jpg,png',
            //'google' => 'boolean',
        ]);

        // crear usuario
        $user = User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            // 'imagen' => $request->imagen,
            // 'google' => $request->google
        ]);

        // token
        $token = JWTAuth::fromUser($user);

        // respuesta en json
        return response()->json([
            'message' => 'Usuario creado correctamente',
            'user' => $user,
            'token' => $token // retornamos el token
        ], 201);
    }


    public function login(Request $request)
    {
        // validacion del request
        $this->validate($request, [
            'correo' => 'required|email',
            'password' => 'required|string|min:6|max:16',
        ]);

        // credenciales
        $credenciales = $request->only('correo', 'password');

        try {
            // si no existe el token para el usuario
            if (!$token = JWTAuth::attempt($credenciales)) {
                return response()->json([
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se pudo crear el token'
            ], 500);
        }

        // si todo es correcto
        return response()->json(compact('token'));
    }

    public function logout(Request $request)
    {
        // validacion del request
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'message' => 'Sesion cerrada correctamente'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se pudo cerrar la sesion'
            ], 500);
        }
    }
}
