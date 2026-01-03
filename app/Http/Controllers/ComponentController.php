<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;
use App\Http\Requests\Component\CreateRequest;
use App\Http\Requests\Component\UpdateRequest;

class ComponentController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $components = Component::query()
            ->when(
                $request->input('name'),
                fn($query, $name) => $query->where('name', 'like', "%{$name}%"),
            )
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        return view('production.components.index', compact('components'));
    }

    public function create()
    {
        // Get distinct categories from existing components
        $categories = Component::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('production.components.create', compact('categories'));
    }

    public function store(CreateRequest $request)
    {
        $validated = $request->validated();

        Component::create($validated);
        return to_route('employee.production.components.index')
            ->with('success', 'Component created successfully.');
    }

    public function edit(Component $component)
    {
        return view('production.components.edit', compact('component'));
    }

    public function update(UpdateRequest $request, Component $component)
    {
        $validated = $request->validated();

        $component->update($validated);
        return to_route('employee.production.components.index')
            ->with('success', 'Component updated successfully.');
    }

    public function destroy(Component $component)
    {
        $component->delete();

        return to_route('employee.production.components.index')
            ->with('success', 'Component deleted successfully.');
    }
}
