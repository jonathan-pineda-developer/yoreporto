<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C_Reporte extends Model
{
    use HasFactory;
    use HasUuids;

    // tabla de la base de datos
    protected $table = 'TB_Reporte';

    protected $primaryKey = 'id';

    // campos de la tabla
    protected $fillable = [
        'id',
        'titulo',
        'descripcion',
        'imagen',
        'categoria_id',
        'user_id',
        'latitud',
        'longitud',
        'estado',

    ];

    // relacion con categoria de muchos a uno
    public function categoria()
    {
        return $this->belongsTo(C_Categoria::class);
    }

    // relacion con usuario de muchos a uno
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // relacion con estado de muchos a uno
    public function estado()
    {
        return $this->belongsTo(C_Estado_Reporte::class);
    }

    // relacion con marcador de uno a uno
    public function marcador()
    {
        return $this->belongsTo(C_Marcador::class);
    }
}
