<?php

namespace Database\Seeders;

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
        switch(\App::Environment()) {

            /**
             * Local/testing seeds
             */
            case 'local':
            case 'testing':
                $seeds = [
                    Production_RoleSeeder::class,
                    Production_UnitsSeeder::class,
                    Local_ItemsSeeder::class,
                    Local_CategoriesSeeder::class,
                    Local_CategoryItemSeeder::class,
                    Local_UsersTableSeeder::class,
                    Local_BinsSeeder::class,
                    Local_BinItemSeeder::class,
                ];
            break;

            /**
             * Staging seeds
             */
            case 'staging':
                $seeds = [
                    Production_RoleSeeder::class,
                    Production_UnitsSeeder::class,
                ];
            break;

            /**
             * Production seeds
             */
            case 'production':
                $seeds = [
                    Production_RoleSeeder::class,
                    Production_UnitsSeeder::class,
                ];
            break;
        }


        /**
         * Run seeders
         */
        array_map(fn($s) => $this->call($s), $seeds);

    } // run
}
