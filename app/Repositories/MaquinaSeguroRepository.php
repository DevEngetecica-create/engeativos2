<?php

namespace App\Repositories;

use App\Interfaces\MaquinaSeguroRepositoryInterface;
use App\Models\MaquinasSeguros;
use Illuminate\Support\Facades\Log;

class MaquinaSeguroRepository implements MaquinaSeguroRepositoryInterface
{
    public function getAll()
    {
        return MaquinasSeguros::all();
    }

    public function getById($id)
    {
        return MaquinasSeguros::findOrFail($id);
    }

    public function create(array $data)
    {
        $seguro = MaquinasSeguros::create($data);
        Log::info('Seguro criado', ['seguro' => $seguro]);
        return $seguro;
    }

    public function update($id, array $data)
    {
        $seguro = MaquinasSeguros::findOrFail($id);
        $seguro->update($data);
        Log::info('Seguro atualizado', ['seguro' => $seguro]);
        return $seguro;
    }

    public function delete($id)
    {
        $seguro = MaquinasSeguros::findOrFail($id);
        $seguro->delete();
        Log::info('Seguro deletado', ['seguro' => $seguro]);
        return $seguro;
    }

    public function search($keyword)
    {
        return MaquinasSeguros::where('nome_seguradora', 'like', "%$keyword%")
                            ->orWhere('valor', 'like', "%$keyword%")
                            ->get();
    }

    public function paginate($perPage)
    {
        return MaquinasSeguros::paginate($perPage);
    }
}