<?php

namespace App\Repositories;

use App\Interfaces\MaquinaHorimetroRepositoryInterface;
use App\Models\MaquinasHorimetro;
use Illuminate\Support\Facades\Log;

class MaquinaHorimetroRepository implements MaquinaHorimetroRepositoryInterface
{
    public function getAll()
    {
        return MaquinasHorimetro::all();
    }

    public function getById($id)
    {
        return MaquinasHorimetro::findOrFail($id);
    }

    public function create(array $data)
    {
        $horimetro = MaquinasHorimetro::create($data);
        Log::info('Horimetro criado', ['Horimetro' => $horimetro]);
        return $horimetro;
    }

    public function update($id, array $data)
    {
        $horimetro = MaquinasHorimetro::findOrFail($id);
        $horimetro->update($data);
        Log::info('Horimetro atualizado', ['horimetro' => $horimetro]);
        return $horimetro;
    }

    public function delete($id)
    {
        $horimetro = MaquinasHorimetro::findOrFail($id);
        $horimetro->delete();
        Log::info('Horimetro deletado', ['horimetro' => $horimetro]);
        return $horimetro;
    }

    public function search($keyword)
    {
        return MaquinasHorimetro::where('horimetro_atual', 'like', "%$keyword%")
                                   ->orWhere('horimetro_novo', 'like', "%$keyword%")
                                   ->get();
    }

    public function paginate($perPage)
    {
        return MaquinasHorimetro::paginate($perPage);
    }
}