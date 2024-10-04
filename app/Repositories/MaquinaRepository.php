<?php

namespace App\Repositories;

use App\Interfaces\MaquinaRepositoryInterface;
use App\Models\Maquinas;
use App\Models\MaquinasImagens;
use App\Model\Log;

class MaquinaRepository implements MaquinaRepositoryInterface
{
    public function getAll()
    {
        return Maquinas::all();
    }

    public function getById($id)
    {
        return Maquinas::findOrFail($id);
    }

    public function create(array $data)
    {
        return Maquinas::create($data);
    }

    public function update($id, array $data)
    {
        $maquina = Maquinas::finOrFail($id);
        $maquina->update($data);

        return $maquina;
    }

    public function delete($id)
    {
        return Maquinas::destroy($id);
    }

    public function search($keyword)
    {
        return Maquinas::where('modelo', 'like', "%$keyword%")
            ->orWhere('codigo_da_maquina', 'like', "%$keyword")
            ->get();
    }

    public function paginate($perPage)
    {
        return Maquinas::paginate($perPage);
    }

    public function storeImage($id, $image)
    {
        $image_name = $image->getClientOriginalName();
        $image->move(public_path("imagen/maquinas"), $image_name);

        $maquinaImagem = new Maquinas();
        $maquinaImagem->maquina_id = $id;
        $maquinaImagem->imagens = $image_name;
        $maquinaImagem->save();

        return $maquinaImagem;
    }

    public function updateImage($id, $image)
    {
        $maquinaImagem = Maquinas::findOrFail($id);
        $imageName = $image->getClientOriginalName();
        $image->move(public_path("/imagens/maquina"), $imageName);

        $maquinaImagem->imagens = $imageName;
        $maquinaImagem->save();

        return $maquinaImagem;
    }

    public function deleteImage($id)
    {
        $maquinaImagem = MaquinasImagens::findOrFail($id);
        if (file_exists(public_path("/imagens/maquina/") . $maquinaImagem->imagens)) {
            unlink(public_path("/imagens/maquina/") . $maquinaImagem->imagens);
        }
        $maquinaImagem->delete();
    }

    public function download($id)
    {
        // Implementar lógica para download de imagem
    }
}
