<?php
use App\Http\Controllers\DailyNumberController;
use App\Http\Controllers\MonthlyTotalController;



Auth::routes();
Route::get('/', [DailyNumberController::class, 'index'])->name('numbers.index');
//Route::get('/monthly-totals', [MonthlyTotalController::class, 'index'])->name('monthly-totals.index');
