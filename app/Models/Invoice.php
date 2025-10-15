<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'package_id',
        'invoice_number',
        'total',
        'status',
        'booking_status',
        'rejection_reason',
        'available_date_id',
        'transaction_date',
        'transaction_id',
        // Customer details
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_company',
        // Event details
        'event_type',
        'event_date',
        'event_time',
        'event_location',
        'venue_address',
        'event_description',
        'special_requirements',
        'technical_requirements',
        'guest_count',
        'event_duration',
        'budget_range',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function order()
    {
        return $this->hasOne(Order::class)->latest();
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function availableDate()
    {
        return $this->belongsTo(AvailableDate::class);
    }

    public function email()
    {
        return $this->hasOne(Email::class);
    }
}
