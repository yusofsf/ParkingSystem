@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex min-h-screen flex-col px-4 py-8">
        <h1 class="mb-8 text-center text-3xl font-bold">Welcome to dashboard</h1>
        <div id="dashboardContainer" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="flex flex-col rounded-lg border bg-cyan-500 p-4" id="carsDiv">
                <h3 id="cars-h3" class="mx-auto mb-2 text-lg font-semibold">Manage Your Cars</h3>
                <button onclick="cars()" class="mx-auto rounded bg-blue-600 px-4 py-2 text-white">Manage</button>
            </div>

            <div class="flex flex-col rounded-lg border bg-cyan-500 p-4">
                <h3 id="book-h3" class="mx-auto mb-2 text-lg font-semibold">Book Parking</h3>
                <a id="book-a" href="/slots" class="mx-auto rounded bg-blue-600 px-4 py-2 text-white">Book</a>
            </div>

            <div class="flex flex-col rounded-lg border bg-cyan-500 p-4" id="paymentsDiv">
                <h3 id="payments-h3" class="mx-auto mb-2 text-lg font-semibold">View Your Payments</h3>
                <button onclick="payments()" class="mx-auto rounded bg-blue-600 px-4 py-2 text-white">
                    View Payments
                </button>
            </div>

            <div class="flex flex-col rounded-lg border bg-cyan-500 p-4" id="usersDiv">
                <h3 class="mx-auto mb-2 text-lg font-semibold">View All Users</h3>
                <button id="users" onclick="users()" class="mx-auto rounded bg-blue-600 px-4 py-2 text-white">
                    View Users
                </button>
            </div>

            <div class="flex flex-col rounded-lg border bg-cyan-500 p-4" id="bookingsDiv">
                <h3 class="mx-auto mb-2 text-lg font-semibold" id="bookings-h3">View Your Bookings</h3>
                <button onclick="bookings()" class="mx-auto rounded bg-blue-600 px-4 py-2 text-white">Bookings</button>
            </div>
        </div>
    </div>
    <script>
        const userRole = Number(localStorage.getItem('user_role'));

        function cars() {
            if (userRole === 1) {
                window.location.href = `/users/${localStorage.getItem('user_id')}/cars`;
            } else if (userRole === 3) {
                window.location.href = `/cars`;
            }
        }

        function users() {
            if (userRole === 3) {
                window.location.href = `/users`;
            }
        }

        function bookings() {
            if (userRole === 1) {
                window.location.href = `/users/${localStorage.getItem('user_id')}/bookings`;
            } else if (userRole === 2) {
                window.location.href = `/bookings`;
            }
        }

        function payments() {
            if (userRole === 1) {
                window.location.href = `/users/${localStorage.getItem('user_id')}/payments`;
            } else if (userRole === 3) {
                window.location.href = `/payments`;
            }
        }

        function showOptions(role) {
            const carsDiv = document.getElementById('carsDiv');
            const usersDiv = document.getElementById('usersDiv');
            const paymentsDiv = document.getElementById('paymentsDiv');
            const bookingsDiv = document.getElementById('bookingsDiv');
            const bookingsh3 = document.getElementById('bookings-h3');
            const bookh3 = document.getElementById('book-h3');
            const booka = document.getElementById('book-a');
            const carsh3 = document.getElementById('cars-h3');
            const paymentsh3 = document.getElementById('payments-h3');

            if (!role) return;

            switch (role) {
                case '1':
                    if (usersDiv) usersDiv.classList.add('hidden');
                    break;
                case '2':
                    if (carsDiv) carsDiv.classList.add('hidden');
                    if (paymentsDiv) paymentsDiv.classList.add('hidden');
                    if (usersDiv) usersDiv.classList.add('hidden');
                    if (bookingsh3) bookingsh3.innerHTML = 'View All Bookings';
                    if (bookh3) bookh3.innerHTML = 'View All Slots';
                    if (booka) booka.innerHTML = 'Slots';
                    break;
                case '3':
                    if (bookingsDiv) bookingsDiv.classList.add('hidden');
                    if (bookh3) bookh3.innerHTML = 'View All Slots';
                    if (carsh3) carsh3.innerHTML = 'Manage All Cars';
                    if (paymentsh3) paymentsh3.innerHTML = 'View All Payments';
                    if (booka) booka.innerHTML = 'Slots';
                    break;
            }
        }

        // Show menu based on role
        showOptions(localStorage.getItem('user_role'));
    </script>
@endsection
