<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_Estado_Reporte extends Model
{
    use HasFactory;

    // tabla de la base de datos
    protected $table = 'TB_Estado_Reporte';

    // relacion con reporte de uno a muchos
    public function reporte()
    {
        return $this->hasMany(C_Reporte::class);
    }
}
