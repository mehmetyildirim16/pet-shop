<?php

namespace Database\Factories\Blogs;

use App\Models\Blogs\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class PostFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'title' => $this->faker->word,
            'content' => $this->faker->paragraph(3, true),
            'metadata' => [
                'author' => $user->full_name,
            ],
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            $image = UploadedFile::fake()->image('photo1.jpg');
            $post->addFile($image);
        });
    }
}
