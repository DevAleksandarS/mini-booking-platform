<?php

use App\Http\Controllers\Api\FacilityController;
use Illuminate\Support\Facades\Route;

Route::prefix("api/facility")->middleware(['auth', 'verified', 'role:admin'])->group(function () {

    Route::post('/', [FacilityController::class, 'store'])->name('facility.store');

    Route::match(['put', 'patch'], '/{facility}', [FacilityController::class, 'update'])
        ->name('facility.update');

    Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('facility.destroy');
});