@extends('layouts.app')

@section('title', 'Payment - ' . $invoice->invoice_number)

@section('content')
<div class="payment-container">
    <div class="container">
        <div class="payment-wrapper">
            <!-- Payment Header -->
            <div class="payment-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1>Complete Your Payment</h1>
                        <p>Invoice: {{ $invoice->invoice_number }}</p>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <div class="amount-display">
                            <span class="amount-label">Total Amount</span>
                            <span class="amount-value">€ {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Payment Form -->
                    <div class="payment-card">
                        <div class="card-header">
                            <h3>Upload Payment Proof</h3>
                            <p>Please upload your bank transfer receipt or payment proof</p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('booking.process-payment', $invoice->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                                @csrf

                                <!-- Bank Information -->
                                <div class="bank-info-section">
                                    <h4>Bank Transfer Details</h4>
                                    <div class="bank-accounts">
                                        <div class="bank-account">
                                            <div class="bank-logo">
                                                <i class="fas fa-university"></i>
                                            </div>
                                            <div class="bank-details">
                                                <h5>Bank Central Asia (BCA)</h5>
                                                <div class="account-info">
                                                    <span class="account-number">1234567890</span>
                                                    <span class="account-name">PT Festvalley</span>
                                                </div>
                                            </div>
                                            <button type="button" class="copy-btn" onclick="copyToClipboard('1234567890')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>

                                        <div class="bank-account">
                                            <div class="bank-logo">
                                                <i class="fas fa-university"></i>
                                            </div>
                                            <div class="bank-details">
                                                <h5>Bank Mandiri</h5>
                                                <div class="account-info">
                                                    <span class="account-number">0987654321</span>
                                                    <span class="account-name">PT Festvalley</span>
                                                </div>
                                            </div>
                                            <button type="button" class="copy-btn" onclick="copyToClipboard('0987654321')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Section -->
                                <div class="upload-section">
                                    <h4>Upload Payment Proof</h4>
                                    <div class="upload-area" id="uploadArea">
                                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <h5>Drag & drop your payment proof here</h5>
                                            <p>or <span class="upload-link">click to browse</span></p>
                                            <small>Supported formats: JPG, PNG, GIF (Max: 2MB)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="file-preview" id="filePreview" style="display: none;">
                                        <img id="previewImage" src="" alt="Payment proof preview">
                                        <div class="file-info">
                                            <span id="fileName"></span>
                                            <button type="button" class="remove-file" onclick="removeFile()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @error('payment_proof')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Instructions -->
                                <div class="payment-instructions">
                                    <h4>Payment Instructions</h4>
                                    <ol>
                                        <li>Transfer the exact amount to one of the bank accounts above</li>
                                        <li>Keep your transfer receipt or take a screenshot</li>
                                        <li>Upload the payment proof using the form above</li>
                                        <li>We'll verify your payment within 24 hours</li>
                                        <li>You'll receive a confirmation email once approved</li>
                                    </ol>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary btn-submit">
                                        <i class="fas fa-upload"></i>
                                        Submit Payment Proof
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="payment-card">
                        <div class="card-header">
                            <h3>Order Summary</h3>
                        </div>

                        <div class="card-body">
                            <div class="order-details">
                                @if($invoice->package->image)
                                    <img src="{{ asset('images/default.jpg') }}" 
                                         data-image="{{ $invoice->package->image }}" 
                                         alt="{{ $invoice->package->package_name }}" 
                                         class="package-image">
                                @else
                                    <div class="package-placeholder">
                                        <i class="fas fa-music"></i>
                                    </div>
                                @endif

                                <h4>{{ $invoice->package->package_name }}</h4>
                                <p class="artist-name">by {{ $invoice->package->user->name }}</p>

                                <div class="order-info">
                                    <div class="info-row">
                                        <span>Package Price:</span>
                                        <span>€ {{ number_format($invoice->total, 0, ',', '.') }}</span>
                                    </div>

                                    @if($invoice->availableDate)
                                    <div class="info-row">
                                        <span>Selected Date:</span>
                                        <span>{{ Carbon\Carbon::parse($invoice->availableDate->tanggal)->format('M j, Y') }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->email)
                                    <div class="info-row">
                                        <span>Event Date:</span>
                                        <span>{{ Carbon\Carbon::parse($invoice->email->event_date)->format('M j, Y') }}</span>
                                    </div>

                                    <div class="info-row">
                                        <span>Event Type:</span>
                                        <span>{{ $invoice->email->event_type }}</span>
                                    </div>

                                    @if($invoice->email->event_location)
                                    <div class="info-row">
                                        <span>Event Location:</span>
                                        <span>{{ $invoice->email->event_location }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->email->event_description)
                                    <div class="info-row">
                                        <span>Event Description:</span>
                                        <span>{{ Str::limit($invoice->email->event_description, 60) }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->email->special_requirements)
                                    <div class="info-row">
                                        <span>Special Requirements:</span>
                                        <span class="text-info">{{ Str::limit($invoice->email->special_requirements, 50) }}</span>
                                    </div>
                                    @endif
                                    @endif

                                    <hr>

                                    <div class="info-row total">
                                        <span>Total Amount:</span>
                                        <span>€ {{ number_format($invoice->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="payment-card">
                        <div class="card-body">
                            <div class="security-notice">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Secure Payment</h4>
                                <p>Your payment information is protected with bank-level security. We'll verify your payment and confirm your booking within 24 hours.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.payment-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

.payment-header {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.payment-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.payment-header p {
    color: #718096;
    margin: 0;
}

.amount-display {
    text-align: right;
}

.amount-label {
    display: block;
    font-size: 0.875rem;
    color: #718096;
    margin-bottom: 0.25rem;
}

.amount-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: #667eea;
}

.payment-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e9ecef;
}

.card-header h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
}

.card-header p {
    margin: 0;
    color: #718096;
    font-size: 0.875rem;
}

.card-body {
    padding: 2rem;
}

.bank-info-section {
    margin-bottom: 2rem;
}

.bank-info-section h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.bank-accounts {
    display: grid;
    gap: 1rem;
}

.bank-account {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.bank-logo {
    width: 50px;
    height: 50px;
    background: #667eea;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-right: 1rem;
}

.bank-details {
    flex: 1;
}

.bank-details h5 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
}

.account-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.account-number {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: #4a5568;
    font-size: 0.95rem;
}

.account-name {
    font-size: 0.875rem;
    color: #718096;
}

.copy-btn {
    background: #667eea;
    border: none;
    color: white;
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.copy-btn:hover {
    background: #5a67d8;
}

.upload-section {
    margin-bottom: 2rem;
}

.upload-section h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.upload-area:hover,
.upload-area.dragover {
    border-color: #667eea;
    background: #f7fafc;
}

.upload-area input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-content i {
    font-size: 3rem;
    color: #a0aec0;
    margin-bottom: 1rem;
}

.upload-content h5 {
    margin: 0 0 0.5rem 0;
    color: #4a5568;
    font-weight: 600;
}

.upload-content p {
    margin: 0 0 0.5rem 0;
    color: #718096;
}

.upload-link {
    color: #667eea;
    font-weight: 600;
}

.upload-content small {
    color: #a0aec0;
}

.file-preview {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
}

.file-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.file-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.remove-file {
    background: #e53e3e;
    border: none;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    cursor: pointer;
}

.payment-instructions {
    margin-bottom: 2rem;
}

.payment-instructions h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.payment-instructions ol {
    color: #4a5568;
    line-height: 1.6;
}

.payment-instructions li {
    margin-bottom: 0.5rem;
}

.form-actions {
    text-align: center;
}

.btn-submit {
    background: #48bb78;
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit:hover {
    background: #38a169;
}

.order-details {
    text-align: center;
}

.package-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.package-placeholder {
    width: 100%;
    height: 150px;
    background: #e2e8f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #a0aec0;
    margin-bottom: 1rem;
}

.order-details h4 {
    margin: 0 0 0.5rem 0;
    color: #2d3748;
    font-weight: 600;
}

.artist-name {
    color: #718096;
    margin: 0 0 1.5rem 0;
}

.order-info {
    text-align: left;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    color: #4a5568;
}

.info-row.total {
    font-weight: 600;
    font-size: 1.1rem;
    color: #2d3748;
}

.security-notice {
    text-align: center;
}

.security-notice i {
    font-size: 2rem;
    color: #48bb78;
    margin-bottom: 1rem;
}

.security-notice h4 {
    margin: 0 0 0.5rem 0;
    color: #2d3748;
    font-weight: 600;
}

.security-notice p {
    color: #718096;
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0;
}

.error-message {
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

@media (max-width: 768px) {
    .payment-wrapper {
        margin: 1rem;
    }
    
    .payment-header {
        padding: 1.5rem;
    }
    
    .amount-display {
        text-align: left;
        margin-top: 1rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .bank-account {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('payment_proof');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');

    // Upload area click handler
    uploadArea.addEventListener('click', function(e) {
        // Prevent double file picker when clicking directly on the file input
        if (e.target !== fileInput) {
            fileInput.click();
        }
    });

    // File input click handler to prevent event bubbling
    fileInput.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Drag and drop handlers
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            fileName.textContent = file.name;
            uploadArea.style.display = 'none';
            filePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please upload your payment proof.');
            return false;
        }
    });
});

function removeFile() {
    const fileInput = document.getElementById('payment_proof');
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    
    fileInput.value = '';
    uploadArea.style.display = 'block';
    filePreview.style.display = 'none';
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const btn = event.target.closest('.copy-btn');
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = '#48bb78';
        
        setTimeout(function() {
            btn.innerHTML = originalIcon;
            btn.style.background = '#667eea';
        }, 2000);
    });
}
</script>

@include('partials.image-url-handler')
@endsection
