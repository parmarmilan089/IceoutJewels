<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Services\VariantCombinationService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductCombinationController extends Controller
{
    protected $combinationService;
    
    public function __construct()
    {
        $this->combinationService = new VariantCombinationService();
    }
    
    public function index($productId, Request $request)
    {
        $product = Product::with('variants')->findOrFail($productId);
        
        if ($request->ajax()) {
            $combinations = ProductVariantCombination::where('product_id', $productId)
                ->select('product_variant_combinations.*');
            
            return DataTables::of($combinations)
                ->addIndexColumn()
                ->addColumn('variant_names', function ($combination) {
                    return $combination->getVariantNames();
                })
                ->addColumn('price_display', function ($combination) {
                    return '$' . number_format($combination->price, 2);
                })
                ->addColumn('stock_status', function ($combination) {
                    return $combination->getStockStatusBadge();
                })
                ->addColumn('availability', function ($combination) {
                    $checked = $combination->is_available ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input availability-toggle" 
                                       id="availability_' . $combination->id . '" 
                                       data-id="' . $combination->id . '" ' . $checked . '>
                                <label class="custom-control-label" for="availability_' . $combination->id . '"></label>
                            </div>';
                })
                ->addColumn('action', function ($combination) {
                    return '<button type="button" class="btn btn-sm btn-primary edit-stock-btn" 
                                    data-id="' . $combination->id . '" 
                                    data-stock="' . $combination->stock . '" 
                                    data-price="' . $combination->price . '"
                                    data-sku="' . $combination->sku . '">
                                <i class="feather feather-edit"></i> Edit
                            </button>';
                })
                ->rawColumns(['stock_status', 'availability', 'action'])
                ->make(true);
        }
        
        return view('admin.products.combinations', compact('product'));
    }
    
    public function generate(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);
            
            $variantTypeIds = $request->variant_types ?? $product->variants->pluck('id')->toArray();
            
            if (empty($variantTypeIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one variant type'
                ], 400);
            }
            
            $combinations = $this->combinationService->generateCombinations($product, $variantTypeIds);
            
            return response()->json([
                'success' => true,
                'message' => count($combinations) . ' combinations generated successfully!',
                'count' => count($combinations)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate combinations: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateStock(Request $request, $id)
    {
        try {
            $combination = ProductVariantCombination::findOrFail($id);
            
            $combination->stock = $request->stock;
            $combination->price = $request->price;
            $combination->is_available = $request->stock > 0;
            $combination->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function toggleAvailability(Request $request)
    {
        try {
            $combination = ProductVariantCombination::findOrFail($request->id);
            $combination->is_available = !$combination->is_available;
            $combination->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Availability updated successfully!',
                'is_available' => $combination->is_available
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update availability: ' . $e->getMessage()
            ], 500);
        }
    }
}
