<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\CadastroEmpresa;
use App\Models\CadastroObra;
use App\Models\MarcaMaquina;
use App\Models\ModeloMaquina;
use App\Models\Veiculo;
use App\Models\VeiculoAbastecimento;
use App\Models\VeiculoDepreciacao;
use App\Models\VeiculoIpva;
use App\Models\VeiculoManutencao;
use App\Models\VeiculoQuilometragem;
use App\Models\VeiculoSeguro;
use App\Models\VeiculoImagens;
use App\Models\VeiculoCategoria;
use App\Models\VeiculoHorimetro;
use App\Models\VeiculoSubCategoria;

use Illuminate\Http\Request;


use App\Traits\{
    Configuracao,
    FuncoesAdaptadas
};

use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log

};

class VeiculoController extends Controller

{

    use Configuracao, FuncoesAdaptadas;

    protected $ativoExternoEstoque;
    protected $ativoExternoEstoque_report;

    public function __construct(Veiculo $ativoExternoEstoque, Veiculo $ativoExternoEstoque_report)

    {      
        $this->ativoExternoEstoque = $ativoExternoEstoque;
        $this->ativoExternoEstoque_report = $ativoExternoEstoque_report;
    }   

    public function index()
    {

        $listPage = request('lista') ?? 15;

        $veiculos = Veiculo::with('manutencaos', 'obra', 'quilometragens', 'horimetro')->when(request('search') != null, function ($query) {

            return  $query->where('placa', 'like', '%' . request('search') . '%')
                ->orWhere('marca', 'LIKE', '%' . request('search') . '%')
                ->orWhere('modelo', 'LIKE', '%' . request('search') . '%')
                ->orWhere('codigo_da_maquina', 'LIKE', '%' . request('search') . '%')
                ->orWhere('tipo', 'LIKE', '%' . request('search') . '%')
                ->orWhere('veiculo', 'LIKE', '%' . request('search') . '%')
                ->with('manutencaos', 'obra');

        })
        ->orderBy('veiculos.id','DESC')
        ->paginate($listPage);        

        $count_veiculos_list = Veiculo::selectRaw("COUNT(*) as total_veiculos")->whereNull("deleted_at")->get();
        $quilometragem = VeiculoQuilometragem::where('veiculo_id', 51)->first();
        $horimetro = VeiculoHorimetro::where('veiculo_id', 51)->first();
        $buscaTotalVeiculos = Veiculo::obterDados();
        return view('pages.ativos.veiculos.partials.list', compact('veiculos', 'buscaTotalVeiculos', 'quilometragem', 'horimetro', 'count_veiculos_list'));

    }


    public function pesquisarSubcategoria(Request $request)
    {
        $selecao = $request->selecao;      

        $categoria = VeiculoSubCategoria::where('id_categoria', $selecao)->get();   

        return response()->json($categoria);
    }

    public function create()
    {

        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->get();
        $marcas = MarcaMaquina::orderBy('marca')->get();
        $modelos = ModeloMaquina::orderBy('modelo')->get();      
        $categorias = VeiculoCategoria::orderBy('nomeCategoria')->get();
        $subCategorias = VeiculoSubCategoria::orderBy('nomeSubCategoria')->get();
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();

        return view('pages.ativos.veiculos.form', compact('obras', 'marcas', 'modelos', 'empresas', 'categorias', 'subCategorias'));

    }

