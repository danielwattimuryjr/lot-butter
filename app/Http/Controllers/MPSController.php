<?php

namespace App\Http\Controllers;

use App\Models\MasterProductionSchedule;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MPSController extends Controller
{
    public function index(Product $product)
    {
        $todayDate = Carbon::now();
        $currentMonth = $todayDate->month;
        $currentYear = $todayDate->year;

        $masterProductionSchedule = MasterProductionSchedule::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->orderBy('week')
            ->get();

        $monthlyData = [];

        foreach ($masterProductionSchedule as $mps) {
            $monthlyData[$mps->week] = [
                'forecasting' => number_format($mps->forecast_value ?? 0),
                'mps' => $mps->mps_value,
                'available' => $mps->available,
                'projected_on_hand' => $mps->projected_on_hand,
                'mps_id' => $mps->id,
            ];
        }

        return view('production.product.master_production_schedule.index', compact('product', 'monthlyData', 'currentMonth'));
    }

    public function edit(Product $product, MasterProductionSchedule $master_production_schedule)
    {
        return view('production.product.master_production_schedule.edit', compact('master_production_schedule', 'product'));
    }

    public function update(Request $request, Product $product, MasterProductionSchedule $master_production_schedule)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'available' => 'nullable|integer|min:0',
                'projected_on_hand' => 'nullable|integer|min:0',
            ]);

            $master_production_schedule->update($validated);
            DB::commit();

            return to_route('employee.production.products.master-production-schedule.index', $product)
                ->with('success', 'MPS updated successfully');
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with('error', 'Failed to update MPS');
        }
    }
}
