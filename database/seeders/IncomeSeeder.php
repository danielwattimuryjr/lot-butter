<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Income;
use Carbon\Carbon;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::whereIn('id', [1, 2, 3])->get();

        if ($products->isEmpty()) {
            $this->command->error('Products not found');
            return;
        }

        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 10, 31)->endOfWeek(); // End of week 4 in October
        $weeks = $startDate->diffInWeeks($endDate);

        $baseQuantity = 50;
        $incomeCounter = 1;

        foreach ($products as $product) {
            for ($i = 0; $i < $weeks; $i++) {
                $date = $startDate->copy()->addWeeks($i);
                $week = $date->weekOfYear;
                $month = $date->month;

                // 📈 tren naik perlahan
                $trend = $baseQuantity + ($i * 2);

                // 🎄 seasonality Desember
                $seasonal = ($month == 12) ? rand(20, 40) : rand(-10, 10);

                $quantity = max(10, $trend + $seasonal);
                $unitPrice = $product->price;
                $amount = $quantity * $unitPrice;

                Income::create([
                    'code' => 'INC-' . str_pad($incomeCounter, 4, '0', STR_PAD_LEFT),
                    'product_id' => $product->id,
                    'description' => 'Penjualan minggu ke-' . $week . ' - ' . $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                    'date_received' => $date->toDateString(),
                    'week' => $week,
                ]);

                $incomeCounter++;
            }
        }
    }
}
