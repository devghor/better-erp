<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthServiceInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $auth,
    ) {}

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return ApiResponse::success($this->auth->login($request->email, $request->password));
    }

    public function refresh(Request $request): JsonResponse
    {
        return ApiResponse::success($this->auth->refresh($request->user()));
    }

    public function authUser(Request $request): JsonResponse
    {
        return ApiResponse::success($this->auth->authUser($request->user()));
    }
}
