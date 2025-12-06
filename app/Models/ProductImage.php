<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'image_type',
        'variant_option_id',
        'display_order',
        'is_primary',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantOption()
    {
        return $this->belongsTo(VariantOption::class);
    }

    // Accessors
    public function getImagePathAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return null;
    }

    // Scopes
    public function scopeGallery($query)
    {
        return $query->where('image_type', 'gallery');
    }

    public function scopeVariant($query)
    {
        return $query->where('image_type', 'variant');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
}
