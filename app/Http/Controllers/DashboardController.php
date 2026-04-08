<?php

namespace App\Http\Controllers;
use App\Models\Links;
use App\Models\Category;
use Illuminate\Http\Request;

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
    public function home(Request $request)
    {
        try {

            $locale = $request->header('Accept-Language', 'en');

            $wantedCategories = ['Youtube', 'Instagram', 'Telegram', 'Facebook', 'Whatsapp', 'Sharechat'];

            $categories = Category::where('isBlocked', false)
                ->where('isDeleted', false)
                ->whereIn('title', $wantedCategories)
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

            /*
            |--------------------------------------------------------------------------
            | Translation Logic
            |--------------------------------------------------------------------------
            */

            if ($locale !== 'en') {

                // Translate Categories
                $categories->transform(function ($item) use ($locale) {

                    if (!empty($item->title)) {
                        $item->title = translateText($item->title, $locale);
                    }

                    if (!empty($item->description)) {
                        $item->description = translateText($item->description, $locale);
                    }

                    if (!empty($item->actionText)) {
                        $item->actionText = translateText($item->actionText, $locale);
                    }
                    return $item;
                });

                // Translate Youtube Link
                if ($youtubeLink) {
                    if (!empty($youtubeLink->title)) {
                        $youtubeLink->title = translateText($youtubeLink->title, $locale);
                    }

                    if (!empty($youtubeLink->description)) {
                        $youtubeLink->description = translateText($youtubeLink->description, $locale);
                    }
                }

                // Translate Zoom Link
                if ($zoomLink) {
                    if (!empty($zoomLink->title)) {
                        $zoomLink->title = translateText($zoomLink->title, $locale);
                    }

                    if (!empty($zoomLink->description)) {
                        $zoomLink->description = translateText($zoomLink->description, $locale);
                    }
                }
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
                'message' => translateText('Something went wrong.', $locale)
            ]);
        }
    }
}
