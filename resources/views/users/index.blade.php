@extends('layouts.app')

@section('title', 'All Users')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="mb-8 text-center text-3xl font-bold">All Users</h1>
        <div id="loadingState" class="py-12 text-center">
            <div
                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
            ></div>
            <p class="mt-2 text-gray-600">Loading users...</p>
        </div>

        <!-- Users Grid -->
        <div id="usersContainer" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Users will be loaded here -->
        </div>

        <!-- No Users Message -->
        <div id="noUsersMessage">
            <div class="rounded-lg bg-white p-8 text-center shadow-md">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="hidden" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Users Found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no user records available at the moment.</p>
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
                <h3 class="mt-2 text-sm font-medium text-red-800">Error Loading Users</h3>
                <p class="mt-1 text-sm text-red-600">There was a problem loading the users. Please try again later.</p>
                <button
                    onclick="fetchUsers()"
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
            document.getElementById('usersContainer').classList.add('hidden');
            document.getElementById('noUsersMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('usersContainer').classList.add('hidden');
            document.getElementById('noUsersMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.remove('hidden');
        }

        function showNoUsers() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('usersContainer').classList.add('hidden');
            document.getElementById('noUsersMessage').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function showUsers() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('usersContainer').classList.remove('hidden');
            document.getElementById('noUsersMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
        }

        async function fetchUsers() {
            showLoading();

            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include',
            });

            try {
                const response = await fetch('/api/users', {
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
                        window.location.href = '/dashboard';
                        return;
                    }
                    throw new Error(`Failed to fetch user: ${response.status}`);
                } else {
                    if (data.result && data.result.length > 0) {
                        displayUsers(data.result);
                        showUsers();
                    } else {
                        showNoUsers();
                    }
                }
            } catch (error) {
                console.error('Error fetching users:', error);
                showError();
            }
        }

        function displayUsers(users) {
            const container = document.getElementById('usersContainer');
            container.innerHTML = users
                .map(
                    (user) => `
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">${user.id}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-red-600">
                            ${getUserRole(user.role)}
                        </span>
                    </div>
                    <div class="space-y-2">
                        <p class="text-gray-600">
                            <span class="font-medium">full name:</span>
                            ${user.full_name}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">User name:</span>
                            ${user.user_name}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">email:</span>
                            ${user.email}
                        </p>
                        ${user.role !== 3 ? `
                        <div class="mt-4 flex space-x-2">
                            <button onclick="deleteUser(${user.id})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete
                            </button>
                        </div>` : ``
                    }
                    </div>
                </div>
            `,
                )
                .join('');
        }

        async function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user?')) {
                return;
            }

            try {
                const response = await fetch(`/api/users/${userId}`, {
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
                    await fetchUsers(); // Refresh the list
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                    }
                }
            } catch (error) {
                console.error('Error deleting user:', error);
                alert('Failed to delete user. Please try again.');
            }
        }

        function getUserRole(role) {
            switch (role) {
                case 1:
                    return 'Customer';
                case 2:
                    return 'Manager';
                case 3:
                    return 'Admin';
            }
        }

        // Fetch users when the page loads
        fetchUsers();
    </script>
@endsection
