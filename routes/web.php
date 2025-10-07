<?php

use App\Http\Controllers\SingerImagesController;
use App\Http\Controllers\ProgramsLinksController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TelegramLinksController;
use App\Http\Controllers\FacebookLinksController;
use App\Http\Controllers\InstagramLinksController;
use App\Http\Controllers\YoutubeLinksController;
use App\Http\Controllers\ZoomLinksController;
use App\Http\Controllers\WebsiteLinksController;
use App\Http\Controllers\SharechatLinksController;
use App\Http\Controllers\WhatsappGroupsLinksController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();





Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name("dashboard");
    // category Routes .......................................................

    
    Route::get('link/category', [CategoryController::class, 'index'])->name('categories');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::put('/category/status/{id}', [CategoryController::class, 'updateStatus'])->name('category.status');

});

// Telegram links Routes .......................................................
Route::get('/telegram', [TelegramLinksController::class, 'index'])->name('telegram.index');
Route::post('/telegram', [TelegramLinksController::class, 'store'])->name('telegram.store');
Route::get('/telegram/{id}', [TelegramLinksController::class, 'show'])->name('telegram.show');
Route::put('/telegram/{id}', [TelegramLinksController::class, 'update'])->name('telegram.update');
Route::put('/telegram/status/{id}', [TelegramLinksController::class, 'updateStatus'])->name('telegram.status');

// Facebook links Routes .......................................................
Route::get('/facebook', [FacebookLinksController::class, 'index'])->name('facebook.index');
Route::post('/facebook', [FacebookLinksController::class, 'store'])->name('facebook.store');
Route::get('/facebook/{id}', [FacebookLinksController::class, 'show'])->name('facebook.show');
Route::put('/facebook/{id}', [FacebookLinksController::class, 'update'])->name('facebook.update');
Route::put('/facebook/status/{id}', [FacebookLinksController::class, 'updateStatus'])->name('facebook.status');

// WhatsappGroups links Routes .......................................................
Route::get('/whatsappGroups', [WhatsappGroupsLinksController::class, 'index'])->name('whatsappGroups.index');
Route::post('/whatsappGroups', [WhatsappGroupsLinksController::class, 'store'])->name('whatsappGroups.store');
Route::get('/whatsappGroups/{id}', [WhatsappGroupsLinksController::class, 'show'])->name('whatsappGroups.show');
Route::put('/whatsappGroups/{id}', [WhatsappGroupsLinksController::class, 'update'])->name('whatsappGroups.update');
Route::put('/whatsappGroups/status/{id}', [WhatsappGroupsLinksController::class, 'updateStatus'])->name('whatsappGroups.status');

// Instagram links Routes .......................................................
Route::get('/instagram', [InstagramLinksController::class, 'index'])->name('instagram.index');
Route::post('/instagram', [InstagramLinksController::class, 'store'])->name('instagram.store');
Route::get('/instagram/{id}', [InstagramLinksController::class, 'show'])->name('instagram.show');
Route::put('/instagram/{id}', [InstagramLinksController::class, 'update'])->name('instagram.update');
Route::put('/instagram/status/{id}', [InstagramLinksController::class, 'updateStatus'])->name('instagram.status');

// Youtube links Routes .......................................................
Route::get('/youtube', [YoutubeLinksController::class, 'index'])->name('youtube.index');
Route::post('/youtube', [YoutubeLinksController::class, 'store'])->name('youtube.store');
Route::get('/youtube/{id}', [YoutubeLinksController::class, 'show'])->name('youtube.show');
Route::put('/youtube/{id}', [YoutubeLinksController::class, 'update'])->name('youtube.update');
Route::put('/youtube/status/{id}', [YoutubeLinksController::class, 'updateStatus'])->name('youtube.status');
Route::put('/youtube/live/{id}', [YoutubeLinksController::class, 'setLive'])->name('youtube.live');

// Zoom links Routes .......................................................
Route::get('/zoom', [ZoomLinksController::class, 'index'])->name('zoom.index');
Route::post('/zoom', [ZoomLinksController::class, 'store'])->name('zoom.store');
Route::get('/zoom/{id}', [ZoomLinksController::class, 'show'])->name('zoom.show');
Route::put('/zoom/{id}', [ZoomLinksController::class, 'update'])->name('zoom.update');
Route::put('/zoom/status/{id}', [ZoomLinksController::class, 'updateStatus'])->name('zoom.status');

// Website links Routes .......................................................
Route::get('/website', [WebsiteLinksController::class, 'index'])->name('website.index');
Route::post('/website', [WebsiteLinksController::class, 'store'])->name('website.store');
Route::get('/website/{id}', [WebsiteLinksController::class, 'show'])->name('website.show');
Route::put('/website/{id}', [WebsiteLinksController::class, 'update'])->name('website.update');
Route::put('/website/status/{id}', [WebsiteLinksController::class, 'updateStatus'])->name('website.status');

// Sharechat links Routes .......................................................
Route::get('/sharechat', [SharechatLinksController::class, 'index'])->name('sharechat.index');
Route::post('/sharechat', [SharechatLinksController::class, 'store'])->name('sharechat.store');
Route::get('/sharechat/{id}', [SharechatLinksController::class, 'show'])->name('sharechat.show');
Route::put('/sharechat/{id}', [SharechatLinksController::class, 'update'])->name('sharechat.update');
Route::put('/sharechat/status/{id}', [SharechatLinksController::class, 'updateStatus'])->name('sharechat.status');

// Singer links Routes .......................................................
Route::get('/singer', [SingerImagesController::class, 'index'])->name('singer.index');
Route::post('/singer', [SingerImagesController::class, 'store'])->name('singer.store');
Route::get('/singer/{id}', [SingerImagesController::class, 'show'])->name('singer.show');
Route::put('/singer/{id}', [SingerImagesController::class, 'update'])->name('singer.update');
Route::put('/singer/status/{id}', [SingerImagesController::class, 'updateStatus'])->name('singer.status');

// Programs links Routes .......................................................
Route::get('/programs', [ProgramsLinksController::class, 'index'])->name('programs.index');
Route::post('/programs', [ProgramsLinksController::class, 'store'])->name('programs.store');
Route::get('/programs/{id}', [ProgramsLinksController::class, 'show'])->name('programs.show');
Route::put('/programs/{id}', [ProgramsLinksController::class, 'update'])->name('programs.update');
Route::put('/programs/status/{id}', [ProgramsLinksController::class, 'updateStatus'])->name('programs.status');

