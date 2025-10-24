<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Links;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LinksController extends Controller
{
    public function index($id)
    {
        $categoryName = ['Youtube'];
        $categories = Category::whereIn('title', $categoryName)->pluck('id', 'title');
        $category = Category::find($id);
        $isYoutube = $categories->contains($category->id);
        $links = Links::where('categoryId', $id)
            ->orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('links.index', compact('category', 'links', 'isYoutube'));
    }
    public function store(Request $request)
{
    $request->validate([
        'categoryId' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'link' => 'required|url|max:255',
    ]);

    DB::beginTransaction();
    try {
        $totalLinks = Links::count();
        $newLink = new Links();
        $newLink->autoId = $totalLinks + 1;
        $newLink->categoryId = $request->categoryId;
        $newLink->title = $request->title;
        $newLink->link = $request->link;
        $newLink->updatedById = Auth::id();
        $newLink->addedById = Auth::id();
        $newLink->save();
        DB::commit();

        return response()->json([
                'success' => true,
                'message' => 'Category Added Successfully',
                'data' => $newLink
            ], 200);

    } catch (\Exception $e) {
        DB::rollback();
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding link!'
            ], 500);
        }
        return redirect()->back()->with('error', 'Error adding link!');
    }
}


    public function show($id)
    {
        $link = Links::find($id);
        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }
        return response()->json($link);
    }

    public function updateStatus(Request $request, $id)
    {
        $link = Links::findOrFail($id);
        if ($link->isBlocked == true) {
            $link->isBlocked = false;
        } else {
            $link->isBlocked = true;
        }
        $link->save();
        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function updateLive(Request $request, $id)
{
    $link = Links::findOrFail($id);
    if (!$link->isLive) {
        Links::where('id', '!=', $link->id)->update(['isLive' => false]);
        $link->isLive = true;
    } else {
        $link->isLive = false;
    }
    $link->save();
    return redirect()->back()->with('success', 'Live status updated successfully!');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        DB::beginTransaction();
        try {
            $link = Links::findOrFail($id);
            $link->categoryId = $request->categoryId;
            $link->title = $request->title;
            $link->link = $request->link;
            $link->updatedById = Auth::id();
            $link->save();
            DB::commit();
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
        $Links = Links::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($Links->isEmpty()) {
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
            'total' => $Links->count(),
            'message' => 'Active links loaded successfully.',
            'data' => $Links
        ]);
    }

    public function categoryLink(Request $request)
    {
        $Links = Links::where('isDeleted', false)
            ->where('isBlocked', false)
            ->where('categoryId', $request->categoryId)
            ->orderBy('id', 'desc')
            ->get();

        if ($Links->isEmpty()) {
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
            'total' => $Links->count(),
            'message' => 'Active links loaded successfully.',
            'data' => $Links
        ]);
    }

    public function singleLink(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'ID is required.',
            ]);
        }
        $link = Links::where('id', $request->id)->first();
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