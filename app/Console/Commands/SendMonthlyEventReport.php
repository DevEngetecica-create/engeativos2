<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificacoesCalenadrios; // Verifique se o nome está correto
use App\Mail\MonthlyEventReport;
use App\Models\ConfiguracaoNotificacaoEmail;
use App\Models\ConfiguracaoNotificacaoEmailJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMonthlyEventReportJob; // Importar o Job criado

class SendMonthlyEventReport extends Command
{
    protected $signature = 'email:send-monthly-event-report';
    protected $description = 'Enviar relatório mensal de eventos por e-mail nos dias 1º e 2 de cada mês';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Definir a localidade para português
            Carbon::setLocale('pt_BR');

            // Obter a data atual
            $currentDate = Carbon::now();

            // Nome dos meses
            $mesAtual = $currentDate->translatedFormat('F'); // Nome completo do mês atual em português
            $proximoMes = $currentDate->copy()->addMonth()->translatedFormat('F'); // Nome do próximo mês em português

            // Buscar eventos do mês vigente
            $eventsCurrentMonth = NotificacoesCalenadrios::whereYear('end', $currentDate->year)
                ->whereMonth('end', $currentDate->month)
                ->get();

            // Buscar eventos do próximo mês
            $nextMonthDate = $currentDate->copy()->addMonth();
            $eventsNextMonth = NotificacoesCalenadrios::whereYear('end', $nextMonthDate->year)
                ->whereMonth('end', $nextMonthDate->month)
                ->get();

            // Verificar se há eventos para enviar
            if ($eventsCurrentMonth->isEmpty() && $eventsNextMonth->isEmpty()) {
                $this->info('Nenhum evento encontrado para o relatório mensal.');
                return 0;
            }

            // Buscar todas as configurações do grupo LEC
            $conta_usuarios = ConfiguracaoNotificacaoEmail::where('id', 35)->get(); // grupo LEC

            foreach ($conta_usuarios as $configuracao) {
                $send_email = new ConfiguracaoNotificacaoEmailJob();

                // Acessa os campos de cada configuração individualmente
                $send_email->id_grupo = $configuracao->id;

                // Converte o array de IDs em uma string separada por vírgulas
                if (is_array($configuracao->id_usuario)) {
                    $send_email->id_usuarios = implode(',', $configuracao->id_usuario);
                } else {
                    $send_email->id_usuarios = $configuracao->id_usuario; // Se for uma string ou número simples
                }

                $send_email->status = "send";

                $send_email->descricao = "Alerta de vencimento documento: mês vigente";

                // Salvar a nova configuração de envio de e-mail
                $send_email->save();
            }

            // Iterar sobre as configurações e pegar os IDs dos usuários
            foreach ($conta_usuarios as $configuracao) {
                // Verifica se id_usuario já é um array
                $usuarios_ids = is_array($configuracao->id_usuario) ? $configuracao->id_usuario : json_decode($configuracao->id_usuario, true);

                // Verificar se é um array
                if (is_array($usuarios_ids)) {
                    // Buscar todos os usuários cujos IDs estão no array $usuarios_ids
                    $usuarios = User::whereIn('id', $usuarios_ids)->get();

                    // Iterar sobre cada usuário encontrado
                    foreach ($usuarios as $usuario) {
                        $email = $usuario->email; // E-mail do destinatário

                        // Despachar o job para enviar o e-mail via fila
                        SendMonthlyEventReportJob::dispatch(
                            $eventsCurrentMonth,
                            $eventsNextMonth,
                            $mesAtual,
                            $proximoMes,
                            $email
                        );

                        $this->info('Relatório de eventos despachado para ' . $email . '!');
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('Erro ao enviar relatório mensal de eventos: ' . $e->getMessage());
            $this->error('Erro ao enviar relatório mensal de eventos.');
            return 1;
        }

        return 0;
    }
}
