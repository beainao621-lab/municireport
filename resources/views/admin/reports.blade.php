{{-- resources/views/admin/reports.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports – MuniciReport Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-w: 250px;
            --brand-deep:   #08519C;
            --brand-mid:    #3182BD;
            --brand-teal:   #6BAED6;
            --brand-pale:   #9ECAE1;
            --bg-page:      #EFF3FF;
            --bg-surface:   #ffffff;
            --bg-raised:    #F8FAFF;
            --bg-input:     #EFF3FF;
            --text-primary:  #0a2a4a;
            --text-muted:    #4a6fa5;
            --border:       #C6DBEF;
            --color-orange: #D97706;
            --color-blue:   #1D6FAB;
            --color-green:  #047857;
            --color-green-bg:  #D1FAE5;
            --color-orange-bg: #FEF3C7;
            --color-blue-bg:   #EFF3FF;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-page:    #0d1b2a; --bg-surface: #0f2035;
                --bg-raised:  #162840; --bg-input:   #1a3050;
                --text-primary: #e2e8f0; --text-muted: #7fb3d3;
                --border: #1e3a5f;
                --color-orange: #F59E0B; --color-blue: #60A5FA;
                --color-green:  #34D399;
                --color-green-bg:  rgba(6,78,59,0.45);
                --color-orange-bg: rgba(120,53,15,0.45);
                --color-blue-bg:   rgba(30,58,138,0.35);
            }
        }

        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-page); color: var(--text-primary); display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar { width: var(--sidebar-w); background: #08519C; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform .28s cubic-bezier(.4,0,.2,1); }
        .brand { display: flex; align-items: center; gap: 11px; padding: 22px 20px 18px; border-bottom: 1.5px solid rgba(255,255,255,0.15); }
        .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg,#3182BD,#9ECAE1); border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.25); }
        .brand-name { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: -0.2px; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }
        .nav-section { padding: 16px 12px 8px; flex: 1; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13.5px; font-weight: 500; color: #C6DBEF; text-decoration: none; border-radius: 10px; border-left: 3px solid transparent; transition: all .15s; margin-bottom: 3px; }
        .nav-item:hover  { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #9ECAE1; font-weight: 600; }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; padding: 36px 44px; }

        .page-title { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; color: var(--text-primary); margin-bottom: 4px; }
        .page-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 28px; }

        /* ── Report type cards ── */
        .report-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .report-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 16px; padding: 24px; text-align: center; text-decoration: none; transition: all .18s; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .report-card:hover { border-color: var(--brand-teal); background: var(--bg-raised); transform: translateY(-2px); }
        .report-card.active-card { border-color: var(--brand-deep); background: var(--bg-raised); }
        .report-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .icon-daily   { background: var(--color-green-bg);  color: var(--color-green); }
        .icon-weekly  { background: var(--color-blue-bg);   color: var(--color-blue); }
        .icon-monthly { background: var(--color-orange-bg); color: var(--color-orange); }
        .report-type { font-size: 15px; font-weight: 700; color: var(--text-primary); }
        .report-period { font-size: 12.5px; color: var(--text-muted); }
        .btn-generate { margin-top: 6px; padding: 9px 20px; border: none; border-radius: 9px; font-size: 13.5px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; text-decoration: none; display: inline-block; transition: opacity .15s; }
        .btn-generate:hover { opacity: .85; }
        .btn-daily   { background: var(--color-green-bg);  color: var(--color-green); }
        .btn-weekly  { background: var(--brand-deep);       color: #fff; }
        .btn-monthly { background: var(--color-orange);     color: #fff; }

        /* ── Advanced filter panel ── */
        .filter-panel { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; padding: 20px 24px; margin-bottom: 24px; }
        .filter-panel-title { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
        .filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
        .filter-field { display: flex; flex-direction: column; gap: 5px; }
        .filter-field label { font-size: 11px; font-weight: 600; color: var(--text-muted); }
        .filter-field select,
        .filter-field input[type="date"] {
            padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 9px;
            font-size: 13px; font-family: 'Inter', sans-serif;
            background: var(--bg-input); color: var(--text-primary); outline: none;
        }
        .filter-field select { padding-right: 32px; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 9px center; }
        .filter-field input[type="date"]:focus,
        .filter-field select:focus { border-color: var(--brand-mid); background: var(--bg-surface); }
        .filter-apply-btn { padding: 9px 22px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 9px; font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; white-space: nowrap; }
        .filter-apply-btn:hover { opacity: .88; }
        .filter-reset-link { font-size: 12.5px; color: var(--text-muted); text-decoration: none; padding: 9px 4px; white-space: nowrap; }
        .filter-reset-link:hover { color: var(--brand-mid); }

        /* ── Report table section ── */
        .report-section { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 16px; overflow: hidden; }
        .report-header { padding: 22px 24px; border-bottom: 1.5px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .report-heading { font-size: 15px; font-weight: 700; color: var(--text-primary); }
        .report-period-label { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .btn-excel { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border: none; border-radius: 10px; background: linear-gradient(135deg, #1D6F42, #217346); color: white; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; box-shadow: 0 4px 14px rgba(29,111,66,0.3); transition: opacity .15s, transform .15s; }
        .btn-excel:hover { opacity: .9; transform: translateY(-1px); }

        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 14px 20px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); background: var(--bg-raised); border-bottom: 1.5px solid var(--border); }
        thead th:not(:first-child) { text-align: center; }
        tbody tr { border-bottom: 1px solid var(--border); }
        tbody tr:last-child { border-bottom: 2px solid var(--border); font-weight: 700; background: var(--bg-raised); }
        tbody td { padding: 15px 20px; font-size: 13.5px; color: var(--text-primary); }
        tbody td:not(:first-child) { text-align: center; }
        .num-new      { color: var(--color-blue);  font-weight: 700; }
        .num-progress { color: var(--color-orange); font-weight: 700; }
        .num-resolved { color: var(--color-green);  font-weight: 700; }
        .num-total    { color: var(--text-primary); font-weight: 700; }

        /* Active filter badge */
        .active-filter-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px 3px 8px; background: var(--color-blue-bg); border: 1.5px solid var(--border); border-radius: 999px; font-size: 11.5px; color: var(--color-blue); font-weight: 600; }
        .active-filter-badge a { color: var(--color-blue); text-decoration: none; font-size: 13px; margin-left: 3px; }
        .active-filter-badge a:hover { color: var(--brand-deep); }

        @media (max-width: 1024px) { .content { padding: 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .content { padding: 16px; }
            .report-cards { grid-template-columns: 1fr; }
            .report-section { overflow-x: auto; }
            .filter-row { flex-direction: column; }
        }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="{{ route('admin.complaints') }}" class="nav-item"><i class="fa-solid fa-clipboard-list"></i> Complaints</a>
        <a href="{{ route('admin.resolved') }}" class="nav-item"><i class="fa-solid fa-circle-check"></i> Resolved</a>
        <a href="{{ route('admin.citizens') }}" class="nav-item"><i class="fa-solid fa-users"></i> Citizens</a>
        <a href="{{ route('admin.reports') }}" class="nav-item active"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    </div>
</aside>

<div class="main">
    <x-topbar :role="'admin'" />

    <div class="content">
        <h1 class="page-title">Summary Reports</h1>
        <p class="page-sub">Generate and filter daily, weekly, and monthly complaint reports.</p>

        {{-- Report type cards --}}
        <div class="report-cards">
            <div class="report-card {{ $type == 'daily' ? 'active-card' : '' }}">
                <div class="report-icon icon-daily"><i class="fa-solid fa-clock"></i></div>
                <div class="report-type">Daily Report</div>
                <div class="report-period">Today's summary</div>
                <a href="{{ route('admin.reports', ['type' => 'daily']) }}" class="btn-generate btn-daily">
                    View <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i>
                </a>
            </div>
            <div class="report-card {{ $type == 'weekly' ? 'active-card' : '' }}">
                <div class="report-icon icon-weekly"><i class="fa-solid fa-calendar-week"></i></div>
                <div class="report-type">Weekly Report</div>
                <div class="report-period">{{ now()->startOfWeek()->format('M j') }}–{{ now()->endOfWeek()->format('j, Y') }}</div>
                <a href="{{ route('admin.reports', ['type' => 'weekly']) }}" class="btn-generate btn-weekly">
                    View <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i>
                </a>
            </div>
            <div class="report-card {{ $type == 'monthly' ? 'active-card' : '' }}">
                <div class="report-icon icon-monthly"><i class="fa-solid fa-chart-column"></i></div>
                <div class="report-type">Monthly Report</div>
                <div class="report-period">{{ now()->format('F Y') }}</div>
                <a href="{{ route('admin.reports', ['type' => 'monthly']) }}" class="btn-generate btn-monthly">
                    View <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i>
                </a>
            </div>
        </div>

        {{-- Advanced filter panel --}}
        <div class="filter-panel">
            <div class="filter-panel-title"><i class="fa-solid fa-sliders"></i> Filter Report</div>
            <form method="GET" action="{{ route('admin.reports') }}">
                <input type="hidden" name="type" value="{{ $type !== 'custom' ? $type : 'monthly' }}">
                <div class="filter-row">
                    <div class="filter-field">
                        <label>Category</label>
                        <select name="filter_category">
                            <option value="all">All Categories</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat }}" {{ $filterCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Date From</label>
                        <input type="date" name="date_from" value="{{ $customStart ?? '' }}">
                    </div>
                    <div class="filter-field">
                        <label>Date To</label>
                        <input type="date" name="date_to" value="{{ $customEnd ?? '' }}">
                    </div>
                    <button type="submit" class="filter-apply-btn"><i class="fa-solid fa-filter"></i> Apply Filter</button>
                    <a href="{{ route('admin.reports', ['type' => $type !== 'custom' ? $type : 'monthly']) }}" class="filter-reset-link">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                </div>
            </form>

            {{-- Active filter badges --}}
            @if($filterCategory !== 'all' || $type === 'custom')
            <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;">
                @if($filterCategory !== 'all')
                <span class="active-filter-badge">
                    <i class="fa-solid fa-tag"></i> {{ $filterCategory }}
                    <a href="{{ route('admin.reports', array_merge(request()->except('filter_category'), ['filter_category' => 'all'])) }}">×</a>
                </span>
                @endif
                @if($type === 'custom' && $customStart && $customEnd)
                <span class="active-filter-badge">
                    <i class="fa-solid fa-calendar-days"></i>
                    {{ \Carbon\Carbon::parse($customStart)->format('M j, Y') }} – {{ \Carbon\Carbon::parse($customEnd)->format('M j, Y') }}
                    <a href="{{ route('admin.reports', ['type' => 'monthly']) }}">×</a>
                </span>
                @endif
            </div>
            @endif
        </div>

        {{-- Report table --}}
        <div class="report-section">
            <div class="report-header">
                <div>
                    <div class="report-heading">
                        {{ $type === 'custom' ? 'Custom Range' : ucfirst($type) }} Report — {{ $label }}
                        @if($filterCategory !== 'all')
                            <span style="font-size:12px;font-weight:500;color:var(--text-muted);"> · {{ $filterCategory }}</span>
                        @endif
                    </div>
                    <div class="report-period-label">
                        Showing {{ $rows->count() }} {{ $filterCategory !== 'all' ? 'result' : 'categor' }}{{ $rows->count() == 1 ? ($filterCategory !== 'all' ? '' : 'y') : ($filterCategory !== 'all' ? 's' : 'ies') }}
                        · {{ $totals['total'] }} total complaints
                    </div>
                </div>
                <button class="btn-excel" onclick="exportExcel()">
                    <i class="fa-solid fa-file-excel"></i> Export to Excel
                </button>
            </div>
            <table id="reportTable">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>New / Pending</th>
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
                        <td colspan="5" style="text-align:center;color:var(--text-muted);padding:32px;">
                            No data for this period{{ $filterCategory !== 'all' ? ' and category' : '' }}.
                        </td>
                    </tr>
                    @endforelse
                    @if($rows->count() > 0)
                    <tr>
                        <td><strong>TOTAL</strong></td>
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

    // Sync the hidden type field when date range is filled
    document.querySelectorAll('input[name="date_from"], input[name="date_to"]').forEach(function(el) {
        el.addEventListener('change', function() {
            var from = document.querySelector('input[name="date_from"]').value;
            var to   = document.querySelector('input[name="date_to"]').value;
            if (from && to) {
                document.querySelector('input[name="type"]').value = 'custom';
            }
        });
    });

    function exportExcel() {
        var table = document.getElementById('reportTable');
        var rows  = Array.from(table.querySelectorAll('tr'));
        var tsv   = rows.map(function(row) {
            return Array.from(row.querySelectorAll('th, td'))
                .map(function(cell) { return '"' + cell.innerText.replace(/"/g, '""') + '"'; })
                .join('\t');
        }).join('\n');
        var typeLabel = '{{ $type === "custom" ? "Custom Range" : ucfirst($type) }}';
        var header = '"MuniciReport – ' + typeLabel + ' Complaint Report"\t\t\t\t\n"Period: {{ $label }}"\t\t\t\t\n"Category Filter: {{ $filterCategory !== "all" ? $filterCategory : "All Categories" }}"\t\t\t\t\n"Generated: ' + new Date().toLocaleString('en-PH') + '"\t\t\t\t\n\n';
        var blob = new Blob(['\uFEFF' + header + tsv], { type: 'application/vnd.ms-excel;charset=utf-8;' });
        var a    = document.createEleme    nt('a');
        a.href   = URL.createObjectURL(blob);
        a.download = 'MuniciReport-{{ $type }}-{{ now()->format("Y-m-d") }}.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
</body>
</html>