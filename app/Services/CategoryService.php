<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function store(array $data, $type = null)
    {
        return Category::create([
            'name' => $data['name'],
            'parent_id' => $data['parent_category'],
            'description' => $data['description'],
            'type' => $type,
        ]);
    }

    public function update(Category $category, array $data)
    {
        return $category->update([
            'name' => $data['name'],
            'parent_id' => $data['parent_category'],
            'description' => $data['description'],
        ]);
    }

}
