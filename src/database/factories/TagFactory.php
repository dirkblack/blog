<?php

use Faker\Generator as Faker;

$factory->define(\DarkBlog\Models\Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
