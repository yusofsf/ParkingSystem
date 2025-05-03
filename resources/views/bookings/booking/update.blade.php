@extends('layouts.app')

@section('title', 'Update Booking')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-8 text-center text-3xl font-bold">Update This Booking</h1>
        <!-- Loading State -->
        <div id="loadingState" class="py-12 text-center">
            <div
                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
            ></div>
            <p class="mt-2 text-gray-600">Loading payment data...</p>
        </div>

        <div id="updateForm" class="rounded-lg bg-white p-6 shadow-md">
            <form id="bookingForm" class="space-y-4">
                <div>
                    <label for="is_paid" class="block text-sm font-medium text-gray-700">is Paid: </label>
                    <select name="is_paid"
                            id="is_paid"
                            class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="1">paid before</option>
                        <option value="0">not paid</option>
                    </select>
                </div>

                <div>
                    <label for="cancelled" class="block text-sm font-medium text-gray-700">Cancelled: </label>
                    <select name="cancelled"
                            id="cancelled"
                            class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="1">cancelled before</option>
                        <option value="0">not canceled</option>
                    </select>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="/dashboard" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Update Booking
                    </button>
                </div>
            </form>
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
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Booking</h3>
                <p class="mt-1 text-sm text-red-600">
                    There was a problem loading the booking data. Please try again later.
                </p>
                <a
                    href="/bookings"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                >
                    Back to Bookings
                </a>
            </div>
        </div>
    </div>

    <script>
        let bookingId = window.location.pathname.split('/')[2];

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

        async function fetchBooking() {
            showLoading();

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch(`/api/bookings/${bookingId}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (!response.ok) {
                    if (response.status === 403) {
                        alert(data.message);
                    } else if (response.status === 404) {
                        alert(data.message);
                    }
                    window.location.href = '/dashboard';
                } else {
                    if (data.result) {
                        populateForm(data.result);
                        showForm();
                    } else {
                        throw new Error('Invalid booking data received');
                    }
                }
            } catch (error) {
                console.error('Error fetching bookings:', error);
                showError();
            }
        }

        function populateForm(booking) {
            document.getElementById('is_paid').value = booking.is_paid;
            document.getElementById('cancelled').value = booking.cancelled;
        }

        document.getElementById('bookingForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                is_paid: document.getElementById('is_paid').value,
                cancelled: document.getElementById('cancelled').value,
            };

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch(`/api/bookings/${bookingId}/update`, {
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
                    window.location.href = '/bookings';
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                        return;
                    } else if (response.status === 404) {
                        alert(data.message);
                        window.location.href = '/bookings';
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
        });

        // Fetch booking data when the page loads
        fetchBooking();
    </script>
@endsection
