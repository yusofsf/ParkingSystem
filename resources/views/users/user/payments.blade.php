@extends('layouts.app')

@section('title', 'All Your Payments')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="mb-8 text-center text-3xl font-bold">Your Payments</h1>
        <div id="loadingState" class="py-12 text-center">
            <div
                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
            ></div>
            <p class="mt-2 text-gray-600">Loading payments...</p>
        </div>

        <!-- Payments Grid -->
        <div id="paymentsContainer" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Payments will be loaded here -->
        </div>

        <!-- No Payments Message -->
        <div id="noPaymentsMessage">
            <div class="rounded-lg bg-white p-8 text-center shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Payments Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no payment records available at the moment.</p>
            </div>
        </div>

        <!-- Error Message -->
        <div id="errorMessage">
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-center">
                <svg class="mx-auto h-6 w-6 text-red-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Payments</h3>
                <p class="mt-1 text-sm text-red-600">
                    There was a problem loading the payments. Please try again later.
                </p>
                <button
                    onclick="fetchPayments()"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                >
                    Try Again
                </button>
            </div>
        </div>
    </div>

    <script>
        const isAdmin = Number(localStorage.getItem('user_role')) === 3;

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('paymentsContainer').classList.add('hidden');
            document.getElementById('noPaymentsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('paymentsContainer').classList.add('hidden');
            document.getElementById('noPaymentsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.remove('hidden');
        }

        function showNoPayments() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('paymentsContainer').classList.add('hidden');
            document.getElementById('noPaymentsMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showPayments() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('paymentsContainer').classList.remove('hidden');
            document.getElementById('noPaymentsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        async function fetchPayments() {
            showLoading();

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });
                const response = await fetch(`/api/users/${localStorage.getItem('user_id')}/payments`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    if (data.result && data.result.length > 0) {
                        displayPayments(data.result);
                        showPayments();
                    } else {
                        showNoPayments();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error fetching payments:', error);
                showError();
            }
        }

        function displayPayments(payments) {
            const container = document.getElementById('paymentsContainer');
            container.innerHTML = payments
                .map(
                    (payment) => `
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">${payment.id}</h3>
                        <span class="py-1 rounded-full text-sm font-medium bg-gray-100 text-blue-800">
                           Details: ${payment.details ? payment.details : `No Details`}
                        </span>
                    </div>
                    <div class="flex mt-3 justify-between">
                        <span class="py-1 rounded-full text-sm font-medium text-red-800">
                            Price: ${payment.total_price}
                        </span>
                        <span class="rounded-full text-sm font-medium
                           ${
                               payment.type === 'credit_card'
                                   ? 'bg-green-100 text-green-800'
                                   : payment.type === 'cash'
                                     ? 'bg-blue-100 text-blue-800'
                                     : 'bg-gray-100 text-gray-800'
                           }">
                           Type: ${payment.type}
                        </span>
                    </div>

                </div>
            `,
                )
                .join('');
        }

        // Fetch payments when the page loads
        fetchPayments();
    </script>
@endsection
