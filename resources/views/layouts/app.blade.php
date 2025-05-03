<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title', 'Parking System')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="mx-auto max-w-7xl px-4">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="/" class="text-xl font-bold text-gray-800">Parking System</a>
                        </div>
                        <div class="sm:ml-6 sm:flex sm:space-x-8" id="rightNav">
                            <a
                                href="/dashboard"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                            >
                                Dashboard
                            </a>
                            <a
                                href="/slots"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                            >
                                Parking Slots
                            </a>
                            <button
                                onclick="cars()"
                                id="carsButton"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                            >
                                Cars
                            </button>
                            <button
                                onclick="payments()"
                                id="paymentsButton"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                            >
                                Payments
                            </button>
                            <a
                                href="/users"
                                id="users"
                                class="hidden border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                            >
                                Users
                            </a>
                            <button
                                onclick="bookings()"
                                id="bookingsButton"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                            >
                                Bookings
                            </button>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center" id="logout">
                        <button
                            onclick="logout()"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container mx-auto px-4 py-8">
            @yield('content')
        </div>

        <script>
            async function logout() {
                try {
                    await fetch('/sanctum/csrf-cookie', {
                        credentials: 'include',
                    });

                    const response = await fetch(`/api/auth/logout`, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        },
                        credentials: 'include',
                    });

                    const data = JSON.parse(await response.text());

                    if (response.ok) {
                        localStorage.removeItem('token');
                        localStorage.removeItem('user_id');
                        localStorage.removeItem('user_role');
                        sessionStorage.removeItem('car_id');
                        sessionStorage.removeItem('slot_id');
                        window.location.href = '/';
                    } else {
                        if (response.status === 403) {
                            alert(data.message);
                        }
                    }
                } catch (error) {
                    console.error('Error Logout:', error);
                    showError();
                }
            }

            function cars() {
                if (Number(localStorage.getItem('user_role')) === 1) {
                    window.location.href = `/users/${localStorage.getItem('user_id')}/cars`;
                } else if (Number(localStorage.getItem('user_role')) === 3) {
                    window.location.href = `/cars`;
                }
            }

            function bookings() {
                if (Number(localStorage.getItem('user_role')) === 1) {
                    window.location.href = `/users/${localStorage.getItem('user_id')}/bookings`;
                } else if (Number(localStorage.getItem('user_role')) === 2) {
                    window.location.href = `/bookings`;
                }
            }

            function payments() {
                if (Number(localStorage.getItem('user_role')) === 1) {
                    window.location.href = `/users/${localStorage.getItem('user_id')}/payments`;
                } else if (Number(localStorage.getItem('user_role')) === 3) {
                    window.location.href = `/payments`;
                }
            }

            function showMenu(role) {
                const carsButton = document.getElementById('carsButton');
                const paymentsButton = document.getElementById('paymentsButton');
                const bookingsButton = document.getElementById('bookingsButton');
                const users = document.getElementById('users');

                if (!role) return;
                switch (role) {
                    case '2':
                        if (carsButton) carsButton.classList.add('hidden');
                        if (paymentsButton) paymentsButton.classList.add('hidden');
                        return;
                    case '3':
                        if (bookingsButton) bookingsButton.classList.add('hidden');
                        if (users) users.classList.remove('hidden');
                        if (users) users.classList.add('inline-flex');
                        if (users) users.classList.add('items-center');
                        return;
                }
            }

            if (localStorage.getItem('user_role') === null) {
                document.getElementById('rightNav').classList.remove('sm:flex');
                document.getElementById('logout').classList.remove('sm:flex');
                document.getElementById('rightNav').classList.add('hidden');
            } else {
                showMenu(localStorage.getItem('user_role'));
            }
        </script>
    </body>
</html>
