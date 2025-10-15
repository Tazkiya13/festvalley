<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function search(Request $request)
    {
        // Get search parameters
        $search = $request->input('search');
        $artist = $request->input('artist'); // Keep for backward compatibility
        $package = $request->input('package'); // Keep for backward compatibility
        $tanggal = $request->input('tanggal');
        $location = $request->input('location'); // Keep for backward compatibility
        $country = $request->input('country');
        $province = $request->input('province');
        $category = $request->input('category');

        $packages = Package::with(['availableDates', 'user', 'user.profile'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('package_name', 'like', '%' . $search . '%')
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', '%' . $search . '%')
                                    ->orWhereHas('profile', function ($profileQuery) use ($search) {
                                        $profileQuery->where('stage_name', 'like', '%' . $search . '%');
                                    });
                      });
                });
            })
            ->when($artist, function ($query, $artist) {
                $query->whereHas('user', function ($q) use ($artist) {
                    $q->where('name', 'like', '%' . $artist . '%')
                      ->orWhereHas('profile', function ($pq) use ($artist) {
                          $pq->where('stage_name', 'like', '%' . $artist . '%');
                      });
                });
            })
            ->when($package, function ($query, $package) {
                $query->where('package_name', 'like', '%' . $package . '%');
            })
            ->when($country, function ($query, $country) {
                $query->where(function ($q) use ($country) {
                    $q->where('country', $country)
                      ->orWhereHas('user.profile', function ($pq) use ($country) {
                          $pq->where('country', $country);
                      });
                });
            })
            ->when($province, function ($query, $province) {
                $query->where(function ($q) use ($province) {
                    $q->where('region', $province)
                      ->orWhereHas('user.profile', function ($pq) use ($province) {
                          $pq->where('province', $province)
                            ->orWhere('region', $province);
                      });
                });
            })
            ->when($location, function ($query, $location) {
                $query->where(function ($q) use ($location) {
                    $q->where('city', 'like', '%' . $location . '%')
                      ->orWhere('region', 'like', '%' . $location . '%')
                      ->orWhere('country', 'like', '%' . $location . '%')
                      ->orWhereHas('user.profile', function ($pq) use ($location) {
                          $pq->where('city', 'like', '%' . $location . '%')
                            ->orWhere('province', 'like', '%' . $location . '%')
                            ->orWhere('address', 'like', '%' . $location . '%');
                      });
                });
            })
            ->when($category, function ($query, $category) {
                $query->whereHas('user.profile', function ($q) use ($category) {
                    $q->where('genre', 'like', '%' . $category . '%');
                });
            })
            ->when($tanggal, function ($query, $tanggal) {
                $tanggalDb = date('Y-m-d', strtotime($tanggal));
                $query->whereHas('availableDates', function ($q) use ($tanggalDb) {
                    $q->where('date', $tanggalDb);
                });
            })
            ->latest()
            ->paginate(12);

        // Get unique genres and cities for filter options
        $genres = array_values(\App\Helpers\Genres::getGenres());
            
        // Get cities from both packages and profiles
        $packageCities = DB::table('packages')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter();
            
        $profileCities = DB::table('profiles')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter();
            
        $cities = $packageCities->merge($profileCities)
            ->unique()
            ->sort()
            ->values();

        return view('landingpage.browse', compact('packages', 'genres', 'cities'));
    }

    public function index()
    {
        $packages = Package::with(['availableDates', 'user', 'user.profile'])
            ->latest()
            ->paginate(12);

        $availableDates = [];
        foreach ($packages as $package) {
            foreach ($package->availableDates as $date) {
                $availableDates[] = $date->date;
            }
        }

        // Get unique genres and cities for filter options
        $genres = array_values(\App\Helpers\Genres::getGenres());
            
        $cities = DB::table('profiles')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort();

        return view('landingpage.browse', compact('packages', 'availableDates', 'genres', 'cities'));
    }

    public function getPackageDetails($id)
    {
        $package = Package::with(['availableDates', 'user', 'user.profile'])->findOrFail($id);
        
        return response()->json([
            'id' => $package->id,
            'package_name' => $package->package_name,
            'description' => $package->description,
            'price' => $package->price,
            'image' => $package->image,
            'photos' => $package->photos,
            'video' => $package->video,
            'artist_name' => $package->user->name,
            'stage_name' => $package->user->profile->stage_name ?? null,
            'genre' => $package->user->profile->genre ?? null,
            // Prioritize package location data over artist profile data
            'country' => $package->country ?? $package->user->profile->country ?? null,
            'region' => $package->region ?? $package->user->profile->region ?? null,
            'city' => $package->city ?? $package->user->profile->city ?? null,
            'province' => $package->user->profile->province ?? null,
            'years_experience' => $package->user->profile->years_experience ?? null,
            'languages' => $package->user->profile->languages ?? [],
            'equipment_owned' => $package->user->profile->equipment_owned ?? [],
            'bio' => $package->user->profile->bio ?? null,
            'phone' => $package->user->profile->phone ?? null,
            'address' => $package->user->profile->address ?? null,
            'social_media' => $package->user->profile->social_media ?? [],
            'available_dates' => $package->availableDates->pluck('date'),
            'created_at' => $package->created_at->format('M d, Y'),
        ]);
    }

    public function create()
    {
        return view('card-list.create');
    }

    public function store(Request $request)
    {
        // Logic to store a new card in the database
        // Validate and save the card data
    }

    public function edit($id)
    {
        // Logic to show the form for editing a card
        return view('card-list.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic to update the card in the database
        // Validate and update the card data
    }

    public function destroy($id)
    {
        // Logic to delete a card from the database
    }
}
