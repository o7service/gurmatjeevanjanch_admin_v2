<?php

namespace App\Http\Controllers;
use App\Models\Audios;
use App\Models\singerImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SingerImagesController extends Controller
{
    public function index()
    {
        $singerImages = singerImages::orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('singer.index', compact('singerImages'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $newFileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newFileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('singers'), $newFileName);
            }
            $totalLinks = singerImages::count();
            $newSinger = new singerImages();
            $newSinger->autoId = $totalLinks + 1;
            $newSinger->name = $request->name;
            $newSinger->imageUrl = $newFileName;
            $newSinger->updatedById = Auth::id();
            $newSinger->addedById = Auth::id();
            $newSinger->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Singer Added Successfully',
                'data' => $newSinger
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
        $link = singerImages::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json($link);
    }

    public function updateStatus(Request $request, $id)
    {
        $link = singerImages::findOrFail($id);
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
    //     $link = singerImages::findOrFail($id);
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
            'name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $link = singerImages::findOrFail($id);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newFileName = 'singers/' . time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('singers'), $newFileName);
                $link->imageUrl = $newFileName;
            }

            $link->name = $request->name;
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
    public function allSingers(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        $startPoint = $request->startPoint ?? 0;
        $limit = $request->limit ?? 5;

        $query = singerImages::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc');

        $total = $query->count();

        $singerImages = $query->skip($startPoint)
            ->take($limit)
            ->get();

        if ($singerImages->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => translateText('No singers found.', $locale),
                'data' => []
            ]);
        }

        if ($locale !== 'en') {
            $singerImages->transform(function ($item) use ($locale) {

                if (!empty($item->name)) {
                    $item->name = translateText($item->name, $locale);
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
            'message' => translateText('Singers loaded successfully.', $locale),
            'data' => $singerImages
        ]);
    }

    public function singleSinger(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => translateText('ID is required.', $locale),
            ]);
        }

        $link = singerImages::where('id', $request->id)->first();
        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => translateText('Singer not found.', $locale),
            ]);
        }

        if ($locale !== 'en') {
            if (!empty($link->name)) {
                $link->name = translateText($link->name, $locale);
            }
            if (!empty($link->description)) {
                $link->description = translateText($link->description, $locale);
            }
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => translateText('Singer loaded successfully.', $locale),
            'data' => $link
        ]);
    }



}
