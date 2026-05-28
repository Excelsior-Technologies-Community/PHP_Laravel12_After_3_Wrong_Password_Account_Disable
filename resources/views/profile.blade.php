<!-- resources/views/profile.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">👤 My Profile</h2>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="/profile/update">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ $account->name }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ $account->email }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">New Password (Optional)</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current password" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimum 6 characters if you want to change password</p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            Update Profile
                        </button>
                        <a href="/dashboard" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition text-center">
                            Cancel
                        </a>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-2">Account Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg text-sm">
                        <p><strong>Account Created:</strong> {{ $account->created_at->format('d M Y, h:i A') }}</p>
                        <p><strong>Last Updated:</strong> {{ $account->updated_at->format('d M Y, h:i A') }}</p>
                        <p><strong>Account ID:</strong> {{ $account->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>