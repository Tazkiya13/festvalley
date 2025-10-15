<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_name',
        'real_name',
        'bio',
        'genre',
        // 'price_per_hour',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'region',
        'maps_link',
        'phone',
        'social_media',
        'profile_photo',
        'cover_photo',
        'years_experience',
        'languages',
        'equipment_owned'
    ];
    protected $casts = [
        'social_media' => 'json',
        'languages' => 'json',
        'equipment_owned' => 'json',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bookings()
    {
        return $this->hasMany(Email::class);
    }
}
