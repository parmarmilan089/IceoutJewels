<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\VariantOption;
use App\Models\Product;
use App\Models\Category;
use App\Services\VariantCombinationService;

class ProductModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Variant Types
        $color = ProductVariant::create([
            'name' => 'Color',
            'slug' => 'color',
            'display_order' => 1,
            'is_active' => true
        ]);

        $size = ProductVariant::create([
            'name' => 'Size',
            'slug' => 'size',
            'display_order' => 2,
            'is_active' => true
        ]);

        $metal = ProductVariant::create([
            'name' => 'Metal',
            'slug' => 'metal',
            'display_order' => 3,
            'is_active' => true
        ]);

        $diamondQuality = ProductVariant::create([
            'name' => 'Diamond Quality',
            'slug' => 'diamond-quality',
            'display_order' => 4,
            'is_active' => true
        ]);

        // Create Color Options
        VariantOption::create([
            'product_variant_id' => $color->id,
            'option_name' => 'Silver',
            'sku_code' => 'SIL',
            'additional_price' => 10.00,
            'display_order' => 1,
            'is_active' => true
        ]);

        VariantOption::create([
            'product_variant_id' => $color->id,
            'option_name' => 'Gold',
            'sku_code' => 'GLD',
            'additional_price' => 20.00,
            'display_order' => 2,
            'is_active' => true
        ]);

        VariantOption::create([
            'product_variant_id' => $color->id,
            'option_name' => 'Rose Gold',
            'sku_code' => 'RSG',
            'additional_price' => 15.00,
            'display_order' => 3,
            'is_active' => true
        ]);

        // Create Size Options
        VariantOption::create([
            'product_variant_id' => $size->id,
            'option_name' => 'Small',
            'sku_code' => 'SM',
            'additional_price' => 0.00,
            'display_order' => 1,
            'is_active' => true
        ]);

        VariantOption::create([
            'product_variant_id' => $size->id,
            'option_name' => 'Medium',
            'sku_code' => 'MD',
            'additional_price' => 5.00,
            'display_order' => 2,
            'is_active' => true
        ]);

        VariantOption::create([
            'product_variant_id' => $size->id,
            'option_name' => 'Large',
            'sku_code' => 'LG',
            'additional_price' => 10.00,
            'display_order' => 3,
            'is_active' => true
        ]);

        // Create Metal Options
        VariantOption::create([
            'product_variant_id' => $metal->id,
            'option_name' => 'Titanium',
            'sku_code' => 'TI',
            'additional_price' => 20.00,
            'display_order' => 1,
            'is_active' => true
        ]);

        VariantOption::create([
            'product_variant_id' => $metal->id,
            'option_name' => 'Stainless Steel',
            'sku_code' => 'SS',
            'additional_price' => 10.00,
            'display_order' => 2,
            'is_active' => true
        ]);

        // Create Diamond Quality Options
        VariantOption::create([
            'product_variant_id' => $diamondQuality->id,
            'option_name' => 'VVS',
            'sku_code' => 'VVS',
            'additional_price' => 50.00,
            'display_order' => 1,
            'is_active' => true
        ]);

        VariantOption::create([
            'product_variant_id' => $diamondQuality->id,
            'option_name' => 'VS',
            'sku_code' => 'VS',
            'additional_price' => 30.00,
            'display_order' => 2,
            'is_active' => true
        ]);

        // Create Sample Products
        $category = Category::first();
        
        if ($category) {
            $product1 = Product::create([
                'name' => 'Ice-Out Cuban Chain',
                'sku' => 'PROD000001',
                'slug' => 'ice-out-cuban-chain',
                'category_id' => $category->id,
                'short_description' => 'Premium ice-out Cuban chain with VVS diamonds',
                'long_description' => 'High-quality iced-out Cuban chain crafted from 925 Sterling Silver with premium VVS diamonds. Perfect for making a statement.',
                'gender' => 'men',
                'material' => '925 Sterling Silver',
                'base_price' => 100.00,
                'weight' => 50.00,
                'duty_fee' => 10.00,
                'customs_fee' => 5.00,
                'insurance_fee' => 3.00,
                'shipping_fee' => 15.00,
                'meta_title' => 'Ice-Out Cuban Chain - Premium Iced Jewelry',
                'meta_description' => 'Shop our premium ice-out Cuban chain with VVS diamonds. High-quality 925 Sterling Silver.',
                'status' => true,
                'is_featured' => true
            ]);

            // Attach variants
            $product1->variants()->attach([$color->id, $size->id, $metal->id]);

            // Generate combinations
            $combinationService = new VariantCombinationService();
            $combinations = $combinationService->generateCombinations($product1, [$color->id, $size->id, $metal->id]);

            // Set stock for combinations
            foreach ($combinations as $combination) {
                $combination->update([
                    'stock' => rand(20, 100),
                    'is_available' => true
                ]);
            }

            $product2 = Product::create([
                'name' => 'Ice-Out Tennis Bracelet',
                'sku' => 'PROD000002',
                'slug' => 'ice-out-tennis-bracelet',
                'category_id' => $category->id,
                'short_description' => 'Stunning ice-out tennis bracelet',
                'long_description' => 'Elegant tennis bracelet featuring premium iced-out diamonds in various metal options.',
                'gender' => 'unisex',
                'material' => 'Gold Plated',
                'base_price' => 150.00,
                'weight' => 30.00,
                'duty_fee' => 10.00,
                'customs_fee' => 5.00,
                'insurance_fee' => 3.00,
                'shipping_fee' => 15.00,
                'meta_title' => 'Ice-Out Tennis Bracelet - Premium Jewelry',
                'meta_description' => 'Elegant ice-out tennis bracelet with premium diamonds.',
                'status' => true,
                'is_featured' => true
            ]);

            // Attach variants
            $product2->variants()->attach([$color->id, $diamondQuality->id]);

            // Generate combinations
            $combinations2 = $combinationService->generateCombinations($product2, [$color->id, $diamondQuality->id]);

            // Set stock for combinations
            foreach ($combinations2 as $combination) {
                $combination->update([
                    'stock' => rand(30, 80),
                    'is_available' => true
                ]);
            }
        }

        $this->command->info('Product module seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 4 Variant Types (Color, Size, Metal, Diamond Quality)');
        $this->command->info('- 12 Variant Options');
        $this->command->info('- 2 Sample Products');
        $this->command->info('- ' . count($combinations) + count($combinations2) . ' Product Combinations');
    }
}
