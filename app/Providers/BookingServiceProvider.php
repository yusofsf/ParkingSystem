<?php

namespace App\Providers;

use App\Interfaces\BookingServiceInterface;
use App\Models\Booking;
use App\Models\ParkingSlot;
use App\Policies\BookingPolicy;
use App\Services\BookingService;
use App\Services\ParkingSlotService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BookingServiceInterface::class, function (Application $app) {
            return new BookingService($app->make(ParkingSlotService::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(Booking::class, BookingPolicy::class);
        Route::model('slot', ParkingSlot::class);
    }
}
