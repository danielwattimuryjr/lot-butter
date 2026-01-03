<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $products = Product::query()
            ->with('variants')
            ->when(
                $request->input('name'),
                fn ($query, $name) => $query->where('name', 'like', "%{$name}%"),
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

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
        ]);

        // Create the variants
        foreach ($validated['variants'] as $variantData) {
            $product->variants()->create([
                'name' => $variantData['name'],
                'number' => $variantData['number'],
                'price' => $variantData['price'],
            ]);
        }

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

        // Update the product
        $product->update([
            'name' => $validated['name'],
        ]);

        // Get existing variant IDs
        $existingVariantIds = [];

        // Update or create variants
        foreach ($validated['variants'] as $variantData) {
            if (isset($variantData['id'])) {
                // Update existing variant
                $variant = $product->variants()->find($variantData['id']);
                if ($variant) {
                    $variant->update([
                        'name' => $variantData['name'],
                        'number' => $variantData['number'],
                        'price' => $variantData['price'],
                    ]);
                    $existingVariantIds[] = $variantData['id'];
                }
            } else {
                // Create new variant
                $newVariant = $product->variants()->create([
                    'name' => $variantData['name'],
                    'number' => $variantData['number'],
                    'price' => $variantData['price'],
                ]);
                $existingVariantIds[] = $newVariant->id;
            }
        }

        // Delete variants that are not in the request
        $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

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
