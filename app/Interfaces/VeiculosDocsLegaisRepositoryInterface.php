<?php

namespace App\Interfaces;

interface VeiculosDocsLegaisRepositoryInterface
{
    public function index();
    public function create(array $data);
    public function store(array $data);
    public function edit(int $id);
    public function update(int $id, array $data, $arquivos);
    public function show(int $id);
    public function delete(int $id);
    public function search(string $query);
    public function paginate(int $perPage);
    public function anexo(int $id);
    public function download(int $id);
}
