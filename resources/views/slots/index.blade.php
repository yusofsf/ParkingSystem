@extends('layouts.app')

@section('title', 'All Slots')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 id="header-default" class="mb-8 text-center text-3xl font-bold">All Slots</h1>
        <h1 id="header-user" class="mb-8 text-center text-3xl font-bold">Book Your Favorite Slot</h1>

        <!-- Search and Sort Controls -->
        <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row">
            <div class="w-full md:w-1/3">
                <input type="text"
                    id="searchInput"
                    placeholder="Search slots..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="flex gap-4">
                <select id="sortField"
                    class="rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <option value="id">Sort by ID</option>
                    <option value="name">Sort by Name</option>
                    <option value="price_per_day">Sort by Price/Day</option>
                    <option value="price_per_hour">Sort by Price/Hour</option>
                    <option value="type">Sort by Type</option>
                </select>
                <select id="sortOrder"
                    class="rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

        <div id="loadingState" class="py-12 text-center hidden">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent">
            </div>
                <p class="mt-2 text-gray-600">Loading slots...</p>
        </div>

        <!-- Slots Grid -->
        <div id="slotsContainer" class="mb-3 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Slots will be loaded here -->
        </div>

        <!-- No Slots Message -->
        <div id="noSlotsMessage">
            <div class="rounded-lg bg-white p-8 text-center shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Slots Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no slot records available at the moment.</p>
            </div>
        </div>

        <div id="createDiv">
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-center">
                <p class="mt-2 text-sm text-green-600">Create Slot</p>
                <a href="/slots/create"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    create slot
                </a>
            </div>
        </div>

        <!-- Error Message -->
        <div id="errorMessage">
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-center">
                <svg class="mx-auto h-6 w-6 text-red-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Slots</h3>
                <p class="mt-1 text-sm text-red-600">There was a problem loading the slots. Please try again later.</p>
                <button onclick="fetchSlots()"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Try Again
                </button>
            </div>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('slotsContainer').classList.add('hidden');
            document.getElementById('noSlotsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('slotsContainer').classList.add('hidden');
            document.getElementById('noSlotsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.remove('hidden');
        }

        function showNoSlots() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('slotsContainer').classList.add('hidden');
            document.getElementById('noSlotsMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showSlots() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('slotsContainer').classList.remove('hidden');
            document.getElementById('noSlotsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        async function fetchSlots() {
            showLoading();

            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include',
            });

            try {
                const response = await fetch('/api/slots', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    if (data.result && data.result.length > 0) {
                        showSlots();
                        displaySlots(data.result);
                    } else {
                        showNoSlots();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error fetching slots:', error);
                showError();
            }
        }

        async function searchSlots() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include',
            });

            try {
                const response = await fetch(`/api/slots/search?search=${searchTerm}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    if (data.result && data.result.length > 0) {
                        showSlots();
                        displaySlots(data.result);
                    } else {
                        showNoSlots();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error fetching slots:', error);
                showError();
            }
        }

        async function filterAndSortSlots() {
            const sortField = document.getElementById('sortField').value;
            const sortOrder = document.getElementById('sortOrder').value;

            // Filter slots based on search term
            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include',
            });

            try {
                const response = await fetch(`/api/slots/sort?sortby=${sortField}&&order=${sortOrder}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    if (data.result && data.result.length > 0) {
                        showSlots();
                        displaySlots(data.result);
                    } else {
                        showNoSlots();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error fetching slots:', error);
                showError();
            }
        }

        // Add event listeners for search and sort controls
        document.getElementById('searchInput').addEventListener('input', searchSlots);
        document.getElementById('sortField').addEventListener('change', filterAndSortSlots);
        document.getElementById('sortOrder').addEventListener('change', filterAndSortSlots);

        function displaySlots(slots) {
            const container = document.getElementById('slotsContainer');
            const userRole = Number(localStorage.getItem('user_role'));
            const isAdmin = userRole === 3;
            const isManager = userRole === 2;
            const isUser = userRole === 1;

            container.innerHTML = slots
                .map(
                    (slot) => `
                <div class="bg-white rounded-lg shadow-md p-6 bg-cyan-300 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">${slot.id}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            ${getSlotType(slot.type)}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-600">
                            <span class="font-medium">Name:</span>
                            ${slot.name}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Available:</span>
                            ${slot.available ? 'Available' : 'Not Available'}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Price Per Day:</span>
                            ${slot.price_per_day.toFixed(2)}$/Day
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Price Per Hour:</span>
                            ${slot.price_per_hour.toFixed(2)}$/Hour
                        </p>
                        <div class="mt-4 flex space-x-2">
                            ${
                                isAdmin || isManager
                                    ? `
                                <a  href="/slots/${slot.id}/update"
                                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Update
                                </a>
                            `
                                    : ``
                            }
                            ${
                                isAdmin
                                    ? `
                                <button onclick="deleteSlot(${slot.id})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                </button>
                            `
                                    : ``
                            }
                            ${
                                isUser
                                    ? `
                            <button onclick="bookSlot(${slot.id})"
                                    class="${
                                        slot.available
                                            ? `text-blue-600 hover:text-blue-800 text-sm font-medium`
                                            : `text-red-400 hover:text-red-600 text-sm font-medium disabled`
                                    }">
                                ${slot.available ? 'Book Slot' : 'Booked'}
                            </button>
                            `
                                    : ``
                            }
                        </div>
                    </div>
                </div>
            `,
                )
                .join('');
        }

        async function deleteSlot(slotId) {
            if (!confirm('Are you sure you want to delete this slot?')) {
                return;
            }

            try {
                const response = await fetch(`/api/slots/${slotId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    alert(data.message);
                    await fetchSlots(); // Refresh the list
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/';
                    }
                }
            } catch (error) {
                console.error('Error deleting slot:', error);
                alert('Failed to delete slot. Please try again.');
            }
        }

        async function bookSlot(slotId) {
            sessionStorage.setItem('slot_id', slotId);
            if (sessionStorage.getItem('car_id')) {
                window.location.href = `/bookings/create`;
            } else {
                window.location.href = `/users/${localStorage.getItem('user_id')}/cars`;
            }
        }

        function getSlotType(type) {
            switch (type) {
                case 1:
                    return 'ECONOMIC';
                case 2:
                    return 'BUSINESS';
                case 3:
                    return 'PREMIUM';
            }
        }

        if (localStorage.getItem('user_role') === '1') {
            document.getElementById('header-default').classList.add('hidden');
            document.getElementById('createDiv').classList.add('hidden');
        } else if (localStorage.getItem('user_role') === '2') {
            document.getElementById('header-user').classList.add('hidden');
        } else {
            document.getElementById('header-user').classList.add('hidden');
            document.getElementById('createDiv').classList.add('hidden');
        }

        // Fetch slots when the page loads
        fetchSlots();
    </script>
@endsection
