<?php

namespace App\Http\Controllers;

use App\Models\MasterProductionSchedule;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MPSController extends Controller
{
    /**
     * Show product selector page for standalone MPS access
     */
    public function productSelector()
    {
        $products = Product::with('variants')->get();

        return view('production.master_production_schedule.product_selector', compact('products'));
    }

    /**
     * Show variant selector for a specific product
     */
    public function variantSelector(Product $product)
    {
        $product->load('variants');

        return view('production.master_production_schedule.variant_selector', compact('product'));
    }

    /**
     * Display MPS for a specific product variant
     */
    public function index(Product $product, ProductVariant $variant)
    {
        $todayDate = Carbon::now();
        $currentMonth = $todayDate->month;
        $currentYear = $todayDate->year;

        // Get beginning inventory for this variant (if exists)
        $mpsRecord = MasterProductionSchedule::where('product_variant_id', $variant->id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();

        $beginningInventory = $mpsRecord->beginning_inventory ?? 0;

        // Get forecasts for the product (not variant-specific)
        $forecasts = DB::table('forecasts')
            ->where('product_id', $product->id)
            ->where('year', $currentYear)
            ->whereRaw('MONTH(STR_TO_DATE(CONCAT(year, \'-W\', LPAD(week, 2, \'0\'), \'-1\'), \'%X-W%V-%w\')) = ?', [$currentMonth])
            ->orderBy('week')
            ->get();

        // Get existing MPS records for edited values
        $mpsRecords = MasterProductionSchedule::where('product_variant_id', $variant->id)
            ->where('year', $currentYear)
            ->where('is_edited', true)
            ->get();

        // Pass data to view for frontend calculation
        return view('production.master_production_schedule.index', compact(
            'product',
            'variant',
            'forecasts',
            'beginningInventory',
            'currentMonth',
            'currentYear',
            'mpsRecord',
            'mpsRecords'
        ));
    }

    public function edit(Product $product, MasterProductionSchedule $master_production_schedule)
    {
        return view('production.product.master_production_schedule.edit', compact('master_production_schedule', 'product'));
    }

    public function editWeek(Product $product, ProductVariant $variant, $year, $week)
    {
        $month = Carbon::now()->month;

        // Get existing MPS record if exists
        $mpsRecord = MasterProductionSchedule::where('product_variant_id', $variant->id)
            ->where('year', $year)
            ->where('week', $week)
            ->first();

        return view('production.master_production_schedule.edit', compact(
            'product',
            'variant',
            'year',
            'month',
            'week',
            'mpsRecord'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'year' => 'required|integer',
                'month' => 'required|integer|min:1|max:12',
                'beginning_inventory' => 'required|integer|min:0',
            ]);

            $mps = MasterProductionSchedule::create($validated);
            DB::commit();

            return back()->with('success', 'Beginning inventory created successfully');
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with('error', 'Failed to create beginning inventory');
        }
    }

    public function update(Request $request, MasterProductionSchedule $mps)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'beginning_inventory' => 'required|integer|min:0',
            ]);

            $mps->update($validated);
            DB::commit();

            // Redirect back to the MPS view
            return back()->with('success', 'Beginning inventory updated successfully');
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with('error', 'Failed to update beginning inventory');
        }
    }

    public function storeWeekly(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'year' => 'required|integer',
                'month' => 'required|integer|min:1|max:12',
                'week' => 'required|integer',
                'beginning_inventory' => 'nullable|integer|min:0',
                'projected_on_hand' => 'nullable|integer',
                'available' => 'nullable|integer',
            ]);

            $validated['is_edited'] = true;

            // Use updateOrCreate to handle both create and update
            $mps = MasterProductionSchedule::updateOrCreate(
                [
                    'product_variant_id' => $validated['product_variant_id'],
                    'year' => $validated['year'],
                    'week' => $validated['week'],
                ],
                $validated
            );

            DB::commit();

            // Get product and variant for redirect
            $variant = ProductVariant::find($validated['product_variant_id']);
            $product = $variant->product;

            return redirect()
                ->route('employee.production.master-production-schedules.variant', [$product, $variant])
                ->with('success', 'Week values updated successfully');
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with('error', 'Failed to update week values: '.$th->getMessage());
        }
    }
}
