<?php

namespace App\Http\Controllers;

use App\Models\Samagam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\TranslateHelper;

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
    public function allSamagams(Request $request)
{
    $locale = $request->header('Accept-Language', 'en');

    $startPoint = $request->startPoint ?? 0;   // default 0
    $limit = $request->limit ?? 5;            // default 10

    $query = Samagam::where('isDeleted', false)
        ->where('isBlocked', false)
        ->orderBy('id', 'desc');

    $total = $query->count();

    $Samagam = $query->skip($startPoint)
        ->take($limit)
        ->get();

    if ($Samagam->isEmpty()) {
        return response()->json([
            'success' => false,
            'status' => 404,
            'message' => translateText('No active Samagam found.', $locale),
            'data' => []
        ]);
    }

    // 🔥 Translate only paginated data
    if ($locale !== 'en') {
        $Samagam->transform(function ($item) use ($locale) {

            $item->organizerName = translateText($item->organizerName, $locale);
            $item->address = translateText($item->address, $locale);
            $item->details = translateText($item->details, $locale);

            return $item;
        });
    }

    return response()->json([
        'success' => true,
        'status' => 200,
        'totalRecords' => $total,
        'startPoint' => (int)$startPoint,
        'limit' => (int)$limit,
        'message' => translateText('Active Samagams loaded successfully.', $locale),
        'data' => $Samagam
    ]);
}


    public function singleSamagam(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        // Check if ID is provided
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => translateText('ID is required.', $locale),
            ]);
        }

        $link = Samagam::where('id', $request->id)->first();

        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => translateText('Samagam not found.', $locale),
            ]);
        }

        // translate 
        $link->organizerName = translateText($link->organizerName, $locale);
        $link->address = translateText($link->address, $locale);
        $link->details = translateText($link->details, $locale);

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Samagam loaded successfully.',
            'data' => $link
        ]);
    }

}
