{{-- resources/views/resident/filecomplaint.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>File a Complaint – MuniciReport</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --sidebar-w: 250px;
            --brand-deep:   #08519C; --brand-mid:    #3182BD;
            --brand-teal:   #6BAED6; --brand-pale:   #9ECAE1;
            --bg-page:      #EFF3FF; --bg-surface:   #ffffff;
            --bg-raised:    #F8FAFF; --bg-input:     #EFF3FF;
            --text-primary:  #0a2a4a; --text-muted:    #4a6fa5;
            --border:       #C6DBEF;
            --color-orange: #D97706; --color-blue:   #1D6FAB;
            --color-green:  #047857;
            --color-green-bg:  #D1FAE5; --color-orange-bg: #FEF3C7; --color-blue-bg: #EFF3FF;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-page: #0d1b2a; --bg-surface: #0f2035;
                --bg-raised: #162840; --bg-input: #1a3050;
                --text-primary: #e2e8f0; --text-muted: #7fb3d3; --border: #1e3a5f;
                --color-orange: #F59E0B; --color-blue: #60A5FA; --color-green: #34D399;
                --color-green-bg: rgba(6,78,59,0.45); --color-orange-bg: rgba(120,53,15,0.45); --color-blue-bg: rgba(30,58,138,0.35);
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
        .nav-badge { background: #ef4444; color: white; border-radius: 999px; font-size: 10px; font-weight: 700; padding: 2px 7px; margin-left: auto; flex-shrink: 0; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; padding: 40px 44px; }

        .page-title { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; color: var(--text-primary); margin-bottom: 5px; }
        .page-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 30px; }

        .alert-error { background: rgba(185,28,28,0.1); border: 1.5px solid rgba(239,68,68,0.3); color: #ef4444; border-radius: 12px; padding: 13px 18px; font-size: 14px; margin-bottom: 24px; }

        /* ── Form card ── */
        .form-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 20px; padding: 38px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .col-full { grid-column: 1 / -1; }
        .field { display: flex; flex-direction: column; }
        .field label { font-size: 11px; font-weight: 700; color: var(--text-muted); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px; }
        input[type="text"], input[type="tel"], select, textarea { width: 100%; padding: 13px 16px; border: 1.5px solid var(--border); border-radius: 11px; font-family: 'Inter', sans-serif; font-size: 14px; background: var(--bg-input); color: var(--text-primary); transition: border-color .2s, box-shadow .2s; outline: none; appearance: none; }
        input:focus, select:focus, textarea:focus { border-color: var(--brand-mid); box-shadow: 0 0 0 3px rgba(49,130,189,0.13); background: var(--bg-surface); }
        input::placeholder, textarea::placeholder { color: var(--text-muted); }
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; background-color: var(--bg-input); padding-right: 42px; cursor: pointer; }
        textarea { resize: vertical; min-height: 130px; line-height: 1.65; }

        /* ── Anonymous Toggle ── */
        .anon-toggle-card {
            grid-column: 1 / -1;
            display: flex; align-items: flex-start; gap: 14px;
            background: var(--bg-raised); border: 1.5px solid var(--border);
            border-radius: 14px; padding: 16px 20px;
            cursor: pointer; transition: border-color .2s, background .2s;
            user-select: none;
        }
        .anon-toggle-card:hover { border-color: var(--brand-teal); background: var(--bg-surface); }
        .anon-toggle-card.active {
            border-color: #7C3AED;
            background: rgba(124,58,237,0.06);
        }
        .anon-checkbox-visual {
            width: 22px; height: 22px; border-radius: 6px;
            border: 2px solid var(--border); background: var(--bg-surface);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px; transition: all .15s;
        }
        .anon-toggle-card.active .anon-checkbox-visual {
            background: #7C3AED; border-color: #7C3AED;
        }
        .anon-checkbox-visual i { font-size: 12px; color: white; display: none; }
        .anon-toggle-card.active .anon-checkbox-visual i { display: block; }
        .anon-text-wrap { flex: 1; }
        .anon-title {
            font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;
            display: flex; align-items: center; gap: 7px;
        }
        .anon-title .anon-badge {
            font-size: 10px; font-weight: 700; letter-spacing: 0.5px;
            background: rgba(124,58,237,0.12); color: #7C3AED;
            border: 1px solid rgba(124,58,237,0.25);
            border-radius: 999px; padding: 2px 9px;
        }
        .anon-desc { font-size: 12.5px; color: var(--text-muted); line-height: 1.6; }

        /* Hidden fields when anonymous */
        .anon-hidden-field { transition: opacity .3s, max-height .3s; overflow: hidden; }
        .anon-hidden-field.is-anon { opacity: 0.4; pointer-events: none; }

        /* ── Custom Category Combobox ── */
        .category-wrap { position: relative; }
        .category-input-row { display: flex; gap: 8px; align-items: stretch; }
        .category-text-input { flex: 1; padding: 13px 16px; border: 1.5px solid var(--border); border-radius: 11px; font-family: 'Inter', sans-serif; font-size: 14px; background: var(--bg-input); color: var(--text-primary); outline: none; transition: border-color .2s, box-shadow .2s; }
        .category-text-input:focus { border-color: var(--brand-mid); box-shadow: 0 0 0 3px rgba(49,130,189,0.13); background: var(--bg-surface); }
        .category-text-input::placeholder { color: var(--text-muted); }
        .btn-add-category { padding: 13px 18px; background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid)); color: white; border: none; border-radius: 11px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap; flex-shrink: 0; transition: opacity .15s; }
        .btn-add-category:hover { opacity: .88; }
        .category-divider { display: flex; align-items: center; gap: 10px; margin: 10px 0; }
        .category-divider span { font-size: 11px; color: var(--text-muted); font-weight: 600; white-space: nowrap; }
        .category-divider::before, .category-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .category-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 6px; }
        .custom-cat-tag { display: inline-flex; align-items: center; gap: 6px; background: var(--bg-input); border: 1.5px solid var(--brand-pale); color: var(--brand-mid); border-radius: 999px; padding: 3px 10px 3px 12px; font-size: 12px; font-weight: 600; margin: 4px 4px 0 0; }
        .custom-cat-tag button { background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 14px; line-height: 1; padding: 0; display: flex; align-items: center; }
        .custom-cat-tag button:hover { color: #ef4444; }
        #custom-cats-wrap { margin-top: 8px; }

        /* ── Upload ── */
        .upload-area { border: 2px dashed var(--brand-pale); border-radius: 13px; padding: 28px 20px; background: var(--bg-input); cursor: pointer; transition: all .2s; text-align: center; }
        .upload-area:hover { border-color: var(--brand-mid); background: var(--bg-raised); }
        .upload-main { font-size: 14px; color: var(--text-muted); font-weight: 500; margin-top: 8px; display: block; }
        .upload-hint { font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px; opacity: .7; }

        /* ── Buttons ── */
        .btn-row { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
        .btn-clear { padding: 13px 28px; border: 1.5px solid var(--border); border-radius: 11px; background: var(--bg-surface); color: var(--text-muted); font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all .15s; }
        .btn-clear:hover { background: var(--bg-raised); color: var(--brand-mid); border-color: var(--brand-teal); }
        .btn-submit { padding: 13px 32px; border: none; border-radius: 11px; background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid), var(--brand-teal)); color: white; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; box-shadow: 0 5px 18px rgba(8,81,156,0.32); transition: opacity .15s; }
        .btn-submit:hover { opacity: 0.88; }

        /* ── Success Modal ── */
        .success-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.45); z-index: 700; align-items: center; justify-content: center; padding: 20px; }
        .success-overlay.show { display: flex; }
        .success-modal { background: var(--bg-surface); border-radius: 24px; padding: 52px 40px 44px; width: 100%; max-width: 420px; text-align: center; box-shadow: 0 28px 80px rgba(8,81,156,0.3); border: 1.5px solid var(--border); animation: popIn .35s cubic-bezier(.34,1.56,.64,1); }
        @keyframes popIn { from { transform: scale(.82); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .success-icon { width: 82px; height: 82px; border-radius: 50%; background: linear-gradient(135deg,#10B981,#6EE7B7); display: flex; align-items: center; justify-content: center; margin: 0 auto 22px; box-shadow: 0 8px 28px rgba(16,185,129,0.38); }
        .success-icon i { font-size: 38px; color: white; }
        .success-modal h2 { font-size: 26px; font-weight: 700; color: var(--text-primary); margin-bottom: 10px; letter-spacing: -0.3px; }
        .success-modal p { font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 30px; }
        .success-modal-btns { display: flex; flex-direction: column; gap: 10px; }
        .btn-view-complaints { padding: 14px 36px; border: none; border-radius: 12px; background: linear-gradient(135deg, var(--brand-deep), var(--brand-mid)); color: white; font-size: 15px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; box-shadow: 0 5px 18px rgba(8,81,156,0.28); }
        .btn-stay { padding: 12px 36px; border: 1.5px solid var(--border); border-radius: 12px; background: var(--bg-surface); color: var(--text-muted); font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; }
        .btn-stay:hover { background: var(--bg-raised); }

        @media (max-width: 1024px) { .content { padding: 28px 24px; } .form-card { padding: 28px 24px; } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 6px 0 28px rgba(8,81,156,0.3); }
            .main { margin-left: 0; }
            .content { padding: 20px 14px; }
            .form-card { padding: 20px 16px; border-radius: 16px; }
            .form-grid { grid-template-columns: 1fr; gap: 16px; }
            .col-full { grid-column: 1; }
            .page-title { font-size: 24px; }
            .btn-row { flex-direction: column-reverse; }
            .btn-clear, .btn-submit { width: 100%; text-align: center; }
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
        <a href="{{ route('resident.complaints.create') }}" class="nav-item active">
            <i class="fa-solid fa-pen-to-square"></i> File a Complaint
        </a>
        <a href="{{ route('resident.complaints.index') }}" class="nav-item">
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
        <h1 class="page-title">File a Complaint</h1>
        <p class="page-sub">Submit your concern to the Mayor's Office online.</p>

        @if($errors->any())
            <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        <div class="form-card">
            <form method="POST" action="{{ route('resident.complaints.store') }}" enctype="multipart/form-data" id="complaintForm">
                @csrf

                {{-- ════════════════════════════════════════════
                     ANONYMOUS TOGGLE — pinaka-unang field
                ════════════════════════════════════════════ --}}
                <div class="form-grid" style="margin-bottom: 24px;">
                    <div class="anon-toggle-card col-full" id="anonCard" onclick="toggleAnonymous()">
                        <div class="anon-checkbox-visual" id="anonCheckVisual">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="anon-text-wrap">
                            <div class="anon-title">
                                <i class="fa-solid fa-user-secret" style="color:#7C3AED;"></i>
                                Submit Anonymously
                                <span class="anon-badge">PRIVATE</span>
                            </div>
                            <div class="anon-desc">
                                Kapag pinili ito, <strong>hindi makikita ng admin</strong> ang iyong pangalan, contact number, at account sa listahan ng Citizens.
                                Maaari ka pa ring mag-track ng iyong complaint sa pamamagitan ng reference number.
                            </div>
                        </div>
                    </div>
                    {{-- Hidden input na isinasama sa form --}}
                    <input type="hidden" name="is_anonymous" id="f-is-anonymous" value="0">
                </div>

                <div class="form-grid">
                    {{-- Name field — idi-dim kapag anonymous --}}
                    <div class="field anon-hidden-field" id="field-name">
                        <label>Full Name</label>
                        <input type="text" name="full_name" id="f-name"
                            value="{{ old('full_name', Auth::user()->name) }}"
                            placeholder="Juan dela Cruz">
                    </div>

                    {{-- Contact field — idi-dim kapag anonymous --}}
                    <div class="field anon-hidden-field" id="field-contact">
                        <label>Contact Number</label>
                        <input type="tel" name="contact_number" id="f-contact"
                            value="{{ old('contact_number', Auth::user()->phone ?? '') }}"
                            placeholder="09XX-XXX-XXXX">
                    </div>

                    <div class="field col-full">
                        <label>Complaint Category</label>
                        <div class="category-wrap">
                            <div class="category-input-row">
                                <input type="text" class="category-text-input" id="f-category-custom"
                                    placeholder="Type your own category…" autocomplete="off">
                                <button type="button" class="btn-add-category" onclick="addCustomCategory()">
                                    <i class="fa-solid fa-plus"></i> Add
                                </button>
                            </div>
                            <div id="custom-cats-wrap"></div>
                            <div class="category-divider"><span>or choose existing</span></div>
                            <input type="hidden" name="category" id="f-category-value" value="{{ old('category') }}" required>
                            <select id="f-category-select" onchange="selectCategory(this.value)">
                                <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select a category</option>
                                @foreach(['Sanitation & Waste','Road & Infrastructure','Noise & Disturbance','Illegal Construction','Street Lighting','Flooding & Drainage','Public Safety'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            <p class="category-hint"><i class="fa-solid fa-circle-info" style="font-size:10px;"></i> You can type a custom category above — it will be added to the dropdown so you can reuse it.</p>
                        </div>
                    </div>

                    <div class="field col-full">
                        <label>Location / Barangay</label>
                        <input type="text" name="location" id="f-location"
                            value="{{ old('location', Auth::user()->location ?? '') }}"
                            placeholder="e.g. Brgy. Poblacion, Victoria" required>
                    </div>
                    <div class="field col-full">
                        <label>Description</label>
                        <textarea name="description" id="f-description"
                            placeholder="Describe your complaint in detail..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="field col-full">
                        <label>Attach Photos (Optional)</label>
                        <div class="upload-area" onclick="document.getElementById('photo-input').click()">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:28px;color:var(--brand-pale);"></i>
                            <span class="upload-main">Click to add photos</span>
                            <span class="upload-hint">PNG, JPG up to 5MB each — click multiple times to add more</span>
                        </div>
                        <input type="file" name="photos[]" id="photo-input"
                               accept="image/*" multiple style="display:none;"
                               onchange="appendPhotos(this)">
                        <div id="photo-preview-container" style="display:none; margin-top:12px;">
                            <div style="font-size:11px;font-weight:700;color:var(--text-muted);margin-bottom:8px;">
                                Photos to upload
                                <span id="photo-count-badge"
                                      style="background:var(--bg-input);color:var(--color-blue);border:1.5px solid var(--brand-pale);border-radius:999px;padding:2px 10px;font-size:11px;font-weight:700;">0</span>
                            </div>
                            <div id="photo-preview-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;"></div>
                        </div>
                    </div>
                </div>

                <div class="btn-row">
                    <button type="button" class="btn-clear" onclick="clearForm()">Clear form</button>
                    <button type="submit" class="btn-submit" id="submitBtn">Submit complaint →</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Success Modal ── -->
<div class="success-overlay" id="successModal">
    <div class="success-modal">
        <div class="success-icon"><i class="fa-solid fa-check"></i></div>
        <h2>Complaint Submitted!</h2>
        <p id="successMsg">Your complaint has been successfully submitted to the Mayor's Office. You can track its status under <strong>My Complaints</strong>.</p>
        <div class="success-modal-btns">
            <button class="btn-view-complaints" id="viewComplaintsBtn" data-url="{{ route('resident.complaints.index') }}">
                <i class="fa-solid fa-clipboard-list"></i> View My Complaints
            </button>
            <button class="btn-stay" id="submitAnotherBtn">Submit Another</button>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('successModal').classList.add('show');
    });
</script>
@endif

<script>
    var STORAGE_KEY = 'municireport_custom_categories';
    var isAnonymous = false;

    function openSidebar()  { document.getElementById('sidebar').classList.add('open');  document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    document.getElementById('viewComplaintsBtn').addEventListener('click', function() { window.location.href = this.dataset.url; });
    document.getElementById('submitAnotherBtn').addEventListener('click', function() { document.getElementById('successModal').classList.remove('show'); });

    /* ══════════════════════════════════════════════
       ANONYMOUS TOGGLE
    ══════════════════════════════════════════════ */
    function toggleAnonymous() {
        isAnonymous = !isAnonymous;

        // Update hidden input
        document.getElementById('f-is-anonymous').value = isAnonymous ? '1' : '0';

        // Update visual checkbox
        var card = document.getElementById('anonCard');
        card.classList.toggle('active', isAnonymous);

        // Dim name & contact fields
        document.getElementById('field-name').classList.toggle('is-anon', isAnonymous);
        document.getElementById('field-contact').classList.toggle('is-anon', isAnonymous);

        // Update success message
        if (isAnonymous) {
            document.getElementById('successMsg').innerHTML =
                'Your <strong>anonymous</strong> complaint has been submitted. Track it via your reference number under <strong>My Complaints</strong>.';
        } else {
            document.getElementById('successMsg').innerHTML =
                'Your complaint has been successfully submitted to the Mayor\'s Office. You can track its status under <strong>My Complaints</strong>.';
        }
    }

    /* ══════════════════════════════════════════════
       CUSTOM CATEGORY
    ══════════════════════════════════════════════ */
    var customCategories = [];

    function loadSavedCategories() {
        try { var saved = localStorage.getItem(STORAGE_KEY); if (saved) customCategories = JSON.parse(saved); } catch(e) { customCategories = []; }
        customCategories.forEach(function(cat) { injectCategoryOption(cat); });
        renderCustomTags();
    }

    function saveCategories() {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(customCategories)); } catch(e) {}
    }

    function injectCategoryOption(cat) {
        var sel = document.getElementById('f-category-select');
        var exists = Array.from(sel.options).some(function(o) { return o.value === cat; });
        if (!exists) {
            var opt = document.createElement('option');
            opt.value = cat; opt.textContent = cat; opt.setAttribute('data-custom', '1');
            sel.appendChild(opt);
        }
    }

    function addCustomCategory() {
        var input = document.getElementById('f-category-custom');
        var val   = input.value.trim();
        if (!val) return;
        var sel    = document.getElementById('f-category-select');
        var exists = Array.from(sel.options).some(function(o) { return o.value.toLowerCase() === val.toLowerCase(); });
        if (!exists) { customCategories.push(val); saveCategories(); injectCategoryOption(val); renderCustomTags(); }
        selectCategory(val);
        for (var i = 0; i < sel.options.length; i++) { if (sel.options[i].value === val) { sel.selectedIndex = i; break; } }
        input.value = '';
    }

    function removeCustomCategory(cat) {
        customCategories = customCategories.filter(function(c) { return c !== cat; });
        saveCategories();
        var sel = document.getElementById('f-category-select');
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === cat && sel.options[i].getAttribute('data-custom')) { sel.remove(i); break; }
        }
        if (document.getElementById('f-category-value').value === cat) { document.getElementById('f-category-value').value = ''; sel.selectedIndex = 0; }
        renderCustomTags();
    }

    function renderCustomTags() {
        var wrap = document.getElementById('custom-cats-wrap');
        wrap.innerHTML = '';
        if (!customCategories.length) return;
        customCategories.forEach(function(cat) {
            var tag = document.createElement('span');
            tag.className = 'custom-cat-tag';
            tag.innerHTML = cat + '<button type="button" title="Remove" onclick="removeCustomCategory(\'' + cat.replace(/'/g, "\\'") + '\')"><i class="fa-solid fa-xmark" style="font-size:11px;"></i></button>';
            wrap.appendChild(tag);
        });
    }

    function selectCategory(val) { document.getElementById('f-category-value').value = val; }

    document.getElementById('f-category-select').addEventListener('change', function() { selectCategory(this.value); });
    document.getElementById('f-category-custom').addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); addCustomCategory(); } });

    (function() {
        var oldVal = '{{ old("category") }}';
        if (oldVal) {
            document.getElementById('f-category-value').value = oldVal;
            var sel = document.getElementById('f-category-select');
            for (var i = 0; i < sel.options.length; i++) { if (sel.options[i].value === oldVal) { sel.selectedIndex = i; break; } }
        }
    })();

    loadSavedCategories();

    /* ══════════════════════════════════════════════
       PHOTO UPLOAD
    ══════════════════════════════════════════════ */
    var selectedFiles = [];

    function appendPhotos(input) {
        Array.from(input.files).forEach(function(newFile) {
            var exists = selectedFiles.some(function(f) { return f.name === newFile.name && f.size === newFile.size; });
            if (!exists) selectedFiles.push(newFile);
        });
        renderPreviews();
        input.value = '';
    }

    function renderPreviews() {
        var grid  = document.getElementById('photo-preview-grid');
        var wrap  = document.getElementById('photo-preview-container');
        var badge = document.getElementById('photo-count-badge');
        grid.innerHTML = '';
        if (selectedFiles.length === 0) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';
        badge.textContent  = selectedFiles.length;
        selectedFiles.forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.style.cssText = 'position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1;background:var(--bg-input);';
                div.innerHTML =
                    '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">' +
                    '<button type="button" onclick="removePhoto(' + index + ')" ' +
                    'style="position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(239,68,68,0.9);color:white;border:none;border-radius:50%;font-size:14px;cursor:pointer;line-height:1;display:flex;align-items:center;justify-content:center;">×</button>';
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removePhoto(index) { selectedFiles.splice(index, 1); renderPreviews(); }

    /* ══════════════════════════════════════════════
       CLEAR FORM
    ══════════════════════════════════════════════ */
    function clearForm() {
        document.getElementById('f-name').value = '';
        document.getElementById('f-contact').value = '';
        document.getElementById('f-category-select').selectedIndex = 0;
        document.getElementById('f-category-value').value = '';
        document.getElementById('f-category-custom').value = '';
        document.getElementById('f-location').value = '';
        document.getElementById('f-description').value = '';
        selectedFiles = [];
        renderPreviews();
        // Reset anonymous
        isAnonymous = false;
        document.getElementById('f-is-anonymous').value = '0';
        document.getElementById('anonCard').classList.remove('active');
        document.getElementById('field-name').classList.remove('is-anon');
        document.getElementById('field-contact').classList.remove('is-anon');
    }

    /* ══════════════════════════════════════════════
       FORM SUBMIT (AJAX)
    ══════════════════════════════════════════════ */
    document.getElementById('complaintForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!document.getElementById('f-category-value').value) { alert('Please select or type a complaint category.'); return; }

        // Kapag hindi anonymous, i-require ang name at contact
        if (!isAnonymous) {
            if (!document.getElementById('f-name').value.trim()) { alert('Please enter your full name.'); return; }
            if (!document.getElementById('f-contact').value.trim()) { alert('Please enter your contact number.'); return; }
        }

        var btn = document.getElementById('submitBtn');
        btn.disabled = true; btn.textContent = 'Submitting...';

        var formData = new FormData(this);
        formData.delete('photos[]');
        selectedFiles.forEach(function(file) { formData.append('photos[]', file); });

        // Kapag anonymous, palitan ng placeholder values para hindi mag-validate error
        if (isAnonymous) {
            formData.set('full_name', 'Anonymous');
            formData.set('contact_number', '—');
        }

        try {
            var response = await fetch(this.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (response.redirected || response.ok) {
                btn.disabled = false; btn.textContent = 'Submit complaint →';
                clearForm();
                document.getElementById('successModal').classList.add('show');
            } else {
                alert('Something went wrong. Please try again.');
                btn.disabled = false; btn.textContent = 'Submit complaint →';
            }
        } catch (err) {
            alert('Network error. Please try again.');
            btn.disabled = false; btn.textContent = 'Submit complaint →';
        }
    });
</script>
</body>
</html>