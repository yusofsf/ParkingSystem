@extends('layouts.app')

@section('title', 'Pay')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-8 text-center text-3xl font-bold">Pay</h1>
        <div class="rounded-lg bg-white p-6 shadow-md">
            <div id="loading" class="py-8 text-center">
                <div class="mx-auto h-12 w-12 animate-spin rounded-full border-b-2 border-indigo-600"></div>
                <p class="mt-4 text-gray-600">Loading payment details...</p>
            </div>

            <!-- Cash Payment Form -->
            <div id="cashPaymentForm" class="hidden">
                <div class="mb-6">
                    <h2 class="mb-4 text-xl font-semibold">Cash Payment</h2>
                    <p class="mb-4 text-gray-600">Please pay the amount when arriving at the parking slot.</p>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-lg font-medium">
                            Amount to Pay:
                            <span id="amount" class="text-indigo-600"></span>
                        </p>
                    </div>
                </div>
                <div class="flex justify-between">
                    <button
                        onclick="processPayment()"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Confirm Payment
                    </button>
                    <button
                        onclick="cancel()"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Cancel
                    </button>
                </div>
            </div>

            <!-- Credit Card Payment Form -->
            <div id="creditCardPaymentForm" class="hidden">
                <div class="mb-6">
                    <h2 class="mb-4 text-xl font-semibold">Credit Card Payment</h2>
                    <div class="mb-4 rounded-lg bg-gray-50 p-4">
                        <p class="text-lg font-medium">
                            Amount to Pay:
                            <span id="creditCardAmount" class="text-indigo-600"></span>
                        </p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Enter Amount</label>
                            <input
                                type="number"
                                id="amountInput"
                                name="amount"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            />
                        </div>
                    </div>
                </div>
                <div class="flex justify-between">
                    <button
                        onclick="processPayment()"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Process Payment
                    </button>
                    <a
                        href="/dashboard"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let paymentId = window.location.pathname.split('/')[2];

        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const response = await fetch(`/api/payments/${paymentId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                const data = JSON.parse(await response.text());

                const payment = data.result;

                if (!response.ok) {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }

                document.getElementById('loading').classList.add('hidden');

                if (payment.type === 'Cash Payment') {
                    document.getElementById('cashPaymentForm').classList.remove('hidden');
                    document.getElementById('amount').textContent = Math.ceil(payment.total_price);
                } else if (payment.type === 'Credit Card Payment') {
                    document.getElementById('creditCardPaymentForm').classList.remove('hidden');
                    document.getElementById('creditCardAmount').textContent = payment.total_price;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load payment details. Please try again.');
                window.location.href = '/dashboard';
            }
        });

        async function processPayment() {
            const price =  Number(document.getElementById('amountInput').value);
            try {
                const response = await fetch(`/api/payments/${paymentId}/pay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    body: JSON.stringify({price}),
                });

                sessionStorage.removeItem('car_id');
                sessionStorage.removeItem('slot_id');

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    alert(data.message);
                    window.location.href = '/dashboard';
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = '';
                        Object.keys(data.errors).forEach((key) => {
                            errorMessage += data.errors[key][0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert(data.message || 'Failed to update car. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Failed to process payment. Please try again.');
            }
        }
        function cancel() {
            sessionStorage.removeItem('car_id');
            sessionStorage.removeItem('slot_id');
        }
    </script>
@endsection
