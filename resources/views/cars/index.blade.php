@extends('layouts.app')

@section('title', 'All Cars')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="mb-8 text-center text-3xl font-bold">Cars</h1>
        <!-- Loading State -->
        <div id="loadingState" class="py-12 text-center">
            <div
                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
            ></div>
            <p class="mt-2 text-gray-600">loading cars...</p>
        </div>

        <!-- Cars Grid -->
        <div id="carsContainer" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Cars will be loaded here -->
        </div>

        <!-- No Cars Message -->
        <div id="noCarsMessage">
            <div class="rounded-lg bg-white p-8 text-center shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Cars Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no car records available at the moment.</p>
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
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Cars</h3>
                <p class="mt-1 text-sm text-red-600">There was a problem loading the cars. Please try again later.</p>
                <button
                    onclick="fetchCars()"
                    class="mt-4 inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                >
                    Try Again
                </button>
            </div>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('carsContainer').classList.add('hidden');
            document.getElementById('noCarsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('carsContainer').classList.add('hidden');
            document.getElementById('noCarsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.remove('hidden');
        }

        function showNoCars() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('carsContainer').classList.add('hidden');
            document.getElementById('noCarsMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showCars() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('carsContainer').classList.remove('hidden');
            document.getElementById('noCarsMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        async function fetchCars() {
            showLoading();

            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include',
            });

            try {
                const response = await fetch('/api/cars', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                });

                const data = JSON.parse(await response.text());

                console.log(data);

                if (response.ok) {
                    if (data.result && data.result.length > 0) {
                        displayCars(data.result);
                        showCars();
                    } else {
                        showNoCars();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error fetching cars:', error);
                showError();
            }
        }

        function displayCars(cars) {
            const container = document.getElementById('carsContainer');
            container.innerHTML = cars
                .map(
                    (car) => `
                <div class="rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">${car.id}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-800">
                            license: ${car.license_plate_number}
                        </span>
                    </div>
                    <div class="space-y-2">
                    <div class="flex justify-between">
                         <p class="text-gray-600">
                            <span class="font-medium">Model:</span>
                            ${car.model}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Color:</span>
                            <span class="inline-block w-4 h-4 rounded-full mr-2" style="background-color: ${car.color.toLowerCase()}"></span>
                            ${car.color}
                        </p>
                        </div>
                        <p class="text-gray-600">
                            <span class="font-medium">Manufacture Date:</span>
                            ${car.date_of_manufacture}
                        </p>
                        </br>
                        <h4 class="text-gray-400">Car Owner:</h4>
                        <div class="flex justify-between">
                            <p class="text-gray-600">
                                <span class="font-medium">User Name:</span>
                                ${car.user.user_name}
                            </p>
                            <p class="text-gray-600">
                                <span class="font-medium">Email:</span>
                                ${car.user.email}
                            </p>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="deleteCar(${car.id})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        async function deleteCar(carId) {
            if (!confirm('Are you sure you want to delete this car?')) {
                return;
            }

            try {
                const response = await fetch(`/api/cars/${carId}`, {
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
                    if (data.result && data.result.length > 0) {
                        displayCars(data.result);
                        showCars();
                    } else {
                        showNoCars();
                    }
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error deleting car:', error);
                alert('Failed to delete car. Please try again.');
            }
        }

        // Fetch cars when the page loads
        fetchCars();
    </script>
@endsection
