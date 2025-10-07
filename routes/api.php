<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FacebookLinksController;
use App\Http\Controllers\InstagramLinksController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\SharechatLinksController;
use App\Http\Controllers\SingerImagesController;
use App\Http\Controllers\TelegramLinksController;

use App\Http\Controllers\WebsiteLinksController;
use App\Http\Controllers\WhatsappGroupsLinksController;
use App\Http\Controllers\YoutubeLinksController;
use App\Http\Controllers\ZoomLinksController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;



// Telegram APIS
Route::post("telegram/links/all", [TelegramLinksController::class, 'allLinks']);
Route::post("telegram/link/single", [TelegramLinksController::class, 'singleLink']);

// Facebook APIS
Route::post("facebook/links/all", [FacebookLinksController::class, 'allLinks']);
Route::post("facebook/link/single", [FacebookLinksController::class, 'singleLink']);

// Instagram APIS
Route::post("instagram/links/all", [InstagramLinksController::class, 'allLinks']);
Route::post("instagram/link/single", [InstagramLinksController::class, 'singleLink']);

// Whatsapp Groups APIS
Route::post("whatsappGroup/links/all", [WhatsappGroupsLinksController::class, 'allLinks']);
Route::post("whatsappGroup/link/single", [WhatsappGroupsLinksController::class, 'singleLink']);

// Youtube APIS
Route::post("youtube/links/all", [YoutubeLinksController::class, 'allLinks']);
Route::post("youtube/link/single", [YoutubeLinksController::class, 'singleLink']);

// Zoom APIS
Route::post("zoom/links/all", [ZoomLinksController::class, 'allLinks']);
Route::post("zoom/link/single", [ZoomLinksController::class, 'singleLink']);

// Website APIS
Route::post("website/links/all", [WebsiteLinksController::class, 'allLinks']);
Route::post("website/link/single", [WebsiteLinksController::class, 'singleLink']);

// Sharechat APIS
Route::post("sharechat/links/all", [SharechatLinksController::class, 'allLinks']);
Route::post("sharechat/link/single", [SharechatLinksController::class, 'singleLink']);

// Singer APIS
Route::post("singer/links/all", [SingerImagesController::class, 'allLinks']);
Route::post("singer/link/single", [SingerImagesController::class, 'singleLink']);

// Categories APIS
Route::post("category/all", [CategoryController::class, 'allCategories']);
Route::post("category/single", [CategoryController::class, 'singleCategory']);

// Links APIS
Route::post("links/all", [LinksController::class, 'allLinks']);
Route::post("link/single", [LinksController::class, 'singleLink']);
Route::post("link/category", [LinksController::class, 'categoryLink']);