<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CategoriaUTE extends Mailable
{
    use Queueable, SerializesModels;


    public $user, $categoria;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $categoria)
    {
        $this->user = $user;
        $this->categoria = $categoria;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacionCategoriaUTE.notificacionCategoriaUTE')
            ->subject('Asiganacion de categoria');
    }
}
