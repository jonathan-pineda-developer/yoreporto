<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_Marcador extends Model
{
    use HasFactory;

    // tabla de la base de datos
    protected $table = 'TB_Marcador';

    protected $primaryKey = 'id';

    // campos de la tabla
    protected $fillable = [
        'latitud',
        'longitud',
    ];

    // relacion con reporte de uno a uno
    public function reporte()
    {
        return $this->hasOne(C_Reporte::class);
    }
}