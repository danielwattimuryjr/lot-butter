<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillOfMaterial\CreateRequest;
use App\Http\Requests\BillOfMaterial\UpdateRequest;
use App\Models\BillOfMaterial;
use App\Models\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class BOMController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->input('product');
        $variantId = $request->input('variant_id');
        $search = $request->input('search');

        // If no product selected, show product selector
        if (!$productId) {
            $products = Product::query()
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->get();
            return view('production.bill_of_material.select', compact('products', 'search'));
        }

        $product = Product::with('variants')->findOrFail($productId);
        $selectedVariant = null;
        $components = collect();

        if ($variantId) {
            $selectedVariant = ProductVariant::with('components')->find($variantId);
            if ($selectedVariant) {
                $components = $selectedVariant->components;
            }
        } else {
            $product->load('components');
            $components = $product->components;
        }

        return view('production.bill_of_material.index', compact('product', 'selectedVariant', 'components'));
    }

    public function create(Request $request)
    {
        $productId = $request->input('product');
        $variantId = $request->input('variant_id');

        if (!$productId) {
            return redirect()->route('employee.production.products.index')
                ->with('error', 'Please select a product first.');
        }

        $product = Product::findOrFail($productId);
        $selectedVariant = $variantId ? ProductVariant::find($variantId) : null;

        $components = Component::get(['id', 'name', 'unit']);

        return view('production.bill_of_material.create', compact('product', 'selectedVariant', 'components'));
    }

    public function store(CreateRequest $request)
    {
        $validated = $request->validated();
        $productId = $request->input('product');
        $variantId = $request->input('variant_id');

        foreach ($validated['components'] as $componentData) {
            BillOfMaterial::create([
                'product_id' => $variantId ? null : $productId,
                'product_variant_id' => $variantId ?: null,
                'component_id' => $componentData['component_id'],
                'quantity' => $componentData['quantity'],
                'level' => $componentData['level'],
            ]);
        }

        return redirect()->route('employee.production.bill-of-materials.index', [
            'product' => $productId,
            'variant_id' => $variantId
        ])->with('success', 'Components successfully added to BOM');
    }

    public function edit(Request $request, $bomId)
    {
        $bom = BillOfMaterial::with('component')->findOrFail($bomId);
        $productId = $request->input('product');
        $variantId = $request->input('variant_id');

        if (!$productId) {
            return redirect()->route('employee.production.products.index')
                ->with('error', 'Please select a product first.');
        }

        $product = Product::findOrFail($productId);
        $selectedVariant = $variantId ? ProductVariant::find($variantId) : null;

        $components = Component::get(['id', 'name', 'unit']);

        return view('production.bill_of_material.edit', compact('product', 'bom', 'selectedVariant', 'components'));
    }

    public function update(UpdateRequest $request, $bomId)
    {
        $validated = $request->validated();
        $productId = $request->input('product');
        $variantId = $request->input('variant_id');

        $bom = BillOfMaterial::findOrFail($bomId);
        $bom->update([
            'component_id' => $validated['component_id'],
            'quantity' => $validated['quantity'],
            'level' => $validated['level'],
        ]);

        return redirect()->route('employee.production.bill-of-materials.index', [
            'product' => $productId,
            'variant_id' => $variantId
        ])->with('success', 'Bill of Material updated successfully');
    }

    public function destroy(Request $request, $bomId)
    {
        $productId = $request->input('product');
        $variantId = $request->input('variant_id');

        $bom = BillOfMaterial::findOrFail($bomId);
        $bom->delete();

        return redirect()->route('employee.production.bill-of-materials.index', [
            'product' => $productId,
            'variant_id' => $variantId
        ])->with('success', 'Component successfully removed from BOM');
    }
}
