<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File a Complaint – MuniciReport</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --border:     #C6DBEF;
            --sidebar-w:  250px;
        }

        html, body { height: 100%; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--very-pale);
            color: var(--text-dark);
            display: flex; min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-w);
            background: #08519C;
            border-right: none;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 200;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }
        .brand {
            display: flex; align-items: center; gap: 11px;
            padding: 22px 20px 18px;
            border-bottom: 1.5px solid rgba(255,255,255,0.15);
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #3182BD, #9ECAE1);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 19px; flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }
        .brand-name { font-size: 14px; font-weight: 700; color: #ffffff; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }

        .nav-section { padding: 16px 12px 8px; }
        .nav-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 1.4px; color: #9ECAE1;
            text-transform: uppercase;
            padding: 0 8px; margin-bottom: 8px; display: block;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px;
            font-size: 14px; font-weight: 500;
            color: #C6DBEF; text-decoration: none;
            border-radius: 10px;
            border-left: 3px solid transparent;
            transition: all .15s; margin-bottom: 3px;
        }
        .nav-item:hover { background: rgba(255,255,255,0.12); color: #ffffff; }
        .nav-item.active {
            background: rgba(255,255,255,0.18); color: #ffffff;
            border-left-color: #9ECAE1; font-weight: 600;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        .topbar {
            background: white; border-bottom: 1.5px solid var(--border);
            padding: 0 32px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .hamburger { display: none; background: none; border: none; cursor: pointer; color: #4a6fa5; padding: 4px; }
        .hamburger svg { width: 22px; height: 22px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }

        .notif-btn {
            position: relative; width: 40px; height: 40px; border-radius: 50%;
            border: 1.5px solid var(--border); background: white;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #4a6fa5; transition: all .15s;
        }
        .notif-btn:hover { background: #EFF3FF; color: #3182BD; border-color: #6BAED6; }
        .notif-btn svg { width: 19px; height: 19px; }
        .notif-dot { position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid white; }

        .user-pill {
            display: flex; align-items: center; gap: 9px;
            padding: 5px 14px 5px 5px;
            border: 1.5px solid var(--border); border-radius: 999px;
            background: white; cursor: pointer; transition: all .15s;
        }
        .user-pill:hover { background: #EFF3FF; border-color: #6BAED6; }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #08519C, #6BAED6);
            color: white; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .user-name { font-size: 13.5px; font-weight: 600; color: #0a2a4a; }

        .content { flex: 1; padding: 40px 44px; }
        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 30px; font-weight: 400; color: #0a2a4a; margin-bottom: 5px;
        }
        .page-sub { font-size: 14.5px; color: #4a6fa5; margin-bottom: 30px; }

        .alert-success {
            background: #e8f4fd; border: 1.5px solid #9ECAE1; color: #08519C;
            border-radius: 12px; padding: 13px 18px; font-size: 14px; margin-bottom: 24px;
        }
        .alert-error {
            background: #fff5f5; border: 1.5px solid #fbb6b6; color: #c53030;
            border-radius: 12px; padding: 13px 18px; font-size: 14px; margin-bottom: 24px;
        }

        .form-card {
            background: white; border: 1.5px solid var(--border);
            border-radius: 20px; padding: 38px;
        }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .col-full { grid-column: 1 / -1; }
        .field { display: flex; flex-direction: column; }
        .field label {
            font-size: 11px; font-weight: 700; color: #4a6fa5;
            letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;
        }
        input[type="text"], input[type="tel"], select, textarea {
            width: 100%; padding: 13px 16px;
            border: 1.5px solid var(--border); border-radius: 11px;
            font-size: 14.5px; font-family: 'DM Sans', sans-serif;
            background: #EFF3FF; color: #0a2a4a;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none; appearance: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #3182BD;
            box-shadow: 0 0 0 3px rgba(49,130,189,0.13);
            background: white;
        }
        input::placeholder, textarea::placeholder { color: #9ECAE1; }
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 15px center;
            background-color: #EFF3FF;
            padding-right: 42px; cursor: pointer;
        }
        textarea { resize: vertical; min-height: 130px; line-height: 1.65; }

        .upload-label {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 8px;
            border: 2px dashed #9ECAE1; border-radius: 13px;
            padding: 38px 20px; background: #EFF3FF;
            cursor: pointer; transition: all .2s; text-align: center;
        }
        .upload-label:hover { border-color: #3182BD; background: #C6DBEF; }
        .upload-circle {
            width: 46px; height: 46px; border: 2px solid #9ECAE1;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #3182BD;
        }
        .upload-circle svg { width: 20px; height: 20px; }
        .upload-main { font-size: 14px; color: #4a6fa5; font-weight: 500; }
        .upload-hint { font-size: 12px; color: #9ECAE1; }
        #photo-preview { margin-top: 8px; font-size: 13px; color: #3182BD; font-weight: 500; }

        .btn-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
        .btn-clear {
            padding: 13px 28px; border: 1.5px solid var(--border);
            border-radius: 11px; background: white; color: #4a6fa5;
            font-size: 14.5px; font-family: 'DM Sans', sans-serif;
            font-weight: 600; cursor: pointer; transition: all .15s;
        }
        .btn-clear:hover { background: #EFF3FF; color: #3182BD; border-color: #6BAED6; }
        .btn-submit {
            padding: 13px 32px; border: none; border-radius: 11px;
            background: linear-gradient(135deg, #08519C 0%, #3182BD 50%, #6BAED6 100%);
            color: white; font-size: 14.5px; font-family: 'DM Sans', sans-serif;
            font-weight: 700; cursor: pointer;
            transition: opacity .2s, transform .15s;
            box-shadow: 0 5px 18px rgba(8,81,156,0.32);
        }
        .btn-submit:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        @media (max-width: 1024px) { .content { padding: 28px 24px; } .form-card { padding: 28px 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar { padding: 0 16px; }
            .content { padding: 20px 14px; }
            .form-card { padding: 20px 16px; border-radius: 16px; }
            .form-grid { grid-template-columns: 1fr; gap: 16px; }
            .col-full { grid-column: 1; }
            .page-title { font-size: 24px; }
            .btn-row { flex-direction: column-reverse; }
            .btn-clear, .btn-submit { width: 100%; text-align: center; }
            .user-name { display: none; }
        }
    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-icon">🏛</div>
        <div>
            <div class="brand-name">MuniciReport</div>
            <div class="brand-sub">Mayor's Office — Victoria</div>
        </div>
    </div>
    <div class="nav-section">
        <span class="nav-label">Menu</span>
        <a href="{{ route('resident.complaints.create') }}" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            File a Complaint
        </a>
        <a href="{{ route('resident.complaints.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            My Complaints
        </a>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <button class="hamburger" onclick="openSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="topbar-right">
            <button class="notif-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="notif-dot"></span>
            </button>
            <div class="user-pill">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <div class="content">
        <h1 class="page-title">File a Complaint</h1>
        <p class="page-sub">Submit your concern to the Mayor's Office online.</p>

        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="form-card">
            <form method="POST" action="{{ route('resident.complaints.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', Auth::user()->name) }}" placeholder="Juan dela Cruz" required>
                    </div>
                    <div class="field">
                        <label>Contact Number</label>
                        <input type="tel" name="contact_number" value="{{ old('contact_number', Auth::user()->phone ?? '') }}" placeholder="09XX-XXX-XXXX" required>
                    </div>
                    <div class="field col-full">
                        <label>Complaint Category</label>
                        <select name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="Sanitation & Waste">Sanitation &amp; Waste</option>
                            <option value="Road & Infrastructure">Road &amp; Infrastructure</option>
                            <option value="Noise & Disturbance">Noise &amp; Disturbance</option>
                            <option value="Illegal Construction">Illegal Construction</option>
                            <option value="Street Lighting">Street Lighting</option>
                            <option value="Flooding & Drainage">Flooding &amp; Drainage</option>
                            <option value="Public Safety">Public Safety</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="field col-full">
                        <label>Location / Barangay</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Brgy. Poblacion, Victoria" required>
                    </div>
                    <div class="field col-full">
                        <label>Description</label>
                        <textarea name="description" placeholder="Describe your complaint in detail..." required>{{ old('description') }}</textarea>
                    </div>
                    <div class="field col-full">
                        <label>Attach Photos (Optional)</label>
                        <label class="upload-label" for="photo-input">
                            <div class="upload-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="upload-main">Click to upload or drag &amp; drop</span>
                            <span class="upload-hint">PNG, JPG up to 5MB</span>
                        </label>
                        <input type="file" name="photo" id="photo-input" accept="image/*" style="display:none;" onchange="showFileName(this)">
                        <div id="photo-preview"></div>
                    </div>
                </div>
                <div class="btn-row">
                    <button type="reset" class="btn-clear" onclick="document.getElementById('photo-preview').textContent=''">Clear form</button>
                    <button type="submit" class="btn-submit">Submit complaint →</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }
    function showFileName(input) { document.getElementById('photo-preview').textContent = input.files[0] ? '📎 ' + input.files[0].name : ''; }
</script>
</body>
</html>