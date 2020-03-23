<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Test::class, function (Faker $faker) {
    return [
        'teacher_id' => rand(1, 2), 
        'questions_id' => null, 
        'title' => $faker->word($nb=3), 
        'description' => $faker->sentence
    ];
});
