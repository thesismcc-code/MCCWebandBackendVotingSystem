{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fingerprint Enrollment</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #1e1e1e; }

        /* Hide scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom Input Styles to match the dark blue theme */
        .custom-input {
            background-color: #17387e; /* Darker blue background */
            border: 1px solid #4a6fa5; /* Light blue border */
            color: white;
            transition: all 0.2s;
        }
        .custom-input:focus {
            outline: none;
            border-color: white;
            background-color: #1c4291;
        }
        .custom-input::placeholder {
            color: #9ca3af;
        }

        /* Enrolled Badge */
        .badge-enrolled {
            background-color: #dcfce7; /* green-100 */
            color: #166534; /* green-800 */
            font-size: 0.70rem;
            padding: 4px 12px;
            border-radius: 9999px;
            font-weight: 700;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 md:p-8">

    <!-- Main Card Container -->
    <div class="w-full max-w-[1300px] bg-[#0f3485] rounded-[2rem] p-6 md:p-8 relative shadow-2xl overflow-hidden min-h-[800px]">

        <!-- Header -->
        <div class="flex items-start gap-4 mb-8">
            <button class="bg-white rounded-full p-2 hover:bg-gray-100 transition shadow-sm group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#0f3485" class="w-6 h-6 group-hover:scale-110 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>
            <div class="text-white">
                <h1 class="text-2xl font-bold tracking-tight">Fingerprint Enrollment</h1>
                <p class="text-blue-200 text-xs font-light mt-1 opacity-90">Register new students and capture biometric data</p>
            </div>
        </div>





        <!-- Floating Action Button (FAB) -->
        <button class="absolute bottom-10 right-10 bg-[#0066ff] hover:bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-2xl transition transform hover:scale-105 z-50">
            <!-- User Plus Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
            </svg>
        </button>

    </div>
</body>
</html> --}}
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
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <!-- Students Card -->
            <div class="bg-white rounded-2xl p-5 flex items-center gap-5 shadow-lg">
                <div class="bg-[#0066ff] w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 leading-none">3</h2>
                    <p class="text-gray-600 text-xs font-semibold mt-1">Enrolled Students</p>
                </div>
            </div>

            <!-- Fingerprint Card -->
            <div class="bg-white rounded-2xl p-5 flex items-center gap-5 shadow-lg">
                <div class="bg-[#22c504] w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565m4.382 3.161A10.5 10.5 0 0112 22.5a10.5 10.5 0 01-9.568-7.557" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 leading-none">3</h2>
                    <p class="text-gray-600 text-xs font-semibold mt-1">Enrolled Fingerprint</p>
                </div>
            </div>

            <!-- Today Card -->
            <div class="bg-white rounded-2xl p-5 flex items-center gap-5 shadow-lg">
                <div class="bg-[#facc15] w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 leading-none">0</h2>
                    <p class="text-gray-600 text-xs font-semibold mt-1">Enrolled Today</p>
                </div>
            </div>
        </div>

        <!-- Search & Filter Row -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <!-- Search Bar (40% width) -->
            <div class="relative w-full md:w-[40%]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                    class="block w-full pl-12 pr-4 py-3.5 bg-[#1c449c] border border-[#4a72c8] rounded-xl text-white placeholder-blue-200/70 focus:outline-none focus:border-white transition shadow-sm"
                    placeholder="Search by Student ID or Name">
            </div>

            <!-- Course Filter (30% width) -->
            <div class="relative w-full md:w-[30%]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <select class="block w-full pl-12 pr-10 py-3.5 bg-[#1c449c] border border-[#4a72c8] rounded-xl text-white appearance-none focus:outline-none focus:border-white transition shadow-sm cursor-pointer">
                    <option>All Courses</option>
                    <option>Computer Science</option>
                    <option>Information Technology</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Year Level Filter (30% width) -->
            <div class="relative w-full md:w-[30%]">
                 <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                     <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <select class="block w-full pl-12 pr-10 py-3.5 bg-[#1c449c] border border-[#4a72c8] rounded-xl text-white appearance-none focus:outline-none focus:border-white transition shadow-sm cursor-pointer">
                    <option>Year Level</option>
                    <option>1st Year</option>
                    <option>2nd Year</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[400px]">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-sm font-bold text-gray-900 border-b border-gray-200">
                            <th class="px-6 py-5">Student ID</th>
                            <th class="px-6 py-5">Name</th>
                            <th class="px-6 py-5">Course</th>
                            <th class="px-6 py-5">Year Level</th>
                            <th class="px-6 py-5">Created</th>
                            <th class="px-6 py-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <!-- Row 1 -->
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-6 py-6 font-medium">CS-2025-001</td>
                            <td class="px-6 py-6 text-gray-900">Jose Perolino</td>
                            <td class="px-6 py-6">Computer Science</td>
                            <td class="px-6 py-6">4th Year</td>
                            <td class="px-6 py-6">12-05-2025</td>
                            <td class="px-6 py-6 text-center">
                                <span class="badge-enrolled">Enrolled</span>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-6 py-6 font-medium">IT-2025-035</td>
                            <td class="px-6 py-6">
                                <a href="#" class="text-blue-600 font-bold underline underline-offset-2">Myles Macrohon</a>
                            </td>
                            <td class="px-6 py-6">Information Technology</td>
                            <td class="px-6 py-6">2nd Year</td>
                            <td class="px-6 py-6">12-05-2025</td>
                            <td class="px-6 py-6 text-center">
                                <span class="badge-enrolled">Enrolled</span>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-6 font-medium">BA-2025-141</td>
                            <td class="px-6 py-6 text-gray-900">Honey Malang</td>
                            <td class="px-6 py-6">Business Administration</td>
                            <td class="px-6 py-6">3rd Year</td>
                            <td class="px-6 py-6">12-05-2025</td>
                            <td class="px-6 py-6 text-center">
                                <span class="badge-enrolled">Enrolled</span>
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
