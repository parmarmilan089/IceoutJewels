<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $variants = ProductVariant::withCount('options')->select('product_variants.*');
            
            return DataTables::of($variants)
                ->addIndexColumn()
                ->addColumn('options_count', function ($variant) {
                    return $variant->options_count . ' options';
                })
                ->addColumn('status', function ($variant) {
                    $checked = $variant->is_active ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input status-toggle" 
                                       id="status_' . $variant->id . '" 
                                       data-id="' . $variant->id . '" ' . $checked . '>
                                <label class="custom-control-label" for="status_' . $variant->id . '"></label>
                            </div>';
                })
                ->addColumn('action', function ($variant) {
                    $editUrl = route('admin.product-variants.edit', $variant->id);
                    $deleteUrl = route('admin.product-variants.destroy', $variant->id);
                    
                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="feather feather-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                        data-id="' . $variant->id . '" 
                                        data-url="' . $deleteUrl . '" title="Delete">
                                    <i class="feather feather-trash-2"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('admin.product-variants.index');
    }
    
    public function create()
    {
        return view('admin.product-variants.create');
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_variants,name',
            'display_order' => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            ProductVariant::create($request->all());
            
            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variant type created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create variant type: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $variant = ProductVariant::findOrFail($id);
        return view('admin.product-variants.create', compact('variant'));
    }
    
    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_variants,name,' . $id,
            'display_order' => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            $variant->update($request->all());
            
            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variant type updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update variant type: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            $variant->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Variant type deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete variant type: ' . $e->getMessage()
            ], 500);
        }
    }
}
