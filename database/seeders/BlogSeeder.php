<?php

namespace Database\Seeders;

use App\Models\Blogs\Post;
use App\Models\Blogs\Promotion;
use Database\Factories\Orders\PostFactory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(10)->create();
        Promotion::factory(10)->create();
    }
}
