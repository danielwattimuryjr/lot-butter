<?php

namespace Database\Seeders;

use App\Models\Component;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding components...');

        $components = [
            [
                'code' => 'FG-001',
                'name' => 'Mochi Ichigo Daifuku',
                'unit' => 'Pcs',
                'category' => 'Finished Goods',
                'safety_stock' => 50,
            ],
            [
                'code' => 'FWS-001',
                'name' => 'Tepung Ketan',
                'unit' => 'Kg',
                'category' => 'Flour, Wheat, Sugar',
                'safety_stock' => 100,
            ],
            [
                'code' => 'FWS-002',
                'name' => 'Gula Pasir',
                'unit' => 'Kg',
                'category' => 'Flour, Wheat, Sugar',
                'safety_stock' => 80,
            ],
            [
                'code' => 'ING-001',
                'name' => 'Fresh Strawberry',
                'unit' => 'Kg',
                'category' => 'Ingredients',
                'safety_stock' => 30,
            ],
            [
                'code' => 'FWS-003',
                'name' => 'Tepung Maizena',
                'unit' => 'Kg',
                'category' => 'Flour, Wheat, Sugar',
                'safety_stock' => 50,
            ],
            [
                'code' => 'DRY-001',
                'name' => 'Dairy Product',
                'unit' => 'Kg',
                'category' => 'Dairy Product',
                'safety_stock' => 40,
            ],
            [
                'code' => 'PKG-001',
                'name' => 'Paper Case',
                'unit' => 'Pcs',
                'category' => 'Packaging',
                'safety_stock' => 500,
            ],
            [
                'code' => 'PKG-002',
                'name' => 'Box Packaging (Small)',
                'unit' => 'Pcs',
                'category' => 'Packaging',
                'safety_stock' => 200,
            ],
            [
                'code' => 'PKG-003',
                'name' => 'Sticker Label',
                'unit' => 'Pcs',
                'category' => 'Packaging',
                'safety_stock' => 1000,
            ],
            [
                'code' => 'PKG-004',
                'name' => 'Box Packaging (Large)',
                'unit' => 'Pcs',
                'category' => 'Packaging',
                'safety_stock' => 150,
            ],
            [
                'code' => 'PKG-005',
                'name' => 'Box Packaging (Medium)',
                'unit' => 'Pcs',
                'category' => 'Packaging',
                'safety_stock' => 150,
            ],
        ];

        foreach ($components as $component) {
            Component::create($component);
        }

        $this->command->info('Created '.count($components).' components successfully');
    }
}
