<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Finance',
                'description' => 'Manages financial transactions'
            ],
            [
                'name' => 'Procurement',
                'description' => 'Manages purchasing activities'
            ],
            [
                'name' => 'Production',
                'description' => 'Executes manufacturing processes'
            ]
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
