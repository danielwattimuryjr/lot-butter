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
}
