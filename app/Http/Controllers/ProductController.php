<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $products = Product::query()
            ->when(
                $request->input('name'),
                fn($query, $name) => $query->where('name', 'like', "%{$name}%"),
            )
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        return view('production.product.index', compact('products'));
    }

    public function create()
    {
        return view('production.product.create');
    }

    public function store(CreateRequest $request)
    {
        $validated = $request->validated();

        Product::create($validated);

        return to_route('employee.production.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('production.product.edit', compact('product'));
    }

    public function update(UpdateRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update($validated);

        return to_route('employee.production.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return to_route('employee.production.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
