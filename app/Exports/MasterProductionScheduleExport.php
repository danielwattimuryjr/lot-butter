<?php

namespace App\Exports;

use App\Models\MasterProductionSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterProductionScheduleExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $productId;

    protected $month;

    protected $year;

    public function __construct($productId, $month = null, $year = null)
    {
        $this->productId = $productId;
        $this->month = $month ?? now()->month;
        $this->year = $year ?? now()->year;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MasterProductionSchedule::with('product')
            ->where('product_id', $this->productId)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->orderBy('week')
            ->get();
    }

    public function map($mps): array
    {
        return [
            $mps->product->name,
            $mps->week === 0 ? 'Week 0 [First Stock]' : 'Week '.$mps->week,
            \Carbon\Carbon::create($mps->year, $mps->month)->format('F Y'),
            $mps->forecast_value,
            $mps->available,
            $mps->mps,
            $mps->projected_on_hand,
        ];
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Week',
            'Month',
            'Forecast Value',
            'Available',
            'MPS',
            'Projected On Hand',
        ];
    }
}
