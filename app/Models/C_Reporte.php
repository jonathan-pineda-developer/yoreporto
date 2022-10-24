<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_Reporte extends Model
{
    use HasFactory;

    // tabla de la base de datos
    protected $table = 'TB_Reporte';

    // relacion con categoria de muchos a uno
    public function categoria()
    {
        return $this->belongsTo(C_Categoria::class);
    }
}
