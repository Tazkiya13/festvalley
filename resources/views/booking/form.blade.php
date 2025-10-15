@extends('layouts.app')

@section('title', 'Book ' . $package->package_name)

@section('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<!-- Main Booking Form -->
<div class="booking-form-container">
    <!-- Header Section -->
    <div class="booking-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="booking-title">Send Booking Request for {{ $package->package_name }}</h1>
                    <p class="booking-subtitle">by {{ $package->user->name }}</p>
                </div>
                <div class="col-md-6">
                    <div class="package-preview">
                        @if($package->image)
                            <img src="{{ asset('images/default.jpg') }}" data-image="{{ $package->image }}"
                                alt="{{ $package->package_name }}"
                                class="package-image">
                        @else
                            <div class="package-placeholder">
                                <i class="fas fa-music"></i>
                            </div>
                        @endif
                        <div class="package-info">
                            <h4>{{ $package->package_name }}</h4>
                            <p class="price">€ {{ number_format($package->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="container">
        <div class="booking-form-wrapper">
            <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="booking-form">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">

                <!-- Progress Steps -->
                <div class="booking-steps">
                    <div class="step active" data-step="1" role="tablist" aria-label="Booking Progress">
                        <div class="step-number" aria-label="Step 1 of 3">1</div>
                        <div class="step-label">Event Details</div>
                        <div class="step-description">Choose your event date and details</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-number" aria-label="Step 2 of 3">2</div>
                        <div class="step-label">Contact Info</div>
                        <div class="step-description">Your contact information</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-number" aria-label="Step 3 of 3">3</div>
                        <div class="step-label">Confirmation</div>
                        <div class="step-description">Review request to book</div>
                    </div>
                    <div class="progress-line">
                        <div class="progress-fill" style="width: 33.33%"></div>
                    </div>
                </div>

                <!-- Step 1: Event Details -->
                <div class="form-step active" data-step="1">
                    <div class="step-header">
                        <h3>Event Details</h3>
                        <p>Tell us about your event</p>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-enhanced">
                                <label for="available_date_id" class="form-label required-label" aria-required="true">
                                    <i class="fas fa-calendar-alt label-icon"></i>
                                    Select Available Date 
                                    <span class="required" aria-label="required">*</span>
                                </label>
                                <div class="date-picker-wrapper" role="group" aria-labelledby="date-picker-label">
                                    <input type="text" 
                                           id="date_picker" 
                                           class="form-control date-picker-input" 
                                           placeholder="Click to select a date" 
                                           aria-describedby="date-picker-info"
                                           aria-required="true"
                                           readonly>
                                    <input type="hidden" 
                                           name="available_date_id" 
                                           id="available_date_id" 
                                           value="{{ old('available_date_id') }}">
                                    <input type="hidden" 
                                           name="event_date" 
                                           id="event_date" 
                                           value="{{ old('event_date') }}">
                                    <div class="input-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <div class="date-picker-info" id="date-picker-info">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Only available dates are selectable
                                    </small>
                                </div>
                                @error('available_date_id')
                                    <div class="error-message" role="alert" aria-live="polite">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('event_date')
                                    <div class="error-message" role="alert" aria-live="polite">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group form-group-enhanced">
                                <label for="event_location" class="form-label required-label" aria-required="true">
                                    <i class="fas fa-map-marker-alt label-icon"></i>
                                    Event Location 
                                    <span class="required" aria-label="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" 
                                           name="event_location" 
                                           id="event_location" 
                                           class="form-control enhanced-input" 
                                           value="{{ old('event_location') }}" 
                                           placeholder="Enter event venue/location"
                                           aria-describedby="location-help" 
                                           required>
                                    <div class="input-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <div class="field-help" id="location-help">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Include venue name and address for best results
                                    </small>
                                </div>
                                @error('event_location')
                                    <div class="error-message" role="alert" aria-live="polite">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-enhanced">
                        <label for="event_type" class="form-label required-label" aria-required="true">
                            <i class="fas fa-tag label-icon"></i>
                            Event Type 
                            <span class="required" aria-label="required">*</span>
                        </label>
                        <div class="select-wrapper">
                            <select name="event_type" id="event_type" class="form-control enhanced-select" required>
                                <option value="">Select event type</option>
                                <option value="Wedding" {{ old('event_type') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                                <option value="Birthday Party" {{ old('event_type') == 'Birthday Party' ? 'selected' : '' }}>Birthday Party</option>
                                <option value="Corporate Event" {{ old('event_type') == 'Corporate Event' ? 'selected' : '' }}>Corporate Event</option>
                                <option value="Private Party" {{ old('event_type') == 'Private Party' ? 'selected' : '' }}>Private Party</option>
                                <option value="Concert" {{ old('event_type') == 'Concert' ? 'selected' : '' }}>Concert</option>
                                <option value="Festival" {{ old('event_type') == 'Festival' ? 'selected' : '' }}>Festival</option>
                                <option value="Other" {{ old('event_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="select-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        @error('event_type')
                            <div class="error-message" role="alert" aria-live="polite">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="event_description" class="form-label">Event Description</label>
                        <textarea name="event_description" id="event_description" class="form-control" rows="4" 
                                  placeholder="Describe your event, expected number of guests, etc.">{{ old('event_description') }}</textarea>
                        @error('event_description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="special_requirements" class="form-label">Special Requirements</label>
                        <textarea name="special_requirements" id="special_requirements" class="form-control" rows="3" 
                                  placeholder="Any specific songs, equipment needs, or special requests?">{{ old('special_requirements') }}</textarea>
                        @error('special_requirements')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-primary btn-next" data-next="2">Next: Contact Info</button>
                    </div>
                </div>

                <!-- Step 2: Contact Information -->
                <div class="form-step" data-step="2">
                    <div class="step-header">
                        <h3>Contact Information</h3>
                        <p>How can we reach you?</p>
                    </div>

                    @guest
                        <!-- Account Creation Notice -->
                        <div class="account-creation-notice">
                            <div class="notice-card">
                                <div class="notice-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="notice-content">
                                    <h4>Create Your Account</h4>
                                    <p>An account is required to book our services. This helps us manage your bookings and provide better service.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input to indicate account creation -->
                        <input type="hidden" name="user_type" value="create">

                        <div class="contact-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name" class="form-label">Full Name <span class="required">*</span></label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                               value="{{ old('customer_name') }}" placeholder="Enter your full name" required>
                                        @error('customer_name')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_email" class="form-label">Email Address <span class="required">*</span></label>
                                        <input type="email" name="customer_email" id="customer_email" class="form-control" 
                                               value="{{ old('customer_email') }}" placeholder="Enter your email" required>
                                        @error('customer_email')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_phone" class="form-label">Phone Number</label>
                                        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" 
                                               value="{{ old('customer_phone') }}" placeholder="Enter your phone number">
                                        @error('customer_phone')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Account Creation Fields -->
                            <div class="account-creation-fields">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">Password <span class="required">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control guest-required" 
                                                   placeholder="Create a password" minlength="8">
                                            <small class="form-text text-muted">Password must be at least 8 characters long</small>
                                            @error('password')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span class="required">*</span></label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control guest-required" 
                                                   placeholder="Confirm your password">
                                            @error('password_confirmation')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="create_account" id="create_account_hidden" value="1">
                        </div>
                    @else
                        <!-- Logged-in User -->
                        <div class="logged-in-user">
                            <div class="user-info-card">
                                <div class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="user-details">
                                    <h4>{{ Auth::user()->name }}</h4>
                                    <p>{{ Auth::user()->email }}</p>
                                </div>
                            </div>

                            <input type="hidden" name="customer_name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="customer_email" value="{{ Auth::user()->email }}">

                            <div class="form-group">
                                <label for="customer_phone" class="form-label">Phone Number</label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="form-control" 
                                       value="{{ old('customer_phone', Auth::user()->phone ?? '') }}" placeholder="Enter your phone number">
                                @error('customer_phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endguest

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary btn-prev" data-prev="1">Previous</button>
                        <button type="button" class="btn btn-primary btn-next" data-next="3">Next: Review</button>
                    </div>
                </div>

                <!-- Step 3: Confirmation -->
                <div class="form-step" data-step="3">
                    <div class="step-header">
                        <h3>Review Your Booking</h3>
                        <p>Please review your booking details before submitting</p>
                    </div>

                    <div class="booking-summary">
                        <div class="summary-card">
                            <h4>Package Details</h4>
                            <div class="summary-item">
                                <span class="label">Package:</span>
                                <span class="value">{{ $package->package_name }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Artist:</span>
                                <span class="value">{{ $package->user->name }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Price:</span>
                                <span class="value">€ {{ number_format($package->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="summary-card">
                            <h4>Event Details</h4>
                            <div class="summary-item">
                                <span class="label">Event Type:</span>
                                <span class="value" id="summary-event-type">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Event Date:</span>
                                <span class="value" id="summary-event-date">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Location:</span>
                                <span class="value" id="summary-location">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Description:</span>
                                <span class="value" id="summary-description">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Special Requirements:</span>
                                <span class="value" id="summary-requirements">-</span>
                            </div>
                        </div>

                        <div class="summary-card">
                            <h4>Contact Information</h4>
                            <div class="summary-item">
                                <span class="label">Name:</span>
                                <span class="value" id="summary-name">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Email:</span>
                                <span class="value" id="summary-email">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Phone:</span>
                                <span class="value" id="summary-phone">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="terms-acceptance">
                        <label class="checkbox-label">
                            <input type="checkbox" id="accept_terms" required>
                            <span class="checkmark"></span>
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                        </label>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary btn-prev" data-prev="2">Previous</button>
                        <button type="submit" class="btn btn-success btn-submit">Submit Booking Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Base Layout Improvements */
body {
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    background: #f8f9fa;
}

.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Form Controls */
.form-control {
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #2d3748;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.required {
    color: #e53e3e;
}

/* Error Messages */
.error-message {
    color: #e53e3e;
    font-size: 14px;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.error-message::before {
    content: '⚠';
    font-size: 16px;
}

/* Validation Error Styling */
.form-control.error {
    border-color: #e53e3e;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

.validation-error {
    color: #e53e3e;
    font-size: 12px;
    margin-top: 4px;
    font-weight: 500;
}

.validation-error::before {
    content: '⚠ ';
    margin-right: 4px;
}

/* Card Improvements */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 1rem 1.5rem;
    border: none;
}

.card-body {
    padding: 2rem;
}

/* Booking Form Container */
.booking-form-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.booking-header {
    background: white;
    margin-bottom: 2rem;
    padding: 2rem 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.booking-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.booking-subtitle {
    font-size: 1.2rem;
    color: #718096;
    margin: 0;
}

.package-preview {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f7fafc;
    padding: 1rem;
    border-radius: 12px;
}

.package-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
}

.package-placeholder {
    width: 80px;
    height: 80px;
    background: #e2e8f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #a0aec0;
}

.package-info h4 {
    margin: 0;
    font-size: 1.1rem;
    color: #2d3748;
}

.package-info .price {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #667eea;
}

.booking-form-wrapper {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 0 auto;
}

/* Progress Steps Container - Properly Arranged */
.booking-steps {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 30px auto 15px;
    padding: 20px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    position: relative;
    max-width: 700px;
    width: 100%;
}

/* Individual Step Styling */
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 3;
    flex: 1;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    min-height: 140px;
}

/* Step Number Circle */
.step-number {
    left: unset;
    transform: unset;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f8fafc;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 20px;
    margin-bottom: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 3px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

/* Active Step Styling */
.step.active .step-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    /* transform: scale(1.15); */
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.35);
}

/* Step Labels */
.step-label {
    font-size: 16px;
    color: #64748b;
    text-align: center;
    font-weight: 600;
    margin-bottom: 4px;
    transition: all 0.3s ease;
    line-height: 1.2;
}

.step-description {
    font-size: 12px;
    color: #94a3b8;
    text-align: center;
    margin-top: 4px;
    line-height: 1.3;
    max-width: 120px;
}

.step.active .step-label {
    color: #1e293b;
    font-weight: 700;
    font-size: 17px;
}

.step.active .step-description {
    color: #475569;
}

/* Form Step Content */
.form-step {
    display: none;
    animation: fadeOut 0.3s ease-out;
    margin-top: 0px;
    padding-top: 0px;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

/* Step Header Styling */
.step-header {
    text-align: center;
    margin-bottom: 0.25rem;
    padding-bottom: 0.15rem;
    border-bottom: 2px solid #e2e8f0;
}

.step-header h3 {
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    font-weight: 700;
}

.step-header p {
    color: #718096;
    margin: 0;
    font-size: 1rem;
}

/* Form Groups */

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.required {
    color: #e53e3e;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.error-message {
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Account Creation Notice Styles */
.account-creation-notice {
    margin-bottom: 2rem;
}

.notice-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.notice-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.notice-content h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.125rem;
    font-weight: 600;
}

.notice-content p {
    margin: 0;
    font-size: 0.875rem;
    opacity: 0.9;
    line-height: 1.5;
}

.logged-in-user .user-info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f7fafc;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
}

.user-avatar {
    font-size: 3rem;
    color: #667eea;
}

.user-details h4 {
    margin: 0;
    color: #2d3748;
}

.user-details p {
    margin: 0;
    color: #718096;
}

.booking-summary {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: #f7fafc;
    padding: 1.5rem;
    border-radius: 12px;
}

.summary-card h4 {
    margin: 0 0 1rem 0;
    color: #2d3748;
    font-size: 1.1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.summary-item .label {
    color: #718096;
}

.summary-item .value {
    color: #2d3748;
    font-weight: 500;
    max-width: 60%;
    text-align: right;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.terms-acceptance {
    margin-bottom: 2rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: #4a5568;
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
}

.step-actions {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

.btn-success {
    background: #48bb78;
    color: white;
}

.btn-success:hover {
    background: #38a169;
}

@media (max-width: 768px) {
    .booking-form-wrapper {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    .notice-card {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
        padding: 1.25rem;
    }
    
    .notice-icon {
        font-size: 1.75rem;
    }
    
    .step-actions {
        flex-direction: column;
    }
    
    .booking-title {
        font-size: 2rem;
    }
    
    .package-preview {
        margin-top: 1rem;
    }
}

/* Additional Mobile Improvements */
@media (max-width: 576px) {
    .booking-title {
        font-size: 1.5rem;
    }
    
    .booking-form-wrapper {
        padding: 1rem;
        margin: 0 0.5rem;
    }
    
    .form-control {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .package-info h4 {
        font-size: 1rem;
    }
    
    .package-info .price {
        font-size: 1.1rem;
    }
    
    .date-picker-input {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
}

/* Loading State Improvements */
.form-control.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.form-control.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* Focus Improvements */
*:focus {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}

.form-control:focus,
.date-picker-input:focus {
    outline: none; /* Custom focus already handled */
}

/* Contact Form Styling */
.user-type-selector {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.user-type-option {
    flex: 1;
    min-width: 200px;
}

.user-type-option input[type="radio"] {
    display: none;
}

.user-type-label {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    gap: 1rem;
}

.user-type-label:hover {
    border-color: #667eea;
    background: #f7fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.user-type-option input[type="radio"]:checked + .user-type-label {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.user-type-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #667eea;
    transition: all 0.3s ease;
}

.user-type-option input[type="radio"]:checked + .user-type-label .user-type-icon {
    background: white;
    color: #667eea;
}

.user-type-content h4 {
    margin: 0 0 0.25rem 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.user-type-content p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.8;
}

.contact-form {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
    .notice-card {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
        padding: 1.25rem;
    }
    
    .notice-icon {
        font-size: 1.75rem;
    }
}
</style>

<script>
// Helper function to get proper image URL
function getImageUrl(image, defaultImage = '{{ asset("images/default.jpg") }}') {
    if (!image) return defaultImage;

    // If image already contains http/https, use as-is
    if (image.includes('http://') || image.includes('https://')) {
        return image;
    }

    // Otherwise, prepend storage path
    return '{{ asset("storage/") }}' + '/' + image;
}

// Apply proper image URLs after page load
document.addEventListener('DOMContentLoaded', function() {
    // Update all images with data-image attribute
    document.querySelectorAll('img[data-image]').forEach(img => {
        const image = img.getAttribute('data-image');
        img.src = getImageUrl(image);
    });

    // Update background images
    document.querySelectorAll('[data-bg-image]').forEach(element => {
        const image = element.getAttribute('data-bg-image');
        if (image) {
            const imageUrl = getImageUrl(image);
            element.style.backgroundImage = `url('${imageUrl}')`;
        }
    });
});

// Global showStep function for accessibility
function showStep(stepNumber) {
    const steps = document.querySelectorAll('.form-step');
    const stepNumbers = document.querySelectorAll('.step');
    
    console.log('showStep called with:', stepNumber);
    
    // Hide all steps
    steps.forEach(step => {
        step.classList.remove('active');
        console.log('Removed active from step:', step.dataset.step);
    });
    
    stepNumbers.forEach(step => {
        step.classList.remove('active');
    });
    
    // Show current step
    const targetStep = document.querySelector(`.form-step[data-step="${stepNumber}"]`);
    const targetStepIndicator = document.querySelector(`.booking-steps .step[data-step="${stepNumber}"]`);
    
    if (targetStep) {
        targetStep.classList.add('active');
        console.log('Added active to step:', stepNumber);
    } else {
        console.error('Target step not found:', stepNumber);
        console.log('Available steps:', document.querySelectorAll('.form-step'));
    }
    
    if (targetStepIndicator) {
        targetStepIndicator.classList.add('active');
        console.log('Added active to step indicator:', stepNumber);
    } else {
        console.error('Target step indicator not found:', stepNumber);
    }
    
    // Update progress bar
    const progressContainer = document.querySelector('.booking-steps');
    if (progressContainer) {
        progressContainer.className = 'booking-steps step-' + stepNumber;
    }
    
    // Update progress fill width based on current step
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        let progressWidth;
        switch (stepNumber) {
            case 1:
                progressWidth = '33.33%';
                break;
            case 2:
                progressWidth = '66.66%';
                break;
            case 3:
                progressWidth = '100%';
                break;
            default:
                progressWidth = '33.33%';
        }
        progressFill.style.width = progressWidth;
        console.log('Updated progress bar to:', progressWidth);
    }
    
    // Scroll to top
    const formWrapper = document.querySelector('.booking-form-wrapper');
    if (formWrapper) {
        formWrapper.scrollIntoView({ behavior: 'smooth' });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const steps = document.querySelectorAll('.form-step');
    const stepNumbers = document.querySelectorAll('.step');
    
    // Handle step navigation
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Next button clicked, target step:', this.dataset.next);
            const nextStep = parseInt(this.dataset.next);
            if (validateCurrentStep()) {
                console.log('Validation passed, showing step:', nextStep);
                showStep(nextStep);
            } else {
                console.log('Validation failed');
            }
        });
    });
    
    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Previous button clicked, target step:', this.dataset.prev);
            const prevStep = parseInt(this.dataset.prev);
            showStep(prevStep);
        });
    });
    
    // Handle user type selection - No longer needed as account creation is required
    // (code removed as guest option is no longer available)
    
    // Update summary in real-time
    const summaryFields = {
        'event_type': 'summary-event-type',
        'event_date': 'summary-event-date',
        'event_location': 'summary-location',
        'event_description': 'summary-description',
        'special_requirements': 'summary-requirements',
        'customer_name': 'summary-name',
        'customer_email': 'summary-email',
        'customer_phone': 'summary-phone'
    };
    
    Object.keys(summaryFields).forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('input', function() {
                const summaryElement = document.getElementById(summaryFields[fieldName]);
                if (summaryElement) {
                    summaryElement.textContent = this.value || '-';
                }
            });
        }
    });
    
    // Initialize summary for logged-in users
    @auth
        document.getElementById('summary-name').textContent = '{{ Auth::user()->name }}';
        document.getElementById('summary-email').textContent = '{{ Auth::user()->email }}';
        // Initialize phone if it has a value
        const phoneField = document.getElementById('customer_phone');
        if (phoneField && phoneField.value) {
            document.getElementById('summary-phone').textContent = phoneField.value;
        }
        // Add event listener for phone field updates
        if (phoneField) {
            phoneField.addEventListener('input', function() {
                document.getElementById('summary-phone').textContent = this.value || '-';
            });
        }
    @endauth
    
    // Real-time password validation
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    if (passwordField && confirmPasswordField) {
        // Validate password strength
        passwordField.addEventListener('input', function() {
            const password = this.value;
            this.classList.remove('error');
            
            // Remove any existing error messages
            const errorMsg = this.parentNode.querySelector('.validation-error');
            if (errorMsg) {
                errorMsg.remove();
            }
            
            if (password.length > 0 && password.length < 8) {
                this.classList.add('error');
                const errorMsg = document.createElement('div');
                errorMsg.className = 'validation-error';
                errorMsg.textContent = 'Password must be at least 8 characters long';
                this.parentNode.appendChild(errorMsg);
            }
        });
        
        // Validate password confirmation
        confirmPasswordField.addEventListener('input', function() {
            const confirmPassword = this.value;
            const password = passwordField.value;
            
            this.classList.remove('error');
            
            // Remove any existing error messages
            const errorMsg = this.parentNode.querySelector('.validation-error');
            if (errorMsg) {
                errorMsg.remove();
            }
            
            if (confirmPassword.length > 0 && password !== confirmPassword) {
                this.classList.add('error');
                const errorMsg = document.createElement('div');
                errorMsg.className = 'validation-error';
                errorMsg.textContent = 'Passwords do not match';
                this.parentNode.appendChild(errorMsg);
            }
        });
    }
    
    function validateCurrentStep() {
        const currentStep = document.querySelector('.form-step.active');
        if (!currentStep) {
            console.error('No active step found');
            return false;
        }
        
        console.log('Validating step:', currentStep.dataset.step);
        
        let isValid = true;
        
        // Check regular required fields
        const requiredFields = currentStep.querySelectorAll('[required]');
        console.log('Required fields found:', requiredFields.length);
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                console.log('Required field is empty:', field.name || field.id);
                field.focus();
                
                // Add error styling
                field.classList.add('error');
                
                // Show error message if it doesn't exist
                let errorMsg = field.parentNode.querySelector('.validation-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error';
                    errorMsg.textContent = 'This field is required';
                    field.parentNode.appendChild(errorMsg);
                }
                
                isValid = false;
                return false;
            } else {
                // Remove error styling
                field.classList.remove('error');
                const errorMsg = field.parentNode.querySelector('.validation-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        // Check guest-required fields (for non-logged-in users)
        const guestRequiredFields = currentStep.querySelectorAll('.guest-required');
        guestRequiredFields.forEach(field => {
            if (!field.value.trim()) {
                console.log('Guest required field is empty:', field.name || field.id);
                field.focus();
                
                // Add error styling
                field.classList.add('error');
                
                // Show error message if it doesn't exist
                let errorMsg = field.parentNode.querySelector('.validation-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error';
                    errorMsg.textContent = 'This field is required';
                    field.parentNode.appendChild(errorMsg);
                }
                
                isValid = false;
                return false;
            } else {
                // Remove error styling
                field.classList.remove('error');
                const errorMsg = field.parentNode.querySelector('.validation-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        // Validate password confirmation if passwords are provided
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        if (passwordField && confirmPasswordField && passwordField.value && confirmPasswordField.value) {
            if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordField.focus();
                confirmPasswordField.classList.add('error');
                
                let errorMsg = confirmPasswordField.parentNode.querySelector('.validation-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error';
                    errorMsg.textContent = 'Passwords do not match';
                    confirmPasswordField.parentNode.appendChild(errorMsg);
                }
                
                isValid = false;
            }
        }
        
        console.log('Validation result:', isValid);
        return isValid;
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        // Check terms acceptance
        if (!document.getElementById('accept_terms').checked) {
            e.preventDefault();
            alert('Please accept the terms and conditions to proceed.');
            return false;
        }
        
        // Validate all guest-required fields before submission
        const guestRequiredFields = document.querySelectorAll('.guest-required');
        let allValid = true;
        
        guestRequiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                
                // Show error message if it doesn't exist
                let errorMsg = field.parentNode.querySelector('.validation-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error';
                    errorMsg.textContent = 'This field is required';
                    field.parentNode.appendChild(errorMsg);
                }
                
                allValid = false;
            }
        });
        
        // Validate password match
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        if (passwordField && confirmPasswordField) {
            if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordField.classList.add('error');
                
                let errorMsg = confirmPasswordField.parentNode.querySelector('.validation-error');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'validation-error';
                    errorMsg.textContent = 'Passwords do not match';
                    confirmPasswordField.parentNode.appendChild(errorMsg);
                }
                
                allValid = false;
            }
        }
        
        if (!allValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.guest-required.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return false;
        }
    });
});
</script>

<!-- Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Date picker initialization
document.addEventListener('DOMContentLoaded', function() {
    // Get available dates from PHP
    const availableDates = @json($package->availableDates->pluck('date')->map(function($date) {
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    })->values());
    
    const availableDateObjects = @json($package->availableDates->mapWithKeys(function($item) {
        return [\Carbon\Carbon::parse($item->date)->format('Y-m-d') => $item->id];
    }));
    
    // Initialize Flatpickr
    const datePicker = flatpickr("#date_picker", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enable: availableDates,
        locale: {
            firstDayOfWeek: 1 // Monday
        },
        onReady: function(selectedDates, dateStr, instance) {
            // Add custom styling to available dates
            instance.calendarContainer.classList.add('custom-calendar');
        },
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const selectedDate = dateStr;
                
                // Set the hidden fields
                document.getElementById('available_date_id').value = availableDateObjects[selectedDate] || '';
                document.getElementById('event_date').value = selectedDate;
                
                // Ensure the date picker input shows the selected date
                instance.input.value = formatDate(selectedDate);
                
                // Also update placeholder to ensure visibility
                instance.input.setAttribute('data-selected', selectedDate);
                instance.input.style.color = '#333'; // Ensure text is visible
                
                // Update the summary
                const summaryElement = document.getElementById('summary-event-date');
                if (summaryElement) {
                    summaryElement.textContent = formatDate(selectedDate);
                }
                
                // Show visual confirmation
                showDateConfirmation(selectedDate);
                
                // Remove any existing error styling
                instance.input.classList.remove('error');
                instance.input.classList.add('date-selected');
                const errorMessage = instance.input.parentNode.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
                
                console.log(`Date selected: ${selectedDate}, Date ID: ${availableDateObjects[selectedDate]}`);
            }
        },
        onOpen: function(selectedDates, dateStr, instance) {
            // Add helpful text
            setTimeout(() => {
                const calendar = instance.calendarContainer;
                if (!calendar.querySelector('.calendar-help')) {
                    const helpText = document.createElement('div');
                    helpText.className = 'calendar-help';
                    helpText.innerHTML = '<small><i class="fas fa-info-circle"></i> Only highlighted dates are available for booking</small>';
                    calendar.appendChild(helpText);
                }
            }, 10);
        }
    });
    
    // Helper function to format date for display
    function formatDate(dateString) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }
    
    // Show visual confirmation when date is selected
    function showDateConfirmation(selectedDate) {
        const datePickerWrapper = document.querySelector('.date-picker-wrapper');
        if (datePickerWrapper) {
            // Remove existing confirmation
            const existingConfirmation = datePickerWrapper.querySelector('.date-confirmation');
            if (existingConfirmation) {
                existingConfirmation.remove();
            }
            
            // Add new confirmation
            const confirmation = document.createElement('div');
            confirmation.className = 'date-confirmation';
            confirmation.innerHTML = `
                <small class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    Selected: ${formatDate(selectedDate)}
                </small>
            `;
            datePickerWrapper.appendChild(confirmation);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                if (confirmation.parentNode) {
                    confirmation.remove();
                }
            }, 3000);
        }
    }
    
    // Pre-fill if old value exists
    @if(old('event_date'))
        datePicker.setDate('{{ old('event_date') }}');
    @endif
});
</script>

<style>
/* Enhanced Date Picker Styles - Properly Arranged */
.date-picker-wrapper {
    position: relative;
    margin-bottom: 8px;
}

.date-picker-input {
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 60px 16px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    color: #374151 !important;
    width: 100%;
    font-weight: 500;
    line-height: 1.5;
    min-height: 56px;
    display: flex;
    align-items: center;
}

.date-picker-input:focus,
.date-picker-input:hover {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
    outline: none;
    transform: translateY(-1px);
}

.date-picker-input.date-selected {
    border-color: #10b981;
    background: linear-gradient(135deg, #f0fff4 0%, #ecfdf5 100%);
    font-weight: 600;
    color: #047857 !important;
}

.date-confirmation {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 10;
    margin-top: 5px;
    padding: 8px 12px;
    background: white;
    border: 1px solid #28a745;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.date-picker-input::before {
    content: '\f073';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}

.date-picker-info {
    margin-top: 8px;
}

.date-picker-info small {
    color: #6c757d;
    font-size: 14px;
}

/* Progress Steps - Unified Clean Implementation */

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 3;
    flex: 1;
    transition: all 0.3s ease;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 12px;
    transition: all 0.4s ease;
    border: 3px solid transparent;
}

.step.active .step-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    border-color: rgba(255,255,255,0.2);
}

.step.completed .step-number {
    background: #28a745;
    color: white;
}

.step.completed .step-number::before {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
}

.step-label {
    font-weight: 600;
    color: #495057;
    font-size: 16px;
    margin-bottom: 6px;
    transition: color 0.3s ease;
}

.step.active .step-label {
    color: #667eea;
}

.step-description {
    font-size: 12px;
    color: #6c757d;
    line-height: 1.4;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.step.active .step-description {
    color: #495057;
    opacity: 1;
}

/* Progress Line Connecting Steps */
.progress-line {
    position: absolute;
    top: 35px;
    left: 80px;
    right: 80px;
    height: 4px;
    background: #e2e8f0;
    border-radius: 2px;
    z-index: 1;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 100%);
}

/* Enhanced Form Groups */
.form-group-enhanced {
    margin-bottom: 5px;
    position: relative;
}

.form-group-enhanced:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

.required-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.label-icon {
    color: #667eea;
    font-size: 16px;
}

.required {
    color: #e74c3c;
    font-weight: 700;
}

/* Input Wrapper Enhancement */
.input-wrapper {
    position: relative;
}

.enhanced-input {
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px 16px 50px;
    font-size: 16px;
    color: #374151;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    font-weight: 500;
    line-height: 1.5;
    min-height: 56px;
}

.enhanced-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
    outline: none;
    transform: translateY(-1px);
}

