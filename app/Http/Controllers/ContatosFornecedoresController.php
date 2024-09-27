<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContatoFornecedorRequest;
use Illuminate\Http\Request;
use App\Models\CadastroFornecedor;
use App\Models\ContatoFornecedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContatosFornecedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CadastroFornecedor $cadastroFornecedor)
    {

        $contatos = ContatoFornecedor::where('id_fornecedor', $cadastroFornecedor->id);

        
        //dd($cadastroFornecedor->id);


        return view('pages.cadastros.fornecedor.contatos.index', compact('contatos'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
