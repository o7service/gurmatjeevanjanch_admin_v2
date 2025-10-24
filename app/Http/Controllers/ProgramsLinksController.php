<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\programsLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramsLinksController extends Controller
{
    public function index()
    {

        $programsLinks = ProgramsLinks::orderBy('isBlocked', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('programs.index', compact('programsLinks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mapLink' => 'required|url|max:255',
            'startDate' => 'required|date|max:255',
            'endDate' => 'required|date|max:255',
            'contactNumber1' => 'required',
            'contactNumber2' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $newFileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newFileName = 'programs/'.time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('programs'), $newFileName);
            }
            $totalLinks = ProgramsLinks::count();
            $newLink = new ProgramsLinks();
            $newLink->autoId = $totalLinks + 1;
            $newLink->title = $request->title;
            $newLink->address = $request->address;
            $newLink->details = $request->details;
            $newLink->imageUrl = $newFileName;
            $newLink->mapLink = $request->mapLink;
            $newLink->startDate = $request->startDate;
            $newLink->endDate = $request->endDate;
            $newLink->contactNumber1 = $request->contactNumber1;
            $newLink->contactNumber2 = $request->contactNumber2;
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
        $link = ProgramsLinks::find($id);

        if (!$link) {
            return response()->json(['error' => 'Link not found'], 404);
        }

        return response()->json($link);
    }



    public function updateStatus(Request $request, $id)
    {
        $link = programsLinks::findOrFail($id);
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
    DB::beginTransaction();
    try {
        $link = ProgramsLinks::findOrFail($id);
        \Log::info('Update Request:', $request->all());

        $newFileName = $link->imageUrl;

        if ($request->hasFile('file')) {
            if ($link->imageUrl && file_exists(public_path('programs/' . $link->imageUrl))) {
                unlink(public_path('programs/' . $link->imageUrl));
            }
            $file = $request->file('file');
            $newFileName = 'programs/'.time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('programs'), $newFileName);
        }

        $link->title = $request->title;
        $link->address = $request->address;
        $link->details = $request->details;
        $link->mapLink = $request->mapLink;
        $link->startDate = $request->startDate;
        $link->endDate = $request->endDate;
        $link->contactNumber1 = $request->contactNumber1;
        $link->contactNumber2 = $request->contactNumber2;
        $link->imageUrl = $newFileName;
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
        return response()->json([
            'success' => false,
            'message' => 'Error Occurred: ' . $e->getMessage()
        ], 500);
    }
}


    //APIS
    public function allPrograms()
    {
        $programsLinks = ProgramsLinks::where('isDeleted', false)
            ->where('isBlocked', false)
            ->orderBy('id', 'desc')
            ->get();

        if ($programsLinks->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'No active programs found.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Active programs loaded successfully.',
            'data' => $programsLinks
        ]);
    }

    public function programByDate(Request $request)
    {
        if (!$request->date) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'Date is required.',
            ]);
        }

        $link = programsLinks::where('startDate', $request->date)->first();
        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Program not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Programs loaded successfully.',
            'data' => $link
        ]);
    }


    public function singleProgram(Request $request)
    {
        // Check if ID is provided
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'ID is required.',
            ]);
        }

        $link = ProgramsLinks::where('id', $request->id)->first();
        if (!$link) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Program not found.',
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Program loaded successfully.',
            'data' => $link
        ]);

    }

}
