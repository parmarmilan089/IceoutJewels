<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variant_product');
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

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->slug)) {
                $variant->slug = Str::slug($variant->name);
            }
        });

        static::updating(function ($variant) {
            if ($variant->isDirty('name') && empty($variant->slug)) {
                $variant->slug = Str::slug($variant->name);
            }
        });
    }
}
