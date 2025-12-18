<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::pluck('id', 'name');

        $employees = [
            [
                'name' => 'Ayu Dewi Gita',
                'nip' => '12012030192',
                'phone_number' => '08887187232',
                'team_id' => $teams['Finance'],
            ],
            [
                'name' => 'Adinda Lailatul',
                'nip' => '12012030100',
                'phone_number' => '08887187212',
                'team_id' => $teams['Procurement'],
            ],
            [
                'name' => 'Andi Siti',
                'nip' => '12012030187',
                'phone_number' => '08887187265',
                'team_id' => $teams['Production'],
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
