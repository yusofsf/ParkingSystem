<?php

namespace App\Services;

use App\Enums\PaymentType;
use App\Interfaces\BookingServiceInterface;
use App\Interfaces\ParkingSlotServiceInterface;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BookingService implements BookingServiceInterface
{
    private ParkingSlotServiceInterface $parkingSlotService;

    function __construct(ParkingSlotServiceInterface $parkingSlotService)
    {
        $this->parkingSlotService = $parkingSlotService;
    }

    function all(): Collection
    {
        return Booking::with('user')->get();
    }

    function cancel(Booking $booking): Booking
    {
        $booking->update([
            'cancelled' => true
        ]);

        $booking->parkingSlot()->update([
            'available' => true
        ]);

        return $booking;
    }

    function update(array $data, Booking $booking): Booking
    {
        $booking->update($data);

        return $booking;
    }

    function store(array $data): array
    {
        $newBooking = Auth::user()->bookings()->create([
            'begin' => Carbon::parse($data['begin'])->toDateTimeString(),
            'end' => Carbon::parse($data['end'])->toDateTimeString(),
            'car_id' => $data['car_id'],
            'parking_slot_id' => $data['slot_id']
        ]);

        $newBooking->parkingSlot()->update([
            'available' => false
        ]);

        return [$newBooking, $this->parkingSlotService->priceDetails($newBooking->parkingSlot)];
    }

    function storedCreditCard(array $data, Booking $booking)
    {
        $totalPrice = $this->parkingSlotService->calcTotalPrice($booking->parkingSlot);

        $payment = $booking->payments()->create([
            'total_price' => $totalPrice,
            'type' => PaymentType::CREDIT_CARD->getPaymentType()
        ]);

        $payment->creditCard()->create($data);

        return $payment;
    }

    function storedCash(Booking $booking)
    {
        $totalPrice = $this->parkingSlotService->calcTotalPrice($booking->parkingSlot);

        $payment = $booking->payments()->create([
            'total_price' => ceil($totalPrice),
            'type' => PaymentType::CASH->getPaymentType()
        ]);

        $payment->cash()->create([
            'total_cash' => ceil($totalPrice)
        ]);

        return $payment;
    }
}
