<?php

namespace DarkBlog\Database\Factories;

use App\Models\User;
use DarkBlog\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'title' => $title,
            'slug' => \DarkBlog\Models\Slug::generate($title),
            'body' => $this->faker->paragraph,
            'user_id' => User::factory()
        ];
    }
}
