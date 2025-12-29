<?php

namespace App\Http\Controllers;

use App\Http\Requests\Logistic\CreateRequest;
use App\Http\Requests\Logistic\UpdateRequest;
use App\Models\Component;
use App\Models\Logistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $logistics = Logistic::query()
            ->with(['component'])
            ->when(
                $request->input('name'),
                fn($query, $name) => $query->whereHas('component', function ($q) use ($name) {
                    $q->where('name', 'like', "%{$name}%");
                })
            )
            ->latest('id')
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        return view('supply-chain.logistic.index', compact('logistics'));
    }

    public function create()
    {
        $components = Component::orderBy('name')->get(['id', 'name', 'unit', 'stock']);

        return view('supply-chain.logistic.create', compact('components'));
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            Logistic::create($validated);

            DB::commit();

            return to_route('employee.supply-chain.logistics.index')
                ->with('success', 'Logistic entry created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create logistic entry: ' . $e->getMessage());
        }
    }

    public function edit(Logistic $logistic)
    {
        $components = Component::orderBy('name')->get(['id', 'name', 'unit', 'stock']);

        return view('supply-chain.logistic.edit', compact('logistic', 'components'));
    }

    public function update(UpdateRequest $request, Logistic $logistic)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            // Store old values untuk recalculation
            $oldComponentId = $logistic->component_id;
            $oldQuantity = $logistic->quantity;
            $oldTransactionType = $logistic->transaction_type;

            // Update logistic
            $logistic->update($validated);

            // Recalculate stock_total untuk logistic ini dan setelahnya
            $this->recalculateFromLogistic($logistic);

            // Jika component berubah, recalculate old component juga
            if ($oldComponentId != $validated['component_id']) {
                $this->recalculateComponent($oldComponentId);
            }

            DB::commit();

            return to_route('employee.supply-chain.logistics.index')
                ->with('success', 'Logistic entry updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update logistic entry: ' . $e->getMessage());
        }
    }

    private function recalculateFromLogistic(Logistic $logistic)
    {
        $componentId = $logistic->component_id;

        // Get semua logistics untuk component ini, dari logistic ini ke bawah
        $logistics = Logistic::where('component_id', $componentId)
            ->where('id', '>=', $logistic->id)
            ->orderBy('id')
            ->get();

        // Get stock sebelum logistic ini
        $previousLogistic = Logistic::where('component_id', $componentId)
            ->where('id', '<', $logistic->id)
            ->latest('id')
            ->first();

        $runningStock = $previousLogistic ? $previousLogistic->stock_total : 0;

        // Recalculate
        foreach ($logistics as $log) {
            if ($log->transaction_type === 'in') {
                $runningStock += $log->quantity;
            } else {
                $runningStock -= $log->quantity;
            }

            $log->update(['stock_total' => $runningStock]);
        }

        // Update component stock
        Component::where('id', $componentId)->update(['stock' => $runningStock]);
    }

    private function recalculateComponent($componentId)
    {
        $lastLogistic = Logistic::where('component_id', $componentId)
            ->latest('id')
            ->first();

        $stock = $lastLogistic ? $lastLogistic->stock_total : 0;

        Component::where('id', $componentId)->update(['stock' => $stock]);
    }

    public function destroy(Logistic $logistic)
    {
        DB::beginTransaction();
        try {
            $logistic->delete();

            DB::commit();

            return to_route('employee.supply-chain.logistics.index')
                ->with('success', 'Logistic entry deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Failed to delete logistic entry: ' . $e->getMessage());
        }
    }
}
