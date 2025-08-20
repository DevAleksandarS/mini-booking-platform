<?php

use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix("api/review")->middleware(['auth', 'verified'])->group(function () {
    Route::post('/{facility}', [ReviewController::class, 'store'])
        ->middleware('role:user')
        ->name('review.store');

    Route::match(['put', 'patch'], '/{review}', [ReviewController::class, 'update'])
        ->middleware(['role:user'])
        ->name('review.update');

    Route::delete('/{review}', [ReviewController::class, 'destroy'])
        ->name('review.destroy');
});