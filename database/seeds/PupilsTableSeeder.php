<?php

use Illuminate\Database\Seeder;

class PupilsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Pupil::class, 10)->create();
    }
}
