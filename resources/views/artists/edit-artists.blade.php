@extends('admin.home')
@section('title', 'Edit Artis')
@section('content')
    <div class="Card">
        <div style="padding: 20px;">
            {{-- <h2>Edit package Artis</h2> --}}
            <form action="{{ route('artists.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="user_id" value="{{ $package->user_id }}">
                {{-- <div class="Gap-bottom">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div> --}}

                {{-- <div class="Gap-bottom">
                    <label for="user_id" class="form-label">Nama Artis</label>
                    {{-- <div class="form-control" id="user_id" name="user_id"> --}}
                    {{-- @foreach ($Artists as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $package->user_id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach --}}
                    {{-- <input type="text" class="form-control" disabled name="user_id" value="{{ $artists->name }}"> --}}
                    {{-- <option value="{{ $package->user_id }}">{{ $package->user->name }}</option> --}}
                    {{-- </div> --}}
                {{-- </div>  --}}

                 <div class="Gap-bottom">
                    <label for="available_dates" class="form-label">Available Dates</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" id="available_dates_input" class="form-control border-start-0"
                            placeholder="Choose Available Dates" readonly>
                    </div>
                    <div id="selected-dates-badge" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                    <!-- Input hidden dihapus, sekarang hanya dibuat dinamis oleh JS -->
                    <small class="text-muted d-block mt-1">
                        Choose date on calendar, Klik (x) to remove date.
                    </small>
                </div>

                <div class="Gap-bottom">
                    <label for="nama_package" class="form-label">Package Name</label>
                    <input type="text" class="form-control" id="package_name" name="package_name"
                        value="{{ $package->package_name }}">
                </div>
                {{-- <div class="Gap-bottom">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description">{{ $package->description }}</textarea>
                </div> --}}
                {{-- <div class="Gap-bottom">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ $package->price }}">
                </div> --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                {{-- <a href="{{ route('packages.index') }}" class="btn btn-secondary">Kembali</a> --}}
            </form>
        </div>
    </div>
    </div>
    </div>
@endsection

<style>
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
// Pass existing dates to JavaScript
window.availableDates = @json($availableDates);

document.addEventListener('DOMContentLoaded', function() {
    let selectedDates = [...window.availableDates]; // Initialize with existing dates
    
    // Initialize flatpickr for date selection
    const datePicker = flatpickr("#available_dates_input", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: selectedDates, // Set existing dates as selected
        onChange: function(selectedDateObjs, dateStr, instance) {
            selectedDates = dateStr.split(', ').filter(date => date.length > 0);
            updateSelectedDatesDisplay();
            updateHiddenInputs();
        }
    });
    
    function updateSelectedDatesDisplay() {
        const container = document.getElementById('selected-dates-badge');
        
        if (selectedDates.length === 0) {
            container.innerHTML = '<span class="text-muted">No dates selected</span>';
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
        
        container.innerHTML = badgesHtml;
    }
    
    function updateHiddenInputs() {
        // Remove existing hidden inputs
        document.querySelectorAll('input[name="available_dates[]"]').forEach(input => input.remove());
        
        // Add new hidden inputs for each selected date
        const form = document.querySelector('form');
        selectedDates.forEach(date => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'available_dates[]';
            hiddenInput.value = date;
            form.appendChild(hiddenInput);
        });
    }
    
    // Make removeDate function global
    window.removeDate = function(dateToRemove) {
        selectedDates = selectedDates.filter(date => date !== dateToRemove);
        datePicker.setDate(selectedDates);
        updateSelectedDatesDisplay();
        updateHiddenInputs();
    };
    
    // Initialize display on page load
    updateSelectedDatesDisplay();
    updateHiddenInputs();
});
</script>

{{-- Include Flatpickr CSS and JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

