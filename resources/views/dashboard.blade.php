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
            width: 400px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 25px;
            font-size: 18px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background: #ff5e62;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        a:hover {
            background: #e04b4f;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Dashboard</h2>
    <p>Login Successful 🎉</p>

    <a href="/logout">Logout</a>
</div>

</body>
</html>
