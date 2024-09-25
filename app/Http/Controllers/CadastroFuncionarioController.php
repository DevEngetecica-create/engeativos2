<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    AnexoFuncionario,
    CadastroEmpresa,
    CadastroFuncionario,
    CadastroObra,
    CadastroFuncao,
    CadastroFuncionarioSetor,
    ConfiguracaoNotificacaoEmail,
    FerramentalRetirada,
    FuncaoEpi,
    FuncaoFuncionario,
    FuncaoQualificacao,
    FuncionarioQualificacao,
    User
};
use App\Notifications\NotificaManutencao;
use App\Notifications\NotificationCadFuncionario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

use App\Traits\Configuracao;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Foreach_;
use ZipArchive;

class CadastroFuncionarioController extends Controller
{
    private $defaultImageName = "user-dummy-img.jpg";
    
    use Configuracao;
   /**
     * Stores uploaded files and associated data.
     *
     * @param array $files
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CadastroFuncionario $funcionario
     */

    public function index(Request $request)
    {
        $id_obra = Session::get('obra')['id'] ?? null;
    
        // Lógica para obter a lista de funcionários
        $lista = CadastroFuncionario::when(request('funcionario', 'setor') != null, function ($query) {
            return $query->where('nome', 'like', '%' . request('funcionario') . '%');
        })
        ->with('funcao', 'obra', 'qualificacoes')
        ->when($id_obra !== null && $id_obra > 0, function ($query) use ($id_obra) {
            return $query->where('id_obra', $id_obra);
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
    
        // Inicializa as variáveis de contagem
        $contar_situacao_1 = 0;
        $contar_situacao_18 = 0;
    
        // Itera sobre cada funcionário para contar as qualificações
        foreach ($lista as $funcionario) {
            foreach ($funcionario->qualificacoes as $qualificacao) {
                if ($qualificacao->situacao == 1) {
                    $contar_situacao_1++;
                } elseif ($qualificacao->situacao == 18) {
                    $contar_situacao_18++;
                }
            }
        }


    
        return view('pages.cadastros.funcionario.partials.list', compact('lista', 'contar_situacao_1', 'contar_situacao_18'));
    }

    
    public function create()
    {
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();
        $estados = Configuracao::estados();
        $obras = CadastroObra::where('status_obra', 'Ativo')->get();
        $funcoes = FuncaoFuncionario::all();
        $setores = CadastroFuncionarioSetor::get();

        return view('pages.cadastros.funcionario.form', compact('estados', 'obras', 'funcoes', 'setores', 'empresas'));
    }
    
     public function store(Request $request)
    {
        // Validate input data
       $validatedData = $request->validate([
           
        
           'id_obra' => 'required',
            /*'nome' => 'required',
            'data_nascimento' => 'required|date',
            
            */
            'cpf' => 'required|unique:funcionarios,cpf,NULL,id,deleted_at,NULL',
            'celular' => 'required',
           /* 'rg' => 'required',
            'id_funcao' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
            'celular' => 'required',
            'status' => 'required'*/
        ],
        
        [      //'matricula.required' => 'Digite corretamente a matrícula',
                'id_obra.required'          => 'É necessário selecionar uma obra para este Funcionário',
                //'nome.required'             => 'Digite corretamente o nome',
                //'data_nascimento.required'  => 'Data de Nascimento Inválida',
                'cpf.required' => 'Campo CPF é obrigatório',
                'cpf.cpf' => 'Este CPF não é válido',
                'cpf.unique' => 'Este CPF já está cadastrado',
                //'rg.required'               => 'Este RG não é válido',
                /*'id_funcao.required'        => 'É necessário selecionar uma função para este Funcionário',
                'cep.required'              => 'O CEP é indispensável',
                'endereco.required'         => 'Preencha o endereço corretamente',
                'numero.required'           => 'Preencha o número da residência',
                'bairro.required'           => 'Preencha o Bairro corretamente',
                'cidade.required'           => 'Preencha a Cidade corretamente',
                'estado.required'           => 'Selecione o Estado corretamente',              */
                'celular.required'          => 'Digite corretamente o telefone celular / whatsapp',
                //'status.required'           => 'Selecione o Status'
            ]        
        );
   
    
        
                
        // Default image handling
        $defaultImageName = "user-dummy-img.jpg";
        $imagePath = $request->file('imagem_usuario');
        $imageName = $defaultImageName;
        $defaultImagePath = public_path("build/images/users/" . $defaultImageName); // Path to the default image
        $imagePath = $request->file('imagem_usuario');
      
      
        if($request->file('imagem_usuario')){
            
            $imageName = $request->file('imagem_usuario')->getClientOriginalName();
            
        }else{
            
              $imageName = $defaultImageName;
        }
        
        $funcionario = new CadastroFuncionario([
            'matricula'         => $request->matricula,
            'id_obra'           => $request->id_obra,
            'id_setor'           => $request->id_setor,
            'nome'              => $request->nome,
            'data_nascimento'   => $request->data_nascimento,
            'cpf'               => $request->cpf,
            'rg'                => $request->rg,
            'id_funcao'         => $request->id_funcao,
            'cep'               => $request->cep,
            'endereco'          => $request->endereco,
            'numero'            => $request->numero,
            'bairro'            => $request->bairro,
            'cidade'            => $request->cidade,
            'estado'            => $request->estado,
            'email'             => $request->email ?? null,
            'celular'             => $request->celular ?? null,
            'nome_mae'          => $request->nome_mae,
            'pis'               => $request->pis,
            'estado_civil'      => $request->estado_civil,
            'dependentes'       => $request->dependentes,
            'data_adminssao'    => $request->data_adminssao ?? null,
            'data_demissao'     => $request->data_demissao ?? null,
            'situacao'          => 18,
            'imagem_usuario'    => $imageName,
        ]);
    
        $funcionario->save();
        
        $targetDir = public_path("build/images/users/{$funcionario->id}");
        $targetPath = $targetDir . '/' . $defaultImageName;
        
        if ($request->hasFile('imagem_usuario') && $imagePath && $imagePath->isValid()) {
            $imageName = $imagePath->getClientOriginalName(); // Get the original name if the file is valid
            // Move the uploaded image to the desired directory
            $imagePath->move($targetDir, $imageName);
        } else {
            // Check if the default image file exists
            if (file_exists($defaultImagePath)) {
                // Ensure the target directory exists
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true); // Create the directory if it doesn't exist
                }
                // Copy the default image if it doesn't already exist in the target location
                if (!file_exists($targetPath)) {
                    copy($defaultImagePath, $targetPath); // Copy the default image
                }
                $imageName = $defaultImageName; // Use the default image name
            } else {
                // Handle the error if the default image does not exist
                throw new Exception("Default image does not exist.");
            }
        }
    
        // Handling file attachments if any
        if ($request->hasFile('file')) {
            $this->salvarAnexoFuncionario($request->file('file'),  $request->data_conclusao, $request, $funcionario);
        }
    
        // Handling qualifications if any
        if ($request->filled('id_qualificacao')) {
            $this->salvarFuncionarioQualificacoes($request, $funcionario);
        }
    
        $notificacoes_emails = ConfiguracaoNotificacaoEmail::where("id_obra", Session::get('obra')['id'])->get();

        // Criar um mapa de ID de usuário para email
        $userIds = [];
        foreach ($notificacoes_emails as $notificacao) {
            if (is_array($notificacao->id_usuario)) {
                $userIds = array_merge($userIds, $notificacao->id_usuario);
            }
        }

        // Remover duplicatas e buscar emails
        $userIds = array_unique($userIds);

        // Buscar os usuários correspondentes aos IDs
        $usuarios = User::whereIn('id', $userIds)->get();

        if ($request->hasFile('file')) {
            $mensagem_arquivo = "Verfique os arquivos carregados";
        } else {
            $mensagem_arquivo = "Não há arquivos anexados";
        }

        $funcao_funcioario = FuncaoFuncionario::find($funcionario->id_funcao);
        $obra_funcioario = CadastroObra::find($funcionario->id_obra);

        $funcionario_nome = $funcionario->nome;
        $funcao = $funcao_funcioario->funcao;
        $obra = $obra_funcioario->nome_fantasia;
        $url = "https://sga-engeativos.com.br/admin/cadastro/funcionario/show/" . $funcionario->id;

        foreach ($usuarios as $usuario) {
            $usuario->notify(new NotificationCadFuncionario($usuario->email, $usuario->name, $funcionario_nome, $funcao, $obra, $url, $mensagem_arquivo));
        }

        // Prepare notification message
        $notification = [
            'title' => "Atenção!!!",
            'message' => "Cadastro realizado com sucesso!",
            'type' => 'success'
        ];

        // Log activity
        $userLog = Auth::user()->email;
        Log::channel('main')->info("$userLog | ADD FUNCIONÁRIO : {$funcionario->nome} | CPF : {$funcionario->cpf}");
    
        // Redirect with success notification
        return redirect('admin/cadastro/funcionario')->with($notification);
    }
    
