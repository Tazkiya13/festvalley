@extends('admin.home')
@section('title', 'Edit package')
@section('content')
    <div class="Card">
        <div style="padding: 20px;">
            {{-- <h2>Edit package Artis</h2> --}}
            <form action="{{ route('packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="user_id" value="{{ $package->user_id }}">
                
                <div class="Gap-bottom">
                    <label for="image" class="form-label">Main Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @if ($package->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $package->image) }}" alt="Current package image" width="150" height="100" style="object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                            <small class="d-block text-muted">Current main image</small>
                        </div>
                    @endif
                </div>

                <div class="Gap-bottom">
                    <label for="photos" class="form-label">Additional Photos</label>
                    <input type="file" name="photos[]" id="photos" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Upload multiple photos (max 5 photos, each max 25MB)</small>
                    
                    @if ($package->photos && count($package->photos) > 0)
                        <div class="mt-2">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($package->photos as $index => $photo)
                                    <div class="existing-photo-item">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Photo {{ $index + 1 }}" width="100" height="100" style="object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                                        <button type="button" class="btn btn-danger btn-sm mt-1 delete-photo" data-photo="{{ $photo }}" style="font-size: 10px; padding: 2px 6px;">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <small class="d-block text-muted mt-1">Current additional photos</small>
                        </div>
                    @endif
                    <div id="photos-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <div class="Gap-bottom">
                    <label for="video" class="form-label">Video</label>
                    <input type="file" name="video" id="video" class="form-control" accept="video/*">
                    <small class="text-muted">Upload a video showcase (max 50MB, formats: mp4, mov, avi)</small>
                    
                    @if ($package->video)
                        <div class="mt-2">
                            <video width="200" height="150" controls style="border-radius: 8px; border: 2px solid #ddd;">
                                <source src="{{ asset('storage/' . $package->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <small class="d-block text-muted mt-1">Current video</small>
                            <button type="button" class="btn btn-danger btn-sm mt-1" id="delete-video">Remove Video</button>
                        </div>
                    @endif
                    <div id="video-preview" class="mt-2"></div>
                </div>

                <div class="Gap-bottom">
                    <label for="user_id" class="form-label">Artist Name</label>
                    {{-- <div class="form-control" id="user_id" name="user_id"> --}}
                    {{-- @foreach ($Artists as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $package->user_id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach --}}
                    <input type="text" class="form-control" disabled name="user_id" value="{{ $artists->name }}">
                    {{-- <option value="{{ $package->user_id }}">{{ $package->user->name }}</option> --}}
                    {{-- </div> --}}
                </div>

                <div class="Gap-bottom">
                    <label for="available_dates" class="form-label">Available Dates</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" id="available_dates_input" class="form-control border-start-0"
                            placeholder="Select Available Dates" readonly>
                    </div>
                    <div id="selected-dates-badge" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                    <!-- Input hidden dihapus, sekarang hanya dibuat dinamis oleh JS -->
                    <small class="text-muted d-block mt-1">
                        Select dates from the calendar. Click the cross (x) to remove a date.
                    </small>
                </div>

                <div class="Gap-bottom">
                    <label for="nama_package" class="form-label">Package Name</label>
                    <input type="text" class="form-control" id="package_name" name="package_name"
                        value="{{ $package->package_name }}">
                </div>

                <!-- Location Information -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="Gap-bottom">
                            <label for="country" class="form-label">Country</label>
                            <select name="country" id="country" class="form-control" required>
                                <option value="">Select Country</option>
                                @foreach(\App\Helpers\Countries::getCountries() as $countryKey => $countryName)
                                    <option value="{{ $countryKey }}" {{ old('country', $package->country) == $countryKey ? 'selected' : '' }}>{{ $countryName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="Gap-bottom">
                            <label for="region" class="form-label">Region</label>
                            <select name="region" id="region" class="form-control">
                                <option value="">Select Region</option>
                                <!-- Populated by JavaScript based on country selection -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="Gap-bottom">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ $package->description }}</textarea>
                </div>
                <div class="Gap-bottom">
                    <label for="price" class="form-label">Price (€)</label>
                    <div class="input-group">
                        <span class="input-group-text">Enter Price in Euro €</span>
                        {{-- <input type="text" name="price" id="price" class="form-control"> --}}
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="0.00" value="{{ $package->price }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                {{-- <a href="{{ route('packages.index') }}" class="btn btn-secondary">Kembali</a> --}}
            </form>
        </div>
    </div>
    </div>
    </div>
@endsection

<script>
    window.availableDates = @json($availableDates);

    // Media preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Photos preview
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('photos-preview');
            previewContainer.innerHTML = '';

            if (files.length > 5) {
                alert('Maximum 5 photos allowed');
                e.target.value = '';
                return;
            }

            Array.from(files).forEach((file, index) => {
                if (file.size > 25 * 1024 * 1024) { // 25MB limit
                    alert(`Photo ${index + 1} is too large. Maximum size is 25MB.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'photo-preview-item';
                    photoDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Photo ${index + 1}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                        <small class="d-block text-center mt-1">New Photo ${index + 1}</small>
                    `;
                    previewContainer.appendChild(photoDiv);
                };
                reader.readAsDataURL(file);
            });
        });

        // Video preview
        document.getElementById('video').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('video-preview');
            previewContainer.innerHTML = '';

            if (file) {
                if (file.size > 50 * 1024 * 1024) { // 50MB limit
                    alert('Video is too large. Maximum size is 50MB.');
                    e.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const videoDiv = document.createElement('div');
                    videoDiv.className = 'video-preview-item';
                    videoDiv.innerHTML = `
                        <video width="200" height="150" controls style="border-radius: 8px; border: 2px solid #ddd;">
                            <source src="${e.target.result}" type="${file.type}">
                            Your browser does not support the video tag.
                        </video>
                        <small class="d-block text-center mt-1">New: ${file.name}</small>
                    `;
                    previewContainer.appendChild(videoDiv);
                };
                reader.readAsDataURL(file);
            }
        });

        // Delete existing photos
        document.querySelectorAll('.delete-photo').forEach(function(button) {
            button.addEventListener('click', function() {
                const photo = this.getAttribute('data-photo');
                if (confirm('Are you sure you want to delete this photo?')) {
                    // Add hidden input to mark photo for deletion
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'delete_photos[]';
                    hiddenInput.value = photo;
                    document.querySelector('form').appendChild(hiddenInput);
                    
                    // Hide the photo
                    this.closest('.existing-photo-item').style.display = 'none';
                }
            });
        });

        // Delete existing video
        const deleteVideoBtn = document.getElementById('delete-video');
        if (deleteVideoBtn) {
            deleteVideoBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this video?')) {
                    // Add hidden input to mark video for deletion
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'delete_video';
                    hiddenInput.value = '1';
                    document.querySelector('form').appendChild(hiddenInput);
                    
                    // Hide the video
                    this.closest('div').style.display = 'none';
                }
            });
        }

        // Country-Region functionality
        const regions = {!! \App\Helpers\Countries::getRegionsAsJson() !!};
        const currentRegion = '{{ old("region", $package->region ?? "") }}';
        
        function updateRegions() {
            const countrySelect = document.getElementById('country');
            const regionSelect = document.getElementById('region');
            const selectedCountry = countrySelect.value;
            
            // Clear existing options
            regionSelect.innerHTML = '<option value="">Select Region</option>';
            
            if (selectedCountry && regions[selectedCountry]) {
                regions[selectedCountry].forEach(function(regionName) {
                    const option = document.createElement('option');
                    option.value = regionName;
                    option.textContent = regionName;
                    if (regionName === currentRegion) {
                        option.selected = true;
                    }
                    regionSelect.appendChild(option);
                });
            }
        }
        
        // Initialize regions on page load
        updateRegions();
        
        // Update regions when country changes
        document.getElementById('country').addEventListener('change', updateRegions);
    });
</script>
{{-- flatpickr menggunakan flatpicker-package-edit.js --}}
