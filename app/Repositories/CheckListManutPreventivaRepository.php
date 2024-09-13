<?php

namespace App\Repositories;

use App\Models\CheckListManutPreventiva;
use App\Interfaces\CheckListManutPreventivaRepositoryInterface;

class CheckListManutPreventivaRepository implements CheckListManutPreventivaRepositoryInterface
{
    public function all()
    {
        return CheckListManutPreventiva::all();
    }

    public function find($id)
    {
        return CheckListManutPreventiva::find($id);
    }

    public function create(array $data)
    {
        //dd($data);
        return CheckListManutPreventiva::create($data);
    }

    public function update($id, array $data)
    {
        $checkList = CheckListManutPreventiva::find($id);
        $checkList->update($data);
        return $checkList;
    }

    public function delete($id)
    {
        return CheckListManutPreventiva::destroy($id);
    }

    public function search($query)
    {
        // Implemente a lógica de pesquisa se necessário
    }

    public function paginate($perPage = 10)
    {
        return CheckListManutPreventiva::paginate($perPage);
    }

    public function getByPreventivaId($id)
    {
        return CheckListManutPreventiva::where('id_manut_preventiva', $id)->paginate();
    }

    public function getByIdVeiculo($id)
    {
        return CheckListManutPreventiva::where('id_veiculo', $id)->paginate();
    }
}
