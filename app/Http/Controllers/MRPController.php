<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\Component;
use App\Models\MasterProductionSchedule;
use App\Models\MaterialRequirementsPlanning;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MRPController extends Controller
{
    /**
     * Show product selector page for MRP
     */
    public function productSelector()
    {
        $products = Product::with('variants')->get();

        return view('production.mrp.product_selector', compact('products'));
    }

    /**
     * Show MRP overview for a specific product (all levels)
     */
    public function overview(Product $product)
    {
        $product->load('variants');

        // Get Level 1 components from Product BOM
        $productLevel1Components = Component::whereIn('id', function ($query) use ($product) {
            $query->select('component_id')
                ->from('bill_of_materials')
                ->where('product_id', $product->id)
                ->where('level', 1)
                ->whereNull('product_variant_id');
        })->get();

        // Get Level 1 components from each Variant BOM (grouped)
        $variantLevel1Components = [];
        foreach ($product->variants as $variant) {
            $components = Component::whereIn('id', function ($query) use ($variant) {
                $query->select('component_id')
                    ->from('bill_of_materials')
                    ->where('product_variant_id', $variant->id)
                    ->where('level', 1);
            })->get();

            if ($components->isNotEmpty()) {
                $variantLevel1Components[$variant->id] = [
                    'variant' => $variant,
                    'components' => $components,
                ];
            }
        }

        // Get Level 2 components from Product BOM
        $level2Components = Component::whereIn('id', function ($query) use ($product) {
            $query->select('component_id')
                ->from('bill_of_materials')
                ->where('product_id', $product->id)
                ->where('level', 2);
        })->get();

        return view('production.mrp.overview', compact(
            'product',
            'productLevel1Components',
            'variantLevel1Components',
            'level2Components'
        ));
    }

    /**
     * Show MRP table for Level 0 (Variant)
     */
    public function level0(Product $product, ProductVariant $variant)
    {
        $todayDate = Carbon::now();
        $currentMonth = $todayDate->month;
        $currentYear = $todayDate->year;

        // Get MPS data for this variant (for Gross Requirements)
        $mpsData = MasterProductionSchedule::where('product_variant_id', $variant->id)
            ->where('year', $currentYear)
            ->orderBy('week')
            ->get()
            ->keyBy('week');

        // Get beginning inventory
        $mpsRecord = MasterProductionSchedule::where('product_variant_id', $variant->id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();
        $beginningInventory = $mpsRecord->beginning_inventory ?? 0;

        // Get forecast data for this month only
        $forecasts = DB::table('forecasts')
            ->where('product_id', $product->id)
            ->where('year', $currentYear)
            ->whereRaw('MONTH(STR_TO_DATE(CONCAT(year, \'-W\', LPAD(week, 2, \'0\'), \'-1\'), \'%X-W%V-%w\')) = ?', [$currentMonth])
            ->orderBy('week')
            ->get();

        // Get edited MRP records
        $mrpRecords = MaterialRequirementsPlanning::where('level', '0')
            ->where('product_variant_id', $variant->id)
            ->where('year', $currentYear)
            ->get()
            ->keyBy('week');

        return view('production.mrp.table', compact(
            'product',
            'variant',
            'forecasts',
            'mpsData',
            'mrpRecords',
            'currentYear',
            'currentMonth',
            'beginningInventory'
        ))->with('level', '0')->with('entityName', $variant->name)->with('entity', $variant);
    }

    /**
     * Show MRP table for Level 1 Product Component (Aggregate from all variants)
     */
    public function level1Product(Product $product, Component $component)
    {
        $todayDate = Carbon::now();
        $currentMonth = $todayDate->month;
        $currentYear = $todayDate->year;

        // Get BOM data for this component
        $bom = DB::table('bill_of_materials')
            ->where('product_id', $product->id)
            ->where('component_id', $component->id)
            ->where('level', 1)
            ->whereNull('product_variant_id')
            ->first();

        if (! $bom) {
            abort(404, 'Component not found in product BOM');
        }

        $bomQuantity = $bom->quantity;

        // Get aggregated beginning inventory from all variants
        $variantIds = $product->variants->pluck('id');
        $beginningInventory = MasterProductionSchedule::whereIn('product_variant_id', $variantIds)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->sum('beginning_inventory');

        // Get forecast data for this month only
        $forecasts = DB::table('forecasts')
            ->where('product_id', $product->id)
            ->where('year', $currentYear)
            ->whereRaw('MONTH(STR_TO_DATE(CONCAT(year, \'-W\', LPAD(week, 2, \'0\'), \'-1\'), \'%X-W%V-%w\')) = ?', [$currentMonth])
            ->orderBy('week')
            ->get();

        // Get edited MRP records
        $mrpRecords = MaterialRequirementsPlanning::where('level', '1')
            ->where('product_id', $product->id)
            ->where('component_id', $component->id)
            ->where('year', $currentYear)
            ->get()
            ->keyBy('week');

        return view('production.mrp.table', compact(
            'product',
            'component',
            'forecasts',
            'mrpRecords',
            'currentYear',
            'currentMonth',
            'beginningInventory',
            'bomQuantity'
        ))->with('level', '1')->with('entityName', $component->name)->with('entity', $component)->with('variant', null)->with('isProductLevel', true);
    }

    /**
     * Show MRP table for Level 1 Variant Component
     */
    public function level1Variant(Product $product, ProductVariant $variant, Component $component)
    {
        $todayDate = Carbon::now();
        $currentMonth = $todayDate->month;
        $currentYear = $todayDate->year;

        // Get BOM data for this component
        $bom = DB::table('bill_of_materials')
            ->where('product_variant_id', $variant->id)
            ->where('component_id', $component->id)
            ->where('level', 1)
            ->first();

        if (! $bom) {
            abort(404, 'Component not found in variant BOM');
        }

        $bomQuantity = $bom->quantity;

        // Get beginning inventory for this variant
        $mpsRecord = MasterProductionSchedule::where('product_variant_id', $variant->id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->first();
        $beginningInventory = $mpsRecord->beginning_inventory ?? 0;

        // Get forecast data for this month only
        $forecasts = DB::table('forecasts')
            ->where('product_id', $product->id)
            ->where('year', $currentYear)
            ->whereRaw('MONTH(STR_TO_DATE(CONCAT(year, \'-W\', LPAD(week, 2, \'0\'), \'-1\'), \'%X-W%V-%w\')) = ?', [$currentMonth])
            ->orderBy('week')
            ->get();

        // Get edited MRP records
        $mrpRecords = MaterialRequirementsPlanning::where('level', '1')
            ->where('product_variant_id', $variant->id)
            ->where('component_id', $component->id)
            ->where('year', $currentYear)
            ->get()
            ->keyBy('week');

        return view('production.mrp.table', compact(
            'product',
            'variant',
            'component',
            'forecasts',
            'mrpRecords',
            'currentYear',
            'currentMonth',
            'beginningInventory',
            'bomQuantity'
        ))->with('level', '1')->with('entityName', $component->name)->with('entity', $component)->with('isProductLevel', false);
    }

    /**
     * Show MRP table for Level 2 (Component)
     */
    public function level2(Product $product, Component $component)
    {
        $todayDate = Carbon::now();
        $currentMonth = $todayDate->month;
        $currentYear = $todayDate->year;

        // Get BOM quantity for this component
        $bom = BillOfMaterial::where('product_id', $product->id)
            ->where('component_id', $component->id)
            ->first();

        $bomQuantity = $bom ? $bom->quantity : 0;

        // Get all variant IDs for this product
        $variantIds = $product->variants->pluck('id');

        // Get aggregated beginning inventory
        $beginningInventory = MasterProductionSchedule::whereIn('product_variant_id', $variantIds)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->sum('beginning_inventory');

        // Get forecast data for this month only
        $forecasts = DB::table('forecasts')
            ->where('product_id', $product->id)
            ->where('year', $currentYear)
            ->whereRaw('MONTH(STR_TO_DATE(CONCAT(year, \'-W\', LPAD(week, 2, \'0\'), \'-1\'), \'%X-W%V-%w\')) = ?', [$currentMonth])
            ->orderBy('week')
            ->get();

        // Get edited MRP records
        $mrpRecords = MaterialRequirementsPlanning::where('level', '2')
            ->where('component_id', $component->id)
            ->where('year', $currentYear)
            ->get()
            ->keyBy('week');

        return view('production.mrp.table', compact(
            'product',
            'component',
            'forecasts',
            'mrpRecords',
            'currentYear',
            'currentMonth',
            'bomQuantity',
            'beginningInventory'
        ))->with('level', '2')->with('entityName', $component->name)->with('entity', $component)->with('variant', null);
    }

    /**
     * Show edit page for MRP week
     */
    public function edit(Request $request, $level, $entityId, $year, $week)
    {
        $month = Carbon::now()->month;

        // Get entity based on level
        if ($level == '0') {
            $entity = ProductVariant::with('product')->findOrFail($entityId);
            $product = $entity->product;
            $entityName = $entity->name;
            $variantId = $entity->id;
        } elseif ($level == '1') {
            // Level 1 could be component with either product_id or product_variant_id
            $entity = Component::findOrFail($entityId);
            $entityName = $entity->name;

            $productId = $request->query('product_id');
            $variantId = $request->query('variant_id');

            $product = Product::findOrFail($productId);
            $variant = $variantId ? ProductVariant::find($variantId) : null;
        } else {
            $entity = Component::findOrFail($entityId);
            $product = Product::find($request->query('product_id'));
            $entityName = $entity->name;
            $variantId = null;
        }

        // Get existing MRP record
        $mrpRecord = MaterialRequirementsPlanning::where('level', $level)
            ->where('year', $year)
            ->where('week', $week)
            ->where('component_id', $entityId)
            ->when($level == '0', fn ($q) => $q->where('product_variant_id', $entityId))
            ->when($level == '1' && isset($variantId) && $variantId, fn ($q) => $q->where('product_variant_id', $variantId))
            ->when($level == '1' && (! isset($variantId) || ! $variantId), fn ($q) => $q->where('product_id', $product->id))
            ->when($level == '2', fn ($q) => $q->where('product_id', $product->id))
            ->first();

        return view('production.mrp.edit', compact(
            'level',
            'entity',
            'entityId',
            'entityName',
            'product',
            'year',
            'month',
            'week',
            'mrpRecord'
        ))->with('variant', $variant ?? null)->with('variantId', $variantId ?? null);
    }

    /**
     * Store/Update MRP values
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'level' => 'required|in:0,1,2',
                'product_id' => 'nullable|exists:products,id',
                'product_variant_id' => 'nullable|exists:product_variants,id',
                'component_id' => 'nullable|exists:components,id',
                'year' => 'required|integer',
                'week' => 'required|integer',
                'scheduled_receipts' => 'nullable|integer|min:0',
                'projected_on_hand' => 'nullable|integer',
                'planned_order_receipts' => 'nullable|integer|min:0',
                'planned_order_releases' => 'nullable|integer|min:0',
            ]);

            $validated['is_edited'] = true;

            // Build unique key based on level
            $uniqueKey = [
                'level' => $validated['level'],
                'year' => $validated['year'],
                'week' => $validated['week'],
            ];

            if ($validated['level'] == '0') {
                $uniqueKey['product_variant_id'] = $validated['product_variant_id'];
                $uniqueKey['component_id'] = null;
                $uniqueKey['product_id'] = null;
            } elseif ($validated['level'] == '1') {
                $uniqueKey['component_id'] = $validated['component_id'];
                // Level 1 can have either product_id or product_variant_id
                if (! empty($validated['product_variant_id'])) {
                    $uniqueKey['product_variant_id'] = $validated['product_variant_id'];
                    $uniqueKey['product_id'] = null;
                } else {
                    $uniqueKey['product_id'] = $validated['product_id'];
                    $uniqueKey['product_variant_id'] = null;
                }
            } else {
                $uniqueKey['component_id'] = $validated['component_id'];
                $uniqueKey['product_id'] = $validated['product_id'];
                $uniqueKey['product_variant_id'] = null;
            }

            $mrp = MaterialRequirementsPlanning::updateOrCreate($uniqueKey, $validated);

            DB::commit();

            // Redirect based on level
            if ($validated['level'] == '0') {
                $variant = ProductVariant::find($validated['product_variant_id']);

                return redirect()
                    ->route('employee.production.mrp.level0', [$variant->product, $variant])
                    ->with('success', 'MRP values updated successfully');
            } elseif ($validated['level'] == '1') {
                $component = Component::find($validated['component_id']);
                if (! empty($validated['product_variant_id'])) {
                    // Variant-specific component
                    $variant = ProductVariant::find($validated['product_variant_id']);

                    return redirect()
                        ->route('employee.production.mrp.level1-variant', [$variant->product, $variant, $component])
                        ->with('success', 'MRP values updated successfully');
                } else {
                    // Product-level component
                    $product = Product::find($validated['product_id']);

                    return redirect()
                        ->route('employee.production.mrp.level1-product', [$product, $component])
                        ->with('success', 'MRP values updated successfully');
                }
            } else {
                return redirect()
                    ->route('employee.production.mrp.level2', [$validated['product_id'], $validated['component_id']])
                    ->with('success', 'MRP values updated successfully');
            }
        } catch (Exception $th) {
            DB::rollBack();

            return back()->with('error', 'Failed to update MRP values: '.$th->getMessage());
        }
    }
}
