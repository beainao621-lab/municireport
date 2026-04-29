{{-- resources/views/resident/messages.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages – MuniciReport</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --sidebar-w: 250px;
            --brand-deep: #08519C; --brand-mid: #3182BD; --brand-teal: #6BAED6; --brand-pale: #9ECAE1;
            --bg-page: #EFF3FF; --bg-surface: #ffffff; --bg-raised: #F8FAFF; --bg-input: #EFF3FF;
            --text-primary: #0a2a4a; --text-muted: #4a6fa5; --border: #C6DBEF;
        }
        @media (prefers-color-scheme: dark) {
            :root { --bg-page: #0d1b2a; --bg-surface: #0f2035; --bg-raised: #162840; --bg-input: #1a3050; --text-primary: #e2e8f0; --text-muted: #7fb3d3; --border: #1e3a5f; }
        }
        html, body { height: 100%; overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-page); color: var(--text-primary); display: flex; min-height: 100vh; }

        .sidebar { width: var(--sidebar-w); background: #08519C; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform .28s; }
        .brand { display: flex; align-items: center; gap: 11px; padding: 22px 20px 18px; border-bottom: 1.5px solid rgba(255,255,255,0.15); }
        .brand-icon { width: 40px; height: 40px; background: linear-gradient(135deg,#3182BD,#9ECAE1); border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .brand-name { font-size: 14px; font-weight: 700; color: #fff; } .brand-sub { font-size: 11px; color: #9ECAE1; margin-top: 2px; }
        .nav-section { padding: 16px 12px 8px; flex: 1; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13.5px; font-weight: 500; color: #C6DBEF; text-decoration: none; border-radius: 10px; border-left: 3px solid transparent; transition: all .15s; margin-bottom: 3px; }
        .nav-item:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #9ECAE1; font-weight: 600; }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .nav-badge { background: #ef4444; color: white; border-radius: 999px; font-size: 10px; font-weight: 700; padding: 2px 7px; margin-left: auto; }
        .overlay { display: none; position: fixed; inset: 0; background: rgba(8,81,156,0.4); z-index: 150; }
        .overlay.show { display: block; }

        .main { margin-left: var(--sidebar-w); width: calc(100% - var(--sidebar-w)); flex: 1; display: flex; flex-direction: column; }
        .content { flex: 1; padding: 28px 32px; }
        .page-title { font-size: 26px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 4px; }
        .page-sub { font-size: 14px; color: var(--text-muted); margin-bottom: 24px; }

        /* Thread list */
        .thread-list { display: flex; flex-direction: column; gap: 12px; }
        .thread-card { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; }
        .thread-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; cursor: pointer; transition: background .12s; gap: 12px; }
        .thread-header:hover { background: var(--bg-raised); }
        .thread-ref { font-size: 12px; font-weight: 700; color: var(--brand-mid); }
        .thread-cat { font-size: 13px; font-weight: 600; color: var(--text-primary); margin-top: 2px; }
        .thread-preview { font-size: 12px; color: var(--text-muted); margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px; }
        .thread-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .unread-badge { background: var(--brand-mid); color: white; border-radius: 999px; font-size: 11px; font-weight: 700; padding: 3px 9px; }
        .thread-chevron { font-size: 12px; color: var(--text-muted); transition: transform .2s; }
        .thread-card.open .thread-chevron { transform: rotate(180deg); }

        /* Chat area */
        .chat-area { display: none; border-top: 1.5px solid var(--border); flex-direction: column; }
        .thread-card.open .chat-area { display: flex; }
        .messages-list { padding: 16px 20px; max-height: 360px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: var(--bg-raised); }
        .msg-row { display: flex; flex-direction: column; }
        .msg-row.mine { align-items: flex-end; }
        .msg-row.theirs { align-items: flex-start; }
        .msg-sender { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 3px; }
        .msg-bubble { max-width: 72%; padding: 10px 14px; border-radius: 14px; font-size: 13.5px; line-height: 1.55; word-break: break-word; }
        .msg-row.mine .msg-bubble { background: linear-gradient(135deg, #08519C, #3182BD); color: white; border-bottom-right-radius: 4px; }
        .msg-row.theirs .msg-bubble { background: var(--bg-surface); border: 1.5px solid var(--border); color: var(--text-primary); border-bottom-left-radius: 4px; }
        .msg-time { font-size: 10px; color: var(--text-muted); margin-top: 3px; }
        .msg-empty { text-align: center; padding: 30px; font-size: 13px; color: var(--text-muted); }
        .chat-input-row { display: flex; gap: 10px; padding: 14px 20px; background: var(--bg-surface); border-top: 1.5px solid var(--border); }
        .chat-input { flex: 1; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; font-family: 'Inter', sans-serif; background: var(--bg-input); color: var(--text-primary); outline: none; resize: none; min-height: 42px; max-height: 100px; line-height: 1.5; }
        .chat-input:focus { border-color: var(--brand-mid); background: var(--bg-surface); }
        .chat-send-btn { padding: 10px 20px; background: linear-gradient(135deg,#08519C,#3182BD); color: white; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600; font-family: 'Inter', sans-serif; cursor: pointer; align-self: flex-end; }
        .chat-send-btn:disabled { opacity: .6; cursor: not-allowed; }

        .empty-state { background: var(--bg-surface); border: 1.5px solid var(--border); border-radius: 14px; padding: 50px 20px; text-align: center; }
        .empty-icon { font-size: 36px; margin-bottom: 10px; opacity: 0.4; color: var(--text-muted); }
        .empty-state p { font-size: 14.5px; color: var(--text-muted); }

        @media(max-width:768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; width: 100%; }
            .content { padding: 16px; }
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
        <a href="{{ route('resident.complaints.create') }}" class="nav-item"><i class="fa-solid fa-pen-to-square"></i> File a Complaint</a>
        <a href="{{ route('resident.complaints.index') }}" class="nav-item"><i class="fa-solid fa-clipboard-list"></i> My Complaints</a>
        <a href="{{ route('resident.messages') }}" class="nav-item active">
            <i class="fa-solid fa-comments"></i> Messages
            @php
                $residentUnread = \App\Models\ComplaintMessage::where('sender_role','admin')
                    ->where('is_read', false)
                    ->whereHas('complaint', fn($q) => $q->where('user_id', Auth::id()))
                    ->count();
            @endphp
            @if($residentUnread > 0)
                <span class="nav-badge">{{ $residentUnread }}</span>
            @endif
        </a>
    </div>
</aside>

<div class="main">
    <x-topbar role="resident" />
    <div class="content">
        <h1 class="page-title">Messages</h1>
        <p class="page-sub">Your conversations with the Mayor's Office regarding your complaints.</p>

        @if($complaints->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-comments"></i></div>
                <p>No messages yet. The Mayor's Office will message you here when they update your complaint.</p>
            </div>
        @else
            <div class="thread-list">
                @foreach($complaints as $complaint)
                @php
                    $msgs = $complaint->messages;
                    $lastMsg = $msgs->last();
                    $unreadCount = $msgs->where('sender_role','admin')->where('is_read', false)->count();
                @endphp
                <div class="thread-card" id="thread-{{ $complaint->id }}">
                    <div class="thread-header" onclick="toggleThread({{ $complaint->id }}, this)">
                        <div style="flex:1;min-width:0;">
                            <div class="thread-ref"><i class="fa-solid fa-hashtag" style="font-size:10px;"></i> {{ $complaint->reference_number }} · {{ $complaint->category }}</div>
                            <div class="thread-cat">{{ Str::limit($complaint->description, 55) }}</div>
                            @if($lastMsg)
                                <div class="thread-preview">
                                    {{ $lastMsg->sender_role === 'admin' ? 'Mayor\'s Office' : 'You' }}: {{ Str::limit($lastMsg->message, 60) }}
                                </div>
                            @endif
                        </div>
                        <div class="thread-right">
                            @if($unreadCount > 0)
                                <span class="unread-badge" id="unread-badge-{{ $complaint->id }}">{{ $unreadCount }}</span>
                            @else
                                <span class="unread-badge" id="unread-badge-{{ $complaint->id }}" style="display:none;">0</span>
                            @endif
                            <i class="fa-solid fa-chevron-down thread-chevron"></i>
                        </div>
                    </div>
                    <div class="chat-area" id="chat-{{ $complaint->id }}">
                        <div class="messages-list" id="msgs-{{ $complaint->id }}">
                            <div class="msg-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading…</div>
                        </div>
                        <div class="chat-input-row">
                            <textarea class="chat-input" id="input-{{ $complaint->id }}" placeholder="Type your message…" rows="1"
                                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage({{ $complaint->id }});}"></textarea>
                            <button class="chat-send-btn" onclick="sendMessage({{ $complaint->id }})">
                                <i class="fa-solid fa-paper-plane"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var BASE_MSG_URL = '{{ url("messages") }}';
var openThreads = {};

function openSidebar()  { document.getElementById('sidebar').classList.add('open');  document.getElementById('overlay').classList.add('show'); }
function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('show'); }

function toggleThread(complaintId, headerEl) {
    var card = document.getElementById('thread-' + complaintId);
    var isOpen = card.classList.contains('open');
    card.classList.toggle('open', !isOpen);
    if (!isOpen) {
        loadMessages(complaintId);
    }
}

function loadMessages(complaintId) {
    fetch(BASE_MSG_URL + '/' + complaintId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        renderMessages(complaintId, data.messages);
        // Hide unread badge
        var badge = document.getElementById('unread-badge-' + complaintId);
        if (badge) badge.style.display = 'none';
    });
}

function renderMessages(complaintId, messages) {
    var container = document.getElementById('msgs-' + complaintId);
    if (!messages || messages.length === 0) {
        container.innerHTML = '<div class="msg-empty">No messages yet. Send the first message!</div>';
        return;
    }
    var html = '';
    messages.forEach(function(m) {
        var isMine = m.sender_role === 'resident';
        var senderLabel = isMine ? 'You' : "Mayor's Office";
        var time = new Date(m.created_at).toLocaleString('en-PH', {month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
        html += '<div class="msg-row ' + (isMine ? 'mine' : 'theirs') + '">' +
            '<div class="msg-sender">' + senderLabel + '</div>' +
            '<div class="msg-bubble">' + escHtml(m.message) + '</div>' +
            '<div class="msg-time">' + time + '</div>' +
            '</div>';
    });
    container.innerHTML = html;
    container.scrollTop = container.scrollHeight;
}

function sendMessage(complaintId) {
    var input = document.getElementById('input-' + complaintId);
    var text  = input.value.trim();
    if (!text) return;
    input.value = '';
    fetch(BASE_MSG_URL + '/' + complaintId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: JSON.stringify({ message: text })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) loadMessages(complaintId);
    });
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Poll for new messages every 10s on open threads
setInterval(function() {
    document.querySelectorAll('.thread-card.open').forEach(function(card) {
        var id = card.id.replace('thread-', '');
        loadMessages(parseInt(id));
    });
}, 10000);
</script>
</body>
</html>