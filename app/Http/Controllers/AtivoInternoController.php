<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\AnexoAtivoInterno;
use App\Models\AtivoExternoEstoque;
use App\Models\AtivosInterno;
use App\Models\CadastroEmpresa;
use App\Models\CadastroObra;
use App\Models\MarcaPatrimonio;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};

use App\Traits\Configuracao;
use LaravelQRCode\Facades\QRCode;

use Session;

class AtivoInternoController extends Controller
{
    public function __construct()
    {
        $this->middleware('access.level:3')->except('index');
    }

    public function index()
    {
        $ativos = AtivosInterno::whereOr('id_obra', Session::get('obra')['id'])->orderByDesc('id')->get();
        return view('pages.ativos.internos.index', compact('ativos'));
    }


    public function create(Request $request)
    {
        $nextPatrimony = Configuracao::PatrimonioSigla() . Configuracao::PatrimonioAtual();

        $obras = CadastroObra::where('status', 'Ativo')->orderByDesc('id')->get();

        $marcas = MarcaPatrimonio::all();

        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();
        
        ($request->id = "") ? "" : $request->id;
        
        $anexos = AnexoAtivoInterno::where('id_ativo_interno', $request->id)->get();

        return view('pages.ativos.internos.create', compact('obras', 'marcas', 'nextPatrimony', 'empresas', 'anexos'));
    }


    public function store(Request $request, $input = "arquivo")
    {
        $data = $request->all();
        $data['patrimonio'] = Configuracao::PatrimonioSigla() . Configuracao::PatrimonioAtual();
        $data['valor_atribuido'] = str_replace('R$ ', '', $request->valor_atribuido);
        
         //SCRIPT PARA SUBIR ARQUIVO NA PASTA 'public/storage/imagem_ativo'
            $nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
            $caminho = public_path('storage/imagem_ativo_interno/' . $nome_img);
            if (@$_FILES['imagem']['name'] == "") {
                $imagem = "";
            } else {

                $imagem = $nome_img;
            }

            $imagem_temp = @$_FILES['imagem']['tmp_name'];

            $ext = pathinfo($imagem, PATHINFO_EXTENSION);

            if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == '') {

                move_uploaded_file($imagem_temp, $caminho);
                $data['imagem'] = $nome_img;
                
            } else {

                echo 'Extensão de Imagem não permitida!';

                exit();
            }
            
            $extensao = $request->file("arquivo")->getClientOriginalExtension() ?? $request->file("imagem")->getClientOriginalExtension();
            
            $nome_arquivo = $request->titulo_nf . "." . $extensao;
          
            $data['arquivo'] = $nome_arquivo;
            
           $request->file($input)->storeAs('uploads/anexos_ativos_internos', $nome_arquivo, 'public');
       
            
            
        $save = AtivosInterno::create($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | STORE ATIVOS INTERNOS: ' . $save->patrimonio);

        if ($save) {
            return redirect()->route('ativo.interno.index')->with('success', 'Registro cadastrado com sucesso.');
        } else {
            return redirect()->route('ativo.interno.index')->with('fail', 'Um erro impediu o cadastro.');
        }
    }

    public function show($id)
    {
        $data = AtivosInterno::where('id', $id)->first();
        
        $anexos = AnexoAtivoInterno::where('id_ativo_interno', $id)->get();

        //$pdf = Pdf::loadView('pages.ativos.internos.show', ['data' => $data]);

        //return $pdf->setPaper('a4', 'landscape')->stream('ativo.pdf');

        // configurações especificas para dimensões do arquivo pdf
        // setPaper array(0.0, 0.0, 165.00, 300.00)
        
     return view('pages.ativos.internos.show', compact('data','anexos'));
    }

    public function edit(AtivosInterno $ativo)
    {
        $obras = CadastroObra::where('status', 'Ativo')->orderByDesc('id')->get();

        $marcas = MarcaPatrimonio::all();

        $anexos = AnexoAtivoInterno::where('id_ativo_interno', $ativo->id)->get();

        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();

        return view('pages.ativos.internos.edit', compact('ativo', 'marcas', 'obras', 'anexos', 'empresas'));
    }


