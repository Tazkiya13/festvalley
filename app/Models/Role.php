<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Contracts\Role as RoleContract;
use App\Models\Permission;
use App\Models\User;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name'];

     public static function findByName(string $name, $guardName = null): RoleContract
    {
        return static::where('name', $name)->where('guard_name', $guardName)->firstOrFail();
    }

    public static function findById($id, $guardName = null): RoleContract
    {
        return static::where('id', $id)->where('guard_name', $guardName)->firstOrFail();
    }

    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $role = static::where('name', $name)->where('guard_name', $guardName)->first();
        if (!$role) {
            return static::create(['name' => $name, 'guard_name' => $guardName]);
        }
        return $role;
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }
}
