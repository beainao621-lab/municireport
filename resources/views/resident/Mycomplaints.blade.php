{{-- resources/views/resident/mycomplaints.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Complaints – MuniciReport</title>
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
            --color-red:    #B91C1C;
            --color-green-bg:  #D1FAE5;
            --color-orange-bg: #FEF3C7;
            --color-blue-bg:   #EFF3FF;
            --color-red-bg:    #FEE2E2;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-page:    #0d1b2a; --bg-surface: #0f2035;
                --bg-raised:  #162840; --bg-input:   #1a3050;
                --text-primary: #e2e8f0; --text-muted: #7fb3d3;
                --border: #1e3a5f;
                --color-orange: #F59E0B; --color-blue: #60A5FA;
                --color-green:  #34D399; --color-red:  #F87171;
                --color-green-bg:  rgba(6,78,59,0.45);
                --color-orange-bg: rgba(120,53,15,0.45);
                --color-blue-bg:   rgba(30,58,138,0.35);
                --color-red-bg:    rgba(127,29,29,0.45);
            }
        }

        html, body { height: 100%; overflow-x: hidden; }
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
        .nav-badge { background: #ef4444; color: white; border-radius: 999px; font-size: 10px; font-weight: 700; padding: 2px 7px; margin-left: auto; flex-shrink: 0; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); width: calc(100% - var(--sidebar-w)); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; padding: 28px 32px; }

        .page-title { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; color: var(--text-primary); margin-bottom: 4px; }
        .page-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 24px; }

        /* ── Stats ── */
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 28px; }
        .stat-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; padding: 18px 20px; }
        .stat-label { font-size: 11px; font-weight: 600; letter-spacing: 0.6px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--text-primary); line-height: 1; }
        .stat-value.blue  { color: var(--color-blue); }
        .stat-value.green { color: var(--color-green); }

        /* ── Complaint Cards ── */
        .complaint-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; padding: 22px 24px; margin-bottom: 16px; }
        .complaint-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 6px; }
        .complaint-title { font-size: 15px; font-weight: 700; color: var(--text-primary); line-height: 1.4; }

        .badge { padding: 4px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700; white-space: nowrap; flex-shrink: 0; }
        .badge-pending   { background: var(--color-orange-bg); color: var(--color-orange); }
        .badge-progress  { background: var(--color-blue-bg);   color: var(--color-blue); }
        .badge-resolved  { background: var(--color-green-bg);  color: var(--color-green); }
        .badge-cancelled { background: var(--color-red-bg);    color: var(--color-red); }

        .complaint-meta  { font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
        .complaint-desc  { font-size: 13.5px; color: var(--text-primary); line-height: 1.65; margin-bottom: 12px; opacity: .85; }
        .complaint-dates { font-size: 12px; color: var(--text-muted); margin-bottom: 14px; display: flex; flex-wrap: wrap; gap: 4px 16px; }
        .divider { border: none; border-top: 1.5px solid var(--border); margin: 14px 0; }

        .cancel-box { background: var(--color-red-bg); border: 1.5px solid rgba(185,28,28,0.25); border-radius: 10px; padding: 12px 16px; margin-bottom: 12px; }
        .cancel-box-title { font-size: 12px; font-weight: 700; color: var(--color-red); margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
        .cancel-box-reason { font-size: 13px; color: var(--text-primary); line-height: 1.55; }

        /* ── Photos toggle ── */
        .photos-toggle-btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-muted); font-size: 12.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all .15s; margin-bottom: 10px; }
        .photos-toggle-btn:hover { background: var(--bg-raised); border-color: var(--brand-teal); color: var(--brand-mid); }
        .photos-collapse { display: none; }
        .photos-collapse.open { display: block; }
        .photos-grid-inline { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 8px; }
        .photos-grid-inline img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 8px; border: 1.5px solid var(--border); cursor: pointer; transition: opacity .15s; }
        .photos-grid-inline img:hover { opacity: .85; }

        /* ── Timeline ── */
        .timeline { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
        .tl-item { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-muted); }
        .tl-dot { width: 9px; height: 9px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
        .tl-dot.done    { background: var(--brand-mid); }
        .tl-dot.pending { background: var(--border); }
        .tl-dot.red     { background: var(--color-red); }

        /* ── Action buttons ── */
        .card-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .view-update-btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid)); color: white; border: none; border-radius: 9px; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; box-shadow: 0 4px 14px rgba(8,81,156,0.2); transition: opacity .15s; }
        .view-update-btn:hover { opacity: .88; }
        .update-dot { width: 8px; height: 8px; background: var(--color-green); border-radius: 50%; display: inline-block; }
        .delete-complaint-btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; background: var(--color-red-bg); border: 1.5px solid rgba(185,28,28,0.3); color: var(--color-red); border-radius: 9px; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all .15s; }
        .delete-complaint-btn:hover { background: #fecaca; border-color: var(--color-red); }

        /* ── Updates modal ── */
        .upd-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 600; align-items: center; justify-content: center; padding: 16px; }
        .upd-modal-overlay.show { display: flex; }
        .upd-modal-box { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 18px; padding: 28px; width: 100%; max-width: 580px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 24px 70px rgba(8,81,156,0.28); overflow: hidden; }
        .upd-modal-box h3 { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; letter-spacing: -0.3px; }
        .upd-modal-ref { font-size: 12px; color: var(--text-muted); margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1.5px solid var(--border); }
        .upd-modal-body { overflow-y: auto; flex: 1; }

        .upd-entry { background: var(--bg-raised); border: 1.5px solid var(--border); border-radius: 12px; padding: 14px 16px; margin-bottom: 14px; }
        .upd-entry:last-child { margin-bottom: 0; }
        .upd-entry-date { font-size: 11px; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .upd-entry-note { font-size: 13.5px; color: var(--text-primary); line-height: 1.65; margin-bottom: 10px; background: var(--bg-surface); border-radius: 8px; padding: 10px 12px; border: 1px solid var(--border); }
        .upd-entry-photos-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
        .upd-entry-photos-grid img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 7px; border: 1.5px solid var(--border); cursor: pointer; transition: opacity .15s; }
        .upd-entry-photos-grid img:hover { opacity: .85; }
        .upd-entry-photos-label { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 6px; }

        /* ── Comment section inside each update ── */
        .comment-section { border-top: 1.5px solid var(--border); padding-top: 12px; margin-top: 4px; }
        .comment-section-title { font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: var(--brand-mid); margin-bottom: 8px; display: flex; align-items: center; gap: 5px; }
        .comment-list { margin-bottom: 10px; }
        .comment-item { background: var(--bg-surface); border: 1px solid var(--border); border-radius: 8px; padding: 8px 10px; margin-bottom: 6px; font-size: 12.5px; }
        .comment-item:last-child { margin-bottom: 0; }
        .comment-meta { font-size: 10.5px; color: var(--text-muted); margin-bottom: 3px; font-weight: 600; }
        .comment-body { color: var(--text-primary); line-height: 1.5; }
        .comment-empty { font-size: 12px; color: var(--text-muted); font-style: italic; }

        /* Comment form */
        .comment-form { display: flex; gap: 8px; margin-top: 8px; }
        .comment-input { flex: 1; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; background: var(--bg-input); color: var(--text-primary); outline: none; resize: none; min-height: 38px; }
        .comment-input:focus { border-color: var(--brand-mid); background: var(--bg-surface); }
        .comment-submit-btn { padding: 9px 14px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 8px; font-size: 12.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; white-space: nowrap; }
        .comment-submit-btn:disabled { opacity: .6; cursor: not-allowed; }

        .upd-close-btn { margin-top: 16px; padding: 11px; width: 100%; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; flex-shrink: 0; transition: background .15s; }
        .upd-close-btn:hover { background: var(--bg-raised); }

        /* ── Delete confirm modal ── */
        .delete-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.5); z-index: 700; align-items: center; justify-content: center; padding: 16px; }
        .delete-overlay.show { display: flex; }
        .delete-box { background: var(--bg-surface); border-radius: 18px; padding: 32px; width: 100%; max-width: 380px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border: 1.5px solid var(--border); }
        .del-icon  { width: 60px; height: 60px; border-radius: 50%; background: var(--color-red-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; font-size: 22px; color: var(--color-red); }
        .del-title { font-size: 19px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
        .del-msg   { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
        .del-btns  { display: flex; gap: 10px; }
        .del-cancel  { flex: 1; padding: 12px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; }
        .del-confirm { flex: 1; padding: 12px; border: none; border-radius: 9px; background: linear-gradient(135deg,#ef4444,#dc2626); color: white; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; }

        /* ── Lightbox ── */
        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 900; align-items: center; justify-content: center; }
        .lightbox.show { display: flex; }
        .lightbox img { max-width: 88vw; max-height: 85vh; border-radius: 10px; object-fit: contain; }
        .lightbox-close { position: fixed; top: 16px; right: 18px; background: rgba(255,255,255,0.15); border: none; color: white; font-size: 24px; cursor: pointer; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; }
        .lightbox-nav { position: fixed; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.15); border: none; color: white; font-size: 20px; cursor: pointer; border-radius: 50%; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; }
        .lightbox-prev { left: 14px; } .lightbox-next { right: 14px; }
        .lightbox-counter { position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,0.8); font-size: 12px; background: rgba(0,0,0,0.4); padding: 4px 12px; border-radius: 999px; }

        /* ── Empty state ── */
        .empty-state { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; padding: 50px 20px; text-align: center; }
        .empty-icon { font-size: 36px; margin-bottom: 10px; opacity: 0.4; color: var(--text-muted); }
        .empty-state p { font-size: 14.5px; color: var(--text-muted); margin-bottom: 14px; }
        .btn-file { display: inline-block; padding: 11px 24px; background: linear-gradient(135deg, var(--brand-deep), var(--brand-teal)); color: white; border-radius: 9px; font-size: 13.5px; font-weight: 600; text-decoration: none; }

        .alert-success { background: var(--color-blue-bg); border: 1.5px solid var(--border); color: var(--brand-mid); border-radius: 10px; padding: 12px 16px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

        /* ══════════════════════════════════════════════
           PAGINATION — same compact style as complaints
        ══════════════════════════════════════════════ */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 18px 0;
        }

        /* Hide the "Showing X to Y of Z" text inside the nav */
        .pagination-wrap nav > div:first-child { display: none !important; }

        /* Container holding page links */
        .pagination-wrap nav > div:last-child,
        .pagination-wrap nav > div { display: flex !important; align-items: center; gap: 4px; }

        /* Inner span wrapper */
        .pagination-wrap nav span.relative { display: flex; align-items: center; gap: 4px; }

        /* All page link anchors and disabled spans */
        .pagination-wrap nav a,
        .pagination-wrap nav span[aria-current="page"] > span,
        .pagination-wrap nav span[aria-disabled="true"] > span {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            color: var(--text-muted);
            background: var(--bg-surface);
            transition: all .15s;
            line-height: 1;
        }

        /* Hover state for clickable links */
        .pagination-wrap nav a:hover {
            background: var(--bg-raised);
            border-color: var(--brand-teal);
            color: var(--brand-mid);
        }

        /* Active / current page */
        .pagination-wrap nav span[aria-current="page"] > span {
            background: linear-gradient(135deg, #08519C, #3182BD) !important;
            color: #fff !important;
            border-color: transparent !important;
            font-weight: 700;
        }

        /* Disabled prev/next */
        .pagination-wrap nav span[aria-disabled="true"] > span {
            opacity: .38;
            cursor: not-allowed;
        }

        /* Keep SVG arrows small */
        .pagination-wrap nav svg {
            width: 14px !important;
            height: 14px !important;
            display: block;
        }

        /* Hide mobile simple prev/next strip */
        .pagination-wrap nav .flex.justify-between { display: none !important; }

        @media (max-width: 900px) { .content { padding: 20px 16px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; width: 100%; }
            .content { padding: 16px; }
            .stats { gap: 8px; } .stat-card { padding: 14px; } .stat-value { font-size: 22px; }
            .complaint-card { padding: 16px; }
            .complaint-header { flex-direction: column; gap: 8px; }
            .photos-grid-inline { grid-template-columns: repeat(3,1fr); }
            .upd-entry-photos-grid { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width: 480px) {
            .stats { grid-template-columns: 1fr 1fr; }
            .photos-grid-inline { grid-template-columns: repeat(2,1fr); }
        }
    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-icon"><i class="fa-solid fa-landmark" style="font-size:18px;color:#fff;"></i></div>
        <div><div class="brand-name">MuniciReport</div><div class="brand-sub">Mayor's Office — Victoria</div></div>
    </div>
    <div class="nav-section">
        <a href="{{ route('resident.complaints.create') }}" class="nav-item">
            <i class="fa-solid fa-pen-to-square"></i> File a Complaint
        </a>
        <a href="{{ route('resident.complaints.index') }}" class="nav-item active">
            <i class="fa-solid fa-clipboard-list"></i> My Complaints
        </a>
        <a href="{{ route('resident.messages') }}" class="nav-item">
            <i class="fa-solid fa-comments"></i> Messages
            @php
                $residentMsgUnread = \App\Models\ComplaintMessage::where('sender_role', 'admin')
                    ->where('is_read', false)
                    ->whereHas('complaint', fn($q) => $q->where('user_id', Auth::id()))
                    ->count();
            @endphp
            @if($residentMsgUnread > 0)
                <span class="nav-badge">{{ $residentMsgUnread }}</span>
            @endif
        </a>
    </div>
</aside>

<div class="main">
    <x-topbar :role="$isAdmin ?? false ? 'admin' : 'resident'" />
    <div class="content">
        <h1 class="page-title">My Complaints</h1>
        <p class="page-sub">Track the status of your submitted complaints.</p>

        <div id="flash-area"></div>
        @if(session('success'))
            <div class="alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Submitted</div>
                <div class="stat-value">{{ $complaints->total() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">In Progress</div>
                <div class="stat-value blue">{{ $complaints->getCollection()->filter(fn($c) => str_contains(strtolower($c->status), 'progress'))->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Resolved</div>
                <div class="stat-value green">{{ $complaints->getCollection()->filter(fn($c) => strtolower($c->status) === 'resolved')->count() }}</div>
            </div>
        </div>

        @forelse($complaints as $complaint)
        @php
            $status = $complaint->status;
            $badgeClass = match(true) {
                str_contains(strtolower($status), 'progress') => 'badge-progress',
                strtolower($status) === 'resolved'            => 'badge-resolved',
                strtolower($status) === 'cancelled'           => 'badge-cancelled',
                default                                       => 'badge-pending',
            };
            $badgeLabel = match(true) {
                str_contains(strtolower($status), 'progress') => 'In Progress',
                strtolower($status) === 'resolved'            => 'Resolved',
                strtolower($status) === 'cancelled'           => 'Cancelled',
                default                                       => 'Pending',
            };
            $submittedPhotos = collect($complaint->photos ?? []);
            $progressUpdates = collect($complaint->progress_updates ?? []);
            $hasUpdates = $progressUpdates->isNotEmpty();
            $isCancelled = strtolower($status) === 'cancelled';
        @endphp
        <div class="complaint-card" id="complaint-card-{{ $complaint->id }}">
            <div class="complaint-header">
                <div class="complaint-title">{{ Str::limit($complaint->description, 60) }}</div>
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
            <div class="complaint-meta">
                <i class="fa-solid fa-hashtag"></i> {{ $complaint->reference_number }}
                &nbsp;·&nbsp;<i class="fa-solid fa-tag"></i> {{ $complaint->category }}
                &nbsp;·&nbsp;<i class="fa-solid fa-location-dot"></i> {{ $complaint->location }}
            </div>
            <div class="complaint-desc">{{ $complaint->description }}</div>
            <div class="complaint-dates">
                <span><i class="fa-solid fa-calendar-plus"></i> Filed: {{ $complaint->created_at->format('M d, Y') }}</span>
                <span><i class="fa-solid fa-pen"></i> Updated: {{ $complaint->updated_at->format('M d, Y') }}</span>
            </div>

            @if($isCancelled && $complaint->cancellation_reason)
            <div class="cancel-box">
                <div class="cancel-box-title"><i class="fa-solid fa-circle-xmark"></i> Cancellation Reason</div>
                <div class="cancel-box-reason">{{ $complaint->cancellation_reason }}</div>
            </div>
            @endif

            @if($submittedPhotos->isNotEmpty())
            <button class="photos-toggle-btn" onclick="togglePhotos(this)">
                <i class="fa-solid fa-images"></i> Your Photos ({{ $submittedPhotos->count() }})
                <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i>
            </button>
            <div class="photos-collapse"
                 data-photos="{{ json_encode($submittedPhotos->map(fn($p) => asset('storage/'.$p))->values()) }}">
                <div class="photos-grid-inline">
                    @foreach($submittedPhotos as $idx => $photo)
                    <img src="{{ asset('storage/' . $photo) }}" alt="photo" data-index="{{ $idx }}" onclick="openLightboxFromGrid(this)">
                    @endforeach
                </div>
            </div>
            @endif

            <hr class="divider">

            <div class="timeline">
                <div class="tl-item">
                    <div class="tl-dot done"></div>
                    <span><strong>{{ $complaint->created_at->format('M d') }}</strong> — Complaint submitted</span>
                </div>
                @if(!str_contains(strtolower($status), 'pending'))
                <div class="tl-item">
                    <div class="tl-dot {{ $isCancelled ? 'red' : 'done' }}"></div>
                    <span>
                        <strong>{{ $complaint->updated_at->format('M d') }}</strong>
                        — Status: <strong>{{ $badgeLabel }}</strong>
                        @if($complaint->assigned_officer) · Assigned to {{ $complaint->assigned_officer }} @endif
                    </span>
                </div>
                @endif
                @if($complaint->remarks)
                <div class="tl-item">
                    <div class="tl-dot done"></div>
                    <span><strong>Office Remarks:</strong> {{ $complaint->remarks }}</span>
                </div>
                @endif
                @if($hasUpdates)
                <div class="tl-item">
                    <div class="tl-dot done" style="background:var(--color-green);"></div>
                    <span><strong>{{ $progressUpdates->count() }} progress update{{ $progressUpdates->count() > 1 ? 's' : '' }}</strong> from the Mayor's Office</span>
                </div>
                @endif
            </div>

            <div class="card-actions">
                @if($hasUpdates)
                <button class="view-update-btn"
                    data-complaint-id="{{ $complaint->id }}"
                    data-updates="{{ json_encode($progressUpdates->map(function($u) {
                        $u['photos'] = collect($u['photos'] ?? [])->map(fn($p) => asset('storage/'.$p))->values()->toArray();
                        return $u;
                    })->values()) }}"
                    data-ref="{{ $complaint->reference_number }}"
                    onclick="openUpdatesModal(this)">
                    <span class="update-dot"></span>
                    <i class="fa-solid fa-circle-info"></i>
                    View Updates ({{ $progressUpdates->count() }})
                </button>
                @endif

                @if($isCancelled)
                <button class="delete-complaint-btn"
                    data-id="{{ $complaint->id }}"
                    data-ref="{{ $complaint->reference_number }}"
                    onclick="openDeleteConfirm(this)">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-clipboard"></i></div>
            <p>You haven't filed any complaints yet.</p>
            <a href="{{ route('resident.complaints.create') }}" class="btn-file">File a Complaint →</a>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($complaints->hasPages())
        <div class="pagination-wrap">{{ $complaints->links() }}</div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     PROGRESS UPDATES MODAL (with comment feature)
══════════════════════════════════════════════════════════ --}}
<div class="upd-modal-overlay" id="updatesModal" onclick="closeUpdatesModalBackdrop(event)">
    <div class="upd-modal-box">
        <h3>Updates from the Mayor's Office</h3>
        <div class="upd-modal-ref" id="upd-modal-ref"></div>
        <div class="upd-modal-body" id="upd-modal-body"></div>
        <button class="upd-close-btn" onclick="document.getElementById('updatesModal').classList.remove('show')">Close</button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════════════════════════ --}}
