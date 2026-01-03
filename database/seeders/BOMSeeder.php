<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Mochi Ichigo Daifuku product
        $mochiIchigoProduct = \App\Models\Product::where('name', 'like', '%Mochi Ichigo Daifuku%')->first();

        if ($mochiIchigoProduct) {
            // Attach components with quantities and levels based on BOM structure
            $mochiIchigoProduct->components()->attach([
                1 => ['quantity' => 1, 'level' => 1],      // Mochi Base (FG-001)
                2 => ['quantity' => 0.04, 'level' => 2],   // Tepung Ketan (FWS-001)
                3 => ['quantity' => 0.008, 'level' => 2],  // Gula Pasir (FWS-002)
                4 => ['quantity' => 0.02, 'level' => 2],   // Fresh Strawberry (ING-001)
                5 => ['quantity' => 0.005, 'level' => 2],  // Tepung Maizena (FWS-003)
                6 => ['quantity' => 0.007, 'level' => 2],  // Dairy Product/Susu (DRY-001)
                7 => ['quantity' => 1, 'level' => 2],      // Paper Case (PKG-001)
            ]);

            // Find "Isi 4" variant and attach its BOM
            $isi4Variant = $mochiIchigoProduct->variants()->where('name', 'Isi 4')->first();
            if ($isi4Variant) {
                $isi4Variant->components()->attach([
                    8 => ['quantity' => 1, 'level' => 1],  // Box Packaging (Small) (PKG-002)
                    9 => ['quantity' => 1, 'level' => 1],  // Sticker Label (PKG-003)
                    1 => ['quantity' => 4, 'level' => 1],  // Mochi Ichigo Daifuku (Satuan) (FG-001)
                ]);
            }

            // Find "Isi 8" variant and attach its BOM
            $isi8Variant = $mochiIchigoProduct->variants()->where('name', 'Isi 8')->first();
            if ($isi8Variant) {
                $isi8Variant->components()->attach([
                    11 => ['quantity' => 1, 'level' => 1],  // Box Packaging (Medium) (PKG-005)
                    9 => ['quantity' => 1, 'level' => 1],   // Sticker Label (PKG-003)
                    1 => ['quantity' => 8, 'level' => 1],   // Mochi Ichigo Daifuku (Satuan) (FG-001)
                ]);
            }

            // Find "Isi 16" variant and attach its BOM
            $isi16Variant = $mochiIchigoProduct->variants()->where('name', 'Isi 16')->first();
            if ($isi16Variant) {
                $isi16Variant->components()->attach([
                    10 => ['quantity' => 1, 'level' => 1],  // Box Packaging (Large) (PKG-004)
                    9 => ['quantity' => 1, 'level' => 1],   // Sticker Label (PKG-003)
                    1 => ['quantity' => 16, 'level' => 1],  // Mochi Ichigo Daifuku (Satuan) (FG-001)
                ]);
            }
        }
    }
}
