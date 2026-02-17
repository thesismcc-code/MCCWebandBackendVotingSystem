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
        body { font-family: 'Inter', sans-serif; background-color: #102864; }
        .bg-main-panel { background-color: #0C3189; }
        [x-cloak] { display: none !important; }

        .badge-enrolled {
            background-color: #dcfce7;
            color: #166534;
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 9999px;
            font-weight: 600;
        }

        /* Scanning Animation */
        @keyframes scan-move {
            0%, 100% { top: 10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            50% { top: 85%; }
        }
        .scan-animation {
            animation: scan-move 2s infinite ease-in-out;
            box-shadow: 0 0 8px #22c504;
        }
    </style>
</head>

<body
    x-data="{
        openModal: false,
        step: 1,
        isScanning: false,

        // Notification State
        notify: { show: false, type: 'success', title: '', message: '' },

        // Helper to trigger notification
        triggerNotification(type, title, message) {
            this.notify.type = type;
            this.notify.title = title;
            this.notify.message = message;
            this.notify.show = true;

            // Auto-close notification after 4 seconds (Slightly longer for Modals)
            setTimeout(() => { this.notify.show = false; }, 4000);
        },

        // Scan Logic
        handleScan() {
            this.isScanning = true;

            setTimeout(() => {
                this.isScanning = false;
                this.openModal = false; // Close the form modal

                // --- LOGIC: SUCCESS VS FAILURE ---
                // Toggle 'success' to test different outcomes
                const success = true;

                if (success) {
                    this.triggerNotification(
                        'success',
                        'Success!',
                        'Fingerprint has been successfully registered.'
                    );
                } else {
                    this.triggerNotification(
                        'error',
                        'Failed!',
                        'Unable to capture fingerprint. Please try again.'
                    );
                }

                // Reset Form Steps after modal closes
                setTimeout(() => { this.step = 1; }, 500);
            }, 2000);
        }
    }"
    class="p-4 md:p-6 min-h-screen text-white flex flex-col font-sans relative"
>

    <!-- ======================================================= -->
    <!-- CENTERED SUCCESS/ERROR MODAL (Based on Screenshot)      -->
    <!-- ======================================================= -->
    <div x-cloak x-show="notify.show" class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop -->
        <div x-show="notify.show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                <!-- Notification Card -->
                <div x-show="notify.show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="notify.show = false"
                    class="relative transform overflow-hidden rounded-[20px] bg-white px-4 pb-8 pt-8 text-center shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[420px]"
                >
                    <!-- Close 'X' Button -->
                    <div class="absolute right-4 top-4">
                        <button @click="notify.show = false" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- SUCCESS CONTENT -->
                    <template x-if="notify.type === 'success'">
                        <div class="flex flex-col items-center justify-center mt-2">
                            <!-- Large Green Ring Icon -->
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-[6px] border-[#04de00] mb-6">
                                <svg class="h-14 w-14 text-[#04de00]" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <!-- Title & Text -->
                            <h3 class="text-3xl font-extrabold text-gray-900 mb-3" x-text="notify.title"></h3>
                            <div class="px-6">
                                <p class="text-sm font-medium text-gray-600 leading-relaxed" x-text="notify.message"></p>
                            </div>
                        </div>
                    </template>

                    <!-- ERROR CONTENT (Variation) -->
                    <template x-if="notify.type === 'error'">
                        <div class="flex flex-col items-center justify-center mt-2">
                            <!-- Large Red Ring Icon -->
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-[6px] border-red-500 mb-6">
                                <svg class="h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <!-- Title & Text -->
                            <h3 class="text-3xl font-extrabold text-gray-900 mb-3" x-text="notify.title"></h3>
                            <div class="px-6">
                                <p class="text-sm font-medium text-gray-600 leading-relaxed" x-text="notify.message"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <!-- END NOTIFICATION MODAL -->



    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.quick-access') }}" class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Manage Accounts</h1>
                <p class="text-blue-200 text-[11px] font-medium">Manage Roles</p>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 relative shadow-2xl flex-1 flex flex-col">

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl p-5 flex items-center gap-5 shadow-lg">
                <div class="bg-[#0066ff] w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>
                </div>
                <div><h2 class="text-3xl font-bold text-gray-900 leading-none">3</h2><p class="text-gray-600 text-xs font-semibold mt-1">Enrolled Students</p></div>
            </div>
            <div class="bg-white rounded-2xl p-5 flex items-center gap-5 shadow-lg">
                <div class="bg-[#22c504] w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565m4.382 3.161A10.5 10.5 0 0112 22.5a10.5 10.5 0 01-9.568-7.557" /></svg>
                </div>
                <div><h2 class="text-3xl font-bold text-gray-900 leading-none">3</h2><p class="text-gray-600 text-xs font-semibold mt-1">Enrolled Fingerprint</p></div>
            </div>
            <div class="bg-white rounded-2xl p-5 flex items-center gap-5 shadow-lg">
                <div class="bg-[#facc15] w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                </div>
                <div><h2 class="text-3xl font-bold text-gray-900 leading-none">0</h2><p class="text-gray-600 text-xs font-semibold mt-1">Enrolled Today</p></div>
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

        <!-- Table Section -->
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
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-6 py-6 font-medium">CS-2025-001</td>
                            <td class="px-6 py-6 text-gray-900">Jose Perolino</td>
                            <td class="px-6 py-6">Computer Science</td>
                            <td class="px-6 py-6">4th Year</td>
                            <td class="px-6 py-6">12-05-2025</td>
                            <td class="px-6 py-6 text-center"><span class="badge-enrolled">Enrolled</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FLOATING ADD BUTTON -->
        <button @click="openModal = true" class="fixed bottom-8 right-8 bg-[#0066ff] w-14 h-14 rounded-full flex items-center justify-center text-white shadow-[0_4px_14px_rgba(0,0,0,0.3)] hover:bg-blue-600 hover:scale-105 transition-all duration-300 z-40">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>

    </div>

    <!-- ========================================== -->
    <!-- INPUT / SCANNER MODAL (Existing)           -->
    <!-- ========================================== -->
    <div x-cloak x-show="openModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div
            x-show="openModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    x-show="openModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="!isScanning && (openModal = false)"
                    class="relative transform rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[550px] min-h-[420px]"
                >
                    <!-- STEP 1: Student Info Form -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-200 delay-100" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Student Information</h3>
                        <form action="#" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Student ID <span class="text-red-500">*</span></label>
                                <input type="text" placeholder="00-0000-000" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" placeholder="Name" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" placeholder="Last Name" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Course/Degree <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none appearance-none bg-white transition cursor-pointer">
                                        <option>Select Course</option>
                                        <option>Computer Science</option>
                                        <option>Information Technology</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Year Level <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none appearance-none bg-white transition cursor-pointer">
                                        <option>Select Year Level</option>
                                        <option>1st Year</option>
                                        <option>2nd Year</option>
                                        <option>3rd Year</option>
                                        <option>4th Year</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#eff6ff] border border-blue-100 rounded-lg p-3 flex gap-3 items-start mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-500 shrink-0 mt-0.5">
                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-[11px] font-bold text-blue-600">Next Step: Fingerprint Enrollment</p>
                                    <p class="text-[10px] text-blue-500 leading-snug">After filling in the student information, you'll proceed to capture their fingerprint for biometric authentication.</p>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button
                                    type="button"
                                    @click="openModal = false"
                                    class="flex-1 rounded-xl border border-red-400 text-red-500 py-3 text-xs font-bold hover:bg-red-50 transition"
                                >
                                    Cancel
                                </button>
                                <button type="button" @click="step = 2" class="flex-1 rounded-xl bg-[#0066ff] text-white py-3 text-xs font-bold hover:bg-blue-600 shadow-md transition">Proceed to Fingerprint</button>
                            </div>
                        </form>
                    </div>

                    <!-- STEP 2: Fingerprint Scan -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-8 flex flex-col h-full w-full">
                        <div class="w-full text-left mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Fingerprint Enrollment</h3>
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center w-full mt-2">
                            <p class="text-gray-800 text-sm font-medium mb-10" x-text="isScanning ? 'Scanning Fingerprint...' : 'Place your Finger on the scanner'"></p>

                            <!-- SCANNER UI with Lucide Icon -->
                            <div class="relative w-40 h-40 flex items-center justify-center mb-10">
                                <div class="absolute inset-0">
                                    <div class="absolute top-0 left-0 w-8 h-8 border-t-[3px] border-l-[3px] border-[#2563eb]"></div>
                                    <div class="absolute top-0 right-0 w-8 h-8 border-t-[3px] border-r-[3px] border-[#2563eb]"></div>
                                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-[3px] border-l-[3px] border-[#2563eb]"></div>
                                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-[3px] border-r-[3px] border-[#2563eb]"></div>
                                </div>

                                <!-- Actual Fingerprint Scanner PNG -->
                                <img
                                    src="/icons/fingerprint_scanner.png"
                                    alt="Fingerprint Scanner"
                                    class="w-27 h-27 object-contain"
                                    :class="isScanning ? 'opacity-100' : 'opacity-80'"
                                >

                                <div class="absolute left-6 right-6 h-[3px] bg-[#22c504] rounded-full scan-animation"></div>
                            </div>

                            <button
                                @click="handleScan()"
                                :disabled="isScanning"
                                :class="isScanning ? 'bg-gray-400 cursor-not-allowed' : 'bg-[#20c200] hover:bg-green-600 active:scale-95'"
                                class="w-48 text-white font-semibold py-3 px-6 rounded-full shadow-lg transform transition-all duration-300"
                            >
                                <span x-show="!isScanning">Scan</span>
                                <span x-show="isScanning">Scanning...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