.enhanced-input:valid {
    border-color: #28a745;
}

/* Default input icon (for location input) */
.input-wrapper .input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
    pointer-events: none;
    transition: color 0.3s ease;
}

/* Date picker specific icon positioning */
.date-picker-wrapper .input-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 18px;
    pointer-events: none;
    transition: color 0.3s ease;
    z-index: 2;
}

/* Location input with left icon */
.input-wrapper .enhanced-input {
    padding-left: 50px;
    padding-right: 20px;
}

.input-wrapper .enhanced-input:focus + .input-icon {
    color: #667eea;
}

.input-wrapper .enhanced-input:valid + .input-icon {
    color: #28a745;
}

/* Date picker hover effects */
.date-picker-wrapper:hover .input-icon {
    color: #5a67d8;
}

.date-picker-input:focus + .input-icon {
    color: #5a67d8;
}

/* Enhanced Select Styling */
.select-wrapper {
    position: relative;
    margin-bottom: 8px;
}

.enhanced-select {
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 50px 16px 20px;
    font-size: 16px;
    color: #374151;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    font-weight: 500;
    line-height: 1.5;
    min-height: 56px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: none;
    cursor: pointer;
}

.enhanced-select:focus,
.enhanced-select:hover {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
    outline: none;
    transform: translateY(-1px);
}

