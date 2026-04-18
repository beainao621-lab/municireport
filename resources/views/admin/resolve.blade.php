<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign & Resolve – MuniciReport Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --deep: #08519C; --mid: #3182BD; --teal: #6BAED6; --light-teal: #9ECAE1;
            --pale: #C6DBEF; --very-pale: #EFF3FF; --text-dark: #0a2a4a; --text-muted: #4a6fa5;
            --border: #C6DBEF; --sidebar-w: 250px;
        }
        html, body { height: 100%; }
        body { font-family: 'DM Sans', sans-serif; background: var(--very-pale); color: var(--text-dark); display: flex; min-height: 100vh; }

        .sidebar { width: var(--sidebar-w); background: #08519C; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform .28s cubic-bezier(.4,0,.2,1); }
        .brand { display: flex; align-items: center; gap: 11px; padding: 22px 20px 18px; border-bottom: 1.5px solid rgba(255,255,255,0.15); }
        .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg,#3182BD,#9ECAE1); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.25); }
        .brand-name { font-size: 14px; font-weight: 700; color: #fff; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }
        .nav-section { padding: 16px 12px 8px; }
        .nav-label { font-size: 10px; font-weight: 700; letter-spacing: 1.4px; color: #9ECAE1; text-transform: uppercase; padding: 0 8px; margin-bottom: 8px; display: block; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 12px 14px; font-size: 14px; font-weight: 500; color: #C6DBEF; text-decoration: none; border-radius: 10px; border-left: 3px solid transparent; transition: all .15s; margin-bottom: 3px; }
        .nav-item:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #9ECAE1; font-weight: 600; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-divider { margin: 8px 12px; border: none; border-top: 1px solid rgba(255,255,255,0.1); }
        .sidebar-footer { margin-top: auto; padding: 16px 12px; }
        .logout-btn { display: flex; align-items: center; gap: 10px; padding: 12px 14px; font-size: 14px; font-weight: 500; color: #9ECAE1; border: none; background: none; border-radius: 10px; cursor: pointer; width: 100%; transition: all .15s; }
        .logout-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .logout-btn svg { width: 18px; height: 18px; }

        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: white; border-bottom: 1.5px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .hamburger { display: none; background: none; border: none; cursor: pointer; color: #4a6fa5; padding: 4px; }
        .hamburger svg { width: 22px; height: 22px; }
        .topbar-badge { font-size: 11px; font-weight: 700; color: #4a6fa5; background: var(--very-pale); border: 1.5px solid var(--border); border-radius: 999px; padding: 3px 10px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }
        .notif-btn { position: relative; width: 40px; height: 40px; border-radius: 50%; border: 1.5px solid var(--border); background: white; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #4a6fa5; }
        .notif-btn svg { width: 19px; height: 19px; }
        .notif-dot { position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid white; }
        .user-pill { display: flex; align-items: center; gap: 9px; padding: 5px 14px 5px 5px; border: 1.5px solid var(--border); border-radius: 999px; background: white; cursor: pointer; }
        .avatar { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg,#08519C,#6BAED6); color: white; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
        .user-name { font-size: 13px; font-weight: 600; color: #0a2a4a; }

        .content { flex: 1; padding: 36px 44px; }
        .page-title { font-family: 'DM Serif Display', serif; font-size: 30px; font-weight: 400; color: #0a2a4a; margin-bottom: 4px; }
        .page-sub { font-size: 14.5px; color: #4a6fa5; margin-bottom: 28px; }

        /* Layout */
        .resolve-grid { display: grid; grid-template-columns: 1fr 420px; gap: 24px; align-items: start; }

        /* Info card */
        .info-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 28px; }
        .info-ref { font-size: 13px; font-weight: 700; color: #3182BD; margin-bottom: 14px; }
        .info-row { display: flex; flex-direction: column; gap: 4px; margin-bottom: 18px; }
        .info-label { font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #4a6fa5; }
        .info-value { font-size: 14.5px; color: #0a2a4a; line-height: 1.55; }
        .badge { font-size: 12px; font-weight: 600; border-radius: 999px; padding: 5px 13px; display: inline-block; }
        .badge-pending  { background: #FEF3C7; color: #B45309; }
        .badge-progress { background: #EFF3FF;  color: #3182BD; }
        .badge-resolved { background: #D1FAE5;  color: #065F46; }

        .photo-preview { width: 100%; border-radius: 11px; border: 1.5px solid var(--border); margin-top: 4px; object-fit: cover; max-height: 200px; }

        /* Form card */
        .form-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 28px; position: sticky; top: 80px; }
        .form-card h2 { font-size: 16px; font-weight: 700; color: #0a2a4a; margin-bottom: 20px; }
        .form-card .complaint-snippet { font-size: 13px; color: #4a6fa5; margin-bottom: 22px; padding-bottom: 18px; border-bottom: 1px solid var(--border); }
        .field { display: flex; flex-direction: column; margin-bottom: 18px; }
        .field label { font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #4a6fa5; margin-bottom: 8px; }
        input[type="text"], select, textarea {
            width: 100%; padding: 12px 15px; border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 14px; font-family: 'DM Sans', sans-serif; background: #EFF3FF; color: #0a2a4a;
            outline: none; transition: border-color .2s, box-shadow .2s, background .2s; appearance: none;
        }
        input:focus, select:focus, textarea:focus { border-color: #3182BD; box-shadow: 0 0 0 3px rgba(49,130,189,0.12); background: white; }
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 13px center; background-color: #EFF3FF; padding-right: 38px; cursor: pointer; }
        textarea { resize: vertical; min-height: 110px; line-height: 1.65; }
        .btn-row { display: flex; gap: 10px; }
        .btn-cancel { flex: 1; padding: 13px; border: 1.5px solid var(--border); border-radius: 10px; background: white; color: #4a6fa5; font-size: 14px; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; text-align: center; text-decoration: none; display: block; transition: all .15s; }
        .btn-cancel:hover { background: #EFF3FF; border-color: #6BAED6; color: #3182BD; }
        .btn-save { flex: 2; padding: 13px; border: none; border-radius: 10px; background: linear-gradient(135deg,#08519C,#3182BD,#6BAED6); color: white; font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; box-shadow: 0 5px 18px rgba(8,81,156,0.28); transition: opacity .2s, transform .15s; }
        .btn-save:hover { opacity: .88; transform: translateY(-1px); }

        @media (max-width: 1024px) { .resolve-grid { grid-template-columns: 1fr; } .form-card { position: static; } .content { padding: 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar { padding: 0 16px; }
            .content { padding: 16px; }
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
            <div class="brand-sub">Admin Panel</div>
        </div>
    </div>
    <div class="nav-section">
        <span class="nav-label">Overview</span>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.complaints') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Complaints
        </a>
    </div>
    <div class="nav-section">
        <hr class="nav-divider">
        <span class="nav-label">Management</span>
        <a href="#" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Assign / Resolve
        </a>
        <a href="{{ route('admin.citizens') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Citizens
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Reports
        </a>
    </div>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <button class="hamburger" onclick="openSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="topbar-badge">Admin Panel</span>
        </div>
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
        <h1 class="page-title">Assign & Resolve Complaint</h1>
        <p class="page-sub">Update complaint status and assign to personnel.</p>

        <div class="resolve-grid">
            <!-- Left: complaint details -->
            <div class="info-card">
                <div class="info-ref">{{ $complaint->reference_number }}</div>

                <div class="info-row">
                    <span class="info-label">Citizen</span>
                    <span class="info-value">{{ $complaint->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Contact</span>
                    <span class="info-value">{{ $complaint->contact_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Category</span>
                    <span class="info-value">{{ $complaint->category }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Location</span>
                    <span class="info-value">{{ $complaint->location }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Filed on</span>
                    <span class="info-value">{{ $complaint->created_at->format('F j, Y — g:i A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Current Status</span>
                    @php
                        $cls = match($complaint->status){
                            'Pending'     => 'badge-pending',
                            'In Progress' => 'badge-progress',
                            'Resolved'    => 'badge-resolved',
                            default       => ''
                        };
                    @endphp
                    <span class="badge {{ $cls }}">{{ $complaint->status }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Description</span>
                    <span class="info-value">{{ $complaint->description }}</span>
                </div>
                @if($complaint->assigned_officer)
                <div class="info-row">
                    <span class="info-label">Assigned Officer</span>
                    <span class="info-value">{{ $complaint->assigned_officer }}</span>
                </div>
                @endif
                @if($complaint->remarks)
                <div class="info-row">
                    <span class="info-label">Previous Remarks</span>
                    <span class="info-value">{{ $complaint->remarks }}</span>
                </div>
                @endif
                @if($complaint->photo)
                <div class="info-row">
                    <span class="info-label">Attached Photo</span>
                    <img src="{{ asset('storage/' . $complaint->photo) }}" alt="Complaint photo" class="photo-preview">
                </div>
                @endif
            </div>

            <!-- Right: update form -->
            <div class="form-card">
                <h2>Update: {{ $complaint->reference_number }}</h2>
                <div class="complaint-snippet">{{ Str::limit($complaint->description, 60) }} · {{ $complaint->location }} · {{ $complaint->category }}</div>

                <form method="POST" action="{{ route('admin.complaints.update', $complaint->id) }}">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div style="background:#fff5f5;border:1.5px solid #fbb6b6;color:#c53030;border-radius:10px;padding:12px 16px;font-size:13.5px;margin-bottom:18px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="field">
                        <label>Assign to Officer</label>
                        <input type="text" name="assigned_officer" value="{{ old('assigned_officer', $complaint->assigned_officer) }}" placeholder="e.g. R. Santos (Sanitation)">
                    </div>
                    <div class="field">
                        <label>Update Status</label>
                        <select name="status" required>
                            <option value="Pending"     {{ old('status', $complaint->status) == 'Pending'     ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ old('status', $complaint->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved"    {{ old('status', $complaint->status) == 'Resolved'    ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Remarks / Action Taken</label>
                        <textarea name="remarks" placeholder="Describe action taken or notes…">{{ old('remarks', $complaint->remarks) }}</textarea>
                    </div>
                    <div class="btn-row">
                        <a href="{{ route('admin.complaints') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-save">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }
</script>
</body>
</html>