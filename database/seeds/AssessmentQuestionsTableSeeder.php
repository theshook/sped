<?php

use Illuminate\Database\Seeder;
use App\Models\AssessmentQuestion;

class AssessmentQuestionsTableSeeder extends Seeder
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
        $question_id = 0;
        for($i=0; $i<10; $i++) {
            AssessmentQuestion::create([
                'assessment_pupil_id' => rand(3,10),
                'question_id' => rand(1, 10),
                'question_type' => rand(1,3),
                'answer' => $faker->word,
                'correct' => $faker->word,
                'wrong' => $faker->word,
            ]);
        }
    }
}
