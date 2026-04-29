{{-- resources/views/admin/complaints.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complaints – MuniciReport Admin</title>
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
            --color-red:       #B91C1C;
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
                --color-green: #34D399; --color-red: #F87171;
                --color-green-bg:  rgba(6,78,59,0.45);
                --color-orange-bg: rgba(120,53,15,0.45);
                --color-blue-bg:   rgba(30,58,138,0.35);
                --color-red-bg:    rgba(127,29,29,0.45);
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

        .filter-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .search-wrap { position: relative; flex: 1; min-width: 180px; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--brand-pale); font-size: 13px; }
        .search-input { width: 100%; padding: 10px 12px 10px 36px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; font-family: 'Inter', sans-serif; background: var(--bg-surface); color: var(--text-primary); outline: none; }
        .search-input:focus { border-color: var(--brand-mid); box-shadow: 0 0 0 3px rgba(49,130,189,0.15); }
        .search-input::placeholder { color: var(--text-muted); }
        .filter-select { padding: 10px 34px 10px 12px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; font-family: 'Inter', sans-serif; background: var(--bg-surface); color: var(--text-primary); outline: none; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; }
        .filter-btn { padding: 10px 20px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; white-space: nowrap; }

        .table-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 700px; }
        thead th { padding: 13px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); background: var(--bg-raised); border-bottom: 1.5px solid var(--border); white-space: nowrap; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--bg-raised); }
        tbody td { padding: 13px 16px; font-size: 13px; color: var(--text-primary); vertical-align: middle; }

        .ref { font-size: 12px; font-weight: 700; color: var(--brand-mid); }

        .badge { font-size: 11px; font-weight: 600; border-radius: 999px; padding: 4px 10px; white-space: nowrap; display: inline-block; }
        .badge-pending   { background: var(--color-orange-bg); color: var(--color-orange); }
        .badge-progress  { background: var(--color-blue-bg);   color: var(--color-blue); }
        .badge-resolved  { background: var(--color-green-bg);  color: var(--color-green); }
        .badge-cancelled { background: var(--color-red-bg);    color: var(--color-red); }

        /* Anonymous badge */
        .badge-anon { font-size: 11px; font-weight: 600; border-radius: 999px; padding: 3px 9px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px; }
        @media (prefers-color-scheme: dark) {
            .badge-anon { background: rgba(255,255,255,0.07); color: #94a3b8; border-color: rgba(255,255,255,0.1); }
        }

        .date-cell { font-size: 12px; color: var(--text-muted); white-space: nowrap; }

        .btn-sm { padding: 6px 12px; border: 1.5px solid var(--border); border-radius: 7px; background: var(--bg-surface); color: var(--text-muted); font-size: 12px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; transition: all .15s; margin-right: 4px; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px; }
        .btn-sm:hover { background: var(--bg-raised); border-color: var(--brand-teal); color: var(--brand-mid); }
        .btn-sm.primary { background: linear-gradient(135deg,#08519C,#3182BD); color: white; border-color: transparent; }
        .btn-sm.primary:hover { opacity: .88; }
        .btn-sm.msg-btn { background: var(--bg-surface); border-color: var(--brand-teal); color: var(--brand-mid); position: relative; }
        .btn-sm.msg-btn:hover { background: var(--color-blue-bg); }
        .btn-sm.danger { background: var(--color-red-bg); border-color: rgba(185,28,28,0.3); color: var(--color-red); }
        .btn-sm.danger:hover { background: #fecaca; border-color: var(--color-red); }

        .msg-unread-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 17px; height: 17px; background: #ef4444; color: white; border-radius: 999px; font-size: 10px; font-weight: 700; padding: 0 4px; line-height: 1; }

        .alert-success { background: var(--color-blue-bg); border: 1.5px solid var(--border); color: var(--brand-mid); border-radius: 10px; padding: 12px 16px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

        .pagination-meta-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 12px 16px; border-top: 1.5px solid var(--border); }
        .pagination-meta-info { font-size: 13px; color: var(--text-muted); }
        .per-page-wrap { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); }
        .per-page-wrap select { padding: 6px 28px 6px 10px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Inter',sans-serif; background: var(--bg-surface); color: var(--text-primary); outline: none; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; }
        .pagination-wrap { display: flex; justify-content: center; align-items: center; padding: 10px 0 16px; }
        .pagination-wrap nav > div:first-child { display: none !important; }
        .pagination-wrap nav > div:last-child, .pagination-wrap nav > div { display: flex !important; align-items: center; gap: 4px; }
        .pagination-wrap nav span.relative { display: flex; align-items: center; gap: 4px; }
        .pagination-wrap nav a, .pagination-wrap nav span[aria-current="page"] > span, .pagination-wrap nav span[aria-disabled="true"] > span { display: inline-flex !important; align-items: center !important; justify-content: center !important; min-width: 34px; height: 34px; padding: 0 8px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; text-decoration: none; color: var(--text-muted); background: var(--bg-surface); transition: all .15s; line-height: 1; }
        .pagination-wrap nav a:hover { background: var(--bg-raised); border-color: var(--brand-teal); color: var(--brand-mid); }
        .pagination-wrap nav span[aria-current="page"] > span { background: linear-gradient(135deg, #08519C, #3182BD) !important; color: #fff !important; border-color: transparent !important; font-weight: 700; }
        .pagination-wrap nav span[aria-disabled="true"] > span { opacity: .38; cursor: not-allowed; }
        .pagination-wrap nav svg { width: 14px !important; height: 14px !important; display: block; }
        .pagination-wrap nav .flex.justify-between { display: none !important; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.45); z-index: 400; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.show { display: flex; }
        .modal-box { background: var(--bg-surface); border-radius: 18px; padding: 28px; width: 100%; max-width: 500px; max-height: 85vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.35); border: 1.5px solid var(--border); }
        .modal-box h3 { font-size: 17px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
        .modal-meta { font-size: 12px; color: var(--text-muted); margin-bottom: 14px; }
        .modal-desc { font-size: 14px; color: var(--text-primary); line-height: 1.7; background: var(--bg-input); border-radius: 10px; padding: 14px; margin-bottom: 14px; overflow-y: auto; max-height: 200px; white-space: pre-wrap; }
        .modal-close-btn { padding: 10px 22px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; align-self: flex-end; }
        .modal-close-btn:hover { background: var(--bg-raised); }

        .update-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.5); z-index: 500; align-items: center; justify-content: center; padding: 16px; }
        .update-overlay.show { display: flex; }
        .update-modal { background: var(--bg-surface); border-radius: 18px; padding: 28px; width: 100%; max-width: 580px; box-shadow: 0 24px 70px rgba(0,0,0,0.4); max-height: 92vh; overflow-y: auto; border: 1.5px solid var(--border); }
        .update-modal h2 { font-size: 19px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
        .update-modal .snippet { font-size: 12.5px; color: var(--text-muted); margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--border); }

        .field { display: flex; flex-direction: column; margin-bottom: 14px; }
        .field label { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 7px; }
        .field input[type="text"], .field select, .field textarea { width: 100%; padding: 11px 13px; border: 1.5px solid var(--border); border-radius: 9px; font-size: 13.5px; font-family: 'Inter', sans-serif; background: var(--bg-input); color: var(--text-primary); outline: none; transition: border-color .2s; }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: var(--brand-mid); background: var(--bg-surface); }
        .field select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234a6fa5' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 11px center; padding-right: 34px; }
        .field textarea { resize: vertical; min-height: 75px; line-height: 1.6; }

        .remarks-toggle-btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-muted); font-size: 12px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; margin-bottom: 10px; transition: all .15s; }
        .remarks-toggle-btn:hover { background: var(--bg-raised); border-color: var(--brand-teal); color: var(--brand-mid); }
        .remarks-collapse { display: none; }
        .remarks-collapse.open { display: block; }

        .cancel-reason-field { display: none; }
        .cancel-reason-field.show { display: block; }
        .cancel-warn { background: var(--color-red-bg); border: 1.5px solid rgba(185,28,28,0.25); border-radius: 9px; padding: 10px 13px; margin-bottom: 10px; font-size: 12.5px; color: var(--color-red); display: flex; align-items: flex-start; gap: 7px; line-height: 1.55; }

        .prev-updates { margin-bottom: 16px; }
        .prev-updates-title { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--brand-mid); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .upd-entry { background: var(--bg-raised); border: 1.5px solid var(--border); border-radius: 10px; padding: 12px 14px; margin-bottom: 8px; }
        .upd-entry-date { font-size: 11px; color: var(--text-muted); margin-bottom: 6px; font-weight: 600; }
        .upd-entry-note { font-size: 13px; color: var(--text-primary); line-height: 1.6; margin-bottom: 8px; }
        .upd-entry-photos { display: grid; grid-template-columns: repeat(4,1fr); gap: 6px; }
        .upd-entry-photos img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 6px; border: 1.5px solid var(--border); cursor: pointer; }

        .upd-comments-section { margin-top: 10px; border-top: 1px solid var(--border); padding-top: 8px; }
        .upd-comments-title { font-size: 10px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: var(--brand-mid); margin-bottom: 6px; }
        .upd-comment-item { background: var(--bg-surface); border: 1px solid var(--border); border-radius: 8px; padding: 8px 10px; margin-bottom: 6px; font-size: 12.5px; }
        .upd-comment-meta { font-size: 10.5px; color: var(--text-muted); margin-bottom: 3px; font-weight: 600; }
        .upd-comment-body { color: var(--text-primary); line-height: 1.55; }

        .new-update-section { border-top: 1.5px solid var(--border); padding-top: 16px; margin-top: 4px; }
        .new-update-title { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--color-green); margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
        .upload-area { border: 2px dashed var(--border); border-radius: 10px; padding: 16px; background: var(--bg-input); cursor: pointer; text-align: center; transition: all .2s; }
        .upload-area:hover { border-color: var(--brand-mid); background: var(--bg-raised); }
        .upload-area i { font-size: 22px; color: var(--brand-pale); margin-bottom: 6px; display: block; }
        .upload-area .ua-title { font-size: 13px; font-weight: 600; color: var(--brand-mid); }
        .upload-area .ua-sub   { font-size: 11px; color: var(--text-muted); }
        .photo-preview-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 6px; }
        .photo-thumb { position: relative; border-radius: 7px; overflow: hidden; aspect-ratio: 1; background: var(--bg-input); }
        .photo-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .photo-thumb .remove-btn { position: absolute; top: 3px; right: 3px; width: 18px; height: 18px; background: rgba(239,68,68,0.9); color: white; border: none; border-radius: 50%; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; }

        .modal-btns { display: flex; gap: 10px; margin-top: 14px; }
        .btn-cancel-m { flex: 1; padding: 12px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; }
        .btn-cancel-m:hover { background: var(--bg-raised); }
        .btn-save-m { flex: 2; padding: 12px; border: none; border-radius: 9px; background: linear-gradient(135deg,#08519C,#3182BD,#6BAED6); color: white; font-size: 13.5px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; }
        .btn-save-m:disabled { opacity: .6; cursor: not-allowed; }

        .save-confirm-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.55); z-index: 700; align-items: center; justify-content: center; padding: 16px; }
        .save-confirm-overlay.show { display: flex; }
        .save-confirm-box { background: var(--bg-surface); border-radius: 18px; padding: 32px; width: 100%; max-width: 380px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border: 1.5px solid var(--border); }
        .sc-icon { width: 60px; height: 60px; border-radius: 50%; background: var(--bg-input); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; font-size: 22px; color: var(--brand-mid); }
        .sc-title { font-size: 19px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
        .sc-msg   { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
        .sc-btns  { display: flex; gap: 10px; }
        .sc-cancel  { flex: 1; padding: 12px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; }
        .sc-confirm { flex: 1; padding: 12px; border: none; border-radius: 9px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; }

        .delete-confirm-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.55); z-index: 800; align-items: center; justify-content: center; padding: 16px; }
        .delete-confirm-overlay.show { display: flex; }
        .delete-confirm-box { background: var(--bg-surface); border-radius: 18px; padding: 32px; width: 100%; max-width: 380px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border: 1.5px solid var(--border); }
        .dc-icon { width: 60px; height: 60px; border-radius: 50%; background: var(--color-red-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; font-size: 22px; color: var(--color-red); }
        .dc-title { font-size: 19px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
        .dc-msg   { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
        .dc-btns  { display: flex; gap: 10px; }
        .dc-cancel  { flex: 1; padding: 12px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--bg-surface); color: var(--text-muted); font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; }
        .dc-confirm { flex: 1; padding: 12px; border: none; border-radius: 9px; background: linear-gradient(135deg,#ef4444,#dc2626); color: white; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; }

        .chat-overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.5); z-index: 600; align-items: center; justify-content: center; padding: 16px; }
        .chat-overlay.show { display: flex; }
        .chat-modal { background: var(--bg-surface); border-radius: 18px; width: 100%; max-width: 520px; height: 580px; display: flex; flex-direction: column; box-shadow: 0 24px 70px rgba(0,0,0,0.4); border: 1.5px solid var(--border); overflow: hidden; }
        .chat-modal-header { padding: 18px 22px; border-bottom: 1.5px solid var(--border); background: var(--bg-raised); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .chat-modal-title { font-size: 15px; font-weight: 700; color: var(--text-primary); }
        .chat-modal-sub   { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .chat-modal-close { background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 18px; padding: 4px; border-radius: 6px; transition: background .15s; }
        .chat-modal-close:hover { background: var(--border); }
        .chat-messages { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; background: var(--bg-raised); }
        .chat-msg-row { display: flex; flex-direction: column; }
        .chat-msg-row.mine   { align-items: flex-end; }
        .chat-msg-row.theirs { align-items: flex-start; }
        .chat-msg-sender { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 3px; }
        .chat-bubble { max-width: 72%; padding: 10px 14px; border-radius: 14px; font-size: 13.5px; line-height: 1.55; word-break: break-word; }
        .chat-msg-row.mine   .chat-bubble { background: linear-gradient(135deg,#08519C,#3182BD); color: white; border-bottom-right-radius: 4px; }
        .chat-msg-row.theirs .chat-bubble { background: var(--bg-surface); border: 1.5px solid var(--border); color: var(--text-primary); border-bottom-left-radius: 4px; }
        .chat-msg-time { font-size: 10px; color: var(--text-muted); margin-top: 3px; }
        .chat-empty { text-align: center; padding: 40px 20px; color: var(--text-muted); font-size: 13px; }
        .chat-input-area { padding: 14px 16px; border-top: 1.5px solid var(--border); display: flex; gap: 10px; background: var(--bg-surface); flex-shrink: 0; }
        .chat-textarea { flex: 1; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Inter',sans-serif; font-size: 13.5px; background: var(--bg-input); color: var(--text-primary); outline: none; resize: none; min-height: 42px; max-height: 100px; }
        .chat-textarea:focus { border-color: var(--brand-mid); background: var(--bg-surface); }
        .chat-send-btn { padding: 10px 18px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600; font-family: 'Inter',sans-serif; cursor: pointer; align-self: flex-end; }
        .chat-send-btn:disabled { opacity: .6; cursor: not-allowed; }

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
            .pagination-meta-bar { flex-direction: column; align-items: flex-start; gap: 8px; }
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
        <a href="{{ route('admin.dashboard') }}"  class="nav-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="{{ route('admin.complaints') }}"  class="nav-item active"><i class="fa-solid fa-clipboard-list"></i> Complaints</a>
        <a href="{{ route('admin.resolved') }}"    class="nav-item"><i class="fa-solid fa-circle-check"></i> Resolved</a>
        <a href="{{ route('admin.citizens') }}"    class="nav-item"><i class="fa-solid fa-users"></i> Citizens</a>
        <a href="{{ route('admin.reports') }}"     class="nav-item"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    </div>
</aside>

<div class="main">
    <x-topbar role="admin" />

    <div class="content">
        <h1 class="page-title">Complaint Management</h1>
        <p class="page-sub">Review, assign, and update active complaints.</p>

        <div id="flash-area"></div>
        @if(session('success'))
            <div class="alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.complaints') }}" id="filterForm">
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            <div class="filter-bar">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="search-input" placeholder="Search complaints…" value="{{ request('search') }}">
                </div>
                <select name="category" class="filter-select">
                    <option value="all">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <select name="status" class="filter-select">
                    <option value="all">All statuses</option>
                    <option value="Pending"     {{ request('status') == 'Pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Cancelled"   {{ request('status') == 'Cancelled'   ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="filter-btn"><i class="fa-solid fa-filter"></i> Filter</button>
            </div>
        </form>

        @foreach($complaints as $c)
            <div class="complaint-data" style="display:none;"
                data-id="{{ $c->id }}"
                data-ref="{{ $c->reference_number }}"
                data-meta="{{ $c->category }} · {{ $c->location }}"
                data-desc="{{ $c->description }}"
                data-officer="{{ $c->assigned_officer ?? '' }}"
                data-status="{{ $c->status }}"
                data-remarks="{{ $c->remarks ?? '' }}"
                data-cancel-reason="{{ $c->cancellation_reason ?? '' }}"
                data-snippet="{{ Str::limit($c->description, 50) }} · {{ $c->location }}"
                data-citizen="{{ $c->getDisplayName() }}"
                data-anonymous="{{ $c->is_anonymous ? '1' : '0' }}"
                data-photos="{{ json_encode(collect($c->photos ?? [])->map(fn($p) => asset('storage/' . $p))->values()) }}"
                data-updates="{{ json_encode(collect($c->progress_updates ?? [])->map(function($u) {
                    $u['photos'] = collect($u['photos'] ?? [])->map(fn($p) => asset('storage/' . $p))->values()->toArray();
                    return $u;
                })->values()) }}">
            </div>
        @endforeach

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Citizen</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Filed</th>
                        <th>Actions</th>
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
                        <td>
                            <button class="btn-sm btn-view-desc" data-id="{{ $c->id }}">
                                <i class="fa-solid fa-eye"></i> View
                            </button>
                        </td>
                        <td>
                         @php
    $unreadForThis = \App\Models\ComplaintMessage::where('complaint_id', $c->id)
        ->where('sender_role', 'resident')
        ->where('is_read', false)
        ->count();
@endphp
<button class="btn-sm msg-btn btn-open-chat"
    data-id="{{ $c->id }}"
    data-ref="{{ $c->reference_number }}"
    data-name="{{ $c->is_anonymous ? 'Anonymous (' . $c->reference_number . ')' : ($c->user->name ?? $c->full_name) }}">
    <i class="fa-solid fa-comments"></i> Message
    @if($c->is_anonymous)
        <span style="font-size:10px;color:var(--text-muted);font-style:italic;"></span>
    @endif
    <span class="msg-unread-badge" id="msgbadge-{{ $c->id }}"
        style="{{ $unreadForThis > 0 ? '' : 'display:none;' }}">{{ $unreadForThis }}</span>
</button>
                        </td>
                        <td>
                            @php
                                $cls = match($c->status){
                                    'Pending'     => 'badge-pending',
                                    'In Progress' => 'badge-progress',
                                    'Resolved'    => 'badge-resolved',
                                    'Cancelled'   => 'badge-cancelled',
                                    default       => ''
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ $c->status }}</span>
                        </td>
                        <td class="date-cell">{{ $c->created_at->format('M j') }}</td>
                        <td>
                            <button class="btn-sm primary btn-open-update" data-id="{{ $c->id }}">
                                <i class="fa-solid fa-pen-to-square"></i> Update
                            </button>
                            @if($c->status === 'Cancelled')
                            <button class="btn-sm danger btn-delete-complaint"
                                data-id="{{ $c->id }}"
                                data-ref="{{ $c->reference_number }}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--text-muted);padding:40px;">
                            No active complaints.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($complaints->hasPages())
            <div class="pagination-meta-bar">
                <div class="pagination-meta-info">
                    Showing <strong>{{ $complaints->firstItem() ?? 0 }}–{{ $complaints->lastItem() ?? 0 }}</strong>
                    of <strong>{{ $complaints->total() }}</strong> complaints
                </div>
                <div class="per-page-wrap">
                    Rows per page:
                    <select onchange="changePerPage(this.value)">
                        @foreach([10,15,25,50] as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="pagination-wrap">{{ $complaints->links() }}</div>
            @else
            <div class="pagination-meta-bar">
                <div class="pagination-meta-info">
                    Showing <strong>{{ $complaints->count() }}</strong>
                    of <strong>{{ $complaints->total() }}</strong> complaints
                </div>
                <div class="per-page-wrap">
                    Rows per page:
                    <select onchange="changePerPage(this.value)">
                        @foreach([10,15,25,50] as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- DESCRIPTION MODAL --}}
<div class="modal-overlay" id="descModal">
    <div class="modal-box">
        <h3 id="desc-ref"></h3>
        <div class="modal-meta" id="desc-meta"></div>
        <div class="modal-desc" id="desc-body"></div>
        <div id="desc-photo-wrap" style="display:none;margin-bottom:14px;"></div>
        <button class="modal-close-btn" onclick="document.getElementById('descModal').classList.remove('show')">Close</button>
    </div>
</div>

{{-- UPDATE MODAL --}}
<div class="update-overlay" id="updateOverlay">
    <div class="update-modal">
        <h2 id="upd-title">Update Complaint</h2>
        <div class="snippet" id="upd-snippet"></div>

        <form id="updForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="upd-complaint-id" value="">

            <div class="field">
                <label>Assign to Officer</label>
                <input type="text" name="assigned_officer" id="upd-officer" placeholder="e.g. R. Santos (Sanitation)">
            </div>

            <div class="field">
                <label>Update Status</label>
                <select name="status" id="upd-status" onchange="handleStatusChange(this.value)">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <div class="cancel-reason-field" id="cancel-reason-field">
                <div class="cancel-warn">
                    <i class="fa-solid fa-triangle-exclamation" style="flex-shrink:0;margin-top:1px;"></i>
                    <span>This will notify the resident that their complaint has been cancelled. Please provide a clear reason.</span>
                </div>
                <div class="field" style="margin-bottom:0;">
                    <label>Cancellation Reason <span style="color:#ef4444;">*</span></label>
                    <textarea name="cancellation_reason" id="upd-cancel-reason" placeholder="Explain why this complaint is being cancelled…"></textarea>
                </div>
            </div>

            <button type="button" class="remarks-toggle-btn" id="remarks-toggle-btn" onclick="toggleRemarks()">
                <i class="fa-solid fa-chevron-right" id="remarks-chevron" style="font-size:10px;transition:transform .2s;"></i>
                Remarks / Action Taken (Internal)
            </button>
            <div class="remarks-collapse" id="remarks-collapse">
                <div class="field">
                    <textarea name="remarks" id="upd-remarks" placeholder="Internal notes for admin use…"></textarea>
                </div>
            </div>

            <div id="prevUpdatesSection" style="display:none;">
                <div class="prev-updates">
                    <div class="prev-updates-title"><i class="fa-solid fa-clock-rotate-left"></i> Previous Progress Updates</div>
                    <div id="prevUpdatesList"></div>
                </div>
            </div>

            <div class="new-update-section" id="progressSection" style="display:none;">
                <div class="new-update-title"><i class="fa-solid fa-plus-circle"></i> Add New Progress Update (Visible to Resident)</div>
                <div class="field">
                    <label>Progress Note</label>
                    <textarea name="progress_note" id="upd-prog-note" placeholder="Describe what's being done…"></textarea>
                </div>
                <div class="field">
                    <label><i class="fa-solid fa-images"></i> Progress Photos</label>
                    <div class="upload-area" onclick="document.getElementById('upd-photo-input').click()">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="ua-title">Click to add photos</div>
                        <div class="ua-sub">JPG, PNG — multiple allowed</div>
                    </div>
                    <input type="file" id="upd-photo-input" accept="image/*" multiple style="display:none;" onchange="appendPhotos(this)">
                    <div id="newPhotosWrap" style="display:none;margin-top:8px;">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px;">
                            New photos <span id="newPhotoCount" style="background:var(--bg-input);color:var(--brand-mid);border:1.5px solid var(--border);border-radius:999px;padding:1px 8px;font-weight:700;">0</span>
                        </div>
                        <div class="photo-preview-grid" id="photoPreviewGrid"></div>
                    </div>
                </div>
            </div>

            <div class="modal-btns">
                <button type="button" class="btn-cancel-m" onclick="closeUpdate()">Cancel</button>
                <button type="button" class="btn-save-m" id="saveBtn" onclick="openSaveConfirm()">
                    <i class="fa-solid fa-floppy-disk"></i> Save changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SAVE CONFIRM MODAL --}}
<div class="save-confirm-overlay" id="saveConfirmOverlay">
    <div class="save-confirm-box">
        <div class="sc-icon"><i class="fa-solid fa-floppy-disk"></i></div>
        <p class="sc-title">Save Changes?</p>
        <p class="sc-msg">Are you sure you want to save the changes to this complaint? The resident will be notified.</p>
        <div class="sc-btns">
            <button class="sc-cancel" onclick="document.getElementById('saveConfirmOverlay').classList.remove('show')">Cancel</button>
            <button class="sc-confirm" id="sc-confirm-btn">Yes, Save</button>
        </div>
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
<div class="delete-confirm-overlay" id="deleteConfirmOverlay">
    <div class="delete-confirm-box">
        <div class="dc-icon"><i class="fa-solid fa-trash"></i></div>
        <p class="dc-title">Delete Complaint?</p>
        <p class="dc-msg">Are you sure you want to permanently delete complaint <strong id="dc-ref"></strong>? This cannot be undone.</p>
        <div class="dc-btns">
            <button class="dc-cancel" onclick="document.getElementById('deleteConfirmOverlay').classList.remove('show')">Cancel</button>
            <button class="dc-confirm" id="dc-confirm-btn"><i class="fa-solid fa-trash"></i> Yes, Delete</button>
        </div>
    </div>
</div>

{{-- CHAT MODAL --}}
<div class="chat-overlay" id="chatOverlay">
    <div class="chat-modal">
        <div class="chat-modal-header">
            <div>
                <div class="chat-modal-title" id="chat-modal-title">Message Resident</div>
                <div class="chat-modal-sub" id="chat-modal-sub"></div>
            </div>
            <button class="chat-modal-close" onclick="closeChatModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="chat-messages" id="chat-messages">
            <div class="chat-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>
        </div>
        <div class="chat-input-area">
            <textarea class="chat-textarea" id="chat-input" placeholder="Type your message… (Enter to send)" rows="1"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendAdminMessage();}"></textarea>
            <button class="chat-send-btn" id="chat-send-btn" onclick="sendAdminMessage()">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

