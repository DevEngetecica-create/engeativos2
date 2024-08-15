<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;

class BrandRepository implements BrandRepositoryInterface
{
    public function all()
    {
        return Brand::all();
    }

    public function paginate($perPage = 10, $search = null)
    {
        return Brand::where('name', 'like', "%{$search}%")->paginate($perPage);
    }

    public function find($id)
    {
        return Brand::find($id);
    }

    public function create(array $data)
    {
        return Brand::create($data);
    }

    public function update(Brand $brand, array $data)
    {
        return $brand->update($data);
    }

    public function delete(Brand $brand)
    {
        return $brand->delete();
    }

    
}