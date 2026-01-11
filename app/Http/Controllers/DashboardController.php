<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Forecast;
use App\Models\Income;
use App\Models\Product;
use App\Models\Purchase;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $currentMonth = now()->format('Y-m');

        // Forecast metrics
        $totalForecastDemand = Forecast::where('year', now()->year)
            ->where('month', now()->month)
            ->sum('forecast_value');

        // Product metrics
        $totalProducts = Product::count();

        // Income metrics
        $totalIncome = Income::sum('amount');
        $monthlyIncome = Income::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Purchase metrics
        $totalPurchases = Purchase::sum('total_amount');
        $monthlyPurchases = Purchase::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Component stock alerts
        $lowStockComponents = Component::where('stock', '<', 10)->count();

        return view('dashboard.index', compact(
            'totalForecastDemand',
            'totalProducts',
            'totalIncome',
            'monthlyIncome',
            'totalPurchases',
            'monthlyPurchases',
            'lowStockComponents'
        ));
    }
}
