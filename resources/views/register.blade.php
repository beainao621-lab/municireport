<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – MuniciReport</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --deep:       #08519C;
            --mid:        #3182BD;
            --teal:       #6BAED6;
            --light-teal: #9ECAE1;
            --pale:       #C6DBEF;
            --very-pale:  #EFF3FF;
            --text-dark:  #0a2a4a;
            --text-muted: #4a6fa5;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(145deg, #03264d 0%, #08519C 25%, #3182BD 55%, #6BAED6 80%, #9ECAE1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            padding: 12px;
            overflow-y: auto;
        }

        body::before {
            content: '';
            position: fixed;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(158,202,225,0.18) 0%, transparent 65%);
            top: -200px; right: -200px;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(3,38,77,0.6) 0%, transparent 70%);
            bottom: -120px; left: -120px;
            pointer-events: none;
        }

        .deco {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            border: 1px solid rgba(158,202,225,0.15);
        }
        .deco-1 { width: 300px; height: 300px; top: 5%; right: 8%; }
        .deco-2 { width: 180px; height: 180px; top: 20%; right: 18%; }
        .deco-3 { width: 220px; height: 220px; bottom: 10%; left: 5%; }

        .card {
            position: relative;
            z-index: 1;
            background: rgba(239, 243, 255, 0.97);
            backdrop-filter: blur(32px);
            border-radius: 24px;
            box-shadow:
                0 40px 100px rgba(8,81,156,0.35),
                0 1px 0 rgba(255,255,255,0.7) inset,
                0 0 0 1px rgba(49,130,189,0.15);
            width: 100%;
            max-width: 480px;
            padding: 24px 32px 22px;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.55s cubic-bezier(.22,1,.36,1) both;
            margin: auto;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 14px;
        }

        .logo-wrap {
            position: relative;
            margin-bottom: 10px;
        }
        .logo-glow {
            position: absolute;
            inset: -16px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(49,130,189,0.18) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.6; }
        }
        .logo-ring {
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1.5px solid rgba(49,130,189,0.2);
        }
        .brand-logo {
            position: relative;
            width: 86px; height: 86px;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(8,81,156,0.32)) drop-shadow(0 2px 6px rgba(8,81,156,0.18));
            display: block;
        }
        .brand-name {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #08519C;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .brand-bar {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #08519C, #6BAED6);
            border-radius: 99px;
            margin-bottom: 4px;
        }

        h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 23px;
            font-weight: 400;
            color: #0a2a4a;
            margin-bottom: 2px;
        }
        .subtitle {
            color: #4a6fa5;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fbb6b6;
            color: #c53030;
            border-radius: 10px;
            padding: 9px 13px;
            font-size: 12.5px;
            margin-bottom: 12px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 12px;
        }
        .col-full { grid-column: 1 / -1; }

        .field { margin-bottom: 11px; }
        label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            color: #0a2a4a;
            margin-bottom: 4px;
            letter-spacing: 0.2px;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #3182BD;
            width: 14px; height: 14px;
            pointer-events: none;
        }
        input {
            width: 100%;
            padding: 9px 38px 9px 34px;
            border: 1.5px solid #9ECAE1;
            border-radius: 10px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            background: rgba(255,255,255,0.88);
            color: #0a2a4a;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }
        input:focus {
            border-color: #3182BD;
            box-shadow: 0 0 0 3px rgba(49,130,189,0.15);
            background: white;
        }
        input::placeholder { color: #9ECAE1; }

        .eye-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #3182BD;
            user-select: none;
            font-size: 16px;
            line-height: 1;
            padding: 2px 4px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #08519C 0%, #3182BD 50%, #6BAED6 100%);
            color: white;
            border: none;
            border-radius: 11px;
            font-size: 14.5px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            letter-spacing: 0.4px;
            box-shadow: 0 8px 22px rgba(8,81,156,0.45);
            margin-top: 6px;
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(8,81,156,0.5);
        }
        .btn:active { transform: translateY(0); box-shadow: none; }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 12px 0;
            color: #4a6fa5;
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #9ECAE1;
        }

        .footer-link {
            text-align: center;
            font-size: 13px;
            color: #4a6fa5;
        }
        .footer-link a {
            color: #08519C;
            font-weight: 700;
            text-decoration: none;
        }
        .footer-link a:hover { text-decoration: underline; }

        @media (max-width: 600px) {
            .card { padding: 20px 20px 18px; border-radius: 20px; }
            h2 { font-size: 20px; }
        }
        @media (max-width: 480px) {
            body { padding: 10px; }
            .card { padding: 18px 16px 16px; }
            .grid-2 { grid-template-columns: 1fr; gap: 0; }
            .col-full { grid-column: 1; }
            h2 { font-size: 19px; }
            .btn { font-size: 14px; padding: 11px; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="deco deco-1"></div>
    <div class="deco deco-2"></div>
    <div class="deco deco-3"></div>

    <div class="card">
        <div class="brand">
            <div class="logo-wrap">
                <div class="logo-glow"></div>
                <div class="logo-ring"></div>
                <img src="{{ asset('images/logo.png') }}" alt="MuniciReport" class="brand-logo">
            </div>
            <span class="brand-name">MuniciReport</span>
            <div class="brand-bar"></div>
        </div>

        <h2>Create account</h2>
        <p class="subtitle">Join MuniciReport as a resident</p>

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="grid-2">

                <div class="field col-full">
                    <label>Full Name</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.267.651 5.879 1.757M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <input type="text" name="name" placeholder="Juan dela Cruz" autocomplete="off" required>
                    </div>
                </div>

                <div class="field col-full">
                    <label>Email Address</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" name="email" placeholder="you@example.com" autocomplete="off" required>
                    </div>
                </div>

                <div class="field">
                    <label>Phone Number</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <input type="text" name="phone" placeholder="09XXXXXXXXX" autocomplete="off" required>
                    </div>
                </div>

                <div class="field">
                    <label>Location / Barangay</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <input type="text" name="location" placeholder="Brgy. San Antonio" autocomplete="off" required>
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input type="password" name="password" id="reg-pass" placeholder="Min. 6 characters" autocomplete="new-password" required>
                        <span class="eye-btn" onclick="togglePass('reg-pass', this)">👁</span>
                    </div>
                </div>

                <div class="field">
                    <label>Confirm Password</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <input type="password" name="password_confirmation" id="reg-confirm" placeholder="Repeat password" autocomplete="new-password" required>
                        <span class="eye-btn" onclick="togglePass('reg-confirm', this)">👁</span>
                    </div>
                </div>

            </div>

            <button class="btn" type="submit">Create Account →</button>
        </form>

        <div class="divider">or</div>
        <p class="footer-link">Already have an account? <a href="/login">Sign in</a></p>
    </div>

    <script>
        function togglePass(id, el) {
            var input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                el.textContent = '👁';
            } else {
                input.type = 'password';
                el.textContent = '👁';
            }
        }
    </script>
</body>
</html>