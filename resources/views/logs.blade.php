<!-- resources/views/logs.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Attempts Log</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">📊 Login Attempts Log</h2>
                    <div class="space-x-2">
                        <a href="/dashboard" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                            ← Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th class="py-3 px-4 border-b text-left">ID</th>
                                <th class="py-3 px-4 border-b text-left">Email</th>
                                <th class="py-3 px-4 border-b text-left">Status</th>
                                <th class="py-3 px-4 border-b text-left">IP Address</th>
                                <th class="py-3 px-4 border-b text-left">User Agent</th>
                                <th class="py-3 px-4 border-b text-left">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 border-b">{{ $log->id }}</td>
                                <td class="py-3 px-4 border-b font-medium">{{ $log->email }}</td>
                                <td class="py-3 px-4 border-b">
                                    @if($log->status == 'success')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">✓ Success</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">✗ Failed</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b font-mono text-sm">{{ $log->ip_address }}</td>
                                <td class="py-3 px-4 border-b text-sm max-w-xs truncate">{{ $log->user_agent }}</td>
                                <td class="py-3 px-4 border-b text-sm">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">No login attempts recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>