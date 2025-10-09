/**
 * FestValley - Booking System
 * Multi-step booking form and package management
 */

const BookingSystem = {
    state: {
        currentStep: 1,
        totalSteps: 3,
        formData: {},
        packageData: null,
        availableDates: [],
        selectedDate: null,
        isSubmitting: false
    },
    
    // Initialize booking system
    init() {
        this.setupEventListeners();
        this.initializeForm();
        this.loadPackageData();
    },
    
    // Setup event listeners
    setupEventListeners() {
        // Step navigation
        document.addEventListener('click', (e) => {
            if (e.target.hasAttribute('data-step-next')) {
                e.preventDefault();
                this.nextStep();
            }
            
            if (e.target.hasAttribute('data-step-prev')) {
                e.preventDefault();
                this.prevStep();
            }
            
            if (e.target.hasAttribute('data-step-goto')) {
                e.preventDefault();
                const step = parseInt(e.target.getAttribute('data-step-goto'));
                this.goToStep(step);
            }
        });
        
        // Form validation on input
        document.addEventListener('input', (e) => {
            if (e.target.closest('.booking-form')) {
                this.validateField(e.target);
                this.updateFormData();
            }
        });
        
        // Date selection
        document.addEventListener('change', (e) => {
            if (e.target.hasAttribute('data-date-select')) {
                this.handleDateSelection(e.target.value);
            }
        });
        
        // File uploads
        document.addEventListener('change', (e) => {
            if (e.target.type === 'file' && e.target.hasAttribute('data-file-upload')) {
                this.handleFileUpload(e.target);
            }
        });
        
        // Final form submission
        const bookingForm = document.getElementById('booking-form');
        if (bookingForm) {
            bookingForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitBooking();
            });
        }
    },
    
    // Initialize booking form
    initializeForm() {
        this.createBookingForm();
        this.initializeDatePicker();
        this.initializeProgressIndicator();
    },
    
    // Create booking form HTML
    createBookingForm() {
        const formContainer = document.getElementById('booking-form-container');
        if (!formContainer) return;
        
        const formHtml = `
            <div class="booking-form-container">
                <div class="form-progress mb-4">
                    <div class="progress">
                        <div class="progress-bar progress-bar-custom" id="progress-bar" 
                             style="width: 33.33%" role="progressbar"></div>
                    </div>
                    <div class="step-indicator mt-3" id="step-indicator">
                        <div class="step active" data-step="1">1</div>
                        <div class="step" data-step="2">2</div>
                        <div class="step" data-step="3">3</div>
                    </div>
                </div>
                
                <form id="booking-form" class="booking-form">
                    <!-- Step 1: Event Details -->
                    <div class="form-step active" data-step="1">
                        <div class="step-header">
                            <h3>Event Details</h3>
                            <p class="text-muted">Tell us about your event</p>
                        </div>
                        
                        <div class="form-field-group">
                            <label for="event_type">Event Type *</label>
                            <input type="text" id="event_type" name="event_type" class="form-control" 
                                   placeholder="Wedding, Corporate Event, Birthday Party, etc." required>
                            <div class="form-text">What type of event are you planning?</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="event_date">Event Date *</label>
                                    <select id="event_date" name="event_date" class="form-select" data-date-select required>
                                        <option value="">Select a date</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="event_time">Event Time</label>
                                    <input type="time" id="event_time" name="event_time" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-field-group">
                            <label for="event_location">Event Location *</label>
                            <input type="text" id="event_location" name="event_location" class="form-control" 
                                   placeholder="Venue name or address" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="guest_count">Expected Guests</label>
                                    <input type="number" id="guest_count" name="guest_count" class="form-control" 
                                           min="1" placeholder="Number of guests">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="event_duration">Event Duration</label>
                                    <select id="event_duration" name="event_duration" class="form-select">
                                        <option value="">Select duration</option>
                                        <option value="1 hour">1 hour</option>
                                        <option value="2 hours">2 hours</option>
                                        <option value="3 hours">3 hours</option>
                                        <option value="4+ hours">4+ hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-field-group">
                            <label for="event_description">Event Description *</label>
                            <textarea id="event_description" name="event_description" class="form-control" 
                                      rows="4" placeholder="Describe your event..." required></textarea>
                        </div>
                    </div>
                    
                    <!-- Step 2: Contact Information -->
                    <div class="form-step" data-step="2">
                        <div class="step-header">
                            <h3>Contact Information</h3>
                            <p class="text-muted">How can we reach you?</p>
                        </div>
                        
                        <div class="alert alert-info" id="guest-notice">
                            <i class="material-icons me-2">info</i>
                            We'll create an account for you to track your booking status.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="customer_name">Full Name *</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="customer_email">Email Address *</label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="customer_phone">Phone Number *</label>
                                    <input type="tel" id="customer_phone" name="customer_phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-group">
                                    <label for="customer_company">Company/Organization</label>
                                    <input type="text" id="customer_company" name="customer_company" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-field-group">
                            <label for="special_requirements">Special Requirements</label>
                            <textarea id="special_requirements" name="special_requirements" class="form-control" 
                                      rows="3" placeholder="Any special requests or requirements..."></textarea>
                        </div>
                        
                        <div class="form-field-group">
                            <label for="budget_range">Budget Range</label>
                            <select id="budget_range" name="budget_range" class="form-select">
                                <option value="">Select budget range</option>
                                <option value="€0 - €500">€0 - €500</option>
                                <option value="€500 - €1000">€500 - €1000</option>
                                <option value="€1000 - €2000">€1000 - €2000</option>
                                <option value="€2000+">€2000+</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Step 3: Review & Confirmation -->
                    <div class="form-step" data-step="3">
                        <div class="step-header">
                            <h3>Review & Confirmation</h3>
                            <p class="text-muted">Please review your booking details</p>
                        </div>
                        
                        <div class="booking-summary" id="booking-summary">
                            <!-- Summary will be populated here -->
                        </div>
                        
                        <div class="form-field-group">
                            <div class="form-check">
                                <input type="checkbox" id="accept_terms" name="accept_terms" class="form-check-input" required>
                                <label for="accept_terms" class="form-check-label">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                                    <a href="#" target="_blank">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-field-group">
                            <div class="form-check">
                                <input type="checkbox" id="marketing_consent" name="marketing_consent" class="form-check-input">
                                <label for="marketing_consent" class="form-check-label">
                                    I would like to receive updates about new artists and special offers
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Navigation -->
                    <div class="form-step-navigation">
                        <button type="button" class="btn btn-outline-secondary" id="prev-btn" 
                                data-step-prev style="display: none;">
                            <i class="material-icons me-2">arrow_back</i>Previous
                        </button>
                        
                        <button type="button" class="btn btn-primary" id="next-btn" data-step-next>
                            Next<i class="material-icons ms-2">arrow_forward</i>
                        </button>
                        
                        <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                            <i class="material-icons me-2">check</i>Submit Booking
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        formContainer.innerHTML = formHtml;
    },
    
    // Initialize date picker with available dates
    initializeDatePicker() {
        this.loadAvailableDates().then(() => {
            const dateSelect = document.getElementById('event_date');
            if (dateSelect && this.state.availableDates.length > 0) {
                this.state.availableDates.forEach(dateObj => {
                    const option = document.createElement('option');
                    option.value = dateObj.id;
                    option.textContent = this.formatDate(dateObj.date);
                    dateSelect.appendChild(option);
                });
            }
        });
    },
    
    // Initialize progress indicator
    initializeProgressIndicator() {
        this.updateProgressIndicator();
    },
    
    // Load package data
    loadPackageData() {
        const urlParams = new URLSearchParams(window.location.search);
        const packageId = urlParams.get('package') || urlParams.get('artist');
        
        if (packageId) {
            // In a real app, this would fetch from API
            this.state.packageData = this.getMockPackageData(packageId);
            this.displayPackageInfo();
        }
    },
    
    // Get mock package data
    getMockPackageData(id) {
        return {
            id: id,
            name: 'Wedding Package Premium',
            artist: 'The Acoustic Duo',
            price: 750,
            description: 'Perfect for intimate weddings with acoustic guitar and vocals',
            duration: '3 hours',
            includes: ['Sound equipment', 'Microphones', 'Song requests']
        };
    },
    
    // Load available dates
    loadAvailableDates() {
        return new Promise((resolve) => {
            // Simulate API call
            setTimeout(() => {
                this.state.availableDates = this.getMockAvailableDates();
                resolve();
            }, 500);
        });
    },
    
    // Get mock available dates
    getMockAvailableDates() {
        const dates = [];
        const today = new Date();
        
        // Generate some future dates
        for (let i = 7; i < 60; i += 7) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            
            dates.push({
                id: i,
                date: date.toISOString().split('T')[0]
            });
        }
        
        return dates;
    },
    
    // Display package information
    displayPackageInfo() {
        if (!this.state.packageData) return;
        
        const packageInfo = document.getElementById('package-info');
        if (packageInfo) {
            packageInfo.innerHTML = `
                <div class="package-card">
                    <h4>${this.state.packageData.name}</h4>
                    <p class="text-muted">by ${this.state.packageData.artist}</p>
                    <p>${this.state.packageData.description}</p>
                    <div class="price-tag">€${this.state.packageData.price}</div>
                </div>
            `;
        }
    },
    
    // Handle step navigation
    nextStep() {
        if (this.validateCurrentStep()) {
            if (this.state.currentStep < this.state.totalSteps) {
                this.state.currentStep++;
                this.updateStepDisplay();
                this.updateProgressIndicator();
                
                if (this.state.currentStep === 3) {
                    this.generateBookingSummary();
                }
            }
        }
    },
    
    prevStep() {
        if (this.state.currentStep > 1) {
            this.state.currentStep--;
            this.updateStepDisplay();
            this.updateProgressIndicator();
        }
    },
    
    goToStep(step) {
        if (step >= 1 && step <= this.state.totalSteps) {
            this.state.currentStep = step;
            this.updateStepDisplay();
            this.updateProgressIndicator();
        }
    },
    
    // Update step display
    updateStepDisplay() {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        
        // Show current step
        const currentStepEl = document.querySelector(`[data-step="${this.state.currentStep}"]`);
        if (currentStepEl) {
            currentStepEl.classList.add('active');
        }
        
        // Update navigation buttons
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        
        if (prevBtn) prevBtn.style.display = this.state.currentStep > 1 ? 'block' : 'none';
        if (nextBtn) nextBtn.style.display = this.state.currentStep < this.state.totalSteps ? 'block' : 'none';
        if (submitBtn) submitBtn.style.display = this.state.currentStep === this.state.totalSteps ? 'block' : 'none';
    },
    
    // Update progress indicator
    updateProgressIndicator() {
        const progressBar = document.getElementById('progress-bar');
        const stepIndicator = document.getElementById('step-indicator');
        
        if (progressBar) {
            const progress = (this.state.currentStep / this.state.totalSteps) * 100;
            progressBar.style.width = `${progress}%`;
        }
        
        if (stepIndicator) {
            stepIndicator.querySelectorAll('.step').forEach((step, index) => {
                const stepNumber = index + 1;
                step.classList.remove('active', 'completed');
                
                if (stepNumber < this.state.currentStep) {
                    step.classList.add('completed');
                } else if (stepNumber === this.state.currentStep) {
                    step.classList.add('active');
                }
            });
        }
    },
    
    // Validate current step
    validateCurrentStep() {
        const currentStepEl = document.querySelector(`[data-step="${this.state.currentStep}"].active`);
        if (!currentStepEl) return false;
        
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    },
    
    // Validate individual field
    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        // Remove existing error styling
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
        }
        
        // Phone validation
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            if (!phoneRegex.test(value.replace(/\s+/g, ''))) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number';
            }
        }
        
        // Number validation
        if (field.type === 'number' && value) {
            const min = field.getAttribute('min');
            if (min && parseInt(value) < parseInt(min)) {
                isValid = false;
                errorMessage = `Value must be at least ${min}`;
            }
        }
        
        // Show error if invalid
        if (!isValid) {
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errorMessage;
            field.parentNode.appendChild(errorDiv);
        }
        
        return isValid;
    },
    
    // Update form data
    updateFormData() {
        const form = document.getElementById('booking-form');
        if (!form) return;
        
        const formData = new FormData(form);
        this.state.formData = {};
        
        for (let [key, value] of formData.entries()) {
            this.state.formData[key] = value;
        }
    },
    
    // Handle date selection
    handleDateSelection(dateId) {
        const selectedDate = this.state.availableDates.find(d => d.id == dateId);
        if (selectedDate) {
            this.state.selectedDate = selectedDate;
        }
    },
    
    // Handle file upload
    handleFileUpload(input) {
        const files = Array.from(input.files);
        const previewContainer = input.nextElementSibling;
        
        if (previewContainer && previewContainer.classList.contains('file-preview-container')) {
            handleFileUpload(input, previewContainer, 5);
        }
    },
    
    // Generate booking summary
    generateBookingSummary() {
        this.updateFormData();
        
        const summaryContainer = document.getElementById('booking-summary');
        if (!summaryContainer) return;
        
        const summaryHtml = `
            <div class="booking-summary-card">
                <h5>Booking Summary</h5>
                
                <div class="summary-section">
                    <h6>Package Details</h6>
                    <div class="summary-item">
                        <span>Package:</span>
                        <span>${this.state.packageData?.name || 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Artist:</span>
                        <span>${this.state.packageData?.artist || 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Price:</span>
                        <span class="price-highlight">€${this.state.packageData?.price || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="summary-section">
                    <h6>Event Information</h6>
                    <div class="summary-item">
                        <span>Event Type:</span>
                        <span>${this.state.formData.event_type || 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Date:</span>
                        <span>${this.state.selectedDate ? this.formatDate(this.state.selectedDate.date) : 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Time:</span>
                        <span>${this.state.formData.event_time || 'Not specified'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Location:</span>
                        <span>${this.state.formData.event_location || 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Guests:</span>
                        <span>${this.state.formData.guest_count || 'Not specified'}</span>
                    </div>
                </div>
                
                <div class="summary-section">
                    <h6>Contact Information</h6>
                    <div class="summary-item">
                        <span>Name:</span>
                        <span>${this.state.formData.customer_name || 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Email:</span>
                        <span>${this.state.formData.customer_email || 'N/A'}</span>
                    </div>
                    <div class="summary-item">
                        <span>Phone:</span>
                        <span>${this.state.formData.customer_phone || 'N/A'}</span>
                    </div>
                </div>
                
                ${this.state.formData.special_requirements ? `
                <div class="summary-section">
                    <h6>Special Requirements</h6>
                    <p>${this.state.formData.special_requirements}</p>
                </div>
                ` : ''}
            </div>
        `;
        
        summaryContainer.innerHTML = summaryHtml;
    },
    
    // Submit booking
    submitBooking() {
        if (this.state.isSubmitting) return;
        
        if (!this.validateCurrentStep()) {
            FestValley.showNotification('Error', 'Please check all required fields', 'error');
            return;
        }
        
        this.state.isSubmitting = true;
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        }
        
        // Prepare booking data
        const bookingData = {
            ...this.state.formData,
            package_id: this.state.packageData?.id,
            available_date_id: this.state.selectedDate?.id
        };
        
        // Submit booking
        fetch('/api/bookings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': FestValley.config.csrfToken
            },
            body: JSON.stringify(bookingData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                FestValley.showNotification('Success', 'Booking submitted successfully!', 'success');
                
                // Redirect to confirmation page
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    this.showBookingConfirmation(data);
                }
            } else {
                throw new Error(data.message || 'Booking submission failed');
            }
        })
        .catch(error => {
            console.error('Booking error:', error);
            FestValley.showNotification('Error', error.message || 'An error occurred', 'error');
        })
        .finally(() => {
            this.state.isSubmitting = false;
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="material-icons me-2">check</i>Submit Booking';
            }
        });
    },
    
    // Show booking confirmation
    showBookingConfirmation(data) {
        const confirmationHtml = `
            <div class="booking-confirmation">
                <div class="text-center mb-4">
                    <i class="material-icons text-success" style="font-size: 4rem;">check_circle</i>
                    <h3 class="mt-3">Booking Submitted Successfully!</h3>
                    <p class="text-muted">Invoice #${data.invoice_number}</p>
                </div>
                
                <div class="alert alert-info">
                    <strong>What happens next?</strong><br>
                    1. The artist will review your booking request<br>
                    2. You'll receive an email confirmation once approved<br>
                    3. Payment instructions will be provided after approval
                </div>
                
                <div class="text-center">
                    <button class="btn btn-primary" onclick="window.location.href='/dashboard'">
                        Go to Dashboard
                    </button>
                </div>
            </div>
        `;
        
        document.querySelector('.booking-form-container').innerHTML = confirmationHtml;
    },
    
    // Format date for display
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
};

// Booking-specific CSS
const bookingStyles = `
<style>
.booking-summary-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1.5rem;
}

.summary-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.summary-section:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.summary-section h6 {
    color: #495057;
    margin-bottom: 0.75rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    padding: 0.25rem 0;
}

.summary-item:last-child {
    margin-bottom: 0;
}

.summary-item span:first-child {
    font-weight: 500;
    color: #6c757d;
}

.summary-item span:last-child {
    font-weight: 600;
    color: #212529;
}

.price-highlight {
    color: #28a745 !important;
    font-size: 1.1rem !important;
}

.booking-confirmation {
    text-align: center;
    padding: 3rem 1rem;
}

.step-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
}

.step-indicator .step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin: 0 1rem;
    position: relative;
    transition: all 0.3s ease;
}

.step-indicator .step.active {
    background: #667eea;
    color: white;
}

.step-indicator .step.completed {
    background: #28a745;
    color: white;
}

.step-indicator .step::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 100%;
    width: 2rem;
    height: 2px;
    background: #dee2e6;
    transform: translateY(-50%);
}

.step-indicator .step:last-child::after {
    display: none;
}

.form-step {
    display: none;
    animation: fadeInUp 0.3s ease-out;
}

.form-step.active {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
`;

// Inject booking styles
document.head.insertAdjacentHTML('beforeend', bookingStyles);

// Initialize booking system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash === '#booking' || document.getElementById('booking-form-container')) {
        BookingSystem.init();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BookingSystem;
} else {
    window.BookingSystem = BookingSystem;
}
