<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veiculo;
use App\Models\CadastroFornecedor;
use App\Models\Preventiva;
use App\Models\Servico;
use App\Models\VeiculoManutencao;
use App\Models\VeiculoQuilometragem;
use App\Models\CadastroObra;
use App\Models\VeiculoImagens;
use App\Models\Anexo;
use App\Models\VeiculoHorimetro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class VeiculoManutencaoController extends Controller
{

    public function index(Request $request, Veiculo $veiculo)
    {
        $fornecedores = CadastroFornecedor::orderBy('id')->get();
        $idManutencoes = VeiculoManutencao::where('veiculo_id', $veiculo->id)->get();
    
        // Obter a data atual
        $dataAtual = Carbon::now();
    
        // Obter o número de meses até a data atual
        $mesesAtuais = $dataAtual->month ?? $request->mes;
    
        // Obter o ano atual
        $anoAtual = $request->ano ?? Carbon::now()->year;
    
        // Calcular o custo anual dos valores do serviço
        $custoAnualManutencao = VeiculoManutencao::selectRaw('YEAR(data_de_execucao) as mesCustoAnoManut')
            ->selectRaw('SUM(CAST(REPLACE(REPLACE(valor_do_servico, ".", ""), ",", ".") AS DECIMAL(13,2))) as custoAnoManut')
            ->where('veiculo_id', $veiculo->id)
            ->groupBy('mesCustoAnoManut')
            ->get();
    
        // Calcular a média dos valores do serviço
        $mediaCustoManutencao = VeiculoManutencao::select(DB::raw('SUM(CAST(REPLACE(REPLACE(valor_do_servico, ".", ""), ",", ".") AS DECIMAL(13,4))) as mediaCustoManutencao'))
            ->whereNull('deleted_at')
            ->where('veiculo_id', $veiculo->id)
            ->whereYear('data_de_execucao', $anoAtual)
            ->get();
    
        // Dividir a média pelo número de meses atuais
        if ($mesesAtuais > 0) {
            $mediaCustoManutencao[0]->mediaCustoManutencao /= 12;
        }
    
        $custoManutencao = VeiculoManutencao::select(DB::raw('SUM(CAST(REPLACE(REPLACE(valor_do_servico, ".", ""), ",", ".") AS DECIMAL(13,2))) as count'))
            ->selectRaw('MONTH(data_de_execucao) as month')
            ->where('veiculo_id', $veiculo->id)
            ->whereYear('data_de_execucao', $anoAtual)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        $labels = [];
        $data = [];
        $colors = ['#6495ED', '#228B22', '#6C7B8B', '#96CDCD', '#DB7093', '#FFA500', '#FFD700', '#8B658B', '#008B8B', '#CD661D', '#FFD700', '#90EE90'];
    
        for ($i = 1; $i <= 12; $i++) {
            $month = date('M', mktime(0, 0, 0, $i, 1));
            $count = 0;
    
            foreach ($custoManutencao as $custoManutencaoMes) {
                if ($custoManutencaoMes->month == $i) {
                    $count = $custoManutencaoMes->count;
                    break;
                }
            }
    
            array_push($data, $count);
        }
    
        $mesesEmIngles = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    
        $mesesFormatados = array_map(function ($mes) {
            return Carbon::createFromFormat('M', $mes)->translatedFormat('M');
        }, $mesesEmIngles);
    
        $TestemediaCustoManutencao = $mediaCustoManutencao[0]->mediaCustoManutencao;
        $mesesMediaCustoManutencao = array_fill(0, 12, $TestemediaCustoManutencao);
    
        $dataSets = [
            [
                'label' => 'Custo mensal',
                'data' => $data,
                'backgroundColor' => $colors,
                'type' => "bar",
            ],
           /* [
                'label' => "Media acumulada",
                'type' => "line",
                'borderColor' => "#FFA500",
                'backgroundColor' => "#FFA500",
                'data' => $mesesMediaCustoManutencao,
                'fill' => false,
            ],*/
        ];
    
        return view('pages.ativos.veiculos.manutencao.index', compact('idManutencoes', 'veiculo', 'fornecedores', 'dataSets', 'mesesFormatados', 'mediaCustoManutencao', 'mesesMediaCustoManutencao', 'custoAnualManutencao'));
    }



    public function list(Request $request, Veiculo $veiculo)
    {

        $listPage = request('lista') ?? 7;

        $data_inicio = $request->data_inicio ?? null;

        $data_fim = $request->data_fim ?? null;

        $search = $request->search;

        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();

        $servicos = Servico::select('id', 'nomeServico')->get();

        if ($data_inicio) {

            $manutencoes = VeiculoManutencao::where('veiculo_id', $veiculo->id)
                ->whereDate('data_de_execucao', '>=', $data_inicio)
                ->whereDate('data_de_execucao', '<=', $data_fim)
                ->orderBy('data_de_execucao', 'DESC')
                ->with('veiculo', 'servico', 'fornecedor')
                ->paginate($listPage);

        } elseif ($search) {
            $manutencoes = VeiculoManutencao::where('veiculo_id', $veiculo->id)->when(request('search') != null, function ($query) {
                return  $query->where('fornecedor_id', 'like', '%' . request('search') . '%');
            })
            ->with('veiculo', 'servico', 'fornecedor')
            ->paginate($listPage);
        } else {


            $manutencoes = VeiculoManutencao::where('veiculo_id', $veiculo->id)->when(request('search') != null, function ($query) {
                return  $query->where('fornecedor_id', 'like', '%' . request('search') . '%');
            })
            ->with('veiculo','servico', 'fornecedor')
            ->paginate($listPage);
        }


        // Retorna as duas views
        return  view('pages.ativos.veiculos.manutencao.partials.list', compact('veiculo', 'servicos', 'manutencoes'));
    }

    public function create(Veiculo $veiculo, Request $request)
    {
        $veiculos = VeiculoManutencao::orderby('veiculo_id', 'DESC')->get();;

        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();

        $servicos = Servico::select('id', 'nomeServico')->get();

        $obras = CadastroObra::all();

        $maiorValorQuilometragem = DB::table('veiculo_quilometragems')->where('veiculo_id', $veiculo->id)
            ->whereNull('deleted_at')
            ->max('quilometragem_nova');

        return view('pages.ativos.veiculos.manutencao.create', compact('veiculo', 'fornecedores', 'servicos', 'obras', 'maiorValorQuilometragem'));
    }

    public function store(Request $request)
    {

        $dataAtual = Carbon::now();

        $request->validate(
            [
                'veiculo_id' => ['required'],
                'fornecedor_id' => ['required'],
                'servico_id' => ['required'],                
                'id_obra' => ['required'],
                'tipo' => ['required'],
                'data_de_execucao' => ['required'],
                'data_de_vencimento' => ['required'],
                'descricao' => ['required'],
                'valor_do_servico' => ['required'],
                'situacao' => ['required'],
            ]
        );

        $saveManutencao = VeiculoManutencao::create(
            [
                'veiculo_id' => $request->veiculo_id,
                'fornecedor_id' => $request->fornecedor_id,
                'servico_id' => $request->servico_id,
                'id_usuario' => Auth::user()->id,
                'id_obra' => $request->id_obra,
                'tipo' => $request->tipo,
                'quilometragem_atual' => $request->quilometragem_atual ?? null,
                'quilometragem_nova' => $request->quilometragem_nova ?? null,
                'horimetro_atual' => $request->horimetro_atual ?? null,
                'horimetro_proximo' => $request->horimetro_proximo ?? null,
                'data_de_execucao' => $request->data_de_execucao,
                'data_de_vencimento' => $request->data_de_vencimentov ?? now(),
                'descricao' => $request->descricao,
                'valor_do_servico' => str_replace('R$ ', '', $request->valor_do_servico),
                'situacao' => $request->situacao,
            ]
        );

        $saveManutencao->save();

        //cadastrar na tebale de horimetro
        if ($request->veiculo_tipo == 'maquinas') {

            $saveHorimetro =  VeiculoHorimetro::create([
                'veiculo_id' => $request->veiculo_id,
                'horimetro_atual' => $request->horimetro_atual ?? 0,
                'horimetro_novo' => $request->horimetro_proximo ?? 0,
                'data_horimetro' => $dataAtual->format('d/m/y'),
                'usuario' => Auth::user()->id,
            ]);

            $saveHorimetro->save();
        } else { // cadastrar na tabela de quilometragem

            $saveQuilometragem =  VeiculoQuilometragem::create([
                'veiculo_id' => $request->veiculo_id,
                'quilometragem_atual' => $request->quilometragem_atual ?? 0,
                'quilometragem_nova' => $request->quilometragem_nova ?? 0,
                'data_cadastro' => $dataAtual->format('d/m/y'),
                'usuario' => Auth::user()->email,
            ]);

            $saveQuilometragem->save();
        }

        //verificar se tem serviço de preventiva = 4
        if ($request->servico_id == 4) {

            $servico = Servico::find($request->servico_id);

            $descricao = 'Serviço realizado: ' . $servico->nomeServico . ' | Preventiva: ' . $request->tipo;

            $preventiva = Preventiva::create(
                [
                    'veiculo_id' => $request->veiculo_id,
                    'user_id' => Auth::user()->id,
                    'manutencao_id' => $saveManutencao->id,
                    'preventiva' => $request->quilometragem_nova,
                    'descricao' =>  $descricao,
                ]
            );

            $preventiva->save();
        } else { // se não tiver não faz nada


        }

        //salvar as imagens da manutençãpo
        if ($request->hasFile("imagens")) {

            $files = [];
            $files = $request->file("imagens");

            foreach ($files as $file) {

                $cadImagem = new VeiculoImagens();
                $cadImagem->idServico = $saveManutencao->id;
                $imageName = time() . '_' . $file->getClientOriginalName();
                $cadImagem->veiculo_id = $request->veiculo_id;
                $cadImagem->imagens = $imageName;
                $file->move(\public_path("/imagens/manutencao_veiculos"), $imageName);

                $cadImagem->save();
            }
        }


        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | STORE MANUTENCAO: ' . $saveManutencao->id);

        if ($saveManutencao) {


            return redirect()->route('ativo.veiculo.manutencao.show', $saveManutencao->id)->with('success', 'Registro salvo com sucesso.');
        } else {

            return redirect()->route('ativo.veiculo.manutencao.index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro.');
        }
    }


    public function show(Veiculo $veiculos, $id)
    {

        $manutencoes = VeiculoManutencao::with('veiculo', 'fornecedor', 'servico', 'funcionario', 'obra', 'situacoes')->find($id);

        $obras = CadastroObra::select('id', 'codigo_obra', 'razao_social')->orderByDesc('id')->get();

        $imagens = VeiculoImagens::where('idServico', $id)
            ->get();

        return view('pages.ativos.veiculos.manutencao.show', compact('veiculos', 'obras', 'imagens', 'manutencoes'));
    }

    public function edit($id)
    {
        if (!$manutencao = VeiculoManutencao::with('veiculo', 'fornecedor', 'servico')->find($id)) {
            return redirect()->route('ativo.veiculo')->with('fail', 'Manutenção não encontrada');
        }

        $fornecedores = CadastroFornecedor::select('id', 'razao_social')->get();

        $servicos = Servico::select('id', 'nomeServico')->get();

        return view('pages.ativos.veiculos.manutencao.edit', compact('manutencao', 'fornecedores', 'servicos'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        if (!$save = VeiculoManutencao::find($id)) {
            return redirect()->route('ativo.veiculo.manutencao.editar', $id)->with('fail', 'Problemas para localizar o registro.');
        }
        $data = $request->all();
        $data['valor_do_servico'] = str_replace('R$ ', '', $request->valor_do_servico);

        $save->update($data);

        $servico = Servico::find($request->servico_id);

        Preventiva::firstOrCreate([

            'manutencao_id' => $request->id

        ]);



        $up_preventiva = Preventiva::where('manutencao_id', $save->id)->first();

        $preventiva['veiculo_id'] = $request->veiculo_id;
        $preventiva['manutencao_id'] = $request->id;


        if ($request->veiculo_tipo == 'maquinas') {
            $preventiva['preventiva'] = $request->horimetro_proximo ?? 0;


            VeiculoQuilometragem::firstOrCreate([
                'veiculo_id' => $request->veiculo_id,
                'quilometragem_atual' => $request->horimetro_atual,
                'quilometragem_nova' => $request->horimetro_atual
            ]);
        } else {

            $preventiva['preventiva'] = $request->quilometragem_nova ?? 0;

            VeiculoQuilometragem::firstOrCreate([
                'veiculo_id' => $request->veiculo_id,
                'quilometragem_atual' => $request->quilometragem_atual,
                'quilometragem_nova' => $request->quilometragem_atual
            ]);
        }

        $preventiva['descricao'] = 'Serviço realizado: ' . $servico->nomeServico . ' | Preventiva: ' . $request->tipo;



        $up_preventiva->update($preventiva);


        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | EDIT MANUTENCAO: ' . $save->id);

        if ($save && $up_preventiva) {
            return redirect()->route('ativo.veiculo.manutencao.index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso.');
        } else {
            return redirect()->route('ativo.veiculo.manutencao.index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro.');
        }
    }

    public function delete($id)
    {
        $manutencao = VeiculoManutencao::findOrFail($id);

        Preventiva::where('manutencao_id', $manutencao->id)->delete();

        if ($manutencao->delete()) {

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | DELETE MANUTENCAO: ' . $manutencao->id);

            return redirect()->route('ativo.veiculo.manutencao.index', $manutencao->veiculo_id)->with('success', 'Registro excluído com sucesso');
        } else {
            return redirect()->route('ativo.veiculo.manutencao.index', $manutencao->veiculo_id)->with('fail', 'Problema nas exclusão do registro');
        }
    }

    public function cancel($id)
    {

        if (!$save = VeiculoManutencao::find($id)) {
            return redirect()->route('ativo.veiculo.manutencao.index', $save->veiculo_id)->with('fail', 'Problemas para localizar o registro.');
        }
        $save->update(['situacao' => 4]);

        if ($save) {
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog . ' | CANCEL MANUTENCAO: ' . $save->id);
            return redirect()->route('ativo.veiculo.manutencao.index', $save->veiculo_id)->with('success', 'Registro salvo com sucesso.');
        } else {
            return redirect()->route('ativo.veiculo.manutencao.index', $save->veiculo_id)->with('fail', 'Erro ao salvar registro.');
        }
    }

    public function storeServico(Request $request)
    {
        $data = $request->all();
        $save = Servico::create($data);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD SERVICO MANUTENCAO: ' . $save->nomeServico);

        if ($save) {
            return response()->json(['success' => true, 'servico_id' => $save->id, 'servico' => $save->nomeServico]);
        } else {
            return response()->json(['fail' => true]);
        }
    }

    public function anexo($id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->route('ativo.veiculo.index')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
            ->where('id_modulo', 28)
            ->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }
    }

    // INICIO DA GALERIA DE IMAGENS

    public function storeimagem(Request $request, $id)
    {
        //dd($request->imagens1);
        if ($request->hasFile("imagens1")) {

            $files = [];
            $files = $request->file("imagens1");

            foreach ($files as $file) {

                $cadImagem = new VeiculoImagens();
                $imageName = time() . '_' . $file->getClientOriginalName();
                $cadImagem->veiculo_id = $request->veiculo_id;
                $cadImagem->imagens = $imageName;
                $cadImagem->idServico = $id;
                $file->move(\public_path("/imagens/manutencao_veiculos"), $imageName);

                $cadImagem->save();
            }
        }

        return redirect()->route('ativo.veiculo.manutencao.show', $id)->with('success', 'Imagem adicionada com sucesso.');
    }

    public function updateImagem(Request $request, $id)
    {
        //dd($id);
        $updateImagem = VeiculoImagens::find($id);
        $data = $request->all();

        if ($request->hasFile("imagens")) {

            $files = $request->file("imagens");

            $imageName = time() . '_' . $files->getClientOriginalName();
            $data["veiculo_id"] = $request->veiculo_id;
            $data["imagens"] = $imageName;
            $files->move(\public_path("/imagens/manutencao_veiculos"), $imageName);

            $updateImagem->update($data);
        }

        return redirect()->route('ativo.veiculo.manutencao.show', $updateImagem->idServico)->with('success', 'Imagem Editado com sucesso.');
    }

    public function deleteimage($id)
    {

        $deleteImages = VeiculoImagens::findOrFail($id);
        if (File::exists("imagens/manutencao_veiculos/" . $deleteImages->image)) {
            File::delete("imagens/manutencao_veiculos/" . $deleteImages->image);
        }

        if (VeiculoImagens::find($id)->delete()) {
            return redirect()->route('ativo.veiculo.manutencao.show', $deleteImages->idServico)->with('success', 'Imagem excluído com sucesso.');
        } else {
            return redirect()->route('ativo.veiculo.manutencao.show', $deleteImages->idServico)->with('fail', 'Problemas ao excluir a imagem.');
        }
    }

    // FIM DA GALERIA DE IMAGENS

}