<div class="delete-overlay" id="deleteOverlay">
    <div class="delete-box">
        <div class="del-icon"><i class="fa-solid fa-trash"></i></div>
        <p class="del-title">Delete Complaint?</p>
        <p class="del-msg">Are you sure you want to permanently delete complaint <strong id="del-ref-label"></strong>? This cannot be undone.</p>
        <div class="del-btns">
            <button class="del-cancel" onclick="closeDeleteConfirm()">Cancel</button>
            <button class="del-confirm" id="del-confirm-btn"><i class="fa-solid fa-trash"></i> Yes, Delete</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     LIGHTBOX
══════════════════════════════════════════════════════════ --}}
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" onclick="closeLightbox()">×</button>
    <button class="lightbox-nav lightbox-prev" onclick="lightboxNav(-1)"><i class="fa-solid fa-chevron-left"></i></button>
    <img id="lightbox-img" src="" alt="">
    <button class="lightbox-nav lightbox-next" onclick="lightboxNav(1)"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="lightbox-counter" id="lightbox-counter">1 / 1</div>
</div>

<script>
    var lbPhotos = [], lbIndex = 0;
    var pendingDeleteId = null;
    var csrfToken       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var deleteBaseUrl   = '{{ url("resident/complaints") }}';
    var commentsBaseUrl = '{{ url("complaint-comments") }}';
    var currentComplaintId = null;

    function openSidebar()  { document.getElementById('sidebar').classList.add('open');  document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    function escHtml(str) {
        return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function togglePhotos(btn) {
        var collapse = btn.nextElementSibling;
        collapse.classList.toggle('open');
        var icon = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
        if (icon) {
            icon.className = collapse.classList.contains('open') ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down';
            icon.style.fontSize = '11px';
        }
    }

    function openLightboxFromGrid(img) {
        var grid = img.closest('.photos-collapse');
        var photos = JSON.parse(grid.dataset.photos || '[]');
        openLightbox(photos, parseInt(img.dataset.index));
    }

    function openLightbox(photos, index) { lbPhotos = photos; lbIndex = index; renderLightbox(); document.getElementById('lightbox').classList.add('show'); }
    function renderLightbox() {
        document.getElementById('lightbox-img').src = lbPhotos[lbIndex];
        document.getElementById('lightbox-counter').textContent = (lbIndex + 1) + ' / ' + lbPhotos.length;
        document.querySelector('.lightbox-prev').style.display = lbPhotos.length > 1 ? 'flex' : 'none';
        document.querySelector('.lightbox-next').style.display = lbPhotos.length > 1 ? 'flex' : 'none';
    }
    function lightboxNav(dir) { lbIndex = (lbIndex + dir + lbPhotos.length) % lbPhotos.length; renderLightbox(); }
    function closeLightbox()  { document.getElementById('lightbox').classList.remove('show'); }

    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('show')) return;
        if (e.key === 'ArrowLeft')  lightboxNav(-1);
        if (e.key === 'ArrowRight') lightboxNav(1);
        if (e.key === 'Escape')     closeLightbox();
    });
    document.getElementById('lightbox').addEventListener('click', function(e) { if (e.target === this) closeLightbox(); });

    /* ── Updates modal ─────────────────────────── */
    function openUpdatesModal(btn) {
        var ref       = btn.dataset.ref;
        currentComplaintId = btn.dataset.complaintId;
        var updates   = [];
        try { updates = JSON.parse(btn.dataset.updates || '[]'); } catch(e) {}

        document.getElementById('upd-modal-ref').textContent = ref;
        var body = document.getElementById('upd-modal-body');
        body.innerHTML = '';

        if (updates.length === 0) {
            body.innerHTML = '<p style="color:var(--text-muted);font-size:14px;text-align:center;padding:20px;">No updates yet.</p>';
        } else {
            updates.forEach(function(upd, i) {
                var div     = document.createElement('div');
                div.className = 'upd-entry';
                var date    = new Date(upd.created_at);
                var dateStr = date.toLocaleDateString('en-PH', {month:'long',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'});

                var html = '<div class="upd-entry-date"><i class="fa-solid fa-calendar-check"></i> Update ' + (i+1) + ' — ' + dateStr + '</div>';
                if (upd.note) html += '<div class="upd-entry-note">' + escHtml(upd.note) + '</div>';

                if (upd.photos && upd.photos.length > 0) {
                    html += '<div class="upd-entry-photos-label"><i class="fa-solid fa-images"></i> Photos (' + upd.photos.length + ')</div>';
                    html += '<div class="upd-entry-photos-grid">';
                    upd.photos.forEach(function(url, idx) {
                        html += '<img src="' + escHtml(url) + '" alt="photo" data-photos=\'' + JSON.stringify(upd.photos) + '\' data-idx="' + idx + '" class="upd-photo-click">';
                    });
                    html += '</div>';
                }

                html += '<div class="comment-section">'
                      + '<div class="comment-section-title"><i class="fa-solid fa-comment-dots"></i> Your Comments</div>'
                      + '<div class="comment-list" id="comment-list-' + i + '"><div class="comment-empty">Loading…</div></div>'
                      + '<div class="comment-form">'
                      + '<textarea class="comment-input" id="comment-input-' + i + '" placeholder="Write a comment on this update…" rows="1"></textarea>'
                      + '<button class="comment-submit-btn" onclick="submitComment(' + i + ')"><i class="fa-solid fa-paper-plane"></i> Send</button>'
                      + '</div>'
                      + '</div>';

                div.innerHTML = html;
                body.appendChild(div);

                div.querySelectorAll('.upd-photo-click').forEach(function(img) {
                    img.addEventListener('click', function() {
                        var photos = JSON.parse(this.dataset.photos);
                        openLightbox(photos, parseInt(this.dataset.idx));
                    });
                });

                loadComments(i);
            });
        }

        document.getElementById('updatesModal').classList.add('show');
    }

    function closeUpdatesModalBackdrop(e) {
        if (e.target === document.getElementById('updatesModal')) document.getElementById('updatesModal').classList.remove('show');
    }

    /* ── Load & render comments ──────────────────── */
    function loadComments(updateIndex) {
        if (!currentComplaintId) return;
        var listEl = document.getElementById('comment-list-' + updateIndex);
        if (!listEl) return;

        fetch(commentsBaseUrl + '/' + currentComplaintId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var comments = (data.comments || []).filter(function(c) { return c.update_index === updateIndex; });
            renderComments(listEl, comments);
        })
        .catch(function() {
            if (listEl) listEl.innerHTML = '<div class="comment-empty">Could not load comments.</div>';
        });
    }

    function renderComments(container, comments) {
        if (comments.length === 0) {
            container.innerHTML = '<div class="comment-empty">No comments yet. Be the first to comment!</div>';
            return;
        }
        container.innerHTML = '';
        comments.forEach(function(c) {
            var date = new Date(c.created_at).toLocaleString('en-PH', {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'});
            var div  = document.createElement('div');
            div.className = 'comment-item';
            div.innerHTML = '<div class="comment-meta"><i class="fa-solid fa-user"></i> ' + escHtml(c.user_name) + ' · ' + date + '</div>'
                          + '<div class="comment-body">' + escHtml(c.comment) + '</div>';
            container.appendChild(div);
        });
    }

    /* ── Submit comment ──────────────────────────── */
    function submitComment(updateIndex) {
        if (!currentComplaintId) return;
        var input   = document.getElementById('comment-input-' + updateIndex);
        var text    = input ? input.value.trim() : '';
        if (!text) { if (input) input.focus(); return; }

        var btn = input.nextElementSibling;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        fetch(commentsBaseUrl + '/' + currentComplaintId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ update_index: updateIndex, comment: text })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send';
            if (data.success) {
                input.value = '';
                loadComments(updateIndex);
            } else {
                alert('Error: ' + (data.message || 'Could not post comment.'));
            }
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send';
            alert('Network error: ' + err.message);
        });
    }

    /* ── Delete complaint ────────────────────────── */
    function openDeleteConfirm(btn) {
        pendingDeleteId = btn.dataset.id;
        document.getElementById('del-ref-label').textContent = btn.dataset.ref;
        document.getElementById('deleteOverlay').classList.add('show');
    }

    function closeDeleteConfirm() {
        document.getElementById('deleteOverlay').classList.remove('show');
        pendingDeleteId = null;
    }

    document.getElementById('del-confirm-btn').addEventListener('click', function() {
        if (!pendingDeleteId) return;
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';

        fetch(deleteBaseUrl + '/' + pendingDeleteId, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            closeDeleteConfirm();
            if (data.success) {
                var card = document.getElementById('complaint-card-' + pendingDeleteId);
                if (card) card.remove();
                document.getElementById('flash-area').innerHTML =
                    '<div class="alert-success"><i class="fa-solid fa-circle-check"></i> ' + data.message + '</div>';
                setTimeout(function() { window.location.reload(); }, 1200);
            } else {
                alert('Error: ' + (data.message || 'Could not delete.'));
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Yes, Delete';
        })
        .catch(function(err) {
            alert('Network error: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Yes, Delete';
            closeDeleteConfirm();
        });
    });

    document.getElementById('deleteOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteConfirm();
    });
</script>
</body>
</html>