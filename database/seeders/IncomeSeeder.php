<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\Product;
use App\Services\JournalService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $journalService = app(JournalService::class);

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

        // Sales percentage target per variant
        // Isi 4: 45.01%, Isi 8: 45.83%, Isi 16: 9.16%
        $variantsList = $variants->sortBy('number')->values();
        $targetPercentages = [
            $variantsList[0]->id => 45.01, // Isi 4
            $variantsList[1]->id => 45.83, // Isi 8
            $variantsList[2]->id => 9.16,  // Isi 16
        ];

        foreach ($quantities as $index => $quantity) {
            // Get date for this week
            $date = $startDate->copy()->addWeeks($index);
            $week = $index + 1; // Continuous week numbering: 1, 2, 3, ...

            // Distribute quantity among variants based on target percentages
            $variantIndex = 0;
            foreach ($variantsList as $variant) {
                $percentage = $targetPercentages[$variant->id];
                $variantQuantity = round($quantity * ($percentage / 100));

                if ($variantQuantity <= 0) {
                    continue;
                }

                // Calculate amount based on variant price
                $unitPrice = $variant->price;
                $amount = $variantQuantity * $unitPrice;

                $income = Income::create([
                    'code' => 'INC-' . str_pad(($index * 3) + $variantIndex + 1, 4, '0', STR_PAD_LEFT),
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'description' => 'Historical Week ' . $week . ' - ' . $variant->name,
                    'quantity' => $variantQuantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                    'date_received' => $date->toDateString(),
                    'week' => $week,
                ]);

                // Create journal entry using the service
                $journalService->createFromIncome($income);

                $variantIndex++;
            }
        }

        $totalRecords = count($quantities) * $variants->count();
        $this->command->info('Created ' . $totalRecords . ' income records for Mochi Ichigo Daifuku');
        $this->command->info('Created ' . $totalRecords . ' journal entries');
        $this->command->info('Sales percentage distribution: Isi 4 (45.01%), Isi 8 (45.83%), Isi 16 (9.16%)');
    }
}
