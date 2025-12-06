<?php

namespace App\Services;

use App\Models\Product;
use App\Models\VariantOption;
use Illuminate\Support\Str;

class SKUGeneratorService
{
    /**
     * Generate unique SKU for a product
     */
    public function generateProductSKU($productName = null)
    {
        $prefix = 'PROD';
        $nextId = Product::withTrashed()->max('id') + 1;
        $sku = $prefix . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness
        while (Product::withTrashed()->where('sku', $sku)->exists()) {
            $nextId++;
            $sku = $prefix . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }
        
        return $sku;
    }
    
    /**
     * Generate SKU for a variant combination
     */
    public function generateCombinationSKU(Product $product, array $combination)
    {
        $sku = $product->sku;
        
        foreach ($combination as $variantSlug => $optionId) {
            $option = VariantOption::find($optionId);
            if ($option && $option->sku_code) {
                $sku .= '-' . strtoupper($option->sku_code);
            }
        }
        
        return $sku;
    }
    
    /**
     * Generate SKU code from option name
     */
    public function generateOptionSKUCode($optionName)
    {
        // Remove special characters and get first 3-4 letters
        $cleaned = preg_replace('/[^A-Za-z0-9]/', '', $optionName);
        $code = strtoupper(substr($cleaned, 0, 3));
        
        // Ensure uniqueness
        $counter = 1;
        $originalCode = $code;
        
        while (VariantOption::where('sku_code', $code)->exists()) {
            $code = $originalCode . $counter;
            $counter++;
        }
        
        return $code;
    }
    
    /**
     * Validate SKU format
     */
    public function validateSKU($sku)
    {
        // SKU should be alphanumeric with hyphens
        return preg_match('/^[A-Z0-9\-]+$/', $sku);
    }
    
    /**
     * Check if SKU is unique
     */
    public function isUniqueSKU($sku, $excludeId = null)
    {
        $query = Product::where('sku', $sku);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }
}