    public function store(Request $request)
    {
        if ($request->tipo == 'maquinas') {

            $modelo = $request->input('modelo_da_maquina');
            $ano = $request->input('ano_da_maquina');
            $veiculo = $request->input('veiculo_maquina');
            $marca = $request->input('marca_da_maquina');

        } else {

            $modelo = $request->input('modelo_nome');
            $ano = $request->input('ano');
            $veiculo = $request->input('veiculo');
            $marca = $request->input('marca_nome');

        }        

        if ($request->file("imagem")) {

            $file = $request->file("imagem");
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(\public_path("veiculo/"), $imageName);           

        }else{

            $imageName = "";
        }

        $veiculo = Veiculo::create(
            [
                'obra_id'               => $request->input('obra')?? 0,
                'idCategoria'           => $request->input('idCategoria'),
                'idSubCategoria'        => $request->input('idSubCategoria'),
                'tipo'                  => $request->input('tipo'),
                'marca'                 => $marca,
                'modelo'                => $modelo,
                'ano'                   => $ano,
                'veiculo'               => $veiculo,
                'valor_fipe'            => str_replace('R$ ', '', $request->input('valor_fipe')),
                'codigo_fipe'           => $request->input('codigo_fipe'),
                'fipe_mes_referencia'   => $request->input('fipe_mes_referencia'),
                'codigo_da_maquina'     => $request->input('codigo_da_maquina'),
                'placa'                 => strtoupper($request->placa),
                'renavam'               => $request->input('renavam'),
                'horimetro_inicial'     => $request->input('horimetro_inicial'),
                'quilometragem_inicial' => $request->input('quilometragem_inicial'),
                'observacao'            => $request->input('observacao'),
                'situacao'              => $request->input('situacao'),
                'imagem'                => $imageName,
                'usuario'                => Auth::user()->email,
            ]

        );

       if ($request->tipo == 'maquinas') {
            VeiculoHorimetro::create([
                'veiculo_id' => $veiculo->id,
                'horimetro_atual'   => $request->horimetro_inicial ?? 0,
                'horimetro_novo'    => $request->horimetro_inicial ?? 0,
                'usuario'           => Auth::user()->email,
            ]);

        } else {

            VeiculoQuilometragem::create([
                'veiculo_id'            => $veiculo->id,
                'quilometragem_atual'   => $request->quilometragem_inicial ?? 0,
                'quilometragem_nova'    => $request->quilometragem_inicial ?? 0,
                'usuario'           => Auth::user()->email,

            ]);

        }

        if ($request->file("imagens")) {

            $files = [];
            $files = $request->file("imagens");

            foreach ($files as $file) {

                $cadImagem = new VeiculoImagens();
                $imageName = time() . '_' . $file->getClientOriginalName();
                $cadImagem->veiculo_id = $veiculo->id;
                $cadImagem->imagens = $imageName;
                $file->move(\public_path("/imagens/veiculos"), $imageName);
                $cadImagem->save();
            }
        }

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD VEICULO | Placa: ' . $veiculo->veiculo);
        return redirect(url('admin/ativo/veiculo'))->with('success', 'Registro cadastrado com sucesso.');

    }



    public function edit($id)

    {

        VeiculoSubCategoria::with('categorias')->find($id);
        $veiculo = Veiculo::with('subCategorias', 'categorias', 'obra')->find($id);
        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->orderByDesc('id')->get();
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();
        $marcas = MarcaMaquina::all();
        $modelos = ModeloMaquina::all();     
        $categorias = VeiculoCategoria::orderBy('nomeCategoria')->get();       
        $subCategorias = VeiculoSubCategoria::orderBy('nomeSubCategoria')->get();

        if ($veiculo->tipo == 'maquinas') {

            return view('pages.ativos.veiculos.edit-maquina', compact('veiculo', 'obras', 'marcas', 'modelos', 'empresas', 'categorias', 'subCategorias'));

        } else {

            return view('pages.ativos.veiculos.edit-veiculo', compact('veiculo', 'obras', 'marcas', 'modelos', 'empresas' , 'categorias', 'subCategorias'));

        }

    }    

    public function show($id)

    {
        $veiculos = Veiculo::find($id);
        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->orderByDesc('id')->get();
        $empresas = CadastroEmpresa::where('status', 'Ativo')->get();  
        $imagens = VeiculoImagens::where('veiculo_id', $id)->get();
        $marcas = MarcaMaquina::all();
        $modelos = ModeloMaquina::all();      

            return view('pages.ativos.veiculos.show', compact('veiculos', 'obras', 'marcas', 'modelos', 'empresas', 'imagens'));

    }


    public function update(Request $request, $id)

    {

        if (! $save = Veiculo::find($id)) {

            return redirect()->route('ativo.interno.index')->with('fail', 'O veículo não foi encontrado.');

        }             

    
        if ($request->tipo == 'maquinas') {

            $data = $request->all();
            $data['obra_id'] =              $request->input('obra');
            $data['idCategoria'] =          $request->input('idCategoria');
            $data['idSubCategoria'] =       $request->input('idSubCategoria');
            $data['marca'] =                $request->input('marca_da_maquina');
            $data['modelo'] =               $request->input('modelo_da_maquina');
            $data['ano'] =                  $request->input('ano_da_maquina');
            $data['horimetro_inicial'] =    $request->input('horimetro_inicial');
            $data['veiculo'] =              $request->input('veiculo_maquina');
            $data['valor_fipe'] =           str_replace('R$ ', '', $request->valor_fipe);
            $data['usuario_update'] =       Auth::user()->email;

            $save->update($data);
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | EDIT MAQUINA | ID: ' . $id);

            return redirect(url('admin/ativo/veiculo'))->with('success', 'Registro editado com sucesso.');

        } else {

            $data = $request->all();
            $data['obra_id'] =                  $request->obra;
            $data['idCategoria'] =              $request->input('idCategoria');
            $data['idSubCategoria'] =           $request->input('idSubCategoria');
            $data['marca'] =                    $request->marca_nome ?? $request->marca;
            $data['modelo'] =                   $request->modelo_nome ?? $request->modelo;
            $data['ano'] =                      substr($request->ano, 0, 4);
            $data['valor_fipe'] =               str_replace('R$ ', '', $request->valor_fipe);
            $data['placa'] =                    strtoupper($request->placa);
            $data['quilometragem_inicial'] =    $request->input('quilometragem_inicial');
            $data['usuario_update'] =           Auth::user()->email;
            $save->update($data);

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | EDIT VEICULO | ID: ' . $id);
            return redirect(url('admin/ativo/veiculo'))->with('success', 'Registro editado com sucesso.');

        }

    }

