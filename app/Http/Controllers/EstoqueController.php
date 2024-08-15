<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Log, Session, File};

use App\Models\Estoque;
use App\Models\AnexoEstoque;
use App\Models\CadastroFornecedor;
use App\Models\CadastroObra;
use App\Models\EstoqueCategoria;
use App\Models\EstoqueSubCategoria;
use App\Models\EstoqueMarcas;

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRFpdf;

use Exception;

use Carbon\Carbon;

class EstoqueController extends Controller

{
    public function index()
    {
        $id_obra = Session::get('obra')['id'];

        if ($id_obra == null) {

            $produtos = Estoque::with('obra', 'categorias')->orderByDesc('id')->paginate(8);
        } else {
            $produtos = Estoque::with('obra', 'categorias')->whereOr('id_obra', $id_obra)->orderByDesc('id')->paginate(8);
        }

        return view('pages.estoque.index', compact('produtos'));
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

        return view('pages.estoque.create', compact('obras', 'marcas', 'subcategorias', 'categorias'));
    }

    public function store(Request $request)
    {

        try {


            /*FORMATAR VALOR PARA SALVAR NO BANCO DE DADOS */
            $string_valor_unitario = $request->valor_unitario;

            // Remove o separador de milhar (ponto)
            $string_valor_unitario = str_replace('.', '', $string_valor_unitario);

            // Substitui a vírgula do separador decimal por um ponto
            $string_valor_unitario = str_replace(',', '.', $string_valor_unitario);

            // Converte para float

            $numeroFloat_valor_unitario = (float) $string_valor_unitario;

            if ($request->file('image')) {

                // Valida a extensão da imagem
                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg|max:2048'
                ], [
                    'image.mimes' => 'A imagem enviada possui extensão inválida. O sistema aceita apenas as extensões "png, jpg, jpeg, svg"'
                ]);

                // Obtém o nome da imagem
                $imageName = $request->file('image')->getClientOriginalName();

                // Primeiro, cria o produto no banco de dados para obter o ID
                $produto = new Estoque(
                    [
                        'id_obra' => $request->id_obra,
                        'id_categoria' => $request->id_categoria,
                        'id_marca' => $request->id_marca,
                        'usuario' =>  Auth::user()->email,
                        'nome_produto' => $request->nome_produto,
                        'quantidade' => $request->quantidade,
                        'valor_unitario' => $numeroFloat_valor_unitario,
                        'estoque_minimo' => $request->estoque_minimo,
                        'unidade' => $request->unidade,
                        'status_produto' => $request->status_produto,
                        'image' => $imageName
                    ]
                );

                $produto->save();

                // Obtém o objeto do arquivo da imagem
                $imagePath = $request->file('image');

                // O caminho onde será salvo a imagem
                $targetDir = public_path("build/assets/images/produtos/{$produto->id}");

                // Cria o diretório se não existir
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Move o upload da imagem para a pasta pública
                $imagePath->move($targetDir, $imageName);
            }

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | STORE PRODUTO: ' . $produto->nome_produto);

            $notification = array(
                 'message'=>[
                'title' => "Sucesso!!!",
                'message' => "Registro cadastrado com sucesso",
                'type' => 'success']
            );

