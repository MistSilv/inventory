<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeController;

Route::post('/Barcode_check', [BarcodeController::class, 'check'])->name('barcode.check');
