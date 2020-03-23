<?php

use Illuminate\Database\Seeder;
use App\Models\Assessment;

class AssessmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = \Faker\Factory::create();
        // Assessment::create([
        //     'teacher_id' => rand(0, 10),
        //     'pupil_id' => rand(0, 10),
        //     'test_id' => rand(0, 10),
        //     'assessment_no' => rand(0, 120),
        //     'assessment_date' => $faker->date,
        // ]);
        factory(App\Models\Assessment::class, 10)->create();
    }
}
