<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Parking System</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <div class="relative flex min-h-screen flex-col items-center justify-center">
                <div class="w-full max-w-4xl px-6 py-16">
                    <div class="mb-12 text-center">
                        <h1 class="mb-4 text-4xl font-bold text-gray-900">Welcome to Parking System</h1>
                        <p class="text-xl text-gray-600">Find and book your parking spot with ease</p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="rounded-lg bg-white p-8 shadow-lg transition-shadow duration-300 hover:shadow-xl">
                            <h2 class="mb-4 text-2xl font-semibold text-gray-900">Explore Parking Slots</h2>
                            <p class="mb-6 text-gray-600">
                                Find available parking slots and book your spot in advance.
                            </p>
                            <a
                                href="/slots"
                                class="inline-block rounded-lg bg-blue-600 px-6 py-3 text-white transition-colors duration-300 hover:bg-blue-700"
                            >
                                View Slots
                            </a>
                        </div>
                        <div
                            class="rounded-lg bg-green-100 p-8 shadow-lg transition-shadow duration-300 hover:shadow-xl"
                        >
                            <h2 class="mb-4 text-2xl font-semibold text-gray-900">Get Started</h2>
                            <p class="mb-6 text-gray-600">Create an account or login to manage your bookings.</p>
                            <div id="guest" class="space-y-4">
                                <a
                                    href="/login"
                                    class="block w-full rounded-lg bg-red-400 px-6 py-3 text-center text-white transition-colors duration-300 hover:bg-green-700"
                                >
                                    Login
                                </a>
                                <a
                                    href="/register"
                                    class="block w-full rounded-lg bg-gray-600 px-6 py-3 text-center text-white transition-colors duration-300 hover:bg-gray-700"
                                >
                                    Register
                                </a>
                            </div>
                            <div id="auth">
                                <a
                                    href="/dashboard"
                                    class="block w-full rounded-lg bg-blue-600 px-6 py-3 text-center text-white transition-colors duration-300 hover:bg-blue-700"
                                >
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            if (localStorage.getItem('token') === null) {
                document.getElementById('guest').classList.add('hidden');
            } else {
                document.getElementById('auth').classList.add('hidden');
            }
        </script>
    </body>
</html>
