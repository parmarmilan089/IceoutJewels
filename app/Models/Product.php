<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'slug',
        'category_id',
        'sub_category_id',
        'short_description',
        'long_description',
        'gender',
        'material',
        'base_price',
        'weight',
        'featured_image',
        'video_url',
        'duty_fee',
        'customs_fee',
        'insurance_fee',
        'shipping_fee',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'duty_fee' => 'decimal:2',
        'customs_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_product');
    }

    public function combinations()
    {
        return $this->hasMany(ProductVariantCombination::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('image_type', 'gallery');
    }

    // Accessors
    public function getFeaturedImageAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByMaterial($query, $material)
    {
        return $query->where('material', $material);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('base_price', [$min, $max]);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name', 'LIKE', '%' . $term . '%')
                  ->orWhere('sku', 'LIKE', '%' . $term . '%')
                  ->orWhere('short_description', 'LIKE', '%' . $term . '%');
        });
    }

    // Boot method for auto-generating SKU and slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'PROD' . str_pad(Product::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->meta_title)) {
                $product->meta_title = $product->name;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Helper methods
    public function getTotalFees()
    {
        return $this->duty_fee + $this->customs_fee + $this->insurance_fee + $this->shipping_fee;
    }

    public function getMinPrice()
    {
        $minCombinationPrice = $this->combinations()->min('price');
        return $minCombinationPrice ?? $this->base_price;
    }

    public function getMaxPrice()
    {
        $maxCombinationPrice = $this->combinations()->max('price');
        return $maxCombinationPrice ?? $this->base_price;
    }

    public function getTotalStock()
    {
        return $this->combinations()->sum('stock');
    }

    public function isInStock()
    {
        return $this->getTotalStock() > 0;
    }
}
