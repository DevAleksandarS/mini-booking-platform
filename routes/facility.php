<?php

use App\Http\Controllers\FacilityController;
use Illuminate\Support\Facades\Route;

Route::prefix("facility")->middleware(['auth', 'verified'])->group(function () {

    Route::post('/', [FacilityController::class, 'store'])->middleware(['role:admin'])->name('facility.store');

    Route::match(['put', 'patch'], '/{facility}', [FacilityController::class, 'update'])
        ->middleware(['role:admin'])
        ->name('facility.update');

    Route::delete('/{facility}', [FacilityController::class, 'destroy'])->middleware(['role:admin'])->name('facility.destroy');
});