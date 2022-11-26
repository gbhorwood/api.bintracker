<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Local_CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(
            [
                ['id' => 1, 'user_id' => 1, 'name' => 'Beans', 'description' => ''],
                ['id' => 2, 'user_id' => 1, 'name' => 'Baking', 'description' => ''],
                ['id' => 3, 'user_id' => 1, 'name' => 'Fats and Oils', 'description' => ''],

                ['id' => 4, 'user_id' => 2, 'name' => 'Office supplies', 'description' => ''],
                ['id' => 5, 'user_id' => 2, 'name' => 'Computer stuff', 'description' => ''],
            ]
        );

        \Schema::enableForeignKeyConstraints();
    }
}
