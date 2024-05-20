<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'admin',
                    'email' => 'admin@gmail.com',
                    'email_verified_at' => Carbon::now('Asia/Jakarta'),
                    'password' => password_hash('123456789', PASSWORD_DEFAULT),
                    'id_user_level' => 1,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
                [
                    'name' => 'user',
                    'email' => 'user@gmail.com',
                    'email_verified_at' => null,
                    'password' => password_hash('123456789', PASSWORD_DEFAULT),
                    'id_user_level' => 2,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
            ]
        );
    }
}
