<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Position Setup - Fingerprint Voting System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #102864; }
        .bg-main-panel { background-color: #0C3189; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans relative">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2">
        <div class="flex items-center gap-4">
            <!-- Back Button -->
            <a href="{{ route('view.election-control') }}" class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Position Setup</h1>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 relative shadow-2xl flex-1 flex flex-col gap-8 border border-blue-800/30">

        <!-- TOP STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto w-full">

            <!-- Total Positions Card -->
            <div class="bg-white rounded-2xl p-4 flex items-center gap-5 shadow-lg relative overflow-hidden">
                <div class="bg-[#0066FF] w-14 h-14 rounded-xl flex items-center justify-center text-white shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 leading-none">3</h2>
                    <p class="text-xs text-gray-500 font-medium mt-1">Total Positions</p>
                </div>
            </div>

            <!-- Total Candidates Card -->
            <div class="bg-white rounded-2xl p-4 flex items-center justify-between shadow-lg relative overflow-hidden">
                <div class="flex items-center gap-5">
                    <div class="bg-[#0066FF] w-14 h-14 rounded-xl flex items-center justify-center text-white shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 leading-none">19</h2>
                        <p class="text-xs text-gray-500 font-medium mt-1">Total Candidates</p>
                    </div>
                </div>
                <a href="{{route('view.election-control-candidate-list')}}" class="text-[#102864] hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            </div>

        </div>

        <!-- POSITIONS TABLE CARD -->
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl mx-auto flex-1 relative min-h-[400px]">

            <!-- Table Header -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-6 px-8 text-left text-gray-900 font-bold text-base w-1/3">Position Name</th>
                            <th class="py-6 px-4 text-center text-gray-900 font-bold text-base w-1/3">Max Vote</th>
                            <th class="py-6 px-8 text-left text-gray-900 font-bold text-base w-1/3 pl-16">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        <!-- Row 1: President -->
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-5 px-8 text-gray-600 font-medium text-base">President</td>
                            <td class="py-5 px-4 text-center text-gray-600 font-medium text-base">1</td>
                            <td class="py-5 px-8 pl-16">
                                <div class="flex items-center gap-2">
                                    <button class="w-9 h-9 rounded bg-[#0066FF] hover:bg-blue-600 text-white flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="w-9 h-9 rounded bg-[#ffb3b3] hover:bg-red-200 text-[#FF0000] flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2: Vice President -->
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-5 px-8 text-gray-600 font-medium text-base">Vice President</td>
                            <td class="py-5 px-4 text-center text-gray-600 font-medium text-base">1</td>
                            <td class="py-5 px-8 pl-16">
                                <div class="flex items-center gap-2">
                                    <button class="w-9 h-9 rounded bg-[#0066FF] hover:bg-blue-600 text-white flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="w-9 h-9 rounded bg-[#ffb3b3] hover:bg-red-200 text-[#FF0000] flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3: Senators -->
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-5 px-8 text-gray-600 font-medium text-base">Senators</td>
                            <td class="py-5 px-4 text-center text-gray-600 font-medium text-base">9</td>
                            <td class="py-5 px-8 pl-16">
                                <div class="flex items-center gap-2">
                                    <button class="w-9 h-9 rounded bg-[#0066FF] hover:bg-blue-600 text-white flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="w-9 h-9 rounded bg-[#ffb3b3] hover:bg-red-200 text-[#FF0000] flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Floating Action Button (FAB) -->
            <button class="absolute bottom-6 right-6 bg-[#0066FF] hover:bg-blue-600 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-transform hover:scale-110">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </button>
        </div>

    </div>
</body>
</html>
