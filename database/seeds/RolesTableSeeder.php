<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('name' => 'admin'),
            array('name' => 'teachers'),
            array('name' => 'pediatrics'),
            array('name' => 'deped'),
            array('name' => 'parents')
        );

        DB::table('roles')->insert($data);
    }
}
