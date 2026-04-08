<?php

namespace App\Http\Controllers;

use App\Models\Audios;
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
    public function allAudios(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        $startPoint = $request->startPoint ?? 0;
        $limit = $request->limit ?? 5;

        $baseQuery = Audios::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc');

        $total = $baseQuery->count();

        $Audios = $baseQuery
            ->skip($startPoint)
            ->take($limit)
            ->get();

        if ($Audios->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active audios found.',
                'data' => []
            ]);
        }

        if ($locale !== 'en') {
            $Audios->transform(function ($item) use ($locale) {

                if (!empty($item->title)) {
                    $item->title = translateText($item->title, $locale);
                }

                if (!empty($item->description)) {
                    $item->description = translateText($item->description, $locale);
                }

                return $item;
            });
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'totalRecords' => $total,
            'startPoint' => (int) $startPoint,
            'limit' => (int) $limit,
            'message' => 'Active audios loaded successfully.',
            'data' => $Audios
        ]);
    }

    public function singerAudio(Request $request)
    {
        $locale = substr($request->header('Accept-Language', 'en'), 0, 2);

        if (!$request->singerId) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'singerId is required.',
            ]);
        }

        $startPoint = $request->startPoint ?? 0;
        $limit = $request->limit ?? 10;

        $baseQuery = Audios::where('isDeleted', false)
            ->where('isBlocked', false)
            ->where('singerId', $request->singerId)
            ->orderBy('id', 'desc');

        $total = $baseQuery->count();

        $Audios = $baseQuery
            ->skip($startPoint)
            ->take($limit)
            ->get();

        if ($Audios->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => translateText('No active audios found.', $locale),
                'data' => []
            ]);
        }

        if ($locale !== 'en') {

            // 🔥 Collect all texts first
            $titles = $Audios->pluck('title')->filter()->values()->toArray();
            $descriptions = $Audios->pluck('description')->filter()->values()->toArray();

            // 🔥 Batch translate
            $translatedTitles = batchTranslate($titles, $locale);
            $translatedDescriptions = batchTranslate($descriptions, $locale);

            // 🔥 Re-assign
            $titleIndex = 0;
            $descIndex = 0;

            $Audios->transform(function ($item) use (&$titleIndex, &$descIndex, $translatedTitles, $translatedDescriptions) {

                if (!empty($item->title)) {
                    $item->title = $translatedTitles[$titleIndex++] ?? $item->title;
                }

                if (!empty($item->description)) {
                    $item->description = $translatedDescriptions[$descIndex++] ?? $item->description;
                }

                return $item;
            });
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'totalRecords' => $total,
            'startPoint' => (int) $startPoint,
            'limit' => (int) $limit,
            'message' => 'Active audios loaded successfully.',
            'data' => $Audios
        ]);
    }
    public function singleAudio(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => translateText('ID is required.', $locale),
            ]);
        }

        $audio = Audios::where('id', $request->id)
            ->where('isBlocked', false)
            ->where('isDeleted', false)
            ->first();

        if (!$audio) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => translateText('Audio not found.', $locale),
            ]);
        }

        if ($locale !== 'en') {

            if (!empty($audio->title)) {
                $audio->title = translateText($audio->title, $locale);
            }

            if (!empty($audio->description)) {
                $audio->description = translateText($audio->description, $locale);
            }
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Audio loaded successfully.',
            'data' => $audio
        ]);
    }

}
