<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Al Emara Developer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white min-h-screen flex">

    <!-- Left Side: Image/Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#3eb27e] relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#3eb27e] to-[#2a7a56] opacity-90"></div>
        <!-- Decorative subtle pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>

        <div class="relative z-10 text-center px-10 text-white flex flex-col items-center">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white border-opacity-30 shadow-lg">
                <i data-lucide="building-2" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="text-4xl font-bold tracking-tight mb-4">Al Emara Developer</h1>
            <p class="text-lg text-green-50 max-w-md font-light leading-relaxed">
                Comprehensive Dashboard & Management System for streamlined business operations.
            </p>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-gray-50 lg:bg-white relative">
        <div class="w-full max-w-md">

            <!-- Mobile Header Logo -->
            <div class="lg:hidden text-center mb-8 flex flex-col items-center">
                <div class="w-16 h-16 bg-[#3eb27e] bg-opacity-10 rounded-2xl flex items-center justify-center mb-4 border border-[#3eb27e] border-opacity-20">
                    <i data-lucide="building-2" class="w-8 h-8 text-[#3eb27e]"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Al Emara Developer</h1>
            </div>

            <!-- Form Header -->
            <div class="mb-10 lg:text-left text-center">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Create account</h2>
                <p class="text-sm text-gray-500 mt-2">Enter your details to get started.</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" class="block w-full rounded-lg border border-gray-200 pl-11 pr-4 py-3 text-sm focus:border-[#3eb27e] focus:ring-1 focus:ring-[#3eb27e] transition-colors bg-gray-50 focus:bg-white placeholder-gray-400" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" class="block w-full rounded-lg border border-gray-200 pl-11 pr-4 py-3 text-sm focus:border-[#3eb27e] focus:ring-1 focus:ring-[#3eb27e] transition-colors bg-gray-50 focus:bg-white placeholder-gray-400" placeholder="john@example.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" class="block w-full rounded-lg border border-gray-200 pl-11 pr-4 py-3 text-sm focus:border-[#3eb27e] focus:ring-1 focus:ring-[#3eb27e] transition-colors bg-gray-50 focus:bg-white placeholder-gray-400" placeholder="••••••••" required>
                    </div>
                    <p class="text-xs text-gray-500">Must be at least 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="shield-check" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-lg border border-gray-200 pl-11 pr-4 py-3 text-sm focus:border-[#3eb27e] focus:ring-1 focus:ring-[#3eb27e] transition-colors bg-gray-50 focus:bg-white placeholder-gray-400" placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-[#3eb27e] hover:bg-[#349c6d] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3eb27e] transition-all active:scale-[0.98] cursor-pointer">
                        Sign Up
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-500">Already have an account? <a href="{{ route('login') }}" class="text-[#3eb27e] hover:text-[#2a7a56] font-medium">Sign in</a></p>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
