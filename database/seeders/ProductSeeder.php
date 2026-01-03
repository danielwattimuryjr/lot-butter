<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::create([
            'name' => 'Mochi Ichigo Daifuku',
        ]);

        // Create variants for the product
        $product->variants()->createMany([
            [
                'name' => 'Isi 4',
                'number' => 4,
                'price' => 65000,
            ],
            [
                'name' => 'Isi 8',
                'number' => 8,
                'price' => 85000,
            ],
            [
                'name' => 'Isi 16',
                'number' => 16,
                'price' => 160000,
            ],
        ]);
    }
}
