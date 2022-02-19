<?php

namespace Database\Factories\Products;

use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{

    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition():array
    {
        $category = Category::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();
        return [
            'title' => $this->faker->sentence(2),
            'category_uuid' => $category->uuid,
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'metadata' => [
                'brand_uuid' => $brand->uuid,
            ],
        ];
    }
}
