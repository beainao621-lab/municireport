{{-- resources/views/components/topbar.blade.php --}}
@props(['role' => 'resident'])

@php
    /** @var \App\Models\User $user */
    $user        = Auth::user();
    $initials    = $user->getInitials();
    $picUrl      = $user->getProfilePictureUrl();
    $isAdmin     = $role === 'admin';
    $unreadCount = $user->unreadNotificationsCount();
@endphp

<style>
/* ── Topbar shell ─────────────────────────────────────── */
.tb-bar{background:#fff;border-bottom:1.5px solid #C6DBEF;padding:0 32px;height:64px;display:flex;align-items:center;gap:12px;position:sticky;top:0;z-index:100;font-family:'DM Sans',sans-serif}
.tb-hamburger{display:none;background:none;border:none;cursor:pointer;color:#4a6fa5;padding:4px;flex-shrink:0}
.tb-badge{font-family:'DM Sans',sans-serif;font-size:11px;font-weight:700;color:#4a6fa5;background:#EFF3FF;border:1.5px solid #C6DBEF;border-radius:999px;padding:3px 10px}
.tb-right{display:flex;align-items:center;gap:10px;margin-left:auto}
.tb-notif-wrap{position:relative}
.tb-notif-btn{position:relative;width:40px;height:40px;border-radius:50%;border:1.5px solid #C6DBEF;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#4a6fa5;transition:background .15s}
.tb-notif-btn:hover{background:#EFF3FF}
.tb-notif-count{position:absolute;top:4px;right:4px;min-width:18px;height:18px;background:#ef4444;color:#fff;font-family:'DM Sans',sans-serif;font-size:9px;font-weight:700;border-radius:999px;border:2px solid #fff;display:none;align-items:center;justify-content:center;padding:0 3px}
.tb-notif-count.show{display:flex}

/* ── Notification Panel ── */
.tb-notif-panel{display:none;position:fixed;top:64px;right:16px;width:370px;max-width:calc(100vw - 32px);background:#fff;border:1.5px solid #C6DBEF;border-radius:16px;box-shadow:0 16px 50px rgba(8,81,156,.18);z-index:500;overflow:hidden;flex-direction:column;font-family:'DM Sans',sans-serif}
.tb-notif-panel.open{display:flex}
.tb-np-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px 10px;border-bottom:1px solid #EFF3FF;flex-wrap:wrap;gap:6px}
.tb-np-title{font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;color:#0a2a4a}
.tb-np-actions{display:flex;gap:6px;align-items:center;flex-wrap:wrap}
.tb-np-btn{font-family:'DM Sans',sans-serif;font-size:11px;font-weight:600;color:#3182BD;background:none;border:none;cursor:pointer;padding:4px 7px;border-radius:6px;transition:background .12s;display:inline-flex;align-items:center;gap:4px;white-space:nowrap}
.tb-np-btn:hover{background:#EFF3FF}
.tb-np-btn.del-mode-btn{color:#ef4444}
.tb-np-body{max-height:320px;overflow-y:auto;overflow-x:hidden}
.tb-np-empty{padding:36px 20px;text-align:center;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:13px}
.tb-np-empty i{font-size:28px;color:#9ECAE1;display:block;margin-bottom:10px}
.tb-notif-item{display:flex;align-items:flex-start;gap:10px;padding:12px 14px;border-bottom:1px solid #EFF3FF;cursor:pointer;transition:background .12s;position:relative;font-family:'DM Sans',sans-serif}
.tb-notif-item:last-child{border-bottom:none}
.tb-notif-item:hover{background:#F8FAFF}
.tb-notif-item.unread{background:#EFF3FF}
.tb-notif-item.unread:hover{background:#e4edfc}
.tb-notif-select{display:none;margin-right:0;flex-shrink:0;margin-top:2px;width:16px;height:16px;cursor:pointer;accent-color:#ef4444}
.tb-notif-select.show{display:block}
.tb-ni-icon{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0}
.tb-ni-icon.info{background:#EFF3FF;color:#3182BD}
.tb-ni-icon.success{background:#D1FAE5;color:#065F46}
.tb-ni-icon.warning{background:#FEF3C7;color:#B45309}
.tb-ni-content{flex:1;min-width:0;overflow:hidden}
.tb-ni-title{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#0a2a4a;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tb-ni-msg{font-family:'DM Sans',sans-serif;font-size:12px;color:#4a6fa5;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;word-break:break-word}
.tb-ni-time{font-family:'DM Sans',sans-serif;font-size:11px;color:#9ECAE1;margin-top:3px}
.tb-ni-close{position:absolute;top:10px;right:10px;background:none;border:none;color:#9ECAE1;font-size:12px;cursor:pointer;opacity:0;transition:opacity .12s;line-height:1;padding:2px}
.tb-notif-item:hover .tb-ni-close{opacity:1}
.tb-ni-close:hover{color:#ef4444}
.delete-mode .tb-ni-close{display:none !important}
.tb-np-footer{padding:10px 14px;border-top:1px solid #EFF3FF;display:flex;gap:8px}

/* Delete confirm bar inside panel */
.tb-np-del-bar{display:none;background:#FEF2F2;border-top:1.5px solid #FECACA;padding:10px 14px}
.tb-np-del-bar.show{display:block}
.tb-np-del-bar-msg{font-family:'DM Sans',sans-serif;font-size:12px;color:#B91C1C;margin-bottom:8px;display:flex;align-items:center;gap:6px}
.tb-np-del-btns{display:flex;gap:8px}
.tb-np-del-yes{font-family:'DM Sans',sans-serif;padding:7px 14px;background:#ef4444;color:#fff;border:none;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px}

.tb-profile-wrap{position:relative}
.tb-user-pill{display:flex;align-items:center;gap:9px;padding:5px 14px 5px 5px;border:1.5px solid #C6DBEF;border-radius:999px;background:#fff;cursor:pointer;transition:background .15s;user-select:none}
.tb-user-pill:hover{background:#EFF3FF}
.tb-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#08519C,#6BAED6);color:#fff;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0}
.tb-avatar img{width:100%;height:100%;object-fit:cover}
.tb-user-name{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;color:#0a2a4a}
.tb-chevron{font-size:10px;color:#4a6fa5;transition:transform .2s}
.tb-user-pill.open .tb-chevron{transform:rotate(180deg)}
.tb-profile-menu{display:none;position:absolute;top:calc(100% + 10px);right:0;width:210px;background:#fff;border:1.5px solid #C6DBEF;border-radius:14px;box-shadow:0 12px 40px rgba(8,81,156,.15);z-index:500;overflow:hidden;padding:6px;font-family:'DM Sans',sans-serif}
.tb-profile-menu.open{display:block}
.tb-pm-item{display:flex;align-items:center;gap:10px;padding:11px 13px;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:500;color:#0a2a4a;border-radius:9px;cursor:pointer;transition:background .12s;border:none;background:none;width:100%;text-align:left}
.tb-pm-item:hover{background:#EFF3FF;color:#08519C}
.tb-pm-item i{width:16px;text-align:center;font-size:14px;color:#4a6fa5}
.tb-pm-item:hover i{color:#08519C}
.tb-pm-sep{height:1px;background:#EFF3FF;margin:4px 0}
.tb-pm-item.logout{color:#ef4444}
.tb-pm-item.logout i{color:#ef4444}
.tb-pm-item.logout:hover{background:#FEF2F2}
.tb-modal-overlay{display:none;position:fixed;inset:0;background:rgba(8,81,156,.38);z-index:800;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(2px)}
.tb-modal-overlay.open{display:flex}
.tb-modal-box{background:#fff;border-radius:20px;padding:32px;width:100%;box-shadow:0 24px 70px rgba(8,81,156,.28);max-height:90vh;overflow-y:auto;font-family:'DM Sans',sans-serif}
#tb-edit-modal .tb-modal-box{max-width:500px}
.tb-modal-title{font-family:'DM Serif Display',serif;font-size:21px;color:#0a2a4a;margin-bottom:4px}
.tb-modal-sub{font-family:'DM Sans',sans-serif;font-size:13px;color:#4a6fa5;margin-bottom:24px}
.tb-pic-row{display:flex;align-items:center;gap:18px;margin-bottom:24px;padding-bottom:20px;border-bottom:1.5px solid #EFF3FF}
.tb-pic-preview{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#08519C,#6BAED6);display:flex;align-items:center;justify-content:center;color:#fff;font-family:'DM Sans',sans-serif;font-size:22px;font-weight:700;overflow:hidden;flex-shrink:0;border:3px solid #C6DBEF}
.tb-pic-preview img{width:100%;height:100%;object-fit:cover}
.tb-pic-info{flex:1}
.tb-pic-info p{font-family:'DM Sans',sans-serif;font-size:13px;color:#4a6fa5;margin-bottom:8px}
.tb-pic-upload-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border:1.5px solid #3182BD;color:#3182BD;background:#EFF3FF;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:12.5px;font-weight:600;cursor:pointer;transition:all .15s}
.tb-pic-upload-btn:hover{background:#3182BD;color:#fff}
.tb-field{display:flex;flex-direction:column;margin-bottom:15px}
.tb-field label{font-family:'DM Sans',sans-serif;font-size:10.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#4a6fa5;margin-bottom:7px}
.tb-field input{width:100%;padding:11px 14px;border:1.5px solid #C6DBEF;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:13.5px;background:#EFF3FF;color:#0a2a4a;outline:none;transition:border-color .2s,background .2s}
.tb-field input:focus{border-color:#3182BD;background:#fff;box-shadow:0 0 0 3px rgba(49,130,189,.1)}
.tb-pw-section{border-top:1.5px solid #EFF3FF;padding-top:18px;margin-top:6px;margin-bottom:16px}
.tb-pw-label{font-family:'DM Sans',sans-serif;font-size:10.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#3182BD;margin-bottom:14px;display:flex;align-items:center;gap:6px}
.tb-pw-input-wrap{position:relative}
.tb-pw-input-wrap input{padding-right:42px}
.tb-pw-eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ECAE1;font-size:15px;padding:2px;transition:color .15s}
.tb-pw-eye:hover{color:#3182BD}
.tb-pw-strength{margin-top:6px;display:none}
.tb-pw-strength.show{display:block}
.tb-pw-strength-bar{height:4px;border-radius:999px;background:#E5E7EB;overflow:hidden;margin-bottom:4px}
.tb-pw-strength-fill{height:100%;border-radius:999px;transition:width .3s,background .3s;width:0%}
.tb-pw-strength-label{font-family:'DM Sans',sans-serif;font-size:11px;color:#4a6fa5}
.tb-verify-step{background:#EFF3FF;border:1.5px solid #C6DBEF;border-radius:12px;padding:16px;margin-bottom:16px}
.tb-verify-title{font-family:'DM Sans',sans-serif;font-size:12px;font-weight:700;color:#08519C;margin-bottom:12px;display:flex;align-items:center;gap:6px}
.tb-verify-info{font-family:'DM Sans',sans-serif;font-size:12px;color:#4a6fa5;margin-bottom:10px}
.tb-verify-err{font-family:'DM Sans',sans-serif;font-size:12px;color:#B91C1C;margin-top:6px;display:none}
.tb-verify-err.show{display:block}
.tb-verify-btns{display:flex;gap:8px;margin-top:12px}
.tb-verify-btn{flex:1;padding:9px;border:none;border-radius:8px;background:linear-gradient(135deg,#08519C,#3182BD);color:#fff;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;cursor:pointer}
.tb-verify-btn:disabled{opacity:.6;cursor:not-allowed}
.tb-verify-cancel{flex:1;padding:9px;border:1.5px solid #C6DBEF;border-radius:8px;background:#fff;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer}
.tb-newpw-section{display:none}
.tb-newpw-section.show{display:block}
.tb-verified-badge{display:inline-flex;align-items:center;gap:6px;font-family:'DM Sans',sans-serif;font-size:11.5px;font-weight:600;color:#065F46;background:#D1FAE5;border:1px solid #A7F3D0;border-radius:6px;padding:4px 10px;margin-bottom:14px}
.tb-modal-btns{display:flex;gap:10px;margin-top:4px}
.tb-btn-cancel{flex:1;padding:12px;border:1.5px solid #C6DBEF;border-radius:10px;background:#fff;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer}
.tb-btn-save{flex:2;padding:12px;border:none;border-radius:10px;background:linear-gradient(135deg,#08519C,#3182BD,#6BAED6);color:#fff;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 5px 18px rgba(8,81,156,.25)}
.tb-btn-save:hover{opacity:.9}
.tb-btn-save:disabled{opacity:.6;cursor:not-allowed}
.tb-alert{padding:10px 14px;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:13px;margin-bottom:14px;display:none}
.tb-alert.show{display:block}
.tb-alert.success{background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0}
.tb-alert.error{background:#FEE2E2;color:#B91C1C;border:1px solid #FECACA}
#tb-acc-modal .tb-modal-box{max-width:420px}
.tb-acc-section{margin-bottom:22px}
.tb-acc-label{font-family:'DM Sans',sans-serif;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#4a6fa5;margin-bottom:12px}
.tb-dark-toggle{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:#EFF3FF;border-radius:12px;border:1.5px solid #C6DBEF}
.tb-dark-info{font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:#0a2a4a}
.tb-dark-sub{font-family:'DM Sans',sans-serif;font-size:11.5px;color:#4a6fa5;margin-top:2px}
.tb-toggle-sw{position:relative;width:48px;height:26px;flex-shrink:0}
.tb-toggle-sw input{opacity:0;width:0;height:0}
.tb-toggle-sw .slider{position:absolute;inset:0;background:#C6DBEF;border-radius:999px;cursor:pointer;transition:background .2s}
.tb-toggle-sw .slider::before{content:'';position:absolute;width:20px;height:20px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 2px 6px rgba(0,0,0,.15)}
.tb-toggle-sw input:checked + .slider{background:#08519C}
.tb-toggle-sw input:checked + .slider::before{transform:translateX(22px)}
.tb-font-row{display:flex;gap:8px;align-items:stretch}
.tb-font-btn{flex:1;padding:12px 0;border:1.5px solid #C6DBEF;border-radius:10px;background:#fff;color:#4a6fa5;font-weight:600;cursor:pointer;transition:all .2s;font-family:'DM Sans',sans-serif;text-align:center;line-height:1}
.tb-font-btn:hover{border-color:#3182BD;color:#3182BD;background:#EFF3FF}
.tb-font-btn.active{border-color:#08519C;background:#08519C;color:#fff}
#tb-fs-small{font-size:11px}
#tb-fs-medium{font-size:14px}
#tb-fs-large{font-size:18px}
.tb-acc-btns{display:flex;gap:10px;margin-top:4px}
.tb-acc-btn-cancel{flex:1;padding:12px;border:1.5px solid #C6DBEF;border-radius:10px;background:#fff;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer}
.tb-acc-btn-save{flex:2;padding:12px;border:none;border-radius:10px;background:linear-gradient(135deg,#08519C,#3182BD,#6BAED6);color:#fff;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 5px 18px rgba(8,81,156,.25)}
.tb-acc-btn-save:hover{opacity:.9}
#tb-logout-modal .tb-modal-box{max-width:400px;text-align:center}
.tb-logout-icon{width:64px;height:64px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:24px;color:#ef4444}
.tb-logout-title{font-family:'DM Serif Display',serif;font-size:22px;color:#0a2a4a;margin-bottom:8px}
.tb-logout-msg{font-family:'DM Sans',sans-serif;font-size:14px;color:#4a6fa5;margin-bottom:28px;line-height:1.6}
.tb-logout-btns{display:flex;gap:10px}
.tb-btn-stay{flex:1;padding:13px;border:1.5px solid #C6DBEF;border-radius:10px;background:#fff;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer}
.tb-btn-logout{flex:1;padding:13px;border:none;border-radius:10px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(239,68,68,.3)}
#tb-save-confirm-modal .tb-modal-box{max-width:400px;text-align:center}
.tb-sc-icon{width:64px;height:64px;border-radius:50%;background:#EFF3FF;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:24px;color:#3182BD}
.tb-sc-title{font-family:'DM Serif Display',serif;font-size:22px;color:#0a2a4a;margin-bottom:8px}
.tb-sc-msg{font-family:'DM Sans',sans-serif;font-size:14px;color:#4a6fa5;margin-bottom:28px;line-height:1.6}
.tb-sc-btns{display:flex;gap:10px}
.tb-sc-cancel{flex:1;padding:13px;border:1.5px solid #C6DBEF;border-radius:10px;background:#fff;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer}
.tb-sc-confirm{flex:2;padding:13px;border:none;border-radius:10px;background:linear-gradient(135deg,#08519C,#3182BD,#6BAED6);color:#fff;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 5px 18px rgba(8,81,156,.25)}

/* Delete confirmation modal for notifications */
#tb-notif-del-confirm-modal .tb-modal-box{max-width:400px;text-align:center}
.tb-del-conf-icon{width:64px;height:64px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:24px;color:#ef4444}
.tb-del-conf-title{font-family:'DM Serif Display',serif;font-size:22px;color:#0a2a4a;margin-bottom:8px}
.tb-del-conf-msg{font-family:'DM Sans',sans-serif;font-size:14px;color:#4a6fa5;margin-bottom:28px;line-height:1.6}
.tb-del-conf-btns{display:flex;gap:10px}
.tb-del-conf-cancel{flex:1;padding:13px;border:1.5px solid #C6DBEF;border-radius:10px;background:#fff;color:#4a6fa5;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer}
.tb-del-conf-yes{flex:1;padding:13px;border:none;border-radius:10px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(239,68,68,.3)}

html.fs-small  { font-size: 13px !important; }
html.fs-medium { font-size: 15px !important; }
html.fs-large  { font-size: 17px !important; }
html.fs-small  *, html.fs-small  body { font-size: inherit !important; }
html.fs-medium *, html.fs-medium body { font-size: inherit !important; }
html.fs-large  *, html.fs-large  body { font-size: inherit !important; }

/* ══════════════════════════════════════════
   DARK MODE — ZERO white surfaces
   ══════════════════════════════════════════ */
body.dark-mode { background: #0d1b2a !important; color: #e2e8f0 !important; }

/* Topbar */
body.dark-mode .tb-bar { background: rgba(8,20,40,0.88) !important; border-color: #1e3a5f; backdrop-filter: blur(12px); }
body.dark-mode .tb-badge { background: rgba(15,39,68,0.6); border-color: #1e3a5f; color: #9ECAE1; }
body.dark-mode .tb-notif-btn { background: rgba(15,39,68,0.6) !important; border-color: #1e3a5f; color: #9ECAE1; }
body.dark-mode .tb-notif-btn:hover { background: rgba(21,45,74,0.8) !important; }
body.dark-mode .tb-user-pill { background: rgba(15,39,68,0.6) !important; border-color: #1e3a5f; }
body.dark-mode .tb-user-pill:hover { background: rgba(21,45,74,0.8) !important; }
body.dark-mode .tb-user-name { color: #e2e8f0; }
body.dark-mode .tb-chevron { color: #9ECAE1; }

/* Sidebar */
body.dark-mode .sidebar { background: rgba(7,26,46,0.92) !important; backdrop-filter: blur(12px); }
body.dark-mode .sidebar *:not(a):not(i) { background: transparent !important; }

/* Notification panel */
body.dark-mode .tb-notif-panel { background: rgba(8,20,40,0.97) !important; border-color: #1e3a5f !important; backdrop-filter: blur(16px); }
body.dark-mode .tb-np-head { background: transparent !important; border-color: #1e3a5f !important; }
body.dark-mode .tb-np-title { color: #e2e8f0; }
body.dark-mode .tb-np-body { background: transparent !important; }
body.dark-mode .tb-np-footer { background: transparent !important; border-color: #1e3a5f !important; }
body.dark-mode .tb-np-empty { background: transparent !important; color: #6BAED6; }
body.dark-mode .tb-notif-item { background: transparent !important; border-color: #1e3a5f !important; }
body.dark-mode .tb-notif-item.unread { background: rgba(10,32,56,0.55) !important; }
body.dark-mode .tb-notif-item:hover { background: rgba(21,45,74,0.65) !important; }
body.dark-mode .tb-ni-title { color: #e2e8f0; }
body.dark-mode .tb-ni-msg { color: #9ECAE1; }
body.dark-mode .tb-ni-time { color: #4a6fa5; }
body.dark-mode .tb-ni-icon.info { background: rgba(10,32,56,0.6); color: #6BAED6; }
body.dark-mode .tb-ni-icon.success { background: rgba(6,60,40,0.5); color: #6ee7b7; }
body.dark-mode .tb-ni-icon.warning { background: rgba(60,40,6,0.5); color: #fcd34d; }
body.dark-mode .tb-ni-close { color: #4a6fa5; }
body.dark-mode .tb-ni-close:hover { color: #ef4444; }
body.dark-mode .tb-np-del-bar { background: rgba(80,10,10,0.75) !important; border-color: #7f1d1d; }
body.dark-mode .tb-np-del-bar-msg { color: #fca5a5; }
body.dark-mode .tb-np-del-yes { background: #dc2626 !important; }
body.dark-mode .tb-np-btn { color: #6BAED6; }
body.dark-mode .tb-np-btn:hover { background: rgba(21,45,74,0.6) !important; }
body.dark-mode .tb-np-btn.del-mode-btn { color: #f87171; }

/* Profile menu */
body.dark-mode .tb-profile-menu { background: rgba(8,20,40,0.97) !important; border-color: #1e3a5f !important; backdrop-filter: blur(16px); }
body.dark-mode .tb-pm-item { color: #e2e8f0; background: transparent !important; }
body.dark-mode .tb-pm-item:hover { background: rgba(21,45,74,0.7) !important; color: #6BAED6 !important; }
body.dark-mode .tb-pm-item i { color: #9ECAE1; }
body.dark-mode .tb-pm-item:hover i { color: #6BAED6; }
body.dark-mode .tb-pm-sep { background: #1e3a5f; }
body.dark-mode .tb-pm-item.logout { color: #f87171; }
body.dark-mode .tb-pm-item.logout i { color: #f87171; }
body.dark-mode .tb-pm-item.logout:hover { background: rgba(80,10,10,0.4) !important; }

/* Modal overlay + box */
body.dark-mode .tb-modal-overlay { background: rgba(0,0,0,0.7) !important; }
body.dark-mode .tb-modal-box { background: rgba(8,20,40,0.98) !important; color: #e2e8f0; border: 1.5px solid #1e3a5f !important; backdrop-filter: blur(20px); }
body.dark-mode .tb-modal-title, body.dark-mode .tb-logout-title, body.dark-mode .tb-sc-title, body.dark-mode .tb-del-conf-title { color: #e2e8f0 !important; }
body.dark-mode .tb-modal-sub, body.dark-mode .tb-logout-msg, body.dark-mode .tb-sc-msg, body.dark-mode .tb-del-conf-msg { color: #9ECAE1 !important; background: transparent !important; }
body.dark-mode #tb-edit-form { background: transparent !important; }
body.dark-mode .tb-pic-row { border-color: #1e3a5f !important; background: transparent !important; }
body.dark-mode .tb-pic-info p { color: #9ECAE1; }
body.dark-mode .tb-pic-upload-btn { background: rgba(10,32,56,0.7) !important; border-color: #3182BD !important; color: #6BAED6 !important; }
body.dark-mode .tb-pic-upload-btn:hover { background: #08519C !important; color: #fff !important; }
body.dark-mode .tb-field label { color: #9ECAE1; }
body.dark-mode .tb-field input { background: rgba(10,32,56,0.7) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }
body.dark-mode .tb-field input:focus { background: rgba(15,39,68,0.9) !important; border-color: #3182BD !important; }
body.dark-mode .tb-pw-section { border-color: #1e3a5f !important; background: transparent !important; }
body.dark-mode .tb-pw-label { color: #6BAED6; }
body.dark-mode .tb-pw-strength-bar { background: rgba(10,32,56,0.7) !important; }
body.dark-mode .tb-verify-step { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; }
body.dark-mode .tb-verify-title { color: #6BAED6; }
body.dark-mode .tb-verify-info { color: #9ECAE1; }
body.dark-mode .tb-verify-cancel { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; color: #9ECAE1 !important; }
body.dark-mode .tb-verified-badge { background: rgba(6,78,59,0.4) !important; border-color: #065f46 !important; color: #6ee7b7 !important; }
body.dark-mode .tb-verify-err { color: #fca5a5; }
body.dark-mode .tb-btn-cancel, body.dark-mode .tb-btn-stay, body.dark-mode .tb-sc-cancel, body.dark-mode .tb-acc-btn-cancel, body.dark-mode .tb-verify-cancel, body.dark-mode .tb-del-conf-cancel { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; color: #9ECAE1 !important; }
body.dark-mode .tb-btn-logout { background: linear-gradient(135deg,#dc2626,#991b1b) !important; }
body.dark-mode .tb-logout-icon, body.dark-mode .tb-del-conf-icon { background: rgba(80,10,10,0.5) !important; color: #f87171; }
body.dark-mode .tb-sc-icon { background: rgba(10,32,56,0.6) !important; color: #6BAED6; }
body.dark-mode .tb-dark-toggle { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; }
body.dark-mode .tb-dark-info { color: #e2e8f0 !important; }
body.dark-mode .tb-dark-sub { color: #9ECAE1 !important; }
body.dark-mode .tb-acc-label { color: #9ECAE1 !important; }
body.dark-mode .tb-font-btn { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; color: #9ECAE1 !important; }
body.dark-mode .tb-font-btn.active { background: #08519C !important; border-color: #08519C !important; color: #fff !important; }
body.dark-mode .tb-font-btn:hover:not(.active) { background: rgba(21,45,74,0.7) !important; border-color: #3182BD !important; color: #6BAED6 !important; }
body.dark-mode .tb-alert.success { background: rgba(6,78,59,0.35) !important; border-color: #065f46 !important; color: #6ee7b7 !important; }
body.dark-mode .tb-alert.error { background: rgba(80,10,10,0.4) !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }

/* ── Page cards & surfaces ── */
body.dark-mode .form-card,
body.dark-mode .stat-card,
body.dark-mode .card,
body.dark-mode .table-card,
body.dark-mode .report-card,
body.dark-mode .report-section,
body.dark-mode .stat-pill,
body.dark-mode .resolved-banner,
body.dark-mode .complaint-card,
body.dark-mode .modal-box,
body.dark-mode .update-modal,
body.dark-mode .modal,
body.dark-mode .save-confirm-box,
body.dark-mode .empty-state { background: rgba(10,22,42,0.8) !important; border-color: #1e3a5f !important; backdrop-filter: blur(8px); }
body.dark-mode .main { background: transparent !important; }
body.dark-mode .content { background: transparent !important; }

body.dark-mode .resolved-banner { background: rgba(6,60,40,0.3) !important; border-color: #065F46 !important; }
body.dark-mode .resolved-banner span { color: #6ee7b7 !important; }
body.dark-mode .resolved-banner i { color: #6ee7b7 !important; }
body.dark-mode .stat-icon { background: rgba(10,32,56,0.6) !important; }

body.dark-mode input[type="text"],
body.dark-mode input[type="email"],
body.dark-mode input[type="tel"],
body.dark-mode input[type="password"],
body.dark-mode input[type="search"],
body.dark-mode select,
body.dark-mode textarea { background: rgba(10,32,56,0.7) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }
body.dark-mode input::placeholder, body.dark-mode textarea::placeholder { color: #4a6fa5 !important; }
body.dark-mode select option { background: #0d1b2a; color: #e2e8f0; }

body.dark-mode table, body.dark-mode .table { background: transparent !important; }
body.dark-mode thead, body.dark-mode thead tr, body.dark-mode thead th { background: rgba(10,26,48,0.7) !important; border-color: #1e3a5f !important; color: #9ECAE1 !important; }
body.dark-mode tbody tr { background: transparent !important; border-color: #1e3a5f !important; }
body.dark-mode tbody tr:hover { background: rgba(21,45,74,0.5) !important; }
body.dark-mode td { border-color: #1e3a5f !important; color: #e2e8f0 !important; background: transparent !important; }

body.dark-mode .btn-sm { background: rgba(10,32,56,0.7) !important; border-color: #1e3a5f !important; color: #9ECAE1 !important; }
body.dark-mode .btn-sm:hover { background: rgba(21,45,74,0.8) !important; border-color: #3182BD !important; color: #6BAED6 !important; }
body.dark-mode .btn-sm.primary { background: linear-gradient(135deg,#08519C,#3182BD) !important; color: #fff !important; border-color: transparent !important; }
body.dark-mode .search-input, body.dark-mode .filter-select { background: rgba(10,32,56,0.7) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }

body.dark-mode .modal-overlay { background: rgba(0,0,0,0.7) !important; }
body.dark-mode .modal, body.dark-mode .modal-box, body.dark-mode .update-modal, body.dark-mode .save-confirm-box { background: rgba(8,20,40,0.98) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }
body.dark-mode .modal h2, body.dark-mode .modal-box h3, body.dark-mode .update-modal h2, body.dark-mode .sc-title { color: #e2e8f0 !important; }
body.dark-mode .modal p, body.dark-mode .modal-meta, body.dark-mode .modal-desc, body.dark-mode .snippet, body.dark-mode .sc-msg { color: #9ECAE1 !important; background: transparent !important; }
body.dark-mode .modal input[type="text"], body.dark-mode .modal input[type="email"], body.dark-mode .modal input[type="password"] { background: rgba(10,32,56,0.7) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }
body.dark-mode .field input, body.dark-mode .field select, body.dark-mode .field textarea { background: rgba(10,32,56,0.7) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }
body.dark-mode .btn-modal-cancel, body.dark-mode .btn-cancel-m, body.dark-mode .modal-close-btn, body.dark-mode .sc-cancel { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; color: #9ECAE1 !important; }
body.dark-mode .sc-icon { background: rgba(10,32,56,0.6) !important; color: #6BAED6 !important; }
body.dark-mode .upd-entry { background: rgba(10,26,48,0.6) !important; border-color: #1e3a5f !important; }
body.dark-mode .upd-entry-date { color: #9ECAE1 !important; }
body.dark-mode .upd-entry-note { color: #e2e8f0 !important; }
body.dark-mode .prev-updates-title { color: #6BAED6 !important; }
body.dark-mode .new-update-title { color: #6ee7b7 !important; }
body.dark-mode .upload-area { background: rgba(10,32,56,0.6) !important; border-color: #1e3a5f !important; }
body.dark-mode .upload-area:hover { background: rgba(21,45,74,0.7) !important; border-color: #3182BD !important; }
body.dark-mode .modal-value { background: rgba(10,32,56,0.6) !important; color: #e2e8f0 !important; }
body.dark-mode .modal-label { color: #9ECAE1 !important; }
body.dark-mode .progress-entry { background: rgba(10,26,48,0.6) !important; border-color: #1e3a5f !important; }
body.dark-mode .progress-date { color: #9ECAE1 !important; }
body.dark-mode .progress-note { color: #e2e8f0 !important; }

body.dark-mode .report-card { background: rgba(10,22,42,0.8) !important; border-color: #1e3a5f !important; color: #e2e8f0 !important; }
body.dark-mode .report-card:hover { background: rgba(21,45,74,0.7) !important; border-color: #3182BD !important; }
body.dark-mode .report-card.active-card { background: rgba(8,81,156,0.2) !important; border-color: #3182BD !important; }
body.dark-mode .report-type { color: #e2e8f0 !important; }
body.dark-mode .report-period { color: #9ECAE1 !important; }
body.dark-mode .report-heading { color: #e2e8f0 !important; }
body.dark-mode .report-period-label { color: #9ECAE1 !important; }
body.dark-mode .report-header { border-color: #1e3a5f !important; background: transparent !important; }

body.dark-mode .tb-toggle-sw .slider::before { background: #e2e8f0; }

body.dark-mode .tb-np-body::-webkit-scrollbar { width: 5px; }
body.dark-mode .tb-np-body::-webkit-scrollbar-track { background: transparent; }
body.dark-mode .tb-np-body::-webkit-scrollbar-thumb { background: #1e3a5f; border-radius: 999px; }

/* Page-level text colors for dark mode */
body.dark-mode .page-title { color: #e2e8f0 !important; }
body.dark-mode .page-sub { color: #9ECAE1 !important; }
body.dark-mode .stat-label { color: #9ECAE1 !important; }
body.dark-mode .stat-value { color: #e2e8f0 !important; }
body.dark-mode .card-title { color: #e2e8f0 !important; }
body.dark-mode .cat-name { color: #e2e8f0 !important; }
body.dark-mode .cat-count { color: #e2e8f0 !important; }
body.dark-mode .bar-track { background: rgba(10,32,56,0.6) !important; }
body.dark-mode .activity-item { border-color: #1e3a5f !important; }
body.dark-mode .activity-desc { color: #e2e8f0 !important; }
body.dark-mode .activity-ref { color: #9ECAE1 !important; }

/* ── RESPONSIVE ── */
@media(max-width:768px){
    .tb-bar{padding:0 16px}
    .tb-hamburger{display:flex !important}
    .tb-user-name{display:none}

    /* Notification panel: full width, anchored below topbar */
    .tb-notif-panel{
        position:fixed;
        top:64px;
        left:8px;
        right:8px;
        width:auto;
        max-width:100%;
        border-radius:14px;
    }

    /* Shrink header actions to fit */
    .tb-np-head{padding:12px 12px 8px;gap:4px}
    .tb-np-actions{gap:4px}
    .tb-np-btn{font-size:10px;padding:3px 6px;gap:3px}

    /* Tighter items on mobile */
    .tb-notif-item{padding:10px 12px;gap:8px}
    .tb-ni-icon{width:30px;height:30px;font-size:12px;flex-shrink:0}
    .tb-ni-title{font-size:12px}
    .tb-ni-msg{font-size:11px}
    .tb-ni-time{font-size:10px}
    .tb-ni-close{right:8px;font-size:11px}

    /* Del bar */
    .tb-np-del-bar{padding:8px 12px}
    .tb-np-del-bar-msg{font-size:11px}
    .tb-np-del-yes{font-size:11px;padding:6px 12px}

    /* Scrollable body height on mobile */
    .tb-np-body{max-height:55vh}

    /* Footer */
    .tb-np-footer{padding:8px 12px}
}

@media(max-width:380px){
    .tb-np-btn span, .tb-np-btn{font-size:9.5px;padding:3px 5px}
    .tb-badge{font-size:10px;padding:2px 8px}
}
</style>

<header class="tb-bar" id="tb-topbar">
    <button class="tb-hamburger" onclick="openSidebar()">
        <i class="fa-solid fa-bars" style="font-size:20px;"></i>
    </button>
    <span class="tb-badge">{{ $isAdmin ? 'Admin Panel' : 'Resident Portal' }}</span>
    <div class="tb-right">
        {{-- Notification Bell --}}
        <div class="tb-notif-wrap">
            <button class="tb-notif-btn" id="tb-notif-btn" onclick="tbToggleNotif(event)" title="Notifications">
                <i class="fa-solid fa-bell" style="font-size:17px;"></i>
                <span class="tb-notif-count {{ $unreadCount > 0 ? 'show' : '' }}" id="tb-notif-count">{{ $unreadCount }}</span>
            </button>
            <div class="tb-notif-panel" id="tb-notif-panel">
                <div class="tb-np-head">
                    <span class="tb-np-title"><i class="fa-solid fa-bell" style="font-size:13px;margin-right:6px;color:#3182BD;"></i>Notifications</span>
                    <div class="tb-np-actions">
                        <button class="tb-np-btn" id="tb-mark-all-btn" onclick="tbMarkAllRead()">
                            <i class="fa-solid fa-check-double"></i> Mark all read
                        </button>
                        <button class="tb-np-btn del-mode-btn" id="tb-del-mode-btn" onclick="tbToggleDeleteMode()">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="tb-np-body" id="tb-np-body">
                    <div class="tb-np-empty" id="tb-np-empty">
                        <i class="fa-solid fa-bell-slash"></i>
                        No notifications yet.
                    </div>
                    <div id="tb-notif-list"></div>
                </div>
                <div class="tb-np-del-bar" id="tb-np-del-bar">
                    <div class="tb-np-del-bar-msg"><i class="fa-solid fa-triangle-exclamation"></i> <span id="tb-del-bar-count">0</span> notification(s) selected</div>
                    <div class="tb-np-del-btns">
                        <button class="tb-np-del-yes" onclick="tbConfirmDeleteSelected()">
                            <i class="fa-solid fa-trash-can"></i> Delete Selected
                        </button>
                    </div>
                </div>
                <div class="tb-np-footer" id="tb-np-footer" style="display:none;">
                    <button class="tb-np-btn del-mode-btn" id="tb-select-all-btn" onclick="tbSelectAll()" style="font-size:12px;display:none;">
                        <i class="fa-solid fa-square-check"></i> Select all
                    </button>
                </div>
            </div>
        </div>

        {{-- Profile Pill --}}
        <div class="tb-profile-wrap">
            <div class="tb-user-pill" id="tb-user-pill" onclick="tbToggleProfile(event)">
                <div class="tb-avatar" id="tb-avatar-wrap">
                    @if($picUrl)
                        <img src="{{ $picUrl }}" alt="Profile">
                    @else
                        <span id="tb-avatar-initials">{{ $initials }}</span>
                    @endif
                </div>
                <span class="tb-user-name" id="tb-user-name-text">{{ $user->name }}</span>
                <i class="fa-solid fa-chevron-down tb-chevron"></i>
            </div>
            <div class="tb-profile-menu" id="tb-profile-menu">
                <button class="tb-pm-item" onclick="tbOpenEditProfile()">
                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                </button>
                <button class="tb-pm-item" onclick="tbOpenAccessibility()">
                    <i class="fa-solid fa-universal-access"></i> Accessibility
                </button>
                <div class="tb-pm-sep"></div>
                <button class="tb-pm-item logout" onclick="tbOpenLogout()">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </div>
        </div>
    </div>
</header>

{{-- Edit Profile Modal --}}
<div class="tb-modal-overlay" id="tb-edit-modal">
    <div class="tb-modal-box">
        <p class="tb-modal-title">Edit Profile</p>
        <p class="tb-modal-sub">Update your account information and photo.</p>
        <div class="tb-alert" id="tb-edit-alert"></div>
        <div class="tb-pic-row">
            <div class="tb-pic-preview" id="tb-pic-preview">
                @if($picUrl)
                    <img src="{{ $picUrl }}" alt="">
                @else
                    <span>{{ $initials }}</span>
                @endif
            </div>
            <div class="tb-pic-info">
                <p>Profile photo (JPG, PNG, WEBP · max 3 MB)</p>
                <label class="tb-pic-upload-btn">
                    <i class="fa-solid fa-camera"></i> Change photo
                    <input type="file" id="tb-pic-input" accept="image/jpg,image/jpeg,image/png,image/webp" style="display:none;" onchange="tbPreviewPic(this)">
                </label>
            </div>
        </div>
        <form id="tb-edit-form" enctype="multipart/form-data">
            @csrf
            <div class="tb-field">
                <label>Full Name</label>
                <input type="text" name="name" id="tb-inp-name" value="{{ $user->name }}" required>
            </div>
            <div class="tb-field">
                <label>Email Address</label>
                <input type="email" name="email" id="tb-inp-email" value="{{ $user->email }}" required>
            </div>
            <div class="tb-field">
                <label>Phone Number</label>
                <input type="text" name="phone" id="tb-inp-phone" value="{{ $user->phone ?? '' }}" placeholder="09XX-XXX-XXXX">
            </div>
            <div class="tb-field">
                <label>Barangay / Location</label>
                <input type="text" name="barangay" id="tb-inp-barangay" value="{{ $user->barangay ?? $user->location ?? '' }}" placeholder="e.g. Brgy. Poblacion">
            </div>
            <div class="tb-pw-section">
                <div class="tb-pw-label"><i class="fa-solid fa-lock"></i> Change Password (optional)</div>
                <div id="tb-verify-wrap">
                    <div class="tb-verify-step">
                        <div class="tb-verify-title"><i class="fa-solid fa-shield-halved"></i> Verify your identity</div>
                        <p class="tb-verify-info">Enter your current password to unlock password change.</p>
                        <div class="tb-field" style="margin-bottom:0">
                            <label>Current Password</label>
                            <div class="tb-pw-input-wrap">
                                <input type="password" id="tb-inp-curpw" placeholder="Enter your current password">
                                <button type="button" class="tb-pw-eye" onclick="tbTogglePwVis('tb-inp-curpw',this)" tabindex="-1">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="tb-verify-err" id="tb-verify-err"><i class="fa-solid fa-circle-exclamation"></i> <span id="tb-verify-err-msg">Incorrect password. Please try again.</span></div>
                        <div class="tb-verify-btns">
                            <button type="button" class="tb-verify-btn" id="tb-verify-btn" onclick="tbVerifyCurrentPassword()">
                                <i class="fa-solid fa-unlock"></i> Verify
                            </button>
                            <button type="button" class="tb-verify-cancel" onclick="tbCancelVerify()">Cancel</button>
                        </div>
                    </div>
                </div>
                <div class="tb-newpw-section" id="tb-newpw-section">
                    <div class="tb-verified-badge"><i class="fa-solid fa-circle-check"></i> Identity verified — set your new password</div>
                    <input type="hidden" name="current_password" id="tb-inp-curpw-hidden">
                    <div class="tb-field">
                        <label>New Password</label>
                        <div class="tb-pw-input-wrap">
                            <input type="password" name="new_password" id="tb-inp-newpw" placeholder="Minimum 8 characters" oninput="tbCheckPwStrength(this.value)">
                            <button type="button" class="tb-pw-eye" onclick="tbTogglePwVis('tb-inp-newpw',this)" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="tb-pw-strength" id="tb-pw-strength">
                            <div class="tb-pw-strength-bar"><div class="tb-pw-strength-fill" id="tb-pw-strength-fill"></div></div>
                            <span class="tb-pw-strength-label" id="tb-pw-strength-label"></span>
                        </div>
                    </div>
                    <div class="tb-field">
                        <label>Confirm New Password</label>
                        <div class="tb-pw-input-wrap">
                            <input type="password" name="new_password_confirmation" id="tb-inp-confirmpw" placeholder="Repeat new password">
                            <button type="button" class="tb-pw-eye" onclick="tbTogglePwVis('tb-inp-confirmpw',this)" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" style="font-family:'DM Sans',sans-serif;font-size:12px;color:#ef4444;background:none;border:none;cursor:pointer;padding:0;margin-bottom:4px;" onclick="tbCancelVerify()">
                        <i class="fa-solid fa-xmark"></i> Cancel password change
                    </button>
                </div>
            </div>
            <div class="tb-modal-btns">
                <button type="button" class="tb-btn-cancel" onclick="tbCloseModal('tb-edit-modal')">Cancel</button>
                <button type="button" class="tb-btn-save" onclick="tbOpenSaveConfirm()">
                    <i class="fa-solid fa-floppy-disk"></i> Save changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Save Confirm Modal --}}
<div class="tb-modal-overlay" id="tb-save-confirm-modal">
    <div class="tb-modal-box">
        <div class="tb-sc-icon"><i class="fa-solid fa-floppy-disk"></i></div>
        <p class="tb-sc-title">Save Changes?</p>
        <p class="tb-sc-msg">Are you sure you want to save your profile changes?</p>
        <div class="tb-sc-btns">
            <button class="tb-sc-cancel" onclick="tbCloseModal('tb-save-confirm-modal')">Cancel</button>
            <button class="tb-sc-confirm" id="tb-sc-confirm-btn"><i class="fa-solid fa-check"></i> Yes, Save</button>
        </div>
    </div>
</div>

{{-- Accessibility Modal --}}
<div class="tb-modal-overlay" id="tb-acc-modal">
    <div class="tb-modal-box">
        <p class="tb-modal-title">Accessibility</p>
        <p class="tb-modal-sub">Personalise your viewing experience.</p>
        <div class="tb-acc-section">
            <div class="tb-acc-label">Theme</div>
            <div class="tb-dark-toggle">
                <div>
                    <div class="tb-dark-info"><i class="fa-solid fa-moon"></i> Dark Mode</div>
                    <div class="tb-dark-sub">Transparent dark theme — easier on the eyes</div>
                </div>
                <label class="tb-toggle-sw">
                    <input type="checkbox" id="tb-dark-toggle" onchange="tbPreviewDark(this.checked)">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        <div class="tb-acc-section">
            <div class="tb-acc-label">Font Size</div>
            <div class="tb-font-row">
                <button class="tb-font-btn" id="tb-fs-small"  onclick="tbPreviewFont('small')">Small</button>
                <button class="tb-font-btn active" id="tb-fs-medium" onclick="tbPreviewFont('medium')">Medium</button>
                <button class="tb-font-btn" id="tb-fs-large"  onclick="tbPreviewFont('large')">Large</button>
            </div>
        </div>
        <div class="tb-acc-btns">
            <button type="button" class="tb-acc-btn-cancel" onclick="tbCancelAccessibility()">Cancel</button>
            <button type="button" class="tb-acc-btn-save" onclick="tbSaveAccessibility()">
                <i class="fa-solid fa-floppy-disk"></i> Save
            </button>
        </div>
    </div>
</div>

{{-- Logout Confirm Modal --}}
<div class="tb-modal-overlay" id="tb-logout-modal">
    <div class="tb-modal-box">
        <div class="tb-logout-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
        <p class="tb-logout-title">Are you sure?</p>
        <p class="tb-logout-msg">You will be logged out of your account.<br>Any unsaved changes will be lost.</p>
        <div class="tb-logout-btns">
            <button class="tb-btn-stay" onclick="tbCloseModal('tb-logout-modal')">Stay logged in</button>
            <form method="POST" action="{{ route('logout') }}" style="flex:1;">
                @csrf
                <button type="submit" class="tb-btn-logout" style="width:100%;"><i class="fa-solid fa-right-from-bracket"></i> Yes, logout</button>
            </form>
        </div>
    </div>
</div>

{{-- Notification Delete Confirm Modal --}}
<div class="tb-modal-overlay" id="tb-notif-del-confirm-modal">
    <div class="tb-modal-box">
        <div class="tb-del-conf-icon"><i class="fa-solid fa-trash-can"></i></div>
        <p class="tb-del-conf-title">Delete Notifications?</p>
        <p class="tb-del-conf-msg" id="tb-del-conf-msg">Are you sure you want to delete the selected notifications? This cannot be undone.</p>
        <div class="tb-del-conf-btns">
            <button class="tb-del-conf-cancel" onclick="tbCloseDelConfirm()"><i class="fa-solid fa-xmark"></i> Cancel</button>
            <button class="tb-del-conf-yes" id="tb-del-conf-yes-btn"><i class="fa-solid fa-trash-can"></i> Yes, Delete</button>
        </div>
    </div>
</div>

<script>
(function(){
    var PROFILE_UPDATE_URL  = '{{ route("profile.update") }}';
    var VERIFY_PASSWORD_URL = '{{ route("profile.verify-password") }}';
    var NOTIF_INDEX_URL     = '{{ route("notifications.index") }}';
    var NOTIF_READ_URL      = '{{ url("notifications") }}';
    var CSRF                = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var tbPendingDark      = null;
    var tbPendingFont      = null;
    var tbPasswordVerified = false;

    // Apply saved settings immediately on load
    (function tbApplySaved(){
        var savedDark = localStorage.getItem('tb-dark') === '1';
        var savedFont = localStorage.getItem('tb-font') || 'medium';
        if (savedDark) document.body.classList.add('dark-mode');
        document.documentElement.classList.remove('fs-small','fs-medium','fs-large');
        document.documentElement.classList.add('fs-' + savedFont);
        tbUpdateFontBtns(savedFont);
    })();

    // ── Modals ───────────────────────────────────────────────
    window.tbCloseModal = function(id){ document.getElementById(id).classList.remove('open'); };
    function tbOpenModal(id){
        document.querySelectorAll('.tb-modal-overlay').forEach(function(m){ m.classList.remove('open'); });
        document.getElementById(id).classList.add('open');
    }

    window.tbToggleProfile = function(e){
        e.stopPropagation();
        var pill   = document.getElementById('tb-user-pill');
        var menu   = document.getElementById('tb-profile-menu');
        var isOpen = menu.classList.contains('open');
        closeAllDropdowns();
        if (!isOpen){ menu.classList.add('open'); pill.classList.add('open'); }
    };
    window.tbOpenEditProfile   = function(){ closeAllDropdowns(); tbResetEditModal(); tbOpenModal('tb-edit-modal'); };
    window.tbOpenAccessibility = function(){ closeAllDropdowns(); tbOpenModal('tb-acc-modal'); tbInitAcc(); };
    window.tbOpenLogout        = function(){ closeAllDropdowns(); tbOpenModal('tb-logout-modal'); };
    window.tbOpenSaveConfirm   = function(){ tbOpenModal('tb-save-confirm-modal'); };

    document.getElementById('tb-sc-confirm-btn').addEventListener('click', function(){
        tbCloseModal('tb-save-confirm-modal');
        tbDoSave();
    });

    window.tbToggleNotif = function(e){
        e.stopPropagation();
        var panel  = document.getElementById('tb-notif-panel');
        var isOpen = panel.classList.contains('open');
        closeAllDropdowns();
        if (!isOpen){ panel.classList.add('open'); tbLoadNotifs(); }
    };

    function closeAllDropdowns(){
        document.getElementById('tb-profile-menu').classList.remove('open');
        document.getElementById('tb-user-pill').classList.remove('open');
        var panel = document.getElementById('tb-notif-panel');
        if (!tbDeleteMode) panel.classList.remove('open');
    }

    document.addEventListener('click', function(e){
        if (!e.target.closest('.tb-notif-wrap') && !e.target.closest('.tb-profile-wrap') && !e.target.closest('.tb-modal-overlay')) {
            document.getElementById('tb-profile-menu').classList.remove('open');
            document.getElementById('tb-user-pill').classList.remove('open');
            if (!tbDeleteMode) {
                document.getElementById('tb-notif-panel').classList.remove('open');
            }
        }
        if (e.target.classList.contains('tb-modal-overlay')) e.target.classList.remove('open');
    });
    document.addEventListener('keydown', function(e){
        if(e.key==='Escape'){
            if (!tbDeleteMode) {
                document.getElementById('tb-notif-panel').classList.remove('open');
            }
            document.getElementById('tb-profile-menu').classList.remove('open');
            document.getElementById('tb-user-pill').classList.remove('open');
        }
    });

    function tbResetEditModal(){
        tbPasswordVerified = false;
        document.getElementById('tb-verify-wrap').style.display = 'block';
        document.getElementById('tb-newpw-section').classList.remove('show');
        document.getElementById('tb-inp-curpw').value = '';
        document.getElementById('tb-inp-newpw')        && (document.getElementById('tb-inp-newpw').value = '');
        document.getElementById('tb-inp-confirmpw')    && (document.getElementById('tb-inp-confirmpw').value = '');
        document.getElementById('tb-inp-curpw-hidden') && (document.getElementById('tb-inp-curpw-hidden').value = '');
        document.getElementById('tb-verify-err').classList.remove('show');
        document.getElementById('tb-pw-strength').classList.remove('show');
    }

    window.tbVerifyCurrentPassword = function(){
        var pw  = document.getElementById('tb-inp-curpw').value;
        var btn = document.getElementById('tb-verify-btn');
        var err = document.getElementById('tb-verify-err');
        if (!pw){
            document.getElementById('tb-verify-err-msg').textContent = 'Please enter your current password.';
            err.classList.add('show'); return;
        }
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying…';
        err.classList.remove('show');
        fetch(VERIFY_PASSWORD_URL, {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
            body: JSON.stringify({current_password: pw})
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-unlock"></i> Verify';
            if (data.success){
                tbPasswordVerified = true;
                document.getElementById('tb-inp-curpw-hidden').value = pw;
                document.getElementById('tb-verify-wrap').style.display = 'none';
                document.getElementById('tb-newpw-section').classList.add('show');
                document.getElementById('tb-inp-newpw').focus();
            } else {
                document.getElementById('tb-verify-err-msg').textContent = data.message || 'Incorrect password. Please try again.';
                err.classList.add('show');
                document.getElementById('tb-inp-curpw').select();
            }
        })
        .catch(function(){
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-unlock"></i> Verify';
            document.getElementById('tb-verify-err-msg').textContent = 'Network error. Please try again.';
            err.classList.add('show');
        });
    };

    document.getElementById('tb-inp-curpw').addEventListener('keydown', function(e){
        if (e.key==='Enter'){ e.preventDefault(); tbVerifyCurrentPassword(); }
    });

    window.tbCancelVerify = function(){
        tbPasswordVerified = false;
        document.getElementById('tb-verify-wrap').style.display = 'block';
        document.getElementById('tb-newpw-section').classList.remove('show');
        document.getElementById('tb-inp-curpw').value = '';
        document.getElementById('tb-inp-newpw')        && (document.getElementById('tb-inp-newpw').value = '');
        document.getElementById('tb-inp-confirmpw')    && (document.getElementById('tb-inp-confirmpw').value = '');
        document.getElementById('tb-inp-curpw-hidden') && (document.getElementById('tb-inp-curpw-hidden').value = '');
        document.getElementById('tb-verify-err').classList.remove('show');
        document.getElementById('tb-pw-strength').classList.remove('show');
    };

    // ── Notifications ────────────────────────────────────────
    var tbNotifData   = [];
    var tbDeleteMode  = false;
    var tbSelectedIds = new Set();

    var tbIconMap = {
        info:    {icon:'fa-circle-info',  cls:'info'},
        success: {icon:'fa-circle-check', cls:'success'},
        warning: {icon:'fa-bell',         cls:'warning'},
        error:   {icon:'fa-circle-xmark', cls:'warning'},
        default: {icon:'fa-circle-dot',   cls:'info'}
    };

    function tbLoadNotifs(){
        fetch(NOTIF_INDEX_URL, {headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
            .then(function(r){ return r.json(); })
            .then(function(data){
                tbNotifData = data.notifications || [];
                tbRenderNotifs();
                tbUpdateBadge(data.unread_count || 0);
            }).catch(function(){});
    }

    function tbRenderNotifs(){
        var list      = document.getElementById('tb-notif-list');
        var empty     = document.getElementById('tb-np-empty');
        var footer    = document.getElementById('tb-np-footer');
        var selAllBtn = document.getElementById('tb-select-all-btn');
        list.innerHTML = '';

        if (!tbNotifData.length){
            empty.style.display  = 'block';
            footer.style.display = 'none';
            return;
        }
        empty.style.display  = 'none';
        footer.style.display = 'flex';
        selAllBtn.style.display = tbDeleteMode ? 'inline-flex' : 'none';

        tbNotifData.forEach(function(n){
            var iconCfg = tbIconMap[n.type] || tbIconMap['default'];
            var item = document.createElement('div');
            item.className = 'tb-notif-item' + (n.is_read ? '' : ' unread');
            item.dataset.id = n.id;
            item.innerHTML =
                '<input type="checkbox" class="tb-notif-select' + (tbDeleteMode ? ' show' : '') + '" data-id="' + n.id + '">' +
                '<div class="tb-ni-icon ' + iconCfg.cls + '"><i class="fa-solid ' + iconCfg.icon + '"></i></div>' +
                '<div class="tb-ni-content">' +
                    '<div class="tb-ni-title">' + tbEsc(n.title)   + '</div>' +
                    '<div class="tb-ni-msg">'   + tbEsc(n.message) + '</div>' +
                    '<div class="tb-ni-time">'  + tbRelTime(n.created_at) + '</div>' +
                '</div>' +
                '<button class="tb-ni-close" onclick="tbDeleteOne(event,' + n.id + ')" title="Dismiss"><i class="fa-solid fa-xmark"></i></button>';

            var cb = item.querySelector('.tb-notif-select');
            if (tbSelectedIds.has(n.id)) cb.checked = true;

            cb.addEventListener('click', function(e){
                e.stopPropagation();
                if (this.checked) tbSelectedIds.add(n.id);
                else tbSelectedIds.delete(n.id);
                tbUpdateDelBarCount();
            });

            item.addEventListener('click', function(e){
                if (e.target.type==='checkbox' || e.target.closest('.tb-ni-close')) return;
                if (tbDeleteMode){
                    cb.checked = !cb.checked;
                    if (cb.checked) tbSelectedIds.add(n.id); else tbSelectedIds.delete(n.id);
                    tbUpdateDelBarCount();
                    return;
                }
                tbClickNotif(n.id, n.link);
            });
            list.appendChild(item);
        });

        document.getElementById('tb-notif-list').classList.toggle('delete-mode', tbDeleteMode);
    }

    function tbUpdateDelBarCount(){
        document.getElementById('tb-del-bar-count').textContent = tbSelectedIds.size;
    }

    window.tbClickNotif = function(id, link){
        fetch(NOTIF_READ_URL + '/' + id + '/read', {
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
        }).then(function(){
            if (link) window.location.href = link;
        }).catch(function(){
            if (link) window.location.href = link;
        });
    };

    window.tbDeleteOne = function(e, id){
        e.stopPropagation();
        fetch(NOTIF_READ_URL + '/' + id, {
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
        }).then(function(){
            tbNotifData = tbNotifData.filter(function(n){ return n.id !== id; });
            tbRenderNotifs();
            tbUpdateBadge(tbNotifData.filter(function(n){ return !n.is_read; }).length);
        });
    };

    window.tbMarkAllRead = function(){
        fetch(NOTIF_READ_URL + '/mark-all-read', {
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
        }).then(function(){
            tbNotifData.forEach(function(n){ n.is_read = true; });
            tbRenderNotifs(); tbUpdateBadge(0);
        });
    };

    window.tbToggleDeleteMode = function(){
        tbDeleteMode = !tbDeleteMode;
        tbSelectedIds.clear();
        tbUpdateDelBarCount();
        document.getElementById('tb-del-mode-btn').innerHTML = tbDeleteMode
            ? '<i class="fa-solid fa-xmark"></i> Cancel'
            : '<i class="fa-solid fa-trash"></i> Delete';
        document.getElementById('tb-np-del-bar').classList.toggle('show', tbDeleteMode);
        document.getElementById('tb-notif-panel').classList.add('open');
        tbRenderNotifs();
    };

    function tbExitDeleteMode(){
        tbDeleteMode = false;
        tbSelectedIds.clear();
        tbUpdateDelBarCount();
        document.getElementById('tb-del-mode-btn').innerHTML = '<i class="fa-solid fa-trash"></i> Delete';
        document.getElementById('tb-np-del-bar').classList.remove('show');
        tbRenderNotifs();
    }

    window.tbSelectAll = function(){
        var allSelected = tbSelectedIds.size === tbNotifData.length;
        tbSelectedIds.clear();
        if (!allSelected) tbNotifData.forEach(function(n){ tbSelectedIds.add(n.id); });
        tbUpdateDelBarCount();
        document.getElementById('tb-select-all-btn').innerHTML = allSelected
            ? '<i class="fa-solid fa-square-check"></i> Select all'
            : '<i class="fa-regular fa-square"></i> Deselect all';
        document.querySelectorAll('.tb-notif-select').forEach(function(c){ c.checked = !allSelected; });
    };

    window.tbConfirmDeleteSelected = function(){
        if (!tbSelectedIds.size) return;
        var count = tbSelectedIds.size;
        document.getElementById('tb-del-conf-msg').textContent =
            'Are you sure you want to delete ' + count + ' notification' + (count > 1 ? 's' : '') + '? This cannot be undone.';
        document.getElementById('tb-notif-del-confirm-modal').classList.add('open');
    };

    window.tbCloseDelConfirm = function(){
        document.getElementById('tb-notif-del-confirm-modal').classList.remove('open');
    };

    document.getElementById('tb-del-conf-yes-btn').addEventListener('click', function(){
        tbCloseDelConfirm();
        tbDoDeleteSelected();
    });

    function tbDoDeleteSelected(){
        if (!tbSelectedIds.size) return;
        var ids = Array.from(tbSelectedIds);
        fetch(NOTIF_READ_URL + '/delete-selected', {
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
            body: JSON.stringify({ids: ids})
        }).then(function(){
            tbNotifData = tbNotifData.filter(function(n){ return !ids.includes(n.id); });
            tbExitDeleteMode();
            tbUpdateBadge(tbNotifData.filter(function(n){ return !n.is_read; }).length);
        });
    }

    function tbUpdateBadge(count){
        var badge = document.getElementById('tb-notif-count');
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.toggle('show', count > 0);
    }

    // Real-time polling every 15s
    var tbLastUnread = {{ $unreadCount }};
    setInterval(function(){
        fetch(NOTIF_INDEX_URL, {headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
            .then(function(r){ return r.json(); })
            .then(function(data){
                var newCount = data.unread_count || 0;
                if (newCount > tbLastUnread){
                    var bell = document.getElementById('tb-notif-btn');
                    bell.style.transition = 'transform .1s';
                    bell.style.transform  = 'scale(1.35)';
                    setTimeout(function(){ bell.style.transform = 'scale(1)'; }, 200);
                }
                tbLastUnread = newCount;
                tbUpdateBadge(newCount);
                if (document.getElementById('tb-notif-panel').classList.contains('open')){
                    tbNotifData = data.notifications || [];
                    tbRenderNotifs();
                }
            }).catch(function(){});
    }, 15000);

    window.tbPreviewPic = function(input){
        if (!input.files || !input.files[0]) return;
        var reader = new FileReader();
        reader.onload = function(e){
            document.getElementById('tb-pic-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    };

    window.tbCheckPwStrength = function(val){
        var bar   = document.getElementById('tb-pw-strength');
        var fill  = document.getElementById('tb-pw-strength-fill');
        var label = document.getElementById('tb-pw-strength-label');
        if (!val){ bar.classList.remove('show'); return; }
        bar.classList.add('show');
        var score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        var configs = [
            {pct:'20%', bg:'#ef4444', text:'Very weak'},
            {pct:'40%', bg:'#f97316', text:'Weak'},
            {pct:'65%', bg:'#eab308', text:'Fair'},
            {pct:'85%', bg:'#22c55e', text:'Strong'},
            {pct:'100%',bg:'#16a34a', text:'Very strong'}
        ];
        var c = configs[score] || configs[0];
        fill.style.width = c.pct; fill.style.background = c.bg;
        label.textContent = c.text; label.style.color = c.bg;
    };

    window.tbTogglePwVis = function(inputId, btn){
        var inp  = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (inp.type === 'password'){ inp.type = 'text'; icon.className = 'fa-solid fa-eye-slash'; }
        else { inp.type = 'password'; icon.className = 'fa-solid fa-eye'; }
    };

    function tbDoSave(){
        var btn     = document.querySelector('#tb-edit-modal .tb-btn-save');
        var alertEl = document.getElementById('tb-edit-alert');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
        alertEl.className = 'tb-alert'; alertEl.style.display = 'none';

        var fd = new FormData(document.getElementById('tb-edit-form'));
        var picInput = document.getElementById('tb-pic-input');
        if (picInput.files && picInput.files[0]) fd.set('profile_picture', picInput.files[0]);
        fd.append('_method', 'PUT');

        fetch(PROFILE_UPDATE_URL, {
            method:'POST',
            headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':CSRF},
            body: fd
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (data.success){
                alertEl.textContent = data.message;
                alertEl.className = 'tb-alert success show';
                document.getElementById('tb-user-name-text').textContent = data.name;
                var avatarWrap = document.getElementById('tb-avatar-wrap');
                if (data.profile_picture_url){
                    avatarWrap.innerHTML = '<img src="' + data.profile_picture_url + '?t=' + Date.now() + '" style="width:100%;height:100%;object-fit:cover;" alt="Profile">';
                } else {
                    avatarWrap.innerHTML = '<span>' + data.initials + '</span>';
                }
                tbCancelVerify();
            } else {
                alertEl.textContent = data.message || 'Something went wrong.';
                alertEl.className = 'tb-alert error show';
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save changes';
        })
        .catch(function(err){
            alertEl.textContent = 'Network error: ' + err.message;
            alertEl.className = 'tb-alert error show';
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save changes';
        });
    }

    function tbInitAcc(){
        var savedDark = localStorage.getItem('tb-dark') === '1';
        var savedFont = localStorage.getItem('tb-font') || 'medium';
        tbPendingDark = savedDark;
        tbPendingFont = savedFont;
        document.getElementById('tb-dark-toggle').checked = savedDark;
        tbUpdateFontBtns(savedFont);
    }

    window.tbPreviewDark = function(on){
        tbPendingDark = on;
        document.body.classList.toggle('dark-mode', on);
    };

    window.tbPreviewFont = function(size){
        tbPendingFont = size;
        document.documentElement.classList.remove('fs-small','fs-medium','fs-large');
        document.documentElement.classList.add('fs-' + size);
        tbUpdateFontBtns(size);
    };

    function tbUpdateFontBtns(size){
        ['small','medium','large'].forEach(function(s){
            var btn = document.getElementById('tb-fs-'+s);
            if (btn) btn.classList.toggle('active', s === size);
        });
    }

    window.tbSaveAccessibility = function(){
        if (tbPendingDark !== null) localStorage.setItem('tb-dark', tbPendingDark ? '1' : '0');
        if (tbPendingFont !== null) localStorage.setItem('tb-font', tbPendingFont);
        tbCloseModal('tb-acc-modal');
    };

    window.tbCancelAccessibility = function(){
        var savedDark = localStorage.getItem('tb-dark') === '1';
        var savedFont = localStorage.getItem('tb-font') || 'medium';
        document.body.classList.toggle('dark-mode', savedDark);
        document.documentElement.classList.remove('fs-small','fs-medium','fs-large');
        document.documentElement.classList.add('fs-' + savedFont);
        document.getElementById('tb-dark-toggle').checked = savedDark;
        tbUpdateFontBtns(savedFont);
        tbPendingDark = null; tbPendingFont = null;
        tbCloseModal('tb-acc-modal');
    };

    function tbRelTime(dateStr){
        if (!dateStr) return '';
        var d = new Date(dateStr), now = new Date();
        var diff = Math.floor((now - d) / 1000);
        if (diff < 60)     return 'Just now';
        if (diff < 3600)   return Math.floor(diff/60)   + 'm ago';
        if (diff < 86400)  return Math.floor(diff/3600)  + 'h ago';
        if (diff < 604800) return Math.floor(diff/86400) + 'd ago';
        return d.toLocaleDateString('en-PH', {month:'short', day:'numeric'});
    }
    function tbEsc(str){
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    if (typeof openSidebar === 'undefined') window.openSidebar = function(){};
})();
</script>