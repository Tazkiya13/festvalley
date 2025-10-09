<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableDate extends Model
{
    /** @use HasFactory<\Database\Factories\AvailableDateFactory> */
    use HasFactory;

    protected $table = 'available_dates';
    protected $fillable = [
        'package_id',
        'date',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
