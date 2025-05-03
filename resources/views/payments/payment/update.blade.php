@extends('layouts.app')

@section('title', 'Update Payment')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-8 text-center text-3xl font-bold">Update Payment</h1>

        <div id="loadingState" class="py-12 text-center">
            <div
                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
            ></div>
            <p class="mt-2 text-gray-600">Loading payment data...</p>
        </div>

        <div id="updateForm" class="hidden rounded-lg bg-white p-6 shadow-md">
            <form id="paymentForm" class="space-y-4">
                <div>
                    <label for="totalPrice" class="block text-sm font-medium text-gray-700">Total Price</label>
                    <input
                        type="text"
                        id="totalPrice"
                        name="totalPrice"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        disabled
                    />
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">type</label>
                    <input
                        type="text"
                        id="type"
                        name="type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        disabled
                    />
                </div>

                <div>
                    <label for="details" class="block text-sm font-medium text-gray-700">details</label>
                    <input
                        type="text"
                        id="details"
                        name="details"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="/dashboard" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Update Payment
                    </button>
                </div>
            </form>
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden">
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-center">
                <svg class="mx-auto h-6 w-6 text-red-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Payment</h3>
                <p class="mt-1 text-sm text-red-600">
                    There was a problem loading the payment data. Please try again later.
                </p>
                <a
                    href="/payments"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                >
                    Back to Payments
                </a>
            </div>
        </div>
    </div>

    <script>
        let paymentId = window.location.pathname.split('/')[2];

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('updateForm').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showForm() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('updateForm').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('updateForm').classList.add('hidden');
            document.getElementById('errorMessage').classList.remove('hidden');
        }

        async function fetchPayment() {
            showLoading();

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch(`/api/payments/${paymentId}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (!response.ok) {
                    if (response.status === 404) {
                        alert(data.message);
                        window.location.href = '/payments';
                        return;
                    }
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                        return;
                    }
                    throw new Error(`Failed to fetch payment: ${response.status}`);
                }

                if (data.result) {
                    populateForm(data.result);
                    showForm();
                } else {
                    throw new Error('Invalid payment data received');
                }
            } catch (error) {
                console.error('Error fetching payment:', error);
                showError();
            }
        }

        function populateForm(payment) {
            document.getElementById('totalPrice').value = payment.total_price;
            document.getElementById('type').value = payment.type;
            document.getElementById('details').value = payment.details;
        }

        document.getElementById('paymentForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                total_price: document.getElementById('totalPrice').value,
                type: document.getElementById('type').value,
                details: document.getElementById('details').value,
            };

            try {
                // First get CSRF token
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch(`/api/payments/${paymentId}`, {
                    method: 'PATCH',
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
                    alert(data.message);
                    window.location.href = '/payments';
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
                        alert(data.message || 'Failed to update payment. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error updating payment:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Fetch payment data when the page loads
        fetchPayment();
    </script>
@endsection
