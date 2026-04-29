<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – MuniciReport</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(145deg, #03264d 0%, #08519C 30%, #2b7bbf 60%, #5aaee0 85%, #8ecfee 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .bg-circle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
        }
        .bg-c1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(107,174,214,0.2) 0%, transparent 70%);
            top: -150px; right: -150px;
        }
        .bg-c2 {
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(3,38,77,0.55) 0%, transparent 70%);
            bottom: -100px; left: -100px;
        }
        .bg-c3 {
            width: 250px; height: 250px;
            border: 1px solid rgba(158,202,225,0.18);
            top: 8%; right: 10%;
        }
        .bg-c4 {
            width: 140px; height: 140px;
            border: 1px solid rgba(158,202,225,0.12);
            top: 22%; right: 20%;
        }

        .card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            background: linear-gradient(160deg, rgba(255,255,255,0.98) 0%, rgba(235,244,255,0.97) 100%);
            border-radius: 28px;
            box-shadow:
                0 40px 100px rgba(3,38,77,0.45),
                0 2px 0 rgba(255,255,255,0.9) inset,
                0 0 0 1px rgba(49,130,189,0.12);
            padding: 28px 36px 26px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: rise 0.6s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(36px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
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
            width: 90px;
            height: 90px;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(8,81,156,0.32)) drop-shadow(0 2px 6px rgba(8,81,156,0.18));
            display: block;
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
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
            margin-bottom: 16px;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: #0a2a4a;
            margin-bottom: 4px;
            text-align: center;
        }
        .subtitle {
            color: #5a7fa8;
            font-size: 13px;
            font-weight: 400;
            text-align: center;
            margin-bottom: 20px;
        }

        form { width: 100%; }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fbb6b6;
            color: #c53030;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 14px;
            width: 100%;
        }
        .alert-success {
            background: #e8f4fd;
            border: 1px solid #9ECAE1;
            color: #08519C;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 14px;
            width: 100%;
        }

        .field { margin-bottom: 14px; }

        label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            color: #3a5f85;
            margin-bottom: 6px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .input-wrap { position: relative; }

        .input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #5a9fd4;
            width: 16px; height: 16px;
            pointer-events: none;
        }

        input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid #d0e4f4;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Outfit', sans-serif;
            background: rgba(255,255,255,0.85);
            color: #0a2a4a;
            transition: border-color .25s, box-shadow .25s, background .25s;
            outline: none;
        }
        input:focus {
            border-color: #3182BD;
            box-shadow: 0 0 0 4px rgba(49,130,189,0.12);
            background: #fff;
        }
        input::placeholder { color: #aec8de; }

        .forgot {
            text-align: right;
            margin-top: -8px;
            margin-bottom: 18px;
        }
        .forgot a {
            color: #3182BD;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
        }
        .forgot a:hover { text-decoration: underline; }

        .btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #063f7a 0%, #08519C 35%, #3182BD 70%, #6BAED6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            letter-spacing: 0.8px;
            box-shadow: 0 8px 24px rgba(8,81,156,0.45), 0 1px 0 rgba(255,255,255,0.15) inset;
            transition: transform .18s, box-shadow .18s;
            position: relative;
            overflow: hidden;
        }
        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.12) 0%, transparent 60%);
            pointer-events: none;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(8,81,156,0.5);
        }
        .btn:active { transform: translateY(0); box-shadow: 0 6px 16px rgba(8,81,156,0.35); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
            color: #8aafc8;
            font-size: 12px;
            font-weight: 500;
            width: 100%;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d4e6f4;
        }

        .footer-link {
            text-align: center;
            font-size: 13.5px;
            color: #5a7fa8;
        }
        .footer-link a {
            color: #08519C;
            font-weight: 700;
            text-decoration: none;
        }
        .footer-link a:hover { text-decoration: underline; }

        @media (max-width: 520px) {
            .card { padding: 22px 18px 20px; border-radius: 20px; }
            .brand-logo { width: 76px; height: 76px; }
            h2 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="bg-circle bg-c1"></div>
    <div class="bg-circle bg-c2"></div>
    <div class="bg-circle bg-c3"></div>
    <div class="bg-circle bg-c4"></div>

    <div class="card">

        <div class="logo-wrap">
            <div class="logo-glow"></div>
            <div class="logo-ring"></div>
            <img src="{{ asset('images/logo.png') }}" alt="MuniciReport" class="brand-logo">
        </div>

        <span class="brand-name">MuniciReport</span>
        <div class="brand-bar"></div>

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
                    <input type="email" name="email" placeholder="you@example.com" autocomplete="off" required value="{{ old('email') }}">
                </div>
            </div>

            <div class="field">
                <label>Password</label>
               <div class="input-wrap">
    <svg ...lock icon...></svg>
    <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off" required>
    <span onclick="togglePass('password', this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#5a9fd4;user-select:none;font-size:18px;">👁</span>
</div>
            </div>

            <div class="forgot"><a href="#">Forgot password?</a></div>
            <button class="btn" type="submit">Sign In →</button>
        </form>

        <div class="divider">or</div>
        <p class="footer-link">Don't have an account? <a href="/register">Create one</a></p>

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