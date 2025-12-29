<?php

namespace App\Exports;

use App\Models\Income;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Income::with('product')->get()->map(function ($income) {
            return [
                'code' => $income->code,
                'product' => $income->product->name,
                'description' => $income->description,
                'quantity' => $income->quantity,
                'unit_price' => $income->product->price,
                'total_amount' => $income->amount,
                'date_received' => $income->date_received,
                'week' => $income->week
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code',
            'Product',
            'Description',
            'Quantity',
            'Unit Price',
            'Total Amount',
            'Date Received',
            'Week'
        ];
    }
}
