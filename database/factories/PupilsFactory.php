<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Pupil::class, function (Faker $faker) {
    return [
		'school_id' => 1,
		'prof_pic' => $faker->imageUrl,
		'first_name' => $faker->word,
		'last_name' => $faker->word,
		'middle_name' => $faker->word,
		'birth_date' => $faker->date,
    ];
});
