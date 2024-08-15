<?php

namespace App\Repositories\Interfaces;

use App\Models\Brand;

interface BrandRepositoryInterface
{
    public function all();
    public function paginate($perPage = 10, $search = null);
    public function find($id);
    public function create(array $data);
    public function update(Brand $category, array $data);
    public function delete(Brand $category);
}