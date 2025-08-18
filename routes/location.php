<?php

use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::prefix("location")->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [LocationController::class, 'index'])->name('location.index');

    Route::post('/', [LocationController::class, 'store'])->middleware(['role:admin'])->name('location.store');

    Route::get('/{location}', [LocationController::class, 'show'])->name('location.show');

    Route::match(['put', 'patch'], '/{location}', [LocationController::class, 'update'])
        ->middleware(['role:admin'])
        ->name('location.update');

    Route::delete('/{location}', [LocationController::class, 'destroy'])->middleware(['role:admin'])->name('location.destroy');
});