            return redirect()->route('ativo.estoque.index')->with($notification);
        } catch (\Exception $e) {

            return redirect()->route('ativo.estoque.index')->with('error', 'Erro ao cadastrar produto.');
        }
    }



   public function show($id)
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

        $produto_estoque = Estoque::find($id);

        return view('pages.estoque.show', compact('produto_estoque', 'marcas', 'obras', 'categorias', 'subcategorias'));
    }


    public function edit($id)
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

        $produto_estoque = Estoque::find($id);

        return view('pages.estoque.edit', compact('produto_estoque', 'marcas', 'obras', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, $id)
    {
        try {
            
            /*FORMATAR VALOR PARA SALVAR NO BANCO DE DADOS */
            $string_valor_unitario = $request->valor_unitario;
    
            // Remove o separador de milhar (ponto)
            $string_valor_unitario = str_replace('.', '', $string_valor_unitario);
    
            // Substitui a vírgula do separador decimal por um ponto
            $string_valor_unitario = str_replace(',', '.', $string_valor_unitario);
    
            // Converte para float
            $numeroFloat_valor_unitario = (float) $string_valor_unitario;

          // dd($string_valor_unitario);
    
            $produto_estoque = Estoque::find($id);
    
            if (!$produto_estoque) {
                return redirect()->route('ativo.estoque.index')->with('error', 'Produto não encontrado.');
            }
    
            $imagePath = public_path("imagens/estoque/{$produto_estoque->id}");
    
            if ($request->hasFile('image')) {
                // Deletar a imagem antiga se existir
                if ($produto_estoque->image) {
                    $oldImage = $imagePath . '/' . $produto_estoque->image;
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }
    
                // Salvar a nova imagem
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $file->move($imagePath, $filename);
                $produto_estoque->image = $filename;
            }
    

            $produto_estoque->update([
                'id_obra'           => $request->id_obra,
                'id_categoria'      => $request->id_categoria,                
                'id_subcategoria'   => $request->id_subcategoria,
                'id_marca'          => $request->id_marca,
                'usuario'           =>  Auth::user()->email,
                'nome_produto'      => $request->nome_produto,
                'valor_unitario'    => $numeroFloat_valor_unitario,
                'estoque_minimo'    => $request->estoque_minimo,
                'unidade'           => $request->unidade,
                'status_produto'    => $request->status_produto,
                'image'             => $filename,
                
            ]);
    
             Log::create([
                'action' => 'Product updated',
                'user_email' => Auth::user()->email,
            ]);
   
            $notification = array(
                'message'=>[
                'title' => "Sucesso!!!",
                'message' => "Registro atualizado com sucesso",
                'type' => 'success']
            );
    
            return redirect()->route('ativo.estoque.index')->with($notification);
        } catch (\Exception $e) {
            return redirect()->route('ativo.estoque.index')->with('error', 'Erro ao atualizar produto.');
        }
    }
    


    public function destroy($id)
    {

        try {
            
            $produto_estoque = Estoque::find($id);
            $userLog = Auth::user()->email;

            $produto_estoque->delete();
            Log::channel('main')->info($userLog . ' | DELETE ATIVOS PRODUTO: ' . $produto_estoque->nome_produto);

            if (File::exists("imagens/estoque/{$produto_estoque->id}" . $produto_estoque->image)) {
                File::delete("imagens/estoque/{$produto_estoque->id}" . $produto_estoque->image);
            }

            $notification = array(
                'message'=>[
                            'title' => "Sucesso!!!",
                            'message' => "Registro excluído com sucesso.",
                            'type' => 'success'
                ]
            );

            return redirect()->route('ativo.estoque.index')->with($notification);
            
        } catch (Exception $e) {

            $message = $e->getMessage();
            $notification = array(
                'message'=>[
                            'title' => "Atenção!!!",
                            'message' => $message,
                            'type' => 'warning'
                ]
            );

            return redirect()->route('ativo.estoque.index')->with($notification);
        }
    }

    /** Download de Arquivos */

    public function anexos(Request $request, $id)
    {
        if ($request->listar == "listar") {
            $ultimoAnexo = AnexoEstoque::where('id_produto', $id)
                ->latest('created_at')
                ->first();
            return response()->json($ultimoAnexo);
    } else {

            $anexos = AnexoEstoque::where('id_produto', $id)->get();
            return view('pages.estoque.partials.anexos', compact('anexos'));
        }
    }

    public function fileUpload(Request $request)
    {

        try {

            $request->validate([
                'file' => 'mimes:png,jpg,jpeg,pdf|max:2048'
            ], [
                'file.mimes' => 'O tipo de arquivo que você está tentando enviar não é válido.'
            ]);



            $id = $request->id; // Pega o ID do request
            $files = $request->file("file");
            $nome_arquivo = $request->file("file")->getClientOriginalName();

            // Armazena o arquivo e obtém o caminho/nome do arquivo

            $files->storeAs('uploads/estoque/', $nome_arquivo, 'public');
            
            $anexo_estoque = new AnexoEstoque([

                'id_produto'    => $id,
                'nome_arquivo'  => $nome_arquivo, // Salva o caminho/nome do arquivo
                'usuario'  => Auth::user()->email, // Salva o caminho/nome do arquivo
            ]);

            $anexo_estoque->save();
            $mensagem = 'Arquivo(s) cadastrado(s) com sucesso.';

            return response()->json($mensagem);
            
        } catch (Exception $e) {

            $mensagem = $e->getMessage();
            return response()->json($mensagem);
        }
    }

    /** Download de Arquivos */

    public function download($id)

    {

        $anexo = (AnexoEstoque::find($id)->nome_arquivo) ?? 0;
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $anexo);
        
        if ($anexo == null or $anexo == "") {

            return redirect()->back()->with('mensagem', 'Não foi possível fazer o download do arquivo, Edite este item e insira um arquivo válido');

            session()->forget('mensagem');
            
        } else {
            return Storage::download('public/uploads/estoque/' . $anexo);
            return redirect()->back()->with('success', 'Download realizado com successo');
            session()->forget('success');
        }
    }


    /** Excluir Anexo */

    public function destroy_file(Request $request)
    {
        try {

            $id_produto = $request->id_produto; // Pega o ID do produto da solicitação
            $anexo = AnexoEstoque::find($id_produto);
            $nomeArquivo = $anexo->nome_arquivo;
            $anexo->delete(); // Deleta o anexo
            
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | DELETE ANEXO ESTOQUE: ' . $nomeArquivo);
            $mensagem = 'Arquivo deletado com sucesso.';

            return response()->json($mensagem);
            
        } catch (Exception $e) {
            
            $mensagem = $e->getMessage();
            return response()->json($mensagem);
        }
    }
}