.select-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 14px;
    pointer-events: none;
    z-index: 2;
    transition: all 0.3s ease;
}

.select-wrapper:hover .select-icon {
    color: #5a67d8;
    transform: translateY(-50%) rotate(180deg);
}

.enhanced-select:focus + .select-icon {
    color: #5a67d8;
}

/* Select option styling */
.enhanced-select option {
    background: #ffffff;
    color: #374151;
    padding: 8px 12px;
}

/* Field Help Text */
.field-help {
    margin-top: 8px;
    opacity: 0.9;
}

.field-help small {
    color: #6c757d;
    font-size: 13px;
    line-height: 1.4;
}

/* Enhanced Error Messages */
.error-message {
    margin-top: 8px;
    padding: 10px 12px;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    color: #e53e3e;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideInDown 0.3s ease;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Success Messages */
.success-message {
    margin-top: 8px;
    padding: 10px 12px;
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    border-radius: 8px;
    color: #2f855a;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideInDown 0.3s ease;
}

/* Button Enhancements */
.btn-next,
.btn-prev {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-next:hover,
.btn-prev:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-next::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-next:hover::before {
    left: 100%;
}

/* Mobile Responsiveness - Enhanced */
@media (max-width: 768px) {
    .booking-steps {
        padding: 25px 15px;
        margin: 25px auto 40px;
        max-width: 100%;
    }
    
    .progress-line {
        top: 50px;
        left: 60px;
        right: 60px;
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        font-size: 18px;
    }
    
    .step-label {
        font-size: 14px;
        font-weight: 600;
    }
    
    .step-description {
        font-size: 11px;
        opacity: 0.8;
        max-width: 100px;
    }
    
    .enhanced-input {
        font-size: 16px; /* Prevents zoom on iOS */
        padding: 14px 16px 14px 45px;
        border-radius: 10px;
    }
    
    .date-picker-input {
        padding: 14px 45px 14px 16px;
        font-size: 16px;
        border-radius: 10px;
        min-height: 52px;
    }

    .form-group-enhanced {
        margin-bottom: 20px;
    }

    .booking-form-wrapper {
        padding: 20px;
    }
}

/* Loading States */
.form-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.6;
}

.form-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #667eea;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Focus Management */
.form-step {
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.4s ease;
}

.form-step.active {
    opacity: 1;
    transform: translateX(0);
}

/* Accessibility Improvements */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .enhanced-input {
        border-width: 3px;
    }
    
    .step-number {
        border-width: 2px;
        border-color: currentColor;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
.custom-calendar .flatpickr-calendar {
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.custom-calendar .flatpickr-months {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
}

.custom-calendar .flatpickr-current-month .flatpickr-monthDropdown-months,
.custom-calendar .flatpickr-current-month .numInputWrapper {
    color: white;
}

.custom-calendar .flatpickr-prev-month,
.custom-calendar .flatpickr-next-month {
    color: white;
    fill: white;
}

.custom-calendar .flatpickr-prev-month:hover,
.custom-calendar .flatpickr-next-month:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.custom-calendar .flatpickr-weekdays {
    background: #f8f9fa;
    padding: 10px 0;
}

.custom-calendar .flatpickr-weekday {
    color: #495057;
    font-weight: 600;
    font-size: 13px;
}

.custom-calendar .flatpickr-days {
    padding: 10px;
}

.custom-calendar .flatpickr-day {
    border-radius: 8px;
    margin: 2px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.custom-calendar .flatpickr-day:hover {
    background: #667eea;
    color: white;
    transform: scale(1.05);
}

.custom-calendar .flatpickr-day.selected {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    transform: scale(1.05);
}

.custom-calendar .flatpickr-day.disabled {
    color: #dee2e6;
    background: transparent;
    cursor: not-allowed;
}

.custom-calendar .flatpickr-day.disabled:hover {
    background: transparent;
    transform: none;
    color: #dee2e6;
}

/* Available dates styling */
.custom-calendar .flatpickr-day.flatpickr-enabled {
    background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
    color: #28a745;
    font-weight: 600;
    border: 2px solid #d4edda;
}

.custom-calendar .flatpickr-day.flatpickr-enabled:hover {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-color: #28a745;
}

.calendar-help {
    background: #f8f9fa;
    padding: 10px 15px;
    border-top: 1px solid #dee2e6;
    text-align: center;
    color: #6c757d;
}

.calendar-help i {
    color: #667eea;
    margin-right: 5px;
}

/* Error state */
.date-picker-input.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

/* Loading state */
.date-picker-input.loading {
    position: relative;
    pointer-events: none;
}

.date-picker-input.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* Mobile responsive */
@media (max-width: 768px) {
    .custom-calendar .flatpickr-calendar {
        font-size: 14px;
    }
    
    .custom-calendar .flatpickr-day {
        height: 35px;
        line-height: 35px;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .date-picker-input {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0 !important;
    }
    
    body.dark-theme .custom-calendar .flatpickr-calendar {
        background: #2d3748;
        color: #e2e8f0;
    }
    
    .custom-calendar .flatpickr-weekdays {
        background: #4a5568;
    }
    
    .custom-calendar .flatpickr-day.flatpickr-enabled {
        background: rgba(72, 187, 120, 0.2);
        border-color: #48bb78;
    }
}
</style>
@endsection
