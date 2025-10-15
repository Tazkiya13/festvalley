<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display the artist's profile
     */
    public function index()
    {
        Gate::authorize('view profile', User::class);
        $user = Auth::user();
        // dd($user->id); // Debugging line to check user role
        if ($user->hasRole('Artist')) {
            $profile = Profile::where('user_id', $user->id)->with(['user'])->first();
            // dd($profile->toArray()); // Debugging line to check profile data
        } else {
            $profile = Profile::get()->first();
        }

        return view('profile.artist.index', compact('profile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $profile = Profile::with('user')->findOrFail($id);
        Gate::authorize('show', $profile);

        return view('profile.admin.edit', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id = null)
    {
        $user = Auth::user();

        // If no ID provided, use current user's profile
        if (!$id) {
            $id = $user->id;
        }

        // Ensure artists can only edit their own profile
        if ($user->hasRole('Artist') && $id != $user->id) {
            abort(403, 'You can only edit your own profile.');
        }

        $profile = Profile::where('user_id', $id)->first();

        // If profile doesn't exist, create a new instance
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $id;
        }

        // Use the same view for both admin and artist
        return view('profile.artist.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = null)
    {
        $user = Auth::user();

        // If no ID provided, use current user
        if (!$id) {
            $id = $user->id;
        }

        // Ensure artists can only edit their own profile
        if ($user->hasRole('Artist') && $id != $user->id) {
            abort(403, 'You can only edit your own profile.');
        }

        $request->validate([
            'stage_name' => 'required|string|max:255',
            'real_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'genre' => 'nullable|string|in:' . implode(',', array_keys(\App\Helpers\Genres::getGenres())),
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'maps_link' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'years_experience' => 'nullable|integer|min:0',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8048',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8048',
            'languages.*' => 'nullable|string',
            'equipment_owned.*' => 'nullable|string',
            'social_media.instagram' => 'nullable|url',
            'social_media.facebook' => 'nullable|url',
            'social_media.youtube' => 'nullable|url',
            'social_media.tiktok' => 'nullable|url',
            'social_media.twitter' => 'nullable|url',
        ]);

        // Find or create profile
        $profile = Profile::firstOrNew(['user_id' => $id]);

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($profile->profile_photo) {
                Storage::delete('public/' . $profile->profile_photo);
            }

            $profilePhotoPath = $request->file('profile_photo')->store('profiles/photos', 'public');
            $profile->profile_photo = $profilePhotoPath;
        }

        if ($request->hasFile('cover_photo')) {
            // Delete old cover if exists
            if ($profile->cover_photo) {
                Storage::delete('public/' . $profile->cover_photo);
            }

            $coverPhotoPath = $request->file('cover_photo')->store('profiles/covers', 'public');
            $profile->cover_photo = $coverPhotoPath;
        }

        // Fill other data
        $profile->fill($request->except(['profile_photo', 'cover_photo', '_token', '_method']));

        // Handle JSON fields
        $profile->languages = array_filter($request->input('languages', []));
        $profile->equipment_owned = array_filter($request->input('equipment_owned', []));
        $profile->social_media = array_filter($request->input('social_media', []));

        $profile->save();

        // Redirect based on user role
        if ($user->hasRole('Admin')) {
            return redirect()->route('profile.admin.show', $profile->id)->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }

    /**
     * Admin: Display all artist profiles
     */
    public function adminIndex(Request $request)
    {
        // dd($request->all());
        $query = Profile::with('user');

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('stage_name', 'LIKE', "%{$search}%")
                  ->orWhere('real_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where(function($q) use ($location) {
                $q->where('city', 'LIKE', "%{$location}%")
                  ->orWhere('province', 'LIKE', "%{$location}%")
                  ->orWhere('address', 'LIKE', "%{$location}%");
            });
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $profiles = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $totalProfiles = Profile::count();
        $topGenre = Profile::select('genre')
                          ->whereNotNull('genre')
                          ->groupBy('genre')
                          ->orderByRaw('COUNT(*) DESC')
                          ->first();
        $avgExperience = Profile::whereNotNull('years_experience')
                               ->avg('years_experience');
        $avgExperience = $avgExperience ? round($avgExperience) : 0;

        return view('profile.admin.index', compact(
            'profiles',
            'totalProfiles',
            'topGenre',
            'avgExperience'
        ));
    }

    /**
     * Admin: Display specific artist profile
     */
    public function adminShow($id)
    {
        $profile = Profile::with('user')->findOrFail($id);
        return view('profile.admin.detail', compact('profile'));
    }

    /**
     * Admin: Show the form for editing artist profile
     */
    public function adminEdit($id)
    {
        $profile = Profile::findOrFail($id);
        // Use the same view as artist edit
        return view('profile.artist.edit', compact('profile'));
    }

    /**
     * Admin: Show the form for creating a new artist
     */
    public function adminCreate()
    {
        // Create a new profile instance for new artist
        $profile = new Profile();
        return view('profile.artist.create', compact('profile'));
    }

    /**
     * Admin: Store a new artist profile
     */
    public function adminStore(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'stage_name' => 'required|string|max:255',
            'real_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'genre' => 'nullable|string|in:' . implode(',', array_keys(\App\Helpers\Genres::getGenres())),
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'maps_link' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'years_experience' => 'nullable|integer|min:0',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8048',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8048',
            'languages.*' => 'nullable|string',
            'equipment_owned.*' => 'nullable|string',
            'social_media.instagram' => 'nullable|url',
            'social_media.facebook' => 'nullable|url',
            'social_media.youtube' => 'nullable|url',
            'social_media.tiktok' => 'nullable|url',
            'social_media.twitter' => 'nullable|url',
        ]);

        // Create the user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto-verify admin created accounts
        ]);

        // Assign Artist role
        $user->assignRole('Artist');

        // Create the profile
        $profile = new Profile();
        $profile->user_id = $user->id;

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profiles/photos', 'public');
            $profile->profile_photo = $profilePhotoPath;
        }

        if ($request->hasFile('cover_photo')) {
            $coverPhotoPath = $request->file('cover_photo')->store('profiles/covers', 'public');
            $profile->cover_photo = $coverPhotoPath;
        }

        // Update profile fields
        $profile->stage_name = $request->stage_name;
        $profile->real_name = $request->real_name;
        $profile->bio = $request->bio;
        $profile->genre = $request->genre;
        $profile->address = $request->address;
        $profile->city = $request->city;
        $profile->province = $request->province;
        $profile->postal_code = $request->postal_code;
        $profile->country = $request->country;
        $profile->region = $request->region;
        $profile->maps_link = $request->maps_link;
        $profile->phone = $request->phone;
        $profile->years_experience = $request->years_experience;

        // Handle arrays (languages and equipment)
        if ($request->has('languages')) {
            $profile->languages = array_filter($request->languages);
        }
        if ($request->has('equipment_owned')) {
            $profile->equipment_owned = array_filter($request->equipment_owned);
        }

        // Handle social media
        if ($request->has('social_media')) {
            $socialMedia = array_filter($request->social_media);
            $profile->social_media = $socialMedia;
        }

        $profile->save();

        return redirect()->route('profile.admin.index')->with('success', 'Artist profile created successfully!');
    }

    /**
     * Display the user's general profile (accessible to all users)
     */
    public function userProfile()
    {
        $user = Auth::user();
        return view('profile.user.index', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function updateUserProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Update user basic information
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);

            // Handle profile information
            $profile = $user->profile ?? new Profile();
            $profile->user_id = $user->id;
            
            if (isset($validated['phone'])) $profile->phone = $validated['phone'];
            if (isset($validated['city'])) $profile->city = $validated['city'];
            if (isset($validated['region'])) $profile->region = $validated['region'];
            if (isset($validated['bio'])) $profile->bio = $validated['bio'];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($profile->profile_photo && Storage::exists('public/' . $profile->profile_photo)) {
                    Storage::delete('public/' . $profile->profile_photo);
                }
                
                $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
                $profile->profile_photo = $photoPath;
            }

            $profile->save();

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating user profile: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile. Please try again.']);
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        try {
            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->route('user.profile')->with('success', 'Password updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update password. Please try again.']);
        }
    }

    /**
     * Delete user account
     */
    public function deleteAccount()
    {
        $user = Auth::user();
        
        try {
            // Delete user's profile photos if they exist
            if ($user->profile && $user->profile->profile_photo) {
                Storage::delete('public/' . $user->profile->profile_photo);
            }
            if ($user->profile && $user->profile->cover_photo) {
                Storage::delete('public/' . $user->profile->cover_photo);
            }

            // Delete user profile
            if ($user->profile) {
                $user->profile->delete();
            }

            // If user is an artist, delete their packages and related data
            if ($user->hasRole('Artist')) {
                // Delete package photos and data
                foreach ($user->packages as $package) {
                    if ($package->package_photo) {
                        Storage::delete('public/' . $package->package_photo);
                    }
                    $package->availableDates()->delete();
                    $package->delete();
                }
            }

            // Delete user bookings and invoices
            $user->bookings()->delete();
            $user->invoices()->delete();

            // Logout user
            Auth::logout();

            // Delete user account
            $user->delete();

            return redirect()->route('home')->with('success', 'Your account has been successfully deleted.');

        } catch (\Exception $e) {
            Log::error('Error deleting user account: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete account. Please try again.']);
        }
    }
}
