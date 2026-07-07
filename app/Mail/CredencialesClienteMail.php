<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesClienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $correo;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($correo, $password)
    {
        $this->correo = $correo;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tus Accesos al Portal de Kenya')
                    ->view('emails.credenciales');
    }
}
