<?php

use Illuminate\Database\Seeder;
use App\Models\ChecklistCategory;

class ChecklistCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=0; $i<10; $i++) {
            ChecklistCategory::create([
                'name' => $faker->word($nb=3)
            ]);
        }
    }
}
