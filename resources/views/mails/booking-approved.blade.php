<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Approved - {{ $package_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 28px;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .header img {
            width: 60px;
            margin-bottom: 12px;
        }
        .header h2 {
            color: #27ae60;
            margin: 0;
            font-size: 24px;
        }
        .info {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.15em;
            margin-bottom: 18px;
        }
        .details {
            margin: 18px 0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px 20px;
            font-size: 1em;
        }
        .event-details {
            margin: 18px 0;
            background: #e8f5e8;
            border-radius: 8px;
            padding: 16px 20px;
            font-size: 1em;
            border-left: 4px solid #27ae60;
        }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 18px;
            margin-right: 10px;
        }
        .btn-primary {
            background: #3498db;
            color: #fff;
        }
        .btn-success {
            background: #27ae60;
            color: #fff;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            color: #888;
            font-size: 0.95em;
        }
        .success-icon {
            background: #27ae60;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✓</div>
            <h2>Booking Approved!</h2>
        </div>

        <div class="info">
            Great news {{ $customer_name }}!<br>
            Your booking request has been approved by the artist.
        </div>

        <div class="details">
            <strong>Booking Details:</strong><br>
            Package: {{ $package_name }}<br>
            Artist: {{ $artist_name }}<br>
            @if($event_type)
            Event Type: {{ $event_type }}<br>
            @endif
            @if($event_date)
            Event Date: {{ \Carbon\Carbon::parse($event_date)->format('l, F j, Y') }}<br>
            @endif
            @if($event_location)
            Location: {{ $event_location }}<br>
            @endif
        </div>

        @if($event_description || $special_requirements)
        <div class="event-details">
            <strong>Event Information:</strong><br>
            @if($event_description)
            <strong>Description:</strong> {{ $event_description }}<br><br>
            @endif
            @if($special_requirements)
            <strong>Special Requirements:</strong> {{ $special_requirements }}
            @endif
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <p><strong>Next Step: Complete Your Payment</strong></p>
            <p>To secure your booking, please proceed with the payment within 24 hours.</p>
            
            <a href="{{ $payment_url }}" class="btn btn-success">
                Proceed to Payment
            </a>
        </div>

        <div class="footer">
            Thank you for choosing FestValley!<br>
            &copy; {{ date('Y') }} FestValley - Your Music Booking Platform
        </div>
    </div>
</body>
</html>
