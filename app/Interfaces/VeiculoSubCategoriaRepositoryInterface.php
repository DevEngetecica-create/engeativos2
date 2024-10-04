<?php

namespace App\Interfaces;

interface VeiculoSubCategoriaRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search($keyword);    
    public function paginate($perPage);
}