<?php

namespace App\Interfaces;

interface VeiculoAcessoriosRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $datata);
    public function delete($id);
    public function search($keyword);
    public function paginate($perpage);
    
}