<?php

namespace Database\Factories\Products;

use App\Models\Products\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{

    protected $model = Brand::class;

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
