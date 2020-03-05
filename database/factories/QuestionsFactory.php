<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Question::class, function (Faker $faker) {
    return [
        'teacher_id' => 1,
        'question' => $faker->text,
        'choice1' => $faker->text,
        'choice2' => $faker->text,
        'choice3' => $faker->text,
        'choice4' => $faker->text,
        'answer' => 'correct answer'
    ];
});
