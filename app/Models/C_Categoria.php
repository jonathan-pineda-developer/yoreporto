<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

class C_Categoria extends Model
{
    use HasFactory;

    // tabla de la base de datos
    protected $table = 'TB_Categoria';

    // llave primaria
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'descripcion',
        'color',
    ];

    // relacion con reporte de uno a muchos
    public function reporte()
    {
        return $this->hasMany(C_Reporte::class);
    }
    // relacion con usuario de uno a muchos
    /*
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
