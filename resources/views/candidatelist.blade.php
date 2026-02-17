<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates List - Fingerprint Voting System</title>

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

        /* Table Styles */
        th { text-transform: uppercase; font-size: 0.875rem; letter-spacing: 0.05em; }
    </style>
</head>

<body class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans relative">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2">
        <div class="flex items-center gap-4">
            <!-- Back Button -->
            <a href="{{ route('view.election-control-posistion-setup') }}" class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Candidates List</h1>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 relative shadow-2xl flex-1 border border-blue-800/30">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-full">

            <!-- LEFT PANEL: Candidates by Position -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white border-b-2 border-gray-200">
                            <tr>
                                <th class="py-4 px-6 text-gray-900 font-bold w-1/4">Position</th>
                                <th class="py-4 px-6 text-gray-900 font-bold w-1/2">Name</th>
                                <th class="py-4 px-6 text-gray-900 font-bold text-center w-1/4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- President Row -->
                            <tr class="hover:bg-gray-50 align-top">
                                <td class="py-4 px-6 text-gray-800 text-sm font-medium pt-5">President</td>
                                <td class="py-4 px-6 text-gray-700 text-sm pt-5">
                                    <div class="mb-1">Honey Malang</div>
                                    <div>Myles Macrohon</div>
                                </td>
                                <td class="py-4 px-6 text-center pt-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="w-8 h-8 rounded bg-[#0066FF] hover:bg-blue-600 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="w-8 h-8 rounded bg-[#ffb3b3] hover:bg-red-200 text-[#FF0000] flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Vice President Row -->
                            <tr class="hover:bg-gray-50 align-top">
                                <td class="py-4 px-6 text-gray-800 text-sm font-medium pt-5">Vice President</td>
                                <td class="py-4 px-6 text-gray-700 text-sm pt-5">
                                    <div class="mb-1">Jose Perolino</div>
                                    <div>Jahaira Ampaso</div>
                                </td>
                                <td class="py-4 px-6 text-center pt-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="w-8 h-8 rounded bg-[#0066FF] hover:bg-blue-600 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="w-8 h-8 rounded bg-[#ffb3b3] hover:bg-red-200 text-[#FF0000] flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Senators Row -->
                            <tr class="hover:bg-gray-50 align-top">
                                <td class="py-4 px-6 text-gray-800 text-sm font-medium pt-5">Senators</td>
                                <td class="py-4 px-6 text-gray-700 text-sm pt-5 space-y-1">
                                    <div>James Cortes</div>
                                    <div>Carley Serato</div>
                                    <div>Breant Cortes</div>
                                    <div>Carley Cobarde</div>
                                    <div>James Cortes</div>
                                    <div>Carley Serato</div>
                                    <div>Jose Perolino</div>
                                    <div>Jahaira Ampaso</div>
                                    <div>James Cortes</div>
                                    <div>Carley Serato</div>
                                </td>
                                <td class="py-4 px-6 text-center pt-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="w-8 h-8 rounded bg-[#0066FF] hover:bg-blue-600 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button class="w-8 h-8 rounded bg-[#ffb3b3] hover:bg-red-200 text-[#FF0000] flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT PANEL: Candidates List (Partylist/Other View) -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col relative">
                <div class="overflow-x-auto h-full">
                    <table class="w-full text-left">
                        <thead class="bg-white border-b-2 border-gray-200">
                            <tr>
                                <th class="py-4 px-6 text-gray-900 font-bold w-1/4">Position</th>
                                <th class="py-4 px-6 text-gray-900 font-bold w-1/2">Name</th>
                                <th class="py-4 px-6 text-gray-900 font-bold text-center w-1/4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- In the screenshot, the right panel lists names under 'Name',
                                 but 'Position' and 'Actions' columns appear empty in the specific view.
                                 Replicating exact screenshot layout. -->

                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-6 text-gray-800 text-sm"></td>
                                <td class="py-3 px-6 text-gray-700 text-sm">Honey Malang</td>
                                <td class="py-3 px-6"></td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-6 text-gray-800 text-sm"></td>
                                <td class="py-3 px-6 text-gray-700 text-sm">Myles Macrohon</td>
                                <td class="py-3 px-6"></td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-6 text-gray-800 text-sm"></td>
                                <td class="py-3 px-6 text-gray-700 text-sm">Honey Malang</td>
                                <td class="py-3 px-6"></td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-6 text-gray-800 text-sm"></td>
                                <td class="py-3 px-6 text-gray-700 text-sm">Myles Macrohon</td>
                                <td class="py-3 px-6"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Floating Action Button (FAB) -->
                <button class="absolute bottom-6 right-6 bg-[#0066FF] hover:bg-blue-600 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-transform hover:scale-110 z-10">
                    <!-- User Group Add Icon -->
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </button>
            </div>

        </div>

    </div>
</body>
</html>
