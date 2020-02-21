<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Pupil::class, function (Faker $faker) {
    return [
		'school_id' => 12,
		'prof_pic' => $faker->imageUrl,
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'middle_name' => $faker->lastName,
		'birth_date' => $faker->date,
    ];
});
