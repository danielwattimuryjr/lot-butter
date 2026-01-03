<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding employees and user accounts...');

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

        foreach ($employees as $employeeData) {
            // Create the employee
            $employee = Employee::create($employeeData);

            // Get the team name for the username
            $teamName = Team::find($employee->team_id)->name;

            // Create user account for the employee
            User::create([
                'employee_id' => $employee->id,
                'username' => 'user.'.$teamName,
                'password' => Hash::make('password'),
            ])->addRole('employee');
        }

        $this->command->info('Created '.count($employees).' employees with user accounts');
    }
}
