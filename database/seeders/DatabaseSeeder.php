<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            RankSeeder::class,
        ]);
    }
}

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'doctor'],
            ['name' => 'customer'],
            ['name'=> 'pharmacist'],
        ]);
    }
}

class RankSeeder extends Seeder
{
    public function run()
    {
        DB::table('ranks')->insert([
            ['name' => 'Bronze', 'min_points' => 500000],
            ['name' => 'Silver', 'min_points' => 1500000],
            ['name' => 'Gold', 'min_points' => 3000000],
            ['name' => 'Member', 'min_points' => 0],
        ]);
    }
}

