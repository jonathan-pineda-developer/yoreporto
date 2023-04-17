<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;
use App\Models\C_Bitacora;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;


class C_AdministradorController extends Controller
{

    // función para obtener la cantidad de reportes finalizados en un mes y año dado
    public function reportes_Finalizados($mes, $anio)
    {
        return C_Reporte::where('estado', 'Finalizado')->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
    }

    // función para obtener la cantidad de reportes en proceso en un mes y año dado
    public function reportes_en_espera($mes, $anio)
    {
        return C_Reporte::where('estado', 'En espera')->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
    }

    // función para obtener la cantidad de reportes aceptados en un mes y año dado
    public function reportes_aceptados($mes, $anio)
    {
        return C_Reporte::where('estado', 'Aceptado')->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
    }
    public function reportes_rechazados($mes, $anio)
    {
        return C_Reporte::where('estado', 'Rechazado')->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
    }

    //función para obtener la cantidad de reportes por categoría en un mes y año dado
    public function reportes_por_categoria($mes, $anio){
    return C_Reporte::select('TB_Categoria.descripcion as Categoria', DB::raw('count(*) as total'))
    ->join('TB_Categoria', 'TB_Categoria.id', '=', 'TB_Reporte.categoria_id')
    ->whereMonth('TB_Reporte.created_at', $mes)
    ->whereYear('TB_Reporte.created_at', $anio)
    ->groupBy('TB_Categoria.descripcion')
    ->get();
    }
    function estadistica($mes, $anio) {
        $categorias = DB::table('TB_Categoria')->get();
        $reportes_por_categoria = [];
        
        foreach ($categorias as $categoria) {
            $reportes_finalizados = C_Reporte::where('estado', 'Finalizado')->where('categoria_id', $categoria->id)->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
            $reportes_en_espera = C_Reporte::where('estado', 'En espera')->where('categoria_id', $categoria->id)->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
            $reportes_aceptados = C_Reporte::where('estado', 'Aceptado')->where('categoria_id', $categoria->id)->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
            $reportes_rechazados = C_Reporte::where('estado', 'Rechazado')->where('categoria_id', $categoria->id)->whereMonth('created_at', $mes)->whereYear('created_at', $anio)->count();
            $total = $reportes_finalizados + $reportes_en_espera + $reportes_aceptados + $reportes_rechazados;
            
            $reportes_por_categoria[] = [
                'Categoria' => $categoria->descripcion,
                'reportes en espera' => $reportes_en_espera,
                'reportes aceptados' => $reportes_aceptados,
                'reportes rechazados' => $reportes_rechazados,
                'reportes finalizados' => $reportes_finalizados,
                'total' => $total
            ];
        }
        
        return [
            'reportes por categoria' => $reportes_por_categoria
        ];
    }
            //funcion para mostrar la bitacora
    public function mostrarBitacora(){
        $bitacora = DB::table('TB_Bitacora')->get();
        return response()->json([
            'bitacora' => $bitacora
        ], 200);
    }
}
