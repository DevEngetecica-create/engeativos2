<?php

namespace App\Repositories;

use App\Interfaces\VeiculoPreventivaRepositoryInterface;
use App\Models\VeiculoPreventiva;

class VeiculoPreventivaRepository implements VeiculoPreventivaRepositoryInterface
{
    public function getAll()
    {
        return VeiculoPreventiva::all();
    }

    public function getById($id)
    {
        return VeiculoPreventiva::findOrFail($id);
    }

    public function create(array $data)
    {
        return VeiculoPreventiva::create($data);
    }

    public function update($id, array $data)
    {
        $preventiva = VeiculoPreventiva::findOrFail($id);
        $preventiva->update($data);
        return $preventiva;
    }

    public function delete($id)
    {
        $preventiva = VeiculoPreventiva::findOrFail($id);
        $preventiva->delete();
        return $preventiva;
    }

    public function search($keyword)
    {
        return VeiculoPreventiva::where('nome_preventiva', 'like', "%$keyword%")
            ->orWhere('nome_servico', 'like', "%$keyword")
            ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoPreventiva::paginate($perPage);
    }
}
