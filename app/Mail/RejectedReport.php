<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RechazoReporte extends Mailable
{
    use Queueable, SerializesModels;

    public $motivo, $reporte, $user, $ute;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($motivo, $reporte, $user, $ute)
    {
        $this->motivo = $motivo;
        $this->reporte = $reporte;
        $this->user = $user;
        $this->ute = $ute;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacionRechazoReporte.rejected_report')
            ->subject('Reporte Rechazado');
    }
}
