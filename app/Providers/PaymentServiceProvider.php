<?php

namespace App\Providers;

use App\Interfaces\PaymentServiceInterface;
use App\Models\Payment;
use App\Policies\PaymentPolicy;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentServiceInterface::class, function () {
            return new PaymentService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(Payment::class, PaymentPolicy::class);
    }
}
