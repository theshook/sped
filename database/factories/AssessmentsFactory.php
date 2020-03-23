<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Assessment::class, function (Faker $faker) {
    return [
        'teacher_id' => rand(1, 10),
        'pupil_id' => rand(1, 10),
        'test_id' => rand(1, 10),
        'assessment_no' => rand(0, 120),
        'assessment_date' => $faker->date,
    ];
});
