<?php

namespace App\Interfaces;

interface CheckListManutPreventivaRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search($query);
    public function paginate($perPage = 10);
    public function getByPreventivaId($id);
    public function getByIdVeiculo($id);
}