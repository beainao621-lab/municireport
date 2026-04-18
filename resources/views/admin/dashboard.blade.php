<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – MuniciReport Admin</title>
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
        .user-role { font-size: 10px; color: #4a6fa5; font-weight: 500; }

        .content { flex: 1; padding: 36px 44px; }
        .page-header { margin-bottom: 28px; }
        .page-title { font-family: 'DM Serif Display', serif; font-size: 30px; font-weight: 400; color: #0a2a4a; margin-bottom: 4px; }
        .page-sub { font-size: 14.5px; color: #4a6fa5; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 28px; }
        .stat-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 22px 24px; }
        .stat-label { font-size: 12px; font-weight: 600; color: #4a6fa5; letter-spacing: 0.5px; margin-bottom: 10px; }
        .stat-value { font-size: 34px; font-weight: 700; color: #0a2a4a; line-height: 1; margin-bottom: 8px; }
        .stat-value.orange { color: #F59E0B; }
        .stat-value.blue   { color: #3182BD; }
        .stat-value.green  { color: #10B981; }
        .stat-sub { font-size: 12.5px; color: #4a6fa5; }
        .stat-sub.up { color: #10B981; }
        .stat-sub.warn { color: #F59E0B; }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 26px; }
        .card-title { font-size: 15px; font-weight: 700; color: #0a2a4a; margin-bottom: 20px; }

        .cat-row { display: flex; flex-direction: column; gap: 14px; }
        .cat-item { display: flex; flex-direction: column; }
        .cat-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .cat-name { font-size: 13.5px; font-weight: 500; color: #0a2a4a; }
        .cat-count { font-size: 13.5px; font-weight: 700; color: #0a2a4a; }
        .bar-track { height: 7px; background: #EFF3FF; border-radius: 99px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 99px; width: 0; transition: width 0.4s ease; }
        .bar-fill.color-1 { background: #10B981; }
        .bar-fill.color-2 { background: #3182BD; }
        .bar-fill.color-3 { background: #F59E0B; }
        .bar-fill.color-4 { background: #6366F1; }
        .bar-fill.color-5 { background: #6B7280; }

        .activity-list { display: flex; flex-direction: column; gap: 14px; }
        .activity-item { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid var(--border); }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .activity-desc { font-size: 13.5px; font-weight: 600; color: #0a2a4a; margin-bottom: 3px; }
        .activity-ref  { font-size: 12px; color: #4a6fa5; }
        .badge { font-size: 11.5px; font-weight: 600; border-radius: 999px; padding: 4px 11px; white-space: nowrap; }
        .badge-pending  { background: #FEF3C7; color: #B45309; }
        .badge-progress { background: #EFF3FF;  color: #3182BD; }
        .badge-resolved { background: #D1FAE5;  color: #065F46; }

        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 1024px) { .content { padding: 24px; } .two-col { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar { padding: 0 16px; }
            .content { padding: 16px; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
            .user-name { display: none; }
        }
        @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.complaints') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Complaints
            @if($stats['pending'] > 0)
                <span style="margin-left:auto;background:#ef4444;color:white;font-size:10px;font-weight:700;border-radius:999px;padding:2px 7px;">{{ $stats['pending'] }}</span>
            @endif
        </a>
    </div>
    <div class="nav-section">
        <hr class="nav-divider">
        <span class="nav-label">Management</span>
        <a href="#" class="nav-item" style="opacity:.5;pointer-events:none;">
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
                <div style="display:flex;flex-direction:column;">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">Administrator</span>
                </div>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-sub">Overview of complaint activity — {{ now()->format('F Y') }}</p>
        </div>

        @if(session('success'))
            <div style="background:#e8f4fd;border:1.5px solid #9ECAE1;color:#08519C;border-radius:12px;padding:13px 18px;font-size:14px;margin-bottom:24px;">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Complaints</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-sub up">↑ {{ $stats['this_week'] }} this week</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending Review</div>
                <div class="stat-value orange">{{ $stats['pending'] }}</div>
                <div class="stat-sub warn">Needs action</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">In Progress</div>
                <div class="stat-value blue">{{ $stats['in_progress'] }}</div>
                <div class="stat-sub">Being handled</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Resolved</div>
                <div class="stat-value green">{{ $stats['resolved'] }}</div>
                <div class="stat-sub up">{{ $stats['resolution_rate'] }}% resolution rate</div>
            </div>
        </div>

        <div class="two-col">
            <div class="card">
                <div class="card-title">Complaints by category</div>
                @php $maxCat = $byCategory->max('total') ?: 1; @endphp
                <div class="cat-row">
                    @foreach($byCategory as $i => $cat)
                    @php
                        $colorClass = 'color-' . (($i % 5) + 1);
                        $pct = round(($cat->total / $maxCat) * 100);
                    @endphp
                    <div class="cat-item">
                        <div class="cat-top">
                            <span class="cat-name">{{ $cat->category }}</span>
                            <span class="cat-count">{{ $cat->total }}</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill {{ $colorClass }}" data-width="{{ $pct }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-title">Recent activity</div>
                <div class="activity-list">
                    @forelse($recent as $c)
                    <div class="activity-item">
                        <div>
                            <div class="activity-desc">{{ Str::limit($c->description, 35) }}</div>
                            <div class="activity-ref">{{ $c->reference_number }}</div>
                        </div>
                        @php
                            $cls = match($c->status){
                                'pending'     => 'badge-pending',
                                'in_progress' => 'badge-progress',
                                'resolved'    => 'badge-resolved',
                                default       => ''
                            };
                            $label = match($c->status){
                                'pending'     => 'Pending',
                                'in_progress' => 'In Progress',
                                'resolved'    => 'Resolved',
                                default       => ucfirst($c->status)
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $label }}</span>
                    </div>
                    @empty
                    <p style="color:#4a6fa5;font-size:14px;">No complaints yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    document.querySelectorAll('.bar-fill').forEach(function(bar) {
        bar.style.width = bar.getAttribute('data-width') + '%';
    });
</script>
</body>
</html>