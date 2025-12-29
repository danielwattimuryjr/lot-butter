<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with(['employee', 'team'])->whereHasRole('employee')->get()->map(function ($user) {
            return [
                'employee_name' => $user->employee ? $user->employee->name : '',
                'team' => $user->team ? $user->team->name : '',
                'username' => $user->username,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Team',
            'Username',
        ];
    }
}
