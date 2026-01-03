<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding teams...');

        $teams = [
            [
                'name' => 'Finance',
                'description' => 'Manages financial transactions',
            ],
            [
                'name' => 'Procurement',
                'description' => 'Manages purchasing activities',
            ],
            [
                'name' => 'Production',
                'description' => 'Executes manufacturing processes',
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }

        $this->command->info('Created '.count($teams).' teams successfully');
    }
}
