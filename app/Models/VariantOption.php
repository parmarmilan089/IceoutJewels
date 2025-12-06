<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'option_name',
        'option_value',
        'additional_price',
        'sku_code',
        'image',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Accessors
    public function getImageAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    public function scopeByVariant($query, $variantId)
    {
        return $query->where('product_variant_id', $variantId);
    }

    // Helper methods
    public function getDisplayName()
    {
        $name = $this->option_name;
        if ($this->additional_price > 0) {
            $name .= ' (+$' . number_format($this->additional_price, 2) . ')';
        }
        return $name;
    }
}
