<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix("user")->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::patch('/{user}/role', [UserController::class, 'update_role'])
        ->name('user.update_role');

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->name('user.destroy');
});