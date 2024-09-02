<?php

namespace App\Repositories;

use App\Interfaces\VeiculoSubCategoriaRepositoryInterface;
use App\Models\VeiculoSubCategoria;
use Illuminate\Support\Facades\Log;

class VeiculoSubCategoriaRepository implements VeiculoSubCategoriaRepositoryInterface
{
    public function getAll()
    {
        return VeiculoSubCategoria::all();
    }

    public function getById($id)
    {
        return VeiculoSubCategoria::findOrFail($id);
    }

    public function create(array $data)
    {
        $subcategoria = VeiculoSubCategoria::create($data);
        Log::info('Subcategoria criada', ['subcategoria' => $subcategoria]);
        return $subcategoria;
    }

    public function update($id, array $data)
    {
        $subcategoria = VeiculoSubCategoria::findOrFail($id);
        $subcategoria->update($data);
        Log::info('Subcategoria atualizada', ['subcategoria' => $subcategoria]);
        return $subcategoria;
    }

    public function delete($id)
    {
        $subcategoria = VeiculoSubCategoria::findOrFail($id);
        $subcategoria->delete();
        Log::info('Subcategoria deletada', ['subcategoria' => $subcategoria]);
        return $subcategoria;
    }

    public function search($keyword)
    {
        return VeiculoSubCategoria::where('nomeSubCategoria', 'like', "%$keyword%")
                                  ->orWhere('statusSubCategoria', 'like', "%$keyword%")
                                  ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoSubCategoria::paginate($perPage);
    }
}