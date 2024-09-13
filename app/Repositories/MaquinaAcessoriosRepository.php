<?php


namespace App\Repositories;

use App\Interfaces\MaquinaAcessoriosRepositoryInterface;
use App\Models\MaquinasAcessorios;
use Illuminate\Support\Facades\Log;

class MaquinaAcessoriosRepository implements MaquinaAcessoriosRepositoryInterface
{
    public function getAll()
    {
        return MaquinasAcessorios::all();
    }

    public function getById($id)
    {
        return MaquinasAcessorios::findOrFail($id);
    }

    public function create(array $data)
    {
        $acessorio = MaquinasAcessorios::create($data);
        Log::info('Acessório criado', ['acessorio' => $acessorio]);
        return $acessorio;
    }

    public function update($id, array $data)
    {
        $acessorio = MaquinasAcessorios::findOrFail($id);
        $acessorio->update($data);
        Log::info('Acessório atualizado', ['acessorio' => $acessorio]);
        return $acessorio;
    }

    public function delete($id)
    {
        $acessorio = MaquinasAcessorios::findOrFail($id);
        $acessorio->delete();
        Log::info('Acessório deletado', ['acessorio' => $acessorio]);
        return $acessorio;
    }

    public function search($keyword)
    {
        return MaquinasAcessorios::where('nome_acessorio', 'like', "%$keyword%")
                                ->orWhere('n_serie', 'like', "%$keyword%")
                                ->get();
    }

    public function paginate($perPage)
    {
        return MaquinasAcessorios::paginate($perPage);
    }
}