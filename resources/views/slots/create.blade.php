@extends('layouts.app')

@section('title', 'Create Slot')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mx-auto max-w-2xl">
            <h1 class="mb-8 text-center text-3xl font-bold">Create New Parking Slot</h1>

            <form id="slotForm" class="rounded-lg bg-white p-6 shadow-md">
                <div class="mb-6">
                    <label for="name" class="mb-2 block text-sm font-bold text-gray-700">Slot Name</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div class="mb-6">
                    <label for="price_per_hour" class="mb-2 block text-sm font-bold text-gray-700">
                        Price per Hour ($)
                    </label>
                    <input
                        type="number"
                        name="price_per_hour"
                        id="price_per_hour"
                        step="0.01"
                        min="0"
                        class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div class="mb-6">
                    <label for="price_per_day" class="mb-2 block text-sm font-bold text-gray-700">
                        Price per Day ($)
                    </label>
                    <input
                        type="number"
                        name="price_per_day"
                        id="price_per_day"
                        step="0.01"
                        min="0"
                        class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div class="mb-6">
                    <label for="status" class="mb-2 block text-sm font-bold text-gray-700">Status</label>
                    <select
                        name="status"
                        id="status"
                        class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="1">Available</option>
                        <option value="0">Not Available</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="slotType" class="mb-2 block text-sm font-bold text-gray-700">type</label>
                    <select
                        name="slotType"
                        id="slotType"
                        class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="3">PREMIUM</option>
                        <option value="2">BUSINESS</option>
                        <option value="1">ECONOMIC</option>
                    </select>
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
                        class="rounded-lg bg-blue-600 px-4 py-2 text-white transition-colors duration-300 hover:bg-blue-700"
                    >
                        Create Slot
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('slotForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                name: document.getElementById('name').value,
                price_per_day: document.getElementById('price_per_day').value,
                price_per_hour: document.getElementById('price_per_hour').value,
                available: document.getElementById('status').value,
                type: Number(document.getElementById('slotType').value),
            };

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });

                const response = await fetch('/api/slots', {
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
                    alert(data.message);
                    window.location.href = `/slots`;
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
