<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
    public function login($request, $validated);
    public function createUser($validated);
    public function logout($request);
}
