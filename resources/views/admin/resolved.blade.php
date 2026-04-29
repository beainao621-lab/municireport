{{-- resources/views/admin/resolved.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resolved Complaints – MuniciReport Admin</title>
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
            --bg-page:    #EFF3FF;
            --bg-surface: #ffffff;
            --bg-raised:  #F8FAFF;
            --bg-input:   #EFF3FF;
            --text-primary: #0a2a4a;
            --text-muted:   #4a6fa5;
            --border: #C6DBEF;
            --color-orange:    #D97706;
            --color-blue:      #1D6FAB;
            --color-green:     #047857;
            --color-green-bg:  #D1FAE5;
            --color-orange-bg: #FEF3C7;
            --color-blue-bg:   #EFF3FF;
            --color-green-border: #6EE7B7;
            --color-green-banner: #D1FAE5;
            --color-green-banner-text: #065F46;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-page:    #0d1b2a; --bg-surface: #0f2035;
                --bg-raised:  #162840; --bg-input:   #1a3050;
                --text-primary: #e2e8f0; --text-muted: #7fb3d3;
                --border: #1e3a5f;
                --color-orange: #F59E0B; --color-blue: #60A5FA;
                --color-green: #34D399;
                --color-green-bg:  rgba(6,78,59,0.45);
                --color-orange-bg: rgba(120,53,15,0.45);
                --color-blue-bg:   rgba(30,58,138,0.35);
                --color-green-border: #065F46;
                --color-green-banner: rgba(6,78,59,0.35);
                --color-green-banner-text: #34D399;
            }
        }

        html, body { height: 100%; overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-page); color: var(--text-primary); display: flex; min-height: 100vh; }

        .sidebar { width: var(--sidebar-w); background: #08519C; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform .28s cubic-bezier(.4,0,.2,1); }
        .brand { display: flex; align-items: center; gap: 11px; padding: 22px 20px 18px; border-bottom: 1.5px solid rgba(255,255,255,0.15); }
        .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg,#3182BD,#9ECAE1); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; flex-shrink: 0; }
        .brand-name { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: -0.2px; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }
        .nav-section { padding: 16px 12px 8px; flex: 1; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13.5px; font-weight: 500; color: #C6DBEF; text-decoration: none; border-radius: 10px; border-left: 3px solid transparent; transition: all .15s; margin-bottom: 3px; }
        .nav-item:hover  { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #9ECAE1; font-weight: 600; }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        .main { margin-left: var(--sidebar-w); width: calc(100% - var(--sidebar-w)); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; padding: 28px 32px; }

        .page-title { font-size: 26px; font-weight: 700; letter-spacing: -0.4px; color: var(--text-primary); margin-bottom: 4px; }
        .page-sub   { font-size: 14px; color: var(--text-muted); margin-bottom: 24px; }

        .resolved-banner { background: var(--color-green-banner); border: 1.5px solid var(--color-green-border); border-radius: 12px; padding: 12px 18px; display: flex; align-items: center; gap: 10px; margin-bottom: 22px; }
        .resolved-banner i    { color: var(--color-green); font-size: 18px; }
        .resolved-banner span { font-size: 14px; color: var(--color-green-banner-text); font-weight: 600; }

        .stats-strip { display: flex; gap: 14px; margin-bottom: 24px; flex-wrap: wrap; }
        .stat-pill { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 12px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; flex: 1; min-width: 140px; }
        .stat-icon { width: 36px; height: 36px; border-radius: 9px; background: var(--color-green-bg); color: var(--color-green); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .stat-num { font-size: 22px; font-weight: 700; color: var(--text-primary); line-height: 1; }
        .stat-lbl { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .search-wrap { position: relative; flex: 1; min-width: 180px; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--brand-pale); font-size: 13px; }
        .search-input { width: 100%; padding: 10px 12px 10px 36px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; font-family: 'Inter', sans-serif; background: var(--bg-surface); color: var(--text-primary); outline: none; }
        .search-input:focus { border-color: var(--brand-mid); box-shadow: 0 0 0 3px rgba(49,130,189,0.15); }
        .search-input::placeholder { color: var(--text-muted); }
        .filter-btn { padding: 10px 20px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; white-space: nowrap; }

        .table-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 650px; }
        thead th { padding: 13px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); background: var(--bg-raised); border-bottom: 1.5px solid var(--border); white-space: nowrap; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--bg-raised); }
        tbody td { padding: 13px 16px; font-size: 13px; color: var(--text-primary); vertical-align: middle; }

        .ref { font-size: 12px; font-weight: 700; color: var(--brand-mid); }
        .badge { font-size: 11px; font-weight: 600; border-radius: 999px; padding: 4px 10px; white-space: nowrap; display: inline-block; }
        .badge-resolved { background: var(--color-green-bg); color: var(--color-green); }

        /* Anonymous badge */
        .badge-anon { font-size: 11px; font-weight: 600; border-radius: 999px; padding: 3px 9px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px; }
        @media (prefers-color-scheme: dark) {
            .badge-anon { background: rgba(255,255,255,0.07); color: #94a3b8; border-color: rgba(255,255,255,0.1); }
        }

        .date-cell    { font-size: 12px; color: var(--text-muted); white-space: nowrap; }
        .officer-cell { font-size: 12.5px; color: var(--text-muted); font-style: italic; }

        .btn-sm { padding: 6px 12px; border: 1.5px solid var(--border); border-radius: 7px; background: var(--bg-surface); color: var(--text-muted); font-size: 12px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all .15s; white-space: nowrap; }
        .btn-sm:hover { background: var(--bg-raised); border-color: var(--brand-teal); color: var(--brand-mid); }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.45); z-index: 400; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: var(--bg-surface); border-radius: 18px; padding: 28px; width: 100%; max-width: 560px; max-height: 88vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.35); overflow-y: auto; border: 1.5px solid var(--border); }
        .modal-box h3 { font-size: 19px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
        .modal-ref  { font-size: 12px; font-weight: 700; color: var(--brand-mid); margin-bottom: 14px; }
        .modal-section { margin-bottom: 14px; }
        .modal-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px; }
        .modal-value { font-size: 13.5px; color: var(--text-primary); line-height: 1.65; background: var(--bg-input); border-radius: 9px; padding: 10px 13px; }
        .modal-divider { border: none; border-top: 1.5px solid var(--border); margin: 16px 0; }
        .photo-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 6px; }
        .photo-grid img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 8px; border: 1.5px solid var(--border); cursor: pointer; }
        .progress-entry { background: var(--bg-raised); border: 1.5px solid var(--border); border-radius: 10px; padding: 12px 14px; margin-bottom: 8px; }
        .progress-date { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 5px; }
        .progress-note { font-size: 13px; color: var(--text-primary); line-height: 1.6; margin-bottom: 6px; }
        .progress-photos { display: grid; grid-template-columns: repeat(4,1fr); gap: 5px; }
        .progress-photos img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 6px; border: 1.5px solid var(--border); cursor: pointer; }
        .modal-close-btn { padding: 10px 22px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; align-self: flex-end; margin-top: 8px; }
        .modal-close-btn:hover { background: var(--bg-raised); }

        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 900; align-items: center; justify-content: center; }
        .lightbox.show { display: flex; }
        .lightbox img { max-width: 88vw; max-height: 85vh; border-radius: 10px; object-fit: contain; }
        .lightbox-close { position: fixed; top: 16px; right: 18px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 24px; cursor: pointer; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; }
        .lightbox-nav { position: fixed; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.15); border: none; color: white; font-size: 20px; cursor: pointer; border-radius: 50%; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; }
        .lightbox-prev { left: 14px; } .lightbox-next { right: 14px; }
        .lightbox-counter { position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,0.8); font-size: 12px; background: rgba(0,0,0,0.4); padding: 4px 12px; border-radius: 999px; }

        @media (max-width: 900px) { .content { padding: 20px 16px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; width: 100%; }
            .content { padding: 16px; }
            .stats-strip { flex-direction: column; }
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
        <a href="{{ route('admin.resolved') }}" class="nav-item active"><i class="fa-solid fa-circle-check"></i> Resolved</a>
        <a href="{{ route('admin.citizens') }}" class="nav-item"><i class="fa-solid fa-users"></i> Citizens</a>
        <a href="{{ route('admin.reports') }}" class="nav-item"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    </div>
</aside>

<div class="main">
    <x-topbar :role="'admin'" />

    <div class="content">
        <h1 class="page-title">Resolved Complaints</h1>
        <p class="page-sub">All complaints that have been successfully resolved.</p>

        <div class="resolved-banner">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ $complaints->total() }} complaint{{ $complaints->total() !== 1 ? 's' : '' }} resolved total</span>
        </div>

        <div class="stats-strip">
            <div class="stat-pill">
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div><div class="stat-num">{{ $complaints->total() }}</div><div class="stat-lbl">Total Resolved</div></div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon" style="background:var(--color-blue-bg);color:var(--color-blue);"><i class="fa-solid fa-calendar-week"></i></div>
                <div><div class="stat-num">{{ $thisWeek }}</div><div class="stat-lbl">Resolved This Week</div></div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon" style="background:var(--color-orange-bg);color:var(--color-orange);"><i class="fa-solid fa-calendar"></i></div>
                <div><div class="stat-num">{{ $thisMonth }}</div><div class="stat-lbl">Resolved This Month</div></div>
            </div>
        </div>

        @foreach($complaints as $c)
            <div class="complaint-data" style="display:none;"
                data-id="{{ $c->id }}"
                data-ref="{{ $c->reference_number }}"
                data-citizen="{{ $c->getDisplayName() }}"
                data-anonymous="{{ $c->is_anonymous ? '1' : '0' }}"
                data-category="{{ $c->category }}"
                data-location="{{ $c->location }}"
                data-desc="{{ $c->description }}"
                data-officer="{{ $c->assigned_officer ?? '—' }}"
                data-remarks="{{ $c->remarks ?? '' }}"
                data-filed="{{ $c->created_at->format('F j, Y') }}"
                data-resolved="{{ $c->updated_at->format('F j, Y') }}"
                data-photos="{{ json_encode(collect($c->photos ?? [])->map(fn($p) => asset('storage/' . $p))->values()) }}"
                data-updates="{{ json_encode(collect($c->progress_updates ?? [])->map(function($u) {
                    $u['photos'] = collect($u['photos'] ?? [])->map(fn($p) => asset('storage/' . $p))->values()->toArray();
                    return $u;
                })->values()) }}">
            </div>
        @endforeach

        <form method="GET" action="{{ route('admin.resolved') }}">
            <div class="filter-bar">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="search-input" placeholder="Search resolved complaints…" value="{{ request('search') }}">
                </div>
                <button type="submit" class="filter-btn"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </div>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Citizen</th>
                        <th>Category</th>
                        <th>Officer</th>
                        <th>Filed</th>
                        <th>Resolved On</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $c)
                    <tr>
                        <td><span class="ref">{{ $c->reference_number }}</span></td>
                        <td>
                            @if($c->is_anonymous)
                                <span class="badge-anon"><i class="fa-solid fa-user-secret"></i> Anonymous</span>
                            @else
                                <span style="font-weight:600;">{{ $c->getDisplayName() }}</span>
                            @endif
                        </td>
                        <td>{{ $c->category }}</td>
                        <td class="officer-cell">{{ $c->assigned_officer ?? '—' }}</td>
                        <td class="date-cell">{{ $c->created_at->format('M j, Y') }}</td>
                        <td class="date-cell">{{ $c->updated_at->format('M j, Y') }}</td>
                        <td><span class="badge badge-resolved"><i class="fa-solid fa-check" style="font-size:10px;margin-right:4px;"></i>Resolved</span></td>
                        <td>
                            <button class="btn-sm btn-view-detail" data-id="{{ $c->id }}">
                                <i class="fa-solid fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:48px;">
                        <i class="fa-solid fa-inbox" style="font-size:28px;opacity:.3;display:block;margin-bottom:10px;"></i>
                        No resolved complaints yet.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($complaints->hasPages())
            <div style="display:flex;justify-content:center;padding:18px;">{{ $complaints->links() }}</div>
            @endif
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-box">
        <h3 id="dm-citizen"></h3>
        <div class="modal-ref" id="dm-ref"></div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div class="modal-section">
                <div class="modal-label">Category</div>
                <div class="modal-value" id="dm-category"></div>
            </div>
            <div class="modal-section">
                <div class="modal-label">Location</div>
                <div class="modal-value" id="dm-location"></div>
            </div>
        </div>

        <div class="modal-section" style="margin-top:10px;">
            <div class="modal-label">Description</div>
            <div class="modal-value" id="dm-desc"></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px;">
            <div class="modal-section">
                <div class="modal-label">Filed On</div>
                <div class="modal-value" id="dm-filed"></div>
            </div>
            <div class="modal-section">
                <div class="modal-label">Resolved On</div>
                <div class="modal-value" id="dm-resolved"></div>
            </div>
        </div>

        <div class="modal-section" style="margin-top:10px;">
            <div class="modal-label">Assigned Officer</div>
            <div class="modal-value" id="dm-officer"></div>
        </div>

        <div class="modal-section" id="dm-remarks-wrap" style="margin-top:10px;display:none;">
            <div class="modal-label">Remarks</div>
            <div class="modal-value" id="dm-remarks"></div>
        </div>

        <div id="dm-photos-wrap" style="display:none;margin-top:10px;">
            <div class="modal-label">Attached Photos</div>
            <div class="photo-grid" id="dm-photos"></div>
        </div>

        <div id="dm-progress-wrap" style="display:none;margin-top:14px;">
            <hr class="modal-divider">
            <div class="modal-label" style="margin-bottom:10px;"><i class="fa-solid fa-clock-rotate-left" style="margin-right:5px;color:var(--brand-mid);"></i>Progress Updates</div>
            <div id="dm-progress-list"></div>
        </div>

        <button class="modal-close-btn" onclick="document.getElementById('detailModal').classList.remove('show')">Close</button>
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" onclick="closeLightbox()">×</button>
    <button class="lightbox-nav lightbox-prev" onclick="lightboxNav(-1)"><i class="fa-solid fa-chevron-left"></i></button>
    <img id="lightbox-img" src="" alt="">
    <button class="lightbox-nav lightbox-next" onclick="lightboxNav(1)"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="lightbox-counter" id="lightbox-counter">1 / 1</div>
