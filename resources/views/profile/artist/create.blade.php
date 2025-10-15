@extends('admin.home')
@section('title', 'Create New Artist')

@section('content')
<div class="content">
    <div class="padding">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-0">
                    <i class="fas fa-user-plus me-2 text-primary"></i>
                    Create New Artist
                </h2>
                <p class="text-muted mb-0">Create a new artist profile and account</p>
            </div>
            <div>
                <a href="{{ route('profile.admin.index') }}" class="btn btn-secondary">
                    <i class="material-icons">arrow_back</i> Back to Admin Panel
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.admin.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <!-- Account Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">account_circle</i>Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name *</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email *</label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password *</label>
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password *</label>
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">person</i>Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stage_name">Stage Name *</label>
                                        <input type="text"
                                               class="form-control @error('stage_name') is-invalid @enderror"
                                               id="stage_name"
                                               name="stage_name"
                                               value="{{ old('stage_name') }}"
                                               required>
                                        @error('stage_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="real_name">Real Name</label>
                                        <input type="text"
                                               class="form-control @error('real_name') is-invalid @enderror"
                                               id="real_name"
                                               name="real_name"
                                               value="{{ old('real_name') }}">
                                        @error('real_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror"
                                          id="bio"
                                          name="bio"
                                          rows="4"
                                          placeholder="Tell us about the artist...">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="genre">Genre</label>
                                        <select class="form-control @error('genre') is-invalid @enderror"
                                                id="genre"
                                                name="genre">
                                            <option value="">Select Genre</option>
                                            @foreach(\App\Helpers\Genres::getGenres() as $key => $genreName)
                                                <option value="{{ $key }}" {{ old('genre') == $key ? 'selected' : '' }}>{{ $genreName }}</option>
                                            @endforeach
                                        </select>
                                        @error('genre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="years_experience">Years of Experience</label>
                                        <input type="number"
                                               class="form-control @error('years_experience') is-invalid @enderror"
                                               id="years_experience"
                                               name="years_experience"
                                               min="0"
                                               value="{{ old('years_experience') }}">
                                        @error('years_experience')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">place</i>Location & Contact</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="3"
                                          placeholder="Full address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control @error('country') is-invalid @enderror"
                                                id="country"
                                                name="country">
                                            <option value="">Select Country</option>
                                            @foreach(\App\Helpers\Countries::getCountries() as $countryKey => $countryName)
                                                <option value="{{ $countryKey }}" {{ old('country') == $countryKey ? 'selected' : '' }}>{{ $countryName }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="region">Region</label>
                                        <select class="form-control @error('region') is-invalid @enderror"
                                                id="region"
                                                name="region">
                                            <option value="">Select Region</option>
                                            <!-- Populated by JavaScript based on country selection -->
                                        </select>
                                        @error('region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="province">Province</label>
                                        <input type="text"
                                               class="form-control @error('province') is-invalid @enderror"
                                               id="province"
                                               name="province"
                                               value="{{ old('province') }}">
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text"
                                               class="form-control @error('city') is-invalid @enderror"
                                               id="city"
                                               name="city"
                                               value="{{ old('city') }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postal_code">Postal Code</label>
                                        <input type="text"
                                               class="form-control @error('postal_code') is-invalid @enderror"
                                               id="postal_code"
                                               name="postal_code"
                                               value="{{ old('postal_code') }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="maps_link">Google Maps Link</label>
                                <input type="url"
                                       class="form-control @error('maps_link') is-invalid @enderror"
                                       id="maps_link"
                                       name="maps_link"
                                       value="{{ old('maps_link') }}"
                                       placeholder="https://maps.google.com/...">
                                @error('maps_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Languages -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">translate</i>Languages</h5>
                        </div>
                        <div class="card-body">
                            <div id="languages-container">
                                @if(old('languages'))
                                    @foreach(old('languages') as $language)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="languages[]" value="{{ $language }}" placeholder="e.g., Indonesian, English">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger remove-language">Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="languages[]" placeholder="e.g., Indonesian, English">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger remove-language">Remove</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-language" class="btn btn-outline-primary btn-sm">
                                <i class="material-icons mr-1">add</i> Add Language
                            </button>
                        </div>
                    </div>

                    <!-- Equipment -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">settings</i>Equipment Owned</h5>
                        </div>
                        <div class="card-body">
                            <div id="equipment-container">
                                @if(old('equipment_owned'))
                                    @foreach(old('equipment_owned') as $equipment)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="equipment_owned[]" value="{{ $equipment }}" placeholder="e.g., Guitar, Microphone, Amplifier">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger remove-equipment">Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="equipment_owned[]" placeholder="e.g., Guitar, Microphone, Amplifier">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-danger remove-equipment">Remove</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-equipment" class="btn btn-outline-primary btn-sm">
                                <i class="material-icons mr-1">add</i> Add Equipment
                            </button>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">share</i>Social Media</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="url"
                                               class="form-control @error('social_media.instagram') is-invalid @enderror"
                                               id="instagram"
                                               name="social_media[instagram]"
                                               value="{{ old('social_media.instagram') }}"
                                               placeholder="https://instagram.com/username">
                                        @error('social_media.instagram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="url"
                                               class="form-control @error('social_media.facebook') is-invalid @enderror"
                                               id="facebook"
                                               name="social_media[facebook]"
                                               value="{{ old('social_media.facebook') }}"
                                               placeholder="https://facebook.com/username">
                                        @error('social_media.facebook')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="youtube">YouTube</label>
                                        <input type="url"
                                               class="form-control @error('social_media.youtube') is-invalid @enderror"
                                               id="youtube"
                                               name="social_media[youtube]"
                                               value="{{ old('social_media.youtube') }}"
                                               placeholder="https://youtube.com/channel/...">
                                        @error('social_media.youtube')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tiktok">TikTok</label>
                                        <input type="url"
                                               class="form-control @error('social_media.tiktok') is-invalid @enderror"
                                               id="tiktok"
                                               name="social_media[tiktok]"
                                               value="{{ old('social_media.tiktok') }}"
                                               placeholder="https://tiktok.com/@username">
                                        @error('social_media.tiktok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="twitter">Twitter/X</label>
                                <input type="url"
                                       class="form-control @error('social_media.twitter') is-invalid @enderror"
                                       id="twitter"
                                       name="social_media[twitter]"
                                       value="{{ old('social_media.twitter') }}"
                                       placeholder="https://twitter.com/username">
                                @error('social_media.twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Photo Upload -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">photo_camera</i>Photos</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="profile_photo">Profile Photo</label>
                                <input type="file"
                                       class="form-control-file @error('profile_photo') is-invalid @enderror"
                                       id="profile_photo"
                                       name="profile_photo"
                                       accept="image/*">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max size: 8MB. Formats: JPG, PNG, GIF, WebP</small>
                            </div>
                            <div class="form-group">
                                <label for="cover_photo">Cover Photo</label>
                                <input type="file"
                                       class="form-control-file @error('cover_photo') is-invalid @enderror"
                                       id="cover_photo"
                                       name="cover_photo"
                                       accept="image/*">
                                @error('cover_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max size: 8MB. Formats: JPG, PNG, GIF, WebP</small>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>

                <!-- Submit Button -->                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons mr-1">save</i> Create Artist Profile
                            </button>
                            <a href="{{ route('profile.admin.index') }}" class="btn btn-secondary btn-block mt-2">
                                <i class="material-icons mr-1">cancel</i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Language functionality
    document.getElementById('add-language').addEventListener('click', function() {
        const container = document.getElementById('languages-container');
        const newField = document.createElement('div');
        newField.className = 'input-group mb-2';
        newField.innerHTML = `
            <input type="text" class="form-control" name="languages[]" placeholder="e.g., Indonesian, English">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger remove-language">Remove</button>
            </div>
        `;
        container.appendChild(newField);
    });

    // Remove Language functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-language')) {
            e.target.closest('.input-group').remove();
        }
    });

    // Add Equipment functionality
    document.getElementById('add-equipment').addEventListener('click', function() {
        const container = document.getElementById('equipment-container');
        const newField = document.createElement('div');
        newField.className = 'input-group mb-2';
        newField.innerHTML = `
            <input type="text" class="form-control" name="equipment_owned[]" placeholder="e.g., Guitar, Microphone, Amplifier">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger remove-equipment">Remove</button>
            </div>
        `;
        container.appendChild(newField);
    });

    // Remove Equipment functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-equipment')) {
            e.target.closest('.input-group').remove();
        }
    });

    // Country-Region functionality
    const regions = {!! \App\Helpers\Countries::getRegionsAsJson() !!};
    
    document.getElementById('country').addEventListener('change', function() {
        const countryCode = this.value;
        const regionSelect = document.getElementById('region');
        
        // Clear existing options
        regionSelect.innerHTML = '<option value="">Select Region</option>';
        
        if (countryCode && regions[countryCode]) {
            regions[countryCode].forEach(function(regionName) {
                const option = document.createElement('option');
                option.value = regionName;
                option.textContent = regionName;
                regionSelect.appendChild(option);
            });
        }
    });
});
</script>
@endsection
