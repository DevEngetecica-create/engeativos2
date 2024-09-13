<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    AtivoConfiguracao,
    AtivoExternoEstoque,
    AtivoExterno,
    AtivoExternoEstoqueItem,
    AtivosExternosStatus,
    CadastroEmpresa,
    CadastroObra,
    Anexo
};


use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};
use Session;
use DB;

class CalibracaoController extends Controller
{
    
    public function index(AtivoExternoEstoque $ativoExternoEstoque, $id)
    {
       
    
         if (!$id) {
            return redirect()->route('ativo.externo.index')->with('fail', 'Problemas para localizar o ativo.');
        }
        
        $ativoExternoEstoque = AtivoExternoEstoque::where('id', $id)->get();
        $anexo = Anexo::where('id_item', $id)->get();
        $dadosAtivoExterno = AtivoExternoEstoque::with('obra', 'situacao', 'ativo_externo')->where('id', $id)->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('pages.ativos.externos.partials.index', compact('anexo','dadosAtivoExterno','ativoExternoEstoque'));
        }
        
        
    }
    
    
    public function create(AtivoExternoEstoque $ativoExternoEstoque)
    {
        
       
        return view('pages.ativos.externos.partials.calibracaoCreate', compact('ativoExternoEstoque'));
    }

    public function store(Request $request, $input = "arquivo")
    {
        
        try{
            
           $file= $request->file("arquivo");
           
           if($request->file('arquivo')== ""){
               
                return back()->with('fail', "Por favor insira um arquivo")->withInput();
               
            }else{
                
               $tipo= $request->file("arquivo")->getClientOriginalExtension();
               $arquivo= $request->file("arquivo")->getClientOriginalName();
            }
            
           
            if ($request->hasFile("arquivo")) {
                
                $request->file('arquivo')->storeAs('uploads/ativo_externo', $arquivo, 'public');
                
            }
            
            //dd($request->data_calibracao);
            
            $anexo = new Anexo([
                           'id_modulo'=> $request->id_modulo,
                           'id_item'=> $request->id_item,
                           'titulo'=> $request->titulo ?? null,
                           'data_vencimento'=> $request->data_vencimento ?? null,
                           'data_calibracao'=> $request->data_calibracao ?? null,
                           'nome_empresa'=> $request->nome_empresa ?? null,
                           'tipo'=> $tipo,
                           'arquivo'=> $arquivo,
                           'descricao'=> $request->detalhes ?? null,
                           'nome_modulo'=> 'ativo_externo',
                        ]);
            
            $anexo->save();
            
            return redirect()->route('ativo.externo.calibracao',$request->id_item)->with('success', 'Registro cadastrado com sucesso.');
            
            }
            
            catch(\Exception $e)
            
            {
                
                return back()->with('fail', $e->getMessage())->withInput();
        }
     
    }
    
    
    
    
    public function edit($id)
    {
        $calibracao = Anexo::find($id);
      
       
        return view('pages.ativos.externos.partials.calibracaoForm', compact('calibracao'));
    }
    
    public function update(Request $request, $id, $input = "arquivo")
    {
        
        try{
            
           $anexo= Anexo::findOrFail($id);
           $file= $request->file("arquivo");
           
           if($request->file('arquivo')== ""){
               
               $arquivo= $anexo->arquivo;
               $tipo= $anexo->tipo;
               
            }else{
                
               $tipo= $request->file("arquivo")->getClientOriginalExtension();
               $arquivo= $request->file("arquivo")->getClientOriginalName();
            }
            
           
            if ($request->hasFile("arquivo")) {
            
            if (File::exists("uploads/ativo_externo/" . $anexo->arquivo)) {
                File::delete("uploads/ativo_externo/" . $anexo->arquivo);
            }
                
                $request->file('arquivo')->storeAs('uploads/ativo_externo', $arquivo, 'public');
                 
                $request['arquivo']= $anexo->arquivo;
            }
            
            $anexo->update([
               'id_modulo'=> $request->id_modulo,
               'id_item'=> $request->id_item,
               'titulo'=> $request->titulo ?? null,
               'data_vencimento'=> $request->data_vencimento ?? null,
               'data_calibracao'=> $request->data_calibracao ?? null,
               'nome_empresa'=> $request->nome_empresa ?? null,
               'tipo'=> $tipo,
               'arquivo'=> $arquivo,
               'descricao'=> $request->detalhes ?? null,
               'nome_modulo'=> 'ativo_externo',
                ]);
            
            return redirect()->route('ativo.externo.calibracao',$request->id_item)->with('success', 'Registro editado com sucesso.');
            
            }
            
            catch(\Exception $e)
            
            {
                
                return back()->with('fail', $e->getMessage())->withInput();
        }
    }
    

    
     public function destroy(Request $request)
    { 
        
    }

}
























































