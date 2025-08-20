<?php

use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\ProfileController;
use App\Models\Facility;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    $facilities = Facility::with('location')->paginate(perPage: 12);

    return view('home', compact('facilities'));
})->name('home');

Route::get('/facility/{id}', function ($id) {
    $facility = Facility::with('location')->findOrFail($id);
    $locations = Location::all();

    return view('facility', compact('facility', 'locations'));
})->name('facility');

Route::get('/reservation/confirm/{id}/{token}', [ReservationController::class, 'confirm'])
    ->name('reservation.confirm');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/reservations', function () {
        $reservations = Reservation::with('facility')->with('user')
            ->where(function ($query) {
                $query->whereNotNull('confirmed_at')
                    ->orWhere(function ($q) {
                        $q->whereNull('confirmed_at')
                            ->where(function ($sub) {
                                $sub->where('confirmation_expires_at', '>', now());
                            });
                    });
            })
            ->latest()
            ->paginate(10);

        return view('admin.reservations', compact('reservations'));
    })->name('reservations');

    Route::get('/facilities', function () {
        $facilities = Facility::paginate(10);
        $locations = Location::all();

        return view('admin.facility', compact('facilities', 'locations'));
    })->name('facilities');

    Route::get('/reviews', function () {
        return view('reviews');
    })->name('reviews');

    Route::get('/users', function () {
        $users = User::where('id', '!=', auth()->id())->paginate(10);

        return view('admin.users', compact('users'));
    })->name('users');

    Route::get('/locations', function () {
        $locations = Location::paginate(10);

        return view('admin.location', compact('locations'));
    })->name('locations');
});

// User Routes
Route::name('user.')->middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/reservations', function () {
        $reservations = Reservation::with('facility')
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->whereNotNull('confirmed_at')
                    ->orWhere(function ($q) {
                        $q->whereNull('confirmed_at')
                            ->where(function ($sub) {
                                $sub->where('confirmation_expires_at', '>', now());
                            });
                    });
            })
            ->latest()
            ->paginate(10);

        return view('user.reservations', compact('reservations'));
    })->name('reservations');

    Route::get('/reviews', function () {
        return view('reviews');
    })->name('reviews');
});


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/location.php';
require __DIR__ . '/facility.php';
require __DIR__ . '/users.php';
require __DIR__ . '/reservation.php';