    public function update(Request $request, $ativo)
    {
        if (! $save = AtivosInterno::find($ativo)) {
            return redirect()->route('ativo.interno.index')->with('fail', 'Problemas para localizar o ativo.');
        }
        
        $data = $request->all();
        
        //SCRIPT PARA SUBIR ARQUIVO NA PASTA 'public/storage/imagem_ativo'
            $nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
            $caminho = public_path('storage/imagem_ativo_interno/' . $nome_img);
            if (@$_FILES['imagem']['name'] == "") {
                $imagem = "";
            } else {

                $imagem = $nome_img;
            }

            $imagem_temp = @$_FILES['imagem']['tmp_name'];

            $ext = pathinfo($imagem, PATHINFO_EXTENSION);

            if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == '') {

                move_uploaded_file($imagem_temp, $caminho);
                $data['imagem'] = $nome_img;
                
            } else {

                echo '<script>alert("Extensão de Imagem não permitida!")</script>';

                exit();
            }


        
        
        $save->update($data);
        
         $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | EDIT ATIVOS INTERNOS: ' . $save->patrimonio);

        return redirect()->route('ativo.interno.edit', $ativo)->with('success', 'Registro atualizado com sucesso.');
    }


    public function destroy(AtivosInterno $ativo)
    {

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | DELETE ATIVOS INTERNOS: ' . $ativo->patrimonio);

        if ($ativo->delete()) {
            return redirect()->route('ativo.interno.index')->with('success', 'Registro excluído com sucesso.');
        } else {
            return redirect()->route('ativo.interno.index')->with('fail', 'Um erro ocorreu na tentativa de exclusão');
        }
    }

    public function storeMarca(Request $request)
    {
        $data = $request->all();
        $save = MarcaPatrimonio::create($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog .' | ADD MARCA PATRIMONIO: ' . $save->marca);

        if ($save) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['fail' => true]);
        }
    }

    //Inserção de arquivo como link no corpo da postagem
    public function fileUpload(Request $request)
    {

        $data = $request->all();
        
       // dd($request->tipo);

        if ($request->hasFile('file')) {
            
             $ext = $request->file("file")->getClientOriginalExtension();
             
            
            if ($ext == 'pdf' or $ext == 'xls' or $ext == 'xlsx' or $ext == 'jpg' or $ext == 'png' or $ext == 'jpeg' or $ext == 'gif') {
                
                $nome_arquivo = preg_replace('/[ -]+/', '-', @$_FILES['file']['name']);
            
                $request->file('file')->storeAs('uploads/anexos_ativos_internos', $nome_arquivo, 'public');
                
            } else {
                
                return redirect()->back()->with('failed', 'A extensão do arquivo não é permitida!!!');
                session()->forget('failed');

            }
            
            //dd($nome_arquivo);

            Anexo::create([
                'id_usuario' => Auth::user()->id,
                'id_ativo_interno' => $request->id_ativo_interno,
                'id_modulo' => $request->titulo ?? null,
                'titulo' => $request->titulo,
                'arquivo' => $nome_arquivo,
                'descricao' => $request->descricao,
                'tipo' => $request->tipo
            ]);
    

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | ADD ANEXOS ATIVOS INTERNOS: ' . $request->id);

            if ($request->id) {
                
                return redirect()->back()->with('success', 'Anexo cadastrado com sucesso.');
                session()->forget('success');
                
            } else {
                
                return redirect()->back()->with('fail', 'Um erro impediu o cadastro.');
                session()->forget('fail');
               
            }
        }

    }
    
     /** Download de Arquivos */
    public function download($id)
    {
        $anexo = (AtivosInterno::find($id)->arquivo) ?? 0;
        
        //dd($anexo);

        $userLog = Auth::user()->email;
        
        Log::channel('main')->info($userLog . ' | DOWNLOAD ANEXO: ' . $anexo);

        if ($anexo == null or $anexo == "") {
            
            return redirect()->back()->with('mensagem', 'Não foi possível fazer o download do arquivo, Edite este item e insira um arquivo válido');
            session()->forget('mensagem');
            
            //return view('pages.ativos.internos.show', $id);
            
        }else{
             return Storage::download('public/uploads/anexos_ativos_internos/' . $anexo);
             
              return redirect()->back()->with('success', 'Download realizado com successo');
                session()->forget('success');
        }
       
       
    }


    /** Excluir Anexo */
    public function destroyAnexo(Request $request, $id, $modulo = null)
    {

        $anexo = AtivosInterno::find($id);
       
        $excluirNF = $request->all();
        
        $excluirNF['arquivo'] = null;
        
        $anexo->update($excluirNF);
        
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DELETE ANEXO: ' . $anexo->titulo);

            if ($anexo->update()) {
                return redirect()->back()->with('success', 'Registro exclúido com successo');
                session()->forget('success');
            } else {
                return redirect()->route($request-$id)->with('fail', 'Um erro ocorreu na tentativa de exclusão');
            }
        

    }












}
