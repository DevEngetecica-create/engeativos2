<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationDocVencDiario extends Mailable
{
    use Queueable, SerializesModels;

    public $anexos;
    public $emails;

    /**
     * Create a new message instance.
     */
    public function __construct($anexos,  $emails)
    {
        $this->anexos = $anexos;       
        $this->emails = $emails;
    }

    /**
     * Build the message.
     */
    public function build()
    {

        // Definir o locale para português brasileiro
        \Carbon\Carbon::setLocale('pt_BR');

        // Obter a data atual
        $currentDate = Carbon::now();


        return $this->view('components.emails.emailVencDocs')
        ->subject('Documento Vencido')

        ->view('components.emails.emailDiarioVencDocs', [
            'email' => $this->emails,
            'anexos' => $this->anexos,
        ]);
    }
}
