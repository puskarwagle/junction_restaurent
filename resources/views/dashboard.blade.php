<x-app-layout>
    <!-- Dashboard Container -->
    <div class="flex h-screen bg-gray-100">

        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white p-4">
            <div class="text-2xl font-semibold mb-8">Dashboard</div>
            <ul>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Overview</a></li>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Analytics</a></li>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Settings</a></li>
                <li><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Welcome to Your Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-gray-600">Hello, User</div>
                    <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-full">
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Total Users</h2>
                    <p class="text-2xl font-bold text-blue-800">1,245</p>
                </div>

                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Revenue</h2>
                    <p class="text-2xl font-bold text-green-800">$12,345</p>
                </div>

                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Orders</h2>
                    <p class="text-2xl font-bold text-red-800">789</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
                <ul class="space-y-4">
                    <li class="flex justify-between">
                        <span class="text-gray-600">New user registration</span>
                        <span class="text-gray-400 text-sm">2 mins ago</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Order placed #456</span>
                        <span class="text-gray-400 text-sm">15 mins ago</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Payment received</span>
                        <span class="text-gray-400 text-sm">30 mins ago</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
