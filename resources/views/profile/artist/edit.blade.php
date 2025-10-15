@extends('admin.home')
@section('title', Auth::user()->hasRole('Admin') ? 'Edit Artist Profile' : 'Edit My Profile')

@section('content')
<div class="content">
    <div class="padding">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-0">
                    <i class="fas fa-user-edit me-2 text-primary"></i>
                    {{ Auth::user()->hasRole('Admin') ? 'Edit Artist Profile' : 'Edit My Profile' }}
                </h2>
                <p class="text-muted mb-0">{{ Auth::user()->hasRole('Admin') ? 'Update artist profile information' : 'Update your artist profile information' }}</p>
            </div>
            <div>
                @if(Auth::user()->hasRole('Admin'))
                    <a href="{{ route('profile.admin.index') }}" class="btn btn-secondary">
                        <i class="material-icons">arrow_back</i> Back to Admin Panel
                    </a>
                @else
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                        <i class="material-icons">arrow_back</i> Back to Profile
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ Auth::user()->hasRole('Admin') ? route('profile.admin.update', $profile->user_id ?: auth()->id()) : route('profile.artist.update', $profile->user_id ?: auth()->id()) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
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
                                               value="{{ old('stage_name', $profile->stage_name) }}"
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
                                               value="{{ old('real_name', $profile->real_name) }}">
                                        @error('real_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bio">Biography</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror"
                                          id="bio"
                                          name="bio"
                                          rows="4"
                                          placeholder="Tell us about your musical journey, style, and experience...">{{ old('bio', $profile->bio) }}</textarea>
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
                                            @foreach (App\Helpers\Genres::getGenres() as $genreKey => $genreName)
                                                <option value="{{ $genreKey }}" {{ old('genre', $profile->genre) == $genreKey ? 'selected' : '' }}>{{ $genreName }}</option>
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
                                               value="{{ old('years_experience', $profile->years_experience) }}"
                                               min="0">
                                        @error('years_experience')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">contact_phone</i>Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $profile->phone) }}"
                                       placeholder="+62 xxx xxxx xxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  id="address"
                                                  name="address"
                                                  rows="3">{{ old('address', $profile->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control @error('country') is-invalid @enderror"
                                                id="country"
                                                name="country">
                                            <option value="">Select Country</option>
                                            @foreach(\App\Helpers\Countries::getCountries() as $countryKey => $countryName)
                                                <option value="{{ $countryKey }}" {{ old('country', $profile->country) == $countryKey ? 'selected' : '' }}>{{ $countryName }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="region">Region</label>
                                        <select class="form-control @error('region') is-invalid @enderror"
                                                id="region"
                                                name="region">
                                            <option value="">Select Region</option>
                                            <!-- Regions will be populated by JavaScript -->
                                        </select>
                                        @error('region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text"
                                               class="form-control @error('city') is-invalid @enderror"
                                               id="city"
                                               name="city"
                                               value="{{ old('city', $profile->city) }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="province">Province</label>
                                        <input type="text"
                                               class="form-control @error('province') is-invalid @enderror"
                                               id="province"
                                               name="province"
                                               value="{{ old('province', $profile->province) }}">
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="postal_code">Postal Code</label>
                                        <input type="text"
                                               class="form-control @error('postal_code') is-invalid @enderror"
                                               id="postal_code"
                                               name="postal_code"
                                               value="{{ old('postal_code', $profile->postal_code) }}">
                                        @error('postal_code')
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
                                       value="{{ old('maps_link', $profile->maps_link) }}"
                                       placeholder="https://maps.google.com/...">
                                @error('maps_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">work</i>Professional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="languages">Languages</label>
                                <div id="languages-container">
                                    @if(old('languages', $profile->languages ?? []))
                                        @foreach(old('languages', $profile->languages ?? []) as $index => $language)
                                            @if($language)
                                                <div class="input-group mb-2">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="languages[]"
                                                           value="{{ $language }}"
                                                           placeholder="e.g., Indonesian, English">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-danger remove-language">Remove</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                   class="form-control"
                                                   name="languages[]"
                                                   placeholder="e.g., Indonesian, English">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger remove-language">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-language">Add Language</button>
                            </div>

                            <div class="form-group">
                                <label for="equipment_owned">Equipment Owned</label>
                                <div id="equipment-container">
                                    @if(old('equipment_owned', $profile->equipment_owned ?? []))
                                        @foreach(old('equipment_owned', $profile->equipment_owned ?? []) as $index => $equipment)
                                            @if($equipment)
                                                <div class="input-group mb-2">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="equipment_owned[]"
                                                           value="{{ $equipment }}"
                                                           placeholder="e.g., Guitar, Microphone, Keyboard">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-danger remove-equipment">Remove</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                   class="form-control"
                                                   name="equipment_owned[]"
                                                   placeholder="e.g., Guitar, Microphone, Keyboard">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger remove-equipment">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-equipment">Add Equipment</button>
                            </div>
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
                                               value="{{ old('social_media.instagram', $profile->social_media['instagram'] ?? '') }}"
                                               placeholder="https://instagram.com/username">
                                        @error('social_media.instagram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="youtube">YouTube</label>
                                        <input type="url"
                                               class="form-control @error('social_media.youtube') is-invalid @enderror"
                                               id="youtube"
                                               name="social_media[youtube]"
                                               value="{{ old('social_media.youtube', $profile->social_media['youtube'] ?? '') }}"
                                               placeholder="https://youtube.com/channel/...">
                                        @error('social_media.youtube')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="url"
                                               class="form-control @error('social_media.facebook') is-invalid @enderror"
                                               id="facebook"
                                               name="social_media[facebook]"
                                               value="{{ old('social_media.facebook', $profile->social_media['facebook'] ?? '') }}"
                                               placeholder="https://facebook.com/username">
                                        @error('social_media.facebook')
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
                                               value="{{ old('social_media.tiktok', $profile->social_media['tiktok'] ?? '') }}"
                                               placeholder="https://tiktok.com/@username">
                                        @error('social_media.tiktok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Profile Photos -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">photo</i>Profile Photos</h5>
                        </div>
                        <div class="card-body">
                            <!-- Profile Photo -->
                            <div class="form-group">
                                <label for="profile_photo">Profile Photo</label>
                                @if($profile->profile_photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                             alt="Current Profile Photo"
                                             class="img-thumbnail"
                                             style="max-width: 150px;">
                                        <p class="text-muted small mt-1">Current profile photo</p>
                                    </div>
                                @endif
                                <input type="file"
                                       class="form-control-file @error('profile_photo') is-invalid @enderror"
                                       id="profile_photo"
                                       name="profile_photo"
                                       accept="image/*">
                                <small class="form-text text-muted">Max size: 8MB. Formats: JPEG, PNG, JPG, GIF, WebP</small>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cover Photo -->
                            <div class="form-group">
                                <label for="cover_photo">Cover Photo</label>
                                @if($profile->cover_photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $profile->cover_photo) }}"
                                             alt="Current Cover Photo"
                                             class="img-thumbnail"
                                             style="max-width: 100%; height: 100px; object-fit: cover;">
                                        <p class="text-muted small mt-1">Current cover photo</p>
                                    </div>
                                @endif
                                <input type="file"
                                       class="form-control-file @error('cover_photo') is-invalid @enderror"
                                       id="cover_photo"
                                       name="cover_photo"
                                       accept="image/*">
                                <small class="form-text text-muted">Max size: 8MB. Formats: JPEG, PNG, JPG, GIF, WebP</small>
                                @error('cover_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons mr-1">save</i> Update Profile
                            </button>
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary btn-block mt-2">
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
            <input type="text" class="form-control" name="equipment_owned[]" placeholder="e.g., Guitar, Microphone, Keyboard">
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

    // Country and Region functionality
    const regions = {!! \App\Helpers\Countries::getRegionsAsJson() !!};

    const countrySelect = document.getElementById('country');
    const regionSelect = document.getElementById('region');
    const currentRegion = '{{ old("region", $profile->region ?? "") }}';

    function updateRegions() {
        const selectedCountry = countrySelect.value;
        
        // Clear existing options
        regionSelect.innerHTML = '<option value="">Select Region</option>';
        
        // Populate new options
        if (selectedCountry && regions[selectedCountry]) {
            regions[selectedCountry].forEach(region => {
                const option = document.createElement('option');
                option.value = region;
                option.textContent = region;
                if (region === currentRegion) {
                    option.selected = true;
                }
                regionSelect.appendChild(option);
            });
        }
    }

    // Initialize regions on page load
    updateRegions();

    // Update regions when country changes
    countrySelect.addEventListener('change', updateRegions);
});
</script>
@endsection
