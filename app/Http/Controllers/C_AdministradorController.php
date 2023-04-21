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
    public function reportes_Finalizados()
    {
        return C_Reporte::where('estado', 'Finalizado')->count();
    }

    // función para obtener la cantidad de reportes en proceso en un mes y año dado
    public function reportes_en_espera()
    {
        return C_Reporte::where('estado', 'En espera')->count();
    }

    // función para obtener la cantidad de reportes aceptados en un mes y año dado
    public function reportes_aceptados()
    {
        return C_Reporte::where('estado', 'Aceptado')->count();
    }
    public function reportes_rechazados()
    {
        return C_Reporte::where('estado', 'Rechazado')->count();
    }


    function estadistica() {
        $categorias = DB::table('TB_Categoria')->get();
        $reportes_por_categoria = [];
        
        foreach ($categorias as $categoria) {
            $reportes_finalizados = C_Reporte::where('estado', 'Finalizado')->where('categoria_id', $categoria->id)->count();
            $reportes_en_espera = C_Reporte::where('estado', 'En espera')->where('categoria_id', $categoria->id)->count();
            $reportes_aceptados = C_Reporte::where('estado', 'Aceptado')->where('categoria_id', $categoria->id)->count();
            $reportes_rechazados = C_Reporte::where('estado', 'Rechazado')->where('categoria_id', $categoria->id)->count();
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


function generarPDF() {
    $data = $this->estadistica();
    $html = view('Informe.report', ['reportes_por_categoria' => $data['reportes por categoria']])->render();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $output = $dompdf->output();

    return response()->streamDownload(
        function() use ($output) {
            echo $output;
        },
        'estadisticas.pdf'
    );
}


    //funcion para mostrar la bitacora
    public function mostrarBitacora(){
        $bitacora = DB::table('TB_Bitacora')->get();
        return response()->json([
            'bitacora' => $bitacora
        ], 200);
    }
}
