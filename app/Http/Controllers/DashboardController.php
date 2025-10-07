<?php

namespace App\Http\Controllers;


use App\Models\facebookLinks;
use App\Models\instagramLinks;
use App\Models\telegramLinks;
use App\Models\whatsappGroupsLinks;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $activeTelegramLinks = telegramLinks::where("isBlocked", false)->count();
        $blockedTelegramLinks = telegramLinks::where("isBlocked", true)->count();
        $activeFacebookLinks = facebookLinks::where("isBlocked", false)->count();
        $blockedFacebookLinks = facebookLinks::where("isBlocked", true)->count();
        $activeWhatsappGroupLinks = whatsappGroupsLinks::where("isBlocked", false)->count();
        $blockedWhatsappGroupLinks = whatsappGroupsLinks::where("isBlocked", true)->count();
        $activeInstagramLinks = instagramLinks::where("isBlocked", false)->count();
        $blockedInstagramLinks = instagramLinks::where("isBlocked", true)->count();
        return view('dashboard.dashboard', compact("activeTelegramLinks", "blockedTelegramLinks", "activeFacebookLinks", "blockedFacebookLinks", "activeWhatsappGroupLinks", "blockedWhatsappGroupLinks", "activeInstagramLinks", "blockedInstagramLinks"));
    }
}
