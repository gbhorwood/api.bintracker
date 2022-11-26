<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Local_BinItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        \DB::table('bin_item')->delete();
        
        \DB::table('bin_item')->insert(
            [
                ['id' => 1, 'user_id' => 1, 'bin_id' => 1, 'item_id' => 4, 'created_at' => '2021-01-03 12:00:00'],
                ['id' => 2, 'user_id' => 1, 'bin_id' => 1, 'item_id' => 4, 'created_at' => '2021-01-04 12:00:00'],
                ['id' => 3, 'user_id' => 1, 'bin_id' => 1, 'item_id' => 4, 'created_at' => '2021-03-04 12:00:00'],
                ['id' => 4, 'user_id' => 1, 'bin_id' => 2, 'item_id' => 1, 'created_at' => '2021-03-04 12:00:00'],
                ['id' => 5, 'user_id' => 1, 'bin_id' => 2, 'item_id' => 2, 'created_at' => '2021-03-04 12:00:00'],

                ['id' => 6, 'user_id' => 2, 'bin_id' => 3, 'item_id' => 5, 'created_at' => '2021-07-01 12:00:00'],
                ['id' => 7, 'user_id' => 2, 'bin_id' => 3, 'item_id' => 5, 'created_at' => '2021-08-01 12:00:00'],
            ]
        );

        \Schema::enableForeignKeyConstraints();
    }
}
