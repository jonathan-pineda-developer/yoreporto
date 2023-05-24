<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        if ($user->estado == 1) {
            return $next($request);
        }

        // delete the users token
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Usuario inactivo'], 401);
    }
}
