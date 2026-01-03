<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function __invoke(Request $request)
    {
        $limit = $request->input('limit', 10);

        $journals = Journal::query()
            ->when(
                $request->input('name'),
                fn ($query, $name) => $query->where('description', 'like', "%{$name}%"),
            )
            ->latest('created_at')
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        return view('finance.journal.index', compact('journals'));
    }
}
