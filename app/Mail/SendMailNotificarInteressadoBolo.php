<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailNotificarInteressadoBolo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $bolo;

    public function __construct($bolo)
    {
        $this->bolo = $bolo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('raimoreirarodrigues@gmail.com')
                ->subject("Bolo disponível")
                ->view('emails.notificar_interessado_bolo')->with(['bolo'=>$this->bolo]);
    }
}
