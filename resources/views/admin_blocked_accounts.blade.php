<!-- resources/views/admin_blocked_accounts.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked Accounts Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">🔒 Blocked Accounts Management</h2>
                    <a href="/dashboard" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                        ← Back to Dashboard
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th class="py-3 px-4 border-b text-left">ID</th>
                                <th class="py-3 px-4 border-b text-left">Name</th>
                                <th class="py-3 px-4 border-b text-left">Email</th>
                                <th class="py-3 px-4 border-b text-left">Locked Until</th>
                                <th class="py-3 px-4 border-b text-left">Remaining Time</th>
                                <th class="py-3 px-4 border-b text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blockedAccounts as $account)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 border-b">{{ $account->id }}</td>
                                <td class="py-3 px-4 border-b font-medium">{{ $account->name }}</td>
                                <td class="py-3 px-4 border-b">{{ $account->email }}</td>
                                <td class="py-3 px-4 border-b text-red-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($account->locked_until)->format('d M Y, h:i A') }}
                                </td>
                                <td class="py-3 px-4 border-b text-orange-600 font-semibold">
                                    {{ now()->diffInMinutes($account->locked_until) }} minute(s)
                                </td>
                                <td class="py-3 px-4 border-b">
                                    <form action="{{ route('admin.unblock', $account->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to unblock this account?')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition">
                                            Unblock Now
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    No blocked accounts at the moment. ✓
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-2">ℹ️ Information</h3>
                    <p class="text-sm text-blue-700">
                        Accounts are automatically locked for 10 minutes after 3 failed login attempts. 
                        You can manually unblock accounts from here before the lock period expires.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>