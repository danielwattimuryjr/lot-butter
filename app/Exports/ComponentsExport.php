<?php

namespace App\Exports;

use App\Models\Component;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComponentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Component::get([
            'code',
            'safety_stock',
            'name',
            'weight',
            'unit',
            'category'
        ]);
    }

    public function headings(): array
    {
        return [
            'Code',
            'Safety Stock',
            'Name',
            'Weight',
            'Unit',
            'Category'
        ];
    }
}
