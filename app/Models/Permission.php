<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Spatie\Permission\Traits\HasPermissions;
// use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // use HasRoles, HasPermissions;

    protected $fillable = ['name', 'guard_name'];
}
