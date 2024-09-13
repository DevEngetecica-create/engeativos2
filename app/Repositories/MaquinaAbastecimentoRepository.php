<?php

namespace App\Repositories;

use App\Interfaces\MaquinaAbastecimentoRepositoryInterface;
use App\Models\MaquinasAbastecimento;
use Illuminate\Support\Facades\Log;

class MaquinaAbastecimentoRepository implements MaquinaAbastecimentoRepositoryInterface
{
    public function getAll()
    {
        return MaquinasAbastecimento::all();
    }

    public function getById($id)
    {
        return MaquinasAbastecimento::findOrFail($id);
    }

    public function create(array $data)
    {
        $abastecimento = MaquinasAbastecimento::create($data);
        Log::info('Abastecimento criado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function update($id, array $data)
    {
        $abastecimento = MaquinasAbastecimento::findOrFail($id);
        $abastecimento->update($data);
        Log::info('Abastecimento atualizado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function delete($id)
    {
        $abastecimento = MaquinasAbastecimento::findOrFail($id);
        $abastecimento->delete();
        Log::info('Abastecimento deletado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function search($keyword)
    {
        return MaquinasAbastecimento::where('fornecedor', 'like', "%$keyword%")
                                   ->orWhere('combustivel', 'like', "%$keyword%")
                                   ->get();
    }

    public function paginate($perPage)
    {
        return MaquinasAbastecimento::paginate($perPage);
    }
}