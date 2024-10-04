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
<<<<<<< HEAD
    public function upload(int $id);
=======
    public function upload(int $id, array $data, $arquivos);
>>>>>>> 9f303e6dee0ad8ab3bba4885151daaa61259c12d
    public function download(int $id);
}
