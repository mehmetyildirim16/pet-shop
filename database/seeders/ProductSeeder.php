<?php

namespace Database\Seeders;

use App\Models\Products\Brand;
use App\Models\Products\Category;
use Database\Factories\Products\CategoryFactory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(10)->create();
        Brand::factory(10)->create();
    }
}
