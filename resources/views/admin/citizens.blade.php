<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizens – MuniciReport Admin</title>
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
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-titles h1 { font-family: 'DM Serif Display', serif; font-size: 30px; font-weight: 400; color: #0a2a4a; margin-bottom: 4px; }
        .page-titles p { font-size: 14.5px; color: #4a6fa5; }
        .btn-add { padding: 11px 22px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 11px; font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: opacity .2s; }
        .btn-add:hover { opacity: .88; }

        .search-bar { margin-bottom: 22px; display: flex; gap: 12px; }
        .search-wrap { position: relative; flex: 1; max-width: 460px; }
        .search-wrap svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ECAE1; }
        .search-input { width: 100%; padding: 11px 14px 11px 40px; border: 1.5px solid var(--border); border-radius: 11px; font-size: 14px; font-family: 'DM Sans', sans-serif; background: white; color: #0a2a4a; outline: none; }
        .search-input:focus { border-color: #3182BD; box-shadow: 0 0 0 3px rgba(49,130,189,0.12); }
        .search-btn { padding: 11px 20px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 11px; font-size: 14px; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; }

        .table-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 14px 18px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #4a6fa5; background: #F8FAFF; border-bottom: 1.5px solid var(--border); white-space: nowrap; }
        tbody tr { border-bottom: 1px solid #EFF3FF; transition: background .12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F8FAFF; }
        tbody td { padding: 16px 18px; font-size: 13.5px; color: #0a2a4a; vertical-align: middle; }
        .citizen-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg,#08519C,#6BAED6); color: white; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .name-cell { display: flex; align-items: center; gap: 12px; }
        .citizen-name { font-weight: 600; }
        .citizen-email { font-size: 12px; color: #4a6fa5; }
        .complaints-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; font-size: 12.5px; font-weight: 700; }
        .c0 { background: #F3F4F6; color: #6B7280; }
        .c1 { background: #D1FAE5; color: #065F46; }
        .c2 { background: #FEF3C7; color: #B45309; }
        .c3 { background: #EFF3FF;  color: #3182BD; }
        .c4 { background: #FEE2E2; color: #B91C1C; }
        .date-cell { font-size: 12.5px; color: #4a6fa5; }
        .btn-view { padding: 7px 18px; border: 1.5px solid var(--border); border-radius: 8px; background: white; color: #4a6fa5; font-size: 13px; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .15s; text-decoration: none; display: inline-block; }
        .btn-view:hover { background: var(--very-pale); border-color: #6BAED6; color: #3182BD; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.35); z-index: 300; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal { background: white; border-radius: 20px; padding: 32px; width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(8,81,156,0.25); }
        .modal h2 { font-family: 'DM Serif Display', serif; font-size: 22px; color: #0a2a4a; margin-bottom: 6px; }
        .modal p { font-size: 13.5px; color: #4a6fa5; margin-bottom: 24px; }
        .field { display: flex; flex-direction: column; margin-bottom: 16px; }
        .field label { font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #4a6fa5; margin-bottom: 7px; }
        .modal input[type="text"], .modal input[type="email"], .modal input[type="password"] {
            width: 100%; padding: 12px 15px; border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 14px; font-family: 'DM Sans', sans-serif; background: #EFF3FF; color: #0a2a4a;
            outline: none; transition: border-color .2s, box-shadow .2s;
        }
        .modal input:focus { border-color: #3182BD; box-shadow: 0 0 0 3px rgba(49,130,189,0.12); background: white; }
        .modal-btns { display: flex; gap: 10px; margin-top: 6px; }
        .btn-modal-cancel { flex: 1; padding: 13px; border: 1.5px solid var(--border); border-radius: 10px; background: white; color: #4a6fa5; font-size: 14px; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; }
        .btn-modal-cancel:hover { background: #EFF3FF; }
        .btn-modal-save { flex: 2; padding: 13px; border: none; border-radius: 10px; background: linear-gradient(135deg,#08519C,#3182BD,#6BAED6); color: white; font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; box-shadow: 0 5px 18px rgba(8,81,156,0.28); }

        @media (max-width: 1024px) { .content { padding: 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar { padding: 0 16px; }
            .content { padding: 16px; }
            .user-name { display: none; }
            .table-card { overflow-x: auto; }
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
       <a href="{{ route('admin.complaints') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Assign / Resolve
        </a>
        <a href="{{ route('admin.citizens') }}" class="nav-item active">
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
        <div class="page-header">
            <div class="page-titles">
                <h1>Registered Citizens</h1>
                <p>Manage citizen accounts and access.</p>
            </div>
            <button class="btn-add" onclick="document.getElementById('addModal').classList.add('show')">+ Add citizen</button>
        </div>

        @if(session('success'))
            <div style="background:#e8f4fd;border:1.5px solid #9ECAE1;color:#08519C;border-radius:12px;padding:13px 18px;font-size:14px;margin-bottom:24px;">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.citizens') }}">
            <div class="search-bar">
                <div class="search-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" class="search-input" placeholder="Search citizens…" value="{{ request('search') }}">
                </div>
                <button type="submit" class="search-btn">Search</button>
            </div>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Barangay</th>
                        <th>Contact</th>
                        <th>Complaints</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citizens as $citizen)
                    <tr>
                        <td>
                            <div class="name-cell">
                                <div class="citizen-avatar">{{ strtoupper(substr($citizen->name, 0, 2)) }}</div>
                                <div>
                                    <div class="citizen-name">{{ $citizen->name }}</div>
                                    <div class="citizen-email">{{ $citizen->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $citizen->barangay ?? '—' }}</td>
                        <td>{{ $citizen->phone ?? '—' }}</td>
                        <td>
                            @php
                                $n   = $citizen->complaints_count;
                                $cls = $n == 0 ? 'c0' : ($n == 1 ? 'c1' : ($n <= 3 ? 'c2' : ($n <= 5 ? 'c3' : 'c4')));
                            @endphp
                            <span class="complaints-badge {{ $cls }}">{{ $n }}</span>
                        </td>
                        <td class="date-cell">{{ $citizen->created_at->format('M Y') }}</td>
                        <td><a href="#" class="btn-view">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#4a6fa5;padding:40px;">No citizens found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($citizens->hasPages())
            <div style="display:flex;justify-content:center;gap:6px;padding:20px;">
                {{ $citizens->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Citizen Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h2>Add Citizen</h2>
        <p>Create a new resident account.</p>
        <form method="POST" action="{{ route('admin.citizens.store') }}">
            @csrf
            <div class="field">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Juan dela Cruz" required>
            </div>
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="juan@email.com" required>
            </div>
            <div class="field">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="09XX-XXX-XXXX">
            </div>
            <div class="field">
                <label>Barangay</label>
                <input type="text" name="barangay" placeholder="e.g. Brgy. Poblacion">
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimum 8 characters" required>
            </div>
            <div class="modal-btns">
                <button type="button" class="btn-modal-cancel" onclick="document.getElementById('addModal').classList.remove('show')">Cancel</button>
                <button type="submit" class="btn-modal-save">Add citizen</button>
            </div>
        </form>
    </div>
</div>

{{-- Open modal if there are validation errors --}}
@if($errors->any())
<script>
    document.getElementById('addModal').classList.add('show');
</script>
@endif

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
</script>
</body>
</html>