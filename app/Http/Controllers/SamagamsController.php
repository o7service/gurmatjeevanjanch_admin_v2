<?php

namespace App\Http\Controllers;

use App\Models\Samagam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SamagamsController extends Controller
{
    public function index($filter)
    {
        $query = Samagam::query();

        // Apply filter dynamically
        switch ($filter) {
            case 'blocked':
                $query->where('isBlocked', true);
                break;

            case 'active':
                $query->where('isBlocked', false);
                break;

            case 'viewed':
                $query->where('isViewed', true);
                break;

            case 'unviewed':
                $query->where('isViewed', false);
                break;

            default:
                // No filter or invalid filter → return all
                break;
        }

        // Common query part
        $samagams = $query->orderBy('isBlocked', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('samagams.index', compact('samagams', 'filter'));
    }

    public function markAsViewed($id)
    {
        $link = Samagam::findOrFail($id);
        if ($link->isViewed == true) {
            $link->isViewed = false;
        } else {
            $link->isViewed = true;
        }
        $link->save();
        return redirect()->back()->with('success', 'Samagam Marked as Viewed!');
    }


    public function store(Request $request)
    {
        if (!$request->organizerName || !$request->details || !$request->startDate || !$request->phone || !$request->address || !$request->endDate || !$request->email || !$request->mapLink) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'Validation error.',
            ]);
        }
        DB::beginTransaction();
        try {
            $totalLinks = Samagam::count();
            $newLink = new Samagam();
            $newLink->autoId = $totalLinks + 1;
            $newLink->organizerName = $request->organizerName;
            $newLink->address = $request->address;
            $newLink->details = $request->details;
            $newLink->mapLink = $request->mapLink;
            $newLink->phone = $request->phone;
            $newLink->email = $request->email;
            $newLink->startDate = $request->startDate;
            $newLink->endDate = $request->endDate;
            $newLink->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Samagam Added Successfully',
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
        $link = Samagam::find($id);
        if (!$link) {
            return response()->json(['error' => 'Samagam not found'], 404);
        }
        return response()->json($link);
    }
    //APIS
    public function allSamagams()
    {
        $Samagam = Samagam::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($Samagam->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active Samagam found.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Active Samagams loaded successfully.',
            'data' => $Samagam
        ]);
    }


    public function singleSamagam(Request $request)
    {
        // Check if ID is provided
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'ID is required.',
            ]);
        }

        $link = Samagam::where('id', $request->id)->first();
        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Samagam not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Samagam loaded successfully.',
            'data' => $link
        ]);

    }

}
