<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $token = $user->createToken($user->user_name . Carbon::now())->plainTextToken;

        return Response::json([
            'user' => $user,
            'token' => $token,
            'message' => 'User is Registered'
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            $token = $user->createToken($user->user_name . Carbon::now())->plainTextToken;

            return Response::json([
                'user' => $user,
                'token' => $token,
                'message' => 'User is Logged in'
            ]);

        }

        return Response::json([
            'message' => 'credential is wrong'
        ], 404);

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return Response::json(['message' => 'Successfully logged out']);
    }
}
