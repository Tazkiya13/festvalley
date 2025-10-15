<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'create package', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit package', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete package', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view package', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create package artists', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit package artists', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete package artists', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view package artists', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create customer booking', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit customer booking', 'guard_name' => 'web']);
        // Permission::firstOrCreate(['name' => 'delete customer booking', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view customer booking', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create customer invoice', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit customer invoice', 'guard_name' => 'web']);
        // Permission::firstOrCreate(['name' => 'delete customer invoice', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view customer invoice', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'approve customer order', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reject customer order', 'guard_name' => 'web']);
        // Permission::firstOrCreate(['name' => 'checkout artists order', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view customer order', 'guard_name' => 'web']);

        // Permission::firstOrCreate(['name' => 'buy package customer', 'guard_name' => 'web']);
        // Permission::firstOrCreate(['name' => 'edit card customer', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'detail package customer', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view package customer', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create chat', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit chat', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete chat', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view chat', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view role', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'create permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view permission', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'edit profile', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'show profile', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view profile', 'guard_name' => 'web']);

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web'])->givePermissionTo(['view profile', 'edit profile', 'show profile', 'create role', 'edit role', 'delete role', 'view role', 'create permission', 'edit permission', 'delete permission', 'view permission', 'create package', 'edit package', 'delete package', 'view package', 'view customer order', 'approve customer order', 'reject customer order', 'view customer booking', 'create chat', 'view chat']);
        Role::firstOrCreate(['name' => 'Artist', 'guard_name' => 'web'])->givePermissionTo(['view profile', 'edit profile', 'show profile', 'view package artists', 'create package artists', 'edit package artists', 'delete package artists', 'view customer booking']);
        Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web'])->givePermissionTo(['view package customer', 'detail package customer', 'create customer booking', 'edit customer booking', 'view customer booking', 'create customer invoice', 'edit customer invoice', 'view customer invoice']);

        // Permission::where('name', 'buy package customer')->delete();
    // Permission::where('name', 'checkout package customer')->delete();
    }
}
