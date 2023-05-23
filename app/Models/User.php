<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    // tabla de referencia en la base
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'imagen',
        'google',
        'codigo_doble_factor',
        'codigo_doble_factor_expira',
    ];

    protected $dates = [
        'codigo_doble_factor_expira',
    ];


    const ROL_ADMINISTRADOR = 'Administrador';
    const ROL_UTE = 'UTE';
    CONST ROL_CIUDADANO = 'Ciudadano';


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


    public function isAdmin()
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function isUte()
    {
        return $this->rol === self::ROL_UTE;
    }

    public function isCiudadano()
    {
        return $this->rol === self::ROL_CIUDADANO;
    }

    // metodos del jwtsubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    // relacion con reporte de uno a muchos
    public function reporte()
    {
        return $this->hasMany(C_Reporte::class);
    }

    public function generarCodigoDobleFactor()
    {
        $this->timestamps = false;
        $this->codigo_doble_factor = mt_rand(100000, 999999);
        $this->codigo_doble_factor_expira = now()->addMinutes(10);
        $this->save();
    }

    public function resetCodigoDobleFactor()
    {
        $this->timestamps = false;
        $this->codigo_doble_factor = null;
        $this->codigo_doble_factor_expira = null;
        $this->save();
    }
}
