<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class LandingPageController extends Controller
{
    public function index()
    {
        // 1. Categories with product count
        $categories = Category::withCount('products')
            ->active()
            ->get();

        // 2. Latest 10 Products
        // Loading relationships that might be needed for display (like price, images)
        $latestProducts = Product::with(['category', 'images'])
            ->active()
            ->latest()
            ->take(10)
            ->get();

        // 3. Sale section
        // For now, we'll select products that might be considered "on sale".
        // Since we don't have a specific `is_sale` flag, we will return a subset.
        // In a real scenario, this might filter by `sale_price < price`.
        $saleProducts = Product::with(['category', 'images'])
            ->active()
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 4. Trending Products with list of 10 products
        $trendingProducts = Product::with(['category', 'images'])
            ->active()
            ->inRandomOrder()
            ->take(10)
            ->get();

        // 5. Product Details : Landing page featured product
        $featuredProduct = Product::with(['category', 'images', 'variants', 'combinations'])
            ->active()
            ->featured()
            ->first();

        // Fallback if no featured product is explicitly set
        if (!$featuredProduct) {
            $featuredProduct = Product::with(['category', 'images', 'variants', 'combinations'])
                ->active()
                ->latest()
                ->first();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'latest_products' => $latestProducts,
                'sale_products' => $saleProducts,
                'trending_products' => $trendingProducts,
                'featured_product' => $featuredProduct,
            ]
        ]);
    }
}
