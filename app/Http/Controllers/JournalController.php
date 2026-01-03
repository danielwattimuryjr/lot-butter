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
            ->latest('date')
            ->paginate($request->input('limit', $limit))
            ->withQueryString();

        // Get current balance (latest balance)
        $currentBalance = Journal::latest('date')->latest('id')->value('balance') ?? 0;

        // Get this month's expenses (debit)
        $thisMonthExpenses = Journal::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('debit');

        // Get this month's revenue (credit)
        $thisMonthRevenue = Journal::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('credit');

        return view('finance.journal.index', compact(
            'journals',
            'currentBalance',
            'thisMonthExpenses',
            'thisMonthRevenue'
        ));
    }
}
