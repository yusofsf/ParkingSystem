@extends('layouts.app')

@section('title', 'Update Slot')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mx-auto max-w-2xl">
            <h1 class="mb-8 text-center text-3xl font-bold">Update This Slot</h1>

            <div id="loadingState" class="py-12 text-center">
                <div
                    class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
                ></div>
                <p class="mt-2 text-gray-600">Loading slots...</p>
            </div>

            <div id="updateForm" class="rounded-lg bg-white p-6 shadow-md">
                <form id="slotForm" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Slot Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label for="price_per_hour" class="block text-sm font-medium text-gray-700">
                            Price per Hour ($)
                        </label>
                        <input
                            type="number"
                            id="price_per_hour"
                            name="price_per_hour"
                            step="0.1"
                            min="0"
                            max="100"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label for="price_per_day" class="block text-sm font-medium text-gray-700">
                            Price per Day ($)
                        </label>
                        <input
                            type="number"
                            id="price_per_day"
                            name="price_per_day"
                            step="0.1"
                            min="0"
                            max="100"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select
                            id="status"
                            name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                            <option value="1">Available</option>
                            <option value="0">Not Available</option>
                        </select>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">slotType</label>
                        <select
                            id="type"
                            name="type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                            <option value="3">PREMIUM</option>
                            <option value="2">BUSINESS</option>
                            <option value="1">ECONOMIC</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a href="/dashboard" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                            Update Slot
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
                    <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Slot</h3>
                    <p class="mt-1 text-sm text-red-600">
                        There was a problem loading the slot data. Please try again later.
                    </p>
                    <a
                        href="/slots"
                        class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    >
                        Back to Slots
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let slotId = window.location.pathname.split('/')[2];

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

        async function fetchSlot() {
            showLoading();

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch(`/api/slots/${slotId}/show`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    if (data.result) {
                        populateForm(data.result);
                        showForm();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/slots';
                    }
                    if (response.status === 404) {
                        alert(data.message);
                        window.location.href = '/slots';
                    }
                }
            } catch (error) {
                console.error('Error fetching slots:', error);
                showError();
            }
        }

        function populateForm(slot) {
            document.getElementById('name').value = slot.name;
            document.getElementById('price_per_hour').value = slot.price_per_hour;
            document.getElementById('price_per_day').value = slot.price_per_day;
            document.getElementById('status').value = slot.available;
            document.getElementById('type').value = slot.type;
        }

        document.getElementById('slotForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                name: document.getElementById('name').value,
                price_per_hour: document.getElementById('price_per_hour').value,
                price_per_day: document.getElementById('price_per_day').value,
                available: document.getElementById('status').value,
                type: document.getElementById('type').value,
            };

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch(`/api/slots/${slotId}`, {
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
                    window.location.href = '/slots';
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
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        // Fetch slot data when the page loads
        fetchSlot();
    </script>
@endsection
