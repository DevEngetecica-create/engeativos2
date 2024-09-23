<?php

namespace App\Mail;

use App\Helpers\Tratamento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentExpirationNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $doc;
    private $nivelAlerta;
    private $diferenca_dias;
    private $email;

    public function __construct($email, $doc, $nivelAlerta, $diferenca_dias)
    {
        $this->email = $email;
        $this->doc = $doc;
        $this->nivelAlerta = $nivelAlerta;
        $this->diferenca_dias = $diferenca_dias;
    }

    public function build()
    {
        return $this->view('components.emails.emailDocTecnico')
                    ->subject('Alerta de Expiração de Documento')
                    ->with([
                        'email' => $this->email,
                        'documento' => $this->doc->tipo_doc_tecnico->nome_documento,
                        'data_validade' => Tratamento::dateBr($this->doc->data_validade),
                        'nivelAlerta' => $this->nivelAlerta,
                        'diferenca_dias' => $this->diferenca_dias,
                    ]);
    }
}