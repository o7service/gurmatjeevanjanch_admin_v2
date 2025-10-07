<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $category = category::orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('category.index', compact('category'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $newFileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newFileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('categorys'), $newFileName);
            }
            $totalLinks = category::count();
            $newcategory = new category();
            $newcategory->autoId = $totalLinks + 1;
            $newcategory->name = $request->name;
            $newcategory->imageUrl = $newFileName;
            $newcategory->updatedById = Auth::id();
            $newcategory->addedById = Auth::id();
            $newcategory->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'category Added Successfully',
                'data' => $newcategory
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error Occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        $link = category::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json($link);
    }

    public function updateStatus(Request $request, $id)
    {
        $link = category::findOrFail($id);
        if ($link->isBlocked == true) {
            $link->isBlocked = false;
        } else {
            $link->isBlocked = true;
        }
        $link->save();
        return redirect()->back()->with('success', 'Status updated successfully!');
    }


    // public function deleteLink(Request $request, $id)
    // {
    //     $link = category::findOrFail($id);
    //     if ($link->isDeleted == true) {
    //         $link->isDeleted = false;
    //     } else {
    //         $link->isDeleted = true;
    //     }
    //     $link->save();
    //     return redirect()->back()->with('success', 'Link Deleted successfully!');
    // }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Find existing link
            $link = category::findOrFail($id);
            // Update fields
            $link->title = $request->title;
            $link->link = $request->link;
            $link->updatedById = Auth::id();
            $link->save();
            DB::commit();
            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Link Updated Successfully',
                'data' => $link
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return (response()->json([
                'success' => false,
                'message' => 'Error Occurred: ' . $e->getMessage()
            ], 500));
        }
    }
    //APIS
    public function allLinks()
    {
        $category = category::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($category->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active links found.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Active links loaded successfully.',
            'data' => $category
        ]);
    }


    public function singleLink(Request $request)
    {
        // Check if ID is provided
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'ID is required.',
            ]);
        }

        $link = category::where('id', $request->id)->first();
        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Link not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Link loaded successfully.',
            'data' => $link
        ]);

    }



}
