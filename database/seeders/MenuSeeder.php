<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Master Data',
                    'slug' => 'master-data',
                    'type' => 'parent',
                    'id_parent' => null,
                    'order' => 1,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
                [
                    'id' => 2,
                    'name' => 'User Level',
                    'slug' => 'user-level',
                    'type' => 'child',
                    'id_parent' => 1,
                    'order' => 1,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
                [
                    'id' => 3,
                    'name' => 'Menu',
                    'slug' => 'menu',
                    'type' => 'child',
                    'id_parent' => 1,
                    'order' => 2,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ],
            ]
        );
    }
}
