<?php

namespace Database\Factories\Products;

use App\Models\Products\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{

    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition():array
    {
        return [
            'title' => $this->faker->sentence(2),
        ];
    }
}
