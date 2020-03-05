<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(AssessmentQuestionsTableSeeder::class);
    // $this->call(ProvincesTableSeeder::class);
    // $this->call(TeachersTableSeeder::class);
    // $this->call(PupilsTableSeeder::class);
  }
}
