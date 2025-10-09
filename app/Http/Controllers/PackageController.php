<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\AvailableDate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index()
    {
        Gate::authorize('view package', Package::class);
        $packages = Package::with('availableDates')->orderBy('id', 'desc')->paginate(10); // Ganti get() dengan paginate()
        $users = User::all();
        return view('package.index', compact(['packages', 'users']));
    }

    public function create()
    {
        // Authorization check - ensure user can create packages
        $user = request()->user();
        if (!$user->can('create package') && !$user->can('create package artists')) {
            abort(403, 'You do not have permission to create packages.');
        }
        
        $Artists = [];
        
        // Only load artists list for Admin users
        if (request()->user() && request()->user()->hasRole('Admin')) {
            $artists = User::all();
            foreach ($artists as $artist) {
                if ($artist->hasRole('Artist')) {
                    $Artists[] = User::findOrFail($artist->id);
                }
            }
        }
        
        return view('package.create-package', compact('Artists'));
    }

    public function store(Request $request)
    {
        // Debug: Check current user and permissions
        $user = $request->user();
        
        // Temporarily simplified authorization check
        if ($user->hasRole('Artist') && !$user->can('create package artists')) {
            abort(403, 'Artists need create package artists permission.');
        }
        
        if ($user->hasRole('Admin') && !$user->can('create package')) {
            abort(403, 'Admins need create package permission.');
        }
        
        // For Artists: ensure they're creating for themselves only
        if ($user->hasRole('Artist')) {
            if ($request->has('user_id') && $request->user_id != $user->id) {
                abort(403, 'Artists can only create packages for themselves.');
            }
        }
        
        // Define validation rules
        $rules = [
            'package_name' => 'required|string|max:255',
            'available_dates' => 'required|string', // Now it's a JSON string
            'description' => 'required|string',
            'price' => 'required|numeric',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:25600',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:25600', // Max 25MB per photo
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', // Max 50MB
        ];
        
        // Only validate user_id for Admin users
        if ($request->user() && $request->user()->hasRole('Admin')) {
            $rules['user_id'] = 'required|exists:users,id';
        }
        
        $request->validate($rules);

        // Parse the JSON dates
        $availableDates = json_decode($request->available_dates, true);
        if (empty($availableDates) || !is_array($availableDates)) {
            return redirect()->back()->withErrors(['available_dates' => 'Please select at least one available date.']);
        }

        // Validate each date
        foreach ($availableDates as $date) {
            if (!strtotime($date)) {
                return redirect()->back()->withErrors(['available_dates' => 'Invalid date format detected.']);
            }
        }

        // For artists, automatically set user_id to their own ID
        $userId = $request->user()->id; // Default to current user
        if ($request->user() && $request->user()->hasRole('Admin') && $request->has('user_id')) {
            $userId = $request->user_id; // Admin can select any user
        }

        // Handle file uploads
        $imagePath = $request->file('image')->store('packages/images', 'public');
        
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('packages/photos', 'public');
            }
        }
        
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('packages/videos', 'public');
        }

        $package = Package::create([
            'user_id' => $userId,
            'package_name' => $request->package_name,
            'description' => $request->description,
            'price' => $request->price,
            'country' => $request->country,
            'region' => $request->region,
            'image' => $imagePath,
            'photos' => !empty($photoPaths) ? $photoPaths : null,
            'video' => $videoPath,
        ]);

        // Store the available dates
        foreach ($availableDates as $date) {
            AvailableDate::create([
                'package_id' => $package->id,
                'date' => $date,
            ]);
        }

        // if artist redirect to /artist, if admin redirect to /packages
        if ($user->hasRole('Admin')) {
            return redirect()->route('packages.index')->with('success', 'Package created successfully.');
        } else {
            return redirect()->route('artists.index')->with('success', 'Package created successfully.');
        }
    }

    public function edit($id)
    {
        $package = Package::with('availableDates')->findOrFail($id);
        Gate::authorize('update', $package); // Check ownership
        
        $artists = User::findOrFail($package->user_id);

        $availableDates = [];
        foreach ($package->availableDates as $date) {
            $availableDates[] = $date->date;
        }
        return view('package.edit-package', compact(['package', 'artists', 'availableDates']));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        Gate::authorize('update', $package); // Check ownership
        
        // Define validation rules
        $rules = [
            'package_name' => 'required|string|max:255',
            'available_dates' => 'required|array|min:1',
            'available_dates.*' => 'required|date',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:25600',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:25600', // Max 25MB per photo
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200', // Max 50MB
        ];
        
        // Only validate user_id for Admin users
        if ($request->user() && $request->user()->hasRole('Admin')) {
            $rules['user_id'] = 'required|exists:users,id';
        }
        
        $validated = $request->validate($rules);

        // For artists, they can only update their own packages (user_id is fixed)
        $userId = $package->user_id; // Keep original user_id
        if ($request->user() && $request->user()->hasRole('Admin') && $request->has('user_id')) {
            $userId = $request->user_id; // Admin can change the user
        }

        $package = Package::findOrFail($id);
        $package->user_id = $userId;
        $package->package_name = $validated['package_name'];
        $package->description = $validated['description'];
        $package->price = $validated['price'];
        $package->country = $validated['country'];
        $package->region = $validated['region'];

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }
            $package->image = $request->file('image')->store('packages/images', 'public');
        }

        // Handle photos deletion and upload
        $currentPhotos = $package->photos ?? [];
        
        // Delete photos marked for deletion
        if ($request->has('delete_photos')) {
            foreach ($request->delete_photos as $photoToDelete) {
                if (Storage::disk('public')->exists($photoToDelete)) {
                    Storage::disk('public')->delete($photoToDelete);
                }
                $currentPhotos = array_filter($currentPhotos, function($photo) use ($photoToDelete) {
                    return $photo !== $photoToDelete;
                });
            }
        }
        
        // Add new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $currentPhotos[] = $photo->store('packages/photos', 'public');
            }
        }
        
        $package->photos = !empty($currentPhotos) ? array_values($currentPhotos) : null;

        // Handle video deletion and upload
        if ($request->has('delete_video') && $request->delete_video == '1') {
            if ($package->video && Storage::disk('public')->exists($package->video)) {
                Storage::disk('public')->delete($package->video);
            }
            $package->video = null;
        } elseif ($request->hasFile('video')) {
            // Delete old video if uploading new one
            if ($package->video && Storage::disk('public')->exists($package->video)) {
                Storage::disk('public')->delete($package->video);
            }
            $package->video = $request->file('video')->store('packages/videos', 'public');
        }

        $package->save();

        // Update available dates
        AvailableDate::where('package_id', $package->id)->delete();
        foreach ($validated['available_dates'] as $date) {
            AvailableDate::create([
                'package_id' => $package->id,
                'date' => $date,
            ]);
        }

        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        Gate::authorize('delete', $package); // Use policy to check ownership
        
        // Delete media files
        if ($package->image && Storage::disk('public')->exists($package->image)) {
            Storage::disk('public')->delete($package->image);
        }
        
        if ($package->photos) {
            foreach ($package->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }
        
        if ($package->video && Storage::disk('public')->exists($package->video)) {
            Storage::disk('public')->delete($package->video);
        }
        
        // Delete the associated available dates
        AvailableDate::where('package_id', $package->id)->delete();
        // Delete the package
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
