<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fingerprint Voting System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-sidebar { background-color: #0b2361; }
        .bg-main { background-color: #081a4d; }
        .bg-dashboard-card { background-color: #102d7d; }
        .text-accent { color: #8ba4d8; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-sidebar flex h-screen overflow-hidden text-white">

    <!-- Sidebar -->
    <aside class="w-64 flex flex-col border-r border-blue-900/50">
        <div class="p-6 flex justify-center">
            <img src="{{ asset('icons/logo.png') }}" alt="Logo" class="w-16 h-16">
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-4">
            <hr class="border-blue-800 mb-6 mx-2">

            <a href="{{ route('view.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->is('dashboard*') ? 'bg-blue-800/40 text-white' : 'text-blue-200 hover:bg-blue-800/30' }}">
                <img src="{{ asset('icons/dashboard_icon.png')}}" alt="icon" class="w-5 h-5 mr-3 object-contain">
                Dashboard
            </a>
            <a href="{{ route('view.quick-access') }}" class="flex items-center px-4 py-3 text-sm font-medium text-blue-200 hover:bg-blue-800/30">
                <img src="{{ asset('icons/quick_access_icon.png')}}" alt="icon" class="w-5 h-5 mr-3 object-contain">
                Quick Access
            <hr class="border-blue-800 my-4 mx-2">
            <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-blue-200 hover:bg-blue-800/30">Manage Accounts</a>
            <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-blue-200 hover:bg-blue-800/30">System Activity</a>
            <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-blue-200 hover:bg-blue-800/30">Voting Logs</a>
            <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-blue-200 hover:bg-blue-800/30">Reports & Analytics</a>
        </nav>

        <div class="p-4">
            <form method="POST" action="#">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-500/10 rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-8 overflow-y-auto">
        <header class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold">Welcome, IT Administrator!</h1>
                <p class="text-accent text-sm">Manage the election process</p>
            </div>
            <div class="text-right">
                <div id="clock" class="text-xl font-bold uppercase tracking-wide">00:00AM</div>
                <div id="date" class="text-accent text-xs">00/00/0000</div>
            </div>
        </header>

        <!-- REUSABLE SPA CONTAINER -->
        <div class="bg-dashboard-card rounded-[2rem] p-8 flex-1">
            @yield('content')
        </div>
    </main>

    <script>
        function updateTime() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('date').textContent = now.toLocaleDateString('en-GB');
        }
        setInterval(updateTime, 1000); updateTime();
    </script>
</body>
</html>
