<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ReservationCreatedMail;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationApproved;
use App\Notifications\ReservationCanceled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Mail;
use Str;

class ReservationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $exists = Reservation::where('facility_id', $validated['facility_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })->where(function ($q) {
                $q->whereNotNull('confirmed_at')
                    ->orWhere(function ($sub) {
                        $sub->whereNull('confirmed_at')
                            ->where('confirmation_expires_at', '>', now());
                    });
            })
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Facility is not available in the selected date range.');
        }

        $reservation = Reservation::create([
            'facility_id' => $validated['facility_id'],
            'user_id' => auth()->id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'confirmation_token' => Str::random(40),
            'confirmation_expires_at' => now()->addHour()
        ]);

        Mail::to($reservation->user->email)->send(new ReservationCreatedMail($reservation));

        return redirect()->back()->with('success', 'Reservation successfully created! Please confirm reservation through email we sent you, it will expire in one hour.');
    }

    public function confirm($id, $token)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('home')->with('error', 'Reservation not found.');
        }

        if ($reservation->confirmation_token !== $token) {
            return redirect()->route('home')->with('error', 'Invalid confirmation token.');
        }

        if ($reservation->confirmation_expires_at && now()->greaterThan($reservation->confirmation_expires_at)) {
            return redirect()->route('home')->with('error', 'Confirmation link has expired.');
        }

        $reservation->update([
            'confirmed_at' => now(),
            'confirmation_token' => null,
            'confirmation_expires_at' => null,
        ]);

        $admins = User::where('role', 'admin')->get();

        Notification::send($admins, new ReservationApproved($reservation));

        return redirect()->route('home')->with('success', 'Reservation confirmed successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id() && auth()->user()->role === 'user') {
            return redirect()->route('user.reservations')
                ->with('error', 'You are not authorized to cancel this reservation.');
        }

        $admins = User::where('role', 'admin')->get();

        Notification::send($admins, new ReservationCanceled($reservation));

        $reservation->delete();

        return redirect()->back()->with('success', 'Reservation canceled successfully.');
    }
}
