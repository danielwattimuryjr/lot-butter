<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProcurementsExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Purchase::with('component')->get()->map(function ($purchase) {
            return [
                'code' => $purchase->code,
                'component' => $purchase->component->name,
                'description' => $purchase->description,
                'quantity' => $purchase->quantity,
                'unit_price' => $purchase->unit_price,
                'total_amount' => $purchase->total_amount,
                'date' => $purchase->date,
                'week' => $purchase->week,
                'supplier' => $purchase->supplier,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code',
            'Component',
            'Description',
            'Quantity',
            'Unit Price',
            'Total Amount',
            'Date',
            'Week',
            'Supplier',
        ];
    }
}
