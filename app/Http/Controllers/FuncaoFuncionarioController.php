<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\CadastroFuncionario;
use App\Models\FuncaoFuncionario;
use App\Models\FuncaoQualificacao;
use App\Models\CustomLog;

use App\Notifications\NotificatioEmail;
use App\Helpers\Tratamento;
use App\Models\Estoque;
use App\Models\FuncaoEpi;
use App\Models\AnexoFuncionario;
use App\Models\CadastroFuncionarioSetor;
use App\Models\FuncionarioQualificacao;
use App\Models\Notification;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FuncaoFuncionarioController extends Controller
{
    public function index()
    {
        $funcoes = FuncaoFuncionario::when(request('funcao', 'funcionarios', 'setor') != null, function ($query) {
            return  $query->where('funcao', 'like', '%' . request('funcao') . '%');
        })
            ->with('funcionarios', 'setor')
            ->orderBy('id', 'desc')
            ->paginate(7);

        $contar_funcao = CadastroFuncionario::select('funcao_funcionarios.funcao', DB::raw('count(*) as total'))
            ->join('funcao_funcionarios', 'funcionarios.id_funcao', '=', 'funcao_funcionarios.id')
            ->groupBy('funcao_funcionarios.funcao')
            ->orderBy('total', 'desc')
            ->get();


        return view('pages.cadastros.funcionario.funcoes.index', compact('funcoes', 'contar_funcao'));
    }

    public function create()
    {
        $setores = CadastroFuncionarioSetor::all();

        return view('pages.cadastros.funcionario.funcoes.create', compact('setores'));
    }

    public function store(Request $request)
    {
        try {

            //cadastrar a função
            $create_funcao = new FuncaoFuncionario(
                [
                    'codigo' => $request->codigo ?? 0,
                    'funcao' => $request->funcao,
                    'id_setor' => $request->id_setor
                ]
            );

            $create_funcao->save();

            if ($request->nome_qualificacao) {

                $request->validate(
                    [
                        'nome_qualificacao'     => 'required',
                        'tempo_validade'        => 'required'
                    ],

                    [
                        "nome_qualificacao.required"  => "Por favor, insira o nome do documento",
                        "tempo_validade.required"     => "Por favor, a validade do documento em meses(ex.: 3, 6, 9)"
                    ]

                );

                //cadastrar as qualificiações de acordo com o ID da função
                $nome_qualificacao = $request->nome_qualificacao;
                $data_validade = $request->tempo_validade;

                // Iterar sobre os arrays e salvar os dados individualmente
                for ($i = 0; $i < count($nome_qualificacao); $i++) {

                    $qualificacao = new FuncaoQualificacao();
                    $qualificacao->id_funcao = $create_funcao->id;
                    $qualificacao->nome_qualificacao = $nome_qualificacao[$i];
                    $qualificacao->tempo_validade = $data_validade[$i];
                    $qualificacao->situacao = 18;
                    $qualificacao->save();
                }
            }

            if ($request->epi) {
                //Salvar os EPIS obrigatórios para a Função
                foreach ($request->epi as $key => $id_epi) {

                    $epi = new FuncaoEpi();
                    $epi->id_estoque = $id_epi;
                    $epi->id_funcao = $create_funcao->id;
                    // $epi->cert_aut = $request->cert_aut[$key]; // Certifique-se de acessar o índice correto de $cert_aut
                    $epi->save();
                }
            }

            //criar a mensagem da notificação do cadastro
            $menssagem = "A funcão " . $request->funcao . " acabou de ser cadastrada no sistema";
            $link = env('APP_URL') . "cadastro/funcionario/funcoes/show/" .  $create_funcao->id;

            //cadatra a notificação
            $notication = new Notification(
                [
                    "id_servico"    => $create_funcao->id,
                    "id_obra"       => Session::get('obra')['id'],
                    "tipo"          => 'cadastro_funcao',
                    "mensagem"      => $menssagem,
                    "usuario"       => Auth::user()->email,
                    "status"        => "unread",
                    "link_acesso"   => $link
                ]
            );

            $notication->save();

            //Registra o log
            $detalhes = Auth::user()->email .  ' | ADD FUNCAO FUNCIONARIO: ' . $request->funcao . date('Y-m-d H:i:s');
            Log::channel('main')->info($detalhes);

            //salvar os dados na tabela de log
            $customLog = new CustomLog(
                [
                    'id_usuario'    => Auth::user()->id,
                    'id_modulo'     => 8,
                    'metodo'        => 'create',
                    'tipo'          => 'success',
                    'descricao'     => $detalhes,
                    'ip_acesso'     => Tratamento::getRealIpAddr(), // captura o ip de quem cadastrou
                    'detalhes'      => $detalhes
                ]
            );

            $customLog->save();

            //exibe a mensagem de sucesso
            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Cadastro efetuado com sucesso !!!",
                'type' => 'success'
            );

            //redireciona para a pagina de funções          

            return redirect()->route('cadastro.funcionario.funcoes.show', $create_funcao->id)->with($notification);
        } catch (Exception $e) {

            // se der error salva o log
            //salvar os dados na tabela de log
            $customLog = new CustomLog(
                [
                    'id_usuario'    => Auth::user()->id,
                    'id_modulo'     => 8,
                    'metodo'        => 'create',
                    'tipo'          => 'error',
                    'ip_acesso'     => Tratamento::getRealIpAddr(),
                    'detalhes' => Auth::user()->email . ' | ' . $e
                ]
            );

            $customLog->save();

            //exibe a notificação do err
            $notification = array(
                'title' => "Error!!!",
                'message' => "Erro ao salvar!!!",
                'type' => 'error'
            );

            return redirect()->route('cadastro.funcionario.funcoes.index')->with($notification);
        }
    }

    public function edit($id)
    {
        $funcao = FuncaoFuncionario::find($id);

        $qualificacoes = FuncaoQualificacao::where('id_funcao', $funcao->id)->get();

        $epis_funcao = FuncaoEpi::where('id_funcao', $funcao->id)->get();
        $epis = collect(); // Cria uma coleção vazia para acumular todos os epis

        foreach ($epis_funcao as $funcao_epi) {
            $epis_individual = Estoque::where('id', $funcao_epi->id_estoque)->get();
            $epis = $epis->merge($epis_individual); // Acumula os resultados em uma única coleção
        }

        $setores = CadastroFuncionarioSetor::all();

        return view('pages.cadastros.funcionario.funcoes.edit', compact('funcao', 'qualificacoes', 'epis_funcao', 'epis', 'setores'));
    }

    public function show($id)
    {
        $funcao = FuncaoFuncionario::find($id);

        $qualificacoes = FuncaoQualificacao::with('funcao')->where('id_funcao', $funcao->id)->get();

        $lista_epis = FuncaoEpi::with('funcao')->where('id_funcao', $funcao->id)->get();

        $funcionarios = CadastroFuncionario::where('id_funcao', $id)->get();

        $setores = CadastroFuncionarioSetor::all();

        return view('pages.cadastros.funcionario.funcoes.show', compact('funcao', 'qualificacoes', 'lista_epis', 'funcionarios', 'setores'));
    }

    public function update(Request $request, $id)
    {

        $update_funcao = FuncaoFuncionario::findOrFail($id);
        //cadastrar a função

        $update_funcao->update(
            [
                'codigo' => $request->codigo ?? 0,
                'funcao' => $request->funcao,
                'id_setor' => $request->id_setor
            ]
        );

        if ($request->nome_qualificacao) {

            $request->validate(
                [
                    'nome_qualificacao'     => 'required',
                    'tempo_validade'        => 'required'
                ],

                [
                    "nome_qualificacao.required"  => "Por favor, insira o nome do documento",
                    "tempo_validade.required"     => "Por favor, a validade do documento em meses(ex.: 3, 6, 9)"
                ]

            );

            $nome_qualificacao = $request->nome_qualificacao;
            $data_validade = $request->tempo_validade;

            $id_estoque = $request->epi;


            $update_qualificacoes = FuncaoQualificacao::where('id_funcao', $id)->get();

            foreach ($nome_qualificacao as $index => $nome) {

                $qualificacaoExistente = $update_qualificacoes->where('nome_qualificacao', $nome)->first();

                if ($qualificacaoExistente) {

                    // Atualizar os registros que já existem das qualificações
                    $qualificacaoExistente->tempo_validade = $data_validade[$index];
                    $qualificacaoExistente->save();
                } else {

                    // Salvar os novos registros
                    $qualificacao = new FuncaoQualificacao();
                    $qualificacao->id_funcao = $id;
                    $qualificacao->nome_qualificacao = $nome;
                    $qualificacao->tempo_validade = $data_validade[$index];
                    $qualificacao->situacao = 18;
                    $qualificacao->save();
                }
            }

            //criar a mensagem da notificação do cadastro
            $menssagem = "A funcão  " . $request->funcao . " acabou de ser editada no sistema";
            $link = env('APP_URL') . "cadastro/funcionario/funcoes/show/" .  $update_funcao->id;

            //cadatra a notificação
            $notication = new Notification(
                [
                    "id_servico"    => $update_funcao->id,
                    "id_obra"       => Session::get('obra')['id'],
                    "tipo"          => 'edicao_funcao',
                    "mensagem"      => $menssagem,
                    "usuario"       => Auth::user()->email,
                    "status"        => "unread",
                    "link_acesso"   => $link
                ]
            );

            $notication->save();

            //Registra o log
            $detalhes = Auth::user()->email .  ' | ADD FUNCAO FUNCIONARIO: ' . $request->funcao . date('Y-m-d H:i:s');
            Log::channel('main')->info($detalhes);

            //salvar os dados na tabela de log
            $customLog = new CustomLog(
                [
                    'id_usuario'    => Auth::user()->id,
                    'id_modulo'     => 8,
                    'metodo'        => 'create',
                    'tipo'          => 'success',
                    'descricao'     => $detalhes,
                    'ip_acesso'     => Tratamento::getRealIpAddr(), // captura o ip de quem cadastrou
                    'detalhes'      => $detalhes
                ]
            );

            $customLog->save();

            //exibe a mensagem de sucesso
            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Cadastro efetuado com sucesso !!!",
                'type' => 'success'
            );
        }

        // Atualizar o EPI ou salvar um novo EPI
        $update_epi = FuncaoEpi::where('id_funcao', $id)->get();

        foreach ($id_estoque as $key => $id_epi) {

            // Verificar se o EPI já está cadastrado na função do funcionário
            $epiExistente = $update_epi->where('id_estoque', $id_epi)->first();

            if ($epiExistente) {
                // Atualizar os registros que já existem dos EPIs
                $epiExistente->id_estoque = $id_epi;
                $epiExistente->id_funcao = $id;
                //$epiExistente->cert_aut = $cert_aut[$key]; // Certifique-se de acessar o índice correto de $cert_aut
                $epiExistente->save();

                //exibe a mensagem de sucesso
                $notification = array(
                    'title' => "Sucesso!!!",
                    'message' => "Cadastro efetuado com sucesso !!!",
                    'type' => 'success'
                );

                return redirect()->back()->with($notification);
            } else {
                // Salvar os novos registros
                $epi = new FuncaoEpi();
                $epi->id_estoque = $id_epi;
                $epi->id_funcao = $id;
                //$epi->cert_aut = $cert_aut[$key]; // Certifique-se de acessar o índice correto de $cert_aut
                $epi->save();
            }
        }

        //$userLog = Auth::user()->email;
        //Log::channel('main')->info($userLog . ' | EDIT FUNCAO FUNCIONARIO: ' . $save->funcao);

        return redirect()->route('cadastro.funcionario.funcoes.index')->with('success', 'Registro atualizado com sucesso.');
    }

    public function destroy(FuncaoFuncionario $funcao)
    {
        $funcionarios = CadastroFuncionario::where('id_funcao', $funcao->id)->count();

        if ($funcionarios > 0) {
            return redirect()->route('cadastro.funcionario.funcoes.index')->with('fail', 'Há funcionários com essa função');
        } else {
            $funcao->delete();
            return redirect()->route('cadastro.funcionario.funcoes.index')->with('success', 'Função deletada com sucesso.');
        }
    }

    public function fastStore(Request $request)
    {
        $data = $request->all();
        $data['status'] = 'Ativo';
        $save = FuncaoFuncionario::create($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD OBRA RÁPIDO: ' . $save->razao_social . ' | CÓDIGO OBRA : ' . $save->codigo_obra);

        if ($save) {
            return redirect()->back()->with('success', 'Um registro foi adicionado com sucesso!');
        } else {
            return redirect()->back()->with('fail', 'Um erro impediu o cadastro.');
        }
    }

    public function delete_epi($id)
    {
        $epi = FuncaoEpi::find($id);

        if ($epi) {

            $epi->delete();  // Marca o registro como deletado, atualizando o deleted_at

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | EPI DELETADO DA FUNCAO: ' . $epi->id);

            $notification = [
                'title' => "Sucesso!!!",
                'message' => "Exclusão efetuada com sucesso!!",
                'type' => 'success'
            ];

            return redirect()->back()->with($notification);
        } else {

            $notification = [
                'title' => "Atenção!!!",
                'message' => "EPI não encontrado.",
                'type' => 'warning'
            ];

            return redirect()->back()->with($notification);
        }
    }

    public function delete_funcao($id)
    {
        $qualificacao = FuncaoQualificacao::find($id);


        if ($qualificacao) {


            $qualificacao->delete();  // Exclui a qualificação da fução

            //excluir todos os anexos
            $anexos = AnexoFuncionario::where('id_qualificacao', $id)
                ->where('id_funcao', $qualificacao->id_funcao)
                ->get();

            foreach ($anexos as $anexo) {
                $anexo->delete();
            }

            // excluir todas as qualificações
            $qualificacoes_funcionarios = FuncionarioQualificacao::where('id_qualificacao', $id)
                ->where('id_funcao', $qualificacao->id_funcao)
                ->get();

            foreach ($qualificacoes_funcionarios as $qualificacao_funcionario) {

                $qualificacao_funcionario->delete();
            }


            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | QUALIFICAÇÃO DELETADO DA FUNCAO: ' . $qualificacao->id);

            $notification = [
                'title' => "Sucesso!!!",
                'message' => "Exclusão efetuada com sucesso!!",
                'type' => 'success'
            ];

            return response()->json($notification);
        } else {

            $notification = [
                'title' => "Sucesso!!!",
                'message' => "Função não encontrada!!",
                'type' => 'warning'
            ];

            return response()->json($notification);
        }
    }
}
