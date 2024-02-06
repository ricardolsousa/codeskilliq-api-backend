<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', 10); // Page size inserted by user or 10

        $categories = Category::paginate($pageSize);

        return response()->json([
            'data' => $categories->items(), // Current Data
            'meta' => [ // Meta data for current page, amount of data and other stuff
                'current_page' => $categories->currentPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
                'page_size' => $categories->perPage(),
                'total' => $categories->total(),
                'last_page' => $categories->lastPage(),
            ],
            'links' => [ // Links to next and previous pages
                'next_page' => $categories->nextPageUrl(),
                'prev_page' => $categories->previousPageUrl(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', Rule::unique('categories', 'name')->ignore($category->id)],
        ]);

        $category->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category removed with success']);
    }
}
