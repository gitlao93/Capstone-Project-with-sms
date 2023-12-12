<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('class_types')->delete();

        $data = [
            ['name' => 'General Academic Strand', 'code' => 'G'],
            ['name' => 'Electrical Installation Management', 'code' => 'E'],
            ['name' => 'Home Economics', 'code' => 'H'],
            ['name' => 'Accountancy Business Maintenance', 'code' => 'A'],
            ['name' => 'Information Commucation Technology', 'code' => 'I'],
            ['name' => 'Humanities and Social Sciences', 'code' => 'HU'],
            ['name' => 'Science Technology Engineering Mathematics', 'code' => 'S'],
   
        ];

        DB::table('class_types')->insert($data);

    }
}
