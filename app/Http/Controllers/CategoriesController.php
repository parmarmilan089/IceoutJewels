<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesController extends Controller
{
    //
    public function index(){
        // Fetch all categories return into json format
        return response()->json(['Categories' => Categories::all(),'total' => Categories::count(),'status' => 1]);
    }

    //store the categories to database
    public function store(Request $request){
        // Validate the incoming request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sort' => ['required'],
        ]);

        // Create a new category
        $category = Categories::create($request->all());

        // Return the created category as json response
        return response()->json($category, 201);
    }
    // Display the specified category.
    public function show($id)
    {
        $category = Categories::findOrFail($id);
        return response()->json($category);
    }

    // Update the specified category in storage.
    public function update(Request $request, $id)
    {
        $category = Categories::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => ['required', 'string'],
            'sort' => ['required'],
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort' => $request->sort,
            'parent_id' => $request->parent_id,
            'status' => $request->status,
        ]);

        return response()->json($category);
    }

    // Remove the specified category from storage.
    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();

        return response()->json(['status' => 1, 'Message' => 'Category deleted successfully']);
    }
}
