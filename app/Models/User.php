<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable;

    // tabla de referencia en la base
    protected $table = 'users';
    protected $primaryKey = 'id';
    // campos que se pueden llenar
    protected $fillable = [
        'nombre', 'apellidos',
        'correo', 'password'
    ];
    /*

        PRUEBAS DE Verdad

        protected $fillable = [
        'nombre', 'apellidos',
        'correo', 'password', 'imagen',
        'rol_id', 'estado', 'cantidad_reportes',
        'google'
    ];
    */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // metodos del jwtsubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
