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

    static $rules = [
		'descripcion' => 'required|string|max:30',
		'user_id' => 'required',
    ];

    protected $fillable = [
        'descripcion',
        'user_id'
    ]; 
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    // relacion con reporte de uno a muchos
    public function reporte()
    {
        return $this->hasMany(C_Reporte::class);
    }
}
