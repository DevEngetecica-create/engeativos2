<?php

namespace App\Console\Commands;

use App\Http\Controllers\VeiculosDocsTecnicosController;
use Illuminate\Console\Command;

class VerificarDocumentosCommand extends Command
{
    protected $signature = 'verificar:documentos';
    protected $description = 'Verifica os documentos e envia notificações';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        app(VeiculosDocsTecnicosController::class)->verificarDocumentos();
        $this->info('Verificação de documentos executada!');
    }
}
