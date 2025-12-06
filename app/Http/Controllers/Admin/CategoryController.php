<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::with('parent')->select('categories.*');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('parent_name', function ($category) {
                    return $category->parent ? $category->parent->name : '-';
                })
                ->addColumn('status', function ($category) {
                    $checked = $category->status ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input status-toggle" 
                                       id="status_' . $category->id . '" 
                                       data-id="' . $category->id . '" ' . $checked . '>
                                <label class="custom-control-label" for="status_' . $category->id . '"></label>
                            </div>';
                })
                ->addColumn('image', function ($category) {
                    if ($category->image) {
                        return '<img src="' . $category->image . '" alt="' . $category->name . '" width="50" height="50" class="rounded">';
                    }
                    return '<span class="badge badge-secondary">No Image</span>';
                })
                ->addColumn('action', function ($category) {
                    $editUrl = route('admin.categories.edit', $category->id);
                    $deleteUrl = route('admin.categories.destroy', $category->id);
                    
                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="feather feather-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                        data-id="' . $category->id . '" 
                                        data-url="' . $deleteUrl . '" title="Delete">
                                    <i class="feather feather-trash-2"></i>
                                </button>
                            </div>';
                })
                ->rawColumns(['status', 'image', 'action'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->active()->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);

            // Handle image upload to public folder
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/categories');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Move file to public folder
                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/categories/' . $imageName;
            }

            Category::create($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('parent', 'children')->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->active()
            ->get();
        
        return view('admin.categories.create', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);

            // Handle image upload to public folder
            if ($request->hasFile('image')) {
                // Delete old image from public folder
                if ($category->getRawOriginal('image')) {
                    $oldImagePath = public_path($category->getRawOriginal('image'));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/categories');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Move file to public folder
                $image->move($uploadPath, $imageName);
                $data['image'] = 'uploads/categories/' . $imageName;
            }

            $category->update($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Delete image from public folder if exists
            if ($category->getRawOriginal('image')) {
                $imagePath = public_path($category->getRawOriginal('image'));
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            $category->status = !$category->status;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $category->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}