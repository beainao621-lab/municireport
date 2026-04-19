<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports – MuniciReport Admin</title>
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

        /* Report type selector */
        .report-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
        .report-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 24px; text-align: center; text-decoration: none; transition: all .18s; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .report-card:hover { border-color: #6BAED6; background: #F8FAFF; transform: translateY(-2px); }
        .report-card.active-card { border-color: #08519C; background: linear-gradient(135deg,#EFF3FF,#C6DBEF); }
        .report-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .report-icon svg { width: 20px; height: 20px; }
        .icon-daily   { background: #D1FAE5; color: #065F46; }
        .icon-weekly  { background: #EFF3FF;  color: #3182BD; }
        .icon-monthly { background: #FEF3C7; color: #B45309; }
        .report-type { font-size: 15px; font-weight: 700; color: #0a2a4a; }
        .report-period { font-size: 12.5px; color: #4a6fa5; }
        .btn-generate { margin-top: 6px; padding: 9px 20px; border: none; border-radius: 9px; font-size: 13.5px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .15s; }
        .btn-daily   { background: #D1FAE5; color: #065F46; }
        .btn-weekly  { background: #08519C; color: white; box-shadow: 0 4px 14px rgba(8,81,156,0.3); }
        .btn-monthly { background: #F59E0B; color: white; box-shadow: 0 4px 14px rgba(245,158,11,0.3); }

        /* Report table */
        .report-section { background: white; border: 1.5px solid var(--border); border-radius: 16px; overflow: hidden; }
        .report-header { padding: 22px 24px; border-bottom: 1.5px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .report-heading { font-size: 15px; font-weight: 700; color: #0a2a4a; }
        .export-btns { display: flex; gap: 8px; }
        .btn-export { padding: 9px 18px; border: 1.5px solid var(--border); border-radius: 9px; background: white; color: #4a6fa5; font-size: 13px; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .15s; }
        .btn-export:hover { background: #EFF3FF; border-color: #6BAED6; color: #3182BD; }
        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 14px 20px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #4a6fa5; background: #F8FAFF; border-bottom: 1.5px solid var(--border); }
        thead th:not(:first-child) { text-align: center; }
        tbody tr { border-bottom: 1px solid #EFF3FF; }
        tbody tr:last-child { border-bottom: 2px solid var(--border); font-weight: 700; background: #F8FAFF; }
        tbody td { padding: 15px 20px; font-size: 13.5px; color: #0a2a4a; }
        tbody td:not(:first-child) { text-align: center; }
        .num-new      { color: #3182BD; font-weight: 700; }
        .num-progress { color: #F59E0B; font-weight: 700; }
        .num-resolved { color: #10B981; font-weight: 700; }
        .num-total    { color: #0a2a4a; font-weight: 700; }

        @media (max-width: 1024px) { .content { padding: 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .hamburger { display: flex; }
            .topbar { padding: 0 16px; }
            .content { padding: 16px; }
            .report-cards { grid-template-columns: 1fr; }
            .user-name { display: none; }
            .report-section { overflow-x: auto; }
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
        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Assign / Resolve
        </a>
        <a href="{{ route('admin.citizens') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Citizens
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-item active">
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
        <h1 class="page-title">Summary Reports</h1>
        <p class="page-sub">Generate daily, weekly, and monthly complaint reports.</p>

        <!-- Report type cards -->
        <div class="report-cards">
            <div class="report-card {{ $type == 'daily' ? 'active-card' : '' }}">
                <div class="report-icon icon-daily">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="report-type">Daily Report</div>
                <div class="report-period">Today's summary</div>
                <a href="{{ route('admin.reports', ['type' => 'daily']) }}" class="btn-generate btn-daily">Generate ↗</a>
            </div>
            <div class="report-card {{ $type == 'weekly' ? 'active-card' : '' }}">
                <div class="report-icon icon-weekly">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="report-type">Weekly Report</div>
                <div class="report-period">{{ now()->startOfWeek()->format('M j') }}–{{ now()->endOfWeek()->format('j, Y') }}</div>
                <a href="{{ route('admin.reports', ['type' => 'weekly']) }}" class="btn-generate btn-weekly">Generate ↗</a>
            </div>
            <div class="report-card {{ $type == 'monthly' ? 'active-card' : '' }}">
                <div class="report-icon icon-monthly">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="report-type">Monthly Report</div>
                <div class="report-period">{{ now()->format('F Y') }}</div>
                <a href="{{ route('admin.reports', ['type' => 'monthly']) }}" class="btn-generate btn-monthly">Generate ↗</a>
            </div>
        </div>

        <!-- Report table -->
        <div class="report-section">
            <div class="report-header">
                <div>
                    <div class="report-heading">Latest generated report — {{ $label }} ({{ ucfirst($type) }})</div>
                </div>
                <div class="export-btns">
                    <button class="btn-export" onclick="window.print()">Export PDF</button>
                    <button class="btn-export" onclick="exportCSV()">Export CSV</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>New</th>
                        <th>In Progress</th>
                        <th>Resolved</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                    <tr>
                        <td>{{ $row->category }}</td>
                        <td><span class="num-new">{{ $row->new_count }}</span></td>
                        <td><span class="num-progress">{{ $row->in_progress_count }}</span></td>
                        <td><span class="num-resolved">{{ $row->resolved_count }}</span></td>
                        <td><span class="num-total">{{ $row->total }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#4a6fa5;padding:32px;">No data for this period.</td>
                    </tr>
                    @endforelse

                    @if($rows->count() > 0)
                    <tr>
                        <td>Total</td>
                        <td><span class="num-new">{{ $totals['new'] }}</span></td>
                        <td><span class="num-progress">{{ $totals['in_progress'] }}</span></td>
                        <td><span class="num-resolved">{{ $totals['resolved'] }}</span></td>
                        <td><span class="num-total">{{ $totals['total'] }}</span></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    function exportCSV() {
        const rows  = document.querySelectorAll('table tr');
        const lines = Array.from(rows).map(r =>
            Array.from(r.querySelectorAll('th,td')).map(c => '"' + c.innerText + '"').join(',')
        );
        const blob = new Blob([lines.join('\n')], { type: 'text/csv' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'municireport-{{ $type }}-{{ now()->format("Y-m-d") }}.csv';
        a.click();
    }
</script>
</body>
</html>