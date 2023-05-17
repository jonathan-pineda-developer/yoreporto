<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RechazoReporte extends Mailable
{
    use Queueable, SerializesModels;

    public $motivo, $reporte, $user, $ute, $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($motivo, $reporte, $user, $ute, $id)
    {
        $this->motivo = $motivo;
        $this->reporte = $reporte;
        $this->user = $user;
        $this->ute = $ute;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacionRechazoReporte.notificacionRechazoReporte')
            ->subject('Reporte Rechazado');
    }
}
