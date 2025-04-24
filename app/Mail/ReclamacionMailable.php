<?php

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReclamacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $archivo;
    public $nombreArchivo;

    public function __construct($archivo, $nombreArchivo)
    {
        $this->archivo = $archivo;
        $this->nombreArchivo = $nombreArchivo;
    }

    public function build()
    {
        return $this->subject('Reclamación enviada')
            ->text('emails.reclamo_simple')
            ->attach($this->archivo, [
                'as' => $this->nombreArchivo,
                'mime' => 'application/pdf',
            ]);
    }
}
