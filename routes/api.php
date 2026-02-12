<?php
use App\Http\Controllers\AudiosController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\ProgramsLinksController;
use App\Http\Controllers\SamagamsController;
use App\Http\Controllers\SingerImagesController;
use Illuminate\Support\Facades\Route;

// Home API
Route::post("home", [DashboardController::class, 'home']);

// Singer APIS
Route::post("singers/all", [SingerImagesController::class, 'allSingers']);
Route::post("singers/single", [SingerImagesController::class, 'singleSinger']);

// Programs APIS
Route::post("programs/all", [ProgramsLinksController::class, 'allPrograms']);
Route::post("programs/single", [ProgramsLinksController::class, 'singleProgram']);
Route::post("programs/date", [ProgramsLinksController::class, 'programByDate']);

// Categories APIS
Route::post("category/all", [CategoryController::class, 'allCategories']);
Route::post("category/single", [CategoryController::class, 'singleCategory']);

// Links APIS
Route::post("links/all", [LinksController::class, 'allLinks']);
Route::post("link/single", [LinksController::class, 'singleLink']);
Route::post("link/category", [LinksController::class, 'categoryLink']);

// Audios APIS
Route::post("audios/all", [AudiosController::class, 'allAudios']);
Route::post("audio/single", [AudiosController::class, 'singleAudio']);
Route::post("singer/audio", [AudiosController::class, 'singerAudio']);

// Samagam APIS
Route::post("samagam/add", [SamagamsController::class, 'store']);
Route::post("samagam/all", [SamagamsController::class, 'allSamagams']);
Route::post("samagam/single", [SamagamsController::class, 'singleSamagam']);