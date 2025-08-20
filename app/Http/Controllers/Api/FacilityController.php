<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'price' => 'required|numeric|min:1',
            'number_of_beds' => 'required|integer|min:1',
            'max_people' => 'required|integer|min:1',
            'image' => 'required|image|max:2048',
        ]);

        $image = $request->file('image');
        $extension = $image->getClientOriginalExtension();
        $filename = preg_replace('/\s+/', '_', $data['name']) . '_' . time() . '.' . $extension;
        $data['image'] = $image->storeAs('images/facility', $filename, 'public');

        Facility::create($data);

        return redirect()->back()->with('success', 'Facility added successfully.');
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'price' => 'required|numeric|min:1',
            'number_of_beds' => 'required|integer|min:1',
            'max_people' => 'required|integer|min:1',
        ]);

        $data['image'] = $request->get('image', $facility->image);

        $facility->update($data);

        return redirect()->back()->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('admin.facilities')->with('success', 'Facility deleted successfully.');
    }
}
