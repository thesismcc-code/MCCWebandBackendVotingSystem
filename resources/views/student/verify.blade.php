<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MCC - Student Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mcc-navy: #152B66;
            --input-bg-color: #ebf1fc;
            --body-text-dark: #333333;
            --white: #ffffff;
            --input-border-radius: 8px;
            --btn-border-radius: 9999px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            height: 100vh;
            width: 100%;
            background-color: var(--white);
            color: var(--body-text-dark);
        }

        .wrapper {
            display: flex;
            height: 100%;
            width: 100%;
        }

        .panel-brand {
            flex: 1;
            background-color: var(--mcc-navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: var(--white);
        }

        .panel-brand img {
            width: 100%;
            max-width: 320px;
            margin-bottom: 30px;
            display: block;
        }

        .panel-brand h1 {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .panel-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: var(--white);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .form-container h2 {
            font-size: 26px;
            font-weight: 700;
            color: var(--mcc-navy);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 15px;
            color: var(--body-text-dark);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .subtitle strong {
            display: block;
            color: #1a1a1a;
            font-weight: 700;
        }

        .input-group {
            display: flex;
            gap: 15px;
            margin-bottom: 35px;
        }

        .code-input {
            width: 65px;
            height: 65px;
            background-color: var(--input-bg-color);
            border: 2px solid transparent;
            border-radius: var(--input-border-radius);
            font-size: 30px;
            font-weight: 600;
            text-align: center;
            color: var(--mcc-navy);
            outline: none;
            transition: all 0.2s ease-in-out;
            caret-color: var(--mcc-navy);
        }

        .code-input:focus {
            background-color: #E2EBFA;
            border-color: var(--mcc-navy);
        }

        .btn-verify {
            background-color: var(--mcc-navy);
            color: var(--white);
            border: none;
            border-radius: var(--btn-border-radius);
            width: 310px;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-bottom: 25px;
        }

        .btn-verify:hover {
            opacity: 0.9;
        }

        .support-links {
            font-size: 14px;
        }

        .support-links span {
            color: #555555;
        }

        .support-links a {
            color: var(--mcc-navy);
            text-decoration: none;
            font-weight: 700;
        }

        .support-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .wrapper {
                flex-direction: column;
            }
            .panel-brand {
                flex: unset;
                height: auto;
                padding: 40px 20px;
            }
            .panel-brand img {
                max-width: 220px;
                margin-bottom: 15px;
            }
            .panel-brand h1 {
                font-size: 24px;
            }
            .panel-form {
                align-items: flex-start;
                padding-top: 50px;
                height: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Left Panel / Graphical Assets side -->
        <div class="panel-brand">
            <!-- Ensure framework bindings remain perfectly untampered  -->
            <img src="{{ asset('icons/logo.png') }}" alt="Mandaue City College Logo">
            <h1>Digital Voting System</h1>
        </div>

        <!-- Right Panel / Primary verification process component block  -->
        <div class="panel-form">
            <div class="form-container">
                <h2>Email Verification</h2>

                <p class="subtitle">
                    We have sent a code to your email<br>
                    <strong>Mylesmacrohon@gmail.com</strong>
                </p>

                <!-- Logic groups setup directly supporting single char typing arrays naturally seen heavily alongside codes  -->
                <div class="input-group">
                    <input type="text" class="code-input" maxlength="1" autofocus autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <input type="text" class="code-input" maxlength="1" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <input type="text" class="code-input" maxlength="1" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <input type="text" class="code-input" maxlength="1" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>

                <button class="btn-verify" type="button">Verify</button>

                <div class="support-links">
                    <span>Didn't receive code?</span>
                    <a href="#">Resend</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Automatic code input moving logic built seamlessly over elements avoiding cumbersome explicit external libraries  -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.code-input');

            inputs.forEach((input, index) => {
                input.addEventListener('keydown', (e) => {
                    // Logic allows Backspace to pull behavior to the preceding layout perfectly allowing typing to intuitively move backward without touching device interface bounds.
                    if (e.key === 'Backspace' && !input.value) {
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });

                input.addEventListener('input', (e) => {
                    // Logic naturally progresses state upon text population cleanly pushing towards Verify submit execution cleanly via inputs layout positions
                    if (input.value) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        } else {
                            // Can additionally add a manual Submit action binding dynamically trigger mechanism if desirable directly towards standard HTML buttons / DOM objects:
                            // document.querySelector('.btn-verify').click()
                            input.blur();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
