<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MCC - Student Dashboard</title>

    <style>
        /* CSS RESET & VARIABLES */
        :root {
            --color-bg-body: #f7f9fa;
            --color-bg-nav: #ffffff;
            --color-bg-card: #ffffff;
            --color-text-title: #0a0a0a;
            --color-text-main: #333333;
            --color-text-muted: #555555;
            --color-primary-navy: #143166;
            --color-primary-blue: #0b5aff;
            --color-primary-red: #d9242d;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--color-bg-body);
            color: var(--color-text-main);
            font-family: var(--font-family);
            min-height: 100vh;
        }

        /* NAVIGATION BAR */
        .navbar {
            background-color: var(--color-bg-nav);
            border-bottom: 2px solid #eaedf1;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 12px 32px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-logo {
            width: 50px;
            height: auto;
            object-fit: contain;
        }

        .nav-title {
            color: var(--color-primary-navy);
            font-size: 1.15rem;
            font-weight: 700;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .btn-bell {
            background-color: #f1f4f8;
            color: var(--color-primary-navy);
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn-bell:hover {
            background-color: #e2e8f0;
        }

        .profile-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--color-primary-navy);
        }

        /* MAIN CONTENT SECTION */
        .main-section {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 32px;
        }

        .header-section {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 32px;
            color: var(--color-text-title);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 16px;
            color: var(--color-text-muted);
        }

        /* CARD COMPONENT */
        .card-panel {
            background-color: var(--color-bg-card);
            border: 1px solid #ebf0f4;
            border-radius: 16px;
            min-height: 60vh;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
        }

        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            max-width: 550px;
            opacity: 0.03;
            /* Matches faint styling requirement */
            pointer-events: none;
            z-index: 1;
        }

        .card-content {
            text-align: center;
            position: relative;
            z-index: 2;
            /* Sits directly above the background watermark logo */
        }

        .instruction-msg {
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 24px;
            color: var(--color-text-title);
        }

        /* BUTTONS AND ALERTS */
        .btn-refresh {
            background-color: var(--color-primary-blue);
            color: white;
            border: none;
            padding: 14px 44px;
            border-radius: 9999px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(11, 90, 255, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 24px;
        }

        .btn-refresh:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(11, 90, 255, 0.35);
        }

        .vote-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 15px;
            color: var(--color-text-title);
            font-weight: 500;
        }

        .alert-circle {
            background-color: var(--color-primary-red);
            color: white;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 11px;
            font-weight: bold;
        }

        /* MEDIA QUERIES (Responsiveness scaling based on standard practice constraints) */
        @media (max-width: 768px) {

            .nav-container,
            .main-section {
                padding: 12px 16px;
            }

            .nav-brand span {
                display: none;
            }

            /* Hide Title on narrow devices */
            .page-title {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <!-- Main Application Header Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Navbar left segment (Logo/Brand details) -->
            <div class="nav-brand">
                <img src="{{ asset('icons/logo_white_bg.png') }}" alt="MCC Logo" class="nav-logo">
                <span class="nav-title">Digital Voting System</span>
            </div>

            <!-- Navbar right segment (Notifications/User options) -->
            <div class="nav-actions">
                <button type="button" class="btn-bell" aria-label="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                    </svg>
                </button>
                <a href="{{ route('view.student-profile') }}">
                    <img src="{{ asset('images/person_image.png') }}" alt="Student Avatar Profile" class="profile-img">
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Functional Section for Web Interface Content -->
    <main class="main-section">

        <!-- Header Strings based closely off the mockup copy instructions -->
        <header class="header-section">
            <h1 class="page-title">Elections coming Soon!</h1>
            <!-- Replicated mockup phrase strings as seen identically -->
            <p class="page-subtitle">Get excited as your vote will shapes MCC's future leaders.</p>
        </header>

        <section class="card-panel">

            <!-- Central Ghost Logo -->
            <img src="{{ asset('icons/logo_white_bg.png') }}" class="watermark-img" alt="Watermark Base Design Layer"
                aria-hidden="true">

            <!-- Interactible Main Text Layer Box Fronted Details -->
            <div class="card-content">
                <p class="instruction-msg">Please check again once the election has started to see live results.</p>
                <button type="button" class="btn-refresh">Refresh</button>
                <div class="vote-status">
                    <span class="alert-circle">!</span>
                    <span>You have not voted yet</span>
                </div>
            </div>

        </section>

    </main>

</body>

</html>
