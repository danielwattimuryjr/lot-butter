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
        $mochiIchigoProducts = \App\Models\Product::where('name', 'like', '%Mochi Ichigo Daifuku%');
        $mochiIchigoProducts->get()->each(function ($product) {
            $product->components()->attach([
                1 => ['quantity' => 0.007],
                2 => ['quantity' => 0.04],
                3 => ['quantity' => 1],
            ]);
        });
    }
}
