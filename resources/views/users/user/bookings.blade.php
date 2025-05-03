@extends('layouts.app')

@section('title', 'All Your Booking')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="mb-8 text-center text-3xl font-bold">Your Bookings</h1>
        <!-- Loading State -->
        <div id="loadingState" class="py-12 text-center">
            <div
                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
            ></div>
            <p class="mt-2 text-gray-600">Loading booking...</p>
        </div>

        <!-- Bookings Grid -->
        <div id="bookingsContainer" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Bookings will be loaded here -->
        </div>

        <!-- No Bookings Message -->
        <div id="noBookingsMessage">
            <div class="rounded-lg bg-white p-8 text-center shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Bookings Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no booking records available at the moment.</p>
            </div>
        </div>

        <div id="bookParking">
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-center">
                <p class="mt-2 text-sm text-green-600">Book Parking Now</p>
                <a
                    href="/slots"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                >
                    Book Parking
                </a>
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
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Bookings</h3>
                <p class="mt-1 text-sm text-red-600">
                    There was a problem loading the bookings. Please try again later.
                </p>
                <button
                    onclick="fetchBookings()"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                >
                    Try Again
                </button>
            </div>
        </div>
    </div>

    <script>
        const isManager = Number(localStorage.getItem('user_role')) === 2;
        const isUser = Number(localStorage.getItem('user_role')) === 1;

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('bookingsContainer').classList.add('hidden');
            document.getElementById('noBookingsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('bookParking').classList.add('hidden');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('bookingsContainer').classList.add('hidden');
            document.getElementById('noBookingsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.remove('hidden');
            document.getElementById('bookParking').classList.add('hidden');
        }

        function showNoBookings() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('bookingsContainer').classList.add('hidden');
            document.getElementById('noBookingsMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('bookParking').classList.remove('hidden');
        }

        function showBookings() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('bookingsContainer').classList.remove('hidden');
            document.getElementById('noBookingsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('bookParking').classList.add('hidden');
        }

        async function fetchBookings() {
            showLoading();

            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include',
            });

            try {
                const response = await fetch(`/api/users/${localStorage.getItem('user_id')}/bookings`, {
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
                        displayBookings(data.result);
                        showBookings();
                    } else {
                        showNoBookings();
                    }
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
                        alert(data.message || 'Failed to update car. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error fetching bookings:', error);
                showError();
            }
        }

        function displayBookings(bookings) {
            const container = document.getElementById('bookingsContainer');
            container.innerHTML = bookings
                .map(
                    (booking) => `
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold"># ${booking.id}</h3>
                    </div>
                    <div class="space-y-2">
                    <div class="flex justify-between">
                        <p class="text-gray-600">
                            <span class="font-medium">Begin:</span>
                            ${booking.begin}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">End:</span>
                            ${booking.end}
                        </p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-gray-600">
                            <span class="font-medium">Is Paid:</span>
                            ${booking.is_paid ? 'paid before' : 'not paid'}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Cancelled:</span>
                            ${booking.cancelled ? 'canceled before' : 'not canceled'}
                        </p>
                    </div>
                            <div class="mt-4 flex space-x-2">
                            ${
                                isManager
                                    ? `
                                <a href="/bookings/${booking.id}/update"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    update
                                </a>
                            `
                                    : ``
                            }
                            ${
                                !booking.cancelled && isUser
                                    ? `<button onclick="cancelBooking(${booking.id})"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Cancel
                                    </button>`
                                    : ``
                            }
                        </div>
                    </div>
                </div>
            `,
                )
                .join('');
        }

        async function cancelBooking(bookingId) {
            if (!confirm('Are you sure you want to cancel this booking?')) {
                return;
            }

            try {
                const response = await fetch(`/api/bookings/${bookingId}/cancel`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    alert('Booking canceled successfully');
                    await fetchBookings(); // Refresh the list
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        // window.location.href = '/dashboard';
                        return;
                    }
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
                console.error('Error canceling booking:', error);
                alert('Failed to cancel booking. Please try again.');
            }
        }

        // Fetch bookings when the page loads
        fetchBookings();
    </script>
@endsection
