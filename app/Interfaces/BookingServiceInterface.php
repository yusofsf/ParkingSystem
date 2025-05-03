<?php

namespace App\Interfaces;

use App\Models\Booking;

interface BookingServiceInterface
{
    function all();

    function cancel(Booking $booking);

    function update(array $data, Booking $booking);

    function store(array $data);

    function storedCreditCard(array $data, Booking $booking);

    function storedCash(Booking $booking);
}
