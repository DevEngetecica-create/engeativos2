<?php

namespace App\Http\Controllers;

use App\Models\AtivoExernoStatus;
use App\Models\CadastroObra;
use App\Models\ConfiguracaoNotificacaoEmail;
use App\Models\User;
use App\Models\CadastroUsuariosVinculo;
use Illuminate\Http\Request;

class ConfiguracaoNotificacaoEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $notificacoes_emails = ConfiguracaoNotificacaoEmail::with('usuario', 'status', 'obra')
        ->orderBy("id", "desc")
        ->paginate(7);

        // Criar um mapa de ID de usuário para email
        $userIds = [];
        foreach ($notificacoes_emails as $notificacao) {
            if (is_array($notificacao->id_usuario)) {
                $userIds = array_merge($userIds, $notificacao->id_usuario);
            }
        }

        // Remover duplicatas e buscar emails
        $userIds = array_unique($userIds);
        $emails = User::whereIn('id', $userIds)->pluck('email', 'id');

        return view('pages.configuracoes.notifications_emails.index', compact('notificacoes_emails', 'emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $email_usuarios = User::get();
        $obras = CadastroObra::get();

        return view('pages.configuracoes.notifications_emails.create', compact('email_usuarios', 'obras'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notificacao_email = new ConfiguracaoNotificacaoEmail();
        $notificacao_email->nome_grupo  = $request->nome_grupo;
        $notificacao_email->id_status   = $request->id_status;
        $notificacao_email->id_obra     = $request->id_obra;
        $notificacao_email->id_modulo     = $request->id_modulo;
        
        // Obtenha o array de IDs de usuários
        $id_usuarios = $request->id_usuario;
        
        // Remova as aspas duplas de cada elemento do array
        $id_usuarios = array_map(function ($id) {
            return str_replace('"', '', $id);
        }, $id_usuarios);
        
        $notificacao_email->id_usuario = $id_usuarios;
        
        // Tratando o campo metodo
        $metodo = $request->metodo;
        
        // Removendo aspas de cada elemento do array
        $metodo = array_map(function ($metodo_modulo) {
            return str_replace('"', '', $metodo_modulo);
        }, $metodo);
        
        // Convertendo o array para uma string JSON
        $notificacao_email->metodo = json_encode($metodo);
        
        $notificacao_email->save();


        return redirect()->route('notificacoes-email.index')->with('success', 'Notificação cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       // Encontrar a notificação por ID
        $notificacoes_emails = ConfiguracaoNotificacaoEmail::with('usuario', 'status', 'obra')->findOrFail($id);
    
        // Criar um array para armazenar IDs de usuários
        $userIds = [];
    
        // Verificar se id_usuario está no formato de array e mesclar no array de IDs
        if (is_array($notificacoes_emails->id_usuario)) {
            $userIds = array_merge($userIds, $notificacoes_emails->id_usuario);
        } else {
            // Caso não seja um array, transformar em array
            $userIds = explode(',', $notificacoes_emails->id_usuario);
        }
    
        // Remover duplicatas
        $userIds = array_unique($userIds);
    
        // Buscar emails dos usuários
        $usuarios = User::whereIn('id', $userIds)->get(['id', 'email']);
    
        // Buscar imagens dos funcionários relacionados
        $imagens_usuario = CadastroUsuariosVinculo::with('vinculo_funcionario')
                            ->whereIn('id_usuario', $userIds)
                            ->get();
      
       
        // Buscar todas as obras
        $obras = CadastroObra::get();

        //Buscar os status
        $status = AtivoExernoStatus::find($notificacoes_emails->id_status);

        return view('pages.configuracoes.notifications_emails.show', compact('notificacoes_emails', 'usuarios', 'imagens_usuario', 'obras', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Encontrar a notificação por ID
        $notificacoes_emails = ConfiguracaoNotificacaoEmail::with('usuario', 'status', 'obra')->findOrFail($id);

        // Criar um array para armazenar IDs de usuários
        $userIds = [];

        // Verificar se id_usuario está no formato de array e mesclar no array de IDs
        if (is_array($notificacoes_emails->id_usuario)) {
            $userIds = array_merge($userIds, $notificacoes_emails->id_usuario);
        }

        // Remover duplicatas e buscar emails
        $userIds = array_unique($userIds);
        $emails = User::pluck('email', 'id'); // Buscar todos os emails de usuários

        // Buscar todas as obras
        $obras = CadastroObra::get();

        //Buscar os status
        $status = AtivoExernoStatus::find($notificacoes_emails->id_status);

        // Retornar a view com os dados
        return view('pages.configuracoes.notifications_emails.edit', compact('notificacoes_emails', 'emails', 'obras', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Validação dos dados do formulário
        $request->validate([
            'nome_grupo' => 'required|string|max:255',
            'id_status' => 'required|integer',
            'id_obra' => 'required|integer',
            'id_modulo' => 'required|integer',
            'id_usuario' => 'required|array',
            'metodo' => 'required|array',
        ]);

        // Encontrar a notificação por ID
        $notificacao_email = ConfiguracaoNotificacaoEmail::findOrFail($id);

        // Atualizar os campos da notificação
        $notificacao_email->nome_grupo = $request->nome_grupo;
        $notificacao_email->id_status = $request->id_status;
        $notificacao_email->id_obra = $request->id_obra;
        $notificacao_email->id_modulo = $request->id_modulo;

        // Obtenha o array de IDs de usuários
        $id_usuarios = $request->id_usuario;

        // Remova as aspas duplas de cada elemento do array
        $id_usuarios = array_map(function ($id) {
            return str_replace('"', '', $id);
        }, $id_usuarios);

        $notificacao_email->id_usuario = $id_usuarios;
        
       // Tratando o campo metodo
        $metodo = $request->metodo;
        
        // Removendo aspas de cada elemento do array
        $metodo = array_map(function ($metodo_modulo) {
            return str_replace('"', '', $metodo_modulo);
        }, $metodo);
        
        // Convertendo o array para uma string JSON
        $notificacao_email->metodo = json_encode($metodo);
        
        // Salvar as mudanças no banco de dados
        $notificacao_email->save();

        return redirect()->route('notificacoes-email.index')->with('success', 'Notificação atualizada com sucesso.');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notificacao = ConfiguracaoNotificacaoEmail::findOrFail($id);
        $notificacao->delete();
        return redirect()->route('notificacoes-email.index')->with('success', 'Notificação excluída com sucesso.');
    }
}
