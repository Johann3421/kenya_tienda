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
        $fromAddress = config('mail.from.address') ?: config('mail.mailers.smtp.username') ?: 'prueba@kenya.com.pe';
        $fromName = config('mail.from.name') ?: 'Kenya';

        return $this->from($fromAddress, $fromName)
                    ->subject('Tus Accesos al Portal de Kenya')
                    ->view('emails.credenciales');
    }
}
