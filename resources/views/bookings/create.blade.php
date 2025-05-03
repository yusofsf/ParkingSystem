@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-8 text-center text-3xl font-bold">Create Booking</h1>
        <!-- Payment Form -->
        <div class="rounded-lg bg-white p-6 shadow-md">
            <form id="confirmForm" class="space-y-4">
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type</label>
                    <select
                        id="payment_type"
                        name="payment_type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                        <option value="">Select Payment Type</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                    </select>
                </div>

                <div>
                    <label for="booking_begin" class="block text-sm font-medium text-gray-700">Booking Start</label>
                    <input
                        type="datetime-local"
                        id="booking_begin"
                        name="booking_begin"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>

                <div>
                    <label for="booking_end" class="block text-sm font-medium text-gray-700">Booking End</label>
                    <input
                        type="datetime-local"
                        id="booking_end"
                        name="booking_end"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>
                <div class="flex items-center justify-between pt-4">
                    <button onclick="cancel()" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </form>
            <form id="paymentForm" class="hidden space-y-4">
                <!-- Price Summary -->
                <div id="priceSummary" class="rounded-lg bg-gray-50 p-4">
                    <h3 class="mb-2 text-lg font-medium text-gray-900">Price Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Hours:</span>
                            <span id="totalHours" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Days:</span>
                            <span id="totalDays" class="font-medium">0</span>
                        </div>
                        <div class="mt-2 border-t pt-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-medium text-gray-900">Total Amount:</span>
                                <span id="totalAmount" class="text-lg font-bold text-blue-600">$0.00</span>
                                <span class="text-lg font-medium text-gray-900">Tax:</span>
                                <span id="tax" class="text-lg font-bold text-blue-600">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4">
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let bookingId;
        document.getElementById('confirmForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const bookingBegin = document.getElementById('booking_begin');
            const bookingEnd = document.getElementById('booking_end');

            if (!bookingBegin || !bookingEnd) {
                alert('Please fill in all required fields');
                return;
            }

            if (bookingBegin.value > bookingEnd.value) {
                alert('Enter Start date must be earlier');
                return;
            }

            const formData = {
                begin: bookingBegin.value,
                end: bookingEnd.value,
                car_id: Number(sessionStorage.getItem('car_id')),
                slot_id: Number(sessionStorage.getItem('slot_id')),
            };

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch('/api/bookings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                    body: JSON.stringify(formData),
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    bookingId = data.result.id;
                    priceSummary(data.details);
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                        return;
                    }
                    if (data.errors) {
                        let errorMessage = '';
                        Object.keys(data.errors).forEach((key) => {
                            errorMessage += data.errors[key] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert(data.message || 'Failed to update booking. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        const paymentForm = document.getElementById('paymentForm');
        const confirmForm = document.getElementById('confirmForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const paymentType = document.getElementById('payment_type');
                if (!paymentType) return;

                if (paymentType.value === 'cash') {
                    await storeCash(bookingId);
                } else {
                    window.location.href = `/bookings/${bookingId}/storeCreditCard`;
                }
            });
        }

        async function storeCash(bookingId) {
            try {
                const response = await fetch(`/api/bookings/${bookingId}/payments/cash`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    window.location.href = `/payments/${data.result.id}/pay`;
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                        return;
                    }
                    if (data.errors) {
                        let errorMessage = '';
                        Object.keys(data.errors).forEach((key) => {
                            errorMessage += data.errors[key][0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert(data.message || 'Failed to update booking. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }

        function priceSummary(details) {
            const totalHours = document.getElementById('totalHours');
            const totalDays = document.getElementById('totalDays');
            const totalAmount = document.getElementById('totalAmount');
            const tax = document.getElementById('tax');

            if (!totalHours || !totalDays || !totalAmount || !tax || !priceSummary) return;

            totalHours.textContent = details.hours;
            totalDays.textContent = details.days;
            totalAmount.textContent = details.total_price;
            tax.textContent = details.tax;
            paymentForm.classList.remove('hidden');
            confirmForm.classList.add('hidden');
        }

        function cancel() {
            sessionStorage.removeItem('car_id');
            sessionStorage.removeItem('slot_id');
        }
    </script>
@endsection
