<?php

namespace Database\Factories\Blogs;

use App\Models\Blogs\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class PromotionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Promotion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'content' => $this->faker->paragraphs(3, true),
            'metadata' => [
                'valid_from' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
                'valid_to' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            ]
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Promotion $promotion) {
            $image = UploadedFile::fake()->image('photo1.jpg');
            $promotion->addFile($image);
        });
    }
}
