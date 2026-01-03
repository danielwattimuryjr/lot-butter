<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        // Get Mochi Ichigo Daifuku product and its variants
        $product = Product::where('name', 'Mochi Ichigo Daifuku')->first();

        if (! $product) {
            $this->command->error('Mochi Ichigo Daifuku product not found');

            return;
        }

        $variants = $product->variants;

        if ($variants->isEmpty()) {
            $this->command->error('No variants found for Mochi Ichigo Daifuku');

            return;
        }

        // Actual quantities from January to October (40 weeks)
        $quantities = [
            4352,
            4224,
            4416,
            4480,
            4320,
            4160,
            4288,
            4352,
            4480,
            4544,
            4608,
            4800,
            7200,
            7520,
            6080,
            5120,
            4480,
            4352,
            4416,
            4480,
            4320,
            4288,
            4352,
            4416,
            4480,
            4544,
            4608,
            4800,
            6880,
            6560,
            5120,
            4800,
            4480,
            4352,
            4416,
            4480,
            4320,
            4160,
            4288,
            4352,
        ];

        $startDate = Carbon::create(2025, 1, 6); // First Monday of 2025

        foreach ($quantities as $index => $quantity) {
            // Get date for this week
            $date = $startDate->copy()->addWeeks($index);
            $week = $date->weekOfYear;

            // Randomly select a variant
            $variant = $variants->random();

            // Calculate amount based on variant price
            $unitPrice = $variant->price;
            $amount = $quantity * $unitPrice;

            Income::create([
                'code' => 'INC-'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'description' => 'Penjualan minggu ke-'.$week.' - '.$product->name.' ('.$variant->name.')',
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'amount' => $amount,
                'date_received' => $date->toDateString(),
                'week' => $week,
            ]);
        }

        $this->command->info('Created '.count($quantities).' income records for Mochi Ichigo Daifuku');
    }
}