{{-- LIGHTBOX --}}
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" onclick="closeLightbox()">×</button>
    <button class="lightbox-nav lightbox-prev" onclick="lightboxNav(-1)"><i class="fa-solid fa-chevron-left"></i></button>
    <img id="lightbox-img" src="" alt="">
    <button class="lightbox-nav lightbox-next" onclick="lightboxNav(1)"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="lightbox-counter" id="lightbox-counter">1 / 1</div>
</div>

<script>
    var updateBaseUrl    = '{{ url("admin/complaints") }}';
    var msgBaseUrl       = '{{ url("messages") }}';
    var adminUnreadUrl   = '{{ route("messages.admin.unread") }}';
    var commentsBaseUrl  = '{{ url("complaint-comments") }}';
    var csrfToken        = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var lbPhotos = [], lbIndex = 0;
    var selectedFiles = [];
    var currentChatComplaintId = null;
    var chatPollTimer = null;
    var pendingDeleteId = null;

    function changePerPage(val) {
        var form = document.getElementById('filterForm');
        form.querySelector('[name="per_page"]').value = val;
        form.submit();
    }

    function openSidebar()  { document.getElementById('sidebar').classList.add('open');  document.getElementById('overlay').classList.add('show'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

    function escHtml(str) {
        return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function getComplaintData(id) {
        var el = document.querySelector('.complaint-data[data-id="' + id + '"]');
        if (!el) return null;
        var photos = [], updates = [];
        try { photos  = JSON.parse(el.dataset.photos  || '[]'); } catch(e) {}
        try { updates = JSON.parse(el.dataset.updates || '[]'); } catch(e) {}
        return {
            id: el.dataset.id, ref: el.dataset.ref, meta: el.dataset.meta,
            desc: el.dataset.desc, photos: photos, officer: el.dataset.officer,
            status: el.dataset.status, remarks: el.dataset.remarks,
            cancelReason: el.dataset.cancelReason,
            snippet: el.dataset.snippet, citizen: el.dataset.citizen,
            anonymous: el.dataset.anonymous === '1',
            updates: updates
        };
    }

    function openLightbox(photos, index) { lbPhotos = photos; lbIndex = index; renderLightbox(); document.getElementById('lightbox').classList.add('show'); }
    function renderLightbox() {
        document.getElementById('lightbox-img').src = lbPhotos[lbIndex];
        document.getElementById('lightbox-counter').textContent = (lbIndex+1) + ' / ' + lbPhotos.length;
        document.querySelector('.lightbox-prev').style.display = lbPhotos.length > 1 ? 'flex' : 'none';
        document.querySelector('.lightbox-next').style.display = lbPhotos.length > 1 ? 'flex' : 'none';
    }
    function lightboxNav(dir) { lbIndex = (lbIndex + dir + lbPhotos.length) % lbPhotos.length; renderLightbox(); }
    function closeLightbox()  { document.getElementById('lightbox').classList.remove('show'); }
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('lightbox').classList.contains('show')) {
            if (e.key==='ArrowLeft')  lightboxNav(-1);
            if (e.key==='ArrowRight') lightboxNav(1);
            if (e.key==='Escape')     closeLightbox();
        }
    });

    function toggleRemarks() {
        var collapse = document.getElementById('remarks-collapse');
        var chevron  = document.getElementById('remarks-chevron');
        var isOpen   = collapse.classList.contains('open');
        collapse.classList.toggle('open', !isOpen);
        chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(90deg)';
    }

    function handleStatusChange(status) {
        var progressSec = document.getElementById('progressSection');
        var cancelField = document.getElementById('cancel-reason-field');
        progressSec.style.display = (status === 'In Progress' || status === 'Resolved') ? 'block' : 'none';
        cancelField.classList.toggle('show', status === 'Cancelled');
    }

    function loadCommentsForUpdate(complaintId, updateIndex, containerEl) {
        fetch(commentsBaseUrl + '/' + complaintId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var comments = (data.comments || []).filter(function(c) { return c.update_index === updateIndex; });
            containerEl.innerHTML = '';
            if (comments.length === 0) {
                containerEl.innerHTML = '<div style="font-size:12px;color:var(--text-muted);font-style:italic;">No comments from resident.</div>';
                return;
            }
            comments.forEach(function(c) {
                var date = new Date(c.created_at).toLocaleString('en-PH', {month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
                var div  = document.createElement('div');
                div.className = 'upd-comment-item';
                div.innerHTML = '<div class="upd-comment-meta"><i class="fa-solid fa-user"></i> ' + escHtml(c.user_name) + ' · ' + date + '</div>'
                              + '<div class="upd-comment-body">' + escHtml(c.comment) + '</div>';
                containerEl.appendChild(div);
            });
        })
        .catch(function(){});
    }

    document.addEventListener('click', function(e) {

        var viewBtn = e.target.closest('.btn-view-desc');
        if (viewBtn) {
            var d = getComplaintData(viewBtn.dataset.id);
            if (!d) return;
            document.getElementById('desc-ref').textContent  = d.ref;
            document.getElementById('desc-meta').textContent = d.meta;
            document.getElementById('desc-body').textContent = d.desc;
            var pw = document.getElementById('desc-photo-wrap');
            pw.innerHTML = '';
            if (d.photos && d.photos.length > 0) {
                var lbl = document.createElement('div');
                lbl.style.cssText = 'font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;letter-spacing:1px;';
                lbl.textContent = 'Attached Photos (' + d.photos.length + ')';
                pw.appendChild(lbl);
                var grid = document.createElement('div');
                grid.style.cssText = 'display:grid;grid-template-columns:repeat(3,1fr);gap:8px;';
                d.photos.forEach(function(url, idx) {
                    var img = document.createElement('img');
                    img.src = url;
                    img.style.cssText = 'width:100%;aspect-ratio:1;object-fit:cover;border-radius:7px;border:1.5px solid var(--border);cursor:pointer;';
                    img.onclick = function() { openLightbox(d.photos, idx); };
                    grid.appendChild(img);
                });
                pw.appendChild(grid);
                pw.style.display = 'block';
            } else { pw.style.display = 'none'; }
            document.getElementById('descModal').classList.add('show');
            return;
        }

        var updBtn = e.target.closest('.btn-open-update');
        if (updBtn) {
            var d = getComplaintData(updBtn.dataset.id);
            if (!d) return;
            document.getElementById('upd-complaint-id').value  = d.id;
            document.getElementById('upd-title').textContent   = 'Update: ' + d.ref;
            document.getElementById('upd-snippet').textContent = d.snippet;
            document.getElementById('upd-officer').value       = d.officer;
            document.getElementById('upd-remarks').value       = d.remarks;
            document.getElementById('upd-cancel-reason').value = d.cancelReason || '';
            document.getElementById('upd-prog-note').value     = '';
            document.getElementById('upd-status').value        = d.status;

            document.getElementById('remarks-collapse').classList.remove('open');
            document.getElementById('remarks-chevron').style.transform = 'rotate(0deg)';
            if (d.remarks && d.remarks.trim()) {
                document.getElementById('remarks-collapse').classList.add('open');
                document.getElementById('remarks-chevron').style.transform = 'rotate(90deg)';
            }

            var prevList    = document.getElementById('prevUpdatesList');
            var prevSection = document.getElementById('prevUpdatesSection');
            prevList.innerHTML = '';
            if (d.updates && d.updates.length > 0) {
                d.updates.forEach(function(upd, idx) {
                    var div = document.createElement('div');
                    div.className = 'upd-entry';
                    var date    = new Date(upd.created_at);
                    var dateStr = date.toLocaleDateString('en-PH', {month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'});
                    var html = '<div class="upd-entry-date"><i class="fa-solid fa-calendar-day"></i> ' + dateStr + '</div>';
                    if (upd.note) html += '<div class="upd-entry-note">' + escHtml(upd.note) + '</div>';
                    if (upd.photos && upd.photos.length > 0) {
                        html += '<div class="upd-entry-photos">';
                        upd.photos.forEach(function(url, pi) {
                            html += '<img src="' + url + '" onclick="openLightbox(' + JSON.stringify(upd.photos) + ',' + pi + ')">';
                        });
                        html += '</div>';
                    }
                    html += '<div class="upd-comments-section">'
                          + '<div class="upd-comments-title"><i class="fa-solid fa-comments"></i> Resident Comments</div>'
                          + '<div class="upd-comments-list-' + idx + '" style="font-size:12px;color:var(--text-muted);font-style:italic;">Loading…</div>'
                          + '</div>';
                    div.innerHTML = html;
                    prevList.appendChild(div);
                    var commentContainer = div.querySelector('.upd-comments-list-' + idx);
                    loadCommentsForUpdate(d.id, idx, commentContainer);
                });
                prevSection.style.display = 'block';
            } else { prevSection.style.display = 'none'; }

            selectedFiles = [];
            renderPreviews();
            document.getElementById('upd-photo-input').value = '';
            handleStatusChange(d.status);
            document.getElementById('updateOverlay').classList.add('show');
            return;
        }

        var delBtn = e.target.closest('.btn-delete-complaint');
        if (delBtn) {
            pendingDeleteId = delBtn.dataset.id;
            document.getElementById('dc-ref').textContent = delBtn.dataset.ref;
            document.getElementById('deleteConfirmOverlay').classList.add('show');
            return;
        }

        var chatBtn = e.target.closest('.btn-open-chat');
        if (chatBtn) {
            currentChatComplaintId = chatBtn.dataset.id;
            document.getElementById('chat-modal-title').textContent = chatBtn.dataset.name;
            document.getElementById('chat-modal-sub').textContent   = chatBtn.dataset.ref;
            document.getElementById('chatOverlay').classList.add('show');
            loadChatMessages();
            clearInterval(chatPollTimer);
            chatPollTimer = setInterval(loadChatMessages, 8000);
            return;
        }

        if (e.target === document.getElementById('descModal'))          document.getElementById('descModal').classList.remove('show');
        if (e.target === document.getElementById('updateOverlay'))      closeUpdate();
        if (e.target === document.getElementById('saveConfirmOverlay')) document.getElementById('saveConfirmOverlay').classList.remove('show');
        if (e.target === document.getElementById('deleteConfirmOverlay')) { document.getElementById('deleteConfirmOverlay').classList.remove('show'); pendingDeleteId = null; }
        if (e.target === document.getElementById('chatOverlay'))        closeChatModal();
    });

    function closeUpdate() { document.getElementById('updateOverlay').classList.remove('show'); }

    document.getElementById('dc-confirm-btn').addEventListener('click', function() {
        if (!pendingDeleteId) return;
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';
        fetch(updateBaseUrl + '/' + pendingDeleteId, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('deleteConfirmOverlay').classList.remove('show');
            if (data.success) {
                document.getElementById('flash-area').innerHTML =
                    '<div class="alert-success"><i class="fa-solid fa-circle-check"></i> ' + data.message + '</div>';
                setTimeout(function() { window.location.reload(); }, 1000);
            } else {
                alert('Error: ' + (data.message || 'Could not delete.'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-trash"></i> Yes, Delete';
            }
        })
        .catch(function(err) {
            alert('Network error: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> Yes, Delete';
        });
    });

    function appendPhotos(input) {
        Array.from(input.files).forEach(function(f) {
            if (!selectedFiles.some(function(x) { return x.name===f.name && x.size===f.size; })) selectedFiles.push(f);
        });
        renderPreviews();
        input.value = '';
    }

    function renderPreviews() {
        var grid  = document.getElementById('photoPreviewGrid');
        var wrap  = document.getElementById('newPhotosWrap');
        var badge = document.getElementById('newPhotoCount');
        grid.innerHTML = '';
        if (selectedFiles.length === 0) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';
        badge.textContent  = selectedFiles.length;
        selectedFiles.forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.className = 'photo-thumb';
                div.innerHTML = '<img src="' + e.target.result + '"><button type="button" class="remove-btn" onclick="removePhoto(' + index + ')">×</button>';
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removePhoto(index) { selectedFiles.splice(index, 1); renderPreviews(); }

    function openSaveConfirm() {
        var status = document.getElementById('upd-status').value;
        if (status === 'Cancelled') {
            var reason = document.getElementById('upd-cancel-reason').value.trim();
            if (!reason) {
                document.getElementById('upd-cancel-reason').focus();
                document.getElementById('upd-cancel-reason').style.borderColor = '#ef4444';
                setTimeout(function(){ document.getElementById('upd-cancel-reason').style.borderColor = ''; }, 2000);
                return;
            }
        }
        document.getElementById('saveConfirmOverlay').classList.add('show');
    }

    document.getElementById('sc-confirm-btn').addEventListener('click', function() {
        document.getElementById('saveConfirmOverlay').classList.remove('show');
        doSave();
    });

    async function doSave() {
        var complaintId = document.getElementById('upd-complaint-id').value;
        if (!complaintId) { alert('Error: Complaint ID missing.'); return; }
        var btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
        var formData = new FormData(document.getElementById('updForm'));
        selectedFiles.forEach(function(file) { formData.append('progress_photos[]', file); });
        try {
            var response = await fetch(updateBaseUrl + '/' + complaintId, {
                method: 'POST', body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            var data = await response.json();
            if (data.success) {
                closeUpdate();
                document.getElementById('flash-area').innerHTML =
                    '<div class="alert-success"><i class="fa-solid fa-circle-check"></i> ' + data.message + '</div>';
                setTimeout(function() { window.location.reload(); }, 1200);
            } else {
                alert('Error: ' + (data.message || 'Something went wrong.'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save changes';
            }
        } catch (err) {
            alert('Network error: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save changes';
        }
    }

    function closeChatModal() {
        document.getElementById('chatOverlay').classList.remove('show');
        clearInterval(chatPollTimer);
        currentChatComplaintId = null;
        refreshMsgBadges();
    }

    function loadChatMessages() {
        if (!currentChatComplaintId) return;
        fetch(msgBaseUrl + '/' + currentChatComplaintId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            renderChatMessages(data.messages || []);
            var badge = document.getElementById('msgbadge-' + currentChatComplaintId);
            if (badge) badge.style.display = 'none';
        })
        .catch(function(){});
    }

    function renderChatMessages(messages) {
        var container = document.getElementById('chat-messages');
        if (!messages.length) {
            container.innerHTML = '<div class="chat-empty"><i class="fa-solid fa-comments" style="font-size:28px;margin-bottom:8px;display:block;opacity:.4;"></i>No messages yet. Start the conversation!</div>';
            return;
        }
        var html = '';
        messages.forEach(function(m) {
            var isMine = m.sender_role === 'admin';
            var label  = isMine ? 'You (Admin)' : 'Resident';
            var time   = new Date(m.created_at).toLocaleString('en-PH', {month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
            html += '<div class="chat-msg-row ' + (isMine ? 'mine' : 'theirs') + '">' +
                '<div class="chat-msg-sender">' + escHtml(label) + '</div>' +
                '<div class="chat-bubble">' + escHtml(m.message) + '</div>' +
                '<div class="chat-msg-time">' + time + '</div>' +
                '</div>';
        });
        container.innerHTML = html;
        container.scrollTop = container.scrollHeight;
    }

    function sendAdminMessage() {
        var input = document.getElementById('chat-input');
        var text  = input.value.trim();
        if (!text || !currentChatComplaintId) return;
        var sendBtn = document.getElementById('chat-send-btn');
        sendBtn.disabled = true;
        input.value = '';
        fetch(msgBaseUrl + '/' + currentChatComplaintId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: JSON.stringify({ message: text })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            sendBtn.disabled = false;
            if (data.success) loadChatMessages();
        })
        .catch(function() { sendBtn.disabled = false; });
    }

    function refreshMsgBadges() {
        fetch(adminUnreadUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(function(r) { return r.json(); })
        .then(function(counts) {
            Object.keys(counts).forEach(function(cid) {
                var badge = document.getElementById('msgbadge-' + cid);
                if (!badge) return;
                if (counts[cid] > 0) { badge.textContent = counts[cid]; badge.style.display = 'inline-flex'; }
                else badge.style.display = 'none';
            });
        })
        .catch(function(){});
    }

    refreshMsgBadges();
    setInterval(refreshMsgBadges, 10000);
</script>
</body>
</html>