<?php

use Faker\Generator as Faker;

$factory->define(\DarkBlog\Models\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        }
    ];
});
