<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\VariantOption;

class PriceCalculator
{
    /**
     * Calculate combination price
     */
    public function calculateCombinationPrice($basePrice, array $variantOptions)
    {
        $totalPrice = $basePrice;
        
        foreach ($variantOptions as $variantSlug => $optionId) {
            $option = VariantOption::find($optionId);
            if ($option) {
                $totalPrice += $option->additional_price;
            }
        }
        
        return round($totalPrice, 2);
    }
    
    /**
     * Calculate checkout total
     */
    public function calculateCheckoutTotal(
        Product $product,
        ProductVariantCombination $combination,
        $quantity = 1,
        $taxRate = 0.10
    ) {
        // Subtotal
        $subtotal = $combination->price * $quantity;
        
        // Fees
        $duty = $product->duty_fee;
        $customs = $product->customs_fee;
        $insurance = $product->insurance_fee;
        $shipping = $product->shipping_fee;
        
        // Tax
        $tax = $subtotal * $taxRate;
        
        // Total
        $total = $subtotal + $duty + $customs + $insurance + $shipping + $tax;
        
        return [
            'subtotal' => round($subtotal, 2),
            'duty_fee' => round($duty, 2),
            'customs_fee' => round($customs, 2),
            'insurance_fee' => round($insurance, 2),
            'shipping_fee' => round($shipping, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
        ];
    }
    
    /**
     * Calculate price with discount
     */
    public function calculateDiscountedPrice($price, $discountPercent)
    {
        $discount = ($price * $discountPercent) / 100;
        return round($price - $discount, 2);
    }
    
    /**
     * Calculate bulk discount
     */
    public function calculateBulkPrice($price, $quantity, array $bulkRules = [])
    {
        // Default bulk rules: [quantity => discount_percent]
        // Example: [10 => 5, 50 => 10, 100 => 15]
        
        if (empty($bulkRules)) {
            return $price * $quantity;
        }
        
        $applicableDiscount = 0;
        
        foreach ($bulkRules as $minQty => $discount) {
            if ($quantity >= $minQty) {
                $applicableDiscount = $discount;
            }
        }
        
        if ($applicableDiscount > 0) {
            $discountedPrice = $this->calculateDiscountedPrice($price, $applicableDiscount);
            return round($discountedPrice * $quantity, 2);
        }
        
        return round($price * $quantity, 2);
    }
    
    /**
     * Format price for display
     */
    public function formatPrice($price, $currency = '$')
    {
        return $currency . number_format($price, 2);
    }
    
    /**
     * Calculate savings
     */
    public function calculateSavings($originalPrice, $salePrice)
    {
        $savings = $originalPrice - $salePrice;
        $savingsPercent = ($savings / $originalPrice) * 100;
        
        return [
            'amount' => round($savings, 2),
            'percent' => round($savingsPercent, 2),
        ];
    }
}
