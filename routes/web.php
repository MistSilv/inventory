<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StocktakingController;
use App\Http\Controllers\PhysicalCensusController;

// Home / Dashboard
Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

// Stocktakings
Route::prefix('stocktakings')->group(function () {
    Route::get('/', [StocktakingController::class, 'index'])->name('stocktakings.index');
    Route::get('/create', [StocktakingController::class, 'create'])->name('stocktakings.create');
    Route::post('/', [StocktakingController::class, 'store'])->name('stocktakings.store');
    Route::get('/{id}', [StocktakingController::class, 'show'])->name('stocktakings.show');
});

// Products
Route::resource('products', ProductController::class)->only([
    'create', 'store', 'index'
]);

Route::get('/test', function () {
    return view('test');
})->name('test');

// Regions
Route::prefix('regions')->group(function () {
    Route::get('/', function () { return "Regions Index"; })->name('regions.index');
    Route::get('/create', function () { return "Create Region"; })->name('regions.create');
    Route::get('/{id}', function ($id) { return "View Region $id"; })->name('regions.show');
    Route::get('/{id}/edit', function ($id) { return "Edit Region $id"; })->name('regions.edit');
});

// Logs
Route::prefix('logs')->group(function () {
    Route::get('/', function () { return "Logs Index"; })->name('logs.index');
    Route::get('/{id}', function ($id) { return "View Log $id"; })->name('logs.show');
});

// Users
Route::prefix('users')->group(function () {
    Route::get('/', function () { return "Users Index"; })->name('users.index');
    Route::get('/create', function () { return "Create User"; })->name('users.create');
    Route::get('/{id}', function ($id) { return "View User $id"; })->name('users.show');
    Route::get('/{id}/edit', function ($id) { return "Edit User $id"; })->name('users.edit');
});

// Units
Route::prefix('units')->group(function () {
    Route::get('/', function () { return "Units Index"; })->name('units.index');
    Route::get('/create', function () { return "Create Unit"; })->name('units.create');
    Route::get('/{id}', function ($id) { return "View Unit $id"; })->name('units.show');
    Route::get('/{id}/edit', function ($id) { return "Edit Unit $id"; })->name('units.edit');
});

// routes/web.php


Route::prefix('region-stocktakings/{regionStocktaking}/censuses')
    ->name('region_stocktakings.censuses.')
    ->group(function () {
        Route::get('/', [PhysicalCensusController::class, 'index'])->name('index');
        Route::get('/create', [PhysicalCensusController::class, 'create'])->name('create');
        Route::post('/', [PhysicalCensusController::class, 'store'])->name('store');
        Route::get('/{census}', [PhysicalCensusController::class, 'show'])
            ->name('show')
            ->scopeBindings(); // <-- Important!
});
Route::get('/region-stocktakings/{regionStocktaking}/revise',
    [StocktakingController::class, 'revise'])
    ->name('region_stocktakings.revise');
    
Route::post('/stocktakings/{regionStocktaking}/adjustments', [StocktakingController::class, 'storeAdjustments'])
    ->name('stocktakings.adjustments.store');






