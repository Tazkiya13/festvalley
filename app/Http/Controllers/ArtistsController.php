<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Package;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\AvailableDate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ArtistsController extends Controller
{
    public function index()
    {
        Gate::authorize('view package artists', Package::class);
        $userId = Auth::id();
        // Ambil semua package milik user yang login, beserta tanggal-tanggal availableDates
        $packages = Package::with('availableDates')
            ->where('user_id', $userId)
            ->paginate(10);
        return view('artists.index', compact('packages'));
    }

    public function create()
    {
        // Logic to show the form for creating a new artist
        return view('artists.create');
    }

    public function store(Request $request)
    {
        // Logic to store a new artist in the database
        // Validate and save the artist data
    }

    public function edit($id)
    {
        $package = Package::with('availableDates')->findOrFail($id);
        $artists = User::findOrFail($package->user_id);

         $availableDates = [];
        foreach ($package->availableDates as $date) {
            $availableDates[] = $date->date; // Fixed: was $date->tanggal, now $date->date
        }
        return view('artists.edit-artists', compact(['package', 'artists', 'availableDates']));
    }

    public function update(Request $request, $id)
    {
         Gate::authorize('edit package artists', Package::class);
        $validated = $request->validate([
            'package_name' => 'required|string|max:255', // Fixed: was name_package, now package_name
            'available_dates' => 'required|array|min:1',
            'available_dates.*' => 'required|date',
        ]);

        $package = Package::findOrFail($id);
        $package->package_name = $validated['package_name']; // Fixed: field name corrected
        $package->save();

        // Update available dates
        AvailableDate::where('package_id', $package->id)->delete();
        foreach ($validated['available_dates'] as $date) {
            AvailableDate::create([
                'package_id' => $package->id,
                'date' => $date, // Fixed: was tanggal, now date
            ]);
        }
        return redirect()->route('artists.index')->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        // Logic to delete an artist from the database
    }

    /**
     * Show booking requests for the artist
     */
    public function bookingRequests()
    {
        $artistId = Auth::id();
        
        // Get all booking requests for this artist's packages
        $invoices = Invoice::with(['package', 'user', 'availableDate', 'order'])
            ->whereHas('package', function($query) use ($artistId) {
                $query->where('user_id', $artistId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('artists.booking-requests', compact('invoices'));
    }
}
