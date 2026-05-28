<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">🔒 Secure System</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Welcome, {{ Session::get('account_name') }}</span>
                    <a href="/profile" class="text-blue-600 hover:text-blue-800">Profile</a>
                    <a href="/logout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="p-6">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome to Dashboard</h2>
                    <p class="text-green-600 text-lg">Login Successful! 🎉</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Security Features</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>✓ 3 attempts lockout</li>
                            <li>✓ 10-minute lock duration</li>
                            <li>✓ Login attempt logging</li>
                            <li>✓ IP tracking</li>
                        </ul>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">Account Status</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>✓ Account is active</li>
                            <li>✓ Security measures active</li>
                            <li>✓ All attempts reset</li>
                        </ul>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h3 class="text-lg font-semibold text-purple-800 mb-2">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="/login-attempts" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">
                                View Login Attempts
                            </a>
                            <a href="/admin/blocked-accounts" class="block text-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded transition">
                                Manage Blocked Accounts
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center text-gray-500 text-sm">
                    <p>Your account is protected with advanced security measures.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>