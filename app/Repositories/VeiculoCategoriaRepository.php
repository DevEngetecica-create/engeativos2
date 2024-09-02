<?php

namespace App\Repositories;

use App\Interfaces\VeiculoCategoriaRepositoryInterface;
use App\Models\VeiculoCategoria;
use Illuminate\Support\Facades\Log;

class VeiculoCategoriaRepository implements VeiculoCategoriaRepositoryInterface
{
    public function getAll()
    {
        return VeiculoCategoria::all();
    }

    public function getById($id)
    {
        return VeiculoCategoria::findOrFail($id);
    }

    public function create(array $data)
    {
        $categoria = VeiculoCategoria::create($data);
        Log::info('Categoria criada', ['categoria' => $categoria]);
        return $categoria;
    }

    public function update($id, array $data)
    {
        $categoria = VeiculoCategoria::findOrFail($id);
        $categoria->update($data);
        Log::info('Categoria atualizada', ['categoria' => $categoria]);
        return $categoria;
    }

    public function delete($id)
    {
        $categoria = VeiculoCategoria::findOrFail($id);
        $categoria->delete();
        Log::info('Categoria deletada', ['categoria' => $categoria]);
        return $categoria;
    }

    public function search($keyword)
    {
        return VeiculoCategoria::where('nomeCategoria', 'like', "%$keyword%")
                               ->orWhere('statusCategoria', 'like', "%$keyword%")
                               ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoCategoria::paginate($perPage);
    }
}