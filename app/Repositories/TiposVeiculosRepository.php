<?php

namespace App\Repositories;

use App\Interfaces\TiposVeiculosRepositoryInterface;
use App\Models\TiposVeiculos;
use Illuminate\Support\Facades\Auth;

class TiposVeiculosRepository implements TiposVeiculosRepositoryInterface
{
    public function index()
    {
        return TiposVeiculos::all();
    }

    public function getAll()
    {
        return TiposVeiculos::all();
    }


    public function create($data)
    {
        return TiposVeiculos::create($data);
    }

    public function store($data)
    {
        
        $save_tipo_veiculo = new TiposVeiculos;
        $save_tipo_veiculo->nome_tipo_veiculo = $data['nome_tipo_veiculo'];  // Corrigido para acessar o array
        $save_tipo_veiculo->tipo_veiculo = strtolower($data['nome_tipo_veiculo']);
        $save_tipo_veiculo->user_create = Auth::user()->email ?? "samuel@email.com";

       //dd($data);

        return $save_tipo_veiculo->save();
    }

    public function edit(int $id)
    {
        return TiposVeiculos::findOrFail($id);
    }
    

    public function update(int $id, $data)
    {
        $edit_tipo_veiculo = TiposVeiculos::findOrFail($id);

        $edit_tipo_veiculo->nome_tipo_veiculo = $data['nome_tipo_veiculo'];  // Corrigido para acessar o array
        $edit_tipo_veiculo->tipo_veiculo = strtolower($data['nome_tipo_veiculo']);
        $edit_tipo_veiculo->user_edit = Auth::user()->email ?? "samuel_outro@email.com";

        $edit_tipo_veiculo->update($data);

        return $edit_tipo_veiculo;
    }

    public function show(int $id)
    {
        return TiposVeiculos::findOrFail($id);
    }

    public function delete(int $id)
    {
        $doc = TiposVeiculos::findOrFail($id);
        $doc->delete();
        return $doc;
    }

    public function search(string $query)
    {
        return TiposVeiculos::where('nome_documento', 'like', '%' . $query . '%')->get();
    }

    public function paginate(int $perPage)
    {
        return TiposVeiculos::paginate($perPage);
    }
}
