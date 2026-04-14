<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAO Final Results - Published</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
    </style>
</head>

<body x-data="{ showConfirm: false, showSuccess: false }" class="p-4 md:p-6 min-h-screen text-white flex flex-col">
    <div class="max-w-7xl mx-auto w-full mb-5 flex items-center justify-between px-2 mt-4 md:mt-2">
        <div class="flex items-center gap-4">
            <a href="{{ route('view.sao-dashboard') }}"
                class="bg-white text-[#113285] rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white leading-tight">Final Results</h1>
                <p class="text-blue-200 text-[11px] font-medium mt-0.5">Election Outcome Summary</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-3xl p-6 md:p-10 shadow-2xl flex-1 flex flex-col mb-4">
        <div class="flex justify-end mb-4">
            <button @click="showConfirm = true"
                class="bg-[#1ccb14] hover:bg-green-600 text-white text-xs md:text-sm font-bold py-3 px-6 md:px-8 rounded-lg shadow-md uppercase tracking-wide transition-colors">
                PUBLISH OFFICIAL RESULTS
            </button>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden shadow-xl w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="p-5 md:p-8 border-b lg:border-b-0 lg:border-r border-gray-300/70 space-y-6">
                    <section>
                        <span
                            class="inline-block bg-[#C0D8FA] text-[#0f172a] text-[13px] md:text-[14px] font-extrabold tracking-wide uppercase px-4 py-1.5 rounded-md">President</span>
                        <div class="mt-3 border border-gray-300 rounded-lg overflow-hidden">
                            <div class="px-6 py-5 space-y-4">
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Honey Malang</span>
                                    <span><span class="font-extrabold">53</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Myles Macrohon</span>
                                    <span><span class="font-extrabold">33</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Jahaira Ampaso</span>
                                    <span><span class="font-extrabold">25</span> votes</span>
                                </div>
                            </div>
                            <div class="bg-[#C1F8BB] px-6 py-4 flex items-center justify-between text-[15px] text-black">
                                <div><span class="font-extrabold uppercase mr-2">Winner:</span><span class="font-extrabold">Honey Malang</span>
                                </div>
                                <span><span class="font-extrabold">53</span> votes</span>
                            </div>
                        </div>
                    </section>

                    <section>
                        <span
                            class="inline-block bg-[#C0D8FA] text-[#0f172a] text-[13px] md:text-[14px] font-extrabold tracking-wide uppercase px-4 py-1.5 rounded-md">Vice
                            President</span>
                        <div class="mt-3 border border-gray-300 rounded-lg overflow-hidden">
                            <div class="px-6 py-5 space-y-4">
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Honey Malang</span>
                                    <span><span class="font-extrabold">53</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Myles Macrohon</span>
                                    <span><span class="font-extrabold">33</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Jahaira Ampaso</span>
                                    <span><span class="font-extrabold">25</span> votes</span>
                                </div>
                            </div>
                            <div class="bg-[#C1F8BB] px-6 py-4 flex items-center justify-between text-[15px] text-black">
                                <div><span class="font-extrabold uppercase mr-2">Winner:</span><span class="font-extrabold">Honey Malang</span>
                                </div>
                                <span><span class="font-extrabold">53</span> votes</span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="p-5 md:p-8">
                    <section>
                        <span
                            class="inline-block bg-[#C0D8FA] text-[#0f172a] text-[13px] md:text-[14px] font-extrabold tracking-wide uppercase px-4 py-1.5 rounded-md">Senators</span>
                        <div class="mt-3 border border-gray-300 rounded-lg overflow-hidden">
                            <div class="px-6 py-5 space-y-4">
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Honey Malang</span>
                                    <span><span class="font-extrabold">53</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Myles Macrohon</span>
                                    <span><span class="font-extrabold">33</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Jahaira Ampaso</span>
                                    <span><span class="font-extrabold">25</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Honey Malang</span>
                                    <span><span class="font-extrabold">53</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between text-[15px] text-slate-800">
                                    <span>Myles Macrohon</span>
                                    <span><span class="font-extrabold">33</span> votes</span>
                                </div>
                            </div>
                            <div class="bg-[#C1F8BB] px-6 py-4 space-y-3 text-[15px] text-black">
                                <div class="font-extrabold uppercase">Winner:</div>
                                <div class="flex items-center justify-between">
                                    <span>Jahaira Ampaso</span>
                                    <span><span class="font-extrabold">25</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Honey Malang</span>
                                    <span><span class="font-extrabold">53</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Myles Macrohon</span>
                                    <span><span class="font-extrabold">33</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Jahaira Ampaso</span>
                                    <span><span class="font-extrabold">25</span> votes</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Myles Macrohon</span>
                                    <span><span class="font-extrabold">33</span> votes</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showConfirm = false"></div>
        <div class="relative z-10 bg-white rounded-3xl p-8 w-full max-w-[420px] shadow-2xl text-center">
            <div class="flex justify-center mb-3">
                <div class="w-20 h-20">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="38" fill="#FEE2E2" />
                        <circle cx="40" cy="40" r="30" stroke="#DC2626" stroke-width="2.5"
                            fill="#FEF2F2" />
                        <rect x="37" y="25" width="6" height="20" rx="3" fill="#DC2626" />
                        <circle cx="40" cy="53" r="3.5" fill="#DC2626" />
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Are you sure?</h3>
            <p class="text-sm text-gray-600 font-medium">You want to publish the official results?</p>
            <div class="flex gap-3 mt-7 justify-center">
                <button @click="showConfirm = false"
                    class="bg-[#ce1b26] text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-md hover:bg-red-700 transition-colors">
                    Cancel
                </button>
                <button @click="showConfirm = false; showSuccess = true"
                    class="bg-[#1ccb14] text-white text-sm font-bold py-2.5 px-8 rounded-lg shadow-md hover:bg-green-600 transition-colors">
                    Submit
                </button>
            </div>
        </div>
    </div>

    <div x-show="showSuccess" x-cloak class="fixed inset-0 z-[130] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showSuccess = false"></div>
        <div class="relative z-10 bg-white rounded-3xl p-8 w-full max-w-[420px] shadow-2xl text-center">
            <button @click="showSuccess = false" class="absolute right-4 top-4 text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="flex justify-center mb-3">
                <div class="w-20 h-20">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="38" fill="#dcfce7" />
                        <circle cx="40" cy="40" r="30" stroke="#00D12E" stroke-width="3" fill="white" />
                        <path d="M28 42L36 50L52 30" stroke="#00D12E" stroke-width="5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Success!</h3>
            <p class="text-sm text-gray-600 font-medium">
                Official results have been published.
                The election results are now visible on
                all student dashboards.
            </p>
        </div>
    </div>
</body>

</html>
