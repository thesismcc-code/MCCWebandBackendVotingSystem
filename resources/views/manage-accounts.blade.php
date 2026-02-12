<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts - Fingerprint Voting System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* 1. Body is now a very Dark Blue (almost navy/black) */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        /* 2. Main Container is a Lighter, Vibrant Royal Blue */
        .bg-main-panel {
            background-color: #0C3189;
        }

        /* Utility */
        [x-cloak] { display: none !important; }

        /* Custom scrollbar for table if needed */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body x-data="{ openModal: false, showDeleteModal: false }" class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans">

    @include('components.notification')

    <!-- ============================== -->
    <!-- DELETE CONFIRMATION MODAL      -->
    <!-- ============================== -->
    <div x-show="showDeleteModal"
         x-cloak
         class="fixed inset-0 z-[150] flex items-center justify-center p-4">

        <!-- Backdrop -->
        <div x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             @click.away="showDeleteModal = false"
             class="bg-white rounded-2xl p-8 w-full max-w-[400px] shadow-2xl relative z-10 flex flex-col items-center text-center">

            <!-- Red Icon Circle -->
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-[#c81e1e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>

            <!-- Title & Text -->
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Are you sure?</h3>
            <p class="text-sm text-gray-600 font-medium mb-8">Are you sure you want to delete this account?</p>

            <!-- Buttons -->
            <div class="flex gap-4 w-full justify-center">
                <button @click="showDeleteModal = false" class="bg-[#ce1b26] text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-md hover:bg-red-700 transition-colors">
                    Cancel
                </button>
                <!-- Form submission would go here -->
                <button class="bg-[#1ccb14] text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-md hover:bg-green-600 transition-colors">
                    Submit
                </button>
            </div>
        </div>
    </div>

    <!-- ============================== -->
    <!-- ADD NEW ACCOUNT MODAL          -->
    <!-- ============================== -->
    <div x-show="openModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">

        <!-- Modal Card -->
        <div @click.away="openModal = false" class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl text-gray-800 relative">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Add New Account</h2>

            <form action="{{route('store.new-accounts')}}" class="space-y-4" method="POST">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter Full Name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Email Address</label>
                    <input type="email" name="email" placeholder="example@gmail.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Role</label>
                    <div class="relative">
                        <select name="role" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium bg-white appearance-none">
                            <option value="">Select Role</option>
                            <option value="comelec">Comelec</option>
                            <option value="sao">SAO Head</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Password</label>
                    <input type="password" name="password" placeholder="Enter Password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 rounded-xl border border-red-400 text-red-500 font-bold text-xs hover:bg-red-50 uppercase tracking-wide transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 uppercase tracking-wide shadow-lg shadow-blue-200/50 transition-all">Add Account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2">
        <div class="flex items-center gap-4">
            <!-- Back Button -->
            <a href="{{ route('view.quick-access') }}" class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Manage Accounts</h1>
                <p class="text-blue-200 text-[11px] font-medium">Manage Roles</p>
            </div>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 relative shadow-2xl flex-1 flex flex-col">

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Cards code (unchanged) -->
            <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-md">
                <div class="bg-blue-600 w-12 h-12 rounded-xl flex items-center justify-center text-white shrink-0">
                    <img
                                    src="/icons/person.png"
                                    alt="person"
                                    class="w-27 h-27 object-contain"
                                    :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                >
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 leading-none">3</div>
                    <div class="text-[11px] text-gray-500 font-medium mt-1">Total Accounts</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-md">
                <div class="bg-green-500 w-12 h-12 rounded-xl flex items-center justify-center text-white shrink-0">
                    <img
                                    src="/icons/person.png"
                                    alt="person"
                                    class="w-27 h-27 object-contain"
                                    :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                >
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 leading-none">2</div>
                    <div class="text-[11px] text-gray-500 font-medium mt-1">Comelec Officers</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-md">
                <div class="bg-yellow-400 w-12 h-12 rounded-xl flex items-center justify-center text-white shrink-0">
                 <img
                                    src="/icons/person.png"
                                    alt="person"
                                    class="w-27 h-27 object-contain"
                                    :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                >
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 leading-none">1</div>
                    <div class="text-[11px] text-gray-500 font-medium mt-1">SAO Head</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-md">
                <div class="bg-[#c81e1e] w-12 h-12 rounded-xl flex items-center justify-center text-white shrink-0">
                <img
                                    src="/icons/delete.png"
                                    alt="delete"
                                    class="w-27 h-27 object-contain"
                                    :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                >
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 leading-none">1</div>
                    <div class="text-[11px] text-gray-500 font-medium mt-1">Deleted Accounts</div>
                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl flex-1 h-full">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="pl-8 pr-6 py-5 text-sm font-bold text-gray-900">Name</th>
                            <th class="px-6 py-5 text-sm font-bold text-gray-900">Email</th>
                            <th class="px-6 py-5 text-sm font-bold text-gray-900 text-center">Role</th>
                            <th class="px-6 py-5 text-sm font-bold text-gray-900 text-center">Created</th>
                            <th class="pl-6 pr-8 py-5 text-sm font-bold text-gray-900 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        <!-- Row 1 -->
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="pl-8 pr-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-sm shrink-0">
                                        <img
                                            src="/icons/person.png"
                                            alt="person"
                                            class="w-4 h-4 object-contain"
                                            :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                        >
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">Jose Perolino</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">perolino@gmail.com</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-200 text-blue-800 text-[10px] font-bold px-3 py-1 rounded-full">Comelec</span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">12-05-2025</td>
                            <td class="pl-6 pr-8 py-4 text-right">
                                <button @click="showDeleteModal = true" class="inline-flex items-center justify-center w-8 h-8 bg-[#c81e1e] rounded-md text-white shadow-sm hover:bg-red-800 transition-colors">
                                    <img
                                    src="/icons/delete.png"
                                    alt="delete"
                                    class="w-4 h-4 object-contain"
                                    :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                >
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- FLOATING ADD BUTTON -->
        <button @click="openModal = true" class="fixed bottom-8 right-8 bg-blue-600 w-14 h-14 rounded-full flex items-center justify-center text-white shadow-[0_4px_14px_rgba(0,0,0,0.3)] hover:bg-blue-500 hover:scale-105 transition-all duration-300 z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>

    </div>

</body>
</html>
