<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AuthRepository implements AuthRepositoryInterface
{
    public function login($request, $validated)
    {
        // Attempt to authenticate the user
        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            $user = Auth::user();
                if ($user->hasRole('Admin')) {
                    return redirect()->route('admin.home')->with('success', 'Login successfully.');
                } elseif ($user->hasRole('Customer')) {
                    return redirect()->route('customer.bookings')->with('success', 'Login successfully.');
                } elseif ($user->hasRole('Artist')) {
                    return redirect()->route('artists.index')->with('success', 'Login successfully.');
                } else {
                    return redirect()->route('packages.browse');
                }

        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function createUser($validated)
    {
        $validated['password'] = bcrypt($validated['password']);
        $roleName = $validated['role'];
        unset($validated['role']);
        $user = User::create($validated);
        Auth::login($user);
        event(new Registered($user));
        // Assign role and permissions based on selected role
        if ($roleName === 'Customer') {
            $permissions = [
                'view package customer',
                'detail package customer',
                'create customer booking',
                'edit customer booking',
                'view customer booking',
                'create customer invoice',
                'edit customer invoice',
                'view customer invoice',
            ];
        } elseif ($roleName === 'Artist') {
            $permissions = [
                // Tambahkan permission khusus artist di sini
                'view package artist',
                'create package artist',
                'edit package artist',
                'delete package artist',
            ];
        } else {
            $permissions = [];
        }
        $role = Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);
        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
            if (!$role->hasPermissionTo($perm)) {
                $role->givePermissionTo($perm);
            }
        }
        $user->assignRole($role);
        $user->givePermissionTo($permissions);
        return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials.');
    }

    public function logout($request)
    {
        try {
            Auth::logout();
            
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            // Clear any additional auth guards if needed
            Auth::guard('web')->logout();
            
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            \Log::error('Logout error: ' . $e->getMessage());
        }
        
        // Redirect without session flash message to avoid session issues
        return redirect()->route('home');
    }
}
