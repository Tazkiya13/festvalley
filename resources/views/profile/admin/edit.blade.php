@extends('admin.home')
@section('title', 'Edit Profile')

@section('content')
<div class="content">
    <div class="padding">
        {{-- <div class="navbar navbar-expand-lg navbar-light bg-light row no-gutters mb-4">
            <h2 class="text-primary">Edit Artist Profile</h2>
            <div class="navbar-nav ml-auto">
                <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                    <i class="material-icons">arrow_back</i> Back to Profile
                </a>
            </div>
        </div> --}}

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.admin.update', $profile->user_id ?: auth()->id()) }}" enctype="multipart/form-data">
            @csrf
            {{-- @method('PUT') --}}

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

                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror"
                                          id="bio"
                                          name="bio"
                                          rows="4"
                                          placeholder="Tell us about yourself, your music style, and experience...">{{ old('bio', $profile->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">location_on</i>Location & Contact</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address"
                                          name="address"
                                          rows="2"
                                          placeholder="Your full address...">{{ old('address', $profile->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text"
                                               class="form-control @error('city') is-invalid @enderror"
                                               id="city"
                                               name="city"
                                               value="{{ old('city', $profile->city) }}"
                                               placeholder="e.g., Jakarta">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="province">Province</label>
                                        <input type="text"
                                               class="form-control @error('province') is-invalid @enderror"
                                               id="province"
                                               name="province"
                                               value="{{ old('province', $profile->province) }}"
                                               placeholder="e.g., DKI Jakarta">
                                        @error('province')
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
                                               value="{{ old('postal_code', $profile->postal_code) }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone', $profile->phone) }}"
                                               placeholder="+62 812-3456-7890">
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
                            <!-- Languages -->
                            <div class="form-group">
                                <label>Languages</label>
                                <div id="languages-container">
                                    @if($profile->languages && count($profile->languages) > 0)
                                        @foreach($profile->languages as $index => $language)
                                            <div class="input-group mb-2">
                                                <input type="text"
                                                       class="form-control"
                                                       name="languages[]"
                                                       value="{{ $language }}"
                                                       placeholder="e.g., Indonesian, English">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-danger btn-remove-language">
                                                        <i class="material-icons">remove</i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                   class="form-control"
                                                   name="languages[]"
                                                   placeholder="e.g., Indonesian">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger btn-remove-language">
                                                    <i class="material-icons">remove</i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-language">
                                    <i class="material-icons">add</i> Add Language
                                </button>
                            </div>

                            <!-- Equipment -->
                            <div class="form-group">
                                <label>Equipment Owned</label>
                                <div id="equipment-container">
                                    @if($profile->equipment_owned && count($profile->equipment_owned) > 0)
                                        @foreach($profile->equipment_owned as $index => $equipment)
                                            <div class="input-group mb-2">
                                                <input type="text"
                                                       class="form-control"
                                                       name="equipment_owned[]"
                                                       value="{{ $equipment }}"
                                                       placeholder="e.g., Guitar, Microphone, Sound System">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-danger btn-remove-equipment">
                                                        <i class="material-icons">remove</i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                   class="form-control"
                                                   name="equipment_owned[]"
                                                   placeholder="e.g., Guitar">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger btn-remove-equipment">
                                                    <i class="material-icons">remove</i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-equipment">
                                    <i class="material-icons">add</i> Add Equipment
                                </button>
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
                            </div>

                            <div class="row">
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

                            <div class="form-group">
                                <label for="twitter">Twitter</label>
                                <input type="url"
                                       class="form-control @error('social_media.twitter') is-invalid @enderror"
                                       id="twitter"
                                       name="social_media[twitter]"
                                       value="{{ old('social_media.twitter', $profile->social_media['twitter'] ?? '') }}"
                                       placeholder="https://twitter.com/username">
                                @error('social_media.twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="material-icons">save</i> Save Profile
                        </button>
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
                            <!-- Profile Photo -->
                            <div class="form-group">
                                <label for="profile_photo">Profile Photo</label>
                                @if($profile->profile_photo)
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                             alt="Current Profile Photo"
                                             class="img-thumbnail"
                                             style="max-width: 150px;">
                                    </div>
                                @endif
                                <input type="file"
                                       class="form-control-file @error('profile_photo') is-invalid @enderror"
                                       id="profile_photo"
                                       name="profile_photo"
                                       accept="image/*">
                                <small class="form-text text-muted">Max size: 25MB. Supported: JPG, PNG, GIF</small>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cover Photo -->
                            <div class="form-group">
                                <label for="cover_photo">Cover Photo</label>
                                @if($profile->cover_photo)
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('storage/' . $profile->cover_photo) }}"
                                             alt="Current Cover Photo"
                                             class="img-thumbnail"
                                             style="max-width: 200px;">
                                    </div>
                                @endif
                                <input type="file"
                                       class="form-control-file @error('cover_photo') is-invalid @enderror"
                                       id="cover_photo"
                                       name="cover_photo"
                                       accept="image/*">
                                <small class="form-text text-muted">Max size: 25MB. Supported: JPG, PNG, GIF</small>
                                @error('cover_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">lightbulb_outline</i>Profile Tips</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2">✓ Use a clear profile photo showing your face</li>
                                <li class="mb-2">✓ Write a compelling bio highlighting your unique style</li>
                                <li class="mb-2">✓ Include all relevant social media links</li>
                                <li class="mb-2">✓ List all instruments/equipment you own</li>
                                <li class="mb-2">✓ Keep your availability status updated</li>
                            </ul>
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
        const newRow = document.createElement('div');
        newRow.className = 'input-group mb-2';
        newRow.innerHTML = `
            <input type="text" class="form-control" name="languages[]" placeholder="e.g., English">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger btn-remove-language">
                    <i class="material-icons">remove</i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Remove Language functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-language')) {
            e.target.closest('.input-group').remove();
        }
    });

    // Add Equipment functionality
    document.getElementById('add-equipment').addEventListener('click', function() {
        const container = document.getElementById('equipment-container');
        const newRow = document.createElement('div');
        newRow.className = 'input-group mb-2';
        newRow.innerHTML = `
            <input type="text" class="form-control" name="equipment_owned[]" placeholder="e.g., Microphone">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger btn-remove-equipment">
                    <i class="material-icons">remove</i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Remove Equipment functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-equipment')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>

<style>
.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
    border-radius: 10px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0 !important;
}

.btn {
    border-radius: 6px;
}

.form-control {
    border-radius: 6px;
}

.custom-control-label::before {
    border-radius: 6px;
}
</style>
@endsection
