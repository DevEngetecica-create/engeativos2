<?php

namespace App\Repositories;

use App\Interfaces\MaquinaManutencaoRepositoryInterface;
use App\Models\MaquinasManutencaos;
use Illuminate\Support\Facades\Log;

class MaquinaManutencaoRepository implements MaquinaManutencaoRepositoryInterface
{
    public function getAll()
    {
        return MaquinasManutencaos::all();
    }

    public function getById($id)
    {
        return MaquinasManutencaos::findOrFail($id);
    }

    public function create(array $data)
    {
        $manutencao = MaquinasManutencaos::create($data);
        Log::info('Manutenção criada', ['manutencao' => $manutencao]);
        return $manutencao;
    }

    public function update($id, array $data)
    {
        $manutencao = MaquinasManutencaos::findOrFail($id);
        $manutencao->update($data);
        Log::info('Manutenção atualizada', ['manutencao' => $manutencao]);
        return $manutencao;
    }

    public function delete($id)
    {
        $manutencao = MaquinasManutencaos::findOrFail($id);
        $manutencao->delete();
        Log::info('Manutenção deletada', ['manutencao' => $manutencao]);
        return $manutencao;
    }

    public function search($keyword)
    {
        return MaquinasManutencaos::where('descricao', 'like', "%$keyword%")
                                ->orWhere('tipo', 'like', "%$keyword%")
                                ->get();
    }

    public function paginate($perPage)
    {
        return MaquinasManutencaos::paginate($perPage);
    }
}