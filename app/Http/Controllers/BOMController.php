<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillOfMaterial\CreateRequest;
use App\Http\Requests\BillOfMaterial\UpdateRequest;
use App\Models\Component;
use App\Models\Product;
use Illuminate\Http\Request;

class BOMController extends Controller
{
    public function index(Product $product)
    {
        $product = $product->load('components');

        return view('production.product.bill_of_material.index', compact('product'));
    }

    public function create(Product $product)
    {
        $components = Component::get(['id', 'name', 'unit']);
        return view('production.product.bill_of_material.create', compact('product', 'components'));
    }

    public function store(CreateRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->components()->attach($validated['component_id'], [
            'quantity' => $validated['quantity']
        ]);

        return to_route('employee.production.products.bill-of-materials.index', $product)
            ->with('success', 'Component successfully added to current product');
    }

    public function edit(Product $product, Component $component)
    {
        $pivot = $product->components()
            ->wherePivot('component_id', $component->id)
            ->first();

        $bom = (object) [
            'component_id' => $component->id,
            'quantity' => $pivot->pivot->quantity,
        ];

        $components = Component::get(['id', 'name', 'unit']);

        return view('production.product.bill_of_material.edit', compact('product', 'bom', 'components'));
    }

    public function update(UpdateRequest $request, Product $product, Component $component)
    {
        $validated = $request->validated();

        $product->components()->detach($component->id);

        $product->components()->attach($validated['component_id'], [
            'quantity' => $validated['quantity'],
        ]);

        return to_route('employee.production.products.bill-of-materials.index', $product)
            ->with('success', 'Bill of Material updated successfully');
    }

    public function destroy(Product $product, Component $component)
    {
        $product->components()->detach($component);

        return to_route('employee.production.products.bill-of-materials.index', $product)
            ->with('success', 'Component successfully removed from current product');
    }
}
