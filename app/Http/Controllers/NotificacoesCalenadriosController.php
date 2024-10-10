<?php

namespace App\Http\Controllers;

use App\Mail\MonthlyEventReport;
use App\Models\AnexoFuncionario;
use App\Models\ConfiguracaoNotificacaoEmail;
use App\Models\ConfiguracaoNotificacaoEmailJob;
use Illuminate\Http\Request;
use App\Models\NotificacoesCalenadrios; // Modelo do seu evento
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class NotificacoesCalenadriosController extends Controller
{
    public function index(Request $request)
    {
        // Sua lógica para a visão do calendário
        return view('pages.calendario.index');
    }

    /*     INSERT INTO events (id_item, nome_modulo, url, start, end, title)
SELECT
	a.id,
    "funcionarios",
    CONCAT("/admin/cadastro/funcionario/show/",a.id_funcionario),
    a.data_validade_doc AS start,
    a.data_validade_doc AS end,
    CONCAT('Venc. do doc. ', a.arquivo, ' do funcionário ', f.nome, ' vence neste dia.') AS title
FROM
    anexos_funcionarios a
INNER JOIN
    funcionarios f ON a.id_funcionario = f.id; */
    public function getEvents()
    {
        $id_obra = Session::get('obra')['id'] ?? null;
        if ($id_obra) {

            $events = NotificacoesCalenadrios::select('id', 'title', 'color', 'start', 'end', 'obs', 'user_id', 'id_item', 'nome_modulo', 'url', 'id_obra')
                ->with('obra') // Carrega a relação 'obra' para evitar N+1 queries
                ->where('id_obra', $id_obra)
                ->get();
        } else {

            $events = NotificacoesCalenadrios::select('id', 'title', 'color', 'start', 'end', 'obs', 'user_id', 'id_item', 'nome_modulo', 'url', 'id_obra')
                ->with('obra') // Carrega a relação 'obra' para evitar N+1 queries
                ->get();
        }

        $calendar_events = [];
        foreach ($events as $event) {
            $calendar_events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'className' => $event->color, // Use 'color' como classe CSS
                'description' => $event->obs,
                'user_id' => $event->user_id,
                'id_item' => $event->id_item,
                'nome_modulo' => $event->nome_modulo,
                'url' => $event->url,


                // Adicione outros campos necessários
            ];
        }

        return response()->json($calendar_events);
    }

    public function alert_email()
    {
        // Obter a data atual
        $currentDate = Carbon::now();

        // Definir a localidade para português
        Carbon::setLocale('pt_BR');

        // Obter a data atual
        $currentDate = Carbon::now();

        // Nome dos meses
        $mesAtual = $currentDate->translatedFormat('F'); // Nome completo do mês atual em português
        $proximoMes = $currentDate->copy()->addMonth()->translatedFormat('F'); // Nome do próximo mês em português

        // Buscar eventos do mês vigente e do próximo mês, com relação 'obra' carregada
        $eventsCurrentMonth = NotificacoesCalenadrios::whereYear('end', $currentDate->year)
            ->whereMonth('end', $currentDate->month)
            ->with('obra', 'anexos') // Carrega a relação 'obra'
            ->orderBy('end', 'asc')
            ->get()
            ->groupBy('obra.codigo_obra');

        $eventsNextMonth = NotificacoesCalenadrios::whereYear('end', $currentDate->copy()->addMonth()->year)
            ->whereMonth('end', $currentDate->copy()->addMonth()->month)
            ->with('obra', 'anexos') // Carrega a relação 'obra'
            ->orderBy('end', 'asc')
            ->get()
            ->groupBy('obra.codigo_obra');

        /* foreach ($eventsCurrentMonth as $key => $docsCurrentMonth) {
            
            
        }
            $docsCurrentMonth = AnexoFuncionario::whereYear('end', $currentDate->year)
            ->whereMonth('end', $currentDate->month)
            ->get();

            $docaNextMonth = AnexoFuncionario::whereYear('end', $currentDate->copy()->addMonth()->year)
            ->whereMonth('end', $currentDate->copy()->addMonth()->month)
            ->with('obra') // Carrega a relação 'obra'
            ->get();
 */
        // Verificar se há eventos para enviar
        if ($eventsCurrentMonth->isEmpty() && $eventsNextMonth->isEmpty()) {
            $this->info('Nenhum evento encontrado para o relatório mensal.');
            return 0;
        }


        $this->enviarEmail($eventsCurrentMonth, $eventsNextMonth);
    }

    protected function enviarEmail($eventsCurrentMonth, $eventsNextMonth)
    {
        // Buscar todas as configurações do grupo LEC
        $conta_usuarios = ConfiguracaoNotificacaoEmail::where('id', 35)->get(); // grupo LEC

        foreach ($conta_usuarios as $configuracao) {
            $send_email = new ConfiguracaoNotificacaoEmailJob;

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
                    Mail::to($email)->send(new MonthlyEventReport($eventsCurrentMonth, $eventsNextMonth, $email));
                }
            }
        }
    }

    public function store(Request $request)
    {
        $event = new NotificacoesCalenadrios();
        $event->title = $request->title;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->color = $request->className;
        $event->obs = $request->description;
        $event->user_id = $request->user_id;
        $event->client_id = $request->client_id;
        $event->save();

        return response()->json(['success' => true, 'event_id' => $event->id]);
    }

    public function update(Request $request, $id)
    {
        $event = NotificacoesCalenadrios::find($id);
        $event->title = $request->title;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->color = $request->className;
        $event->obs = $request->description;
        // Atualize outros campos conforme necessário
        $event->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $event = NotificacoesCalenadrios::find($id);
        $event->delete();

        return response()->json(['success' => true]);
    }
}
