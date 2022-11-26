<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Production_RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // clear table data to prevent errors (incase seed was previously run)
      DB::table('roles')->delete();

      // create roles
      DB::table('roles')->insert([

        [
          'id' => 1,
          'name' => 'Admin',
          'description' => 'admin user'
        ],

        [
          'id' => 2,
          'name' => 'Normal User',
          'description' => 'normal user'
        ],

      ]);
    }
}
