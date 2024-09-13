<?php


namespace App\Interfaces;

interface TiposVeiculosRepositoryInterface
{
    public function index();
    public function getAll();
    public function create($data);
    public function store($data);
    public function edit(int $id);
    public function update(int $id, $data);
    public function show(int $id);
    public function delete(int $id);
    public function search(string $query);
    public function paginate(int $perPage);
}
