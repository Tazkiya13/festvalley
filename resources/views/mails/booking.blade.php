<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Package Approval Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 520px;
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
        .info {
            color: #2980b9;
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
        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 18px;
            margin-right: 10px;
        }
        .btn-approve {
            background: #27ae60;
            color: #fff;
        }
        .btn-reject {
            background: #e74c3c;
            color: #fff;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            color: #888;
            font-size: 0.95em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://img.icons8.com/color/96/000000/checked--v1.png" alt="Booking Status">
            <h2>Booking Package Approval Request</h2>
        </div>

        <div class="info">
            {{-- Halo {{ $user->name ?? 'Artis' }},<br> --}}
            Hello,<br>
            Your package has been booked by a customer and is awaiting your approval.
        </div>

        <div class="details">
            <strong>Booking Details:</strong><br>
            Customer Name: {{ $invoice->user->name ?? '-' }}<br>
            Package: {{ $invoice->package->package_name ?? '-' }}<br>
            Booking Date: {{ $booking_date ? \Carbon\Carbon::parse($booking_date)->translatedFormat('d F Y') : '-' }}<br>
            
            @if($mail->event_type)
            <br><strong>Event Details:</strong><br>
            Event Type: {{ $mail->event_type }}<br>
            @endif
            
            @if($mail->event_date)
            Event Date: {{ \Carbon\Carbon::parse($mail->event_date)->translatedFormat('d F Y') }}<br>
            @endif
            
            @if($mail->event_location)
            Event Location: {{ $mail->event_location }}<br>
            @endif
            
            @if($mail->event_description)
            <br><strong>Event Description:</strong><br>
            {{ $mail->event_description }}<br>
            @endif
            
            @if($mail->special_requirements)
            <br><strong>Special Requirements:</strong><br>
            {{ $mail->special_requirements }}<br>
            @endif
        </div>

        <div>
            Please choose the following action for this booking:
            <br>
            <a href="{{ url('/booking/approve/'.$mail_id) }}" class="btn btn-approve">Approve</a>
            <a href="{{ url('/booking/reject/'.$mail_id) }}" class="btn btn-reject">Reject</a>
        </div>

        <div class="footer">
            Thank you for your cooperation.<br>
            &copy; {{ date('Y') }} BandGeeks
        </div>
    </div>
</body>
</html>
