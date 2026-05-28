<!-- resources/views/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Secure System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">🔐 Secure Login</h2>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        <form id="loginForm" action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required autofocus>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="/register" class="text-blue-600 hover:underline text-sm">Don't have an account? Register</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let submitBtn = this.querySelector('button[type="submit"]');
            let originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '⏳ Logging in...';
            submitBtn.disabled = true;

            let formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        willClose: () => {
                            window.location.href = data.redirect;
                        }
                    });
                } else if (data.status === 'warning') {
                    Swal.fire({
                        icon: 'warning',
                        title: '⚠️ Attention!',
                        text: data.message,
                        confirmButtonColor: '#f59e0b'
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Access Denied',
                        text: data.message,
                        confirmButtonColor: '#ef4444'
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Something went wrong. Please try again.',
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>