<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): array
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->issueTokens($user);
    }

    public function refresh(Request $request): array
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        return $this->issueTokens($user);
    }

    protected function issueTokens(User $user): array
    {
        $accessTokenTtl = (int) config('sanctum.access_token_ttl');
        $refreshTokenTtl = (int) config('sanctum.refresh_token_ttl');

        $accessToken = $user->createToken(
            'access_token',
            ['access-api'],
            now()->addMinutes($accessTokenTtl)
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            ['issue-access-token'],
            now()->addMinutes($refreshTokenTtl)
        );

        return [
            'token_type' => 'Bearer',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'expires_in' => $accessTokenTtl * 60,
        ];
    }

    public  function authUser(Request $request): array
    {
        return $request->user()->toArray();
    }
}
