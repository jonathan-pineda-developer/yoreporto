<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\NuevoUTERol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecuperacionContrasenia;
use App\Mail\CodigoAutentificacion;

require_once '../vendor/autoload.php';
class C_AuthController extends Controller
{
    // Get $id_token via HTTPS POST.
    public function googleSignIn(Request $request)
    {
        $client = new \Google_Client(['client_id' => env('GOOGLE_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($request->token);
        //desestructuramos el payload
        $email = $payload['email'];
        $name = $payload['name'];
        $apellidos = $payload['name'];
        $picture = $payload['picture'];
        $userDB = User::where('email', $email)->first();
        if (!$userDB) {
            $user = new User();
            $user->nombre = $name;
            $user->email = $email;
            $user->apellidos = $apellidos;
            $user->password = Hash::make($email);
            $user->imagen = $picture;
            $user->google = 1;
        } else {
            $user = $userDB;
            $user->google = 1;
            $user->save();
        }
        if ($userDB->estado != 1) {
            return response()->json([
                'message' => 'Usuario inhabilitado comuniquese con el administrador'
            ], 401);
        }
        $token = JWTAuth::fromUser($user);


        if ($payload) {
            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
            return response()->json([
                'ok' => true,
                'status' => 'success',
                'message' => 'Login Success',
                //desestructuramos el payload
                'email' => $email,
                'name' => $name,
                'picture' => $picture,
                'token' => $token
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

    //nuevo token
    public function renew(Request $request)
    {
        //respuesta del usuario
        $user = JWTAuth::toUser($request->token);
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);

        JWTAuth::setToken($newToken)->toUser();

        return response()->json([
            'ok' => true,
            'status' => 'success',
            'message' => 'Token Refreshed',
            'token' => $newToken,
            'user' => $user
        ], 200);
    }

    //token existe
    // public function renew(Request $request){
    //     $token = JWTAuth::getToken();
    //     $tokenExists = JWTAuth::check($token);
    //     return response()->json([
    //         'ok' => true,
    //         'status' => 'success',
    //         'message' => 'Token Exists',
    //         'tokenExists' => $token
    //     ], 200);
    // }

    public function getUsuarios()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }
        $usuarios = User::all();
        return response()->json($usuarios);
    }

    public function registro(Request $request)
    {

        // validacion del request
        $this->validate($request, [
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:16',
            'rol' => 'string',
        ]);

        // verificar si el email ya existe
        $emailARegistrar = $request->email;

        $emailExistente = User::where('email', $emailARegistrar)->first();

        if ($emailExistente) {
            return response()->json([
                'message' => 'El email que intenta registrar ya existe, por favor intente con otro'
            ], 400);
        }

        // crear usuario
        $user = User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol
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
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:16',
        ]);

        // credenciales
        $credenciales = $request->only('email', 'password');

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

        // user que esta haciendo login en base al email
        $user = User::where('email', $request->email)->first();

        if ($user->estado != 1) {
            return response()->json([
                'message' => 'Usuario inhabilitado comuniquese con el administrador'
            ], 401);
        }

        // check si el usuario tiene un rol distinto a Ciudadano necesitara autentificacion por doble factor
        if ($user->rol == "UTE") {
            $user->generarCodigoDobleFactor();
            Mail::to($user->email)->send(new CodigoAutentificacion($user));
            return response()->json([
                'message' => 'Se ha enviado un código de autentificación a su correo electrónico',
                'token' => $token,
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Login correcto',
                'token' => $token,
                'user' => $user
            ], 200);
        }

        // si todo es correcto
        return response()->json(compact('token'));
    }

    // verificar codigo de autentificacion por doble factor
    public function verificarCodigoDobleFactor(Request $request)
    {

        // validacion del request
        $this->validate($request, [
            'codigo' => 'required|string',
        ]);

        $user = User::where('codigo_doble_factor', $request->codigo)->first();

        // verificarcion del codigo
        if ($user) {
            // verificar si el codigo esta expirado en este momento
            if ($user->codigo_doble_factor_expira < now()) {
                $user->resetCodigoDobleFactor();
                return response()->json([
                    'message' => 'El código ha expirado, por favor vuelva a solicitar uno',
                ], 400);
            }
            $user->resetCodigoDobleFactor();
            return response()->json([
                'message' => 'Código correcto',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Código incorrecto',
            ], 400);
        }
    }

    // reenviar codigo de autentificacion por doble factor al id del usuario que trae el url
    public function reenviarCodigoDobleFactor(Request $request, $id)
    {
        $user = User::find($id);
        $user->generarCodigoDobleFactor();
        Mail::to($user->email)->send(new CodigoAutentificacion($user));
        return response()->json([
            'message' => 'Se ha enviado un código de autentificación a su correo electrónico',
        ], 200);
    }

    //reenviarCodigo por email
    public function reenviarCodigoDobleFactorEmail(Request $request)
    {
        // validacion del request
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        $user->generarCodigoDobleFactor();
        Mail::to($user->email)->send(new CodigoAutentificacion($user));
        return response()->json([
            'message' => 'Se ha enviado un código de autentificación a su correo electrónico',
        ], 200);
    }

    //registro UTE
    public function registro_UTE(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }
        // validacion del request
        $this->validate($request, [
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
        ]);

        // verificar si el email ya existe
        $emailARegistrar = $request->email;

        $emailExistente = User::where('email', $emailARegistrar)->first();

        if ($emailExistente) {
            return response()->json([
                'message' => 'El email que intenta registrar ya existe, por favor intente con otro'
            ], 400);
        }

        // crear usuario
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
        $password = substr($random, 0, 10);
        $emailpass = $password;

        $user = User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        $user->rol = "UTE";
        $user->save();

        // token
        $token = JWTAuth::fromUser($user);

        // envio de correo de bienvenida con usuario y contraseña
        Mail::to($user->email)->send(new NuevoUTERol($user, $emailpass));

        // respuesta en json
        return response()->json([
            'message' => 'Usuario creado correctamente',
            'user' => $user,
            'token' => $token // retornamos el token
        ], 201);
    }

    // recuperar contraseña, recibe el email y envia un correo donde accede a la pagina para cambiar la contraseña
    public function solicitudRecuperacionContrasenia(Request $request)
    {
        // validacion del request
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        // verificar si el email ya existe
        $emailARegistrar = $request->email;

        $user = User::where('email', $emailARegistrar)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Correo enviado correctamente, revise su bandeja de entrada para recuperar su contraseña'
            ], 400);
        }

        // envio de correo de bienvenida con usuario y contraseña
        Mail::to($user->email)->send(new RecuperacionContrasenia($user));

        // respuesta en json
        return response()->json([
            'message' => 'Correo enviado correctamente, revise su bandeja de entrada para recuperar su contraseña',
        ], 201);
    }

    // cambiar contraseña de usuario del id que se recibe
    public function cambiarContrasena(Request $request, $id)
    {

        // validacion del request
        $this->validate($request, [
            'contrasenaActual' => 'required|string',
            'contrasenaNueva' => 'required|string',
            'contrasenaConfirmar' => 'required|string',
        ]);
        $user = User::find($id);
        if ($user == null) {
            return response()->json([
                'message' => 'No se encontró el registro'
            ], 404);
        } else {
            //si la contraseña actual es igual a la contraseña del usuario
            if (Hash::check($request->contrasenaActual, $user->password)) {
                //si la contraseña nueva es igual a la contraseña confirmar
                if ($request->contrasenaNueva == $request->contrasenaConfirmar) {
                    $user->password = Hash::make($request->contrasenaNueva);
                    $user->save();
                    return response()->json([
                        'message' => 'Contraseña cambiada correctamente',
                    ], 201);
                } else {
                    return response()->json([
                        'message' => 'Las contraseñas no coinciden'
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'La contraseña actual no es correcta'
                ], 400);
            }
        }
    }
    //olvido contraseña password y confirmarPassword
    public function olvidoContrasenia(Request $request, $id)
    {

        // validacion del request
        $this->validate($request, [
            'password' => 'required|string',
            'confirmarPassword' => 'required|string',
        ]);
        $user = User::find($id);

        if ($request->password != $request->confirmarPassword) {
            return response()->json([
                'message' => 'Las contraseñas no coinciden'
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();


        // respuesta en json
        return response()->json([
            'message' => 'Contraseña cambiada correctamente',
        ], 201);
    }
}
