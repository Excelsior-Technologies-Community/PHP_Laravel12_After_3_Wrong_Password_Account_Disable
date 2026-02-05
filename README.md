# PHP_Laravel12_After_3_Wrong_Password_Account_Disable


## Project Description

PHP_Laravel12_After_3_Wrong_Password_Account_Disable is a security-focused authentication project developed using Laravel 12.
The main purpose of this project is to demonstrate how to protect user accounts by temporarily locking them after three consecutive wrong password attempts.

This project implements a custom authentication system instead of using Laravel’s default authentication. It includes registration, login, dashboard access, logout, and time-based account locking functionality to prevent brute-force login attacks.



## Project Security Logic (Core Concept)

- This project follows a time-based account lock mechanism:

- After 3 wrong password attempts, the user account is temporarily locked

- If the user tries to log in again (4th attempt), login is blocked

- The account remains locked for 10 minutes

- After entering the correct password, all failed attempts are reset

- Successful login redirects the user to the dashboard

- This approach helps prevent unauthorized access and brute-force attacks.


## Technology Stack Used

- Backend: PHP 8+, Laravel 12

- Database: MySQL

- Frontend: Blade Templates + CSS

- Authentication: Custom (Session-based)

- ORM: Eloquent ORM

- Security: Password hashing & account lock logic



---



# Project Setup 

---

## STEP 1: Create New Laravel 12 Project

### Run Command :

```
composer create-project laravel/laravel PHP_Laravel12_After_3_Wrong_Password_Account_Disable "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_After_3_Wrong_Password_Account_Disable

```

Make sure Laravel 12 is installed successfully.



## STEP 2: Database Configuration

### Open .env file and update database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_account_disable
DB_USERNAME=root
DB_PASSWORD=


```

### Create database:

```
laravel12_account_disable

```


## STEP 3: Create accounts Table (IMPORTANT)

### Run Command:

```
php artisan make:migration create_accounts_table

```

### Migration File database/migrations/xxxx_create_accounts_table.php

```

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    // Create table
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();                       // Primary key
            $table->string('name');             // User name
            $table->string('email')->unique();  // Email (unique)
            $table->string('password');         // Password

            // Security fields
            $table->integer('failed_attempts')->default(0); // Wrong password count
            $table->timestamp('locked_until')->nullable();  // Lock time

            $table->timestamps();               // created_at & updated_at
        });
    }

    // Drop table
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};


```

### Run migration:

```
php artisan migrate

```

Explanation:

This step creates a custom accounts table instead of using default users table.
It stores login credentials, failed login attempts, and lock time for security.

Why important:

failed_attempts tracks wrong passwords and locked_until temporarily disables the account.



## STEP 4: Create Account Model

### Run:

```
php artisan make:model Account

```

### app/Models/Account.php

```

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'accounts';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'failed_attempts',
        'locked_until',
    ];
}

```

Explanation:

The Account model connects Laravel logic with the accounts database table.
It allows us to easily insert, update, and fetch account records using Eloquent ORM.



## STEP 5: Create Authentication Controller

### Run:

```
php artisan make:controller AccountAuthController

```

### app/Http/Controllers/AccountAuthController.php

```

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AccountAuthController extends Controller
{
    // Show Register Page
    public function register()
    {
        return view('register');
    }

    // Register Logic
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6',
        ]);

        Account::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt password
        ]);

        return redirect('/login')->with('success', 'Account Created Successfully');
    }

    // Show Login Page
    public function login()
    {
        return view('login');
    }

    //  LOGIN LOGIC WITH TIME-BASED LOCK
    public function loginPost(Request $request)
    {
        $account = Account::where('email', $request->email)->first();

        // Email not found
        if (!$account) {
            return back()->with('error', 'Invalid Email');
        }

        // Check if account is locked
        if ($account->locked_until && Carbon::now()->lessThan($account->locked_until)) {
            return back()->with(
                'error',
                'Your account has been locked after 3 failed login attempts. Please try again after 10 minutes.'
            );
        }

        // Wrong password
        if (!Hash::check($request->password, $account->password)) {

            $account->failed_attempts++;

            // Lock account after 3 wrong attempts
            if ($account->failed_attempts >= 3) {
                $account->locked_until = Carbon::now()->addMinutes(10);
                $account->failed_attempts = 0; // Reset after lock
            }

            $account->save();

            return back()->with('error', 'Wrong Password');
        }

        // Correct login → reset everything
        $account->failed_attempts = 0;
        $account->locked_until = null;
        $account->save();

        Session::put('account_id', $account->id);

        return redirect('/dashboard');
    }

    // Dashboard
    public function dashboard()
    {
        if (!Session::has('account_id')) {
            return redirect('/login');
        }

        return view('dashboard');
    }

    // Logout
    public function logout()
    {
        Session::forget('account_id');
        return redirect('/login');
    }
}

