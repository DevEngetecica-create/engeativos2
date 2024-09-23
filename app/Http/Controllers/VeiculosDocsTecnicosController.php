<?php


namespace App\Http\Controllers;

use App\Interfaces\VeiculosDocsTecnicosRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\VeiculosDocsTecnicos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentExpirationNotification;
use App\Models\ConfiguracaoNotificacaoEmail;
use App\Models\ConfiguracaoNotificacaoEmailJob;
use App\Models\User;
use App\Models\Veiculo;

class VeiculosDocsTecnicosController extends Controller
{
    private $repository;

    public function __construct(VeiculosDocsTecnicosRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        try {

            $docs = $this->repository->index();
            return view('veiculos.partials.docs_tecnicos.index', compact('docs'));
        } catch (\Exception $e) {

            Log::error('Erro ao listar documentos: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao listar documentos');
        }
    }

    public function create($id)
    {

        return view('veiculos.partials.docs_tecnicos.create', compact('id'));
    }

    public function email()
    {

        return view('components.emails.emailDocTecnico');
    }

    public function store(Request $request)
    {

        try {

            $this->repository->store($request->all());

            toastr()->success('Documento cadastrado com sucesso!');

            return redirect()->route('veiculos.show', $request->id_veiculo);
        } catch (\Exception $e) {

            Log::error('Erro ao cadastrar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao cadastrar documento');
        }
    }

    public function edit($id)
    {
        try {

            $doc = $this->repository->edit($id);
            return view('veiculos.partials.docs_tecnicos.edit', compact('doc'));
        } catch (\Exception $e) {

            Log::error('Erro ao editar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao editar documento');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->repository->update($id, $request->all(), $request->file('arquivo'));

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Documento atualizado com sucesso!",
                'type' => 'success'
            );

            return redirect()->route('veiculo.show', $request->veiculo_id . "#abastecimentos")->with($notification);
        } catch (\Exception $e) {

            Log::error('Erro ao atualizar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao atualizar documento');
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            toastr()->success('Documento deletado com sucesso!');
            return redirect()->route('veiculos_docs_tecnicos.index');
        } catch (\Exception $e) {

            Log::error('Erro ao deletar documento: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao deletar documento');
        }
    }

    public function upload(Request $request, $id)
    {
        try {

            $doc_tecnico = $this->repository->upload($id, $request->all(), $request->file('arquivo'));

            Log::info('Adicionado arquivo', ['doc_tecnico' => $doc_tecnico]);

            return response()->json('ok');
        } catch (\Exception $e) {

            Log::error('Erro ao atualizar arquivo', ['error' => $e->getMessage()]);

            return response()->json('ok');
        }
    }


    public function download($id)
    {
        try {
            // Obter o documento
            $doc = $this->repository->index()->where('id', $id)->first();

            if ($doc->arquivo)

                // Executar o download através do método do repositório
                return $this->repository->download($id);

            toastr()->success('Download efetuado com sucesso!');

            return redirect()->back()->withErrors('Erro ao atualizar documento');
        } catch (\Exception $e) {
            Log::error('Erro ao fazer o download: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao fazer o download');
        }
    }

    public function verificarDocumentos()
    {

        Log::info('Função verificarDocumentos foi chamada.');

        // Busca todos os documentos técnicos
        $docs_tecnicos = VeiculosDocsTecnicos::where('status', 'a_vencer')
                                        ->orWhere('status', 'vencido')
                                        ->get();

        foreach ($docs_tecnicos as $doc) {

            if (!empty($doc->data_documento) && !empty($doc->data_validade)) {

                $data_documento = Carbon::parse($doc->data_documento);
                $data_validade = Carbon::parse($doc->data_validade);
                $diferenca_dias = $data_documento->diffInDays($data_validade);

                Log::info("Documento ID: {$doc->id}, Diferença de dias: {$diferenca_dias}");

                if ($diferenca_dias >= 40) {

                    Log::info('Enviando e-mail de alerta (Verde).');
                    $this->enviarEmail($doc, 'Verde', $diferenca_dias);
                } elseif ($diferenca_dias < 39 && $diferenca_dias >= 15) {

                    Log::info('Enviando e-mail de alerta (Amarelo).');
                    $this->enviarEmail($doc, 'Amarelo', $diferenca_dias);
                } elseif ($diferenca_dias < 14 && $diferenca_dias >= 1) {

                    Log::info('Enviando e-mail de alerta (Vermelho).');
                    $this->enviarEmail($doc, 'Vermelho', $diferenca_dias);
                }
            }
        }
    }

    protected function enviarEmail($doc, $nivelAlerta, $diferenca_dias)
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
            $send_email->descricao = "Alerta de vencimento documento: " . $doc->tipo_doc_tecnico->nome_documento;

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
                    Mail::to($email)->send(new DocumentExpirationNotification($email, $doc, $nivelAlerta, $diferenca_dias));
                }
            }
        }
    }
}
