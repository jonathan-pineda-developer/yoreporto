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
    public function updated(C_Reporte $reporte)
    {
        $this->logOperation('updated', $reporte);

    }
    private function logOperation(string $operation, C_Reporte $reporte)
    {
        
        //traer el nombre del ute que realizo la operacion y está logueado
        $user_id = auth()->user()->id;
        $ute = DB::table('users')->where('id', $user_id)->value('nombre');
        $reporte_id = $reporte->id;
        $modified_at = $reporte->updated_at;

        // Determinar el valor de la operación en función del estado del reporte
        $operation = $reporte->estado === 'Aceptado' || $reporte->estado === 'Rechazado'
        ? $reporte->estado
        : 'Modificado';
        
        DB::table('TB_Bitacora')->insert([
            'operation' => $operation,
            'ute' => $ute,
            'reporte_id' => $reporte_id,
            'modified_at' => $modified_at,
        ]);}
}
