<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $title = $this->faker->unique()->sentence(3);
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->lexify('????'),
            'description' => $this->faker->paragraph(),
            'category_id' => null,
        ];
    }
}