    public function adicionarMarca(Request $request)

    {
        MarcaMaquina::create([
            'marca' => $request->input('add_marca_da_maquina')
        ]);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD MARCA MÁQUINA: ' . $request->marca);

        return redirect()->back()->withInput();

    }


    public function delete($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | DELETE VEÍCULO: ' . $veiculo->id);

        VeiculoQuilometragem::where('veiculo_id', $id)->delete();
        VeiculoAbastecimento::where('veiculo_id', $id)->delete();
        VeiculoDepreciacao::where('veiculo_id', $id)->delete();
        VeiculoIpva::where('veiculo_id', $id)->delete();
        VeiculoManutencao::where('veiculo_id', $id)->delete();
        VeiculoSeguro::where('veiculo_id', $id)->delete();

        if ($veiculo->delete()) {

            return redirect(url('admin/ativo/veiculo'))->with('success', 'Registro excluído com sucesso.');

        } else {

            return redirect(url('admin/ativo/veiculo'))->with('fail', 'Problemas ao excluir registro.');

        }

    }

    // INICIO DA GALERIA DE IMAGENS

    public function storeimagem(Request $request)

    {

       //dd($request->imagens1);

        if ($request->file("imagens1")) {

            $files = [];
            $files = $request->file("imagens1");

            foreach ($files as $file) {

               $cadImagem = new VeiculoImagens();
                $imageName = time() . '_' . $file->getClientOriginalName();
                $cadImagem->veiculo_id = $request->veiculo_id;
                $cadImagem->imagens = $imageName;
                $file->move(\public_path("/imagens/veiculos"), $imageName);
                $cadImagem->save();               

            }

        }        

       return redirect()->route('ativo.veiculo.detalhes', $request->veiculo_id)->with('success', 'Imagem adicionada com sucesso.');

    }    

    public function updateImagem(Request $request, $id)
    {

        $updateImagem = VeiculoImagens::find($id);
        $data = $request->all();

        if ($request->file("imagens")) {            

            $files = $request->file("imagens");          

                $imageName = time() . '_' . $files->getClientOriginalName();
                $data["veiculo_id"] = $request->veiculo_id;
                $data["imagens"] = $imageName;
                $files->move(\public_path("/imagens/veiculos"), $imageName);
                $updateImagem->update($data);
        }

       return redirect()->route('ativo.veiculo.detalhes', $request->veiculo_id)->with('success', 'Imagem Editado com sucesso.');

    }    

    public function deleteimage($id)
    {       

        $images = VeiculoImagens::findOrFail($id);

        if (File::exists("imagens/veiculos/" . $images->image)) {
            File::delete("imagens/veiculos/" . $images->image);
        }        

        if (VeiculoImagens::find($id)->delete()) {
            return redirect()->route('ativo.veiculo.detalhes', $images->veiculo_id)->with('success', 'Imagem excluído com sucesso.');

        } else {
            return redirect()->route('ativo.veiculo.detalhes', $images->veiculo_id)->with('fail', 'Problemas ao excluir a imagem.');

        }

    }    

    // FIM DA GALERIA DE IMAGENS   

    public function storeMarca(Request $request)
    {
        $data = $request->all();
        $save = MarcaMaquina::create($data);
        $userLog = Auth::user()->email;

        Log::channel('main')->info($userLog .' | ADD MARCA MÁQUINA: ' . $save->marca);

        if ($save) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['fail' => true]);            
        }

    }    

    public function anexo($id_ativo_externo = null)
    {

        if (!$id_ativo_externo) {
            return redirect()->url('admin/ativo/veiculo')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
        ->where('id_modulo', 14 )
        ->get();

        if (!$anexo) {
           echo "Deu ruim!!!!!!!";
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }

    }

}

