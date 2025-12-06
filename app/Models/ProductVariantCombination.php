<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantCombination extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'combination_string',
        'sku',
        'price',
        'stock',
        'low_stock_alert',
        'image',
        'is_available',
    ];

    protected $casts = [
        'combination_string' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'low_stock_alert' => 'integer',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getImageAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return $this->product->featured_image ?? null;
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'low_stock_alert');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', 0);
    }

    // Helper methods
    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function isLowStock()
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_alert;
    }

    public function isOutOfStock()
    {
        return $this->stock == 0;
    }

    public function getVariantOptions()
    {
        $options = [];
        foreach ($this->combination_string as $variantSlug => $optionId) {
            $option = VariantOption::find($optionId);
            if ($option) {
                $options[$variantSlug] = $option;
            }
        }
        return $options;
    }

    public function getVariantNames()
    {
        $names = [];
        foreach ($this->getVariantOptions() as $slug => $option) {
            $names[] = $option->option_name;
        }
        return implode(' / ', $names);
    }

    public function decrementStock($quantity = 1)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            
            if ($this->stock == 0) {
                $this->update(['is_available' => false]);
            }
            
            return true;
        }
        return false;
    }

    public function incrementStock($quantity = 1)
    {
        $this->increment('stock', $quantity);
        
        if ($this->stock > 0 && !$this->is_available) {
            $this->update(['is_available' => true]);
        }
        
        return true;
    }

    public function getStockStatus()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusBadge()
    {
        $status = $this->getStockStatus();
        
        switch ($status) {
            case 'out_of_stock':
                return '<span class="badge badge-danger">Out of Stock</span>';
            case 'low_stock':
                return '<span class="badge badge-warning">Low Stock (' . $this->stock . ')</span>';
            default:
                return '<span class="badge badge-success">In Stock (' . $this->stock . ')</span>';
        }
    }
}
