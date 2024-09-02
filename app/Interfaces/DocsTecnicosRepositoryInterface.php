<?php


namespace App\Interfaces;

interface DocsTecnicosRepositoryInterface
{
    
    public function index();
    public function getByTipoVeiculo(int $tipo_veiculo_id);
    public function create(array $data);
    public function store(array $data);
    public function edit(int $id);
    public function update(int $id, array $data);
    public function show(int $id);
    public function delete(int $id);
    public function search(string $query);
    public function paginate(int $perPage);

}
