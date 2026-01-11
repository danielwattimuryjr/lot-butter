<?php

namespace App\Http\Controllers;

use App\Http\Requests\Income\CreateRequest;
use App\Http\Requests\Income\UpdateRequest;
use App\Models\Income;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $incomes = Income::query()
            ->with(['product.variants', 'productVariant', 'journal'])
            ->when(
                $request->input('name'),
                fn ($query, $name) => $query->where('description', 'like', "%{$name}%"),
            )
            ->orderBy('date_received', 'desc')
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        return view('finance.income.index', compact('incomes'));
    }

    public function show(Income $income)
    {
        $income->load(['product', 'productVariant', 'journal']);

        return response()->json([
            'code' => $income->code,
            'product' => $income->product ? $income->product->name : 'N/A',
            'variant' => $income->productVariant ? $income->productVariant->name : '-',
            'description' => $income->description,
            'quantity' => number_format($income->quantity),
            'unit_price' => 'Rp ' . number_format($income->unit_price, 0, ',', '.'),
            'amount' => 'Rp ' . number_format($income->amount, 0, ',', '.'),
            'date_received' => $income->date_received->format('d M Y'),
            'week' => $income->week,
            'status' => $income->status,
            'created_at' => $income->created_at->format('d M Y H:i'),
            'updated_at' => $income->updated_at->format('d M Y H:i'),
        ]);
    }

    public function create()
    {
        $products = Product::with('variants')->get(['id', 'name']);

        return view('finance.income.create', compact('products'));
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            Income::create($validated);

            DB::commit();

            return to_route('employee.finance.incomes.index')->with('success', 'Income created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create income entry: '.$e->getMessage());
        }
    }

    public function edit(Income $income)
    {
        $income = $income->load(['product.variants', 'productVariant', 'journal']);
        $products = Product::with('variants')->get(['id', 'name']);

        return view('finance.income.edit', compact('income', 'products'));
    }

    public function update(Income $income, UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $income->update($validated);

            DB::commit();

            return to_route('employee.finance.incomes.index')->with('success', 'Income updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update income entry: '.$e->getMessage());
        }
    }

    public function destroy(Income $income)
    {
        DB::beginTransaction();
        try {
            $income->delete();

            DB::commit();

            return to_route('employee.finance.incomes.index')->with('success', 'Income deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to delete income entry: '.$e->getMessage());
        }
    }

    public function approve(Income $income)
    {
        DB::beginTransaction();
        try {
            $income->update(['status' => 'approved']);

            DB::commit();

            return back()->with('success', 'Income approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to approve income: '.$e->getMessage());
        }
    }

    public function reject(Income $income)
    {
        DB::beginTransaction();
        try {
            $income->update(['status' => 'rejected']);

            DB::commit();

            return back()->with('success', 'Income rejected successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to reject income: '.$e->getMessage());
        }
    }
}
