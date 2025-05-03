<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingSlot\StoreRequest;
use App\Http\Requests\ParkingSlot\UpdateRequest;
use App\Interfaces\ParkingSlotServiceInterface;
use App\Models\ParkingSlot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class ParkingSlotController extends Controller
{
    private ParkingSlotServiceInterface $parkingSlotService;

    public function __construct(ParkingSlotServiceInterface $parkingSlotService)
    {
        $this->parkingSlotService = $parkingSlotService;
    }

    public function index(): JsonResponse
    {
        return Response::json([
            'result' => $this->parkingSlotService->all(),
            'message' => 'slot Updated'
        ]);
    }

    /**
     * Handle the incoming request.
     */
    public function update(UpdateRequest $request, ParkingSlot $parkingSlot): JsonResponse
    {
        return Response::json([
            'result' => $this->parkingSlotService->update($request->validated(), $parkingSlot),
            'message' => 'slot Updated'
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $results = $this->parkingSlotService->search($request->string('search'));

        if ($results->count() == 0) {
            return Response::json([
                'message' => 'search not successful'
            ], 400);
        }

        return Response::json([
            'result' => $results,
            'message' => 'search successful'
        ]);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $slotCreated = $this->parkingSlotService->store($request->validated());

        return Response::json([
            'result' => $slotCreated,
            'message' => 'Slot Created'
        ], 201);
    }

    public function show(ParkingSlot $parkingSlot): JsonResponse
    {
        Gate::authorize('view', ParkingSlot::class);

        return Response::json([
            'result' => $parkingSlot,
            'message' => 'Slot Found'
        ]);
    }

    public function sort(Request $request): JsonResponse
    {
        return Response::json([
            'result' => $this->parkingSlotService->sort($request->string('sortby'), $request->string('order')),
            'message' => 'slots sorted'
        ]);
    }

    public function delete(ParkingSlot $parkingSlot): JsonResponse
    {
        Gate::authorize('delete', ParkingSlot::class);

        $parkingSlot->delete();

        return Response::json([
            'message' => 'ParkingSlot Deleted'
        ]);
    }
}
