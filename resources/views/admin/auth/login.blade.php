<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <form method="POST" action="{{ route('admin.login.submit') }}"
        class="w-full max-w-md bg-white p-8 rounded-2xl shadow">
        @csrf

        <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email"
                class="w-full rounded-xl border-gray-300 focus:ring focus:ring-black/10" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password"
                class="w-full rounded-xl border-gray-300 focus:ring focus:ring-black/10" required>
        </div>

        @error('email')
            <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full py-2.5 rounded-xl bg-black text-white font-semibold">
            Sign in
        </button>
    </form>

</body>

</html>
