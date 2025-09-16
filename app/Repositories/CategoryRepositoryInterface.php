<?php

namespace App\Repositories;

interface CategoryRepositoryInterface
{
    // Define your interface methods here
    public function getAll(array $field);
    public function getById($id, array $field);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}