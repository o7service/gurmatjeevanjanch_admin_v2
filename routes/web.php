<?php

use App\Http\Controllers\AudiosController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\SamagamsController;
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
    Route::put('/category/single/{id}', [CategoryController::class, 'updateSingle'])->name('category.single');

    // Links Routes .......................................................
    Route::get('/links/{id}', [LinksController::class, 'index'])->name('links.index');
    Route::post('/links', [LinksController::class, 'store'])->name('links.store');
    Route::get('/links/show/{id}', [LinksController::class, 'show'])->name('links.show');
    Route::put('/links/{id}', [LinksController::class, 'update'])->name('links.update');
    Route::put('/links/status/{id}', [LinksController::class, 'updateStatus'])->name('links.status');
    Route::put('/links/live/{id}', [LinksController::class, 'updateLive'])->name('links.live');

    // Audios Routes .......................................................
    Route::get('/audios/{id}', [AudiosController::class, 'index'])->name('audios.index');
    Route::post('/audios', [AudiosController::class, 'store'])->name('audios.store');
    Route::get('/audios/show/{id}', [AudiosController::class, 'show'])->name('audios.show');
    Route::put('/audios/{id}', [AudiosController::class, 'update'])->name('audios.update');
    Route::put('/audios/status/{id}', [AudiosController::class, 'updateStatus'])->name('audios.status');
    
    // Singer links Routes .......................................................
    Route::get('/singer', [SingerImagesController::class, 'index'])->name('singer.index');
    Route::post('/singer', [SingerImagesController::class, 'store'])->name('singer.store');
    Route::get('/singer/{id}', [SingerImagesController::class, 'show'])->name('singer.show');
    Route::put('/singer/update/{id}', [SingerImagesController::class, 'update'])->name('singer.update');
    Route::put('/singer/status/{id}', [SingerImagesController::class, 'updateStatus'])->name('singer.status');

    // Samagam Routes .......................................................
    Route::get('/samagam/{filter}', [SamagamsController::class, 'index'])->name('samagam.index');
    Route::post('/samagam', [SamagamsController::class, 'store'])->name('samagam.store');
    Route::get('/samagam/{id}', [SamagamsController::class, 'show'])->name('samagam.show');
    Route::put('/samagam/update/{id}', [SamagamsController::class, 'update'])->name('samagam.update');
    Route::post('/samagams/{id}/mark-as-viewed', [SamagamsController::class, 'markAsViewed'])->name('samagam.markAsViewed');
    Route::put('/samagam/status/{id}', [SamagamsController::class, 'updateStatus'])->name('samagam.status');

    // Programs links Routes .......................................................
    Route::get('/list/programs', [ProgramsLinksController::class, 'index'])->name('programs.index');
    Route::post('/programs/add', [ProgramsLinksController::class, 'store'])->name('programs.store');
    Route::get('/programs/{id}', [ProgramsLinksController::class, 'show'])->name('programs.show');
    Route::put('/programs/update/{id}', [ProgramsLinksController::class, 'update'])->name('programs.update');
    Route::put('/programs/status/{id}', [ProgramsLinksController::class, 'updateStatus'])->name('programs.status');
});
