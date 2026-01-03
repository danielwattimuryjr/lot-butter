<?php

namespace App\Exports;

use App\Models\Component;
use App\Models\MaterialRequirementsPlanning;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaterialRequirementsPlanningExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
  protected $componentId;
  protected $month;
  protected $year;

  public function __construct($componentId, $month = null, $year = null)
  {
    $this->componentId = $componentId;
    $this->month = $month ?? now()->month;
    $this->year = $year ?? now()->year;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return MaterialRequirementsPlanning::with('component')
      ->where('component_id', $this->componentId)
      ->where('month', $this->month)
      ->where('year', $this->year)
      ->orderBy('week')
      ->get();
  }

  public function map($mrp): array
  {
    return [
      $mrp->component->name,
      $mrp->week === 0 ? 'Week 0 [First Stock]' : 'Week ' . $mrp->week,
      \Carbon\Carbon::create($mrp->year, $mrp->month)->format('F Y'),
      $mrp->gross_requirements,
      $mrp->schedule_receipts,
      $mrp->projected_on_hand,
      $mrp->net_requirements,
      $mrp->planned_order_receipts,
      $mrp->planned_order_releases,
    ];
  }

  public function headings(): array
  {
    return [
      'Component Name',
      'Week',
      'Month',
      'Gross Requirements',
      'Schedule Receipts',
      'Projected On Hand',
      'Net Requirements',
      'Planned Order Receipts',
      'Planned Order Releases',
    ];
  }
}
