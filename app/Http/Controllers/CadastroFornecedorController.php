<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContatoFornecedorRequest;
use Illuminate\Http\Request;
use App\Models\CadastroFornecedor;
use App\Models\ContatoFornecedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Traits\Configuracao;

class CadastroFornecedorController extends Controller
{
    use Configuracao;

    public function index(Request $request)
    {

        $fornecedore = $request->fornecedore;

        // Se um termo de pesquisa foi fornecido, filtra as categorias pelo nome
        if ($fornecedore) {

            $fornecedores = CadastroFornecedor::with('contatos')
            ->where('nome_fantasia', 'LIKE', "%$fornecedore%")
            ->orderBy('id', 'desc')
            ->paginate(9);

        }else{

            $fornecedores = CadastroFornecedor::with('contatos')
            ->orderBy('id', 'desc')
            ->paginate(9);

        }

        return view('pages.cadastros.fornecedor.index', compact('fornecedores'));
    }
    
    
    public function create()
    {
        $estados = Configuracao::estados();

        return view('pages.cadastros.fornecedor.form', compact('estados'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'razao_social' => 'required|min:5',
                'nome_fantasia' => 'required',
                'cnpj' => 'required|cnpj|unique:fornecedores,cnpj,NULL,id,deleted_at,NULL',
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

        $fornecedor = new CadastroFornecedor();
        $fornecedor->razao_social = $request->razao_social;
        $fornecedor->nome_fantasia = $request->nome_fantasia;
        $fornecedor->cnpj = $request->cnpj;
        $fornecedor->cep = $request->cep;
        $fornecedor->endereco = $request->endereco;
        $fornecedor->numero = $request->numero;
        $fornecedor->bairro = $request->bairro;
        $fornecedor->cidade = $request->cidade;
        $fornecedor->estado = $request->estado;
        $fornecedor->email = $request->email;
        $fornecedor->celular = $request->celular;
        $fornecedor->status = $request->status;

        $fornecedor->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | ADD FORNECEDOR : ' . $fornecedor->razao_social);

        return redirect()->route('cadastro.fornecedor')->with('success', 'Um registro foi adicionado com sucesso!');

    }

    public function edit($id)
    {
        $store = CadastroFornecedor::find($id);

        $estados = Configuracao::estados();

        $contatos = ContatoFornecedor::where('id_fornecedor', $id)->get();

        if(!$id or !$store) {
            return redirect()->route('cadastro.fornecedor')->with('fail', 'Esse registro não foi encontrado.');
        }

        return view('pages.cadastros.fornecedor.form', compact('store', 'estados', 'contatos'));
    }

    public function update(Request $request, $id)
    {
        $fornecedor = CadastroFornecedor::find($id);

        $request->validate(
            [
                'razao_social' => 'required|min:5',
                'nome_fantasia' => 'required',
                'cnpj' => 'required|cnpj|unique:fornecedores,cnpj,null,'. $fornecedor->id .',deleted_at,null',
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


        $fornecedor->razao_social = $request->razao_social;
        $fornecedor->nome_fantasia = $request->nome_fantasia;
        $fornecedor->cnpj = $request->cnpj;
        $fornecedor->cep = $request->cep;
        $fornecedor->endereco = $request->endereco;
        $fornecedor->numero = $request->numero;
        $fornecedor->bairro = $request->bairro;
        $fornecedor->cidade = $request->cidade;
        $fornecedor->estado = $request->estado;
        $fornecedor->email = $request->email;
        $fornecedor->celular = $request->celular;
        $fornecedor->status = $request->status;
        $fornecedor->save();

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT FORNECEDOR : ' . $fornecedor->razao_social);

        return redirect()->route('cadastro.fornecedor.editar', $id)->with('success', 'Um registro foi modificado com sucesso!');
    }

    public function destroy(CadastroFornecedor $id)
    {
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE FORNECEDOR : ' . $id->razao_social);

        if($id->delete()) {
            return redirect()->route('cadastro.fornecedor')->with('success', 'Fornecedor excluído com sucesso!');
        } else {
            return redirect()->route('cadastro.fornecedor')->with('fail', 'Fornecedor excluído com sucesso!');
        }
    }

    public function storeContato(StoreContatoFornecedorRequest $request)
    {
        $data = $request->validated();
        $save = ContatoFornecedor::create($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | ADD CONTATO FORNECEDOR : ' . $save->nome);

        if($save) {
            return redirect()->route('cadastro.fornecedor.editar', $request->id_fornecedor)->with('success', 'Contato adicionado com sucesso!');
        } else {
            return redirect()->route('cadastro.fornecedor.editar', $request->id_fornecedor)->with('fail', 'Problemas ao adicionar contato');
        }
    }

    public function destroyContato(ContatoFornecedor $contato)
    {
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE CONTATO FORNECEDOR : ' . $contato->nome);

        if ($contato->delete()) {
            return redirect()->route('cadastro.fornecedor.editar', $contato->id_fornecedor)->with('success', 'Contato excluído com sucesso.');
        } else {
            return redirect()->route('cadastro.fornecedor.editar', $contato->id_fornecedor)->with('fail', 'Um erro ocorreu na tentativa de exclusão do contato');
        }
    }

}
