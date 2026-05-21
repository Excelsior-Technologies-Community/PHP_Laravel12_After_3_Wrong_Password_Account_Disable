<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Attempts Log</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 md:p-10">

    <div class="max-w-6xl mx-auto bg-white p-6 md:p-8 rounded-lg shadow-md">
        
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">🔐 Login Attempt Logs</h2>

        <div class="text-right mb-6">
            <a href="/dashboard" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 shadow-sm">
                ⬅ Back to Dashboard
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-4 border border-gray-700 text-center font-semibold">ID</th>
                        <th class="p-4 border border-gray-700 text-center font-semibold">Email</th>
                        <th class="p-4 border border-gray-700 text-center font-semibold">Status</th>
                        <th class="p-4 border border-gray-700 text-center font-semibold">IP Address</th>
                        <th class="p-4 border border-gray-700 text-center font-semibold">Device Info</th>
                        <th class="p-4 border border-gray-700 text-center font-semibold">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="p-4 border text-center text-gray-700">{{ $log->id }}</td>
                            <td class="p-4 border text-center text-gray-700 font-medium">{{ $log->email }}</td>
                            <td class="p-4 border text-center">
                                @if($log->status == 'success')
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-sm">Success</span>
                                @else
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-sm">Failed</span>
                                @endif
                            </td>
                            <td class="p-4 border text-center text-gray-600 text-sm">{{ $log->ip_address }}</td>
                            <td class="p-4 border max-w-xs break-words text-sm text-gray-500 leading-relaxed">
                                {{ $log->user_agent }}
                            </td>
                            <td class="p-4 border text-center text-sm text-gray-600 font-medium">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>