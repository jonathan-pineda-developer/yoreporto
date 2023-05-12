<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReporteACiudadano extends Mailable
{
    use Queueable, SerializesModels;

    public $reporte, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reporte, $user)
    {
        $this->reporte = $reporte;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacionReporteACiudadano.notificacionReporteACiudadano')
            ->subject('Reporte realizado exitosamente!');
    }
}
