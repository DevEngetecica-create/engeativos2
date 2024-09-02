<?php

namespace App\Interfaces;

interface VeiculoManutencaoRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data, $arquivos);
    public function update($id, array $data, $arquivos);
    public function delete($id);
    public function search($keyword);
    public function paginate($perPage);
    public function storeImage($id, $image);
    public function updateImage($id, $image, $veiculo_id);
    public function deleteImage($id);
    public function download($id);
    public function upload($id, array $data, $arquivos);
}

