<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Rank;

class RolesAndRanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            ['name' => 'admin'],
            ['name' => 'doctor'],
            ['name' => 'pharmacist'],
            ['name' => 'customer'],
        ];
        
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
        
        // Create ranks
        $ranks = [
            ['name' => 'bronze', 'min_points' => 0],
            ['name' => 'silver', 'min_points' => 100],
            ['name' => 'gold', 'min_points' => 500],
            ['name' => 'platinum', 'min_points' => 1000],
        ];
        
        foreach ($ranks as $rank) {
            Rank::updateOrCreate(
                ['name' => $rank['name']],
                $rank
            );
        }
    }
} 