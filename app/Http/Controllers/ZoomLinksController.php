<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\zoomLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoomLinksController extends Controller
{
    public function index()
    {
        $zoomLinks = zoomLinks::orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('zoom.index', compact('zoomLinks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);
        DB::beginTransaction();
        try {
            $totalLinks = zoomLinks::count();
            $newLink = new zoomLinks();
            $newLink->autoId = $totalLinks + 1;
            $newLink->title = $request->title;
            $newLink->link = $request->link;
            $newLink->updatedById = Auth::id();
            $newLink->addedById = Auth::id();
            $newLink->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Link Added Successfully',
                'data' => $newLink
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
        $link = zoomLinks::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json($link);
    }

    public function updateStatus(Request $request, $id)
    {
        $link = zoomLinks::findOrFail($id);
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
    //     $link = zoomLinks::findOrFail($id);
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
            $link = ZoomLinks::findOrFail($id);
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
        $zoomLinks = zoomLinks::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($zoomLinks->isEmpty()) {
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
            'data' => $zoomLinks
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

        $link = zoomLinks::where('id', $request->id)->first();
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
