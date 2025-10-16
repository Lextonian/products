<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // создаём дерево категорий: 5 корней, у каждого 0-3 детей
        $roots = ProductCategory::factory()->count(5)->create();

        foreach ($roots as $root) {
            $children = ProductCategory::factory()->count(rand(0,3))->create([
                'parent_id' => $root->id
            ]);

            foreach ($children as $child) {
                ProductCategory::factory()->count(rand(0,2))->create(['parent_id' => $child->id]);
            }
        }
    }
}
