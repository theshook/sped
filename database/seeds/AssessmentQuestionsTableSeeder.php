<?php

use Illuminate\Database\Seeder;

class AssessmentQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Question::class, 20)->create();
    }
}
