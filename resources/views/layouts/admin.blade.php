<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Al Emara Developer</title>

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fallback to CDN if Vite assets not built -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts for Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { background-color: #2b2e38; }
        .sidebar-item-active { background-color: #3eb27e; color: white; }
        .sidebar-item:hover:not(.sidebar-item-active) { background-color: rgba(255,255,255,0.05); }
    </style>
</head>
<body class="antialiased text-gray-800 flex h-screen overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 sidebar text-gray-300 flex flex-col h-full flex-shrink-0 transition-all duration-300 z-20">
        <!-- Logo Area -->
        <div class="h-14 flex items-center px-4 font-bold text-lg text-white border-b border-gray-700 bg-[#2b2e38]">
            Al Emara Developer
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1 custom-scrollbar">
            <a href="/" class="{{ request()->is('/') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Summary
            </a>
            <a href="/daily-expenses" class="{{ request()->is('daily-expenses') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Daily Expenses
            </a>
            <a href="/stock-entry" class="{{ request()->is('stock-entry') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Stock Entry
            </a>
            <a href="/item-departures" class="{{ request()->is('item-departures') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Item Departures
            </a>
            <a href="/item-master" class="{{ request()->is('item-master') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Item Master
            </a>
            <a href="/bill-payments" class="{{ request()->is('bill-payments') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Bill Payments
            </a>
            <a href="/budget-plan" class="{{ request()->is('budget-plan') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Budget Plan
            </a>
            <a href="/partner-collection" class="{{ request()->is('partner-collection') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Partner Collection
            </a>
            <a href="/supplier-master" class="{{ request()->is('supplier-master') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Supplier Master
            </a>
            <a href="/account-masters" class="{{ request()->is('account-masters') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Account Masters
            </a>
            <a href="/partner-master" class="{{ request()->is('partner-master') ? 'sidebar-item-active' : 'sidebar-item' }} flex items-center px-3 py-2.5 rounded-md text-sm font-medium transition-colors">
                Partner Master
            </a>
        </nav>
        
        <!-- User Profile -->
        <div class="p-4 bg-[#23252d] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#3eb27e] text-white flex items-center justify-center font-bold text-xs">
                    F
                </div>
                <span class="text-sm font-medium truncate w-24">Fakhrul Islam</span>
            </div>
            <div class="flex gap-2">
                <button class="text-gray-400 hover:text-white transition-colors"><i data-lucide="accessibility" class="w-4 h-4"></i></button>
                <button class="text-gray-400 hover:text-white transition-colors relative">
                    <i data-lucide="bell" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main ContentWrapper -->
    <div class="flex-1 flex flex-col h-full bg-[#f8f9fa]">
        
        <!-- Top Navbar -->
        <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 z-10">
            <!-- Left Branding inside navbar (since sidebar pushes content) -->
            <div class="flex items-center text-blue-500 font-medium">
                <i data-lucide="layers" class="w-5 h-5 mr-2"></i>
                <span class="text-gray-700 text-sm">Al Emara Developer</span>
            </div>
            
            <!-- Center Icons -->
            <div class="flex items-center gap-4 text-gray-400">
                <button class="hover:text-blue-500 transition-colors border-b-2 border-blue-500 pb-1 -mb-1 text-blue-500"><i data-lucide="monitor" class="w-5 h-5"></i></button>
                <button class="hover:text-gray-600 transition-colors"><i data-lucide="smartphone" class="w-5 h-5"></i></button>
                <button class="hover:text-gray-600 transition-colors"><i data-lucide="tablet" class="w-5 h-5"></i></button>
            </div>
            
            <!-- Right Links -->
            <div class="flex items-center gap-6 text-sm text-gray-500">
                <a href="#" class="text-blue-500 hover:underline">Subscription</a>
                <a href="#" class="flex items-center hover:text-gray-700 transition-colors"><i data-lucide="pencil" class="w-4 h-4 mr-1"></i> Edit this application</a>
                <a href="#" class="flex items-center hover:text-gray-700 transition-colors"><i data-lucide="help-circle" class="w-4 h-4 mr-1"></i> Help</a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto p-6">
            @yield('content')
        </main>
        
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
