<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #667eea, #764ba2);
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
            background: #667eea;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #5563c1;
        }

        .error {
            color: red;
            text-align: center;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        a {
            text-decoration: none;
            color: #667eea;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Login</h2>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login">
        @csrf

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <div class="link">
        <a href="/register">Create Account</a>
    </div>
</div>

</body>
</html>
