@extends('admin.home')
@section('title', 'Create Package')

@section('content')
    <div class="Card">
        <div style="padding: 20px;">
            <h2>Create package</h2>
            <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if(Auth::user()->hasRole('Admin'))
                    <div class="Gap-bottom">
                        <label for="user_id" class="form-label">Artist Name</label>
                        <select name="user_id" id="user_id" class="form-control">
                            @foreach ($Artists as $artist)
                                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    {{-- For Artists, automatically use their own ID --}}
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="Gap-bottom">
                        <label class="form-label">Artist Name</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        <small class="text-muted">You are creating a package for yourself</small>
                    </div>
                @endif

                <div class="Gap-bottom">
                    <label for="country" class="form-label">Country</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="">Select Country</option>
                        @foreach(\App\Helpers\Countries::getCountries() as $countryKey => $countryName)
                            <option value="{{ $countryKey }}" {{ old('country') == $countryKey ? 'selected' : '' }}>{{ $countryName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="Gap-bottom">
                    <label for="region" class="form-label">Region</label>
                    <select name="region" id="region" class="form-control">
                        <option value="">Select Region</option>
                        <!-- Populated by JavaScript based on country selection -->
                    </select>
                </div>

                <div class="Gap-bottom">
                    <label for="image" class="form-label">Main Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Main package image (recommended: 1200x800px)</small>
                </div>

                <div class="Gap-bottom">
                    <label for="photos" class="form-label">Additional Photos</label>
                    <input type="file" name="photos[]" id="photos" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Upload multiple photos (max 5 photos, each max 25MB)</small>
                    <div id="photos-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                </div>

                <div class="Gap-bottom">
                    <label for="video" class="form-label">Video</label>
                    <input type="file" name="video" id="video" class="form-control" accept="video/*">
                    <small class="text-muted">Upload a video showcase (max 50MB, formats: mp4, mov, avi)</small>
                    <div id="video-preview" class="mt-2"></div>
                </div>

                <div class="Gap-bottom">
                    <label for="package_name" class="form-label">Package Name</label>
                    <input type="text" name="package_name" id="package_name" class="form-control">
                </div>

                <div class="Gap-bottom">
                    <label class="form-label">Available Dates</label>
                    <p class="text-muted small mb-3">Click on dates in the calendar below to select/deselect available dates</p>
                    
                    <!-- Hidden input to store selected dates -->
                    <input type="hidden" name="available_dates" id="selected-dates" value="">
                    
                    <!-- Calendar Container -->
                    <div id="calendar-container" class="border rounded p-3 bg-light">
                        <div id="inline-calendar"></div>
                    </div>
                    
                    <!-- Selected Dates Display -->
                    <div class="mt-3">
                        <strong>Selected Dates:</strong>
                        <div id="selected-dates-display" class="mt-2">
                            <span class="text-muted">No dates selected</span>
                        </div>
                    </div>
                </div>

                <div class="Gap-bottom">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="5" class="form-control"></textarea>
                </div>

                <div class="Gap-bottom">
                    <label for="price" class="form-label">Price (€)</label>
                    <div class="input-group">
                        <span class="input-group-text">Price in Euro €</span>
                        {{-- <input type="text" name="price" id="price" class="form-control"> --}}
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create package</button>
            </form>
        </div>
    </div>
@endsection

<style>
#calendar-container {
    max-width: 400px;
}

.flatpickr-calendar {
    position: static !important;
    width: 100% !important;
    box-shadow: none !important;
    border: 1px solid #ddd !important;
}

.flatpickr-day.selected {
    background: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

.flatpickr-day.selected:hover {
    background: #218838 !important;
    border-color: #1e7e34 !important;
}

.selected-date-badge {
    display: inline-block;
    background: #007bff;
    color: white;
    padding: 4px 8px;
    margin: 2px;
    border-radius: 4px;
    font-size: 0.875rem;
    position: relative;
}

.selected-date-badge .remove-date {
    margin-left: 5px;
    cursor: pointer;
    font-weight: bold;
}

.selected-date-badge .remove-date:hover {
    color: #ff6b6b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedDates = [];
    
    // Initialize inline calendar
    const calendar = flatpickr("#inline-calendar", {
        mode: "multiple",
        inline: true,
        dateFormat: "Y-m-d",
        minDate: "today",
        showMonths: 1,
        onChange: function(selectedDateObjs, dateStr, instance) {
            selectedDates = dateStr.split(', ').filter(date => date.length > 0);
            updateSelectedDatesDisplay();
            updateHiddenInput();
        },
        onReady: function(selectedDateObjs, dateStr, instance) {
            // Customize the calendar appearance
            const calendarElement = instance.calendarContainer;
            calendarElement.style.position = 'static';
            calendarElement.style.width = '100%';
        }
    });
    
    function updateSelectedDatesDisplay() {
        const displayContainer = document.getElementById('selected-dates-display');
        
        if (selectedDates.length === 0) {
            displayContainer.innerHTML = '<span class="text-muted">No dates selected</span>';
            return;
        }
        
        const badgesHtml = selectedDates.map(date => {
            const formattedDate = new Date(date).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            return `
                <span class="selected-date-badge">
                    ${formattedDate}
                    <span class="remove-date" onclick="removeDate('${date}')">&times;</span>
                </span>
            `;
        }).join('');
        
        displayContainer.innerHTML = badgesHtml;
    }
    
    function updateHiddenInput() {
        document.getElementById('selected-dates').value = JSON.stringify(selectedDates);
    }
    
    // Make removeDate function global
    window.removeDate = function(dateToRemove) {
        selectedDates = selectedDates.filter(date => date !== dateToRemove);
        
        // Update the flatpickr instance
        calendar.setDate(selectedDates);
        
        updateSelectedDatesDisplay();
        updateHiddenInput();
    };
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (selectedDates.length === 0) {
            e.preventDefault();
            alert('Please select at least one available date.');
            return false;
        }
    });

    // Media preview functionality
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
                    <small class="d-block text-center mt-1">Photo ${index + 1}</small>
                `;
                previewContainer.appendChild(photoDiv);
            };
            reader.readAsDataURL(file);
        });
    });

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
                    <small class="d-block text-center mt-1">${file.name}</small>
                `;
                previewContainer.appendChild(videoDiv);
            };
            reader.readAsDataURL(file);
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

{{-- Include Flatpickr CSS and JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
