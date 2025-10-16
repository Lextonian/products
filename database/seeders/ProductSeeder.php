<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // создаём 80 продуктов и рандомно привязываем к категориям
        $allCategoryIds = ProductCategory::pluck('id')->all();

        if (empty($allCategoryIds)) {
            return;
        }

        Product::factory()
            ->count(80)
            ->make()
            ->each(function ($product) use ($allCategoryIds) {
                $product->category_id = $this->fakerCategory($allCategoryIds);
                $product->save();
            });
    }

    protected function fakerCategory(array $ids)
    {
        if (empty($ids)) return null;
        return $ids[array_rand($ids)];
    }
}
