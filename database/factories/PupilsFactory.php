<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Pupil::class, function (Faker $faker) {
    return [
        'school_id' => rand(1, 10),
        'parent_id' => rand(1, 10),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_name' => $faker->lastName,
        'birth_date' => $faker->date,
        'prof_pic' => $faker->imageUrl($width = 400, $height = 400)
    ];
});
