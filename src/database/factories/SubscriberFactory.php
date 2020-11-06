<?php

use Faker\Generator as Faker;

$factory->define(\DarkBlog\Models\Subscriber::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'name' => $faker->firstName . ' ' .$faker->lastName
    ];
});
