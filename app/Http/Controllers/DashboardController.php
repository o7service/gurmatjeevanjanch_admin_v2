<?php

namespace App\Http\Controllers;
use App\Models\Links;
use App\Models\Category;



class DashboardController extends Controller
{
    public function dashboard()
    {
        $categoryNames = ['Whatsapp', 'Facebook', 'Telegram', 'Instagram', 'Youtube'];
        $categories = Category::whereIn('title', $categoryNames)->pluck('id', 'title');
        $telegramId = $categories['Telegram'];
        $facebookId = $categories['Facebook'];
        $whatsappId = $categories['Whatsapp'];
        $instagramId = $categories['Instagram'];
        $youtubeId = $categories['Youtube'];

        $stats = [];

        foreach ($categories as $name => $id) {
            $stats["active{$name}Links"] = Links::where('categoryId', $id)
                ->where('isBlocked', false)
                ->count();

            $stats["blocked{$name}Links"] = Links::where('categoryId', $id)
                ->where('isBlocked', true)
                ->count();
        }

        return view('dashboard.dashboard', array_merge($stats, [
            'telegramId' => $telegramId,
            'facebookId' => $facebookId,
            'whatsappId' => $whatsappId,
            'instagramId' => $instagramId,
            'youtubeId' => $youtubeId,
        ]));

    }


    // APIS
    public function home()
    {
        try {

            $wantedCategories = ['Youtube', 'Instagram', 'Telegram', 'Facebook', 'Whatsapp', 'Sharechat'];

            $categories = Category::where('isBlocked', false)
                ->whereIn('title', $wantedCategories)
                ->where('isDeleted', false)
                ->orderBy('id', 'desc')
                ->get();

            $youtubeLink = null;
            $zoomLink = null;

            $youtubeCategory = $categories->firstWhere('title', 'Youtube');

            $zoomCategory = Category::where('title', 'Zoom')
                ->where('isDeleted', false)
                ->where('isBlocked', false)
                ->first();

            if ($youtubeCategory) {
                $youtubeLink = Links::where('isDeleted', false)
                    ->where('isBlocked', false)
                    ->where('isLive', true)
                    ->where('categoryId', $youtubeCategory->id)
                    ->orderBy('id', 'desc')
                    ->first();
            }

            if ($zoomCategory) {
                $zoomLink = Links::where('isDeleted', false)
                    ->where('isBlocked', false)
                    ->where('categoryId', $zoomCategory->id)
                    ->orderBy('id', 'desc')
                    ->first();
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Home data loaded successfully.',
                'data' => [
                    'categories' => $categories,
                    'youtubeLink' => $youtubeLink,
                    'zoomLink' => $zoomLink,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }
}
