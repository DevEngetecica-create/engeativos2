<?php


namespace App\Repositories;

use App\Interfaces\VeiculoAcessoriosRepositoryInterface;
use App\Models\VeiculoAcessorios;
use Illuminate\Support\Facades\Log;

class VeiculoAcessoriosRepository implements VeiculoAcessoriosRepositoryInterface
{
    public function getAll()
    {
        return VeiculoAcessorios::all();
    }

    public function getById($id)
    {
        return VeiculoAcessorios::findOrFail($id);
    }

    public function create(array $data)
    {
        $acessorio = VeiculoAcessorios::create($data);
        Log::info('Acessório criado', ['acessorio' => $acessorio]);
        return $acessorio;
    }

    public function update($id, array $data)
    {
        $acessorio = VeiculoAcessorios::findOrFail($id);
        $acessorio->update($data);
        Log::info('Acessório atualizado', ['acessorio' => $acessorio]);
        return $acessorio;
    }

    public function delete($id)
    {
        $acessorio = VeiculoAcessorios::findOrFail($id);
        $acessorio->delete();
        Log::info('Acessório deletado', ['acessorio' => $acessorio]);
        return $acessorio;
    }

    public function search($keyword)
    {
        return VeiculoAcessorios::where('nome_acessorio', 'like', "%$keyword%")
                                ->orWhere('n_serie', 'like', "%$keyword%")
                                ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoAcessorios::paginate($perPage);
    }
}