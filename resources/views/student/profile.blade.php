<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MCC - Student Profile</title>

    <style>
        :root {
            --mcc-blue: #0F1D46;
            --mcc-red: #D7282F;
            --brand-white: #FFFFFF;
            --text-dark: #222222;
            --text-muted: #8F8F8F;
            --bg-body: #FAFBFC;
            --border-standard: #EDEDED;
            --input-shadow: 0px 4px 18px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--brand-white);
            color: var(--text-dark);
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .watermark {
            position: fixed;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.03;
            z-index: 0;
            pointer-events: none;
            width: 50%;
            max-width: 700px;
        }

        .watermark img {
            width: 100%;
            height: auto;
            display: block;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background-color: var(--brand-white);
            border-bottom: 1px solid var(--border-standard);
            position: relative;
            z-index: 50;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-left img {
            height: 45px;
            border-radius: 50%;
        }

        .nav-left h1 {
            color: var(--mcc-blue);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .icon-btn-bg {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background-color: #EDEEF0;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .icon-btn-bg svg {
            width: 18px;
            fill: var(--mcc-blue);
        }

        .icon-btn-bg:hover {
            background-color: #DDE0E5;
        }

        .avatar-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #E6EBF5, #DDE4F0);
            color: #5A6785;
            border: 3px solid var(--mcc-blue);
            font-weight: 700;
            line-height: 1;
            user-select: none;
        }

        .avatar-placeholder--header {
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }

        main {
            position: relative;
            z-index: 10;
            max-width: 1024px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .btn-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            padding: 10px 24px;
            border-radius: 9999px;
            text-decoration: none;
            border: none;
        }

        .btn-pill-back {
            background-color: var(--mcc-blue);
            color: var(--brand-white);
        }

        .btn-pill-back svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .btn-pill-update {
            background-color: var(--mcc-blue);
            color: var(--brand-white);
            padding: 12px 32px;
        }

        .profile-row-editor {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 3.5rem;
            margin-bottom: 2rem;
        }

        .profile-avatar-block {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .avatar-placeholder--main {
            width: 130px;
            height: 130px;
            font-size: 2rem;
        }

        .controls-flex {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-outline-normal {
            padding: 12px 20px;
            font-weight: 500;
            font-size: 0.95rem;
            border: 1px solid var(--border-standard);
            border-radius: 12px;
            background-color: var(--brand-white);
            color: var(--text-dark);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-outline-danger {
            padding: 12px 30px;
            font-weight: 500;
            font-size: 0.95rem;
            border: 1px solid var(--mcc-red);
            border-radius: 12px;
            background-color: var(--brand-white);
            color: var(--mcc-red);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-outline-normal:hover,
        .btn-outline-danger:hover {
            background-color: #F8F9FB;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            column-gap: 2.5rem;
            row-gap: 2rem;
            margin-top: 1.5rem;
        }

        .form-control-block {
            display: flex;
            flex-direction: column;
        }

        .form-control-block label {
            margin-bottom: 0.75rem;
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .exact-card-input {
            padding: 20px 24px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--text-dark);
            background-color: var(--brand-white);
            border: 2px solid transparent;
            border-radius: 10px;
            box-shadow: var(--input-shadow);
            outline: none;
            transition: var(--transition);
        }

        .exact-card-input::placeholder {
            color: var(--text-muted);
        }

        .exact-card-input:focus {
            border-color: #E2E8F0;
        }

        .placeholder-mimic {
            color: var(--text-muted) !important;
        }

        .warning-text-note {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .exclam-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: var(--mcc-red);
            color: white;
            font-weight: 800;
            font-size: 11px;
        }

        @media (max-width: 800px) {
            .profile-row-editor {
                flex-direction: column;
                align-items: flex-start;
                gap: 2rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .btn-pill-update {
                align-self: flex-start;
            }

            .watermark {
                width: 80%;
            }
        }
    </style>
</head>

<body>

    <div class="watermark">
        <img src="{{ asset('icons/logo_white_bg.png') }}" alt="big-logo">
    </div>

    <header class="header">
        <div class="nav-left">
            <img src="{{ asset('icons/logo_white_bg.png') }}" alt="logo">
            <h1>Digital Voting System</h1>
        </div>
        <div class="nav-right">
            <button class="icon-btn-bg" aria-label="Notifications">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z" />
                </svg>
            </button>
            <div class="avatar-placeholder avatar-placeholder--header" aria-label="profile-placeholder">U</div>
        </div>
    </header>

    <main>
        <a href="javascript:history.back()" class="btn-pill btn-pill-back">
            <svg viewBox="0 0 24 24">
                <path d="M20 12H4M4 12L10 6M4 12L10 18" />
            </svg>
            Back
        </a>

        <section class="profile-row-editor">

            <div class="profile-avatar-block">
                <div class="avatar-placeholder avatar-placeholder--main" aria-label="main-profile-placeholder">U</div>
                <div class="controls-flex">
                    <button type="button" class="btn-outline-normal">Upload new picture</button>
                    <button type="button" class="btn-outline-danger">Delete</button>
                </div>
            </div>

            <button type="submit" form="profile-form" class="btn-pill btn-pill-update">Update Profile</button>
        </section>



        <form id="profile-form" action="{{ route('update-profile') }}" method="POST">
            @csrf



            <div class="form-grid">

                <div class="form-control-block">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="exact-card-input"
                        value="{{ old('first_name', $profile['first_name'] ?? '') }}">
                </div>

                <div class="form-control-block">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="exact-card-input"
                        value="{{ old('last_name', $profile['last_name'] ?? '') }}">
                </div>

                <div class="form-control-block">
                    <label>Email Address</label>
                    <input type="email" name="email" class="exact-card-input placeholder-mimic"
                        placeholder="example@gmail.com" value="{{ old('email', $profile['email'] ?? '') }}">

                    @if (blank(old('email', $profile['email'] ?? '')))
                        <div class="warning-text-note">
                            <span class="exclam-indicator">!</span>
                            Enter your email to activate your account
                        </div>
                    @endif
                </div>

                <div class="form-control-block">
                    <label>Degree</label>
                    <input type="text" name="course" class="exact-card-input"
                        value="{{ old('course', $profile['course'] ?? '') }}">
                </div>

            </div>

        </form>

    </main>

</body>

</html>
