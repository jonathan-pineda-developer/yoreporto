<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;


class NuevoUTEContraseña extends Mailable
{
    use Queueable, SerializesModels;

     /**
     * The user instance.
     *
     * @var \App\Models\User
     */

    protected $user;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    //public function build()
    //{
      //  return $this->view('view.name');
    //}

    public function content()
    {
        return new Content(
            view: 'emails.notificacionRol.notificacionRolUTE',
            with: [
                'rol' => $this->user->rol,
            ],
            text: 'emails.notificacionRol.notificacionRolUTE-text'
        );
    }
}