    public function salvarAnexoFuncionario($files, $datas_conclusao, $request, $funcionario)
    {
        if ($files) {

            foreach ($files as $index => $file) {

                $tempo_validade = $request->tempo_validade[$index] ?? null;
                $situacao_doc = $request->situacao_doc[$index] ?? 1;
                $data_conclusao = Carbon::parse($datas_conclusao[$index]);
                $data_calculada = $data_conclusao->copy()->addMonths($tempo_validade);
                $dataHoraAtual = Carbon::now()->format('Y-m-d H:i:s');
                $usuario_aprov = ($request->situacao_doc[$index] == 2) ? Auth::user()->email : null;
                $nome_arquivo = $file->getClientOriginalName();

                // Verifica se já existe um anexo para essa qualificação e funcionário
                $anexo_existente = AnexoFuncionario::where('id_funcionario', $funcionario->id)
                    ->where('id_funcao', $request->id_funcao)
                    ->where('id_qualificacao', $request->id_qualificacao[$index])
                    ->first();

                if (!$anexo_existente) {
                    $anexo_funcionario = new AnexoFuncionario([
                        'id_funcionario' => $funcionario->id,
                        'id_funcao' => $request->id_funcao ?? 1,
                        'id_qualificacao' => $request->id_qualificacao[$index],
                        'usuario_cad' => Auth::user()->email,
                        'arquivo' => $nome_arquivo,
                        'nome_arquivo' => $nome_arquivo,
                        'data_conclusao' => $data_conclusao,
                        'data_validade_doc' => $data_calculada,
                        'data_aprovacao' => $dataHoraAtual,
                        'situacao_doc' => $situacao_doc,
                        'usuario_aprov' => $usuario_aprov,
                    ]);

                    $anexo_funcionario->save();
                    $file->storeAs('uploads/usuarios/' . $funcionario->id, $nome_arquivo, 'public');
                }
            }
        }
    }
    
