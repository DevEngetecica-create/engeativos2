<?php

namespace App\Repositories;

use App\Interfaces\VeiculoAbastecimentoRepositoryInterface;
use App\Models\VeiculoAbastecimento;
use Illuminate\Support\Facades\Log;

class VeiculoAbastecimentoRepository implements VeiculoAbastecimentoRepositoryInterface
{
    public function getAll()
    {
        return VeiculoAbastecimento::all();
    }

    public function getById($id)
    {
        return VeiculoAbastecimento::findOrFail($id);
    }

    public function create(array $data)
    {
        $abastecimento = VeiculoAbastecimento::create($data);
        Log::info('Abastecimento criado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function update($id, array $data)
    {
        $abastecimento = VeiculoAbastecimento::findOrFail($id);
        $abastecimento->update($data);
        Log::info('Abastecimento atualizado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function delete($id)
    {
        $abastecimento = VeiculoAbastecimento::findOrFail($id);
        $abastecimento->delete();
        Log::info('Abastecimento deletado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function search($keyword)
    {
        return VeiculoAbastecimento::where('fornecedor', 'like', "%$keyword%")
                                   ->orWhere('combustivel', 'like', "%$keyword%")
                                   ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoAbastecimento::paginate($perPage);
    }
}