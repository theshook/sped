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
    $this->call(ProvincesTableSeeder::class);
    $this->call(SchoolsTableSeeder::class);
    $this->call(TeachersTableSeeder::class);
    $this->call(ParentsTableSeeder::class);
    $this->call(PupilsTableSeeder::class);
    $this->call(RolesTableSeeder::class);
  }
}
