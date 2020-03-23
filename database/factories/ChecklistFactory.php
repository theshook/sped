<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Models\Checklist::class, function (Faker $faker) {
    return [
        'content' => $faker->word($nb=2), 
        'checklist_categories_id' => rand(1, 5)
    ];
});
