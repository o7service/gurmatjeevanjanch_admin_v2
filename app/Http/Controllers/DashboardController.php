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

        $activeTelegramLinks = 0;
        $blockedTelegramLinks = 0;
        $activeFacebookLinks = 0;
        $blockedFacebookLinks = 0;
        $activeWhatsappGroupLinks = 0;
        $blockedWhatsappGroupLinks = 0;
        $activeInstagramLinks = 0;
        $blockedInstagramLinks = 0;
        return view('dashboard.dashboard', compact("activeTelegramLinks", "blockedTelegramLinks", "activeFacebookLinks", "blockedFacebookLinks", "activeWhatsappGroupLinks", "blockedWhatsappGroupLinks", "activeInstagramLinks", "blockedInstagramLinks"));
    }
}
