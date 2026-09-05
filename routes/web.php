<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('tracker.index'));

Route::get('/tracker', [ActivityController::class, 'index'])->name('tracker.index');
Route::post('/tracker/store', [ActivityController::class, 'store'])->name('tracker.store');
Route::post('/tracker/dispatch-weekly', [ActivityController::class, 'dispatchWeeklyDigest'])->name('tracker.dispatch-weekly');
Route::post('/tracker/review', [ActivityController::class, 'review'])->name('tracker.review');
Route::post('/tracker/correction', [ActivityController::class, 'submitCorrection'])->name('tracker.correction');