<?php

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = \Faker\Factory::create();
    $teacher_id = 0;
    $checklist_id = 0;
    for ($i = 0; $i < 10; $i++) {
      Question::create([
        'teacher_id' => rand(1, 10),
        'checklist_category_id' => rand(1, 10),
        'question_type' => rand(1, 3),
        'question' => $faker->sentence,
        'choice1' => $faker->sentence,
        'choice2' => $faker->sentence,
        'choice3' => $faker->sentence,
        'choice4' => $faker->sentence,
        'answer' => $faker->word,
        'explanation' => $faker->word($nb = 10)
      ]);
    }
  }
}
