<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\storeCreditCardRequest;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\UpdateRequest;
use App\Interfaces\BookingServiceInterface;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    private BookingServiceInterface $bookingService;

    public function __construct(BookingServiceInterface $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Booking::class);

        return Response::json([
            'result' => $this->bookingService->all(),
            'message' => 'All Booked Parking Slots'
        ]);
    }

    public function show(Booking $booking): JsonResponse
    {
        Gate::authorize('view', Booking::class);

        return Response::json([
            'result' => $booking,
            'message' => 'Booking Found'
        ]);
    }

    public function update(UpdateRequest $request, Booking $booking): JsonResponse
    {
        return Response::json([
            'result' => $this->bookingService->update($request->all(), $booking),
            'message' => 'Booking Updated'
        ], 202);
    }

    public function cancel(Booking $booking): JsonResponse
    {
        Gate::authorize('cancel', Booking::class);

        return Response::json([
            'result' => $this->bookingService->cancel($booking),
            'message' => 'Booking Cancelled'
        ], 202);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        [$result, $details] = $this->bookingService->store($request->validated());

        return Response::json([
            'result' => $result,
            'details' => $details,
            'message' => 'Booking stored'
        ], 202);
    }

    public function storeCash(Booking $booking): JsonResponse
    {
        Gate::authorize('storeCash', Booking::class);

        return Response::json([
            'result' => $this->bookingService->storedCash($booking),
            'message' => 'Cash stored'
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCreditCard(storeCreditCardRequest $request, Booking $booking): JsonResponse
    {
        return Response::json([
            'result' => $this->bookingService->storedCreditCard($request->validated(), $booking),
            'message' => 'credit card stored'
        ], 201);
    }
}
