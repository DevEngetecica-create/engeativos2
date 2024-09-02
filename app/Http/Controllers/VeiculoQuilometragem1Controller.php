<?php



namespace App\Http\Controllers;



use App\Models\Veiculo;

use App\Models\VeiculoQuilometragem;

use App\Models\VeiculoHorimetro;

use App\Models\CadastroFuncionario;

use App\Models\VeiculoManutencao;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;



class VeiculoQuilometragem1Controller extends Controller

{



    public function index(Veiculo $veiculo)

    {



        if ($veiculo->tipo == "maquinas") {

            $quilometragens = VeiculoHorimetro::with('veiculo', 'usuario')

                ->where('veiculo_id', $veiculo->id)

                ->orderBy('id', 'desc')

                ->get();



            $maiorValorManutencao = VeiculoManutencao::where('veiculo_id', $veiculo->id)

                ->max('horimetro_proximo');



            $maiorValor = VeiculoHorimetro::with('veiculo')

                ->where('veiculo_id', $veiculo->id)

                ->max('horimetro_novo');

            $unidade = "horas";

        } else {



            $quilometragens = VeiculoQuilometragem::with('veiculo', 'usuario')

                ->where('veiculo_id', $veiculo->id)

                ->orderBy('id', 'desc')

                ->get();



            $maiorValorManutencao = VeiculoManutencao::where('veiculo_id', $veiculo->id)

                ->max('horimetro_proximo');



            $maiorValor = VeiculoQuilometragem::with('veiculo')

                ->where('veiculo_id', $veiculo->id)

                ->max('quilometragem_nova');

            $unidade = "km";

        }



        $funcionarios = CadastroFuncionario::all();



        $calculTempoTrabalho = $maiorValorManutencao - $maiorValor;



        if ($calculTempoTrabalho <= 0) {



            $mensagemAviso = 'MISERICÓRDIA!!!! A máquina já esta trabalhando à ' . ($maiorValorManutencao - $maiorValor) * -1 . ' horas sem revisão' ;



            $mensagemAvisoAlert ="";



        } elseif ($calculTempoTrabalho >= 1) {



            $mensagemAviso = 'A próx. revisão será com ' . $calculTempoTrabalho . ' horas';



            $mensagemAvisoAlert = "Restam " . ($maiorValorManutencao - $maiorValor);



            

        }



        return view('pages.ativos.veiculos.quilometragem.index', compact('calculTempoTrabalho','mensagemAvisoAlert','unidade', 'mensagemAviso', 'veiculo', 'quilometragens',  'maiorValorManutencao', 'maiorValor', 'funcionarios'));

    

        

    }





    



    public function create(Veiculo $veiculo)

    {

        $maiorValorTblManutencao = DB::table('veiculo_manutencaos')        

        ->where('veiculo_id',$veiculo->id)

        ->whereNull('deleted_at')

        ->max('horimetro_proximo');



        $funcionarios = CadastroFuncionario::all();

        

        $maiorValorQuilometragem = DB::table('veiculo_quilometragems')->where('veiculo_id',$veiculo->id)

        ->whereNull('deleted_at')

        ->max('quilometragem_nova');

        

        return view('pages.ativos.veiculos.quilometragem.create', compact('veiculo', 'maiorValorQuilometragem', 'maiorValorTblManutencao', 'funcionarios'));

    }



    public function store(Request $request)

