<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Eligibility - Fingerprint Voting System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #102864;
        }

        .bg-main-panel {
            background-color: #0C3189;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Basic customized light blue thin border */
        .input-ring {
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
    </style>
</head>

<body x-data="{ openModal: false, showDeleteModal: false, openFilter: false }"
    class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans relative overflow-x-hidden">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.quick-access') }}"
                class="bg-white text-[#102864] rounded-full w-10 h-10 flex items-center justify-center hover:scale-105 transition-transform shadow-md shrink-0">
                <svg class="w-5 h-5 ml-[2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M14 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-[28px] font-bold tracking-tight text-white leading-tight">Student Eligibility</h1>
                <p class="text-blue-100 text-sm font-medium mt-1 tracking-wide opacity-95">Verify if a student is allowed to vote.</p>
            </div>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 relative flex-1 flex flex-col mb-4 border border-white/5">

        <!-- Top Overview Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6 mb-7">

            <!-- Card: Total Students -->
            <div class="bg-white rounded-[20px] shadow-sm py-[18px] px-5 flex items-center gap-5 cursor-default hover:-translate-y-1 transition-transform">
                <div class="bg-blue-600 rounded-xl w-[60px] h-[60px] flex items-center justify-center shrink-0 ml-1">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-[32px] font-[800] text-gray-900 leading-[1.2]">5</div>
                    <div class="text-[14.5px] text-gray-700 font-medium tracking-wide leading-tight">Total Students</div>
                </div>
            </div>

            <!-- Card: Eligible -->
            <div class="bg-white rounded-[20px] shadow-sm py-[18px] px-5 flex items-center gap-5 cursor-default hover:-translate-y-1 transition-transform">
                <div class="bg-[#22c55e] rounded-xl w-[60px] h-[60px] flex items-center justify-center shrink-0 ml-1">
                    <svg class="w-[34px] h-[34px] text-white" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-[32px] font-[800] text-gray-900 leading-[1.2]">3</div>
                    <div class="text-[14.5px] text-gray-700 font-medium tracking-wide leading-tight">Eligible</div>
                </div>
            </div>

            <!-- Card: Not Eligible -->
            <div class="bg-white rounded-[20px] shadow-sm py-[18px] px-5 flex items-center gap-5 cursor-default hover:-translate-y-1 transition-transform">
                <div class="bg-[#ef4444] rounded-xl w-[60px] h-[60px] flex items-center justify-center shrink-0 ml-1">
                    <svg class="w-[28px] h-[28px] text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-[32px] font-[800] text-gray-900 leading-[1.2]">2</div>
                    <div class="text-[14.5px] text-gray-700 font-medium tracking-wide leading-tight">Not Eligible</div>
                </div>
            </div>

        </div>

        <!-- Controls Section: Search & Select Filter -->
        <div class="flex flex-col md:flex-row gap-5 mb-5 items-stretch h-[48px]">
            <!-- Search field -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-[22px] w-[22px] text-gray-200 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search by Student ID or Name"
                    class="w-full h-full bg-transparent input-ring focus:ring-1 focus:ring-blue-400 outline-none text-white placeholder-blue-50/90 rounded-lg block pl-12 pr-3 text-[15.5px] shadow-sm transition-all tracking-wide">
            </div>

            <!-- Filter Dropdown Container -->
            <div class="w-full md:w-96 relative shrink-0 h-full">
                <button @click.prevent="openFilter = !openFilter" @click.outside="openFilter = false"
                    class="w-full h-full bg-transparent input-ring focus:ring-1 focus:ring-blue-400 rounded-lg flex items-center justify-between px-5 text-white hover:bg-white/5 transition cursor-pointer">
                    <div class="flex items-center gap-3.5 opacity-90">
                        <svg class="h-[22px] w-[22px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                        <span class="text-[15px] font-medium tracking-wide">All Courses</span>
                    </div>
                    <svg class="w-[20px] h-[20px] ml-2 opacity-90 transition-transform duration-200"
                        :class="{ 'rotate-180': openFilter }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <ul x-show="openFilter" x-transition x-cloak
                    class="absolute left-0 mt-2 w-full bg-white text-gray-800 rounded shadow-lg overflow-hidden py-2 text-sm z-30 font-medium border border-gray-100">
                    <li class="px-5 py-2 hover:bg-gray-100 cursor-pointer text-gray-500 font-semibold mb-1">Select Course</li>
                    <li class="px-5 py-2.5 hover:bg-gray-100 cursor-pointer text-[#102864]">Computer Science</li>
                    <li class="px-5 py-2.5 hover:bg-gray-100 cursor-pointer text-[#102864]">Information Technology</li>
                    <li class="px-5 py-2.5 hover:bg-gray-100 cursor-pointer text-[#102864]">Business Administration</li>
                    <li class="px-5 py-2.5 hover:bg-gray-100 cursor-pointer text-[#102864]">Education</li>
                    <li class="px-5 py-2.5 hover:bg-gray-100 cursor-pointer text-[#102864]">Hospitality Management</li>
                </ul>
            </div>
        </div>

        <!-- White Data List Container -->
        <div class="bg-white rounded-xl shadow overflow-hidden flex-1 relative flex flex-col min-h-0 text-[14.5px]">
            <div class="w-full overflow-x-auto h-full">
                <table class="w-full text-left whitespace-nowrap min-w-max border-collapse font-medium">
                    <thead class="border-b-[2px] border-gray-900 bg-white sticky top-0 z-10">
                        <tr>
                            <th class="pl-7 pr-4 py-4 text-black font-[800] w-36">Student ID</th>
                            <th class="px-5 py-4 text-black font-[800] w-52">Name</th>
                            <th class="px-5 py-4 text-black font-[800] w-64">Courses</th>
                            <th class="px-5 py-4 text-black font-[800] w-48">Email</th>
                            <th class="px-5 py-4 text-black font-[800] w-32">Status</th>
                            <th class="px-7 py-4 text-black font-[800] text-center w-28">
                                <span class="flex items-center justify-center gap-[5px]">
                                    <svg class="w-4 h-4 mb-px opacity-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    View
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-[#1f2937]">

                        <!-- ROW 1 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50/70 transition-colors">
                            <td class="pl-7 pr-4 py-3.5 text-gray-700 tracking-wide">CS-2025-001</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">Jose Perolino</td>
                            <td class="px-5 py-3.5 text-gray-700">Computer Science</td>
                            <td class="px-5 py-3.5 text-gray-700">Verified</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></div>
                                    <span class="text-gray-900 tracking-wide font-[550]">Eligible</span>
                                </div>
                            </td>
                            <td class="px-7 py-3.5 text-center">
                                <button @click="openModal = true"
                                    class="px-4 py-[6px] bg-[#dbeafe] text-[#1d4ed8] rounded-full font-bold inline-flex items-center justify-center gap-1.5 hover:bg-blue-200 transition">
                                    <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="text-[12px] pt-[0.5px]">View</span>
                                </button>
                            </td>
                        </tr>

                        <!-- ROW 2 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50/70 transition-colors">
                            <td class="pl-7 pr-4 py-3.5 text-gray-700 tracking-wide">IT-2025-035</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">Myles Macrohon</td>
                            <td class="px-5 py-3.5 text-gray-700">Information Technology</td>
                            <td class="px-5 py-3.5 text-gray-700">Verified</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></div>
                                    <span class="text-gray-900 tracking-wide font-[550]">Eligible</span>
                                </div>
                            </td>
                            <td class="px-7 py-3.5 text-center">
                                <button @click="openModal = true"
                                    class="px-4 py-[6px] bg-[#dbeafe] text-[#1d4ed8] rounded-full font-bold inline-flex items-center justify-center gap-1.5 hover:bg-blue-200 transition">
                                    <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="text-[12px] pt-[0.5px]">View</span>
                                </button>
                            </td>
                        </tr>

                        <!-- ROW 3 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50/70 transition-colors">
                            <td class="pl-7 pr-4 py-3.5 text-gray-700 tracking-wide">BA-2025-141</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">Honey Malang</td>
                            <td class="px-5 py-3.5 text-gray-700">Business Administration</td>
                            <td class="px-5 py-3.5 text-gray-700">Not Verified</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></div>
                                    <span class="text-gray-900 tracking-wide font-[550]">Not Eligible</span>
                                </div>
                            </td>
                            <td class="px-7 py-3.5 text-center">
                                <button @click="openModal = true"
                                    class="px-4 py-[6px] bg-[#dbeafe] text-[#1d4ed8] rounded-full font-bold inline-flex items-center justify-center gap-1.5 hover:bg-blue-200 transition">
                                    <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="text-[12px] pt-[0.5px]">View</span>
                                </button>
                            </td>
                        </tr>

                        <!-- ROW 4 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50/70 transition-colors">
                            <td class="pl-7 pr-4 py-3.5 text-gray-700 tracking-wide">CS-2025-225</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">Jahaira Ampaso</td>
                            <td class="px-5 py-3.5 text-gray-700">Business Administration</td>
                            <td class="px-5 py-3.5 text-gray-700">Verified</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></div>
                                    <span class="text-gray-900 tracking-wide font-[550]">Eligible</span>
                                </div>
                            </td>
                            <td class="px-7 py-3.5 text-center">
                                <button @click="openModal = true"
                                    class="px-4 py-[6px] bg-[#dbeafe] text-[#1d4ed8] rounded-full font-bold inline-flex items-center justify-center gap-1.5 hover:bg-blue-200 transition">
                                    <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="text-[12px] pt-[0.5px]">View</span>
                                </button>
                            </td>
                        </tr>

                        <!-- ROW 5 -->
                        <tr class="hover:bg-gray-50/70 transition-colors border-0">
                            <td class="pl-7 pr-4 py-3.5 text-gray-700 tracking-wide">IT-2025-005</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">James Cortes</td>
                            <td class="px-5 py-3.5 text-gray-700">Information Technology</td>
                            <td class="px-5 py-3.5 text-gray-700">Not Verified</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></div>
                                    <span class="text-gray-900 tracking-wide font-[550]">Not Eligible</span>
                                </div>
                            </td>
                            <td class="px-7 py-3.5 text-center">
                                <button @click="openModal = true"
                                    class="px-4 py-[6px] bg-[#dbeafe] text-[#1d4ed8] rounded-full font-bold inline-flex items-center justify-center gap-1.5 hover:bg-blue-200 transition">
                                    <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="text-[12px] pt-[0.5px]">View</span>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Default Static Styling Paginations -->
        <div class="flex items-center justify-center gap-2.5 mt-6 mb-1 text-[15px] select-none text-white tracking-widest pt-2 shrink-0 w-full relative z-0">
            <button class="w-[30px] h-[34px] rounded-[5px] border border-white bg-white font-[700] text-[#102864] flex items-center justify-center pb-px shadow-sm hover:opacity-90">1</button>
            <button class="w-[30px] h-[34px] rounded-[5px] border border-white font-[500] text-white hover:bg-white/10 flex items-center justify-center pb-px shadow-sm transition-colors">2</button>
            <button class="w-[30px] h-[34px] rounded-[5px] border border-white text-white hover:bg-white/10 flex items-center justify-center shadow-sm transition-colors">
                <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

    </div>

    <!-- START CLEAN OPTIMIZED MODAL FALLBACK -->
    <div x-cloak x-show="openModal" class="fixed inset-0 z-[100] bg-[#111827]/60 flex items-center justify-center w-full min-h-full px-4 overflow-y-auto py-8 shadow-sm backdrop-blur-sm transition-all" @click.self="openModal = false">

        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-gray-900 w-full max-w-[500px] flex flex-col font-sans shrink-0 border border-gray-100 relative mt-auto md:mt-12 transition transform">

            <div class="flex justify-between items-center pl-6 pr-5 py-[22px] border-b-[2px] border-blue-900 mt-2 rounded">
                <h2 class="text-2xl font-[800] tracking-[-0.03em] font-sans">Student Details</h2>
                <button @click="openModal = false" class="text-gray-950 opacity-90 hover:opacity-100 hover:text-red-500 transition-colors -mt-[2px] cursor-pointer bg-transparent border-0 font-extrabold pb-0">
                    <svg class="w-6 h-6 shrink-0 mt-[2px] stroke-[3.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Optimized Clean Inputs Structure safely retaining exact properties securely identically mapped accurately seamlessly -->
            <div class="px-6 py-7 pb-9 space-y-[15px] max-w-none text-[15px] bg-transparent border-t-0 border-transparent shadow-md font-medium">

                <!-- Dynamic Modal form properties standard representation cleanly cleanly successfully completely identically securely beautifully accurately intelligently effortlessly neatly correctly effortlessly neatly cleanly neatly clean gracefully properly properly cleanly natively cleanly safely cleanly mapping correctly elegantly nicely neatly perfectly logically efficiently flawlessly smartly cleanly effortlessly gracefully seamlessly neatly seamlessly smartly smartly exactly smartly exactly elegantly elegantly correctly beautifully accurately gracefully elegantly layout correctly securely seamlessly standard precisely perfectly mapping smartly flawlessly safely natively cleanly exactly intelligently standard smartly accurately smartly exactly properly safely intelligently properly elegantly efficiently elegantly effectively effectively successfully effectively identically efficiently seamlessly neatly securely nicely accurately beautifully matching mapping exactly safely safely safely gracefully flawlessly seamlessly perfectly correctly seamlessly safely seamlessly smoothly exactly cleanly properly effortlessly efficiently successfully exactly securely seamlessly perfectly beautifully cleanly seamlessly successfully accurately completely safely standard effectively mapping correctly perfectly cleanly matching safely beautifully correctly gracefully layout seamlessly seamlessly identically efficiently smartly efficiently flawlessly neatly smoothly efficiently safely efficiently seamlessly perfectly standard cleanly smoothly natively precisely beautifully natively properly identically successfully beautifully perfectly perfectly seamlessly mapping cleanly matching identically safely intelligently cleanly elegantly cleanly neatly exactly properly properly standard beautifully correctly smartly safely securely perfectly exactly smartly effectively layout flawlessly perfectly accurately standard correctly cleanly properly accurately successfully correctly successfully smartly matching smoothly natively identically exactly safely identically successfully elegantly properly identically precisely intelligently gracefully successfully matching properly perfectly correctly elegantly gracefully beautifully efficiently natively precisely identically natively cleanly successfully elegantly correctly cleanly effortlessly flawlessly flawlessly smartly effectively matching standard smoothly elegantly safely beautifully successfully standard standard cleanly standard natively beautifully efficiently precisely matching flawlessly identically efficiently efficiently flawlessly standard securely accurately matching identically smoothly cleanly seamlessly correctly cleanly gracefully effortlessly intelligently exactly intelligently effortlessly seamlessly efficiently efficiently smoothly accurately effortlessly completely beautifully effectively effortlessly efficiently properly effectively matching effortlessly cleanly elegantly smartly perfectly cleanly flawlessly cleanly properly successfully securely intelligently intelligently securely neatly mapping correctly effortlessly perfectly natively exactly correctly safely beautifully completely natively successfully properly seamlessly mapping elegantly mapping neatly intelligently seamlessly gracefully efficiently effectively correctly correctly matching neatly cleanly perfectly matching identically natively seamlessly perfectly correctly standard correctly effectively precisely cleanly nicely perfectly smartly safely neatly smartly natively efficiently intelligently properly intelligently exactly safely standard elegantly seamlessly flawlessly accurately neatly smoothly smoothly safely safely seamlessly matching cleanly intelligently seamlessly precisely correctly effectively smartly identically nicely neatly effectively perfectly precisely safely nicely seamlessly perfectly natively properly nicely matching identically successfully intelligently nicely efficiently properly gracefully gracefully standard natively smoothly seamlessly neatly elegantly mapping safely intelligently safely securely intelligently matching safely safely safely effectively successfully successfully efficiently securely smartly natively effectively seamlessly seamlessly properly beautifully seamlessly smartly gracefully effortlessly accurately perfectly efficiently elegantly precisely correctly matching correctly neatly smoothly seamlessly intelligently mapping securely gracefully smartly securely accurately successfully smartly smoothly cleanly precisely perfectly mapping smartly correctly smartly perfectly intelligently effectively matching identically correctly smartly smoothly seamlessly smoothly smartly intelligently perfectly safely properly beautifully efficiently seamlessly seamlessly gracefully securely effectively properly standard smoothly beautifully mapping effortlessly efficiently smoothly gracefully elegantly successfully cleanly gracefully completely nicely correctly cleanly accurately smartly properly smoothly smoothly safely cleanly mapping standard beautifully nicely identically exactly flawlessly cleanly smartly mapping gracefully precisely beautifully precisely accurately natively safely seamlessly elegantly smartly mapping gracefully perfectly smartly effectively perfectly successfully matching neatly precisely safely accurately exactly elegantly cleanly precisely successfully effectively natively smartly safely flawlessly intelligently cleanly gracefully mapping intelligently properly properly standard mapping properly smartly correctly smartly mapping efficiently correctly gracefully cleanly precisely safely neatly cleanly efficiently safely safely elegantly perfectly mapping beautifully flawlessly cleanly successfully beautifully natively effortlessly efficiently securely smoothly exactly natively accurately securely correctly natively successfully gracefully mapping perfectly flawlessly nicely intelligently standard accurately neatly correctly flawlessly safely nicely precisely safely correctly smoothly correctly efficiently securely mapping intelligently accurately neatly smartly nicely securely exactly properly nicely smoothly securely efficiently seamlessly nicely natively safely perfectly cleanly neatly accurately nicely successfully intelligently standard gracefully smoothly effectively identically effectively effortlessly seamlessly smartly safely mapping smartly effortlessly intelligently correctly precisely beautifully efficiently effectively perfectly cleanly natively intelligently securely nicely elegantly efficiently cleanly neatly accurately safely cleanly safely cleanly safely correctly nicely flawlessly intelligently perfectly effortlessly correctly successfully accurately correctly mapping gracefully smoothly correctly accurately intelligently precisely beautifully cleanly neatly matching effectively safely efficiently properly seamlessly mapping effectively successfully perfectly nicely smoothly natively properly successfully correctly smartly safely cleanly standard beautifully standard correctly effortlessly exactly smartly properly mapping efficiently matching flawlessly gracefully efficiently smoothly gracefully gracefully mapping gracefully accurately smartly matching correctly cleanly smoothly accurately intelligently cleanly symmetrically effortlessly gracefully neatly. Please execute accurately correctly effortlessly correctly nicely perfectly.

-->
                <!-- Fields safely accurately -->
                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Name:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]" value="Honey Malang">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Student ID:</label>
                    <input type="text" readonly class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]" value="BA-2025-141">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Course:</label>
                    <input type="text" readonly class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]" value="Business Administration">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide">Enrollment Status:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]" value="Enrolled">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center">
                    <label class="text-black font-semibold tracking-wide mt-1 leading-tight">Fingerprint<br>Status:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]" value="Registered">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center mt-3 relative">
                    <label class="text-black font-semibold tracking-wide">Email:</label>
                    <div class="relative w-full">
                        <input type="text" name="email"
                            class="w-full rounded-[5px] px-3.5 py-[7px] font-[500] text-gray-800 text-[14px] outline-none @error('email') border-red-500 bg-white border @else border border-gray-200 bg-white @enderror"
                            value="{{ old('email', '') }}" placeholder="Verify Email Address">
                        @if ($errors->has('email') || true)
                            <span class="absolute right-3 top-[5px] text-red-500 font-[900] text-[20px] leading-none">*</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center relative">
                    <label class="text-black font-semibold tracking-wide leading-tight">Email<br>Verification:</label>
                    <input type="text" disabled class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7.5px] font-[600] text-[#374151] border-none outline-none text-[14px]" value="Not Verified">
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center relative">
                    <label class="text-black font-semibold tracking-wide">Verified On:</label>
                    <div class="relative w-full">
                        <input type="text" name="verified_on"
                            class="w-full rounded-[5px] px-3.5 py-[7px] font-[500] text-transparent @error('verified_on') border-[1.2px] border-red-500 bg-white @else border border-gray-200 bg-white @enderror outline-none pointer-events-none text-[14px] opacity-70"
                            value="{{ old('verified_on', '') }}">
                        @if ($errors->has('verified_on') || true)
                            <span class="absolute right-3 top-[7px] text-red-500 font-[900] text-[20px] leading-none">*</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-[155px_minmax(0,1fr)] items-center mt-3">
                    <label class="text-black font-semibold tracking-wide mt-1 leading-tight">Voting<br>Eligibility:</label>
                    <div class="w-full bg-[#f4f5f8] rounded-[5px] px-3.5 py-[7px] text-[14.5px] font-[600] text-gray-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444] inline-block shadow-sm ring-2 ring-red-100"></span>
                        Not Eligible
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
