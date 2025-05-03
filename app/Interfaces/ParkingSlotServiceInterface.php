<?php

namespace App\Interfaces;

use App\Models\ParkingSlot;

interface ParkingSlotServiceInterface
{
    function priceDetails(ParkingSlot $parkingSlot);

    function calcTotalPrice(ParkingSlot $parkingSlot);

    function update(array $data, ParkingSlot $parkingSlot);

    function search(string $search);

    function store(array $data);

    function sort(string $sortBy, string $order);
}
