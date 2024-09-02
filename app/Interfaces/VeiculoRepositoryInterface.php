<?php
namespace App\Interfaces;

interface VeiculoRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data, $imagem);
    public function update($id, array $data);
    public function delete($id);
    public function search($perPage, $keyword);
    public function paginate($perPage, $keyword);
    public function storeImage($id, $image);
    public function updateImage($id, $image, $veiculo_id);
    public function deleteImage($id);
    public function download($id);
  
}