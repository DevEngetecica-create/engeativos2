<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CadastroEmpresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Traits\Configuracao;

class CadastroEmpresaController extends Controller
{
    use Configuracao;

    public function index(Request $request)
    {
        $empresa = $request->empresa;

        // Se um termo de pesquisa foi fornecido, filtra as categorias pelo nome
        if ($empresa) {

            $lista = CadastroEmpresa::where('razao_social', 'LIKE', "%$empresa%")
                ->orderBy('id', 'asc')
                ->paginate('5');

        } else {

            $lista = CadastroEmpresa::orderBy('id', 'asc')
                ->paginate('5');
        }

        return view('pages.cadastros.empresa.index', compact('lista'));
    }


    public function create()
    {
        $estados = Configuracao::estados();

        return view('pages.cadastros.empresa.form', compact('estados'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'razao_social' => 'required|min:5',
                'nome_fantasia' => 'required',
                'cnpj' => 'required|cnpj|unique:empresas,cnpj,NULL,id,deleted_at,NULL',
                'cep' => 'required',
                'endereco' => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'estado' => 'required',
                'email' => 'required',
                'celular' => 'required',
                'status' => 'required'
            ],
            [
                'razao_social.required' => 'É necessário preencher a Razão Social',
                'nome_fantasia.required' => 'É necessário preencher o Nome Fantasia',
                'cnpj.required' => 'Este CNPJ não é válido',
                'cnpj.unique' => 'Este CNPJ já está cadastrado',
                'cnpj.cnpj' => 'Este CNPJ não é válido',
                'cep.required' => 'O CEP é indispensável',
                'endereco.required' => 'Preencha o endereço corretamente',
                'numero.required' => 'Preencha o número da residência',
                'bairro.required' => 'Preencha o Bairro corretamente',
                'cidade.required' => 'Preencha a Cidade corretamente',
                'estado.required' => 'Selecione o Estado corretamente',
                'email.required' => 'Digite o e-mail do cliente',
                'celular.required' => 'Digite corretamente o telefone celular / whatsapp',
                'status.required' => 'Selecione o Status'
            ]
        );

        $empresa = new CadastroEmpresa();
        $empresa->razao_social = $request->razao_social;
        $empresa->nome_fantasia = $request->nome_fantasia;
        $empresa->cnpj = $request->cnpj;
        $empresa->cep = $request->cep;
        $empresa->endereco = $request->endereco;
        $empresa->numero = $request->numero;
        $empresa->bairro = $request->bairro;
        $empresa->cidade = $request->cidade;
        $empresa->estado = $request->estado;
        $empresa->email = $request->email;
        $empresa->celular = $request->celular;
        $empresa->status = $request->status;

        $empresa->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | ADD EMPRESA : ' . $empresa->razao_social);

        return redirect()->route('cadastro.empresa')->with('success', 'Um registro foi adicionado com sucesso!');

    }

    public function edit($id)
    {
        $store = CadastroEmpresa::find($id);

        $estados = Configuracao::estados();

        if(!$id or !$store) {
            return redirect()->route('cadastro.empresa')->with('fail', 'Esse registro não foi encontrado.');
        }

        return view('pages.cadastros.empresa.form', compact('store', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $empresa = CadastroEmpresa::find($id);

        $request->validate(
            [
                'razao_social' => 'required|min:5',
                'nome_fantasia' => 'required',
                'cnpj' => 'required|unique:empresas,cnpj,null,'.$empresa->id.',deleted_at,null',
                'cep' => 'required',
                'endereco' => 'required',
                'numero' => 'required',
                'bairro' => 'required',
                'cidade' => 'required',
                'estado' => 'required',
                'email' => 'required',
                'celular' => 'required',
                'status' => 'required'
            ],
            [
                'razao_social.required' => 'É necessário preencher a Razão Social',
                'nome_fantasia.required' => 'É necessário preencher o Nome Fantasia',
                'cnpj.required' => 'Este CNPJ não é válido',
                'cnpj.unique' => 'Este CNPJ já está cadastrado',
                'cep.required' => 'O CEP é indispensável',
                'endereco.required' => 'Preencha o endereço corretamente',
                'numero.required' => 'Preencha o número da residência',
                'bairro.required' => 'Preencha o Bairro corretamente',
                'cidade.required' => 'Preencha a Cidade corretamente',
                'estado.required' => 'Selecione o Estado corretamente',
                'email.required' => 'Digite o e-mail do cliente',
                'celular.required' => 'Digite corretamente o telefone celular / whatsapp',
                'status.required' => 'Selecione o Status'
            ]
        );


        $empresa->razao_social = $request->razao_social;
        $empresa->nome_fantasia = $request->nome_fantasia;
        $empresa->cnpj = $request->cnpj;
        $empresa->cep = $request->cep;
        $empresa->endereco = $request->endereco;
        $empresa->numero = $request->numero;
        $empresa->bairro = $request->bairro;
        $empresa->cidade = $request->cidade;
        $empresa->estado = $request->estado;
        $empresa->email = $request->email;
        $empresa->celular = $request->celular;
        $empresa->status = $request->status;
        $empresa->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT EMPRESA : ' . $empresa->razao_social);

        return redirect()->route('cadastro.empresa.editar', $id)->with('success', 'Um registro foi modificado com sucesso!');
    }

    public function destroy($id)
    {
        $empresa = CadastroEmpresa::find($id);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE EMPRESA: ' . $empresa->razao_social);

        if ($empresa->delete()) {
            return redirect()->route('cadastro.empresa')->with('success', 'Registro excluído com sucesso.');
        } else {
            return redirect()->route('cadastro.empresa')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
        }
    }

}
