<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Facility $facility)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $existing_review = $facility->reviews()
            ->where('user_id', auth()->id())
            ->first();

        if ($existing_review) {
            return redirect()->back()->with('error', 'You have already added a review for this facility.');
        }

        $has_ended_reservation = Reservation::where('facility_id', $facility->id)
            ->where('user_id', auth()->id())
            ->whereDate('end_date', '<', Carbon::today())
            ->exists();

        if (!$has_ended_reservation) {
            return redirect()->back()->with('error', 'You dont have any reservation that ended.');
        }

        $facility->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && auth()->user()->role === 'user') {
            return redirect()->back()->with('error', 'You are not authorized to delete this review.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
