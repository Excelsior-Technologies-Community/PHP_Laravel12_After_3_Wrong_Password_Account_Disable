<!DOCTYPE html>
<html>
<head>
    <title>Login Attempts Log</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .container {
            width: 90%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #343a40;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .failed {
            color: red;
            font-weight: bold;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-failed {
            background: #dc3545;
        }

        .top-bar {
            text-align: right;
            margin-bottom: 10px;
        }

        .top-bar a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .top-bar a:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>🔐 Login Attempt Logs</h2>

    <div class="top-bar">
        <a href="/dashboard">⬅ Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Status</th>
                <th>IP Address</th>
                <th>Device Info</th>
                <th>Time</th>
            </tr>
        </thead>

        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->email }}</td>

                    <td>
                        @if($log->status == 'success')
                            <span class="badge badge-success">Success</span>
                        @else
                            <span class="badge badge-failed">Failed</span>
                        @endif
                    </td>

                    <td>{{ $log->ip_address }}</td>
                    <td style="max-width:200px; word-break:break-word;">
                        {{ $log->user_agent }}
                    </td>

                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

</body>
</html>