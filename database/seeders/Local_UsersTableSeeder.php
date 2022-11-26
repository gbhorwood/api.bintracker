<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Local_UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'one',
                    'email' => 'one@example.ca',
                    'email_verified_at' => NULL,
                    'password' => '$2y$10$3YyKbL6D1eS25NhLwlwdZedeCf7lsPLy6YlqEu5z5VLvei19CA5t.',
                    'remember_token' => NULL,
                    'role_id' => 2,
                    'created_at' => '2021-08-26 20:19:37',
                    'updated_at' => '2021-08-26 20:19:37',
                ],
                [
                    'id' => 2,
                    'name' => 'two',
                    'email' => 'two@example.ca',
                    'email_verified_at' => NULL,
                    'password' => '$2y$10$zc.GJrQeoHXw6RxNINiSw.d5wITSHsbfCrACPrCyZVxIp.A8MaTJS',
                    'remember_token' => NULL,
                    'role_id' => 2,
                    'created_at' => '2021-08-26 20:20:20',
                    'updated_at' => '2021-08-26 20:20:20',
                ],
                [
                    'id' => 3,
                    'name' => 'admin',
                    'email' => 'admin@example.ca',
                    'email_verified_at' => NULL,
                    'password' => '$2y$10$vvvS5LyxF3hB1XlngTCDIeduKsDpbUBBk8PcobUWOJgMBhtX9a7aC',
                    'remember_token' => NULL,
                    'role_id' => 1,
                    'created_at' => '2021-08-26 20:20:20',
                    'updated_at' => '2021-08-26 20:20:20',
                ],
            ]
        );
        
        
        \Schema::enableForeignKeyConstraints();
    }
}