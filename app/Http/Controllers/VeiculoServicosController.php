<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class VeiculoServicosController extends Controller
{
    public function index(Veiculo $veiculo, Request $request){
        
        $tabela = Servico::orderby('id', 'desc')->get();
        return view('ativo.veiculo.manutencao.index', $request->veiculo_id);
    }

    public function create(){
        return view('servicos.create');
    }


    public function insert(Request $request){
        
        $tabela = new Servico();       
        $tabela->nomeServico = $request->carro;       
        $tabela->save();
        
        return redirect()->route('servicos.index');

    }


    public function edit(servico $item){
        return view('painel-instrutor.servicos.edit', ['item' => $item]);   
     }
 
 
     public function editar(Request $request, servico $item){
         
        $item->nomeServico = $request->nomeServico;
     
        $item->save();
         return redirect()->route('servicos.index');
 
     }


     public function delete(servico $item){
       
        $item->delete();
        
        return redirect()->route('servicos.index');
     }

     public function modal($id){
        $item = servico::orderby('id', 'desc')->paginate();
        return view('pages.ativos.veiculo.manutencao.index', ['itens' => $item, 'id' => $id]);

     }


}
