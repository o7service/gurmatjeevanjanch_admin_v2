<?php

use App\Http\Controllers\LinksController;
use App\Http\Controllers\SingerImagesController;
use App\Http\Controllers\ProgramsLinksController;
use App\Http\Controllers\CategoryController;
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
    Route::get('/layout/category', [CategoryController::class, 'getCategories'])->name('category.layout');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::put('/category/status/{id}', [CategoryController::class, 'updateStatus'])->name('category.status');

    // Links Routes .......................................................
    Route::get('/links/{id}', [LinksController::class, 'index'])->name('links.index');
    Route::post('/links', [LinksController::class, 'store'])->name('links.store');
    Route::get('/links/show/{id}', [LinksController::class, 'show'])->name('links.show');
    Route::put('/links/{id}', [LinksController::class, 'update'])->name('links.update');
    Route::put('/links/status/{id}', [LinksController::class, 'updateStatus'])->name('links.status');
});

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
