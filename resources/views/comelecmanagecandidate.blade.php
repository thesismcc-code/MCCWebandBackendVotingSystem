<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Candidates</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1e47; /* Deep Navy Background */
        }

        .bg-main-panel {
            background-color: #0b2e7a; /* Lighter Royal Blue Panel */
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="p-4 md:p-8 min-h-screen text-white flex flex-col font-sans antialiased selection:bg-blue-500 selection:text-white">

    <!-- HEADER SECTION -->
    <div class="max-w-7xl mx-auto w-full mb-6 flex items-center gap-4 px-2">
        <!-- Back Button -->
        <a href="{{ route('view.comelec-dashboard') }}"
            class="bg-white text-[#0b2e7a] rounded-full w-10 h-10 flex items-center justify-center hover:scale-105 transition-transform shadow-md flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-white">Manage Candidates</h1>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="max-w-7xl mx-auto w-full bg-main-panel rounded-[2rem] p-8 shadow-2xl min-h-[600px]">

        <h2 class="text-white font-bold text-lg tracking-wide uppercase mb-8">POSITIONS</h2>

        <!-- CARDS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card: President -->
            <a href="#" class="block group">
                <div class="bg-white rounded-xl p-6 h-32 relative shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex flex-col h-full justify-between">
                        <div>
                            <h3 class="text-black font-extrabold text-[0.95rem] uppercase tracking-wide">PRESIDENT</h3>
                            <p class="text-black font-bold text-lg mt-1">2</p>
                        </div>
                    </div>

                    <!-- Arrow Icon Circle -->
                    <div class="absolute bottom-5 right-5 w-8 h-8 rounded-full bg-[#103080] flex items-center justify-center group-hover:bg-[#0c2466] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Card: Vice President -->
            <a href="#" class="block group">
                <div class="bg-white rounded-xl p-6 h-32 relative shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex flex-col h-full justify-between">
                        <div>
                            <h3 class="text-black font-extrabold text-[0.95rem] uppercase tracking-wide">VICE PRESIDENT</h3>
                            <p class="text-black font-bold text-lg mt-1">2</p>
                        </div>
                    </div>

                    <div class="absolute bottom-5 right-5 w-8 h-8 rounded-full bg-[#103080] flex items-center justify-center group-hover:bg-[#0c2466] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Card: Senators -->
            <a href="#" class="block group">
                <div class="bg-white rounded-xl p-6 h-32 relative shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex flex-col h-full justify-between">
                        <div>
                            <h3 class="text-black font-extrabold text-[0.95rem] uppercase tracking-wide">SENATORS</h3>
                            <p class="text-black font-bold text-lg mt-1">2</p>
                        </div>
                    </div>

                    <div class="absolute bottom-5 right-5 w-8 h-8 rounded-full bg-[#103080] flex items-center justify-center group-hover:bg-[#0c2466] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                </div>
            </a>

        </div>
    </div>

</body>

</html>
