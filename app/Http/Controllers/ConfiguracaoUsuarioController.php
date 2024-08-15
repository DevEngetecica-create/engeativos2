<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoUsuario;
use App\Models\CadastroEmpresa;
use App\Models\CadastroFuncionario;
use App\Models\CadastroUsuariosVinculo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ConfiguracaoUsuarioNiveis as Niveis;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NotificationUser;
class ConfiguracaoUsuarioController extends Controller
{
    public function index(Request $request)
    {

        $usuario = $request->usuario;

        // Se um termo de pesquisa foi fornecido, filtra as categorias pelo nome
        if ($usuario) {
           
            $lista = ConfiguracaoUsuario::select("usuarios_niveis.titulo as nivel", "users.*")
                ->join(
                    "usuarios_vinculos",
                    "usuarios_vinculos.id_usuario",
                    "=",
                    "users.id"
                )
                ->join(
                    "usuarios_niveis",
                    "usuarios_niveis.id",
                    "=",
                    "usuarios_vinculos.id_nivel"
                )
                ->where('name', 'LIKE', "%$usuario%")
                ->orderBy('id', 'desc')
                ->paginate(8);
        } else {
            // Se nenhum termo de pesquisa foi fornecido, obtém todas as categorias

            $lista = ConfiguracaoUsuario::select("usuarios_niveis.titulo as nivel", "users.*")
                ->join(
                    "usuarios_vinculos",
                    "usuarios_vinculos.id_usuario",
                    "=",
                    "users.id"
                )
                ->join(
                    "usuarios_niveis",
                    "usuarios_niveis.id",
                    "=",
                    "usuarios_vinculos.id_nivel"
                )->orderBy('id', 'desc')
                ->paginate(8);
        }

        $permite_excluir = 0;

        
        
        return view('pages.configuracoes.usuario.index', compact('lista', 'permite_excluir'));
    }

    public function create()
    {
        $usuario_niveis = Niveis::all();
        if (\Session::get('obra')['id']) {
            $funcionarios = CadastroFuncionario::where('id_obra', \Session::get('obra')['id'])->orderBy('nome', 'ASC')->get();
        } else {
            $funcionarios = CadastroFuncionario::orderBy('nome', 'ASC')->get();
        }

        return view('pages.configuracoes.usuario.form', compact('usuario_niveis', 'funcionarios'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'id_funcionario' => 'required',
                'password' => 'required|min:5',
                'password_confirm' => 'required|min:5|same:password',
                'nivel' => 'required',
                'status' => 'required'
            ],
            [
                'id_funcionario.required' => 'Escolha um funcionário',
                'password.required' => 'É necessário digitar uma senha que contenha no mínimo 5 caracteres',
                'password_confirm.required' => 'A confirmação de senha é necessária',
                'password_confirm.same' => 'As senhas digitadas não são iguais',
                'nivel.required' => 'Escolha um nível de acesso',
                'status.required' => 'Escolha um status'
            ]
        );

        $funcionario = CadastroFuncionario::find($request->id_funcionario);

        $user = new ConfiguracaoUsuario();
        $user->name = $funcionario->nome;
        $user->password = Hash::make($request->password);
        $user->email = $funcionario->email;
        $user->bloqueado = 1;
        
        if(empty($user->matricula)){
            
            $user->matricula = $funcionario->matricula;
            
        } 
     
        if(empty($user->avatar)){
            
            $user->avatar = "user-dummy-img.jpg";
        } 
        
        
         if (empty($user->email)) {
            return redirect()->route('usuario.adicionar')->with('fail', 'Para cadastrar o usuário é obrigatorio o e-mail do funcionário!!! Acesse o Cadastro de Funcionário e insira o e-mail do funcionario');
        };
        
        if ($user->save()) {
            $user_vinculo = new CadastroUsuariosVinculo();
            $user_vinculo->id_usuario = $user->id;
            $user_vinculo->id_obra  = $funcionario->id_obra ?? null;
            $user_vinculo->id_funcionario  = $funcionario->id;
            $user_vinculo->id_nivel  = $request->nivel ?? 1;
            $user_vinculo->status = $request->status ?? 1;
            $user_vinculo->save();
        };

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | ADD CONFIGURACAO USUARIO: ' . $user->name .' | -> OBRA: ' .  $user_vinculo->id_obra .' | -> NIVEL: ' . $user_vinculo->id_nivel);

        return redirect()->route('usuario')->with('success', 'Um registro foi adicionado com sucesso!');
    }

    public function edit($id = null)
    {
        $store = ConfiguracaoUsuario::with('vinculo')->find($id);
        $usuario_niveis = Niveis::all();
        $empresas = CadastroEmpresa::all();
        $funcionarios = CadastroFuncionario::all();

        if (!$id or !$store) :
            return redirect()->route('usuario')->with('fail', 'Esse registro não foi encontrado.');
        endif;

        //if ($id == Auth::user()->id) :
         //   return redirect()->route('usuario')->with('fail', 'Você não pode modificar seu próprio usuário.');
        //endif;



        return view('pages.configuracoes.usuario.form', compact('store', 'usuario_niveis', 'empresas', 'funcionarios'));
    }

    public function update(Request $request, $id)
    {
        $user = ConfiguracaoUsuario::find($id);
        // $user->name = $request->nome;
        // $user->email = $request->email;
        
        if($request->nivel){
            $user_nivel = CadastroUsuariosVinculo::where('id_usuario', $user->id);
            $user_nivel->update([
                "id_nivel"              => $request->nivel                
            ]);
        }

        if (Auth::user()->user_level == 1) {
            $user->user_level = $request->nivel;
        }

        if (isset($request->password) && isset($request->password_confirm)) {
            if ($request->password === $request->password_confirm) {
                $user->password = Hash::make($request->password);
            } else {
                return redirect()->route('usuario.adicionar')->with('fail', 'As senhas digitadas devem ser iguais.');
            }
        }

        $user->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT CONFIGURACAO USUARIO: ' . $user->name);

        return redirect()->route('usuario')->with('success', 'Um registro foi modificado com sucesso!');
    }

    public function destroy(ConfiguracaoUsuario $id)
    {
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DELETE USUÁRIO : ' . $id->nome);

        CadastroUsuariosVinculo::where('id_usuario', $id)->delete();


        if ($id->delete()) {
            return redirect()->route('usuario')->with('success', 'Usuário excluído com sucesso!');
        } else {
            return redirect()->route('usuario')->with('fail', 'Usuário excluído com sucesso!');
        }
    }
    
    public function bloquear($id)
    {
        $usuario = User::find($id);
        $usuario->bloqueado = true;
        $usuario->update();
        
        $notification = [
                        'title' => 'Atenção!!!',
                        'message' => 'Usuário bloqueado',
                        'type' => 'warning'
                        ];
           
       return back()->with('notification', $notification);
                
    }

    public function desbloquear($id)
    {
        $usuario = User::find($id);
        $usuario->bloqueado = false;
        $usuario->update();
            
        $notification = [
                    'title' => 'Atenção!!!',
                    'message' => 'Usuário liberado',
                    'type' => 'success'
                    ];

        //Envia Notificação por e-mail no endereço para o usuário
        
        $menssagem = "O seu acesso está liberado no sistema SGA-Engeativos";
        
        $usuario->notify(new NotificationUser($usuario->email, $usuario->name, $menssagem, env('APP_URL')));
           
       return back()->with('notification', $notification);
    }
    
}