    private function isAccessDenied($nivel_usuario, $id_funcionario_sessao, $id)
    {
        return $nivel_usuario >= 3  && $id_funcionario_sessao != $id && $nivel_usuario != 14 && $nivel_usuario != 15  && $nivel_usuario != 10;
    }
    
    private function denyAccess($id_funcionario_sessao)
    {
        $notification = [
            'title' => "Atenção!",
            'message' => "Acesso negado!!!",
            'type' => 'warning'
        ];
        return redirect()->route('cadastro.funcionario.editar', $id_funcionario_sessao)->with($notification);
    }
    
    private function needsQualificationInfo($nivel_usuario, $id_funcionario_sessao, $id)
    {
        return ($nivel_usuario <= 2 && $id_funcionario_sessao != $id) || //permite editar ou visualizar os dados de outro usuário
               ($nivel_usuario <= 2 && $id_funcionario_sessao == $id) || //permite editar ou visualizar os dados de outro usuário
               ($nivel_usuario == 10 && $id_funcionario_sessao != $id) || //permite editar ou visualizar os dados de outro usuário
               ($nivel_usuario == 14 && $id_funcionario_sessao != $id) || //permite editar ou visualizar os dados de outro usuário
               ($nivel_usuario == 15 && $id_funcionario_sessao != $id) || //permite editar ou visualizar os dados de outro usuário
               ($nivel_usuario >= 3 && $id_funcionario_sessao == $id); //bloqueia visualizar os dados de outro usuário
    }
    
    private function loadAndPresentQualificationInfo($id, $store, $estados, $obras, $funcoes, $empresas, $setores, $view)
    {
        if (!$id || !$store) {
            return redirect('admin/cadastro/funcionario')->with('fail', 'Esse registro não foi encontrado.');
        }
        
        $anexos_funcionarios = AnexoFuncionario::where('id_funcionario', $id)
                                               ->where('id_qualificacao', "=", 0)
                                               ->get();
    

             $qualificacao_funcoes = FuncionarioQualificacao::with('qualificacoes')
                                ->leftJoin('anexos_funcionarios', function ($join) {
                                    $join->on('funcionarios_qualificacoes.id_funcionario', '=', 'anexos_funcionarios.id_funcionario')
                                         ->on('funcionarios_qualificacoes.id_funcao', '=', 'anexos_funcionarios.id_funcao')
                                         ->on('funcionarios_qualificacoes.id_qualificacao', '=', 'anexos_funcionarios.id_qualificacao');
                                })
                                ->where('funcionarios_qualificacoes.id_funcionario', $id)
                                ->where('funcionarios_qualificacoes.id_qualificacao', '!=', 0)
                                ->select('funcionarios_qualificacoes.*', 'anexos_funcionarios.id as id_anexos', 'anexos_funcionarios.nome_arquivo', 'anexos_funcionarios.data_conclusao', 'anexos_funcionarios.data_validade_doc', 'anexos_funcionarios.situacao_doc', 'anexos_funcionarios.usuario_cad', 'anexos_funcionarios.usuario_aprov', 'anexos_funcionarios.data_aprovacao', 'anexos_funcionarios.observacoes')
                                ->get();

                                                        
           //dd($qualificacao_funcoes);                                              
    
        $qualificacao_epis = FuncaoEpi::where('id_funcao', $store->id_funcao)->get();
    
        return view($view, compact(
            'store', 'estados', 'obras', 'funcoes', 'empresas', 'setores','qualificacao_funcoes', 'qualificacao_epis', 'anexos_funcionarios'
        ));
    }
    
