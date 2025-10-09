<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

    protected $table = 'packages';
    
    protected $fillable = [
        'user_id',
        'package_name',
        'description',
        'country',
        'region', 
        'price',
        'image',
        'photos',
        'video',
    ];

    protected $casts = [
        'photos' => 'array', // Cast JSON to array
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function availableDates()
    {
        return $this->hasMany(AvailableDate::class, 'package_id');
    }
    
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'package_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'package_id');
    }
}
