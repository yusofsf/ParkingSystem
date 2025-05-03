@extends('layouts.app')

@section('title', 'Create Car')

@section('content')
    <div class="mx-auto w-full max-w-md p-6">
        <div class="rounded-lg bg-white p-8 shadow-lg">
            <h2 class="mb-8 text-center text-2xl font-bold">Create car</h2>

            <form id="carForm" class="space-y-4">
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input
                        type="text"
                        id="model"
                        name="model"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <input
                        type="text"
                        id="color"
                        name="color"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>

                <div>
                    <label for="dateOfManufacture" class="block text-sm font-medium text-gray-700">
                        date of manufacture
                    </label>
                    <input
                        type="text"
                        id="dateOfManufacture"
                        name="dateOfManufacture"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>

                <div>
                    <label for="licensePlateNumber" class="block text-sm font-medium text-gray-700">
                        license plate number
                    </label>
                    <input
                        type="text"
                        id="licensePlateNumber"
                        name="licensePlateNumber"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                </div>

                <div class="flex items-center justify-between">
                    <a
                        href="/dashboard"
                        class="mr-2 rounded-lg bg-gray-500 px-4 py-2 text-white transition-colors duration-300 hover:bg-gray-600"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Create New Car
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('carForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                model: document.getElementById('model').value,
                color: document.getElementById('color').value,
                date_of_manufacture: document.getElementById('dateOfManufacture').value,
                license_plate_number: document.getElementById('licensePlateNumber').value,
            };

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch('/api/cars', {
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
                    // Registration successful
                    alert(data.message);
                    window.location.href = `/users/${localStorage.getItem('user_id')}/cars`;
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
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    </script>
@endsection
