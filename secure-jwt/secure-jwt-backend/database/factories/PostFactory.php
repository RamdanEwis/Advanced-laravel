<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition():array
    {
        return [
            'title' => $this->faker->sentence, // 📜 Random title
            'content' => $this->faker->paragraph, // 📝 Random content
            'user_id' => null, // 🧑‍💻 This will be set in the seeder
        ];
    }
}
