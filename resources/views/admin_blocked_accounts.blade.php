<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blocked Accounts Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">🔒 Blocked Accounts</h2>
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Name</th>
                    <th class="p-3 border">Email</th>
                    <th class="p-3 border">Locked Until</th>
                    <th class="p-3 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blockedAccounts as $account)
                <tr>
                    <td class="p-3 border">{{ $account->id }}</td>
                    <td class="p-3 border">{{ $account->name }}</td>
                    <td class="p-3 border">{{ $account->email }}</td>
                    <td class="p-3 border text-red-500 font-bold">{{ \Carbon\Carbon::parse($account->locked_until)->format('d M Y, h:i A') }}</td>
                    <td class="p-3 border">
                        <form action="{{ route('admin.unblock', $account->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Unblock Now</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-3 border text-center text-gray-500">No blocked accounts right now.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>