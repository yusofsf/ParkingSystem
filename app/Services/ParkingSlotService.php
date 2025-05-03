<?php

namespace App\Services;

use App\Interfaces\ParkingSlotServiceInterface;
use App\Models\ParkingSlot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ParkingSlotService implements ParkingSlotServiceInterface
{
    private const BASE_PRICE = 10;
    private const BASE_TAX_PER_DAY = 4;
    private int $days;

    private int $hours;

    function all(): Collection
    {
        return ParkingSlot::all();
    }

    function priceDetails(ParkingSlot $parkingSlot): array
    {
        return [
            'total_price' => $this->calcTotalPrice($parkingSlot),
            'tax' => $this->calcTax(),
            'hours' => $this->hours,
            'days' => $this->days
        ];
    }

    function calcTotalPrice(ParkingSlot $parkingSlot): float
    {
        return $this->calcSubTotal($parkingSlot) + $this->calcTax();
    }

    private function calcSubTotal(ParkingSlot $parkingSlot): float
    {
        $begin = Carbon::parse($parkingSlot->booking->begin);
        $end = Carbon::parse($parkingSlot->booking->end);
        $this->days = round($begin->diffInDays($end, true));
        $this->hours = ceil($begin->copy()->addDays($this->days)->diffInHours($end, true));

        return round(($parkingSlot->price_per_day * $this->days) +
            ($parkingSlot->price_per_hour * $this->hours) +
            ParkingSlotService::BASE_PRICE, 2);
    }

    private function calcTax(): float
    {
        return ($this->days * ParkingSlotService::BASE_TAX_PER_DAY);
    }

    function update(array $data, ParkingSlot $parkingSlot): ParkingSlot
    {
        $parkingSlot->update($data);

        return $parkingSlot;
    }

    function search(string $search): Collection
    {
        return ParkingSlot::where('name', 'like', "%$search%")->get();
    }

    function store(array $data)
    {
        return ParkingSlot::create($data);
    }

    function sort(string $sortBy, string $order)
    {
        return ParkingSlot::orderBy($sortBy, $order)->get();
    }
}
