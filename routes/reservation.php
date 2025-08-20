<?php

use App\Http\Controllers\Api\ReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix("api/reservation")->middleware(['auth', 'verified'])->group(function () {
    Route::post('/', [ReservationController::class, 'store'])
        ->middleware('role:user')
        ->name('reservation.store');

    Route::delete('/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservation.destroy');
});