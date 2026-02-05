<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #43cea2, #185a9d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            background: #43cea2;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #35b28d;
        }

        .success {
            color: green;
            text-align: center;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        a {
            text-decoration: none;
            color: #185a9d;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Register</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Register</button>
    </form>

    <div class="link">
        <a href="/login">Already have account?</a>
    </div>
</div>

</body>
</html>
