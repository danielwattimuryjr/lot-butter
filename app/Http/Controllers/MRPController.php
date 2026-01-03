<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\MaterialRequirementsPlanning;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MRPController extends Controller
{
    public function index(Request $request, Component $component)
    {
        // Get available months/years from MRP data for this component
        $availableMonths = MaterialRequirementsPlanning::where('component_id', $component->id)
            ->select('month', 'year')
            ->distinct()
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'year' => $item->year,
                    'label' => Carbon::create($item->year, $item->month)->format('F Y'),
                ];
            });

        // Get selected month and year from query parameters or default to current
        $todayDate = Carbon::now();
        $selectedMonth = $request->query('month', $todayDate->month);
        $selectedYear = $request->query('year', $todayDate->year);

        // Get MRP data for this component and selected month/year
        $mrpData = MaterialRequirementsPlanning::where('component_id', $component->id)
            ->where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->orderBy('week')
            ->get();

        // Organize data by week
        $weeklyData = [];
        foreach ($mrpData as $mrp) {
            $weeklyData[$mrp->week] = [
                'gross_requirements' => $mrp->gross_requirements,
                'schedule_receipts' => $mrp->schedule_receipts,
                'projected_on_hand' => $mrp->projected_on_hand,
                'net_requirements' => $mrp->net_requirements,
                'planned_order_receipts' => $mrp->planned_order_receipts,
                'planned_order_releases' => $mrp->planned_order_releases,
                'mrp_id' => $mrp->id,
            ];
        }

        return view('production.components.material_requirements_planning.index', compact('component', 'weeklyData', 'availableMonths', 'selectedMonth', 'selectedYear'));
    }

    public function edit(Component $component, MaterialRequirementsPlanning $materialRequirementsPlanning)
    {
        // Ensure the MRP record belongs to this component
        if ($materialRequirementsPlanning->component_id !== $component->id) {
            abort(404);
        }

        return view('production.components.material_requirements_planning.edit', compact('component', 'materialRequirementsPlanning'));
    }

    public function update(Request $request, Component $component, MaterialRequirementsPlanning $materialRequirementsPlanning)
    {
        // Ensure the MRP record belongs to this component
        if ($materialRequirementsPlanning->component_id !== $component->id) {
            abort(404);
        }

        $validated = $request->validate([
            'schedule_receipts' => 'nullable|numeric|min:0',
            'projected_on_hand' => 'nullable|numeric',
            'planned_order_receipts' => 'nullable|numeric|min:0',
            'planned_order_releases' => 'nullable|numeric|min:0',
        ]);

        // Update the MRP record
        $materialRequirementsPlanning->update($validated);

        return redirect()
            ->route('employee.production.components.material-requirements-planning.index', $component)
            ->with('success', 'MRP updated successfully. Subsequent weeks have been recalculated.');
    }
}
