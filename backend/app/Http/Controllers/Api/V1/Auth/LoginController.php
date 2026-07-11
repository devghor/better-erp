<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthServiceInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $auth,
    ) {}

    #[OA\Post(
        path: '/auth/login',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'Secr3t!Passw0rd'),
                ],
            ),
        ),
        responses: [
            new OA\Response(response: 200, description: 'Access and refresh tokens issued'),
            new OA\Response(response: 422, description: 'Invalid credentials or validation error', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ],
    )]
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return ApiResponse::success($this->auth->login($request->email, $request->password));
    }

    #[OA\Post(
        path: '/auth/refresh',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'New access token issued'),
            new OA\Response(response: 401, description: 'Unauthenticated', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ],
    )]
    public function refresh(Request $request): JsonResponse
    {
        return ApiResponse::success($this->auth->refresh($request->user()));
    }

    #[OA\Get(
        path: '/auth/auth-user',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Currently authenticated user',
                content: new OA\JsonContent(ref: '#/components/schemas/User'),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ],
    )]
    public function authUser(Request $request): JsonResponse
    {
        return ApiResponse::success($this->auth->authUser($request->user()));
    }
}
