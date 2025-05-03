<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class UserController
{
    public function index(): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $user->isAdmin());

        return Response::json([
            'result' => User::all(),
            'message' => 'all users'
        ]);
    }

    public function show(User $user): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $user->isAdmin());

        return Response::json([
            'result' => $user,
            'message' => 'user found'
        ]);
    }

    public function delete(User $user): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $user->isAdmin());

        $user->delete();

        return Response::json([
            'message' => 'user deleted'
        ]);
    }

    public function cars(User $user): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $user->isUser());

        return Response::json([
            'result' => $user->cars()->get(),
            'message' => 'cars found'
        ]);
    }

    public function payments(User $user): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $user->isUser());

        return Response::json([
            'result' => $user->payments()->get(),
            'message' => 'payments found'
        ]);
    }

    public function bookings(User $user): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $user->isUser());

        return Response::json([
            'result' => $user->bookings()->get(),
            'message' => 'bookings found'
        ]);
    }
}
