{{-- resources/views/admin/citizens.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Citizens – MuniciReport Admin</title>
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
        .sidebar { width: var(--sidebar-w); background: #08519C; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform .28s cubic-bezier(.4,0,.2,1); }
        .brand { display: flex; align-items: center; gap: 11px; padding: 22px 20px 18px; border-bottom: 1.5px solid rgba(255,255,255,0.15); }
        .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg,#3182BD,#9ECAE1); border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.25); }
        .brand-name { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: -0.2px; }
        .brand-sub  { font-size: 11px; color: #9ECAE1; margin-top: 2px; }
        .nav-section { padding: 16px 12px 8px; flex: 1; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13.5px; font-weight: 500; color: #C6DBEF; text-decoration: none; border-radius: 10px; border-left: 3px solid transparent; transition: all .15s; margin-bottom: 3px; }
        .nav-item:hover  { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #9ECAE1; font-weight: 600; }
        .nav-item i      { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; padding: 36px 44px; }

        /* ── Page header — exact match sa dashboard ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }

        /* Exact copy ng .page-title sa dashboard */
        .page-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1;
        }

        /* Exact copy ng .page-sub sa dashboard */
        .page-sub {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        .btn-add {
            padding: 11px 22px;
            background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid));
            color: white; border: none; border-radius: 11px;
            font-size: 13.5px; font-weight: 700; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: opacity .15s;
        }
        .btn-add:hover { opacity: .88; }

        /* ── Search ── */
        .search-bar { margin-bottom: 22px; display: flex; gap: 12px; }
        .search-wrap { position: relative; flex: 1; max-width: 460px; }
        .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--brand-pale); font-size: 14px; }
        .search-input {
            width: 100%; padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--border); border-radius: 11px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            background: var(--bg-surface); color: var(--text-primary); outline: none;
        }
        .search-input:focus { border-color: var(--brand-mid); box-shadow: 0 0 0 3px rgba(49,130,189,0.12); }
        .search-input::placeholder { color: var(--text-muted); }
        .search-btn {
            padding: 11px 20px;
            background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid));
            color: white; border: none; border-radius: 11px;
            font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: opacity .15s;
        }
        .search-btn:hover { opacity: .88; }

        /* ── Table ── */
        .table-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 16px; overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 700px; }
        thead th {
            padding: 14px 18px; text-align: left;
            font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
            color: var(--text-muted); background: var(--bg-raised);
            border-bottom: 1.5px solid var(--border); white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--bg-raised); }
        tbody td { padding: 14px 18px; font-size: 13.5px; color: var(--text-primary); vertical-align: middle; }

        .citizen-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg,#08519C,#6BAED6);
            color: white; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;
        }
        .citizen-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .name-cell { display: flex; align-items: center; gap: 12px; }

        /* matches .activity-desc sa dashboard */
        .citizen-name  { font-size: 13px; font-weight: 600; color: var(--text-primary); }

        /* matches .activity-ref sa dashboard */
        .citizen-email { font-size: 12px; color: var(--text-muted); }

        /* matches .cat-name sa dashboard */
        .phone-cell    { font-size: 13px; font-weight: 500; color: var(--text-primary); }

        /* matches .stat-sub sa dashboard */
        .date-cell     { font-size: 12.5px; color: var(--text-muted); }

        .location-cell { font-size: 13px; font-weight: 500; color: var(--text-muted); }

        .complaints-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; font-size: 12.5px; font-weight: 700; }
        .c0 { background: var(--bg-raised);       color: var(--text-muted); }
        .c1 { background: var(--color-green-bg);  color: var(--color-green); }
        .c2 { background: var(--color-orange-bg); color: var(--color-orange); }
        .c3 { background: var(--color-blue-bg);   color: var(--color-blue); }
        .c4 { background: rgba(185,28,28,0.12);   color: #ef4444; }

        .btn-sm {
            padding: 6px 12px; border: 1.5px solid var(--border); border-radius: 7px;
            background: var(--bg-surface); color: var(--text-muted);
            font-size: 12px; font-weight: 600; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: all .15s;
        }
        .btn-sm:hover { background: var(--bg-raised); border-color: var(--brand-teal); color: var(--brand-mid); }

        /* ── Modals ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.35); z-index: 300; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.show { display: flex; }
        .modal { background: var(--bg-surface); border-radius: 20px; padding: 32px; width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(8,81,156,0.25); border: 1.5px solid var(--border); }
        .modal h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
        .modal p  { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; }
        .field { display: flex; flex-direction: column; margin-bottom: 16px; }
        .field label { font-size: 10.5px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 7px; }
        .modal input[type="text"],
        .modal input[type="email"],
        .modal input[type="password"] {
            width: 100%; padding: 12px 15px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            background: var(--bg-input); color: var(--text-primary); outline: none;
        }
        .modal input:focus { border-color: var(--brand-mid); box-shadow: 0 0 0 3px rgba(49,130,189,0.12); background: var(--bg-surface); }
        .modal input::placeholder { color: var(--text-muted); }
        .modal-btns { display: flex; gap: 10px; margin-top: 6px; }
        .btn-modal-cancel {
            flex: 1; padding: 13px; border: 1.5px solid var(--border); border-radius: 10px;
            background: var(--bg-surface); color: var(--text-muted);
            font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer;
            transition: background .15s;
        }
        .btn-modal-cancel:hover { background: var(--bg-raised); }
        .btn-modal-save {
            flex: 2; padding: 13px; border: none; border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid), var(--brand-teal));
            color: white; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif;
            cursor: pointer; box-shadow: 0 5px 18px rgba(8,81,156,0.28);
        }
        .btn-modal-save:disabled { opacity: .6; cursor: not-allowed; }
        .modal-alert { padding: 10px 14px; border-radius: 9px; font-size: 13px; margin-bottom: 14px; display: none; }
        .modal-alert.show    { display: block; }
        .modal-alert.success { background: var(--color-green-bg);  color: var(--color-green);  border: 1px solid rgba(52,211,153,0.3); }
        .modal-alert.error   { background: rgba(185,28,28,0.12);   color: #ef4444;             border: 1px solid rgba(239,68,68,0.3); }

        /* ── Alert banner — exact copy sa dashboard ── */
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

        @media (max-width: 1024px) { .content { padding: 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .content { padding: 16px; }
            .table-card { overflow-x: auto; }
        }
    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-icon">
            <i class="fa-solid fa-landmark" style="font-size:18px;color:#fff;"></i>
        </div>
        <div>
            <div class="brand-name">MuniciReport</div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </div>
    <div class="nav-section">
        <a href="{{ route('admin.dashboard') }}"  class="nav-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="{{ route('admin.complaints') }}"  class="nav-item"><i class="fa-solid fa-clipboard-list"></i> Complaints</a>
        <a href="{{ route('admin.resolved') }}"    class="nav-item"><i class="fa-solid fa-circle-check"></i> Resolved</a>
        <a href="{{ route('admin.citizens') }}"    class="nav-item active"><i class="fa-solid fa-users"></i> Citizens</a>
        <a href="{{ route('admin.reports') }}"     class="nav-item"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    </div>
</aside>

<div class="main">
    <x-topbar role="admin" />

    <div class="content">
        <div class="page-header">
            <div>
                {{-- Exact same classes as dashboard --}}
                <h1 class="page-title">Registered Citizens</h1>
                <p class="page-sub">Manage citizen accounts and their complaint history.</p>
            </div>
            <button class="btn-add" onclick="document.getElementById('addModal').classList.add('show')">
                <i class="fa-solid fa-plus"></i> Add citizen
            </button>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.citizens') }}">
            <div class="search-bar">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="search-input" placeholder="Search by name, email or phone…" value="{{ request('search') }}">
                </div>
                <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </div>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location / Barangay</th>
                        <th>Complaints</th>
                        <th>First Report</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citizens as $citizen)
                    <tr>
                        <td>
                            <div class="name-cell">
                                <div class="citizen-avatar">
                                    @if($citizen->getProfilePictureUrl())
                                        <img src="{{ $citizen->getProfilePictureUrl() }}" alt="">
                                    @else
                                        {{ strtoupper(substr($citizen->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="citizen-name">{{ $citizen->name }}</div>
                            </div>
                        </td>
                        <td class="citizen-email">{{ $citizen->email }}</td>
                        <td class="phone-cell">{{ $citizen->phone ?? '—' }}</td>
                        <td class="location-cell">{{ $citizen->barangay ?? $citizen->location ?? '—' }}</td>
                        <td>
                            @php
                                $n = $citizen->complaints_count;
                                $cls = $n == 0 ? 'c0' : ($n == 1 ? 'c1' : ($n <= 3 ? 'c2' : ($n <= 5 ? 'c3' : 'c4')));
                            @endphp
                            <span class="complaints-badge {{ $cls }}">{{ $n }}</span>
                        </td>
                        <td class="date-cell">
                            @if($citizen->complaints_count > 0 && $citizen->complaints->isNotEmpty())
                                {{ $citizen->complaints->min('created_at')?->format('M j, Y') ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="date-cell">{{ $citizen->created_at->format('M j, Y') }}</td>
                        <td>
                            <button class="btn-sm btn-edit-citizen"
                                data-id="{{ $citizen->id }}"
                                data-name="{{ $citizen->name }}"
                                data-email="{{ $citizen->email }}"
                                data-phone="{{ $citizen->phone ?? '' }}"
                                data-barangay="{{ $citizen->barangay ?? '' }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--text-muted);padding:40px;">
                            <i class="fa-solid fa-users-slash" style="font-size:28px;color:var(--brand-pale);display:block;margin-bottom:10px;"></i>
                            No citizens found.
                        </td>
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

{{-- Add Citizen Modal --}}
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h2><i class="fa-solid fa-user-plus" style="font-size:18px;color:var(--brand-mid);margin-right:8px;"></i>Add Citizen</h2>
        <p>Create a new resident account.</p>
        <form method="POST" action="{{ route('admin.citizens.store') }}">
            @csrf
            <div class="field">
                <label><i class="fa-solid fa-user"></i> Full Name</label>
                <input type="text" name="name" placeholder="Juan dela Cruz" required value="{{ old('name') }}">
            </div>
            <div class="field">
                <label><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="email" name="email" placeholder="juan@email.com" required value="{{ old('email') }}">
            </div>
            <div class="field">
                <label><i class="fa-solid fa-phone"></i> Phone Number</label>
                <input type="text" name="phone" placeholder="09XX-XXX-XXXX" value="{{ old('phone') }}">
            </div>
            <div class="field">
                <label><i class="fa-solid fa-location-dot"></i> Barangay / Location</label>
                <input type="text" name="barangay" placeholder="e.g. Brgy. Poblacion" value="{{ old('barangay') }}">
            </div>
            <div class="field">
                <label><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" name="password" placeholder="Minimum 8 characters" required>
            </div>
            <div class="modal-btns">
                <button type="button" class="btn-modal-cancel" onclick="document.getElementById('addModal').classList.remove('show')">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-floppy-disk"></i> Add citizen
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Citizen Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h2><i class="fa-solid fa-user-pen" style="font-size:18px;color:var(--brand-mid);margin-right:8px;"></i>Edit Citizen</h2>
        <p>Update this citizen's account information.</p>
        <div class="modal-alert" id="edit-alert"></div>
        <form id="editCitizenForm">
            @csrf
            <input type="hidden" id="edit-citizen-id">
            <div class="field">
                <label><i class="fa-solid fa-user"></i> Full Name</label>
                <input type="text" id="edit-name" name="name" placeholder="Juan dela Cruz" required>
            </div>
            <div class="field">
                <label><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="email" id="edit-email" name="email" placeholder="juan@email.com" required>
            </div>
            <div class="field">
                <label><i class="fa-solid fa-phone"></i> Phone Number</label>
                <input type="text" id="edit-phone" name="phone" placeholder="09XX-XXX-XXXX">
            </div>
            <div class="field">
                <label><i class="fa-solid fa-location-dot"></i> Barangay / Location</label>
                <input type="text" id="edit-barangay" name="barangay" placeholder="e.g. Brgy. Poblacion">
            </div>
            <div class="modal-btns">
                <button type="button" class="btn-modal-cancel" onclick="document.getElementById('editModal').classList.remove('show')">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </button>
                <button type="submit" class="btn-modal-save" id="edit-save-btn">
                    <i class="fa-solid fa-floppy-disk"></i> Save changes
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>document.getElementById('addModal').classList.add('show');</script>
@endif

<script>
    var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    document.getElementById('addModal').addEventListener('click',  function(e){ if(e.target===this) this.classList.remove('show'); });
    document.getElementById('editModal').addEventListener('click', function(e){ if(e.target===this) this.classList.remove('show'); });

    document.addEventListener('click', function(e){
        var btn = e.target.closest('.btn-edit-citizen');
        if (!btn) return;
        document.getElementById('edit-citizen-id').value = btn.dataset.id;
        document.getElementById('edit-name').value       = btn.dataset.name;
        document.getElementById('edit-email').value      = btn.dataset.email;
        document.getElementById('edit-phone').value      = btn.dataset.phone;
        document.getElementById('edit-barangay').value   = btn.dataset.barangay;
        document.getElementById('edit-alert').className  = 'modal-alert';
        document.getElementById('editModal').classList.add('show');
    });

    document.getElementById('editCitizenForm').addEventListener('submit', async function(e){
        e.preventDefault();
        var id  = document.getElementById('edit-citizen-id').value;
        var btn = document.getElementById('edit-save-btn');
        var alert = document.getElementById('edit-alert');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';

        var body = new FormData(this);
        body.append('_method','PUT');

        try {
            var r = await fetch('/admin/citizens/' + id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: body
            });
            var data = await r.json();
            if (data.success) {
                alert.textContent = data.message;
                alert.className = 'modal-alert success show';
                setTimeout(function(){ window.location.reload(); }, 1200);
            } else {
                alert.textContent = data.message || 'Something went wrong.';
                alert.className = 'modal-alert error show';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save changes';
            }
        } catch(err) {
            alert.textContent = 'Network error: ' + err.message;
            alert.className = 'modal-alert error show';
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save changes';
        }
    });
</script>
</body>
</html>