    private function loadAndPresentShowInfo($id, $store, $estados, $obras, $funcoes, $empresas, $setores, $view)
    {
        if (!$id || !$store) {
            return redirect('admin/cadastro/funcionario')->with('fail', 'Esse registro não foi encontrado.');
        }
    
        $anexos_funcionarios = AnexoFuncionario::with('situacoes')->where('id_funcionario', $id)
                                               ->where('id_qualificacao' ,"=", 0)
                                               ->get();
    
      $qualificacao_funcoes = FuncionarioQualificacao::with('qualificacoes')
                                ->leftJoin('anexos_funcionarios', function ($join) {
                                    $join->on('funcionarios_qualificacoes.id_funcionario', '=', 'anexos_funcionarios.id_funcionario')
                                         ->on('funcionarios_qualificacoes.id_funcao', '=', 'anexos_funcionarios.id_funcao')
                                         ->on('funcionarios_qualificacoes.id_qualificacao', '=', 'anexos_funcionarios.id_qualificacao');
                                })
                                ->where('funcionarios_qualificacoes.id_funcionario', $id)
                                ->where('funcionarios_qualificacoes.id_qualificacao', '!=', 0)
                                ->select('funcionarios_qualificacoes.*', 'anexos_funcionarios.id as id_anexos', 'anexos_funcionarios.nome_arquivo', 'anexos_funcionarios.data_conclusao', 'anexos_funcionarios.data_validade_doc', 'anexos_funcionarios.situacao_doc', 'anexos_funcionarios.usuario_cad', 'anexos_funcionarios.usuario_aprov', 'anexos_funcionarios.data_aprovacao', 'anexos_funcionarios.observacoes')
                                ->get();
    
        $qualificacao_epis = FuncaoEpi::where('id_funcao', $store->id_funcao)->get();
    
        $itensRetirados = FerramentalRetirada::select('ativos_ferramental_retirada')
                                             ->select(
                                                 'ativos_ferramental_retirada.*',
                                                 'ativos_ferramental_retirada.status as statusRetirada',
                                                 'ativos_ferramental_retirada.created_at as dataRetirada',
                                                 'ativos_ferramental_retirada.data_devolucao',
                                                 'funcionarios.nome as funcionario',
                                                 'funcionarios.matricula as funcionario_matricula',
                                                 'ativos_ferramental_retirada_item.id_ativo_externo as id_ferramenta_retirada',
                                                 'ativos_externos.titulo as nome_ferramenta',
                                                 'ativos_externos_estoque.patrimonio'
                                             )
                                             ->join("funcionarios",  "ativos_ferramental_retirada.id_funcionario", "=", "funcionarios.id")
                                             ->join("ativos_ferramental_retirada_item", "ativos_ferramental_retirada.id", "=", "ativos_ferramental_retirada_item.id_retirada")
                                             ->join("ativos_externos_estoque", "ativos_ferramental_retirada_item.id_ativo_externo", "=", "ativos_externos_estoque.id")
                                             ->join("ativos_externos", "ativos_externos_estoque.id_ativo_externo", "=", "ativos_externos.id")
                                             ->where('ativos_ferramental_retirada.id_funcionario', $id)
                                             ->orderByDesc("ativos_ferramental_retirada_item.created_at")
                                             ->get();
    
        return view($view, compact(
            'store', 'estados', 'obras', 'funcoes', 'setores', 'empresas', 'anexos_funcionarios', 'qualificacao_funcoes', 'qualificacao_epis', 'itensRetirados'
        ));
    }

    public function edit($id)
    {      
        
        $id_funcionario_sessao = session('usuario_vinculo.id_funcionario');
        $nivel_usuario = session('usuario_vinculo.id_nivel');
    
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();
        $store = CadastroFuncionario::with('funcao')->find($id);
        $estados = Configuracao::estados();
        $obras = CadastroObra::where('status_obra', 'Ativo')->get();
        $funcoes = FuncaoFuncionario::all();
        $setores = CadastroFuncionarioSetor::all();

        
    
        if ($this->isAccessDenied($nivel_usuario, $id_funcionario_sessao, $id)) {
            return $this->denyAccess($id_funcionario_sessao);
        }
    
        if ($this->needsQualificationInfo($nivel_usuario, $id_funcionario_sessao, $id)) {
              
            return $this->loadAndPresentQualificationInfo($id, $store, $estados, $obras, $funcoes, $empresas, $setores, 'pages.cadastros.funcionario.form');
            
        }
    
        return redirect('admin/cadastro/funcionario')->with('fail', 'Esse registro não foi encontrado.');
    }


