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