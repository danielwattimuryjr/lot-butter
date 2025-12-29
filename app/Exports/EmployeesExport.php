<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Employee::with('team')->get()->map(function ($employee) {
            return [
                'name' => $employee->name,
                'phone_number' => $employee->phone_number,
                'nip' => $employee->nip,
                'team' => $employee->team ? $employee->team->name : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone Number',
            'NIP',
            'Team'
        ];
    }
}
