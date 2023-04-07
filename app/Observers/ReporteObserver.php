<?php

namespace App\Observers;
use App\Models\C_Reporte;
use App\Models\C_Categoria;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class ReporteObserver
{
    /**
     * Handle the reporte "updated" event.
     *
     * @param  \App\Models\C_Reporte  $reporte
     * @return void
     */
    public function updated1(C_Reporte $reporte,$request)
    {
        $this->logOperation('Rechazado', $reporte, $request);
    }
    public function updated(C_Reporte $reporte)
    {
        $this->logOperation1('Aceptado', $reporte);
    }
    public function logOperation(string $operation, C_Reporte $reporte, $request)
    {
        // Traer el nombre completo del UTE que realizó la operación y está logueado
        $user_id = auth()->user()->id;
        $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
                   ->where('id', $user_id)
                   ->where('rol', 'UTE')
                   ->value('nombre_completo');
        $reporte_id = $reporte->id;
        $modified_at = $reporte->updated_at;
    
        // Determinar el valor de la operación en función del estado del reporte
        if($reporte->estado === 'Aceptado'){
            $operation = 'Aceptado';
        }elseif($reporte->estado === 'Rechazado'){
            $operation = 'Rechazado';
        }else{
            $operation = 'Finalizado';
        }
    
        DB::table('TB_Bitacora')->insert([
            'operation' => $operation,
            'justificacion' => $request,
            'ute' => $ute,
            'reporte_id' => $reporte_id,
            'modified_at' => $modified_at,
        ]);
    }
    public function logOperation1(string $operation, C_Reporte $reporte)
    {
        // Traer el nombre completo del UTE que realizó la operación y está logueado
        $user_id = auth()->user()->id;
        $ute = User::select(DB::raw("CONCAT(nombre, ' ', apellidos) AS nombre_completo"))
                   ->where('id', $user_id)
                   ->where('rol', 'UTE')
                   ->value('nombre_completo');
        $reporte_id = $reporte->id;
        $modified_at = $reporte->updated_at;
    
        // Determinar el valor de la operación en función del estado del reporte
        if($reporte->estado === 'Aceptado'){
            $operation = 'Aceptado';
        }elseif($reporte->estado === 'Rechazado'){
            $operation = 'Rechazado';
        }else{
            $operation = 'Finalizado';
        }
    
        DB::table('TB_Bitacora')->insert([
            'operation' => $operation,
            'ute' => $ute,
            'reporte_id' => $reporte_id,
            'modified_at' => $modified_at,
        ]);
    }
    
}
