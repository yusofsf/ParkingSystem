<?php

namespace App\Providers;

use App\Interfaces\ParkingSlotServiceInterface;
use App\Models\ParkingSlot;
use App\Policies\ParkingSlotPolicy;
use App\Services\ParkingSlotService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ParkingSlotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ParkingSlotServiceInterface::class, function () {
            return new ParkingSlotService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(ParkingSlot::class, ParkingSlotPolicy::class);
    }
}
