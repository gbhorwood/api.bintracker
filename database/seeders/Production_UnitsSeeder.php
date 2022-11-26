<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Production_UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        \DB::table('units')->delete();
        
        \DB::table('units')->insert(
            [
                ['id' => 1, 'name' => 'count'],
                ['id' => 2, 'name' => 'g'],
                ['id' => 3, 'name' => 'kg'],
                ['id' => 4, 'name' => 'ml'],
                ['id' => 5, 'name' => 'l'],
            ]
        );

        \Schema::enableForeignKeyConstraints();
    }
}
