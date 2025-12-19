<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PrintingMaterial;
use App\Models\PrintingFinishing;

class ProductDemoSeeder extends Seeder
{
    public function run(): void
    {
        // === PRODUCT ===
        $banner = Product::create([
            'name' => 'Banner Flexi',
            'category' => 'Large Format',
            'is_active' => true,
        ]);

        $kartu = Product::create([
            'name' => 'Kartu Nama',
            'category' => 'Offset',
            'is_active' => true,
        ]);

        // === VARIANTS ===
        ProductVariant::insert([
            [
                'product_id' => $banner->id,
                'name' => '60 x 160 cm',
                'base_price' => 35000,
            ],
            [
                'product_id' => $banner->id,
                'name' => '80 x 180 cm',
                'base_price' => 45000,
            ],
            [
                'product_id' => $banner->id,
                'name' => 'Custom / m²',
                'base_price' => 25000,
            ],
        ]);

        // === MATERIALS ===
        PrintingMaterial::insert([
            ['name' => 'Flexi China', 'price' => 15000],
            ['name' => 'Flexi Korea', 'price' => 25000],
            ['name' => 'Vinyl Glossy', 'price' => 30000],
        ]);

        // === FINISHING ===
        PrintingFinishing::insert([
            ['name' => 'Laminasi Doff', 'price' => 5000],
            ['name' => 'Laminasi Glossy', 'price' => 5000],
            ['name' => 'Mata Ayam', 'price' => 2000],
        ]);
    }
}
