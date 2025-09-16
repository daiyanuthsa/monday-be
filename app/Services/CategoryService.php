<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
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
    public function update(int $id, array $data)
    {
        // Find the category first. findOrFail will throw an exception if not found.
        $category = $this->categoryRepository->getById($id, ['id', 'photo']);

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            // Get the raw path of the old photo before the model is updated
            $oldPhotoPath = $category->getRawOriginal('photo');

            // Delete old photo if exists
            if ($oldPhotoPath) {
                Storage::disk('public')->delete($oldPhotoPath);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->categoryRepository->update($id, $data);
    }
    public function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('categories', 'public');
    }
}