<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($categories);
    }
    // Display paginated categories
    public function index()
    {
        $categories = Category::orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('category.index', compact('categories'));
    }

    // Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $newFileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newFileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('categories'), $newFileName);
            }

            $totalCategories = Category::count();

            $newCategory = new Category();
            $newCategory->autoId = $totalCategories + 1;
            $newCategory->title = $request->title;
            $newCategory->icon = $newFileName;
            $newCategory->addedById = Auth::id();
            $newCategory->updatedById = Auth::id();
            $newCategory->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category Added Successfully',
                'data' => $newCategory
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error Occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show a single category
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    // Toggle category status (block/unblock)
    public function updateStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->isBlocked = !$category->isBlocked;
        $category->updatedById = Auth::id();
        $category->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    // Update a category
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            // 'file' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newFileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('categories'), $newFileName);
                $category->icon = $newFileName;
            }

            $category->title = $request->title;
            $category->updatedById = Auth::id();
            $category->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category Updated Successfully',
                'data' => $category
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error Occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // API: Get all active categories
    public function allCategories()
    {
        $categories = Category::where('isBlocked', false)
            ->where('isDeleted', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active categories found.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Active categories loaded successfully.',
            'data' => $categories
        ]);
    }

    // API: Get a single category by ID
    public function singleCategory(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'ID is required.',
            ]);
        }

        $category = Category::find($request->id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Category not found.',
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Category loaded successfully.',
            'data' => $category
        ]);
    }
}
