<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\youtubeLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YoutubeLinksController extends Controller
{
    public function index()
    {
        $youtubeLinks = YoutubeLinks::orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('youtube.index', compact('youtubeLinks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);
        DB::beginTransaction();
        try {
            $totalLinks = YoutubeLinks::count();
            $newLink = new YoutubeLinks();
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
        $link = YoutubeLinks::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json($link);
    }

    public function updateStatus(Request $request, $id)
    {
        $link = youtubeLinks::findOrFail($id);
        if ($link->isBlocked == true) {
            $link->isBlocked = false;
        } else {
            $link->isBlocked = true;
        }
        $link->save();
        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function setLive(Request $request, $id)
    {
        $link = youtubeLinks::findOrFail($id);

        if (!$link->isLive) {
            youtubeLinks::where('id', '!=', $id)->update(['isLive' => false]);
            $link->isLive = true;
        } else {
            $link->isLive = false;
        }
        $link->save();
        return redirect()->back()->with('success', 'Live status updated!');
    }


    // public function deleteLink(Request $request, $id)
    // {
    //     $link = youtubeLinks::findOrFail($id);
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
            $link = YoutubeLinks::findOrFail($id);
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
        $youtubeLinks = youtubeLinks::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($youtubeLinks->isEmpty()) {
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
            'data' => $youtubeLinks
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

        $link = youtubeLinks::where('id', $request->id)->first();
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
