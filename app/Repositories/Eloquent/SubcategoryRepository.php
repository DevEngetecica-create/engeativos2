<?php

namespace App\Repositories\Eloquent;

use App\Models\Subcategory;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;

class SubcategoryRepository implements SubcategoryRepositoryInterface
{
    
    public function all()
    {
        return Subcategory::all();
    }

    public function find($id)
    {
        return Subcategory::find($id);
    }

    public function create(array $data)
    {
        return Subcategory::create($data);
    }

    public function update(Subcategory $subcategory, array $data)
    {
        return $subcategory->update($data);
    }

    public function delete(Subcategory $subcategory)
    {
        return $subcategory->delete();
    }

    public function paginate($perPage = 10, $search = null)
    {
        return Subcategory::where('name', 'like', "%{$search}%")->orderBy('id', 'desc')->paginate($perPage);
    }
}
