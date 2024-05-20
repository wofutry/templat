<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_levels')->insert(
            [
                [
                    'name' => 'admin',
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
                [
                    'name' => 'user',
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
            ]
        );
    }
}
