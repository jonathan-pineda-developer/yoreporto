<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoReporte extends Mailable
{
    use Queueable, SerializesModels;

    public $categoria;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacionReporte.notificacionReporte')
            ->subject('Nuevo reporte');
    }
}
