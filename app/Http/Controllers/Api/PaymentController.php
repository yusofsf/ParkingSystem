<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PayRequest;
use App\Http\Requests\Payment\UpdateRequest;
use App\Interfaces\PaymentServiceInterface;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    private PaymentServiceInterface $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Payment::class);

        return Response::json([
            'result' => $this->paymentService->all(),
            'message' => 'all payments'
        ]);
    }

    public function update(UpdateRequest $request, Payment $payment): JsonResponse
    {
        Gate::authorize('update', Payment::class);

        return Response::json([
            'result' => $this->paymentService->update($request->validated(), $payment),
            'message' => 'updated payments'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): JsonResponse
    {
        Gate::authorize('view', Payment::class);

        return Response::json([
            'result' => $payment,
            'message' => 'payment found'
        ]);
    }

    public function pay(PayRequest $request, Payment $payment): JsonResponse
    {
        [$message, $payment] = $this->paymentService->pay($request->validated(), $payment);

        if ($message == 'enter valid amount') {
            return Response::json([
                'result' => $payment,
                'message' => $message
            ], 402);
        }

        return Response::json([
            'result' => $payment,
            'message' => $message
        ]);
    }
}
