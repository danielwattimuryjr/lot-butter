<?php

namespace App\Http\Controllers;

use App\Http\Requests\Procurement\CreateRequest;
use App\Http\Requests\Procurement\UpdateRequest;
use App\Models\Component;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $procurements = Purchase::query()
            ->with(['component'])
            ->when(
                $request->input('name'),
                fn ($query, $name) => $query->whereHas('component', function ($q) use ($name) {
                    $q->where('name', 'like', "%{$name}%");
                }),
            )
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        return view('supply-chain.procurement.index', compact('procurements'));
    }

    public function create()
    {
        $components = Component::get(['id', 'name']);

        return view('supply-chain.procurement.create', compact('components'));
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            Purchase::create($validated);
            DB::commit();

            return to_route('employee.supply-chain.procurements.index')
                ->with('success', 'Purchase created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create procurement entry: '.$e->getMessage());
        }
    }

    public function edit(Purchase $purchase)
    {
        $components = Component::get(['id', 'name']);

        return view('supply-chain.procurement.edit', compact('components', 'purchase'));
    }

    public function update(UpdateRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $purchase->update($validated);

            DB::commit();

            return to_route('employee.supply-chain.procurements.index')
                ->with('success', 'Purchase updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update procurement entry: '.$e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $purchase->delete();

            DB::commit();

            return to_route('employee.supply-chain.procurements.index')
                ->with('success', 'Purchase deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to delete procurement entry: '.$e->getMessage());
        }
    }
}
