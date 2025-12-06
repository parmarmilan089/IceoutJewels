<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\VariantOption;

class VariantCombinationService
{
    /**
     * Generate all possible combinations for a product
     */
    public function generateCombinations(Product $product, array $variantTypeIds)
    {
        // Get all variant options for selected variant types
        $variantOptions = [];
        
        foreach ($variantTypeIds as $variantTypeId) {
            $options = VariantOption::where('product_variant_id', $variantTypeId)
                ->active()
                ->get();
            
            if ($options->isEmpty()) {
                continue;
            }
            
            $variant = $options->first()->variant;
            $variantOptions[$variant->slug] = $options->pluck('id')->toArray();
        }
        
        if (empty($variantOptions)) {
            return [];
        }
        
        // Generate cartesian product
        $combinations = $this->cartesianProduct($variantOptions);
        
        // Create combination records
        $createdCombinations = [];
        
        foreach ($combinations as $combination) {
            $combinationData = $this->prepareCombinationData($product, $combination);
            
            // Check if combination already exists
            $existing = ProductVariantCombination::where('product_id', $product->id)
                ->where('sku', $combinationData['sku'])
                ->first();
            
            if (!$existing) {
                $createdCombinations[] = ProductVariantCombination::create($combinationData);
            }
        }
        
        return $createdCombinations;
    }
    
    /**
     * Generate cartesian product of variant options
     */
    private function cartesianProduct(array $arrays)
    {
        $result = [[]];
        
        foreach ($arrays as $key => $values) {
            $append = [];
            
            foreach ($result as $product) {
                foreach ($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }
            
            $result = $append;
        }
        
        return $result;
    }
    
    /**
     * Prepare combination data
     */
    private function prepareCombinationData(Product $product, array $combination)
    {
        $skuGenerator = new SKUGeneratorService();
        $priceCalculator = new PriceCalculator();
        
        // Generate SKU
        $sku = $skuGenerator->generateCombinationSKU($product, $combination);
        
        // Calculate price
        $price = $priceCalculator->calculateCombinationPrice($product->base_price, $combination);
        
        return [
            'product_id' => $product->id,
            'combination_string' => $combination,
            'sku' => $sku,
            'price' => $price,
            'stock' => 0,
            'low_stock_alert' => 5,
            'is_available' => false,
        ];
    }
    
    /**
     * Update combination stock
     */
    public function updateCombinationStock($combinationId, $quantity, $operation = 'set')
    {
        $combination = ProductVariantCombination::findOrFail($combinationId);
        
        switch ($operation) {
            case 'add':
                $combination->incrementStock($quantity);
                break;
            case 'subtract':
                $combination->decrementStock($quantity);
                break;
            default:
                $combination->stock = $quantity;
                $combination->is_available = $quantity > 0;
                $combination->save();
        }
        
        return $combination;
    }
    
    /**
     * Find combination by variant selections
     */
    public function findCombination(Product $product, array $selectedOptions)
    {
        return ProductVariantCombination::where('product_id', $product->id)
            ->where('combination_string', json_encode($selectedOptions))
            ->first();
    }
    
    /**
     * Get available combinations for a product
     */
    public function getAvailableCombinations(Product $product)
    {
        return ProductVariantCombination::where('product_id', $product->id)
            ->available()
            ->get();
    }
    
    /**
     * Delete all combinations for a product
     */
    public function deleteCombinations(Product $product)
    {
        return ProductVariantCombination::where('product_id', $product->id)->delete();
    }
    
    /**
     * Regenerate combinations for a product
     */
    public function regenerateCombinations(Product $product, array $variantTypeIds)
    {
        // Delete existing combinations
        $this->deleteCombinations($product);
        
        // Generate new combinations
        return $this->generateCombinations($product, $variantTypeIds);
    }
}
