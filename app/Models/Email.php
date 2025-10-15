<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    /** @use HasFactory<\Database\Factories\EmailFactory> */
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'order_id',
        'user_id',
        'package_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'event_type',
        'event_date',
        'event_location',
        'event_description',
        'special_requirements',
        'subject',
        'body',
        'status',
        'sent_at',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function availableDate()
    {
        return $this->hasOneThrough(AvailableDate::class, Invoice::class, 'id', 'id', 'invoice_id', 'available_date_id');
    }
}
