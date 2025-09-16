<?php

namespace App\Repositories;


use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{

    // Implement the interface methods here
    public function getAll(array $field)
    {
        return Category::select($field)->latest()->paginate(10);
    }
    public function getById($id, array $field)
    {
        return Category::select($field)->find($id);
    }
    public function create(array $data){
        return Category::create($data);
    }
    public function update($id, array $data){
        $category = Category::findOrFail($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }
    public function delete($id)    {
        $category = Category::findOrFail($id);
        if ($category) {
            $category->delete();
            return true;
        }
        return false;
    }
}