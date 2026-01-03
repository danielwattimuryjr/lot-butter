<?php

namespace Database\Seeders;

use App\Models\Component;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Component::create([
            'code' => 'BB-05',
            'name' => 'Filling Cream',
            'weight' => 20,
            'unit' => 'Kg',
            'safety_stock' => 10,
            'category' => 'Dairy Product',
        ]);

        Component::create([
            'code' => 'BB-01',
            'name' => 'Tepung Ketan',
            'weight' => 20,
            'unit' => 'Kg',
            'safety_stock' => 10,
            'category' => 'Flour,Wheat,Grain',
        ]);

        Component::create([
            'code' => 'PK-01',
            'name' => 'Paper Case',
            'weight' => 1,
            'unit' => 'Pcs',
            'safety_stock' => 10,
            'category' => 'Packaging',
        ]);
    }
}
