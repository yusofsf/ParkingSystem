<?php

use App\Models\Car;
use App\Models\ParkingSlot;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Car::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(ParkingSlot::class)->constrained()->onDelete('cascade');
            $table->dateTime('begin');
            $table->dateTime('end');
            $table->boolean('is_paid');
            $table->boolean('cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
