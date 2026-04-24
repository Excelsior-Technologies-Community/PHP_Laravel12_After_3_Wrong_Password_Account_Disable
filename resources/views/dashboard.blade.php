<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9966, #ff5e62);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 40px;
            width: 420px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 8px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .logout {
            background: #ff5e62;
        }

        .logout:hover {
            background: #e04b4f;
        }

        .logs {
            background: #007bff;
        }

        .logs:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="card">

    <h2>Dashboard</h2>
    <p>Login Successful 🎉</p>

    <!--  NEW BUTTON -->
    <a href="/login-attempts" class="btn logs">View Login Attempts</a>

    <br>

    <a href="/logout" class="btn logout">Logout</a>

</div>

</body>
</html>