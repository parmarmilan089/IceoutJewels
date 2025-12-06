<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VariantOption;
use App\Models\ProductVariant;
use App\Services\SKUGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VariantOptionController extends Controller
{
    protected $skuGenerator;
    
    public function __construct()
    {
        $this->skuGenerator = new SKUGeneratorService();
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $options = VariantOption::with('variant')->select('variant_options.*');
            
            return DataTables::of($options)
                ->addIndexColumn()
                ->addColumn('variant_name', function ($option) {
                    return $option->variant ? $option->variant->name : '-';
                })
                ->addColumn('price_display', function ($option) {
                    if ($option->additional_price > 0) {
                        return '+$' . number_format($option->additional_price, 2);
                    }
                    return '$0.00';
                })
                ->addColumn('status', function ($option) {
                    $checked = $option->is_active ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input status-toggle" 
                                       id="status_' . $option->id . '" 
                                       data-id="' . $option->id . '" ' . $checked . '>
                                <label class="custom-control-label" for="status_' . $option->id . '"></label>
                            </div>';
                })
                ->addColumn('action', function ($option) {
                    $editUrl = route('admin.variant-options.edit', $option->id);
                    $deleteUrl = route('admin.variant-options.destroy', $option->id);
                    
                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="feather feather-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                        data-id="' . $option->id . '" 
                                        data-url="' . $deleteUrl . '" title="Delete">
                                    <i class="feather feather-trash-2"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('admin.variant-options.index');
    }
    
    public function create()
    {
        $variants = ProductVariant::where('is_active', 1)->orderBy('display_order')->get();
        return view('admin.variant-options.create', compact('variants'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'option_name' => 'required|string|max:255',
            'sku_code' => 'nullable|string|max:10|unique:variant_options,sku_code',
            'additional_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_order' => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $data = $request->all();
            
            // Generate SKU code if not provided
            if (empty($data['sku_code'])) {
                $data['sku_code'] = $this->skuGenerator->generateOptionSKUCode($request->option_name);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $data['sku_code'] . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/variants');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/variants/' . $imageName;
            }
            
            VariantOption::create($data);
            
            return redirect()->route('admin.variant-options.index')
                ->with('success', 'Variant option created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create variant option: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $option = VariantOption::findOrFail($id);
        $variants = ProductVariant::where('is_active', 1)->orderBy('display_order')->get();
        return view('admin.variant-options.create', compact('option', 'variants'));
    }
    
    public function update(Request $request, $id)
    {
        $option = VariantOption::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'option_name' => 'required|string|max:255',
            'sku_code' => 'nullable|string|max:10|unique:variant_options,sku_code,' . $id,
            'additional_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_order' => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($option->getRawOriginal('image')) {
                    $oldImagePath = public_path($option->getRawOriginal('image'));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $data['sku_code'] . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/variants');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/variants/' . $imageName;
            }
            
            $option->update($data);
            
            return redirect()->route('admin.variant-options.index')
                ->with('success', 'Variant option updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update variant option: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $option = VariantOption::findOrFail($id);
            
            // Delete image
            if ($option->getRawOriginal('image')) {
                $imagePath = public_path($option->getRawOriginal('image'));
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $option->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Variant option deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete variant option: ' . $e->getMessage()
            ], 500);
        }
    }
}
