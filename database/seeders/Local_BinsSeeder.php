<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Local_BinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        \DB::table('bins')->delete();
        
        \DB::table('bins')->insert(
            [
                ['id' => 1, 'user_id' => 1, 'name' => 'Big red bin'],
                ['id' => 2, 'user_id' => 1, 'name' => 'Smaller red bin'],
                ['id' => 3, 'user_id' => 2, 'name' => 'Blue bin'],
                ['id' => 4, 'user_id' => 2, 'name' => 'Empty grey bin'],
            ]
        );

        \Schema::enableForeignKeyConstraints();
    }
}
