<?php

namespace App\Repositories;

use App\Interfaces\VeiculoIpvaRepositoryInterface;
use App\Models\VeiculoIpva;
use Illuminate\Support\Facades\Log;

class VeiculoIpvaRepository implements VeiculoIpvaRepositoryInterface
{
    public function getAll()
    {
        return VeiculoIpva::all()->orderBy('id', 'desc');
    }

    public function getById($id)
    {
        return VeiculoIpva::findOrFail($id);
    }

    public function create(array $data)
    {
        $ipva = VeiculoIpva::create($data);
        Log::info('IPVA criado', ['ipva' => $ipva]);
        return $ipva;
    }

    public function update($id, array $data)
    {
        $ipva = VeiculoIpva::findOrFail($id);
        $ipva->update($data);
        Log::info('IPVA atualizado', ['ipva' => $ipva]);
        return $ipva;
    }

    public function delete($id)
    {
        $ipva = VeiculoIpva::findOrFail($id);
        $ipva->delete();
        Log::info('IPVA deletado', ['ipva' => $ipva]);
        return $ipva;
    }

    public function search($keyword)
    {
        return VeiculoIpva::where('referencia_ano', 'like', "%$keyword%")
                          ->orWhere('nome_anexo_ipva', 'like', "%$keyword%")
                          ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoIpva::paginate($perPage);
    }
}