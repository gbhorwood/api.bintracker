<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Local_ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        \DB::table('items')->delete();
        
        \DB::table('items')->insert(
            [
                ['id' => 1, 'user_id' => 1, 'name' => 'Beans, garbanzo', 'amount' => 300, 'unit_id' => 2, 'image' => 'http://example.ca/garbanzo.jpg'],
                ['id' => 2, 'user_id' => 1, 'name' => 'Beans, fava', 'amount' => 450, 'unit_id' => 2, 'image' => 'http://example.ca/fava.jpg'],
                ['id' => 3, 'user_id' => 1, 'name' => 'Flour, all-purpose', 'amount' => 20, 'unit_id' => 3, 'image' => 'http://example.ca/flour.jpg'],
                ['id' => 4, 'user_id' => 1, 'name' => 'Oil, canola', 'amount' => 1, 'unit_id' => 5, 'image' => 'http://example.ca/oil.jpg'],

                ['id' => 5, 'user_id' => 2, 'name' => 'Diskettes, floppy 3.5in', 'amount' => 100, 'unit_id' => 1, 'image' => 'http://example.ca/disk.jpg'],
                ['id' => 6, 'user_id' => 2, 'name' => 'Paper, ream', 'amount' => 5, 'unit_id' => 1, 'image' => 'http://example.ca/paper.jpg'],
            ]
        );

        \Schema::enableForeignKeyConstraints();
    }
}
