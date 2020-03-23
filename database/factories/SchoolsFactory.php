<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\School::class, function (Faker $faker) {
    return [
        'province_id' => rand(1, 10),
        'name' => $faker->text($maxNbChars = 32)
    ];
});
