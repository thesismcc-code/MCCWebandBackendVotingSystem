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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0b2361; }
        .bg-card-container { background-color: #102d7d; }
        .text-accent { color: #8ba4d8; }

        /* Prevents Alpine.js elements from flickering on load */
        [x-cloak] { display: none !important; }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body x-data="{ openModal: false }" class="p-4 md:p-8 min-h-screen text-white relative">

    @include('components.notification')

    <!-- ADD NEW ACCOUNT MODAL -->
    <div x-show="openModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

        <!-- Modal Card -->
        <div @click.away="openModal = false" class="bg-white rounded-[2.5rem] p-10 w-full max-w-md shadow-2xl text-gray-800">
            <h2 class="text-2xl font-bold mb-8 text-gray-900">Add New Account</h2>

            <form action="{{route('store.new-accounts')}}" class="space-y-5" method="POST">
                @csrf
                <!-- Full Name Input -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-2 ml-1">Full Name</label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}"  placeholder="Enter Full Name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder-gray-400">
                </div>

                <!-- Email Input -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"  placeholder="example@gmail.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder-gray-400">
                </div>

                <!-- Role Dropdown -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-2 ml-1">Role</label>
                    <div class="relative">
                        <select name="role" value="{{ old('role') }} "class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm appearance-none bg-white text-gray-400">
                            <option value="">Select Role</option>
                            <option value="comelec">Comelec</option>
                            <option value="sao">SAO Head</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-2 ml-1">Password</label>
                    <input type="password" name="password" placeholder="Enter Password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder-gray-400">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 px-6 rounded-xl border border-red-400 text-red-500 font-bold text-xs hover:bg-red-50 transition-colors uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 py-3 px-6 rounded-xl bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all uppercase tracking-wider">
                        Add Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- PAGE HEADER SECTION -->
    <div class="flex justify-between items-center mb-8 px-2">
        <div class="flex items-center">
            <!-- UPDATED BACK BUTTON: Uses route instead of history.back() -->
            <a href="{{ route('view.quick-access') }}" class="bg-white p-2.5 rounded-full text-[#0b2361] mr-5 shadow-lg hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold leading-none tracking-tight">Manage Accounts</h1>
                <p class="text-accent text-xs mt-1 font-medium">Manage Roles</p>
            </div>
        </div>

        <!-- Top Trash Button -->
        <button class="bg-red-600 p-3.5 rounded-2xl text-white shadow-xl hover:bg-red-700 transition-all hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    </div>

    <!-- CONTENT CONTAINER -->
    <div class="bg-card-container rounded-[3rem] p-8 md:p-12 shadow-2xl min-h-[75vh] relative">

        <!-- Stats Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Accounts Stat -->
            <div class="bg-white rounded-[2.5rem] p-7 flex items-center shadow-xl">
                <div class="bg-blue-600 p-5 rounded-2xl mr-6 text-white shadow-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                </div>
                <div>
                    <div class="text-5xl font-black text-gray-900 leading-none">3</div>
                    <div class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-2">Total Accounts</div>
                </div>
            </div>

            <!-- Comelec Officers Stat -->
            <div class="bg-white rounded-[2.5rem] p-7 flex items-center shadow-xl">
                <div class="bg-green-500 p-5 rounded-2xl mr-6 text-white shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <div class="text-5xl font-black text-gray-900 leading-none">2</div>
                    <div class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-2">Comelec Officers</div>
                </div>
            </div>

            <!-- SAO Head Stat -->
            <div class="bg-white rounded-[2.5rem] p-7 flex items-center shadow-xl">
                <div class="bg-yellow-400 p-5 rounded-2xl mr-6 text-white shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <div class="text-5xl font-black text-gray-900 leading-none">1</div>
                    <div class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-2">SAO Head</div>
                </div>
            </div>
        </div>

        <!-- ACCOUNTS TABLE SECTION -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[11px] font-extrabold text-gray-900 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-10 py-7">Name</th>
                            <th class="px-6 py-7">Email</th>
                            <th class="px-6 py-7 text-center">Role</th>
                            <th class="px-6 py-7 text-center">Status</th>
                            <th class="px-6 py-7">Created</th>
                            <th class="px-10 py-7 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <!-- User Row 1 -->
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-10 py-6">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 p-2.5 rounded-full text-white mr-4 shadow-md">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-800 tracking-tight">Jose Perolino</span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-xs text-gray-500 font-semibold">perolino@gmail.com</td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-tighter">Comelec</span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-green-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase">Active</span>
                            </td>
                            <td class="px-6 py-6 text-xs text-gray-500 font-semibold">12-05-2025</td>
                            <td class="px-10 py-6 text-right">
                                <button class="bg-red-600 p-2.5 rounded-xl text-white shadow-md hover:bg-red-700 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>

                        <!-- User Row 2 -->
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-10 py-6">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 p-2.5 rounded-full text-white mr-4 shadow-md">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-800 tracking-tight">James Cortes</span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-xs text-gray-500 font-semibold">cortes@gmail.com</td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-tighter">Comelec</span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-green-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase">Active</span>
                            </td>
                            <td class="px-6 py-6 text-xs text-gray-500 font-semibold">12-05-2025</td>
                            <td class="px-10 py-6 text-right">
                                <button class="bg-red-600 p-2.5 rounded-xl text-white shadow-md hover:bg-red-700 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>

                        <!-- User Row 3 -->
                        <tr class="hover:bg-gray-50 transition-colors group border-none">
                            <td class="px-10 py-6">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 p-2.5 rounded-full text-white mr-4 shadow-md">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-800 tracking-tight">Sir Baunsit</span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-xs text-gray-500 font-semibold">baunsit@gmail.com</td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-yellow-100 text-yellow-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-tighter">SAO Head</span>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="bg-green-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase">Active</span>
                            </td>
                            <td class="px-6 py-6 text-xs text-gray-500 font-semibold">12-05-2025</td>
                            <td class="px-10 py-6 text-right">
                                <button class="bg-red-600 p-2.5 rounded-xl text-white shadow-md hover:bg-red-700 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FLOATING ACTION BUTTON (Trigger) -->
        <button @click="openModal = true" class="fixed bottom-12 right-12 bg-blue-600 text-white p-6 rounded-full shadow-[0_20px_50px_rgba(37,99,235,0.4)] hover:bg-blue-700 hover:scale-110 transition-all duration-300 z-50 group">
            <svg class="w-8 h-8 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </button>

    </div>

</body>
</html>
