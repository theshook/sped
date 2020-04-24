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
    $count = 0;

    // Multiple choices
    for ($i = 0; $i < 5; $i++) {
      Question::create([
        'teacher_id' => 1,
        'checklist_id' => rand(1, 3),
        'question_type' => 1,
        // 'question' => $faker->sentence,
        'question' => 'Identification #' . $count++,
        'choice1' => $faker->sentence,
        'choice2' => $faker->sentence,
        'choice3' => $faker->sentence,
        'choice4' => $faker->sentence,
        'answer' => $faker->word,
        'explanation' => $faker->word($nb = 10)
      ]);
    }


    // Enumeration
    for ($i = 0; $i < 5; $i++) {
      Question::create([
        'teacher_id' => 1,
        'checklist_id' => rand(1, 3),
        'question_type' => 2,
        // 'question' => $faker->sentence,
        'question' => 'Enumeration #' . $count++,
        'choice1' => null,
        'choice2' => null,
        'choice3' => null,
        'choice4' => null,
        'answer' => $faker->word,
        'explanation' => $faker->word($nb = 10)
      ]);
    }

    // Identification
    for ($i = 0; $i < 5; $i++) {
      Question::create([
        'teacher_id' => 1,
        'checklist_id' => rand(1, 3),
        'question_type' => 3,
        'question' => 'Enumeration #' . $count++,
        'choice1' => null,
        'choice2' => null,
        'choice3' => null,
        'choice4' => null,
        'answer' => $faker->word,
        'explanation' => $faker->word($nb = 10)
      ]);
    }
  }
}
