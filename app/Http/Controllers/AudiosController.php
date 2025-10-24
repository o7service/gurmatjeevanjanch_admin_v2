<?php

namespace App\Http\Controllers;

use App\Models\Audios;
use App\Models\Category;
use App\Models\Links;
use App\Models\singerImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AudiosController extends Controller
{
    public function index($id)
    {
        // Fetch the category by ID
        $singer = singerImages::find($id);

        // Fetch only links related to this category
        $audios = Audios::where('singerId', $id)
            ->orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Send both variables to the view
        return view('audios.index', compact('singer', 'audios'));
    }

    public function store(Request $request)
    {
      
        DB::beginTransaction();
        try {
            $totalLinks = Audios::count();
            $newLink = new Audios();
            $newLink->autoId = $totalLinks + 1;
            $newLink->singerId = $request->singerId;
            $newLink->title = $request->title;
            $newLink->audioLink = $request->link;
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
        $link = Audios::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json($link);
    }

    public function updateStatus(Request $request, $id)
    {
        $link = Audios::findOrFail($id);
        if ($link->isBlocked == true) {
            $link->isBlocked = false;
        } else {
            $link->isBlocked = true;
        }
        $link->save();
        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Find existing link
            $link = Audios::findOrFail($id);
            // Update fields
            $link->singerId = $request->singerId;
            $link->title = $request->title;
            $link->audioLink = $request->link;
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
    public function allAudios()
    {
        $Audios = Audios::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($Audios->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active audios found.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Active audios loaded successfully.',
            'data' => $Audios
        ]);
    }

    public function singerAudio(Request $request)
    {
        // Check if ID is provided
        if (!$request->singerId) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'singerId is required.',
            ]);
        }
        $Audios = Audios::where('isDeleted', false)
            ->where('isBlocked', false)
            ->where('singerId', $request->singerId)
            ->orderBy('id', 'desc')
            ->get();

        if ($Audios->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active audios found.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Active audios loaded successfully.',
            'data' => $Audios
        ]);

    }


    public function singleAudio(Request $request)
    {
        // Check if ID is provided
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'ID is required.',
            ]);
        }

        $link = Links::where('id', $request->id)->where('isBlocked', false)->first();
        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Audio not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Audio loaded successfully.',
            'data' => $link
        ]);

    }

}
