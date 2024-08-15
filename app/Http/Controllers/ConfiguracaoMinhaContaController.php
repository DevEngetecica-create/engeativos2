<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ConfiguracaoUsuarioNiveis as Niveis;
Use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConfiguracaoMinhaContaController extends Controller
{

    public function index()
    {
        $usuario_niveis = Niveis::all();

        return view('pages.configuracoes.minhaconta.index', compact('usuario_niveis'));
    }

    public function store(Request $request)
    {
        $id_usuario = Auth::user()->id;

        if ($id_usuario) {

            $user = User::find($id_usuario);
            $user->name = $request->nome;

            if (Auth::user()->user_level == 1) {
                $user->user_level = $request->nivel;
            }

            if(isset($request->password) && isset($request->password_confirm)){
                if($request->password === $request->password_confirm){
                    $user->password = Hash::make($request->password);
                } else {
                    return redirect()->route('minhaconta')->with('fail', 'Problemas para alterar o registro');
                }
            }

            $user->save();

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | STORE MINHA CONTA: ' . $user->name);

            return redirect()->route('minhaconta')->with('success', 'Registro modificado com sucesso.');

        } else {
            return redirect()->route('minhaconta')->with('fail', 'Problemas para alterar o registro');
        }
    }

}
