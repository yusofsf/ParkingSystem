<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Register - Parking System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="mx-auto w-full max-w-md p-6">
            <div class="rounded-lg bg-white p-8 shadow-lg">
                <h2 class="mb-6 text-center text-2xl font-bold">Create an Account</h2>

                <form id="registerForm" class="space-y-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label for="user_name" class="block text-sm font-medium text-gray-700">User Name</label>
                        <input
                            type="text"
                            id="user_name"
                            name="user_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Register
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">Sign in</a>
                    </p>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('registerForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                const formData = {
                    full_name: document.getElementById('full_name').value,
                    user_name: document.getElementById('user_name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value,
                };

                try {
                    const response = await fetch('/api/auth/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    const data = JSON.parse(await response.text());

                    if (response.ok) {
                        localStorage.setItem('token', data.token);

                        localStorage.setItem('user_id', data.user.id);

                        localStorage.setItem('user_role', data.user.role);
                        // Registration successful
                        alert(data.message);
                        window.location.href = '/dashboard';
                    } else {
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
    </body>
</html>
