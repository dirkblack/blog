<?php

namespace DarkBlog\Database\Factories;

use DarkBlog\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'name'  => $this->faker->firstName . ' ' . $this->faker->lastName
        ];
    }
}
