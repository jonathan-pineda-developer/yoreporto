<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;



class C_AdministradorController extends Controller
{
    //hacer un metodo que genere un informe por rango de fecha que digite el usuarioy se mostrara la fecha de realización del reporte, su estado, personal a cargo de su atención y su fecha de cierre en caso de que haya sido finalizado
    public function informe(Request $request){
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $informe = C_Reporte::select('users.nombre as Nombre del UTE', 'TB_Reporte.estado as Estado','TB_Categoria.descripcion as Categoria','TB_Reporte.created_at as Fecha de creacion', 'TB_Reporte.updated_at as Fecha de actualizacion/finalizacion')
        ->join('TB_Categoria', 'TB_Categoria.id', '=', 'TB_Reporte.categoria_id')
        ->join('users', 'users.id', '=', 'TB_Categoria.user_id')
        ->whereBetween('TB_Reporte.created_at', [$request->fecha_inicio, $request->fecha_fin])//
        ->get();
        $reportes = C_Reporte::all();
        if (count($reportes) < 0) {
            return response()->json([
                'message' => 'No se encontraron reportes',
            ], 404);
         
        } else {
            return response()->json([
                'reportes' => $informe
            ], 200);
        }
        }
}
