<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-10 w-full max-w-md text-center rounded-xl shadow-lg">
        
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h2>
        <p class="text-lg text-green-600 font-semibold mb-8">Login Successful 🎉</p>

        <div class="flex flex-col space-y-4">
            <a href="/login-attempts" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-sm">
                View Login Attempts
            </a>

            <a href="/admin/blocked-accounts" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-sm">
                Manage Blocked Accounts
            </a>

            <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-sm">
                Logout
            </a>
        </div>

    </div>

</body>
</html>