    public function update(Request $request, $id)
    {
        try {
            $funcionario = CadastroFuncionario::findOrFail($id);

            if ($request->file('imagem_usuario') && $request->file('imagem_usuario')->isValid()) {
                $imageName = $request->file('imagem_usuario')->getClientOriginalName();
                $request->file("imagem_usuario")->move(public_path("build/images/users/{$funcionario->id}"), $imageName);
            } else {
                $imageName = $funcionario->imagem_usuario;
            }

            $funcionario->update([
                'matricula'         => $request->matricula,
                'id_obra'           => $request->id_obra,
                'id_setor'           => $request->id_setor,
                'nome'              => $request->nome,
                'data_nascimento'   => $request->data_nascimento,
                'cpf'               => $request->cpf,
                'rg'                => $request->rg,
                'id_funcao'         => $request->id_funcao,
                'cep'               => $request->cep,
                'endereco'          => $request->endereco,
                'numero'            => $request->numero,
                'bairro'            => $request->bairro,
                'cidade'            => $request->cidade,
                'estado'            => $request->estado,
                'email'             => $request->email,
                'celular'           => $request->celular,
                'nome_mae'          => $request->nome_mae,
                'pis'               => $request->pis,
                'estado_civil'      => $request->estado_civil,
                'dependentes'       => $request->dependentes,
                'data_adminssao'    => $request->data_adminssao,
                'data_demissao'     => $request->data_demissao,
                'imagem_usuario'    => $imageName,
            ]);

            // Verificar e salvar anexos e qualificações
            $this->salvarAnexoFuncionario($request->file('file'), $request->data_conclusao, $request, $funcionario);
            
            $this->salvarFuncionarioQualificacoes($request, $funcionario);

            $notification = array(
                'title' => "Atenção!",
                'message' => "Cadastro atualizado com sucesso!",
                'type' => 'success'
            );

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | UPDATED FUNCIONÁRIO: ' . $funcionario->nome);

            return redirect('admin/cadastro/funcionario')->with($notification);
        } catch (Exception $e) {
            $message = $e->getMessage();

            $notification = array(
                'title' => "Atenção!",
                'message' => "Erro ao atualizar o cadastro!",
                'type' => 'warning'
            );

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' Error ao atualizar o funcionario| ' . $funcionario->nome . ' | ' . $message);

            return redirect()->route('cadastro.funcionario.editar', $funcionario->id)->with($notification);
        }
    }
    
    public function show($id)
    {
        $id_funcionario_sessao = session('usuario_vinculo.id_funcionario');
        $nivel_usuario = session('usuario_vinculo.id_nivel');
    
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();
        $store = CadastroFuncionario::with('funcao', 'obra')->where('id', $id)->first();
        $estados = Configuracao::estados();
        $obras = CadastroObra::where('status_obra', 'Ativo')->get();
        $funcoes = FuncaoFuncionario::all();
    
        if ($this->isAccessDenied($nivel_usuario, $id_funcionario_sessao, $id)) {
            return $this->denyAccess($id_funcionario_sessao);
        }
    
        if ($this->needsQualificationInfo($nivel_usuario, $id_funcionario_sessao, $id)) {
            return $this->loadAndPresentShowInfo($id, $store, $estados, $obras, $funcoes, $empresas, 'pages.cadastros.funcionario.show');
        }
    
        return redirect('admin/cadastro/funcionario')->with('fail', 'Esse registro não foi encontrado.');
    }


    public function cad_edi_password_func(Request $request, $id)
    {
        // Encontra o funcionário pelo ID
        $funcionario = CadastroFuncionario::find($id);
        
        // Verifica se o funcionário foi encontrado
        if (!$funcionario) {
            return response()->json(['mensagem' => 'Funcionário não encontrado'], 404);
        }
    
        $password = $funcionario->password;
        $cpf = $funcionario->cpf;
        
        // Verifica se o CPF fornecido corresponde ao CPF do funcionário
        if ($password != null && $request->cpf != $cpf) {
            return response()->json([
                                    'mensagem' => 'CPF inválido',
                                    'icon' => 'warning'        
            
            ]);
        }
    
        // Se a senha já existe, altera a senha
        if ($password != null) {
            $dataHoraAtual = Carbon::now();
            $dataHoraFormatada = $dataHoraAtual->format('Y-m-d H:i:s');
    
            $funcionario->update([
                "password" =>$request->password,
                "data_altera_password" => $dataHoraFormatada,
            ]);
    
            return response()->json(['mensagem' => 'Senha alterada com sucesso']);
        }
    
        // Se a senha não existe, cadastra a senha
        $funcionario->update([
            "password" => $request->password,
        ]);
        
        return response()->json(['mensagem' => 'Senha cadastrada com sucesso']);
    }


    // Função para salvar um anexo de funcionário quando for editar os dados
    public function adicionar_anexos_funcionarios(Request $request)
    {

        if ($request->file == null) {

            $notification = [
                'title' => "Atenção!",
                'message' => "Selecione um arquivo",
                'type' => 'warning'
            ];


            return back()->with('notification', $notification);
        };


        if ($request->file("file")) {

            $files = $request->file("file");

            foreach ($files as $key => $file) {

              
                $nome_arquivo = $request->nome_qualificacao[$key];
                $dataHoraAtual = Carbon::now();
                // Formata a data e hora conforme necessário
                if ($request->situacao_doc) {
                    $situacao_doc = $request->situacao_doc[$key] ?? 1;
                    if ($request->situacao_doc[$key] == 2) {
                        $dataFormatada = $dataHoraAtual->format('Y-m-d H:i:s');
                        $usuario_aprov = Auth::user()->email;
                    } else {

                        $dataFormatada = null;
                        $usuario_aprov = null;
                    }
                }

                $anexo_funcionario = new AnexoFuncionario(
                    [
                        'id_funcionario'    => $request->id_funcionario_anexo,
                        'id_qualificacao'   => 0,
                        'id_funcao'         => $request->id_funcao,
                        'usuario_cad'       => Auth::user()->email,
                        'usuario_aprov'     => $usuario_aprov ?? null,
                        'arquivo'           => $file->getClientOriginalName(),
                        'nome_arquivo'      => $nome_arquivo,
                        'data_conclusao'    => $request->data_conclusao[$key],
                        'data_aprovacao'    => $dataFormatada ?? null,
                        'situacao_doc'      => $situacao_doc ?? 1,
                    ]
                );

                $anexo_funcionario->save();

                $file->storeAs('public/uploads/usuarios/' . $request->id_funcionario, $file->getClientOriginalName());
            }
        }


        if ($request->modulo == "show_funcionario") {

            $notification = [
                'title' => "Sucesso!!!",
                'message' => "Arquivos adicionados com sucesso!!!",
                'type' => 'success'
            ];

            return redirect()->route('cadastro.funcionario.show', $anexo_funcionario->id_funcionario)->with('notification', $notification);
        } else {

            $notification = [
                'title' => "Sucesso!!!",
                'message' => "Arquivos adicionados com sucesso!!!",
                'type' => 'success'
            ];
            return redirect()->route('cadastro.funcionario.editar', $anexo_funcionario->id_funcionario)->with('notification', $notification);
        }
    }

    public function destroy(CadastroFuncionario $id)
    {
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DELETE FUNCIONÁRIO : ' . $id->nome . ' | CPF : ' . $id->cpf);

        if ($id->delete()) {
            return redirect('admin/cadastro/funcionario')->with('success', 'Funcionário excluído com sucesso!');
        } else {
            return redirect('admin/cadastro/funcionario')->with('fail', 'Funcionário excluído com sucesso!');
        }
    }

    public function aprovar_documentos(Request $request)
    {

        $aprovar_documento = AnexoFuncionario::find($request->id);
        
       // dd($request->id);
        
        $formattedDate = Carbon::now()->format('d/m/Y h:m:s');
        $data_hora_atual = $formattedDate;
        $motivo = $request->motivoReprovacao ? $aprovar_documento->observacoes . "<p class='m-0 p-2 text-success'>" . $data_hora_atual . ": " . $request->motivoReprovacao . "</p><hr class='m-0 p-0'>" : '';

        //Envia Notificação por e-mail no endereço para nova Manutencão
        /* $email_config =  User::where('id', $aprovedOrcamento->id_user_solicitante)->first();

        //id da manutenção
        $id_manutencao = $id;

        //patrimonio e nome da verramenta
        $nomeFerramenta = $patrimonio->patrimonio . " - " . $aprovedOrcamento->nomeAtivo;

        //mensagem de envio
        $menssagem = " O orçamento da ferramenta {$nomeFerramenta} foi apvoddo para enviar para manutenção";

        // dd($email_config . ' | ' . $id_manutencao.  ' | ' . $method. ' | ' .$nomeFerramenta );

        $email_config->notify(new NotificaManutencao($email_config->email, $id_manutencao, $menssagem));

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | APROVOU O DOCUMENTO: ' . $aprovedOrcamento->id);
        
 */
//

        if ($request->selectValue == 2) {
            
            //dd($request->selectValue);
            $aprovar_documento->update([
                'situacao_doc'   => 2,
                'usuario_aprov' => Auth::user()->email
            ]);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Documento aprovado com sucesso!!!",
                'type' => 'success'
            );
            
        }else if ($request->selectValue == 18) {
            $aprovar_documento->update([
                'situacao_doc'   => 18,
                'usuario_reprov' => Auth::user()->email,
                'observacoes' => $motivo
            ]);

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Documento pendente!!!",
                'type' => 'warning'
            );
            
        }else {
            $aprovar_documento->update([
                'situacao_doc'   => 1,
                'usuario_reprov' => Auth::user()->email,
            ]);

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Documento está pendnete!!!",
                'type' => 'warning'
            );
        }

        $data = [$aprovar_documento, $notification];

        return response()->json($data);
    }

    public function obter_motivo($id)
    {
        $aprovar_documento = AnexoFuncionario::find($id);
        return response()->json(['motivoReprovacao' => $aprovar_documento->observacoes ?? ""]);
    }

    public function download($id)
    {
        //dd($id);
        if ($id) {
            
            // Procurar o registro pelo ID
            $anexoFuncionario = AnexoFuncionario::find($id);
            $download_documento = $anexoFuncionario->arquivo;
            $id_funcionario = $anexoFuncionario->id_funcionario;
    
            // Verificar se o registro foi encontrado
            if ($anexoFuncionario) {
               
                
                //dd($id_funcionario);
    
                    // Fazer o download do arquivo
                    $path = 'public/uploads/usuarios/' . $id_funcionario . '/' . $download_documento;
                    $path_sem_id = 'public/uploads/usuarios/' . $download_documento;
        
                    if (Storage::exists($path)) {
                        
                        return Storage::download($path);
                          $notification = array(
                            'title' => "Atenção!",
                            'message' => "Download efetuado com sucesso.",
                            'type' => 'success'
                        );
                        
                         return redirect()->route('cadastro.funcionario.show', $id_funcionario."#projects")->with($notification);
                        
                    } else {
                        
                        // Trate o caso em que o arquivo não existe
                        return Storage::download($path_sem_id);
                        
                        $notification = array(
                        'title' => "Atenção!",
                        'message' => "'Download efetuado porem não foi possivel encontrar a pasta'",
                        'type' => 'error'
                                );
                            }
                             // Redirecionar com mensagem de erro
                        return redirect()->route('cadastro.funcionario.show', $id_funcionario."#projects")->with($notification);
                        
                    }
                    
               
        }
    }

    
    public function downloads_zip($id)
    {

        $nome_funcionario = CadastroFuncionario::find($id);

        $directoryPath = storage_path('app/public/uploads/usuarios/' . $id);

        // Verifica se o diretório existe
        if (!File::exists($directoryPath)) {

            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Não existe documentos cadastrados para este Funcionário!!!",
                'type' => 'warning'
            );

            return redirect()->route('cadastro.funcionario.show', $id)->with($notification);
        } else {

            // Substituir todos os espaços em branco por "_"
            $nomeSubstituido = str_replace(" ", "_", $nome_funcionario->nome);

            // Converter todas as letras para minúsculas
            $nomeMinusculo = strtolower($nomeSubstituido);

            // Remover todos os caracteres especiais, mantendo apenas letras, números e "_"
            $nomeFinal = preg_replace('/[^a-z0-9_]/', '', $nomeMinusculo);


            $zip = new ZipArchive;

            $file_name = $nomeFinal . ".zip";

            if ($zip->open($file_name, ZipArchive::CREATE)) {

                $files = File::files(storage_path('app/public/uploads/usuarios/' . $id));

                foreach ($files as $file) {

                    $nameInZipFfile = basename($file);

                    $zip->addFile($file, $nameInZipFfile);
                }

                $zip->close();
            }

            return response()->download($file_name);

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Download realizado com sucesso",
                'type' => 'success'
            );

            return redirect()->route('cadastro.funcionario.show', $id)->with($notification);
        }
    }

    public function excluir_anexos_funcionarios(Request $request, $id)
    {

        $anexos = AnexoFuncionario::find($id);

         if (File::exists("public/uploads/usuarios/{$anexos->id_funcionario}/" . $anexos->arquivo)) {

            File::delete("public/uploads/usuarios/{$anexos->id_funcionario}/" . $anexos->arquivo);
     }

        //dd($anexos->id_funcionario);

        if ($anexos->id_funcionario) {

            if ($request->modulo == "show_funcionario") {

                $anexos->delete();

                $notification = array(
                    'title' => "Sucesso!!!",
                    'message' => "Arquivo excluido com sucesso!!!",
                    'type' => 'success'
                );

                return redirect()->back()->with($notification);
            } else {

                $anexos->delete();

                $notification = array(
                    'title' => "Sucesso!!!",
                    'message' => "Arquivo excluido com sucesso!!!",
                    'type' => 'success'
                );
                return redirect()->route('cadastro.funcionario.editar', $anexos->id_funcionario)->with($notification);
            }
        } else {

            $notification = array(
                'title' => "Sucesso!!!",
                'message' => "Arquivo excluido com sucesso!!!",
                'type' => 'warning'
            );
            return redirect()->route('cadastro.funcionario.editar', $anexos->id_funcionario)->with($notification);
        }
    }
    
    public function salvarFuncionarioQualificacoes($request, $funcionario)
    {
        $id_qualificacoes = $request->id_qualificacao;
        $tempo_validades = $request->tempo_validade;

        if ($id_qualificacoes) {
            foreach ($request->id_qualificacao as $index => $id_qualificacao) {
                $situacao_doc = $request->situacao_doc[$index] ?? 1;
                $tempo_validade = $tempo_validades[$index];

                // Verifica se já existe uma qualificação para essa função e funcionário
                $qualificacao_existente = FuncionarioQualificacao::where('id_funcionario', $funcionario->id)
                    ->where('id_funcao', $request->id_funcao)
                    ->where('id_qualificacao', $id_qualificacao)
                    ->whereNotNull('deleted_at')
                    ->first();

                if (!$qualificacao_existente) {
                    $qualificacao_funcionario = new FuncionarioQualificacao([
                        'id_funcionario' => $funcionario->id,
                        'id_funcao' => $request->id_funcao,
                        'id_qualificacao' => $id_qualificacao,
                        'tempo_validade' => $tempo_validade,
                        'situacao' => $situacao_doc,
                        'usuario' => Auth::user()->email,
                    ]);

                    $qualificacao_funcionario->save();
                }
            }
        }
    }
    
    // Função para salvar um anexo de funcionário quando for editar os dados
    public function editar_anexos_funcionarios(Request $request)

    {
        
        $dataHoraAtual = Carbon::now();
        $dataFormatada = $dataHoraAtual->format('Y-m-d H:i:s');

        $editar_data_conclusao = Carbon::parse($request->data_conclusao);
        $data_calculado = $editar_data_conclusao->addMonths($request->tempo_validade);

      
         if($request->acao_cadastrar_editar == "editar_qualificacao" ){
          
        
            if ($request->file == null && $request->data_conclusao == null) {
                return response()->json([
                    'notification' => [
                        'title' => 'Atenção!',
                        'message' => 'Selecione um arquivo ou insira uma data de conclusão.',
                        'type' => 'warning'
                    ]
                ]);
            }
            
              
    
            $editar_anexo_funcionario = AnexoFuncionario::find($request->id_anexo);
            
            if($request->file){
            
                $file = $request->file;
            }
        
    
                $editar_anexo_funcionario->update([
                    'usuario_cad'       => Auth::user()->email,
                    'arquivo'           => $file->getClientOriginalName(),
                    'nome_arquivo'      => $file->getClientOriginalName(),
                    'data_conclusao'    => $request->data_conclusao,
                    'data_aprovacao'    => $dataFormatada,
                    'data_validade_doc' => $data_calculado,
                ]);
    
    
              $file->storeAs('public/uploads/usuarios/' . $request->id_funcionario_anexo, $file->getClientOriginalName());
    
                return response()->json([
                    'message' => [
                        'title' => 'Sucesso!!!',
                        'message' => 'Arquivos adicionados com sucesso!!!',
                        'type' => 'success'
                    ],
                    'data' => [
                        $editar_anexo_funcionario
                    ]
                    
                ]);
            
    
            return response()->json([
                'notification' => [
                    'title' => 'Erro!',
                    'message' => 'Ocorreu um erro ao tentar salvar o arquivo.',
                    'type' => 'error'
                ]
            ]);
            
         } else {            
            
            // Cadastrando novo arquivo
            
            if ($request->file) {
                
                $file = $request->file;
                
                $salvar_anexo_funcionario = new AnexoFuncionario([
                    'id_funcionario' => $request->id_funcionario_anexo,
                    'id_qualificacao' => $request->id_qualificacao,
                    'id_funcao' => $request->id_funcao_anexo,
                    'usuario_cad' => Auth::user()->email,
                    'arquivo' => $file->getClientOriginalName(),
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'data_conclusao' => $request->data_conclusao,
                    'data_validade_doc' => $data_calculado,
                    'situacao_doc' => 1,
                ]);
                
                $salvar_anexo_funcionario->save();
                
               $file->storeAs('public/uploads/usuarios/' . $request->id_funcionario_anexo, $file->getClientOriginalName());
    
                return response()->json([
                    'message' => [
                        'title' => 'Sucesso!!!',
                        'message' => 'Arquivos adicionados com sucesso!!!',
                        'type' => 'success'
                    ],
                    'data' => $salvar_anexo_funcionario
                ]);
            }
    
                return response()->json([
                    'notification' => [
                        'title' => 'Erro!',
                        'message' => 'Ocorreu um erro ao tentar salvar o arquivo.',
                        'type' => 'error'
                    ]
                ]);
            }
    }
    
    
    public function consultar_qualificacao(Request $request)
    {
        $consulta = [];
        
//dd($request->all());


        $id_qualificacao = $request->id_qualificacao;
        $data_conclusao = $request->data_conclusao;
        $id_funcionario = $request->id_funcionario;
        $id_funcao = $request->id_funcao;
        $editar_data_conclusao = $request->editar_data_conclusao;
        $id_anexo = $request->id_anexo;


        if ($request->id_funcionario or $request->id_funcionario != "") {

            $validator = Validator::make($request->all(), [
                'id_funcionario' => 'required|integer|exists:funcionarios,id',
                'id_funcao' => 'required|integer|exists:funcionarios_qualificacoes,id', // Corrigi aqui para a tabela correta de funções
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors(),
                    'type' => 'error'
                ], 422);
            }
        }

        try {
            // Verifica se há necessidade de alterar a função e excluir documentos anteriores
            $update_funcao = FuncionarioQualificacao::where("id_funcionario", $id_funcionario)->get();

            foreach ($update_funcao as $funcao) {
                if ($funcao->id_funcao != $id_funcao) {
                    $consulta = [
                        'message' => "Atenção você está alterando a função deste funcionário. Com isso, a documentação anterior será excluída",
                        'type' => 'warning'
                    ];

                    return response()->json($consulta);
                }
            }
            
            

            // Calcula a validade do certificado
            if ($request->data_conclusao) {
                
                

                $data_calculado = FuncaoQualificacao::where('id', $id_qualificacao)
                    ->orderBy('id')
                    ->first();

                //dd($data_calculado);

                if ($data_calculado) {
                    $data_conclusao = Carbon::parse($data_conclusao);
                    $data_calculado = $data_conclusao->addMonths($data_calculado->tempo_validade);

                    return response()->json(['data_calculado' => $data_calculado->format('d/m/Y')]);
                }
            }
            
            

            // Calcula a validade do certificado
            if ($request->editar_data_conclusao) {

            // dd($request->all());
               
                $existeAnexo = AnexoFuncionario::where('id', $id_anexo)->where('id_funcionario', $id_funcionario)->exists();

                if ($existeAnexo) {
                    
                    $data_calculado = AnexoFuncionario::with('qualificacoes')->find($id_anexo);
                    $data_calculado = $data_calculado->qualificacoes;
                    
                } else {
                    
                    //dd($request->all());
                    
                    $data_calculado = FuncionarioQualificacao::find($id_anexo);
                }
            
                if ($data_calculado) {
                    
                    $editar_data_conclusao = Carbon::parse($editar_data_conclusao);
                    
                    $data_calculado = $editar_data_conclusao->addMonths($data_calculado->tempo_validade);
            
                    return response()->json(['data_calculado' => $data_calculado->format('d/m/Y')]);
                }
            
                return response()->json(['error' => 'Não foi possível calcular a data de validade'], 400);
                
                
            }

            if ($id_anexo) {  
                
                if($request->acao_cadastrar_editar == "editar_qualificacao" ){
                 
                 //dd($request->all());

                $anexo = AnexoFuncionario::find($id_anexo);
//dd($anexo);
                return response()->json([
                    'id_anexo' => $anexo->id,
                    'nome_qualificacao' => $anexo->qualificacao->nome_qualificacao,
                    'id_qualificacao' => $anexo->id_qualificacao,
                    'tempo_validade' => $anexo->qualificacao->tempo_validade,
                    'data_conclusao' => $anexo->data_conclusao,
                    'situacao_doc' => $anexo->situacao_doc
                ]);
                
                    
                }elseif($request->acao_cadastrar_editar == "cadastrar_qualificacao" ){
                    
                     // Obtém as qualificações obrigatórias para a nova função
                    $anexo = FuncionarioQualificacao::find($id_anexo);
                    
                    //dd($anexo);
                    
                    return response()->json([
                    'id_anexo' => $anexo->id,
                    'nome_qualificacao' => $anexo->qualificacoes->nome_qualificacao,
                    'id_qualificacao' => $anexo->id_qualificacao,
                    'id_funcao_qualificacao' => $anexo->qualificacoes->id_qualificacao,
                    'tempo_validade' => $anexo->qualificacoes->tempo_validade,
                    'situacao_doc' => $anexo->situacao_doc
                ]);
                    
                
                }
                
            }

            // Obtém as qualificações obrigatórias para a nova função
            $informacoes = FuncaoQualificacao::where('id_funcao', $id_funcao)
                ->orderBy('id')
                ->get();

            $consulta = [
                'message' =>  $informacoes,
                'type' => 'success'
            ];

            return response()->json($consulta);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao processar sua solicitação',
                'error' => $e->getMessage(),
                'type' => 'error'
            ], 500);
        }
    }

    public function excluir_qualificacao(Request $request)
    {
        $id_funcionario = $request->id_funcionario;

        try {
            // Excluir registros relacionados ao id_funcionario
            FuncionarioQualificacao::where('id_funcionario', $id_funcionario)->delete();

            return response()->json([
                'message' => 'Registros excluídos com sucesso!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao tentar excluir os registros.',
                'type' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    
    
}






















































