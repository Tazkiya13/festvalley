<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['is_admin', 'customer_phone', 'name', 'message'];
}
