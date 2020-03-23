<?php

use Illuminate\Database\Seeder;
use App\Models\AssessmentPupil;

class AssessmentPupilsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $assessment_id = 0;
        $pupil_id = 0;
        for($i=0; $i<10; $i++) {
            AssessmentPupil::create([
                'assessment_id' => rand(1, 5),
                'pupil_id' => rand(1, 10),
                'is_locked' => 'true',
                'total_score' => rand(5, 50),
                'status' => 'true',
                'recommendation' => $faker->sentence,
                'validated_by' => $faker->name,
                'validated_date' => $faker->date,
                'closed_by' => $faker->name,
                'closed_date' => $faker->date,
            ]);
        }
        // factory(App\Models\AssessmentPupil::class, 10)->create();
    }
}
