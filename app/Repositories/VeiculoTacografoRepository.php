<?php


namespace App\Repositories;

use App\Interfaces\VeiculoTacografoRepositoryInterface;
use App\Models\VeiculoTacografo;
use Illuminate\Support\Facades\Log;

class VeiculoTacografoRepository implements VeiculoTacografoRepositoryInterface
{
    public function getAll()
    {
        return VeiculoTacografo::all();
    }

    public function getById($id)
    {
        return VeiculoTacografo::findOrFail($id);
    }

    public function create(array $data)
    {
        $tacografo = VeiculoTacografo::create($data);
        Log::info('Tacógrafo criado', ['tacografo' => $tacografo]);
        return $tacografo;
    }

    public function update($id, array $data)
    {
        $tacografo = VeiculoTacografo::findOrFail($id);
        $tacografo->update($data);
        Log::info('Tacógrafo atualizado', ['tacografo' => $tacografo]);
        return $tacografo;
    }

    public function delete($id)
    {
        $tacografo = VeiculoTacografo::findOrFail($id);
        $tacografo->delete();
        Log::info('Tacógrafo deletado', ['tacografo' => $tacografo]);
        return $tacografo;
    }

    public function search($keyword)
    {
        return VeiculoTacografo::where('descricao', 'like', "%$keyword%")
            ->orWhere('observacao', 'like', "%$keyword%")
            ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoTacografo::paginate($perPage);
    }
}
