<?php
namespace App\Interfaces;

interface MaquinaRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search($keyword);
    public function paginate($perPage);
    public function storeImage($id, $image);
    public function updateImage($id, $image);
    public function deleteImage($id);
    public function download($id);
}