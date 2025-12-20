<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\VariantOption;
use App\Services\SKUGeneratorService;
use App\Services\VariantCombinationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $skuGenerator;
    protected $combinationService;
    
    public function __construct()
    {
        $this->skuGenerator = new SKUGeneratorService();
        $this->combinationService = new VariantCombinationService();
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category', 'combinations'])
                ->select('products.*');
            
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('category_name', function ($product) {
                    return $product->category ? $product->category->name : '-';
                })
                ->addColumn('price_range', function ($product) {
                    $min = $product->getMinPrice();
                    $max = $product->getMaxPrice();
                    if ($min == $max) {
                        return '$' . number_format($min, 2);
                    }
                    return '$' . number_format($min, 2) . ' - $' . number_format($max, 2);
                })
                ->addColumn('stock', function ($product) {
                    $total = $product->getTotalStock();
                    if ($total > 0) {
                        return '<span class="badge badge-success">' . $total . ' units</span>';
                    }
                    return '<span class="badge badge-danger">Out of Stock</span>';
                })
                ->addColumn('status', function ($product) {
                    $checked = $product->status ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input status-toggle" 
                                       id="status_' . $product->id . '" 
                                       data-id="' . $product->id . '" ' . $checked . '>
                                <label class="custom-control-label" for="status_' . $product->id . '"></label>
                            </div>';
                })
                ->addColumn('image', function ($product) {
                    if ($product->getRawOriginal('featured_image')) {
                        return '<img src="' . $product->featured_image . '" width="50" height="50" class="rounded">';
                    }
                    return '<span class="badge badge-secondary">No Image</span>';
                })
                ->addColumn('action', function ($product) {
                    $editUrl = route('admin.products.edit', $product->id);
                    $deleteUrl = route('admin.products.destroy', $product->id);
                    $combinationsUrl = route('admin.products.combinations', $product->id);
                    
                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="feather feather-edit"></i>
                                </a>
                                <a href="' . $combinationsUrl . '" class="btn btn-sm btn-info" title="Combinations">
                                    <i class="feather feather-layers"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                        data-id="' . $product->id . '" 
                                        data-url="' . $deleteUrl . '" title="Delete">
                                    <i class="feather feather-trash-2"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['status', 'image', 'stock', 'action'])
                ->make(true);
        }
        
        return view('admin.products.index');
    }
    
    public function create()
    {
        $categories = Category::whereNull('parent_id')->where('status', 1)->get();
        // Load variants with their options for selection
        $variants = ProductVariant::with(['options' => function($query) {
            $query->active()->orderBy('display_order');
        }])->where('is_active', 1)->orderBy('display_order')->get();
        $sku = $this->skuGenerator->generateProductSKU();
        
        return view('admin.products.create', compact('categories', 'variants', 'sku'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'gender' => 'required|in:men,women,unisex',
            'material' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $data = $request->all();
            
            // Generate SKU if not provided
            if (empty($data['sku'])) {
                $data['sku'] = $this->skuGenerator->generateProductSKU();
            }
            
            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $imageName = time() . '_' . $data['sku'] . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/products');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['featured_image'] = 'uploads/products/' . $imageName;
            }
            
            $product = Product::create($data);
            
            // Attach selected variants
            if ($request->has('variant_types')) {
                $product->variants()->sync($request->variant_types);
                $this->syncVariantsAndOptions($product, $request);
            }
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create product: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $product = Product::with(['variants', 'combinations'])->findOrFail($id);
        $categories = Category::whereNull('parent_id')->where('status', 1)->get();
        // Load variants with their options for selection
        $variants = ProductVariant::with(['options' => function($query) {
            $query->active()->orderBy('display_order');
        }])->where('is_active', 1)->orderBy('display_order')->get();
        
        // Extract selected options from combinations
        $selectedOptions = [];
        if ($product->combinations) {
            foreach ($product->combinations as $combination) {
                foreach ($combination->getVariantOptions() as $option) {
                    if (!isset($selectedOptions[$option->product_variant_id])) {
                        $selectedOptions[$option->product_variant_id] = collect();
                    }
                    if (!$selectedOptions[$option->product_variant_id]->contains('id', $option->id)) {
                        $selectedOptions[$option->product_variant_id]->push($option);
                    }
                }
            }
        }
        
        return view('admin.products.create', compact('product', 'categories', 'variants', 'selectedOptions'));
    }
    
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|unique:products,sku,' . $id,
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($product->getRawOriginal('featured_image')) {
                    $oldImagePath = public_path($product->getRawOriginal('featured_image'));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $image = $request->file('featured_image');
                $imageName = time() . '_' . $data['sku'] . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/products');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['featured_image'] = 'uploads/products/' . $imageName;
            }
            
            $product->update($data);
            
            // Sync variants
            if ($request->has('variant_types')) {
                $product->variants()->sync($request->variant_types);
                $this->syncVariantsAndOptions($product, $request);
            }
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete featured image
            if ($product->getRawOriginal('featured_image')) {
                $imagePath = public_path($product->getRawOriginal('featured_image'));
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function toggleStatus(Request $request)
    {
        try {
            $product = Product::findOrFail($request->id);
            $product->status = !$product->status;
            $product->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $product->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    private function syncVariantsAndOptions(Product $product, Request $request)
    {
        $variantOptionsInput = $request->input('variant_options', []);
        $variantTypes = $request->input('variant_types', []);
        
        if (empty($variantTypes)) {
            return;
        }

        $optionsBySlug = [];

        foreach ($variantTypes as $variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant) continue;

            $inputData = $variantOptionsInput[$variantId] ?? null;
            
            if ($inputData && isset($inputData['names'])) {
                $names = $inputData['names'];
                $prices = $inputData['prices'] ?? [];
                
                $optionIds = [];
                
                foreach ($names as $index => $name) {
                    if (empty($name)) continue;
                    
                    $price = $prices[$index] ?? 0;
                    
                    // Create or Update GLOBAL Option
                    $option = VariantOption::firstOrNew([
                        'product_variant_id' => $variantId,
                        'option_name' => $name
                    ]);
                    
                    $option->additional_price = $price;
                    $option->is_active = true; // Ensure active
                    if (!$option->exists) {
                         // Default SKU Code
                         $option->sku_code = strtoupper(substr($name, 0, 3)) . rand(10, 99); 
                    }
                    $option->save();
                    
                    $optionIds[] = $option->id;
                }
                
                if (!empty($optionIds)) {
                    $optionsBySlug[$variant->slug] = $optionIds;
                }
            }
        }
        
        if (!empty($optionsBySlug)) {
            $this->combinationService->generateCombinationsFromSpecificOptions($product, $optionsBySlug);
        }
    }
}