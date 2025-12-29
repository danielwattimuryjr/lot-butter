<?php

namespace App\Exports;

use App\Models\Logistic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogisticsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Logistic::with('component')->get()->map(function ($logistic) {
            return [
                'code' => $logistic->code,
                'component' => $logistic->component->name,
                'type' => $logistic->transaction_type,
                'date' => $logistic->date,
                'quantity' => $logistic->quantity,
                'total' => $logistic->stock_total
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Logistic Code',
            'Component',
            'Type',
            'Date',
            'Quantity',
            'Stock Total'
        ];
    }
}