    {



       //dd($request->all());

        

        if ($request->veiculo_tipo == "maquinas") {



          $request->validate([

                'horimetro_atual' => 'required',

                'horimetro_novo' => 'required|gte:horimetro_atual',

            ]);

    

            $data = $request->all();

            $data['id_funcionario'] = $request->id_funcionario;

            $data['usuario'] = Auth::user()->email;

            $data['data_horimetro'] = $request->data_cadastro;

            

            $save = VeiculoHorimetro::create($data);

            

        }else{



            $request->validate([

                'quilometragem_atual' => 'required',

                'quilometragem_nova' => 'required|gte:quilometragem_atual',

            ]);

    

            $data = $request->all();

            $data['id_funcionario'] = $request->id_funcionario;

            $data['usuario'] = Auth::user()->email;

            $data['data_quilometragem'] = $request->data_cadastro;

    



            $save = VeiculoQuilometragem::create($data);



        }

       

        if($save){



            $userLog = Auth::user()->email;

            Log::channel('main')->info($userLog .' | STORE QUILOMETRAGEM: ' . $request->veiculo_id);



            return redirect()->route('ativo.veiculo.quilometragem.index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso');





        } else {



            return redirect()->route('ativo.veiculo.quilometragem.index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro');

        }



    }



    public function edit(Request $request, $id)

    {

        

        $tipo = $request->tipo;

        

       // dd($tipo);

        

        if($request->tipo == "maquinas")

        {

            

             $quilometragem = VeiculoHorimetro::find($id);

            

        }else{

           

            

             $quilometragem = VeiculoQuilometragem::with('veiculo')->find($id);

              //dd($quilometragem);

            

             //$quilometragem = VeiculoQuilometragem::with('veiculo')->where('id', $id);

            

        };

        

        



        $funcionarios = CadastroFuncionario::all();



        return view('pages.ativos.veiculos.quilometragem.edit', compact('tipo', 'quilometragem', 'funcionarios'));

    }



    public function update(Request $request, $id)

    {

        



        if ($request->veiculo_tipo == "maquinas") {

            

            if (!$update = VeiculoHorimetro::find($id)) {

                

                return redirect()->route('ativo.veiculo.quilometragem.editar', $id)->with('fail', 'Problemas para localizar o registro.');

            }



            

            $data['horimetro_atual'] = $request->quilometragem_atual;

            $data['horimetro_novo'] = $request->quilometragem_nova;

            

            $data['id_funcionario'] = $request->id_funcionario;

            $data['usuario'] = Auth::user()->email;

            $data['data_horimetro'] = $request->data_cadastro;



            $update->update($data);



        } else {

            

            if (!$update = VeiculoQuilometragem::find($id)) {

                

                return redirect()->route('ativo.veiculo.quilometragem.editar', $id)->with('fail', 'Problemas para localizar o registro.');

            }



            $request->validate([

                'veiculo_id' => 'required',

                'quilometragem_atual' => 'required',

                'quilometragem_nova' => 'required|gte:quilometragem_atual',

            ]);



            

            $data['quilometragem_atual'] = $request->quilometragem_atual;

            $data['quilometragem_nova'] = $request->quilometragem_nova;

            $data['id_funcionario'] = $request->id_funcionario;

            $data['usuario'] = Auth::user()->email;

            $data['data_quilometragem'] = $request->data_cadastro;



            $update->update($data);



        }





        if($update) {

            $userLog = Auth::user()->email;

            Log::channel('main')->info($userLog .' | EDIT QUILOMETRAGEM/HORIMETRO: ' . $id);



            return redirect()->route('ativo.veiculo.quilometragem.index', $request->veiculo_id)->with('success', 'Registro salvo com sucesso.');

        } else {

            return redirect()->route('ativo.veiculo.quilometragem.index', $request->veiculo_id)->with('fail', 'Erro ao salvar registro.');

        }



    }



    public function delete($id)

    {

        $quilometragem = VeiculoQuilometragem::findOrFail($id);



        $userLog = Auth::user()->email;

        Log::channel('main')->info($userLog .' | DELETE QUILOMETRAGEM/HORIMETRO: ' . $quilometragem->id);



        if($quilometragem->delete()) {

            return redirect()->back()->with('success', 'Registro excluído com sucesso.');

        } else {

            return redirect()->back()->with('fail', 'Erro ao excluir registro.');

        }

    }

}