</div>

<script>
    var lbPhotos = [], lbIndex = 0;
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    function getComplaintData(id) {
        var el = document.querySelector('.complaint-data[data-id="' + id + '"]');
        if (!el) return null;
        var photos = [], updates = [];
        try { photos  = JSON.parse(el.dataset.photos  || '[]'); } catch(e) {}
        try { updates = JSON.parse(el.dataset.updates || '[]'); } catch(e) {}
        return { id: el.dataset.id, ref: el.dataset.ref, citizen: el.dataset.citizen,
            anonymous: el.dataset.anonymous === '1',
            category: el.dataset.category, location: el.dataset.location, desc: el.dataset.desc,
            officer: el.dataset.officer, remarks: el.dataset.remarks, filed: el.dataset.filed,
            resolved: el.dataset.resolved, photos: photos, updates: updates };
    }

    function openLightbox(photos, index) { lbPhotos = photos; lbIndex = index; renderLightbox(); document.getElementById('lightbox').classList.add('show'); }
    function renderLightbox() {
        document.getElementById('lightbox-img').src = lbPhotos[lbIndex];
        document.getElementById('lightbox-counter').textContent = (lbIndex + 1) + ' / ' + lbPhotos.length;
        document.querySelector('.lightbox-prev').style.display = lbPhotos.length > 1 ? 'flex' : 'none';
        document.querySelector('.lightbox-next').style.display = lbPhotos.length > 1 ? 'flex' : 'none';
    }
    function lightboxNav(dir) { lbIndex = (lbIndex + dir + lbPhotos.length) % lbPhotos.length; renderLightbox(); }
    function closeLightbox() { document.getElementById('lightbox').classList.remove('show'); }
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('show')) return;
        if (e.key === 'ArrowLeft') lightboxNav(-1);
        if (e.key === 'ArrowRight') lightboxNav(1);
        if (e.key === 'Escape') closeLightbox();
    });

    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-view-detail');
        if (btn) {
            var d = getComplaintData(btn.dataset.id);
            if (!d) return;

            // Show "Anonymous" in the modal title if needed
            var citizenEl = document.getElementById('dm-citizen');
            if (d.anonymous) {
                citizenEl.innerHTML = '<span style="display:inline-flex;align-items:center;gap:6px;font-size:15px;color:#64748b;"><i class="fa-solid fa-user-secret"></i> Anonymous</span>';
            } else {
                citizenEl.textContent = d.citizen;
            }

            document.getElementById('dm-ref').textContent      = d.ref;
            document.getElementById('dm-category').textContent = d.category;
            document.getElementById('dm-location').textContent = d.location;
            document.getElementById('dm-desc').textContent     = d.desc;
            document.getElementById('dm-filed').textContent    = d.filed;
            document.getElementById('dm-resolved').textContent = d.resolved;
            document.getElementById('dm-officer').textContent  = d.officer;

            var rWrap = document.getElementById('dm-remarks-wrap');
            if (d.remarks) { document.getElementById('dm-remarks').textContent = d.remarks; rWrap.style.display = 'block'; }
            else rWrap.style.display = 'none';

            var pWrap = document.getElementById('dm-photos-wrap');
            var pGrid = document.getElementById('dm-photos');
            pGrid.innerHTML = '';
            if (d.photos && d.photos.length > 0) {
                d.photos.forEach(function(url, idx) {
                    var img = document.createElement('img');
                    img.src = url;
                    img.onclick = function() { openLightbox(d.photos, idx); };
                    pGrid.appendChild(img);
                });
                pWrap.style.display = 'block';
            } else pWrap.style.display = 'none';

            var updWrap = document.getElementById('dm-progress-wrap');
            var updList = document.getElementById('dm-progress-list');
            updList.innerHTML = '';
            if (d.updates && d.updates.length > 0) {
                d.updates.forEach(function(upd) {
                    var div = document.createElement('div');
                    div.className = 'progress-entry';
                    var date = new Date(upd.created_at);
                    var dateStr = date.toLocaleDateString('en-PH', {month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'});
                    var html = '<div class="progress-date"><i class="fa-solid fa-calendar-day"></i> ' + dateStr + '</div>';
                    if (upd.note) html += '<div class="progress-note">' + upd.note + '</div>';
                    if (upd.photos && upd.photos.length > 0) {
                        html += '<div class="progress-photos">';
                        upd.photos.forEach(function(url, idx) {
                            html += '<img src="' + url + '" onclick="openLightbox(' + JSON.stringify(upd.photos) + ',' + idx + ')">';
                        });
                        html += '</div>';
                    }
                    div.innerHTML = html;
                    updList.appendChild(div);
                });
                updWrap.style.display = 'block';
            } else updWrap.style.display = 'none';

            document.getElementById('detailModal').classList.add('show');
        }
        if (e.target === document.getElementById('detailModal')) document.getElementById('detailModal').classList.remove('show');
    });
</script>
</body>
</html>