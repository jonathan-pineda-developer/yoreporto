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
use Dompdf\Dompdf;

class C_AdministradorController extends Controller
{
    public function autorizarAdmin(User $user){
        if (!$user->isAdmin()) {
           return response()->json([
               'message' => 'No tiene permisos para realizar esta acción'
           ], 403);
       }
    }

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
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

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
                'espera' => $reportes_en_espera,
                'aceptados' => $reportes_aceptados,
                'rechazados' => $reportes_rechazados,
                'finalizados' => $reportes_finalizados,
                'total' => $total
            ];
        }

        return [
            'categorias' => $reportes_por_categoria
        ];
    }


    function generarPDF() {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $data = $this->estadistica();
        $html = view('Informe.report', ['reportes_por_categoria' => $data['categorias']])->render();

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
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $bitacora = DB::table('TB_Bitacora')->paginate(10);
        return response()->json([
            'bitacora' => $bitacora
        ], 200);
    }

    public function total_usuarios()
    {
        $usuarios=User::where('rol', 'Ciudadano')->where('estado', '1')->count();
        if($usuarios){
            return response()->json([
                'usuarios' => $usuarios
            ], 200);
        }else{
            return response()->json([
                'message' => 'No hay usuarios'
            ], 404);
        }
    }

    public function  getFiltroBitacora(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'No tiene permisos para realizar esta acción'
            ], 403);
        }

        $query = C_Bitacora::query();

        if ($request->filled('fecha_inicio')) {
            $query->where('modified_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->where('modified_at', '<=', $request->fecha_fin);
        }
        if ($request->filled('reporte_id')) {
            $query->where('reporte_id', $request->reporte_id);
        }
        $bitacoras = $query->get();

        return response()->json(['bitacora' => $bitacoras]);
    }

}
