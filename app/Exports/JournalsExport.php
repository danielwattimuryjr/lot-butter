<?php

namespace App\Exports;

use App\Models\Journal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JournalsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Journal::get([
            'code',
            'date',
            'description',
            'debit',
            'credit',
            'balance'
        ]);
    }

    public function headings(): array
    {
        return [
            'Code',
            'Date',
            'Description',
            'Debit',
            'Credit',
            'Balance'
        ];
    }
}
