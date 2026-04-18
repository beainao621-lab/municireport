<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints – MuniciReport</title>
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
            width: var(--sidebar-w); background: #08519C;
            border-right: none;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 200; transition: transform .28s cubic-bezier(.4,0,.2,1);
        }
        .brand {
            display: flex; align-items: center; gap: 11px;
            padding: 22px 20px 18px; border-bottom: 1.5px solid rgba(255,255,255,0.15);
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #3182BD, #9ECAE1);
            border-radius: 11px; display: flex; align-items: center; justify-content: center;
            font-size: 19px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }
        .brand-name { font-size: 14px; font-weight: 700; color: #ffffff; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }

        .nav-section { padding: 16px 12px 8px; }
        .nav-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1.4px; color: #9ECAE1;
            text-transform: uppercase; padding: 0 8px; margin-bottom: 8px; display: block;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px; font-size: 14px; font-weight: 500;
            color: #C6DBEF; text-decoration: none;
            border-radius: 10px; border-left: 3px solid transparent;
            transition: all .15s; margin-bottom: 3px;
        }
        .nav-item:hover { background: rgba(255,255,255,0.12); color: #ffffff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #ffffff; border-left-color: #9ECAE1; font-weight: 600; }
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
            padding: 5px 14px 5px 5px; border: 1.5px solid var(--border);
            border-radius: 999px; background: white; cursor: pointer; transition: all .15s;
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
        .page-sub { font-size: 14.5px; color: #4a6fa5; margin-bottom: 28px; }

        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 32px; }
        .stat-card {
            background: white; border: 1.5px solid var(--border);
            border-radius: 16px; padding: 22px 24px;
        }
        .stat-label { font-size: 12.5px; color: #4a6fa5; margin-bottom: 8px; font-weight: 500; }
        .stat-value { font-size: 32px; font-weight: 700; color: #0a2a4a; line-height: 1; }
        .stat-value.teal  { color: #3182BD; }
        .stat-value.green { color: #16a34a; }

        .complaint-card {
            background: white; border: 1.5px solid var(--border);
            border-radius: 16px; padding: 24px 26px; margin-bottom: 18px;
            transition: box-shadow .2s;
        }
        .complaint-card:hover { box-shadow: 0 4px 20px rgba(8,81,156,0.1); }

        .complaint-header {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 14px; margin-bottom: 6px;
        }
        .complaint-title { font-size: 15.5px; font-weight: 700; color: #0a2a4a; line-height: 1.4; }

        .badge { padding: 5px 13px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; flex-shrink: 0; }
        .badge-pending  { background: #fef9c3; color: #854d0e; }
        .badge-progress { background: #dbeafe; color: #1d4ed8; }
        .badge-resolved { background: #dcfce7; color: #15803d; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        .complaint-meta { font-size: 12.5px; color: #4a6fa5; margin-bottom: 10px; }
        .complaint-desc { font-size: 14px; color: #2a4a6a; line-height: 1.65; margin-bottom: 14px; }
        .complaint-dates { font-size: 12.5px; color: #4a6fa5; margin-bottom: 16px; }
        .complaint-dates span { margin-right: 18px; }

        .divider { border: none; border-top: 1.5px solid #C6DBEF; margin: 16px 0; }

        .timeline { display: flex; flex-direction: column; gap: 10px; }
        .tl-item { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #4a6fa5; }
        .tl-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
        .tl-dot.done    { background: #3182BD; }
        .tl-dot.pending { background: #cbd5e1; }

        .empty-state {
            background: white; border: 1.5px solid var(--border);
            border-radius: 16px; padding: 60px 20px; text-align: center;
        }
        .empty-icon { font-size: 40px; margin-bottom: 12px; opacity: 0.5; }
        .empty-state p { font-size: 15px; color: #4a6fa5; margin-bottom: 16px; }
        .btn-file {
            display: inline-block; padding: 12px 26px;
            background: linear-gradient(135deg, #08519C, #6BAED6);
            color: white; border-radius: 10px; font-size: 14px;
            font-weight: 600; text-decoration: none;
            box-shadow: 0 4px 14px rgba(8,81,156,0.28);
            transition: opacity .15s;
        }
        .btn-file:hover { opacity: 0.88; }

        @media (max-width: 1024px) { .content { padding: 28px 24px; } .stats { gap: 14px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar { padding: 0 16px; }
            .content { padding: 20px 14px; }
            .stats { grid-template-columns: 1fr 1fr; gap: 12px; }
            .stats .stat-card:last-child { grid-column: 1 / -1; }
            .complaint-card { padding: 18px 16px; border-radius: 14px; }
            .page-title { font-size: 24px; }
            .user-name { display: none; }
            .complaint-header { flex-direction: column; gap: 8px; }
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
        <a href="{{ route('resident.complaints.create') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            File a Complaint
        </a>
        <a href="{{ route('resident.complaints.index') }}" class="nav-item active">
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
                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                    <span class="notif-dot"></span>
                @endif
            </button>
            <div class="user-pill">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <div class="content">
        <h1 class="page-title">My Complaints</h1>
        <p class="page-sub">Track the status of all your submitted complaints.</p>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Submitted</div>
                <div class="stat-value">{{ $complaints->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">In Progress</div>
                <div class="stat-value teal">{{ $complaints->where('status', 'in_progress')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Resolved</div>
                <div class="stat-value green">{{ $complaints->where('status', 'resolved')->count() }}</div>
            </div>
        </div>

        @forelse($complaints as $complaint)
        <div class="complaint-card">
            <div class="complaint-header">
                <div class="complaint-title">{{ Str::limit($complaint->description, 60) }}</div>
                @php
                    $badgeClass = match($complaint->status) {
                        'in_progress' => 'badge-progress',
                        'resolved'    => 'badge-resolved',
                        'rejected'    => 'badge-rejected',
                        default       => 'badge-pending',
                    };
                    $badgeLabel = match($complaint->status) {
                        'in_progress' => 'In Progress',
                        'resolved'    => 'Resolved',
                        'rejected'    => 'Rejected',
                        default       => 'Pending',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
            <div class="complaint-meta">#MR-{{ str_pad($complaint->id, 4, '0', STR_PAD_LEFT) }} · {{ $complaint->category }} · {{ $complaint->location }}</div>
            <div class="complaint-desc">{{ $complaint->description }}</div>
            <div class="complaint-dates">
                <span>Filed: {{ $complaint->created_at->format('M d, Y') }}</span>
                <span>Updated: {{ $complaint->updated_at->format('M d, Y') }}</span>
            </div>
            <hr class="divider">
            <div class="timeline">
                <div class="tl-item">
                    <div class="tl-dot done"></div>
                    <span><strong>{{ $complaint->created_at->format('M d') }}</strong> — Complaint submitted</span>
                </div>
                @if($complaint->status !== 'pending')
                <div class="tl-item">
                    <div class="tl-dot done"></div>
                    <span><strong>{{ $complaint->updated_at->format('M d') }}</strong> — Status updated to {{ $badgeLabel }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <p>You haven't filed any complaints yet.</p>
            <a href="{{ route('resident.complaints.create') }}" class="btn-file">File a Complaint →</a>
        </div>
        @endforelse
    </div>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }
</script>
</body>
</html>