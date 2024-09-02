<?php

namespace App\Repositories;

use App\Interfaces\VeiculoSeguroRepositoryInterface;
use App\Models\VeiculoSeguro;
use Illuminate\Support\Facades\Log;

class VeiculoSeguroRepository implements VeiculoSeguroRepositoryInterface
{
    public function getAll()
    {
        return VeiculoSeguro::all();
    }

    public function getById($id)
    {
        return VeiculoSeguro::findOrFail($id);
    }

    public function create(array $data)
    {
        $seguro = VeiculoSeguro::create($data);
        Log::info('Seguro criado', ['seguro' => $seguro]);
        return $seguro;
    }

    public function update($id, array $data)
    {
        $seguro = VeiculoSeguro::findOrFail($id);
        $seguro->update($data);
        Log::info('Seguro atualizado', ['seguro' => $seguro]);
        return $seguro;
    }

    public function delete($id)
    {
        $seguro = VeiculoSeguro::findOrFail($id);
        $seguro->delete();
        Log::info('Seguro deletado', ['seguro' => $seguro]);
        return $seguro;
    }

    public function search($keyword)
    {
        return VeiculoSeguro::where('nome_seguradora', 'like', "%$keyword%")
                            ->orWhere('valor', 'like', "%$keyword%")
                            ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoSeguro::paginate($perPage);
    }
}