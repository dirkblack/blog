<?php

use Faker\Generator as Faker;

$factory->define(\DarkBlog\Models\Post::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'title' => $title,
        'slug' => \DarkBlog\Models\Slug::generate($title),
        'body' => $faker->paragraph,
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        }
    ];
});
