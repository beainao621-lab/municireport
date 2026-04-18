<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – MuniciReport</title>
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
            --card-w:     500px;
            --card-h:     580px;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(145deg, #03264d 0%, #08519C 25%, #3182BD 55%, #6BAED6 80%, #9ECAE1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            padding: 16px;
            position: relative;
            overflow: hidden;
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
            border-radius: 28px;
            box-shadow:
                0 40px 100px rgba(8,81,156,0.35),
                0 1px 0 rgba(255,255,255,0.7) inset,
                0 0 0 1px rgba(49,130,189,0.15);
            width: 100%;
            max-width: var(--card-w);
            height: var(--card-h);
            padding: 0 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            animation: slideUp 0.55s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .brand-icon {
            width: 46px; height: 46px;
            background: linear-gradient(135deg, #08519C, #6BAED6);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 21px;
            box-shadow: 0 6px 16px rgba(8,81,156,0.4);
        }
        .brand-name {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #08519C;
            letter-spacing: 2px;
        }

        h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 34px;
            font-weight: 400;
            color: #0a2a4a;
            margin-bottom: 6px;
            line-height: 1.15;
        }
        .subtitle {
            color: #4a6fa5;
            font-size: 15px;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .card:has(.alert-error) .brand,
        .card:has(.alert-success) .brand { margin-bottom: 16px; }
        .card:has(.alert-error) h2,
        .card:has(.alert-success) h2 { margin-bottom: 4px; }
        .card:has(.alert-error) .subtitle,
        .card:has(.alert-success) .subtitle { margin-bottom: 14px; }
        .card:has(.alert-error) .field,
        .card:has(.alert-success) .field { margin-bottom: 14px; }
        .card:has(.alert-error) .forgot,
        .card:has(.alert-success) .forgot { margin-top: -8px; margin-bottom: 18px; }
        .card:has(.alert-error) .divider,
        .card:has(.alert-success) .divider { margin: 16px 0; }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fbb6b6;
            color: #c53030;
            border-radius: 10px;
            padding: 11px 15px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #e8f4fd;
            border: 1px solid #9ECAE1;
            color: #08519C;
            border-radius: 10px;
            padding: 11px 15px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .field { margin-bottom: 20px; }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #0a2a4a;
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #3182BD;
            width: 17px; height: 17px;
        }
        input {
            width: 100%;
            padding: 14px 15px 14px 44px;
            border: 1.5px solid #9ECAE1;
            border-radius: 12px;
            font-size: 14.5px;
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

        .forgot {
            text-align: right;
            margin-top: -14px;
            margin-bottom: 26px;
        }
        .forgot a {
            color: #3182BD;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
        }
        .forgot a:hover { text-decoration: underline; }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #08519C 0%, #3182BD 50%, #6BAED6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15.5px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            letter-spacing: 0.4px;
            box-shadow: 0 8px 24px rgba(8,81,156,0.45);
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(8,81,156,0.5);
        }
        .btn:active { transform: translateY(0); box-shadow: none; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
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
            font-size: 14px;
            color: #4a6fa5;
        }
        .footer-link a {
            color: #08519C;
            font-weight: 700;
            text-decoration: none;
        }
        .footer-link a:hover { text-decoration: underline; }

        @media (max-width: 560px) {
            .card { height: auto; padding: 36px 24px; }
            h2 { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="deco deco-1"></div>
    <div class="deco deco-2"></div>
    <div class="deco deco-3"></div>

    <div class="card">
        <div class="brand">
            <div class="brand-icon">🏛</div>
            <span class="brand-name">MUNICIREPORT</span>
        </div>

        <h2>Welcome back</h2>
        <p class="subtitle">Sign in to your account to continue</p>

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="field">
                <label>Email Address</label>
                <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <input type="email" name="email" placeholder="you@example.com" autocomplete="off" required>
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <input type="password" name="password" placeholder="Enter your password" autocomplete="off" required>
                </div>
            </div>

            <div class="forgot"><a href="#">Forgot password?</a></div>
            <button class="btn" type="submit">Sign In →</button>
        </form>

        <div class="divider">or</div>
        <p class="footer-link">Don't have an account? <a href="/register">Create one</a></p>
    </div>
</body>
</html>