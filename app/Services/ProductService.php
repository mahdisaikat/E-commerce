<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function store(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'details' => $data['details'] ?? null,
            ]);

            $this->syncProductCategories($product, $data);

            return $product;
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'details' => $data['details'] ?? null,
            ]);

            $this->syncProductCategories($product, $data);

            return $product->fresh();
        });
    }

    /**
     * Sync product category (many-to-many)
     */
    protected function syncProductCategories(Product $product, array $data): void
    {
        $categoryId = $data['product_category'];
        $isPrimary = $data['is_primary'] ?? false;

        if ($isPrimary) {
            // Set all others to non-primary for this product
            $product->categories()->updateExistingPivot($product->categories->pluck('id')->toArray(), ['is_primary' => false]);
        }

        $product->categories()->syncWithPivotValues(
            [$categoryId],               // Category IDs
            ['is_primary' => $isPrimary],// Pivot data
            false                        // Don't detach others
        );
    }
}
