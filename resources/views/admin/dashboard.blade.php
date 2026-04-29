{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – MuniciReport Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-w: 250px;

            /* Brand palette */
            --brand-deep:   #08519C;
            --brand-mid:    #3182BD;
            --brand-teal:   #6BAED6;
            --brand-pale:   #9ECAE1;

            /* Light-mode surfaces */
            --bg-page:      #EFF3FF;
            --bg-surface:   #ffffff;
            --bg-raised:    #F8FAFF;
            --bg-input:     #EFF3FF;

            /* Light-mode text */
            --text-primary:  #0a2a4a;
            --text-muted:    #4a6fa5;

            /* Light-mode border */
            --border:       #C6DBEF;

            /* Semantic colors — light */
            --color-orange: #D97706;
            --color-blue:   #1D6FAB;
            --color-green:  #047857;
            --color-green-bg:  #D1FAE5;
            --color-orange-bg: #FEF3C7;
            --color-blue-bg:   #EFF3FF;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-page:    #0d1b2a;
                --bg-surface: #0f2035;
                --bg-raised:  #162840;
                --bg-input:   #1a3050;

                --text-primary: #e2e8f0;
                --text-muted:   #7fb3d3;

                --border: #1e3a5f;

                --color-orange: #F59E0B;
                --color-blue:   #60A5FA;
                --color-green:  #34D399;
                --color-green-bg:  rgba(6,78,59,0.45);
                --color-orange-bg: rgba(120,53,15,0.45);
                --color-blue-bg:   rgba(30,58,138,0.35);
            }
        }

        html, body { height: 100%; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: #08519C;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
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
            background: linear-gradient(135deg,#3182BD,#9ECAE1);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }
        .brand-name { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: -0.2px; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }

        .nav-section { padding: 16px 12px 8px; flex: 1; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px;
            font-size: 13.5px; font-weight: 500;
            color: #C6DBEF;
            text-decoration: none;
            border-radius: 10px;
            border-left: 3px solid transparent;
            transition: all .15s;
            margin-bottom: 3px;
        }
        .nav-item:hover  { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #9ECAE1; font-weight: 600; }
        .nav-item i      { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
       

        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; padding: 36px 44px; }

        .page-title {
            font-size: 28px; font-weight: 700; letter-spacing: -0.5px;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        .page-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 28px; }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--bg-surface);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 22px 24px;
        }
        .stat-label {
            font-size: 11px; font-weight: 600;
            letter-spacing: 0.6px; text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
        }
        .stat-value {
            font-size: 34px; font-weight: 700;
            color: var(--text-primary);
            line-height: 1; margin-bottom: 8px;
        }
        .stat-value.orange { color: var(--color-orange); }
        .stat-value.blue   { color: var(--color-blue); }
        .stat-value.green  { color: var(--color-green); }

        .stat-sub { font-size: 12.5px; color: var(--text-muted); }
        .stat-sub.up   { color: var(--color-green); }
        .stat-sub.warn { color: var(--color-orange); }

        /* ── Two-column cards ── */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card {
            background: var(--bg-surface);
            border: 1.5px solid var(--border);
            border-radius: 16px; padding: 26px;
        }
        .card-title { font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; }

        /* ── Category bars ── */
        .cat-row { display: flex; flex-direction: column; gap: 14px; }
        .cat-item { display: flex; flex-direction: column; }
        .cat-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .cat-name  { font-size: 13px; font-weight: 500; color: var(--text-primary); }
        .cat-count { font-size: 13px; font-weight: 700; color: var(--text-primary); }
        .bar-track { height: 7px; background: var(--bg-input); border-radius: 99px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }
        .bar-fill.color-1 { background: #10B981; }
        .bar-fill.color-2 { background: #3B82F6; }
        .bar-fill.color-3 { background: #F59E0B; }
        .bar-fill.color-4 { background: #8B5CF6; }
        .bar-fill.color-5 { background: #6B7280; }

        /* ── Activity list ── */
        .activity-list { display: flex; flex-direction: column; gap: 14px; }
        .activity-item {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .activity-desc { font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 3px; }
        .activity-ref  { font-size: 12px; color: var(--text-muted); }

        /* ── Badges ── */
        .badge { font-size: 11.5px; font-weight: 600; border-radius: 999px; padding: 4px 11px; white-space: nowrap; }
        .badge-pending  { background: var(--color-orange-bg); color: var(--color-orange); }
        .badge-progress { background: var(--color-blue-bg);   color: var(--color-blue); }
        .badge-resolved { background: var(--color-green-bg);  color: var(--color-green); }

        /* ── Alert ── */
        .alert-success {
            background: var(--color-blue-bg);
            border: 1.5px solid var(--border);
            color: var(--brand-mid);
            border-radius: 12px; padding: 13px 18px;
            font-size: 14px; margin-bottom: 24px;
        }

        @media (prefers-color-scheme: dark) {
            .alert-success { color: #93C5FD; }
        }

        /* ── Responsive ── */
        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 1024px) { .content { padding: 24px; } .two-col { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .content { padding: 16px; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        }
        @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">


<div class="brand" style="flex-direction:column; align-items:center; justify-content:center; padding: 16px 20px 14px; border-bottom: 1.5px solid rgba(255,255,255,0.15); display:flex; gap:0;">
    <img src="{{ asset('images/logo.png') }}" alt="MuniciReport"
        style="height:90px; width:auto; object-fit:contain; display:block; margin-bottom:2px;">
    <div style="color:#fff; font-size:12px; font-weight:700; letter-spacing:2px; text-align:center; font-family:'Inter', sans-serif;">MUNICIREPORT</div>
</div>
    <div class="nav-section">
        <a href="{{ route('admin.dashboard') }}" class="nav-item active">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="{{ route('admin.complaints') }}" class="nav-item">
            <i class="fa-solid fa-clipboard-list"></i> Complaints
           
        </a>
        <a href="{{ route('admin.resolved') }}" class="nav-item">
            <i class="fa-solid fa-circle-check"></i> Resolved
        </a>
        <a href="{{ route('admin.citizens') }}" class="nav-item">
            <i class="fa-solid fa-users"></i> Citizens
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-item">
            <i class="fa-solid fa-chart-bar"></i> Reports
        </a>
    </div>
</aside>

<div class="main">
    <x-topbar :role="'admin'" />

    <div class="content">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-sub">Overview of complaint activity — {{ now()->format('F Y') }}</p>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Complaints</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-sub up"><i class="fa-solid fa-arrow-up" style="font-size:10px;"></i> {{ $stats['this_week'] }} this week</div>
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
                            $cls = match(strtolower($c->status)){
                                'pending'     => 'badge-pending',
                                'in progress' => 'badge-progress',
                                'in_progress' => 'badge-progress',
                                'resolved'    => 'badge-resolved',
                                default       => ''
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $c->status }}</span>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:14px;">No complaints yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    document.querySelectorAll('.bar-fill[data-width]').forEach(function(el) {
        el.style.width = el.getAttribute('data-width') + '%';
    });
</script>
</body>
</html>