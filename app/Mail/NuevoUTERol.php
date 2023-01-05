<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;


class NuevoUTERol extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */

    public $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacionRol.notificacionRolUTE')
            ->subject('Nuevo usuario UTE');
        // ->with(['rol' => $this->user->rol, ]);
    }

    /*
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
    */
}
