<?php

namespace App\Http\Controllers;
use App\Models\Links;


class DashboardController extends Controller
{
    public function dashboard()
    {
        $categoryNames = ['Whatsapp', 'Facebook', 'Telegram', 'Instagram', 'Youtube'];
        $categories = \App\Models\Category::whereIn('title', $categoryNames)->pluck('id', 'title');
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
}
