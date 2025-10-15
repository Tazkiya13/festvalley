<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Repositories\AuthRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(protected AuthRepositoryInterface $authRepository) {}

    public function login(AuthUserRequest $request)
    {
        $validated = $request->validated();
        return $this->authRepository->login($request, $validated);
    }

    public function store(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        return $this->authRepository->createUser($validated);
    }

    public function logout(Request $request)
    {
        return $this->authRepository->logout($request);
    }
}
