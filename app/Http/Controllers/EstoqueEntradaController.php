<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\CadastroFornecedor;
use App\Models\CadastroFuncionario;
use App\Models\CadastroObra;
use App\Models\Estoque;
use App\Models\EstoqueEntrada;
use App\Models\EstoqueCategoria;
use App\Models\EstoqueSubCategoria;
use App\Models\EstoqueMarcas;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log,
    Session
};

class EstoqueEntradaController extends Controller
{

   public function index()
    {

        $id_obra = Session::get('obra')['id'];
        if ($id_obra == null) {
            $produtos_entradas = EstoqueEntrada::with('obra', 'categoria', 'produto', 'funcionario')
                ->orderBy('id', 'desc')
                ->paginate(8);
        } else {

            $produtos_entradas = EstoqueEntrada::with('obra', 'categoria', 'produto', 'funcionario')
                ->where('id_obra', $id_obra)
                ->orderBy('id', 'desc')
                ->paginate(8);
        }

        return view('pages.estoque.entradas.index', compact('produtos_entradas'));
    }


    public function create(Request $request)
    {

        $id_obra = Session::get('obra')['id'];

        if ($id_obra == null) {

            $obras = CadastroObra::orderBy('id')->get();
        } else {

            $obras = CadastroObra::where('id', $id_obra)->orderBy('id')->get();
        }

        $categorias = EstoqueCategoria::all();
        $subcategorias = EstoqueSubCategoria::all();
        $marcas = EstoqueMarcas::all();

        $fornecedores = CadastroFornecedor::all();

        $id_obra = Session::get('obra')['id'];
        $id_categoria = $request->id_categoria;

        // Query base para funcionários, que pode ser refinada dependendo de $id_obra
        $funcionariosQuery = CadastroFuncionario::orderBy('id', 'desc');

        // Adiciona condição sobre id_obra se ela não for nula

        if ($id_obra) {
            $funcionariosQuery->where('id_obra', $id_obra);
        }


        $funcionarios = $funcionariosQuery->get();

        // Query base para produtos saídas

        $produtosEntradasQuery = Estoque::with('obra', 'categorias', 'marca')->orderBy('id', 'desc');


        // Adiciona condição sobre id_obra se ela não for nula

        if ($id_obra) {
            $produtosEntradasQuery->where('id_obra', $id_obra);
        }

        // Adiciona condição sobre id_categoria se ela for fornecida

        if ($id_categoria) {

            $produtosEntradasQuery->where('id_categoria', 'like', "%$id_categoria%");
        }

        $produtos_entradas = $produtosEntradasQuery->paginate(4);

        return view('pages.estoque.entradas.create', compact('produtos_entradas', 'fornecedores', 'obras', 'categorias', 'subcategorias'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'id_produto'                => 'required|array',
            'quantidade_entrada'        => 'required|array',
            'valor_unitario_entrada'    => 'required|array',
            'valor_total_entrada'       => 'required|array',
            'nota_fical'                => 'required|array',
        ]);

        try {

            foreach ($request->id_produto as $index => $id_produto) {
                if (isset($request->file("arquivo_nf")[$index])) {
                    $file = $request->file("arquivo_nf")[$index];
                    $nome_arquivo_nf = $file->getClientOriginalName();
                    $file->storeAs('uploads/estoque/entradas', $nome_arquivo_nf, 'public');
                } else {
                    $nome_arquivo_nf = null;
                }

                if (isset($request->file("arquivo_ca")[$index])) {
                    $file_arquivo_ca = $request->file("arquivo_ca")[$index];
                    $nome_arquivo_ca = preg_replace('/[ -]+/', '-', $file_arquivo_ca->getClientOriginalName());
                    $file_arquivo_ca->storeAs('uploads/estoque/entradas/ca/', $nome_arquivo_ca, 'public');
                } else {
                    $nome_arquivo_ca = null;
                }

                $produto_estoque =  new EstoqueEntrada([
                    'usuario'                   => Auth::user()->email,
                    'id_obra'                   => $request->id_obra,
                    'id_produto'                => $request->id_produto[$index],
                    'quantidade_entrada'        => $request->quantidade_entrada[$index],
                    'valor_unitario_entrada'    => $request->valor_unitario_entrada[$index],
                    'valor_total_entrada'       => $request->valor_total_entrada[$index],
                    'cert_aut_entrada'          => $request->cert_aut_entrada[$index] ?? null,
                    'num_lote_entrada'          => $request->num_lote_entrada[$index] ?? null,
                    'data_validade_lote_ca'     => $request->data_validade_lote_ca[$index] ?? null,
                    'num_nf'                    => $request->num_nf[$index] ?? null,
                    'arquivo_nf'                => $nome_arquivo_nf[$index],
                    'arquivo_ca'                => $nome_arquivo_ca
                ]);

                $produto_estoque->save();
            }

            Log::info('Produtos adicionados com sucesso', ['action' => 'store', 'user_id' => auth()->id()]);
            Session::flash('menssagem', ['title' => 'Sucesso', 'message' => 'Produtos adicionados com sucesso!', 'type' => 'success']);

            return redirect()->route('ativo.estoque.entrada.index');
        } catch (\Exception $e) {

            Log::error('Erro ao adicionar produtos', ['error' => $e->getMessage(), 'action' => 'store', 'user_id' => auth()->id()]);
            Session::flash('menssagem', ['title' => 'Erro', 'message' => 'Erro ao adicionar produtos!', 'type' => 'error']);

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $produto_entrada = EstoqueEntrada::with('produto')->find($id);

        $id_obra = Session::get('obra')['id'];

        if ($id_obra == null) {
            $obras = CadastroObra::orderBy('id')->get();
        } else {
            $obras = CadastroObra::where('id', $id_obra)->orderBy('id')->get();
        }

        $categorias = EstoqueCategoria::all();
        $subcategorias = EstoqueSubCategoria::all();
        $marcas = EstoqueMarcas::all();
        $fornecedores = CadastroFornecedor::all();

        return view('pages.estoque.entradas.edit', compact('produto_entrada', 'obras', 'categorias', 'subcategorias', 'marcas', 'fornecedores'));
    }



    public function update(Request $request, $id)
    {
        try {
            $produto_entrada = EstoqueEntrada::findOrFail($id);

            $data = $request->only([
                'id_obra',
                'id_categoria',
                'id_subcategoria',
                'nome_produto',
                'quantidade_entrada',
                'num_nf',
                'cert_aut_entrada',
                'num_lote_entrada',
                'data_validade_lote_ca'
            ]);

            // Função para converter o valor formatado para float
            function convertToFloat($value)
            {
                return floatval(str_replace(['.', ','], ['', '.'], $value));
            }

            $data['valor_unitario_entrada'] = convertToFloat($request->valor_unitario_entrada);
            $data['valor_total_entrada'] = convertToFloat($request->valor_total_entrada);
            $data['usuario_edit'] = Auth::user()->email;

            if ($request->hasFile('arquivo_nf')) {
                // Deletar o arquivo anterior
                Storage::disk('public')->delete('uploads/estoque/entradas/' . $produto_entrada->titulo_nf);
                // Armazenar o novo arquivo
                $file = $request->file("arquivo_nf");
                $nome_arquivo_nf = $file->getClientOriginalName();
                $file->storeAs('uploads/estoque/entradas', $nome_arquivo_nf, 'public');

                $data['arquivo_nf'] = $nome_arquivo_nf;
            }

            if ($request->hasFile('arquivo_ca')) {
                // Deletar o arquivo anterior
                Storage::disk('public')->delete('uploads/estoque/entradas/ca/' . $produto_entrada->arquivo_ca);
                // Armazenar o novo arquivo

                $file_arquivo_ca = $request->file("arquivo_ca");
                $nome_arquivo_ca = $file_arquivo_ca->getClientOriginalName();
                $file_arquivo_ca->storeAs('uploads/estoque/entradas/ca/', $nome_arquivo_ca, 'public');

                $data['arquivo_ca'] = $nome_arquivo_ca;
            }

            $produto_entrada->update($data);

            Log::info('Produto atualizado com sucesso', [
                'action' => 'update',
                'user_id' => auth()->id(),
                'produto_id' => $id
            ]);
            Session::flash('mensagem', [
                'title' => 'Sucesso',
                'message' => 'Produto atualizado com sucesso!',
                'type' => 'success'
            ]);

            return redirect()->route('ativo.estoque.entrada.index');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar produto', [
                'error' => $e->getMessage(),
                'action' => 'update',
                'user_id' => auth()->id()
            ]);
            Session::flash('mensagem', [
                'title' => 'Erro',
                'message' => 'Erro ao atualizar produto!',
                'type' => 'error'
            ]);

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

   
    public function show($id)
    {
        $produto_entrada = EstoqueEntrada::with('produto')->find($id);
    
        $id_obra = Session::get('obra')['id'];
    
        if ($id_obra == null) {
            $obras = CadastroObra::orderBy('id')->get();
        } else {
            $obras = CadastroObra::where('id', $id_obra)->orderBy('id')->get();
        }
    
        $categorias = EstoqueCategoria::all();
        $subcategorias = EstoqueSubCategoria::all();
        $marcas = EstoqueMarcas::all();
        $fornecedores = CadastroFornecedor::all();
    
        return view('pages.estoque.entradas.show', compact('produto_entrada', 'obras', 'categorias', 'subcategorias', 'marcas', 'fornecedores'));
    }


    public function destroy($id)
    {
        try {
            $produto_entrada = EstoqueEntrada::findOrFail($id);
            // Deletar o arquivo relacionado
            Storage::disk('public')->delete('nf_arquivos/' . $produto_entrada->titulo_nf);
            $produto_entrada->delete();

            Log::info('Produto deletado com sucesso', ['action' => 'destroy', 'user_id' => auth()->id(), 'produto_id' => $id]);
            Session::flash('menssagem', ['title' => 'Sucesso', 'message' => 'Produto deletado com sucesso!', 'type' => 'success']);

            return redirect()->route('ativo.estoque.entrada.index');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar produto', ['error' => $e->getMessage(), 'action' => 'destroy', 'user_id' => auth()->id()]);
            Session::flash('menssagem', ['title' => 'Erro', 'message' => 'Erro ao deletar produto!', 'type' => 'error']);

            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    /** Download de Arquivos */

    public function download($id)
    {
        $anexo = (EstoqueEntrada::find($id)->arquivo) ?? 0;


        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $anexo);

        if ($anexo == null or $anexo == "") {
            return redirect()->back()->with('mensagem', 'Não foi possível fazer o download do arquivo');
            session()->forget('mensagem');
        } else {

            return Storage::download('public/uploads/estoque/entradas/' . $anexo);
            return redirect()->back()->with('success', 'Download realizado com successo');
            session()->forget('success');
        }
    }

    public function pesquisar_categoria(Request $request)
    {
        $id_obra = Session::get('obra')['id'];
        $id_categoria = $request->input('id_categoria');
        $id_subcategoria = $request->input('id_subcategoria');
        $page = $request->input('page', 1);
        $nome_produto = $request->input('nome_produto');

        $produtos_entradas = Estoque::with(['obra', 'categorias', 'subcategorias', 'entradas', 'saidas', 'fornecedor', 'marca'])
            ->when($id_categoria, function ($query, $id_categoria) {
                return $query->where('id_categoria', $id_categoria);
            })
            ->when($id_subcategoria, function ($query, $id_subcategoria) {
                return $query->where('id_subcategoria', $id_subcategoria);
            })
            ->when($nome_produto, function ($query, $nome_produto) {
                return $query->where('nome_produto', 'like', '%' . $nome_produto . '%');
            })
            ->when($id_obra && !$id_categoria, function ($query, $id_obra) {
                return $query->where('id_obra', $id_obra);
            })
            ->orderBy('id', 'desc')
            ->paginate(3, ['*'], 'page', $page);

        $viewData = [
            'produtos_entradas' => $produtos_entradas,
        ];

        if ($request->expectsJson()) {
            $view = view('pages.estoque.entradas.partials.list', $viewData)->render();
            return response()->json([
                'html' => $view
            ]);
        }
    }
}
