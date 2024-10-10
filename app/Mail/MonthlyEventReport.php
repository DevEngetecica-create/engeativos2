<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonthlyEventReport extends Mailable
{
    use Queueable, SerializesModels;

    public $eventsCurrentMonth;
    public $eventsNextMonth;
    public $emails;

    /**
     * Create a new message instance.
     */
    public function __construct($eventsCurrentMonth, $eventsNextMonth, $emails)
    {
        $this->eventsCurrentMonth = $eventsCurrentMonth;
        $this->eventsNextMonth = $eventsNextMonth;
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

        // Formatar o mês atual e o próximo mês para strings
        $mes_atual = $currentDate->translatedFormat('F');
        $proximo_mes = $currentDate->copy()->addMonth()->translatedFormat('F');

        return $this->view('components.emails.emailVencDocs')
            ->subject('Relatório de Documentos - Mês Atual e Próximo Mês')
            ->with([
                'eventsCurrentMonth' => $this->eventsCurrentMonth,
                'eventsNextMonth' => $this->eventsNextMonth,
                'mes_atual' => $mes_atual,
                'proximo_mes' => $proximo_mes,
                'email' =>$this->emails
            ]);
    }
}
