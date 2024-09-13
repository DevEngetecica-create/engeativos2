<?php

namespace App\Repositories;

use App\Interfaces\VeiculoQuilometragemRepositoryInterface;
use App\Models\VeiculoQuilometragem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VeiculoQuilometragemRepository implements VeiculoQuilometragemRepositoryInterface
{
    public function index()
    {
        return VeiculoQuilometragem::all();
    }

    public function getAll()
    {
        return VeiculoQuilometragem::all();
    }

    public function getById($id)
    {
        return VeiculoQuilometragem::findOrFail($id);
    }

    public function create(array $data)
    {
        // Verifique se já existe uma quilometragem com o veiculo_id = 68
        $existeQuilometragem = VeiculoQuilometragem::where('veiculo_id', $data['id'])->exists();

        // Crie a nova quilometragem
        $quilometragem = new VeiculoQuilometragem;

        if ($existeQuilometragem) {

            $quilometragem->veiculo_id = $data['id'];
            $quilometragem->id_funcionario = $data['id_funcionario'];
            $quilometragem->id_obra = $data['obra_id'];
            $quilometragem->user_create = Auth::user()->email;
            $quilometragem->data_quilometragem = now(); // Use a data atual
            $quilometragem->quilometragem_atual = $data['quilometragem_atual'];
            $quilometragem->quilometragem_nova = $data['quilometragem_nova'];
            

            $quilometragem->save();

            return true;

        } else {

            $quilometragem->veiculo_id = $data['id'];
            $quilometragem->id_obra = $data['obra_id'];
            $quilometragem->user_create = Auth::user()->email;
            $quilometragem->data_quilometragem = now(); // Use a data atual
            $quilometragem->quilometragem_atual = $data['quilometragem_inicial'];
            $quilometragem->quilometragem_nova = $data['quilometragem_inicial'];
            
            $quilometragem->save();

            return true;
        }
    }


    public function update($id, array $data)
    {
        $quilometragem = VeiculoQuilometragem::findOrFail($id);

        $quilometragem->veiculo_id = $data['id'];
            $quilometragem->id_funcionario = $data['id_funcionario'];
            $quilometragem->id_obra = $data['obra_id'];
            $quilometragem->user_edit = Auth::user()->email;
            $quilometragem->data_quilometragem = now(); // Use a data atual
            $quilometragem->quilometragem_atual = $data['quilometragem_atual'];
            $quilometragem->quilometragem_nova = $data['quilometragem_nova'];
        
            $quilometragem->save();
        
        return true;
    }

    public function delete($id)
    {
        $quilometragem = VeiculoQuilometragem::findOrFail($id);
        $quilometragem->delete();
        Log::info('Quilometragem deletada', ['quilometragem' => $quilometragem]);
        return $quilometragem;
    }

    public function search($keyword)
    {
        return VeiculoQuilometragem::where('quilometragem_atual', 'like', "%$keyword%")
            ->orWhere('quilometragem_nova', 'like', "%$keyword%")
            ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoQuilometragem::paginate($perPage);
    }
}
