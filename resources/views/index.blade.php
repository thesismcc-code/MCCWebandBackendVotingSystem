<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fingerprint Voting System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Navy Background Color */
        .bg-navy-main { background-color: #102251; }
        .text-navy-main { color: #102251; }
    </style>
</head>
<body class="bg-navy-main min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-[2rem] shadow-2xl p-8 w-full max-w-sm text-center">

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <!-- Replace 'logo.png' with your actual image path -->
            <img src="{{ asset('icons/logo_white_bg.png') }}" alt="Mandaue City College Logo" class="w-24 h-24 object-contain">
        </div>

        <!-- Title -->
        <h1 class="text-navy-main font-bold text-lg mb-8">
            Fingerprint Voting System
        </h1>

        <!-- Login Form -->
        <form method="POST" action="{{route('login')}}" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div class="relative">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder-gray-400"
                >
            </div>

            <!-- Password Input -->
            <div class="relative" x-data="{ show: false }">
                <input
                    :type="show ? 'text' : 'password'"
                    name="password"
                    placeholder="Password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder-gray-400"
                >
                <!-- Toggle Visibility Icon -->
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="w-full bg-navy-main text-white py-3 rounded-lg font-semibold hover:bg-opacity-90 transition duration-200 text-sm mt-2"
            >
                Log In
            </button>
        </form>

        <!-- Forgot Password Link -->
        <div class="mt-4">
            <a href="#" class="text-xs text-blue-600 hover:underline">
                Forgot password?
            </a>
        </div>

        <!-- Footer Text -->
        <div class="mt-12 text-[10px] text-gray-400 tracking-wider">
            © Mandaue City College
        </div>

    </div>

    <!-- Alpine.js (Optional, for the password toggle functionality) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
