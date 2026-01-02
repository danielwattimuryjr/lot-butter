<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Mochi Ichigo Daifuku',
            'pack' => 4,
            'price' => 65000,
        ]);

        Product::create([
            'name' => 'Mochi Ichigo Daifuku',
            'pack' => 8,
            'price' => 85000,
        ]);

        Product::create([
            'name' => 'Mochi Ichigo Daifuku',
            'pack' => 16,
            'price' => 160000,
        ]);
    }
}
