<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    // Implement the service methods here;
    public function create(array $data)
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        return $this->categoryRepository->create($data);
    }
    public function update(int $id, array $data){
        $category = $this->categoryRepository->getById($id, ['id', 'photo']);

        if (!$category) {
            return null; // Or throw an exception
        }

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            // Delete old photo if exists
            if ($category->photo) {
                Storage::delete(str_replace(Storage::url(''), '', $category->photo));
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->categoryRepository->update($id, $data);
    }
    public function uploadPhoto(UploadedFile $photo){
        return $photo->store('categories', 'public');
    }
}