```
Explanation:

This controller handles registration, login, dashboard access, and logout.
It contains the core logic for locking the account after 3 wrong password attempts.

Security Logic:

After 3 wrong attempts → account locked for 10 minutes using locked_until.



## STEP 6: Routes

### routes/web.php:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountAuthController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [AccountAuthController::class, 'register']);
Route::post('/register', [AccountAuthController::class, 'registerPost']);

Route::get('/login', [AccountAuthController::class, 'login']);
Route::post('/login', [AccountAuthController::class, 'loginPost']);

Route::get('/dashboard', [AccountAuthController::class, 'dashboard']);
Route::get('/logout', [AccountAuthController::class, 'logout']);

```
Explanation:

Routes connect URLs to controller methods.
They define which page opens for register, login, dashboard, and logout actions.



## STEP 7: Blade Views:

### resources/views/register.blade.php

```

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

```

Explanation:

This page allows users to create a new account with name, email, and password.
CSS is used for a clean and modern UI without any framework.


### resources/views/login.blade.php

```

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

```

Explanation:

This page allows users to log in using email and password.
It also shows error messages for wrong password or locked account.


### resources/views/dashboard.blade.php

```

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

```

Explanation:

This page is shown only after successful login.
It confirms login success and provides a logout option.


## STEP 8: Run Server

### Run:

```
php artisan serve

```

### Open in browser:

```
 http://127.0.0.1:8000/register

```

Explanation:

This command starts the Laravel development server.
You can now access the application in the browser and test all features.



## So you can see this type Output:

## Register Page:


<img width="1899" height="962" alt="Screenshot 2026-02-05 115756" src="https://github.com/user-attachments/assets/dd33c1fc-061e-447f-85ad-262b1bc2a73f" />


### Login Page:


<img width="1887" height="967" alt="image" src="https://github.com/user-attachments/assets/eef956bd-e008-49c3-950d-2de7c4a871ba" />


### If the user enters the wrong password 3 times:


<img width="1893" height="953" alt="Screenshot 2026-02-05 115820" src="https://github.com/user-attachments/assets/2e2cbedb-f003-452f-bdbe-433354083a30" />


### If the user tries to log in again (4th attempt) after the account is locked:


<img width="1892" height="964" alt="Screenshot 2026-02-05 123914" src="https://github.com/user-attachments/assets/ead4f0a1-dafc-4087-8526-780034a0190e" />


### If the user enters the correct password(Dashboard Page):


<img width="1897" height="956" alt="Screenshot 2026-02-05 120026" src="https://github.com/user-attachments/assets/a434551f-2005-462a-93f6-13c813ce55d3" />




---


# Project Folder Structure:

```
PHP_Laravel12_After_3_Wrong_Password_Account_Disable
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── AccountAuthController.php
│   │
│   └── Models
│       └── Account.php
│
├── bootstrap
│   └── app.php
│
├── config
│   └── app.php
│
├── database
│   ├── migrations
│   │   └── xxxx_create_accounts_table.php
│   │
│   └── factories
│
├── public
│   └── index.php
│
├── resources
│   └── views
│       ├── register.blade.php
│       ├── login.blade.php
│       └── dashboard.blade.php
│
├── routes
│   └── web.php
│
├── storage
│
├── tests
│
├── .env
├── .env.example
├── artisan
├── composer.json
├── composer.lock
└── README.md
```
