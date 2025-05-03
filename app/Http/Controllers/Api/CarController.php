<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\StoreRequest;
use App\Http\Requests\Car\UpdateRequest;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Car::class);

        return Response::json([
            'result' => Car::with('user')->get(),
            'message' => 'All Car'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $storeRequest): JsonResponse
    {
        Gate::authorize('create', Car::class);

        return Response::json([
            'result' => Auth::user()->cars()->create($storeRequest->validated()),
            'message' => 'Car Created'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car): JsonResponse
    {
        Gate::authorize('view', Car::class);

        return Response::json([
            'result' => $car,
            'message' => 'Car is Found'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $updateRequest, Car $car): JsonResponse
    {
        Gate::authorize('update', Car::class);

        $car->update($updateRequest->validated());

        return Response::json([
            'result' => $car,
            'message' => 'Car Updated'
        ]);
    }

    public function delete(Car $car): JsonResponse
    {
        Gate::authorize('delete', Car::class);

        $car->delete();

        return Response::json([
            'message' => 'Car Deleted'
        ]);
    }
}
