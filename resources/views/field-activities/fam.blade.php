<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0f4c81">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Field Activities – FAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root{--brand:#0f4c81;--brand-mid:#1a6bb5;--brand-lite:#dbeafe;--accent:#00c896;--warn:#f59e0b;--danger:#ef4444;--purple:#7c3aed;--dark:#0d1b2a;--text:#1e293b;--muted:#64748b;--border:#e2e8f0;--surface:#f8fafc;--white:#fff;--shadow-sm:0 1px 4px rgba(0,0,0,.06);--shadow:0 4px 16px rgba(0,0,0,.08);--radius:10px;--radius-lg:16px}
        *,*::before,*::after{box-sizing:border-box}
        body{font-family:'IBM Plex Sans',system-ui,sans-serif;background:var(--surface);color:var(--text);padding-top:64px;padding-bottom:env(safe-area-inset-bottom);margin:0}
        .topnav{position:fixed;top:0;left:0;right:0;z-index:1000;height:64px;background:var(--brand);display:flex;align-items:center;padding:0 1.25rem;gap:.75rem;box-shadow:0 2px 12px rgba(0,0,0,.2)}
        .topnav .brand{font-weight:700;font-size:1rem;color:#fff;display:flex;align-items:center;gap:.5rem;cursor:pointer;text-decoration:none;flex-shrink:0}
        .topnav .brand-icon{width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1rem}
        .topnav-links{display:flex;align-items:center;gap:.25rem;list-style:none;margin:0;padding:0}
        .topnav-right{margin-left:auto;display:flex;align-items:center;gap:.5rem;flex-shrink:0}
        .topnav-btn{background:rgba(255,255,255,.12);border:none;color:#fff;width:36px;height:36px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:background .2s}
        .topnav-btn:hover{background:rgba(255,255,255,.22)}
        .avatar{width:32px;height:32px;border-radius:50%;background:var(--accent);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem}
        .mobile-nav{display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--white);border-top:1px solid var(--border);padding:6px 0 calc(6px + env(safe-area-inset-bottom));grid-template-columns:repeat(4,1fr)}
        .mob-nav-btn{display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px;cursor:pointer;font-size:.6rem;color:var(--muted);background:none;border:none;transition:color .2s}
        .mob-nav-btn i{font-size:1.2rem}.mob-nav-btn.active{color:var(--brand)}
        @media(max-width:768px){body{padding-top:56px;padding-bottom:80px}.topnav{height:56px}.mobile-nav{display:grid}.topnav-links{display:none!important}}
        .view{display:none;animation:fadeUp .25s ease}.view.active{display:block}
        @keyframes fadeUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        .page-wrap{max-width:1280px;margin:0 auto;padding:1.5rem 1.25rem}
        .stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem}
        @media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr)}}
        .stat-card{background:var(--white);border-radius:var(--radius-lg);padding:1.15rem;border:1px solid var(--border);box-shadow:var(--shadow-sm)}
        .stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:.65rem}
        .stat-value{font-size:1.4rem;font-weight:700;line-height:1.1}.stat-label{font-size:.75rem;color:var(--muted);margin-top:2px}
        .activity-card{background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);border-left:4px solid transparent;margin-bottom:.65rem;padding:1rem 1.15rem;cursor:pointer;transition:box-shadow .2s,transform .2s;box-shadow:var(--shadow-sm)}
        .activity-card:hover{box-shadow:var(--shadow);transform:translateY(-1px)}
        .activity-card.active-now{border-left-color:var(--brand)}.activity-card.approved{border-left-color:var(--accent)}
        .badge-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:999px;font-size:.7rem;font-weight:600;white-space:nowrap}
        .bp-approved{background:#d1fae5;color:#065f46}.bp-progress{background:#fef3c7;color:#92400e}.bp-complete{background:#d1fae5;color:#065f46}
        .bp-pending{background:#fef3c7;color:#92400e}.bp-rejected{background:#fee2e2;color:#991b1b}.bp-paid{background:#d1fae5;color:#065f46}.bp-submitted{background:#dbeafe;color:#1e40af}
        .page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem}
        .page-title{font-size:1.3rem;font-weight:700;color:var(--dark)}.page-subtitle{font-size:.8rem;color:var(--muted);margin-top:2px}
        .section-card{border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:1rem}
        .section-head{padding:.75rem 1rem;background:var(--surface);border-bottom:1px solid var(--border);font-weight:600;font-size:.85rem;display:flex;align-items:center;justify-content:space-between}
        .section-body{padding:1rem;background:var(--white)}
        .breadcrumb-bar{display:flex;align-items:center;gap:.4rem;font-size:.78rem;color:var(--muted);margin-bottom:.5rem}
        .breadcrumb-bar a{color:var(--brand);text-decoration:none;cursor:pointer}
        .engagement-tag{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:.72rem;font-weight:600}
        .eng-hourly{background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe}.eng-daily{background:#ede9fe;color:#5b21b6;border:1px solid #ddd6fe}.eng-fixed{background:#fce7f3;color:#9d174d;border:1px solid #fbcfe8}
        .pagination-row{display:flex;flex-wrap:wrap;gap:.35rem;justify-content:flex-end;padding:.75rem 0;align-items:center}
        .pagination-row button{min-width:44px}
        .detail-tabs{display:flex;gap:0;background:var(--white);border-radius:var(--radius-lg) var(--radius-lg) 0 0;border:1px solid var(--border);border-bottom:none;overflow-x:auto}
        .d-tab{flex:1;padding:.65rem .4rem;font-size:.78rem;font-weight:600;color:var(--muted);cursor:pointer;border:none;background:none;display:flex;align-items:center;justify-content:center;gap:4px;border-bottom:3px solid transparent;transition:all .18s;white-space:nowrap}
        .d-tab:hover{color:var(--brand);background:var(--surface)}.d-tab.active{color:var(--brand);border-bottom-color:var(--brand);background:var(--white)}
        @media(max-width:600px){.d-tab span.tab-label{display:none}.d-tab{font-size:.95rem;padding:.6rem .4rem}}
        .tab-content-panel{display:none}.tab-content-panel.active{display:block}
        .tab-panel-card{background:var(--white);border:1px solid var(--border);border-top:none;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:1.15rem}
        @media(max-width:480px){.tab-panel-card{padding:.85rem}}
        .tl-cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px}
        .tl-cal-head{text-align:center;font-size:.68rem;font-weight:700;color:var(--muted);text-transform:uppercase;padding:5px;background:var(--surface);border-radius:5px}
        .tl-cal-day{min-height:52px;border:1.5px solid var(--border);border-radius:7px;padding:3px;background:white;cursor:pointer;display:flex;flex-direction:column;align-items:center;transition:all .15s}
        .tl-cal-day:hover{box-shadow:var(--shadow);transform:translateY(-1px)}
        .tl-cal-day.outside{background:var(--surface);opacity:.3;pointer-events:none}
        .tl-cal-day.today{border-color:var(--brand);border-width:2px;background:var(--brand-lite)}
        .tl-cal-day.filled{border-color:var(--accent);background:#f0fdf4}.tl-cal-day.unfilled{border-color:var(--danger);background:#fff1f2}
        .tl-cal-day.inactive{opacity:.35;pointer-events:none;background:#f9fafb}
        .tl-cal-day-num{font-size:.72rem;font-weight:700;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center}
        .today .tl-cal-day-num{background:var(--brand);color:white}
        .tl-cal-status{font-size:.5rem;font-weight:700;margin-top:1px;text-transform:uppercase}
        .tl-cal-day.filled .tl-cal-status{color:#065f46}.tl-cal-day.unfilled .tl-cal-status{color:#991b1b}
        @media(max-width:480px){.tl-cal-day{min-height:40px;padding:2px}.tl-cal-day-num{font-size:.6rem;width:16px;height:16px}.tl-cal-status{font-size:.45rem}}
        .hour-timeline{position:relative;padding-left:48px}
        .hour-row{position:relative;display:flex;align-items:flex-start;min-height:44px}.hour-row+.hour-row{border-top:1px dashed var(--border)}
        .hour-label{position:absolute;left:-48px;top:8px;width:40px;text-align:right;font-size:.65rem;font-weight:600;color:var(--muted);font-family:'IBM Plex Mono',monospace}
        .hour-dot{position:absolute;left:-18px;top:13px;width:7px;height:7px;border-radius:50%;background:var(--border);border:2px solid white;box-shadow:0 0 0 1px var(--border)}
        .hour-dot.has-entry{background:var(--brand);box-shadow:0 0 0 3px rgba(15,76,129,.15)}
        .hour-content{flex:1;padding:6px 0}
        .hour-entry-pill{display:inline-flex;align-items:center;gap:5px;background:var(--brand-lite);color:var(--brand);border:1px solid #bfdbfe;border-radius:6px;padding:3px 7px;font-size:.75rem;font-weight:500;margin:2px}
        .hour-entry-pill .remove-hour{background:none;border:none;color:var(--danger);cursor:pointer;padding:0;line-height:1;font-size:.7rem}
        .hour-add-btn{display:inline-flex;align-items:center;gap:3px;background:none;border:1px dashed var(--border);border-radius:6px;padding:2px 7px;font-size:.7rem;color:var(--muted);cursor:pointer;transition:all .15s}
        .hour-add-btn:hover{border-color:var(--brand);color:var(--brand);background:var(--brand-lite)}
        .fixed-milestone{display:flex;align-items:flex-start;gap:.65rem;padding:.75rem;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:.4rem;background:white;position:relative}
        .fixed-milestone .milestone-check{width:20px;height:20px;border-radius:50%;border:2px solid var(--border);flex-shrink:0;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;margin-top:1px}
        .fixed-milestone.done .milestone-check{background:var(--accent);border-color:var(--accent);color:white}
        .fixed-milestone.done .milestone-body{opacity:.55}
        .fixed-milestone .milestone-remove{position:absolute;top:5px;right:5px;background:none;border:none;color:var(--danger);cursor:pointer;font-size:.8rem;opacity:0;transition:opacity .15s}
        .fixed-milestone:hover .milestone-remove{opacity:1}
        .inline-add-form{background:var(--brand-lite);border:1.5px solid var(--brand);border-radius:var(--radius);padding:.85rem;margin-top:.4rem;animation:formSlide .2s ease}
        @keyframes formSlide{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
        .inline-add-form label{font-size:.72rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.03em;display:block;margin-bottom:2px}
        .inline-add-form .form-control,.inline-add-form .form-select{font-size:.82rem;border-color:var(--border);border-radius:7px;padding:.4rem .6rem}
        .inline-add-form .form-control:focus,.inline-add-form .form-select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(15,76,129,.1)}
        .btn-add-confirm{background:var(--brand);color:white;border:none;border-radius:7px;padding:.4rem .9rem;font-size:.8rem;font-weight:600;cursor:pointer;transition:background .18s}
        .btn-add-confirm:hover{background:var(--brand-mid)}
        .btn-cancel{background:none;border:1px solid var(--border);border-radius:7px;padding:.4rem .75rem;font-size:.8rem;color:var(--muted);cursor:pointer}
        .day-panel-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.4);z-index:1100;animation:fadeIn .2s ease}
        .day-panel-overlay.active{display:flex;justify-content:center;align-items:flex-end}
        @media(min-width:769px){.day-panel-overlay.active{align-items:center}}
        .day-panel{background:var(--white);border-radius:var(--radius-lg) var(--radius-lg) 0 0;width:100%;max-height:90vh;overflow-y:auto;animation:slideUp .3s ease;padding:0}
        @media(min-width:769px){.day-panel{max-width:600px;border-radius:var(--radius-lg);max-height:85vh}}
        .day-panel-header{position:sticky;top:0;z-index:1;background:var(--white);border-bottom:1px solid var(--border);padding:.85rem 1.15rem;display:flex;align-items:center;justify-content:space-between}
        .day-panel-body{padding:1.15rem}
        @keyframes slideUp{from{transform:translateY(100%)}to{transform:translateY(0)}}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        @keyframes spin { to { transform: rotate(360deg); } }
        .upload-drop{border:2px dashed var(--border);border-radius:var(--radius);padding:.75rem;text-align:center;cursor:pointer;transition:all .2s}
        .upload-drop:hover{border-color:var(--brand);background:var(--brand-lite)}
        .budget-row{display:flex;justify-content:space-between;align-items:center;padding:.45rem 0;border-bottom:1px solid var(--border)}.budget-row:last-child{border-bottom:none}
        .b-label{font-size:.8rem;color:var(--muted)}.b-value{font-family:'IBM Plex Mono',monospace;font-size:.85rem;font-weight:600}
        .expense-entry{border:1px solid var(--border);border-radius:var(--radius);padding:.65rem;margin-bottom:.4rem;background:#fafbfc}
        .logistics-entry{border:1px solid var(--border);border-radius:var(--radius);padding:.65rem;margin-bottom:.4rem;background:#fafbfc}
        .gps-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:999px;font-size:.68rem;font-weight:600}
        .gps-ok{background:#d1fae5;color:#065f46}.gps-pend{background:#fef3c7;color:#92400e}
        .log-entry{border:1px solid var(--border);border-radius:var(--radius);padding:.75rem;margin-bottom:.5rem;background:var(--white);position:relative;cursor:pointer;transition:box-shadow .15s}
        .log-entry:hover{box-shadow:var(--shadow-sm)}
        .log-entry .remove-btn{position:absolute;top:5px;right:5px;background:none;border:none;color:var(--danger);cursor:pointer;font-size:.85rem}
        .exp-table{width:100%;border-collapse:collapse}.exp-table th{font-size:.7rem;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);padding:.45rem .65rem;background:var(--surface);border-bottom:2px solid var(--border);text-align:left}
        .exp-table td{padding:.5rem .65rem;border-bottom:1px solid var(--border);font-size:.82rem}.exp-table tfoot td{background:var(--surface);font-weight:700}
        .filter-bar{background:var(--white);border-radius:var(--radius);border:1px solid var(--border);padding:.65rem .85rem;margin-bottom:.85rem;display:flex;flex-wrap:wrap;gap:.4rem;align-items:center}
        .btn{border-radius:8px;font-weight:500}.btn-brand{background:var(--brand);color:white;border:none}.btn-brand:hover{background:var(--brand-mid);color:white}.btn-accent{background:var(--accent);color:var(--dark);border:none}.btn-accent:hover{background:#00b082}
        .divider{height:1px;background:var(--border);margin:.85rem 0}.mono{font-family:'IBM Plex Mono',monospace}.text-accent{color:var(--accent)!important}.text-brand{color:var(--brand)!important}
        .toast-notif{position:fixed;bottom:90px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--dark);color:white;padding:.5rem 1.1rem;border-radius:999px;font-size:.8rem;font-weight:500;opacity:0;transition:all .25s;z-index:9999;pointer-events:none;white-space:nowrap}
        .toast-notif.show{opacity:1;transform:translateX(-50%) translateY(0)}
        .admin-table{width:100%;border-collapse:collapse}
        .admin-table th{font-size:.7rem;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);padding:.55rem .65rem;background:var(--surface);border-bottom:2px solid var(--border);text-align:left;white-space:nowrap}
        .admin-table td{padding:.55rem .65rem;border-bottom:1px solid var(--border);font-size:.8rem;vertical-align:middle}
        /* ensure long text wraps instead of overflowing */
        .admin-table th, .admin-table td, .profile-exp-table th, .profile-exp-table td, .profile-info-item .piv { box-sizing: border-box; word-break: break-word; overflow-wrap: anywhere; white-space:normal }
        .admin-table tr:hover td{background:#f8fafc}
        @media(max-width:768px){.admin-table-wrap{display:block;overflow-x:auto;-webkit-overflow-scrolling:touch}}
        .report-card{border:2px solid var(--accent);border-radius:var(--radius-lg);background:linear-gradient(135deg,#f0fdf9,white);padding:1.15rem;margin-bottom:.85rem}
        .rpt-tab-btn.active{background:var(--brand);color:white;border-color:var(--brand)}
        .profile-section{margin-bottom:1.5rem}
        .profile-section-title{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);border-bottom:2px solid var(--border);padding-bottom:.4rem;margin-bottom:.85rem;display:flex;align-items:center;gap:.4rem}
        .profile-info-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem}
        .profile-info-item .pil{font-size:.68rem;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;font-weight:600;margin-bottom:2px}
        .profile-info-item .piv{font-size:.85rem;font-weight:600;color:var(--text)}
        .profile-stat-row{display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:.85rem}
        .profile-stat-box{flex:1;min-width:100px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:.65rem .85rem;text-align:center}
        .profile-stat-box .psv{font-size:1.2rem;font-weight:700;font-family:'IBM Plex Mono',monospace}
        .profile-stat-box .psl{font-size:.68rem;color:var(--muted);margin-top:2px}
        .profile-log-row{display:flex;gap:1rem;padding:.55rem;border-radius:7px;border:1px solid var(--border);margin-bottom:.35rem;background:#fafbfc;flex-wrap:wrap}
        .profile-log-date{font-size:.72rem;font-weight:700;color:var(--brand);white-space:nowrap;min-width:80px}
        .profile-log-work{font-size:.78rem;flex:1}
        .profile-log-earn{font-size:.72rem;font-weight:700;font-family:'IBM Plex Mono',monospace;color:var(--accent);white-space:nowrap}
        .profile-exp-table{width:100%;border-collapse:collapse;font-size:.78rem}
        .profile-exp-table th{text-transform:uppercase;font-size:.65rem;letter-spacing:.04em;color:var(--muted);padding:.4rem .6rem;border-bottom:2px solid var(--border);text-align:left;background:var(--surface)}
        .profile-exp-table td{padding:.42rem .6rem;border-bottom:1px solid var(--border)}
        .profile-exp-table tfoot td{font-weight:700;background:var(--surface)}
        .no-data-row{text-align:center;color:var(--muted);padding:1rem;font-size:.78rem}
        .profile-progress-bar{height:8px;border-radius:999px;background:var(--border);overflow:hidden;margin:.5rem 0}
        .profile-progress-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--brand),var(--accent))}
        .review-chip{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:999px;font-size:.65rem;font-weight:700;letter-spacing:.02em}
        .rc-approved{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
        .rc-rejected{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
        .rc-pending{background:#fef3c7;color:#92400e;border:1px solid #fcd34d}
        .review-panel{border:1px solid var(--border);border-radius:var(--radius);padding:.6rem .85rem;margin-top:.5rem;background:var(--surface);display:flex;flex-direction:column;gap:.4rem}
        .review-panel label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--muted)}
        @media print {
            body > *{ display:none!important }
            #view-detail{ display:block!important;padding:0!important }
            .detail-tabs,.topnav,.mobile-nav,.day-panel-overlay{ display:none!important }
            .tab-content-panel{ display:block!important }
            .tab-panel-card{ border:none!important;padding:0!important }
            body{ padding:0!important }
            @page{ margin:1cm;size:A4 }
        }

        .pdf-export-mode .section-card[style*="sticky"] {
    position: static !important;
}
    </style>
</head>
<body>

<!-- TOP NAV -->
<nav class="topnav">
    <a class="brand" onclick="showView('dashboard')">
        <div class="brand-icon"><i class="bi bi-calendar-check"></i></div>
    </a>
    <a href="/" class="btn btn-light btn-sm ms-3 desktop-nav" style="font-weight:500;font-size:.8rem;">← Main Dashboard</a>
    <ul class="navbar-nav flex-row gap-1 ms-3 desktop-nav">
        <li><button class="topnav-btn px-3 text-white" style="width:auto;font-size:.82rem;font-weight:500" onclick="showView('dashboard')"><i class="bi bi-speedometer2 me-1"></i>Dashboard</button></li>
        <li><button class="topnav-btn px-3 text-white" style="width:auto;font-size:.82rem;font-weight:500" onclick="showView('activities')"><i class="bi bi-list-check me-1"></i>Activities</button></li>
        @if(in_array($user->role->name ?? '', ['Admin', 'Manager', 'Finance']))
        <li><button class="topnav-btn px-3 text-white" style="width:auto;font-size:.82rem;font-weight:500" onclick="showView('admin')"><i class="bi bi-shield-check me-1"></i>Admin</button></li>
        <li><button class="topnav-btn px-3 text-white" style="width:auto;font-size:.82rem;font-weight:500" onclick="showView('reports')"><i class="bi bi-bar-chart me-1"></i>Reports</button></li>
        @endif
    </ul>
    <div class="topnav-right">
        <button class="topnav-btn"><i class="bi bi-bell"></i></button>
        <div class="d-flex align-items-center gap-1">
            <div class="avatar" id="user-avatar">
                @php
                    $initials = '--';
                    if (!empty($user->name)) {
                        $parts = array_filter(explode(' ', $user->name));
                        $letters = collect($parts)->map(fn($n) => $n !== '' ? mb_substr($n,0,1) : '')->implode('');
                        if ($letters !== '') $initials = strtoupper(mb_substr($letters,0,2));
                    }
                @endphp
                {{ $initials }}
            </div>
            <span class="desktop-nav text-white" style="font-size:.8rem" id="user-name">{{ $user->name ?? 'User' }}</span>
        </div>
    </div>
</nav>

@if(isset($activity) && ($activity->title || ($activity->requisition && $activity->requisition->title)))
    <div class="container mt-2" style="padding-top:.25rem">
        <div class="alert alert-info mb-0" style="font-size:.9rem;">
            <strong>Requisition:</strong>
            {{ $activity->title ?? $activity->requisition->title }}
        </div>
    </div>
@endif

<!-- MOBILE BOTTOM NAV -->
<nav class="mobile-nav">
    <button class="mob-nav-btn active" onclick="showView('dashboard');setMobActive(this)"><i class="bi bi-speedometer2"></i><span>Home</span></button>
    <button class="mob-nav-btn" onclick="showView('activities');setMobActive(this)"><i class="bi bi-list-check"></i><span>Activities</span></button>
    @if(in_array($user->role->name ?? '', ['Admin', 'Manager', 'Finance']))
    <button class="mob-nav-btn" onclick="showView('admin');setMobActive(this)"><i class="bi bi-shield-check"></i><span>Admin</span></button>
    <button class="mob-nav-btn" onclick="showView('reports');setMobActive(this)"><i class="bi bi-bar-chart"></i><span>Reports</span></button>
    @endif
</nav>

<div class="container-fluid">

<!-- ══════════ DASHBOARD VIEW ══════════ -->
<div id="view-dashboard" class="view active">
    <div class="page-wrap">
        <div class="page-header">
            <div>
                <div class="page-title" id="greeting">
                    @php
                        $greeting = 'Welcome';
                        $firstName = '';
                        if (!empty($user->name)) {
                            $firstName = explode(' ', trim($user->name))[0] ?? '';
                            $hour = (int)date('G');
                            if ($hour < 12) $greeting = 'Good morning';
                            elseif ($hour < 17) $greeting = 'Good afternoon';
                            else $greeting = 'Good evening';
                        }
                    @endphp
                    {{ $greeting }}@if($firstName), {{ $firstName }}@endif 👋
                </div>
                <div class="page-subtitle" id="user-subtitle">Your approved engagements</div>
            </div>
        </div>
        <div class="stat-grid mb-4" id="dash-stats"></div>
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="section-head">
                        <span><i class="bi bi-activity text-brand me-2"></i>Your Activities</span>
                        <button class="btn btn-sm btn-outline-primary" onclick="showView('activities')">View All</button>
                    </div>
                    <div class="section-body" style="padding:.65rem" id="dash-activities"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card mb-3">
                    <div class="section-head"><i class="bi bi-calculator me-2 text-brand"></i>Financial Overview</div>
                    <div class="section-body" id="dash-finance"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ ACTIVITIES LIST VIEW ══════════ -->
<div id="view-activities" class="view">
    <div class="page-wrap">
        <div class="page-header">
            <div>
                <div class="page-title">📋 My Approved Activities</div>
                <div class="page-subtitle">Activities you are approved to work on</div>
            </div>
        </div>
        <div class="filter-bar">
            <!-- FIX: Added id="filter-search" so the search input value can be read -->
            <input type="text" id="filter-search" class="form-control form-control-sm" style="max-width:180px" placeholder="Search..." oninput="renderActivitiesList()">
            <select class="form-select form-select-sm" style="max-width:140px" id="filter-engagement" onchange="renderActivitiesList()">
                <option value="">All Types</option>
                <option>hourly</option>
                <option>daily</option>
                <option>fixed</option>
            </select>
            <select class="form-select form-select-sm" style="max-width:130px" id="filter-status" onchange="renderActivitiesList()">
                <option value="">All Status</option>
                <option>In Progress</option>
                <option>Upcoming</option>
                <option>Completed</option>
            </select>
        </div>
        <div id="activities-list"></div>
    </div>
</div>

<!-- ══════════ DETAIL VIEW ══════════ -->
<div id="view-detail" class="view">
    <div class="page-wrap">
        <div class="breadcrumb-bar mb-1">
            <a onclick="showView('activities')">Activities</a>
            <i class="bi bi-chevron-right" style="font-size:.6rem"></i>
            <span id="detail-breadcrumb">Activity</span>
        </div>
        <div class="page-header">
            <div>
                <div class="page-title" id="detail-title"></div>
                <div class="d-flex gap-2 flex-wrap align-items-center mt-1" id="detail-badges"></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="printActivityProfile()"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="showView('activities')"><i class="bi bi-arrow-left me-1"></i>Back</button>
            </div>
        </div>
        <div class="detail-tabs" id="detail-tabs">
            <button class="d-tab active" id="tab-btn-overview" onclick="switchTab('overview')"><i class="bi bi-info-circle"></i><span class="tab-label">Overview</span></button>
            <button class="d-tab" id="tab-btn-logsheet" onclick="switchTab('logsheet')"><i class="bi bi-journal-text"></i><span class="tab-label">Logsheet</span></button>
            <button class="d-tab" id="tab-btn-expenses" onclick="switchTab('expenses')"><i class="bi bi-receipt"></i><span class="tab-label">Expenses</span></button>
            <button class="d-tab" id="tab-btn-docs" onclick="switchTab('docs')"><i class="bi bi-folder2-open"></i><span class="tab-label">Docs</span></button>
        </div>

        <!-- OVERVIEW TAB -->
        <div class="tab-content-panel active" id="tab-overview">
            <div class="tab-panel-card">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="section-card">
                            <div class="section-head"><span><i class="bi bi-info-circle text-brand me-2"></i>Activity Information</span></div>
                            <div class="section-body" id="ov-info"></div>
                        </div>
                        <div class="section-card">
                            <div class="section-head">
                                <span><i class="bi bi-journal-check text-accent me-2"></i>Logsheet Progress</span>
                                <button class="btn btn-sm btn-brand" onclick="switchTab('logsheet')"><i class="bi bi-journal-text me-1"></i>Open Logsheet</button>
                            </div>
                            <div class="section-body" id="ov-progress"></div>
                        </div>
                        <!-- Inline profile detail rendered here -->
                        <div id="ov-profile-detail"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="section-card" style="position:sticky;top:75px">
                            <div class="section-head"><i class="bi bi-calculator me-2 text-brand"></i>Financial Summary</div>
                            <div class="section-body" id="ov-finance"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content-panel" id="tab-logsheet"><div class="tab-panel-card" id="logsheet-container"></div></div>
        <div class="tab-content-panel" id="tab-expenses"><div class="tab-panel-card" id="expenses-container"></div></div>
        <div class="tab-content-panel" id="tab-docs"><div class="tab-panel-card" id="docs-container"></div></div>
    </div>
</div>

<!-- ══════════ ADMIN VIEW ══════════ -->
<div id="view-admin" class="view">
    <div class="page-wrap">
        <div class="page-header">
            <div>
                <div class="page-title"><i class="bi bi-shield-check me-2"></i>Admin – Approvals &amp; Payments</div>
                <div class="page-subtitle">Manage personnel activities, approve logsheets, and process payments</div>
            </div>
        </div>
        <div class="filter-bar">
            <select class="form-select form-select-sm" style="max-width:150px" id="admin-filter-status" onchange="renderAdminView()">
                <option value="">All Status</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Paid</option>
                <option>Rejected</option>
            </select>
            <select class="form-select form-select-sm" style="max-width:140px" id="admin-filter-type" onchange="renderAdminView()">
                <option value="">All Types</option>
                <option>hourly</option>
                <option>daily</option>
                <option>fixed</option>
            </select>
            <select class="form-select form-select-sm" style="max-width:150px" id="admin-filter-person" onchange="renderAdminView()">
                <option value="">All Personnel</option>
                <option>{{ $user->name ?? 'User' }}</option>
                @foreach($teamMembers ?? [] as $member)
                    @if($member->id !== ($user->id ?? null))
                        <option>{{ $member->name }}</option>
                    @endif
                @endforeach
            </select>
            <button class="btn btn-sm btn-brand ms-auto" onclick="showView('reports')"><i class="bi bi-file-earmark-bar-graph me-1"></i>Reports</button>
        </div>
        <div id="admin-stats" class="stat-grid mb-3"></div>
        <div class="section-card">
            <div class="section-head"><span><i class="bi bi-table text-brand me-2"></i>Activity Submissions</span></div>
            <div class="section-body p-0 admin-table-wrap" id="admin-table-wrap"></div>
        </div>
    </div>
</div>

<!-- ══════════ REPORTS VIEW ══════════ -->
<div id="view-reports" class="view">
    <div class="page-wrap">
        <div class="page-header">
            <div>
                <div class="page-title"><i class="bi bi-bar-chart me-2"></i>Reports</div>
                <div class="page-subtitle">Filter, preview and download activity reports as Excel</div>
            </div>
            <button class="btn btn-sm btn-outline-secondary" onclick="showView('admin')"><i class="bi bi-arrow-left me-1"></i>Back</button>
        </div>
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-outline-primary rpt-tab-btn active" data-type="summary">Activity Summary</button>
                <button class="btn btn-sm btn-outline-primary rpt-tab-btn" data-type="finance">Financial</button>
                <button class="btn btn-sm btn-outline-primary rpt-tab-btn" data-type="logsheet">Logsheet</button>
                <button class="btn btn-sm btn-outline-primary rpt-tab-btn" data-type="expenses">Expenses</button>
                <button class="btn btn-sm btn-outline-primary rpt-tab-btn" data-type="payment">Payment Status</button>
            </div>
            <button class="btn btn-sm btn-success ms-auto fw-bold" onclick="downloadExcelReport()">
                <i class="bi bi-file-earmark-excel me-1"></i>Download Excel
            </button>
        </div>
        <div class="filter-bar flex-wrap">
            <div>
                <label style="font-size:.7rem;font-weight:600;color:var(--muted);display:block">From</label>
                <input type="date" id="rpt-filter-from" class="form-control form-control-sm" style="max-width:140px" onchange="renderReports()">
            </div>
            <div>
                <label style="font-size:.7rem;font-weight:600;color:var(--muted);display:block">To</label>
                <input type="date" id="rpt-filter-to" class="form-control form-control-sm" style="max-width:140px" onchange="renderReports()">
            </div>
            <div>
                <label style="font-size:.7rem;font-weight:600;color:var(--muted);display:block">Person</label>
                <select id="rpt-filter-person" class="form-select form-select-sm" style="max-width:160px" onchange="renderReports()">
                    <option value="">All Personnel</option>
                </select>
            </div>
            <div>
                <label style="font-size:.7rem;font-weight:600;color:var(--muted);display:block">Engagement</label>
                <select id="rpt-filter-type" class="form-select form-select-sm" style="max-width:130px" onchange="renderReports()">
                    <option value="">All Types</option>
                    <option value="hourly">Hourly</option>
                    <option value="daily">Daily</option>
                    <option value="fixed">Fixed</option>
                </select>
            </div>
            <div>
                <label style="font-size:.7rem;font-weight:600;color:var(--muted);display:block">Status</label>
                <select id="rpt-filter-status" class="form-select form-select-sm" style="max-width:140px" onchange="renderReports()">
                    <option value="">All Status</option>
                    <option value="Upcoming">Draft/Upcoming</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div>
                <label style="font-size:.7rem;font-weight:600;color:var(--muted);display:block">Payment</label>
                <select id="rpt-filter-paid" class="form-select form-select-sm" style="max-width:120px" onchange="renderReports()">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>
        </div>
        <div class="stat-grid mb-3" id="rpt-stat-cards"></div>
        <div class="section-card">
            <div class="section-head">
                <span id="rpt-table-label"><i class="bi bi-table text-brand me-2"></i>Activity Summary</span>
                <span id="rpt-row-count" class="text-muted" style="font-size:.75rem;font-weight:400"></span>
            </div>
            <div class="section-body p-0" style="overflow-x:auto">
                <table class="admin-table" id="rpt-table">
                    <thead id="rpt-thead"></thead>
                    <tbody id="rpt-tbody"></tbody>
                    <tfoot id="rpt-tfoot" style="background:var(--surface);font-weight:700"></tfoot>
                </table>
            </div>
            <div id="rpt-pagination" class="d-flex justify-content-between align-items-center p-3"></div>
        </div>
    </div>
</div>

</div><!-- /container-fluid -->

<!-- DAY PANEL OVERLAY -->
<div class="day-panel-overlay" id="day-panel-overlay">
    <div class="day-panel" onclick="event.stopPropagation()">
        <div class="day-panel-header">
            <div>
                <div class="fw-bold" id="dp-title"></div>
                <div class="text-muted small" id="dp-subtitle"></div>
            </div>
            <button class="btn btn-sm btn-outline-secondary" onclick="closeDayPanel()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="day-panel-body" id="dp-body"></div>
    </div>
</div>

<div class="toast-notif" id="toast-notif"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ════════════════════════════════════
// BOOTSTRAP DATA FROM LARAVEL
// ════════════════════════════════════
const APP_USER = {
    id: {{ $user->id ?? 'null' }},
    name: "{{ $user->name ?? 'User' }}",
    role: "{{ $user->role->name ?? 'staff' }}",
    permissions: {
        canManageActivities: {{ (auth()->user() && auth()->user()->role->hasPermission('field-activities.edit')) ? 'true' : 'false' }},
        canLogsheetOnly: {{ (auth()->user() && auth()->user()->role->hasPermission('field-activities.logsheet.create') && !auth()->user()->role->hasPermission('field-activities.edit')) ? 'true' : 'false' }},
        isAdmin: "{{ $user->role->name ?? '' }}" === 'Admin',
        isManager: "{{ $user->role->name ?? '' }}" === 'Manager',
        isFinance: "{{ $user->role->name ?? '' }}" === 'Finance'
    }
};

// ════════════════════════════════════
// GLOBALS
// ════════════════════════════════════
const today = new Date();
const KES = n => 'KES ' + Number(n).toLocaleString();

let activities = [];
let currentActivity = null, logMonthOffset = 0, currentDayKey = null, currentHourlyDateKey = null;
let logMode = 'day';
let activitiesPage = 1, activitiesPageSize = 9, activityFilters = { search:'', engagement:'', status:'' };
let adminPage = 1, adminPageSize = 10, adminFilters = { status:'', type:'', person:'' };
// ════════════════════════════════════
// UTILITY
// ════════════════════════════════════
function dateKey(d) { return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0'); }
function formatDate(d) { return d.toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'}); }
function formatDateRange(s,e) { return s.getMonth()===e.getMonth()&&s.getFullYear()===e.getFullYear() ? s.getDate()+'–'+e.getDate()+' '+s.toLocaleDateString('en-GB',{month:'short',year:'numeric'}) : formatDate(s)+' – '+formatDate(e); }
function totalDays(a) { return Math.ceil((a.endDate - a.startDate) / 864e5) + 1; }
function showToast(m) { const t=document.getElementById('toast-notif'); t.textContent=m; t.classList.add('show'); setTimeout(()=>t.classList.remove('show'), 2200); }

function buildPagination(currentPage, totalPages, callbackName) {
    if (totalPages <= 1) return '';
    const prevClass = currentPage <= 1 ? 'disabled' : '';
    const nextClass = currentPage >= totalPages ? 'disabled' : '';
    let pages = '';
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    for (let p = startPage; p <= endPage; p++) {
        pages += `<button class="btn btn-sm ${p === currentPage ? 'btn-brand' : 'btn-outline-secondary'}" onclick="${callbackName}(${p})">${p}</button>`;
    }
    return `<div class="pagination-row">
            <button class="btn btn-sm btn-outline-secondary ${prevClass}" onclick="${callbackName}(${Math.max(1, currentPage - 1)})" ${prevClass ? 'disabled' : ''}>Prev</button>
            ${pages}
            <button class="btn btn-sm btn-outline-secondary ${nextClass}" onclick="${callbackName}(${Math.min(totalPages, currentPage + 1)})" ${nextClass ? 'disabled' : ''}>Next</button>
        </div>`;
}

function gotoActivitiesPage(page) {
    activitiesPage = Math.max(1, page);
    renderActivitiesList();
}

function gotoAdminPage(page) {
    adminPage = Math.max(1, page);
    renderAdminView();
}

// function calcWork(a) {
//     if (a.engagement === 'daily') return a.rate * a.filledDays.length;
//     if (a.engagement === 'hourly') { let h=0; Object.values(a.logs).forEach(l=>{ if(l.work) l.work.forEach(w=>{ if(w.hours) h+=w.hours; }); }); return a.rate * h; }
//     return a.rate;
// }

// NEW — Work Logged removed from the payment calculation entirely.
// Total Payable now comes only from Expenses + Logistics (calcExpenses).
function calcWork(a) {
    if (a.engagement === 'daily') {
        return a.rate * a.filledDays.length;
    }

    if (a.engagement === 'hourly') {
        let h = 0;
        Object.values(a.logs).forEach(l => {
            if (l.work) l.work.forEach(w => { if (w.hours) h += Number(w.hours || 0); });
        });
        return a.rate * h;
    }

    return a.rate;
}
function calcExpenses(a) { let t=0; Object.values(a.logs).forEach(l=>{ if(l.expenses) l.expenses.forEach(e=>t+=e.amount); if(l.logistics) l.logistics.forEach(e=>t+=e.cost); }); return t; }
function calcContract(a) { const td=totalDays(a); if(a.engagement==='fixed') return a.rate; if(a.engagement==='daily') return a.rate*td; return a.rate*td*8; }
function engTag(a) {
    const c = a.engagement==='hourly'?'eng-hourly':a.engagement==='daily'?'eng-daily':'eng-fixed';
    const i = a.engagement==='hourly'?'bi-clock':a.engagement==='daily'?'bi-calendar-day':'bi-box';
    const l = a.engagement==='hourly'?KES(a.rate)+'/hr':a.engagement==='daily'?KES(a.rate)+'/day':KES(a.rate)+' (fixed)';
    return `<span class="engagement-tag ${c}"><i class="bi ${i} me-1"></i>${l}</span>`;
}

function isAllowedDocumentFile(file) {
    if (!file || !file.name) return false;
    const ext = file.name.split('.').pop().toLowerCase();
    return ['pdf','doc','docx','jpg','jpeg','png'].includes(ext);
}

function getAllowedDocumentAccept() {
    return '.pdf,.doc,.docx,.jpg,.jpeg,.png';
}

// ════════════════════════════════════
// VIEW & TAB SWITCHING
// ════════════════════════════════════
function showView(id) {
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    const t = document.getElementById('view-' + id);
    if (t) t.classList.add('active');
    window.scrollTo(0, 0);
    if (id === 'dashboard') renderDashboard();
    if (id === 'activities') renderActivitiesList();
    if (id === 'admin') renderAdminView();
    if (id === 'reports') { populateReportPersonFilter(); renderReports(); }
}
function setMobActive(b) { document.querySelectorAll('.mob-nav-btn').forEach(x => x.classList.remove('active')); b.classList.add('active'); }
// NEW
function switchTab(n) {
    // Logsheet-only users can view Logsheet, Overview, and Expenses — Docs stays restricted
    if (APP_USER.permissions.canLogsheetOnly && n === 'docs') {
        return;
    }
    document.querySelectorAll('.d-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content-panel').forEach(p => p.classList.remove('active'));
    const b = document.getElementById('tab-btn-' + n), p = document.getElementById('tab-' + n);
    if (b) b.classList.add('active');
    if (p) p.classList.add('active');
    if (n === 'logsheet') renderLogsheet();
    if (n === 'expenses') renderExpensesTab();
    if (n === 'docs') renderDocsTab();
    if (n === 'overview') renderOverview();
}

// ════════════════════════════════════
// FETCH ACTIVITIES
// ════════════════════════════════════
async function fetchActivities() {
    try {
        const res = await fetch('/api/field-activities', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        activities = await Promise.all((data || []).map(async (a, idx) => {
            let logs = {};
            let filledDays = [];
            try {
                const r2 = await fetch(`/api/field-activities/${a.id}/logs`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const logData = await r2.json();
                if (logData && logData.data) {
                    logData.data.forEach(l => {
                        logs[l.date] = l.log_data;
                        if (!filledDays.includes(l.date)) filledDays.push(l.date);
                    });
                }
            } catch (e) { logs = {}; filledDays = []; }
            return {
                id: idx,
                apiId: a.id,
                title: a.title || 'Untitled',
                person: a.created_by_name || APP_USER.name,
                created_by_id: a.created_by || null,
                status: mapStatus(a.status),
                dbStatus: a.status,
                adminStatus: mapAdminStatus(a.status),
                approver: a.approver || a.approver_name || a.approved_by_name || a.approved_by || null,
                engagement: a.engagement_type || 'daily',
                rate: parseFloat(a.engagement_rate) || 0,
                startDate: new Date(a.start_date + 'T00:00:00'),
                endDate: new Date(a.end_date + 'T00:00:00'),
                location: a.location || '',
                description: a.description || '',
                filledDays,
                logs,
                fixedItems: [],
                paid: a.status === 'funded',
                budget: parseFloat(a.budget) || 0,
                invoice: a.invoice || null,
            };
        }));
        renderDashboard();
    } catch (e) {
        console.error('Failed to load activities', e);
        activities = [];
        renderDashboard();
    }
}
function mapStatus(s) { const m = {draft:'Upcoming',submitted:'Upcoming',approved:'In Progress',funded:'Completed',rejected:'Upcoming'}; return m[s] || 'Upcoming'; }
function mapAdminStatus(s) { const m = {draft:'Pending',submitted:'Pending',approved:'Approved',funded:'Paid',rejected:'Rejected'}; return m[s] || 'Pending'; }

function getFinancialStatusSummary(list) {
    const summary = { approved: 0, pending: 0, paid: 0, declined: 0 };
    list.forEach(a => {
        const total = calcWork(a) + calcExpenses(a);
        const adminStatus = mapAdminStatus(a.status);
        if (adminStatus === 'Rejected') {
            summary.declined += total;
        } else if (a.paid || adminStatus === 'Paid') {
            summary.paid += total;
        } else if (adminStatus === 'Approved') {
            summary.approved += total;
        } else {
            summary.pending += total;
        }
    });
    return summary;
}

// ════════════════════════════════════
// DASHBOARD
// ════════════════════════════════════
function renderDashboard() {
    let my = [];
    if (["Admin", "Manager", "Finance"].includes(APP_USER.role)) {
        my = activities;
    } else {
        my = activities.filter(a => a.created_by_id === APP_USER.id);
    }
    const active = my.filter(a => a.status === 'In Progress').length;
    const filled = my.reduce((s,a) => s + a.filledDays.length, 0);
    const totalExp = my.reduce((s,a) => s + calcExpenses(a), 0);
    const totalWork = my.reduce((s,a) => s + calcWork(a), 0);
    const financialSummary = getFinancialStatusSummary(my);

    document.getElementById('dash-stats').innerHTML = `
        <div class="stat-card"><div class="stat-icon" style="background:#d1fae5;color:#065f46"><i class="bi bi-check-circle"></i></div><div class="stat-value">${my.length}</div><div class="stat-label">Activities</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#fef3c7;color:#92400e"><i class="bi bi-hourglass-split"></i></div><div class="stat-value">${active}</div><div class="stat-label">Active</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#dbeafe;color:var(--brand)"><i class="bi bi-journal-check"></i></div><div class="stat-value">${filled}</div><div class="stat-label">Days Logged</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-cash-stack"></i></div><div class="stat-value" style="font-size:1.1rem">${Math.round(totalExp/1000)}K</div><div class="stat-label">Expenses</div></div>`;

    const rows = my.slice(0,5).map((a, idx) => {
        const fp = totalDays(a) > 0 ? Math.round(a.filledDays.length / totalDays(a) * 100) : 0;
        return `<tr>
            <td>${idx + 1}</td>
            <td>${a.title}</td>
            <td>${formatDateRange(a.startDate, a.endDate)}</td>
            <td>${fp}%</td>
            <td>${a.engagement}</td>
            <td class="mono">${a.engagement==='hourly'?KES(a.rate)+'/hr':a.engagement==='daily'?KES(a.rate)+'/day':KES(a.rate)}</td>
            <td>${a.person}</td>
            <td>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation();openActivityDetail(${a.id});setTimeout(()=>switchTab('logsheet'),50)">View Logsheet</button>
                    <button class="btn btn-sm btn-brand" onclick="event.stopPropagation();openActivityDetail(${a.id});setTimeout(()=>switchTab('overview'),50)">View Overview</button>
                </div>
            </td>
        </tr>`;
    }).join('');

    document.getElementById('dash-activities').innerHTML = rows
        ? `<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>#</th><th>Activity name</th><th>Timelines</th><th>Logsheet % progress</th><th>Engagement rate</th><th>Rate</th><th>Officer name</th><th>Action</th></tr></thead><tbody>${rows}</tbody></table></div>`
        : '<div class="text-muted text-center py-3">No activities yet</div>';
    document.getElementById('dash-finance').innerHTML = `
        <div class="budget-row"><span class="b-label">Work Value</span><span class="b-value mono text-brand">${KES(totalWork)}</span></div>
        <div class="budget-row"><span class="b-label">Expenses</span><span class="b-value mono" style="color:var(--warn)">${KES(totalExp)}</span></div>
        <div class="divider" style="margin:.4rem 0"></div>
        <div class="d-flex justify-content-between"><span class="fw-bold" style="font-size:.85rem">Total Payable</span><span class="fw-bold mono text-accent">${KES(totalWork+totalExp)}</span></div>
        <div class="mt-3">
            <div class="row g-2">
                <div class="col-6">
                    <div class="border rounded p-2" style="background:#f0fdf4;border-color:#bbf7d0!important">
                        <div class="small text-muted">Approved</div>
                        <div class="fw-bold text-success">${KES(financialSummary.approved)}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2" style="background:#fffbeb;border-color:#fde68a!important">
                        <div class="small text-muted">Pending</div>
                        <div class="fw-bold" style="color:var(--warn)">${KES(financialSummary.pending)}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2" style="background:#ecfeff;border-color:#a5f3fc!important">
                        <div class="small text-muted">Paid</div>
                        <div class="fw-bold text-info">${KES(financialSummary.paid)}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2" style="background:#fef2f2;border-color:#fecaca!important">
                        <div class="small text-muted">Declined</div>
                        <div class="fw-bold text-danger">${KES(financialSummary.declined)}</div>
                    </div>
                </div>
            </div>
        </div>`;
}

// ════════════════════════════════════
// ACTIVITIES LIST
// FIX: Added search filter — reads #filter-search value and applies to title/person/location
// ════════════════════════════════════
function renderActivitiesList() {
    const ef = document.getElementById('filter-engagement')?.value || '';
    const sf = document.getElementById('filter-status')?.value || '';
    const sq = (document.getElementById('filter-search')?.value || '').toLowerCase().trim();
    const newFilters = { search: sq, engagement: ef, status: sf };
    if (newFilters.search !== activityFilters.search || newFilters.engagement !== activityFilters.engagement || newFilters.status !== activityFilters.status) {
        activitiesPage = 1;
        activityFilters = newFilters;
    }

    let list = [];
    if (["Admin", "Manager", "Finance"].includes(APP_USER.role)) {
        list = activities;
    } else {
        list = activities.filter(a => a.created_by_id === APP_USER.id);
    }
    if (ef) list = list.filter(a => a.engagement === ef);
    if (sf) list = list.filter(a => a.status === sf);
    if (sq) list = list.filter(a =>
        (a.title || '').toLowerCase().includes(sq) ||
        (a.person || '').toLowerCase().includes(sq) ||
        (a.location || '').toLowerCase().includes(sq)
    );

    const c = document.getElementById('activities-list');
    if (!c) return;
    if (!list.length) {
        c.innerHTML = '<div class="text-muted text-center py-4">No matching activities</div>';
        return;
    }

    const totalPages = Math.max(1, Math.ceil(list.length / activitiesPageSize));
    if (activitiesPage > totalPages) activitiesPage = totalPages;
    const pageStart = (activitiesPage - 1) * activitiesPageSize;
    const pageItems = list.slice(pageStart, pageStart + activitiesPageSize);

    const rows = pageItems.map((a, idx) => {
        const fp = totalDays(a) > 0 ? Math.round(a.filledDays.length / totalDays(a) * 100) : 0;
        return `<tr>
            <td>${pageStart + idx + 1}</td>
            <td>${a.title}</td>
            <td>${formatDateRange(a.startDate, a.endDate)}</td>
            <td>${fp}%</td>
            <td>${a.engagement}</td>
            <td class="mono">${a.engagement==='hourly'?KES(a.rate)+'/hr':a.engagement==='daily'?KES(a.rate)+'/day':KES(a.rate)}</td>
            <td>${a.person}</td>
            <td>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation();openActivityDetail(${a.id});setTimeout(()=>switchTab('logsheet'),50)">View Logsheet</button>
                    <button class="btn btn-sm btn-brand" onclick="event.stopPropagation();openActivityDetail(${a.id});setTimeout(()=>switchTab('overview'),50)">View Overview</button>
                </div>
            </td>
        </tr>`;
    }).join('');

    c.innerHTML = `<div class="admin-table-wrap"><table class="admin-table"><thead><tr>
            <th>#</th>
            <th>Activity name</th>
            <th>Timelines</th>
            <th>Logsheet % progress</th>
            <th>Engagement rate</th>
            <th>Rate</th>
            <th>Officer name</th>
            <th>Action</th>
        </tr></thead><tbody>${rows}</tbody></table></div>
        ${buildPagination(activitiesPage, totalPages, 'gotoActivitiesPage')}`;
}

// ════════════════════════════════════
// ACTIVITY DETAIL
// ════════════════════════════════════
function openActivityDetail(idx) {
    currentActivity = idx;
    const a = activities[idx];
    document.getElementById('detail-breadcrumb').textContent = a.title.split('–')[0].trim();
    document.getElementById('detail-title').textContent = a.title;
    document.getElementById('detail-badges').innerHTML = `
    <span class="badge-pill ${a.paid ? 'bp-paid' : a.adminStatus==='Rejected' ? 'bp-rejected' : a.adminStatus==='Approved' ? 'bp-approved' : 'bp-pending'}">
        <i class="bi bi-check-circle me-1"></i>${a.paid ? 'Paid' : a.adminStatus}
    </span>
    ${engTag(a)}
    <span class="badge-pill ${a.status==='In Progress'?'bp-progress':'bp-complete'}">${a.status}</span>`;
    showView('detail');
    // Logsheet-only users go directly to logsheet tab
    if (APP_USER.permissions.canLogsheetOnly) {
        switchTab('logsheet');
    } else {
        switchTab('overview');
    }
}

// ════════════════════════════════════
// OVERVIEW TAB
// ════════════════════════════════════
function renderOverview() {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    const td = totalDays(a), contract = calcContract(a), work = calcWork(a), exp = calcExpenses(a);
    const filled = a.filledDays.length, pct = td > 0 ? Math.round(filled/td*100) : 0;

    document.getElementById('ov-info').innerHTML = `
        <div class="row g-3 mb-3">
            <div class="col-6 col-sm-3"><div class="text-muted small">Start</div><div class="fw-semibold">${formatDate(a.startDate)}</div></div>
            <div class="col-6 col-sm-3"><div class="text-muted small">End</div><div class="fw-semibold">${formatDate(a.endDate)}</div></div>
            <div class="col-6 col-sm-3"><div class="text-muted small">Duration</div><div class="fw-semibold">${td} days</div></div>
            <div class="col-6 col-sm-3"><div class="text-muted small">Location</div><div class="fw-semibold">${a.location || '—'}</div></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-sm-4"><div class="text-muted small">Engagement</div><div class="mt-1">${engTag(a)}</div></div>
            <div class="col-sm-4"><div class="text-muted small">Rate</div><div class="fw-semibold mono text-brand">${a.engagement==='hourly'?KES(a.rate)+'/hr':a.engagement==='daily'?KES(a.rate)+'/day':KES(a.rate)+' (fixed)'}</div></div>
            <div class="col-sm-4"><div class="text-muted small">Contract</div><div class="fw-semibold mono text-accent">${KES(contract)}</div></div>
        </div>
        <div class="text-muted small mb-1">Description</div>
        <p class="mb-0" style="font-size:.85rem;line-height:1.5">${a.description || '—'}</p>`;

    document.getElementById('ov-progress').innerHTML = `
        <div class="d-flex gap-4 flex-wrap mb-3">
            <div><div class="text-muted small">Filled</div><div class="fw-bold text-accent" style="font-size:1.15rem">${filled}</div></div>
            <div><div class="text-muted small">Remaining</div><div class="fw-bold" style="font-size:1.15rem;color:var(--warn)">${td-filled}</div></div>
            <div><div class="text-muted small">%</div><div class="fw-bold text-brand" style="font-size:1.15rem">${pct}%</div></div>
        </div>
        <div style="height:7px;border-radius:999px;background:var(--border)">
            <div style="height:100%;width:${pct}%;border-radius:999px;background:linear-gradient(90deg,var(--brand),var(--accent))"></div>
        </div>`;

    document.getElementById('ov-finance').innerHTML = `
        <div class="budget-row"><span class="b-label">Contract</span><span class="b-value mono">${KES(contract)}</span></div>
        <div class="budget-row"><span class="b-label">Work Logged</span><span class="b-value mono text-brand">${KES(work)}</span></div>
        <div class="budget-row"><span class="b-label">Expenses</span><span class="b-value mono" style="color:var(--warn)">${KES(exp)}</span></div>
        <div class="divider" style="margin:.4rem 0"></div>
        <div class="d-flex justify-content-between"><span class="fw-bold" style="font-size:.85rem">Total Payable</span><span class="fw-bold mono text-accent">${KES(work+exp)}</span></div>`;

    // Inject invoice upload / summary under the Financial Summary (right column)
    // Allow all users to upload invoices; admin review panel shown only to Admin/Manager/Finance
    const canManageForInv = true;
    const finEl = document.getElementById('ov-finance');
    if (finEl) {
        finEl.innerHTML += `<div style="margin-top:.75rem" id="ov-invoice-${a.apiId}">${buildInvoiceSection(a, canManageForInv)}</div>`;
    }

    document.getElementById('ov-profile-detail').innerHTML = buildInlineProfile(a);
}

// ════════════════════════════════════
// REMOVE LOG ITEM — Admin/Manager/Finance only
// FIX: Extracted from inside template literal to proper top-level async function.
// Was previously buried inside buildInlineProfile()'s template string, making it
// unreachable at runtime and breaking all inline remove buttons.
// ════════════════════════════════════
async function removeLogItem(activityApiId, type, dk, itemIdx) {
    // Find activity by apiId since we pass apiId from the inline buttons
    const a = activities.find(x => x.apiId === activityApiId);
    if (!a || !a.logs[dk]) return;

    const list = a.logs[dk][type];
    if (!list || itemIdx < 0 || itemIdx >= list.length) return;

    if (!confirm('Remove this entry? This will be saved immediately.')) return;

    list.splice(itemIdx, 1);

    // If the day log is now fully empty, remove from filledDays
    const l = a.logs[dk];
    const isEmpty = (!l.work || !l.work.length)
        && (!l.expenses || !l.expenses.length)
        && (!l.logistics || !l.logistics.length)
        && (!l.docs || !l.docs.length);
    if (isEmpty) {
        a.filledDays = a.filledDays.filter(d => d !== dk);
        delete a.logs[dk];
    }

    try {
        const res = await fetch(`/api/field-activities/${a.apiId}/logs`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                date: dk,
                log_data: a.logs[dk] || { work: [], expenses: [], logistics: [], docs: [] }
            })
        });
        const data = await res.json();
        showToast(data?.success ? '✓ Entry removed' : '⚠ Removed locally (sync failed)');
    } catch (e) {
        showToast('⚠ Removed locally (offline)');
    }

    // Re-render overview to reflect changes
    renderOverview();
}


// ════════════════════════════════════
// PROFILE (OVERVIEW) QUICK-ADD — Expenses & Logistics
// Open to ALL roles. Approve/Reject stays gated inside approveExpenseLog().
// ════════════════════════════════════
function toggleProfileAddForm(type, apiId) {
    const f = document.getElementById('profile-' + type + '-add-' + apiId);
    if (!f) return;
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
    if (f.style.display === 'block') setTimeout(() => f.querySelector('input,select')?.focus(), 50);
}

async function confirmProfileExpAdd(apiId) {
    const a = activities.find(x => x.apiId === apiId);
    if (!a) return;
    const dk   = document.getElementById('profile-exp-date-' + apiId)?.value;
    const desc = document.getElementById('profile-exp-desc-' + apiId)?.value.trim();
    const cat  = document.getElementById('profile-exp-cat-' + apiId)?.value;
    const amt  = parseFloat(document.getElementById('profile-exp-amt-' + apiId)?.value);
    if (!dk || !desc || !amt) { showToast('Please fill in date, description and amount'); return; }
    if (!a.logs[dk]) a.logs[dk] = { work: [], expenses: [], logistics: [], docs: [] };
    if (!Array.isArray(a.logs[dk].expenses)) a.logs[dk].expenses = [];
    a.logs[dk].expenses.push({ desc, cat, amount: amt });
    if (!a.filledDays.includes(dk)) a.filledDays.push(dk);
    await syncLogDateToApi(a, dk);
    renderOverview();
    showToast('✓ Expense added');
}

async function confirmProfileLogAdd(apiId) {
    const a = activities.find(x => x.apiId === apiId);
    if (!a) return;
    const dk   = document.getElementById('profile-log-date-' + apiId)?.value;
    const from = document.getElementById('profile-log-from-' + apiId)?.value.trim();
    const to   = document.getElementById('profile-log-to-' + apiId)?.value.trim();
    const mode = document.getElementById('profile-log-mode-' + apiId)?.value;
    const cost = parseFloat(document.getElementById('profile-log-cost-' + apiId)?.value);
    if (!dk || !from || !to || !cost) { showToast('Please fill in all fields'); return; }
    if (!a.logs[dk]) a.logs[dk] = { work: [], expenses: [], logistics: [], docs: [] };
    if (!Array.isArray(a.logs[dk].logistics)) a.logs[dk].logistics = [];
    a.logs[dk].logistics.push({ from, to, mode, cost, gps: null });
    if (!a.filledDays.includes(dk)) a.filledDays.push(dk);
    await syncLogDateToApi(a, dk);
    renderOverview();
    showToast('✓ Logistics entry added');
}

// ════════════════════════════════════
// OVERVIEW REVIEW ENTRY — approve/reject a day's log from the overview tab
// Shows an optional note prompt then calls the review API
// ════════════════════════════════════
async function overviewReviewEntry(dk, status, activityApiId) {
    const a = activities.find(x => x.apiId === activityApiId);
    if (!a) return;

    // Optional note via prompt (keeps UI simple without inline form)
    const note = prompt(
        (status === 'approved' ? '✓ Approving' : '✗ Rejecting') +
        ' log for ' + formatDate(new Date(dk + 'T00:00:00')) +
        '\n\nOptional note (press OK to skip):'
    );
    // null means user cancelled the prompt
    if (note === null) return;

    try {
        const res = await fetch(`/api/field-activities/${a.apiId}/logs/${dk}/review`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status, note: note.trim() || null })
        });
        const data = await res.json();
        if (data && data.success) {
            if (!a.logs[dk]) a.logs[dk] = {};
            a.logs[dk].review = data.log_data.review;
            renderOverview();
            showToast(status === 'approved' ? '✓ Entry approved' : '✗ Entry rejected');
        } else {
            showToast('Review failed: ' + (data.message || 'unknown error'));
        }
    } catch (e) {
        showToast('Review failed — network error');
    }
}

// ════════════════════════════════════
// REVIEW SINGLE ITEM (work/expenses/logistics)
// Used by the action buttons in Overview.
// ════════════════════════════════════
async function approveExpenseLog(activityApiId, type, dk, itemIdx, status) {
    if (!['Admin', 'Manager', 'Finance'].includes(APP_USER.role)) {
        showToast('Only Admin, Manager, or Finance can review items');
        return;
    }

    const a = activities.find(x => x.apiId === activityApiId);
    if (!a || !a.logs[dk] || !Array.isArray(a.logs[dk][type])) {
        showToast('Item not found');
        return;
    }

    const list = a.logs[dk][type];
    if (itemIdx < 0 || itemIdx >= list.length) {
        showToast('Item not found');
        return;
    }

    try {
        const res = await fetch(`/api/field-activities/${a.apiId}/logs/${dk}/${type}/${itemIdx}/review`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status })
        });

        const data = await res.json();
        if (res.ok && data?.success) {
            if (type === 'work' && typeof list[itemIdx] === 'string') {
                list[itemIdx] = { desc: list[itemIdx] };
            }
            list[itemIdx].review = data.review;
            renderOverview();
            showToast(status === 'approved' ? '✓ Item approved' : '✗ Item rejected');
        } else {
            showToast(data?.message || 'Review failed');
        }
    } catch (e) {
        showToast('Review failed — network error');
    }
}

// ════════════════════════════════════
// BUILD INLINE PROFILE
// FIX: Removed the erroneously embedded removeLogItem function definition that was
// inside the Transport & Logistics ternary's else branch — it is now a proper
// top-level function above. Also fixed onclick references to pass a.apiId instead
// of a.id so removeLogItem can locate the activity correctly.
// ════════════════════════════════════
function buildInlineProfile(a) {
    const td = totalDays(a), work = calcWork(a), exp = calcExpenses(a), contract = calcContract(a);
    const pct = td > 0 ? Math.round(a.filledDays.length / td * 100) : 0;
    // Allow management for: Admin, Manager, Finance, Regional_Coordinator, activity creator, and proposed coaches
    // (Backend validates these in FieldActivityApiController::canAccessActivity)
    const canManage = ['Admin','Manager','Finance','Regional_Coordinator'].includes(APP_USER.role);
    const reviewChip = (review, pendingLabel = '⏳ Pending review') => {
        if (!review || !review.status) return `<span class="review-chip rc-pending">${pendingLabel}</span>`;
        if (review.status === 'approved') return `<span class="review-chip rc-approved">✓ Approved</span>`;
        if (review.status === 'rejected') return `<span class="review-chip rc-rejected">✗ Rejected</span>`;
        return `<span class="review-chip rc-pending">${pendingLabel}</span>`;
    };

    // ── Logsheet entries ──
    let logRows = '';
    let logCount = 0;
    [...a.filledDays].sort().forEach(dk => {
        const log = a.logs[dk] || {};
        const ws = log.work || [];
        const hrs = ws.reduce((s,w) => s + (w.hours || 0), 0);
        
        const workDesc = ws.map(w => typeof w === 'string' ? w : w.desc).filter(Boolean).join(' · ') || '—';

        let workItems = '';
        if (canManage && ws.length) {
            workItems = `<div style="margin-top:4px">` +
                ws.map((w, wi) => {
                    const desc = typeof w === 'string' ? w : w.desc;
                    const hrs2 = w.hours || 0;
                    const itemReview = typeof w === 'object' ? w.review : null;
                    return `<div style="display:flex;align-items:center;gap:6px;padding:3px 0;border-bottom:1px dashed var(--border)">
                        <span style="flex:1;font-size:.75rem">${desc}${hrs2 ? ' · ' + hrs2 + 'h' : ''}</span>
                        ${reviewChip(itemReview, '⏳ Pending')}
                        <button onclick="removeLogItem(${a.apiId},'work','${dk}',${wi})"
                            style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:.75rem;padding:0 3px"
                            title="Remove"><i class="bi bi-trash3"></i></button>
                        <button onclick="approveExpenseLog(${a.apiId},'work','${dk}',${wi},'approved')"
                            class="btn btn-sm btn-success" style="font-size:.65rem;padding:1px 5px;margin-left:2px"
                            title="Approve work"><i class="bi bi-check2"></i></button>
                        <button onclick="approveExpenseLog(${a.apiId},'work','${dk}',${wi},'rejected')"
                            class="btn btn-sm btn-danger" style="font-size:.65rem;padding:1px 5px;margin-left:2px"
                            title="Reject work"><i class="bi bi-x"></i></button>
                    </div>`;
                }).join('') +
            `</div>`;
        } else if (!canManage && ws.length) {
            // Non-admin: show review status chip only
            const chip = reviewChip(log.review);
            workItems = `<div style="font-size:.75rem;color:var(--muted)">${workDesc}</div>
                <div style="margin-top:4px">${chip}</div>`;
        } else {
            workItems = `<div style="font-size:.75rem;color:var(--muted)">${workDesc}</div>`;
        }

        logRows += `<div class="profile-log-row">
            <div class="profile-log-date"><i class="bi bi-calendar-check me-1"></i>${formatDate(new Date(dk+'T00:00:00'))}</div>
            <div style="flex:1">${workItems}</div>
            ${hrs ? `<div style="font-size:.7rem;color:var(--muted);min-width:40px">${hrs}h</div>` : ''}
            
        </div>`;
        logCount++;
    });

    // ── Expenses & logistics ──
    let expRows = '', expTotal = 0;
    let logExpRows = '', logExpTotal = 0;
    let expN = 0;

    Object.entries(a.logs).forEach(([dk, l]) => {
        (l.expenses || []).forEach((e, ei) => {
            expTotal += e.amount;
            expN++;
            const n = expN;
            const eReviewChip = reviewChip(e.review, '⏳ Pending');
            expRows += `<tr>
                <td>${n}</td>
                <td>${formatDate(new Date(dk+'T00:00:00'))}</td>
                <td>${e.desc}</td>
                <td>${e.cat}</td>
                <td class="mono text-brand">${KES(e.amount)}</td>
                <td>${eReviewChip}</td>
                <td style="white-space:nowrap">
                    <button onclick="removeLogItem(${a.apiId},'expenses','${dk}',${ei})"
                        class="btn btn-sm btn-outline-danger" style="font-size:.65rem;padding:1px 5px"
                        title="Remove expense"><i class="bi bi-trash3"></i></button>
                    ${canManage ? `<button onclick="approveExpenseLog(${a.apiId},'expenses','${dk}',${ei},'approved')"
                        class="btn btn-sm btn-success" style="font-size:.65rem;padding:1px 5px;margin-left:2px"
                        title="Approve expense"><i class="bi bi-check2"></i></button>
                    <button onclick="approveExpenseLog(${a.apiId},'expenses','${dk}',${ei},'rejected')"
                        class="btn btn-sm btn-danger" style="font-size:.65rem;padding:1px 5px;margin-left:2px"
                        title="Reject expense"><i class="bi bi-x"></i></button>` : ''}
                </td>
            </tr>`;
        });
        (l.logistics || []).forEach((lg, li) => {
            logExpTotal += lg.cost;
            expN++;
            const n = expN;
            const lgReviewChip = reviewChip(lg.review, '⏳ Pending');
            logExpRows += `<tr>
                <td>${n}</td>
                <td>${formatDate(new Date(dk+'T00:00:00'))}</td>
                <td>${lg.from} → ${lg.to}</td>
                <td>${lg.mode}</td>
                <td class="mono" style="color:var(--purple)">${KES(lg.cost)}</td>
                <td>${lgReviewChip}</td>
                <td style="white-space:nowrap">
                    <button onclick="removeLogItem(${a.apiId},'logistics','${dk}',${li})"
                        class="btn btn-sm btn-outline-danger" style="font-size:.65rem;padding:1px 5px"
                        title="Remove transport"><i class="bi bi-trash3"></i></button>
                    ${canManage ? `<button onclick="approveExpenseLog(${a.apiId},'logistics','${dk}',${li},'approved')"
                        class="btn btn-sm btn-success" style="font-size:.65rem;padding:1px 5px;margin-left:2px"
                        title="Approve logistics"><i class="bi bi-check2"></i></button>
                    <button onclick="approveExpenseLog(${a.apiId},'logistics','${dk}',${li},'rejected')"
                        class="btn btn-sm btn-danger" style="font-size:.65rem;padding:1px 5px;margin-left:2px"
                        title="Reject logistics"><i class="bi bi-x"></i></button>` : ''}
                </td>
            </tr>`;
        });
    });

    // ── Documents ──
    let docsList = '', docCount = 0;
    Object.entries(a.logs).forEach(([dk, l]) => {
        (l.docs || []).forEach(d => {
            docCount++;
            const docId = d.id || null;
            const docName = d.name || d.file_name || (typeof d === 'string' ? d : 'Document');
            const viewUrl = docId ? `/api/field-activity-documents/${docId}/view` : null;
            const downloadUrl = docId ? `/api/field-activity-documents/${docId}/download` : null;
            const isLegacy = !docId;
            docsList += `<div style="display:flex;align-items:center;gap:.5rem;padding:.35rem 0;border-bottom:1px solid var(--border);font-size:.78rem">
                <i class="bi bi-file-earmark text-brand"></i>
                <span style="flex:1">${docName}</span>
                <span style="font-size:.68rem;color:var(--muted);margin-right:.5rem">${dk}</span>
                ${downloadUrl
                    ? `<a href="${viewUrl}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:.65rem;padding:2px 7px"><i class="bi bi-eye me-1"></i>View</a>
                       <a href="${downloadUrl}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size:.65rem;padding:2px 7px"><i class="bi bi-download me-1"></i>Download</a>`
                    : `<span style="font-size:.65rem;color:#b45309">Legacy doc - re-upload in Docs tab</span>`}
                ${isLegacy ? `<span class="badge" style="background:#fff7ed;color:#9a3412;border:1px solid #fed7aa">legacy</span>` : ''}
            </div>`;
        });
    });

    // ── Fixed deliverables ──
    let fixedList = '';
    if (a.engagement === 'fixed' && a.fixedItems && a.fixedItems.length) {
        a.fixedItems.forEach(it => {
            fixedList += `<div style="display:flex;gap:.65rem;align-items:flex-start;padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:${it.done?'var(--accent)':'var(--muted)'};font-size:1rem">
                    ${it.done ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-circle"></i>'}
                </span>
                <div>
                    <div style="font-size:.82rem;font-weight:600">${it.desc}</div>
                    <div style="font-size:.68rem;color:var(--muted)">${it.cat}${it.date?' · '+it.date:''}</div>
                </div>
            </div>`;
        });
    }

    let adminActionButtons = '';
    if (canManage) {
        adminActionButtons = `<div class="d-flex gap-2 mt-2">
            ${a.adminStatus==='Pending'
                ? `<button class="btn btn-success" style="font-size:.9rem" onclick="setAdminStatus(${a.id},'approved')"><i class="bi bi-check"></i> Approve</button>
                   <button class="btn btn-danger" style="font-size:.9rem" onclick="setAdminStatus(${a.id},'rejected')"><i class="bi bi-x"></i> Reject</button>`
                : a.adminStatus==='Approved'
                    ? `<button class="btn btn-accent" style="font-size:.9rem" onclick="setAdminStatus(${a.id},'funded')"><i class="bi bi-cash me-1"></i> Pay</button>`
                    : a.adminStatus==='Paid' ? '<span class="text-muted" style="font-size:.9rem">✓ Paid</span>' : ''}
        </div>`;
    }
    return `
    <!-- Stat summary boxes 
    <div class="section-card mt-0">
        <div class="section-head"><i class="bi bi-bar-chart-line text-brand me-2"></i>Activity Summary</div>
        <div class="section-body">
            <div class="profile-stat-row">
                <div class="profile-stat-box"><div class="psv text-brand">${td}</div><div class="psl">Total Days</div></div>
                <div class="profile-stat-box"><div class="psv text-accent">${a.filledDays.length}</div><div class="psl">Days Logged</div></div>
                <div class="profile-stat-box"><div class="psv" style="color:var(--warn)">${td - a.filledDays.length}</div><div class="psl">Remaining</div></div>
                <div class="profile-stat-box"><div class="psv text-brand">${pct}%</div><div class="psl">Complete</div></div>
            </div>
            <div class="profile-progress-bar"><div class="profile-progress-fill" style="width:${pct}%"></div></div>
        </div>
    </div>-->

    <!-- Financial breakdown -->
    <div class="section-card">
        <div class="section-head"><i class="bi bi-calculator text-brand me-2"></i>Financial Breakdown</div>
        <div class="section-body">
            <div class="profile-stat-row">
                <div class="profile-stat-box"><div class="psv mono">${KES(contract)}</div><div class="psl">Contract Value</div></div>
                <div class="profile-stat-box"><div class="psv mono text-brand">${KES(work)}</div><div class="psl">Work Logged</div></div>
                <div class="profile-stat-box"><div class="psv mono" style="color:var(--warn)">${KES(exp)}</div><div class="psl">Expenses</div></div>
                <div class="profile-stat-box" style="border-color:var(--accent)"><div class="psv mono text-accent">${KES(work+exp)}</div><div class="psl">Total Payable</div></div>
            </div>
        </div>
    </div>

    <!-- Personnel info -->
    <div class="section-card">
        <div class="section-head"><i class="bi bi-person text-brand me-2"></i>Personnel &amp; Status</div>
        <div class="section-body">
            <div class="profile-info-grid">
                <div class="profile-info-item"><div class="pil">Person</div><div class="piv">${a.person}</div></div>
                <div class="profile-info-item"><div class="pil">Engagement</div><div class="piv" style="text-transform:capitalize">${a.engagement}</div></div>
                <div class="profile-info-item"><div class="pil">Rate</div><div class="piv mono text-brand">${a.engagement==='hourly'?KES(a.rate)+'/hr':a.engagement==='daily'?KES(a.rate)+'/day':KES(a.rate)+' (fixed)'}</div></div>
                <div class="profile-info-item"><div class="pil">Start Date</div><div class="piv">${formatDate(a.startDate)}</div></div>
                <div class="profile-info-item"><div class="pil">End Date</div><div class="piv">${formatDate(a.endDate)}</div></div>
                <div class="profile-info-item"><div class="pil">Location</div><div class="piv">${a.location || '—'}</div></div>
            </div>
        </div>
    </div>

    <!-- Log Entries -->
    <div class="section-card">
        <div class="section-head">
            <span><i class="bi bi-journal-text text-brand me-2"></i>Log Entries (${logCount} ${logCount===1?'entry':'entries'})</span>
            <button class="btn btn-sm btn-brand" onclick="switchTab('logsheet')"><i class="bi bi-plus me-1"></i>Add Entry</button>
        </div>
        <div class="section-body">
            ${a.engagement==='fixed' && a.fixedItems && a.fixedItems.length
                ? `<div>${fixedList}</div>`
                : logRows
                    ? `<div>${logRows}</div>`
                    : `<div class="no-data-row"><i class="bi bi-journal-x me-1"></i>No log entries yet — <a href="javascript:void(0)" onclick="switchTab('logsheet')" style="color:var(--brand)">open logsheet to add</a></div>`}
        </div>
    </div>

   
    <!-- General Expenses -->
    <div class="section-card">
        <div class="section-head">
            <span><i class="bi bi-receipt text-accent me-2"></i>General Expenses</span>
            <div class="d-flex align-items-center gap-2">
                ${expTotal ? `<span class="mono fw-bold text-accent">${KES(expTotal)}</span>` : ''}
                <button class="btn btn-sm btn-outline-primary" style="font-size:.7rem;padding:2px 8px" onclick="toggleProfileAddForm('exp',${a.apiId})"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
        </div>
        <div class="section-body">
            <div id="profile-exp-add-${a.apiId}" class="inline-add-form mb-2" style="display:none">
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>Date</label><input type="date" id="profile-exp-date-${a.apiId}" class="form-control" value="${dateKey(today)}" min="${dateKey(a.startDate)}" max="${dateKey(a.endDate)}"></div>
                    <div class="col-6"><label>Category</label><select id="profile-exp-cat-${a.apiId}" class="form-select"><option>Meals</option><option>Materials</option><option>Venue</option><option>Other</option></select></div>
                </div>
                <div class="mb-2"><label>Description</label><input type="text" id="profile-exp-desc-${a.apiId}" class="form-control" placeholder="Expense description"></div>
                <div class="mb-2"><label>Amount</label><input type="number" id="profile-exp-amt-${a.apiId}" class="form-control" placeholder="0" min="0"></div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmProfileExpAdd(${a.apiId})"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleProfileAddForm('exp',${a.apiId})">Cancel</button>
                </div>
            </div>
            <div style="${expRows?'padding:0':''}">
            ${expRows
                ? `<table class="profile-exp-table">
                    <thead><tr><th>#</th><th>Date</th><th>Description</th><th>Category</th><th>Amount</th><th>Review</th>${canManage?'<th></th>':''}</tr></thead>
                    <tbody>${expRows}</tbody>
                    <tfoot><tr><td colspan="${canManage?6:5}" style="padding:.4rem .6rem">Total</td><td class="mono text-brand" style="padding:.4rem .6rem">${KES(expTotal)}</td>${canManage?'<td></td>':''}</tr></tfoot>
                   </table>`
                : `<div class="no-data-row"><i class="bi bi-receipt me-1"></i>No expense entries</div>`}
            </div>
        </div>
    </div>

   
    <!-- Transport & Logistics -->
    <div class="section-card">
        <div class="section-head">
            <span><i class="bi bi-truck me-2" style="color:var(--purple)"></i>Transport &amp; Logistics</span>
            <div class="d-flex align-items-center gap-2">
                ${logExpTotal ? `<span class="mono fw-bold" style="color:var(--purple)">${KES(logExpTotal)}</span>` : ''}
                <button class="btn btn-sm btn-outline-primary" style="font-size:.7rem;padding:2px 8px" onclick="toggleProfileAddForm('log',${a.apiId})"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
        </div>
        <div class="section-body">
            <div id="profile-log-add-${a.apiId}" class="inline-add-form mb-2" style="display:none">
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>Date</label><input type="date" id="profile-log-date-${a.apiId}" class="form-control" value="${dateKey(today)}" min="${dateKey(a.startDate)}" max="${dateKey(a.endDate)}"></div>
                    <div class="col-6"><label>Mode</label><select id="profile-log-mode-${a.apiId}" class="form-select"><option>Bus</option><option>Matatu</option><option>Car</option><option>Boda</option><option>Taxi</option></select></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>From</label><input type="text" id="profile-log-from-${a.apiId}" class="form-control" placeholder="Departure"></div>
                    <div class="col-6"><label>To</label><input type="text" id="profile-log-to-${a.apiId}" class="form-control" placeholder="Destination"></div>
                </div>
                <div class="mb-2"><label>Cost</label><input type="number" id="profile-log-cost-${a.apiId}" class="form-control" placeholder="0" min="0"></div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmProfileLogAdd(${a.apiId})"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleProfileAddForm('log',${a.apiId})">Cancel</button>
                </div>
            </div>
            <div style="${logExpRows?'padding:0':''}">
            ${logExpRows
                ? `<table class="profile-exp-table">
                    <thead><tr><th>#</th><th>Date</th><th>Route</th><th>Mode</th><th>Cost</th><th>Review</th>${canManage?'<th></th>':''}</tr></thead>
                    <tbody>${logExpRows}</tbody>
                    <tfoot><tr><td colspan="${canManage?6:5}" style="padding:.4rem .6rem">Total</td><td class="mono" style="padding:.4rem .6rem;color:var(--purple)">${KES(logExpTotal)}</td>${canManage?'<td></td>':''}</tr></tfoot>
                   </table>`
                : `<div class="no-data-row"><i class="bi bi-truck me-1"></i>No transport entries</div>`}
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="section-card">
        <div class="section-head">
            <span><i class="bi bi-folder2-open text-brand me-2"></i>Documents (${docCount})</span>
            <button class="btn btn-sm btn-outline-primary" onclick="switchTab('docs')"><i class="bi bi-upload me-1"></i>Upload</button>
        </div>
        <div class="section-body">
            ${docsList || `<div class="no-data-row"><i class="bi bi-folder2 me-1"></i>No documents uploaded</div>`}
        </div>
    </div>

    <!-- Grand Total footer -->
    <div style="background:linear-gradient(135deg,var(--brand),var(--brand-mid));color:#fff;border-radius:var(--radius);padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem">
        <div style="font-size:.85rem;opacity:.85">Grand Total Payable · ${a.person}</div>
        <div style="font-size:1.4rem;font-weight:700;font-family:'IBM Plex Mono',monospace">${KES(work+exp)}</div>
    </div>
    ${adminActionButtons}
    `;
}

// ════════════════════════════════════
// openProfileModal — redirects to inline detail view (no popup)
// ════════════════════════════════════
function openProfileModal(idx) {
    openActivityDetail(idx);
}


function printActivityProfile() {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    const url = `/field-activities/${a.apiId}/invoice.pdf`;
    window.open(url, '_blank');
}
// ════════════════════════════════════
// LOGSHEET ROUTING
// ════════════════════════════════════
function renderLogsheet() {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    const c = document.getElementById('logsheet-container');
    if (a.engagement === 'daily') renderDailyLogsheet(a, c);
    else if (a.engagement === 'hourly') renderHourlyLogsheet(a, c);
    else renderFixedLogsheet(a, c);
}

// ════════════════════════════════════
// DAILY LOGSHEET
// ════════════════════════════════════
function renderDailyLogsheet(a, c) {
    logMonthOffset = 0;
    c.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div><div class="fw-bold" style="font-size:1rem"><i class="bi bi-calendar-day me-2 text-brand"></i>Daily Logsheet</div>
        <div class="text-muted small" id="logsheet-rate-label">Click a date to log work · ${KES(a.rate)}/day</div></div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="d-flex gap-2 flex-wrap" style="font-size:.68rem">
                <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;border-radius:3px;background:#d1fae5;border:1px solid #065f46;display:inline-block"></span>Filled</span>
                <span class="d-flex align-items-center gap-1"><span style="width:10px;height:10px;border-radius:3px;background:#fff1f2;border:1px solid #991b1b;display:inline-block"></span>Open</span>
            </div>
            <div class="d-flex align-items-center gap-2" style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:4px 10px">
                <span style="font-size:.7rem;font-weight:600;color:var(--muted)">Log by:</span>
                <button id="log-mode-day" onclick="setLogMode('day')"
                    style="font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:6px;border:none;cursor:pointer;background:var(--brand);color:#fff;transition:all .15s">
                    <i class="bi bi-calendar-check me-1"></i>Day
                </button>
                <button id="log-mode-hours" onclick="setLogMode('hours')"
                    style="font-size:.72rem;font-weight:600;padding:3px 10px;border-radius:6px;border:none;cursor:pointer;background:transparent;color:var(--muted);transition:all .15s">
                    <i class="bi bi-clock me-1"></i>Hours
                </button>
            </div>
        </div></div>
        <div class="section-card">
            <div class="section-head d-flex justify-content-between align-items-center">
                <button class="btn btn-sm btn-outline-secondary" onclick="logMonthOffset--;renderDailyCalendar()"><i class="bi bi-chevron-left"></i></button>
                <span class="fw-bold" id="log-month-label"></span>
                <button class="btn btn-sm btn-outline-secondary" onclick="logMonthOffset++;renderDailyCalendar()"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="section-body" style="padding:.4rem">
                <div class="tl-cal-grid mb-1">
                    <div class="tl-cal-head">S</div><div class="tl-cal-head">M</div><div class="tl-cal-head">T</div>
                    <div class="tl-cal-head">W</div><div class="tl-cal-head">T</div><div class="tl-cal-head">F</div><div class="tl-cal-head">S</div>
                </div>
                <div class="tl-cal-grid" id="log-cal-body"></div>
            </div>
        </div>
        <div class="section-card mt-2">
            <div class="section-head">
                <span><i class="bi bi-list-check text-brand me-2"></i>Entries (${a.filledDays.length} days · <span class="mono text-brand">${KES(calcWork(a))}</span>)</span>
            </div>
            <div class="section-body" id="logsheet-entries-list"></div>
        </div>`;
    renderDailyCalendar();
}

function renderDailyCalendar() {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    const base = new Date(a.startDate);
    base.setMonth(base.getMonth() + logMonthOffset);
    const y = base.getFullYear(), m = base.getMonth();
    document.getElementById('log-month-label').textContent = new Date(y,m).toLocaleDateString('en-US',{month:'long',year:'numeric'});
    const c = document.getElementById('log-cal-body');
    c.innerHTML = '';
    const fd = new Date(y,m,1).getDay(), dim = new Date(y,m+1,0).getDate();
    for (let i=0;i<fd;i++) { const d=document.createElement('div'); d.className='tl-cal-day outside'; c.appendChild(d); }
    for (let d=1;d<=dim;d++) {
        const date = new Date(y,m,d), key = dateKey(date);
        const inR = date >= a.startDate && date <= a.endDate;
        const isFilled = a.filledDays.includes(key);
        const isToday = date.toDateString() === today.toDateString();
        const isPast = date <= today;
        const cell = document.createElement('div');
        cell.className = 'tl-cal-day';
        if (!inR) cell.classList.add('inactive');
        else if (isFilled) cell.classList.add('filled');
        else if (isPast||isToday) cell.classList.add('unfilled');
        else cell.classList.add('inactive');
        if (isToday && inR) { cell.classList.remove('inactive'); cell.classList.add('today'); if(!isFilled) cell.classList.add('unfilled'); }
        if (inR && (isPast||isToday)) { cell.style.cursor='pointer'; cell.onclick = () => openDayPanel(key, a); }
        cell.innerHTML = `<div class="tl-cal-day-num">${d}</div>${inR?`<div class="tl-cal-status">${isFilled?'✓':'○'}</div>`:''}`;
        c.appendChild(cell);
    }
    const el = document.getElementById('logsheet-entries-list');
    const keys = [...a.filledDays].sort().reverse();
    if (!keys.length) { el.innerHTML='<div class="text-muted text-center py-3" style="font-size:.82rem">Click a date above to start</div>'; return; }
    el.innerHTML = keys.map(k => {
        const log = a.logs[k] || {work:[]};
        const ws = log.work || [];
        const review = log.review;
        const canReview = ['Admin','Manager','Finance'].includes(APP_USER.role);
        const chipHtml = review
            ? `<span class="review-chip ${review.status==='approved'?'rc-approved':'rc-rejected'}">
                  ${review.status==='approved'?'✓ Approved':'✗ Rejected'}
                  ${review.by ? `· ${review.by}`:''}</span>`
            : `<span class="review-chip rc-pending">⏳ Pending review</span>`;
        const reviewControls = canReview ? `
            <div class="review-panel" id="review-panel-${k}" style="display:none">
                <label>Review action</label>
                <textarea id="review-note-${k}" class="form-control form-control-sm" rows="2"
                    placeholder="Optional note…" style="font-size:.78rem"></textarea>
                <div class="d-flex gap-2 mt-1">
                    <button class="btn btn-sm btn-success" style="font-size:.72rem"
                        onclick="submitLogReview('${k}','approved')">
                        <i class="bi bi-check2 me-1"></i>Approve
                    </button>
                    <button class="btn btn-sm btn-danger" style="font-size:.72rem"
                        onclick="submitLogReview('${k}','rejected')">
                        <i class="bi bi-x-lg me-1"></i>Reject
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" style="font-size:.72rem"
                        onclick="document.getElementById('review-panel-${k}').style.display='none'">
                        Cancel
                    </button>
                </div>
            </div>
            <div class="mt-1">
                <button class="btn btn-sm btn-outline-secondary" style="font-size:.68rem;padding:1px 6px"
                    onclick="document.getElementById('review-panel-${k}').style.display=
                        document.getElementById('review-panel-${k}').style.display==='none'?'flex':'none'">
                    <i class="bi bi-pencil-square me-1"></i>Review
                </button>
            </div>` : '';
        return `<div class="log-entry" style="cursor:default">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                <span class="fw-bold" style="font-size:.82rem">
                    <i class="bi bi-calendar-check text-accent me-1"></i>
                    ${formatDate(new Date(k+'T00:00:00'))}
                </span>
                ${chipHtml}
            </div>
            <div style="font-size:.75rem;color:var(--muted)">
                ${ws.length ? ws.map(w=>typeof w==='string'?w:w.desc).join(' · ') : '—'}
            </div>
            ${review?.note ? `<div style="font-size:.7rem;color:var(--muted);margin-top:3px">
                <i class="bi bi-chat-text me-1"></i>${review.note}</div>` : ''}
            ${reviewControls}
        </div>`;
    }).join('');
}



function setLogMode(mode) {
    logMode = mode;
    // Update button styles
    const dayBtn = document.getElementById('log-mode-day');
    const hrsBtn = document.getElementById('log-mode-hours');
    if (dayBtn && hrsBtn) {
        if (mode === 'day') {
            dayBtn.style.background = 'var(--brand)'; dayBtn.style.color = '#fff';
            hrsBtn.style.background = 'transparent'; hrsBtn.style.color = 'var(--muted)';
        } else {
            hrsBtn.style.background = 'var(--brand)'; hrsBtn.style.color = '#fff';
            dayBtn.style.background = 'transparent'; dayBtn.style.color = 'var(--muted)';
        }
    }
    // Update subtitle hint
    const a = activities[currentActivity];
    const lbl = document.getElementById('logsheet-rate-label');
    if (lbl && a) {
        lbl.textContent = mode === 'day'
            ? `Click a date to log work · ${KES(a.rate)}/day`
            : `Click a date to log hours worked · ${KES(a.rate)}/hr rate`;
    }
}

// ════════════════════════════════════
// HOURLY LOGSHEET
// ════════════════════════════════════
function renderHourlyLogsheet(a, c) {
    let d = new Date(today);
    if (d < a.startDate) d = new Date(a.startDate);
    if (d > a.endDate) d = new Date(a.endDate);
    currentHourlyDateKey = dateKey(d);
    c.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div><div class="fw-bold" style="font-size:1rem"><i class="bi bi-clock me-2 text-brand"></i>Hourly Logsheet</div>
        <div class="text-muted small">Log hours per time slot · ${KES(a.rate)}/hr</div></div></div>
        <div class="section-card mb-2">
            <div class="section-head d-flex justify-content-between align-items-center">
                <span id="hourly-date-label">${formatDate(d)}</span>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-secondary" onclick="changeHourlyDate(-1)"><i class="bi bi-chevron-left"></i></button>
                    <input type="date" id="hourly-date-picker" class="form-control form-control-sm" style="max-width:130px;font-size:.78rem"
                        value="${currentHourlyDateKey}" min="${dateKey(a.startDate)}" max="${dateKey(a.endDate)}"
                        onchange="currentHourlyDateKey=this.value;renderHourlyTimeline()">
                    <button class="btn btn-sm btn-outline-secondary" onclick="changeHourlyDate(1)"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="section-body" style="padding:.5rem .85rem">
                <div class="hour-timeline" id="hourly-timeline-body"></div>
            </div>
        </div>
        <div class="section-card">
            <div class="section-head"><i class="bi bi-bar-chart text-brand me-2"></i>Summary</div>
            <div class="section-body" id="hourly-summary"></div>
        </div>`;
    renderHourlyTimeline();
}

function changeHourlyDate(dir) {
    if (!currentHourlyDateKey) return;
    const d = new Date(currentHourlyDateKey + 'T00:00:00');
    d.setDate(d.getDate() + dir);
    const a = activities[currentActivity];
    if (d < a.startDate || d > a.endDate) return;
    currentHourlyDateKey = dateKey(d);
    document.getElementById('hourly-date-picker').value = currentHourlyDateKey;
    renderHourlyTimeline();
}

function renderHourlyTimeline() {
    if (currentActivity === null || !currentHourlyDateKey) return;
    const a = activities[currentActivity];
    document.getElementById('hourly-date-label').textContent = formatDate(new Date(currentHourlyDateKey+'T00:00:00'));
    const log = a.logs[currentHourlyDateKey] || {work:[]};
    const wi = log.work || [];
    const b = document.getElementById('hourly-timeline-body');
    b.innerHTML = '';
    for (let h=6;h<=21;h++) {
        const lbl = h<12?h+'am':h===12?'12pm':(h-12)+'pm';
        const se = wi.filter(w => w.startHour === h);
        b.innerHTML += `<div class="hour-row">
            <span class="hour-label">${lbl}</span>
            <div class="hour-dot ${se.length?'has-entry':''}"></div>
            <div class="hour-content">
                ${se.map(w=>`<span class="hour-entry-pill">
                    <i class="bi bi-clock" style="font-size:.6rem"></i>
                    ${w.desc.length>25?w.desc.substring(0,25)+'…':w.desc}
                    <span style="font-size:.6rem;color:var(--muted)">${w.hours}h</span>
                    <button class="remove-hour" onclick="removeHourEntry(${wi.indexOf(w)})"><i class="bi bi-x"></i></button>
                </span>`).join('')}
                <button class="hour-add-btn" onclick="showHourAddForm(${h},this)"><i class="bi bi-plus" style="font-size:.7rem"></i>Add</button>
            </div>
        </div>`;
    }
    const th = wi.reduce((s,w) => s+(w.hours||0), 0);
    document.getElementById('hourly-summary').innerHTML = `<div class="d-flex gap-4 flex-wrap">
        <div><div class="text-muted small">Hours Today</div><div class="fw-bold" style="font-size:1.1rem;color:var(--brand)">${th}h</div></div>
        <div><div class="text-muted small">Total Hours All Days</div><div class="fw-bold mono text-brand">${Object.values(a.logs).reduce((s,l)=>s+(l.work||[]).reduce((ss,w)=>ss+(w.hours||0),0),0)}h</div></div>
    </div>`;
}

function showHourAddForm(hour, btn) {
    document.querySelectorAll('.hour-add-inline-form').forEach(e => e.remove());
    const lbl = hour<12?hour+'am':hour===12?'12pm':(hour-12)+'pm';
    const f = document.createElement('div');
    f.className = 'inline-add-form hour-add-inline-form mt-1';
    f.innerHTML = `<div class="mb-2"><label>Activity at ${lbl}</label><input type="text" class="form-control" id="h-add-desc" placeholder="What was done?"></div>
        <div class="mb-2"><label>Hours</label><input type="number" class="form-control" id="h-add-dur" value="1" min="0.5" max="8" step="0.5"></div>
        <div class="d-flex gap-2">
            <button class="btn-add-confirm" onclick="confirmHourAdd(${hour})"><i class="bi bi-check me-1"></i>Save</button>
            <button class="btn-cancel" onclick="this.closest('.hour-add-inline-form').remove()">Cancel</button>
        </div>`;
    btn.closest('.hour-content').appendChild(f);
    setTimeout(() => f.querySelector('input')?.focus(), 50);
}

function confirmHourAdd(hour) {
    const a = activities[currentActivity];
    const desc = document.getElementById('h-add-desc')?.value.trim();
    const hours = parseFloat(document.getElementById('h-add-dur')?.value);
    if (!desc || !hours) return;
    if (!a.logs[currentHourlyDateKey]) a.logs[currentHourlyDateKey] = {work:[],expenses:[],logistics:[],docs:[]};
    a.logs[currentHourlyDateKey].work.push({desc, hours, startHour: hour});
    if (!a.filledDays.includes(currentHourlyDateKey)) a.filledDays.push(currentHourlyDateKey);
    renderHourlyTimeline();
    showToast('✓ ' + hours + 'h logged');
}

function removeHourEntry(idx) {
    const a = activities[currentActivity];
    if (a.logs[currentHourlyDateKey]?.work) {
        a.logs[currentHourlyDateKey].work.splice(idx, 1);
        if (!a.logs[currentHourlyDateKey].work.length) a.filledDays = a.filledDays.filter(d => d !== currentHourlyDateKey);
        renderHourlyTimeline();
    }
}

// ════════════════════════════════════
// FIXED LOGSHEET
// ════════════════════════════════════
function renderFixedLogsheet(a, c) {
    const items = a.fixedItems || [];
    c.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div><div class="fw-bold" style="font-size:1rem"><i class="bi bi-list-task me-2 text-brand"></i>Fixed Engagement</div>
        <div class="text-muted small">Record deliverables · ${KES(a.rate)} total</div></div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-brand" onclick="showFixedAddForm()"><i class="bi bi-plus me-1"></i>Add</button>
        </div>
    </div>
    <div id="fixed-add-form" style="display:none" class="inline-add-form mb-3">
        <div class="mb-2"><label>Deliverable</label><input type="text" id="fixed-add-desc" class="form-control" placeholder="Describe work…"></div>
        <div class="row g-2 mb-2">
            <div class="col-6"><label>Date</label><input type="date" id="fixed-add-date" class="form-control" value="${dateKey(today)}"></div>
            <div class="col-6"><label>Category</label><select id="fixed-add-cat" class="form-select"><option>Field Work</option><option>Meeting</option><option>Reporting</option><option>Training</option><option>Other</option></select></div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn-add-confirm" onclick="confirmFixedAdd()"><i class="bi bi-check me-1"></i>Save</button>
            <button class="btn-cancel" onclick="hideFixedAddForm()">Cancel</button>
        </div>
    </div>
    <div id="fixed-list"></div>
    <div id="fixed-empty" class="text-muted text-center py-4" style="font-size:.82rem;${items.length?'display:none':''}">
        <i class="bi bi-list-task d-block mb-2" style="font-size:1.8rem;color:var(--border)"></i>No deliverables yet
    </div>`;
    renderFixedItems();
}

function renderFixedItems() {
    const a = activities[currentActivity];
    const items = a.fixedItems || [];
    const c = document.getElementById('fixed-list');
    if (!c) return;
    c.innerHTML = '';
    const empty = document.getElementById('fixed-empty');
    if (!items.length) { if(empty) empty.style.display='block'; return; }
    if (empty) empty.style.display = 'none';
    items.forEach((it, i) => {
        c.innerHTML += `<div class="fixed-milestone ${it.done?'done':''}">
            <div class="milestone-check" onclick="activities[${currentActivity}].fixedItems[${i}].done=!activities[${currentActivity}].fixedItems[${i}].done;renderFixedItems()">
                ${it.done?'<i class="bi bi-check" style="font-size:.8rem"></i>':''}
            </div>
            <div class="milestone-body flex-grow-1">
                <div class="fw-semibold" style="font-size:.82rem">${it.desc}</div>
                <div class="text-muted" style="font-size:.7rem"><i class="bi bi-tag me-1"></i>${it.cat}${it.date?' · '+it.date:''}</div>
            </div>
            <button class="milestone-remove" onclick="activities[${currentActivity}].fixedItems.splice(${i},1);renderFixedItems()"><i class="bi bi-x-circle"></i></button>
        </div>`;
    });
}

function showFixedAddForm() {
    const f=document.getElementById('fixed-add-form');
    f.style.display=f.style.display==='none'?'block':'none';
    if(f.style.display==='block') setTimeout(()=>document.getElementById('fixed-add-desc')?.focus(),50);
}
function hideFixedAddForm() {
    document.getElementById('fixed-add-form').style.display='none';
    document.getElementById('fixed-add-desc').value='';
}
function confirmFixedAdd() {
    const a = activities[currentActivity];
    const desc = document.getElementById('fixed-add-desc').value.trim();
    if (!desc) return;
    if (!a.fixedItems) a.fixedItems = [];
    a.fixedItems.push({desc, date:document.getElementById('fixed-add-date').value, cat:document.getElementById('fixed-add-cat').value, done:false});
    const date = document.getElementById('fixed-add-date').value;
    if (date && !a.filledDays.includes(date)) a.filledDays.push(date);
    hideFixedAddForm();
    renderFixedItems();
    showToast('✓ Recorded');
}

// ════════════════════════════════════
// DAY PANEL
// ════════════════════════════════════
function openDayPanel(key, a) {
    currentDayKey = key;
    const date = new Date(key + 'T00:00:00');
    document.getElementById('day-panel-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    document.getElementById('dp-title').textContent = 'Logsheet – ' + formatDate(date);
    document.getElementById('dp-subtitle').textContent = logMode === 'hours' ? 'Hourly mode — click a slot to log' : 'Daily mode';
    const log = a.logs[key] || {work:[],expenses:[],logistics:[],docs:[]};
    const body = document.getElementById('dp-body');

    // ── HOURS MODE: show hour-slot timeline ──
    if (logMode === 'hours') {
        const wi = log.work || [];
        let timelineHtml = '';
        // Build dynamic timeline — slots advance by each entry's actual hours
const hLabel = h => h < 12 ? h + 'am' : h === 12 ? '12pm' : (h-12) + 'pm';

// Sort entries by startHour so timeline is ordered
const sortedEntries = [...wi].sort((a,b) => (a.startHour||0) - (b.startHour||0));

// Build a set of occupied hour ranges so we can skip them
const occupiedRanges = sortedEntries.map(e => ({
    start: e.startHour || 6,
    end: (e.startHour || 6) + (e.hours || 1),
    entry: e
}));

// Walk the timeline from 6am to 10pm dynamically
let cursor = 6;
while (cursor < 22) {
    // Check if this cursor is inside an existing entry
    const active = occupiedRanges.find(r => r.start === cursor);
    const blocked = !active && occupiedRanges.some(r => cursor > r.start && cursor < r.end);

    if (blocked) {
        cursor++;
        continue; // skip hours consumed by a multi-hour entry
    }

    const slotStart = cursor;
    const slotEnd = active ? slotStart + active.entry.hours : slotStart + 1;
    const slotLabel = hLabel(slotStart) + ' – ' + hLabel(slotEnd);
    const earning = 0; // not displayed

    timelineHtml += `
    <div style="display:flex;align-items:flex-start;gap:.6rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
        <div style="min-width:90px;font-size:.7rem;font-weight:700;color:${active?'var(--brand)':'var(--muted)'};font-family:'IBM Plex Mono',monospace;padding-top:3px">${slotLabel}</div>
        ${active
            ? `<div style="flex:1;background:var(--brand-lite);border:1px solid #bfdbfe;border-radius:6px;padding:5px 10px;font-size:.78rem">
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <span><strong>${active.entry.hours}h</strong> · ${active.entry.desc}</span>
                    <div style="display:flex;align-items:center;gap:6px">
                        <button onclick="removeDpHourSlot(${slotStart})" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:.75rem;padding:0"><i class="bi bi-x-circle"></i></button>
                    </div>
                </div>
               </div>`
            : `<button onclick="showDpHourSlotForm(${slotStart}, this)"
                style="flex:1;background:none;border:1px dashed var(--border);border-radius:6px;padding:4px 10px;font-size:.72rem;color:var(--muted);cursor:pointer;text-align:left;transition:all .15s"
                onmouseover="this.style.borderColor='var(--brand)';this.style.color='var(--brand)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
                <i class="bi bi-plus me-1" style="font-size:.7rem"></i>Add task for ${hLabel(slotStart)}
               </button>`}
    </div>
    <div id="dp-slot-form-${slotStart}" style="display:none;padding:.4rem 0 .4rem 98px"></div>`;

    cursor = slotEnd;
}

// Add a "+" button to append a new slot after the last entry
const lastOccupied = occupiedRanges.length ? Math.max(...occupiedRanges.map(r => r.end)) : 6;
if (lastOccupied < 22) {
    timelineHtml += `
    <div style="padding:.5rem 0;margin-top:.25rem">
        <button onclick="showDpHourSlotForm(${lastOccupied}, this)"
            style="background:var(--surface);border:1px dashed var(--border);border-radius:6px;padding:5px 14px;font-size:.72rem;color:var(--muted);cursor:pointer;width:100%;text-align:center;transition:all .15s"
            onmouseover="this.style.borderColor='var(--brand)';this.style.color='var(--brand)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
            <i class="bi bi-plus-circle me-1"></i>Add next slot (from ${hLabel(lastOccupied)})
        </button>
        <div id="dp-slot-form-${lastOccupied}" style="display:none;padding:.4rem 0"></div>
    </div>`;
}

        body.innerHTML = `
        <div class="mb-3">
            <div class="fw-bold mb-2" style="font-size:.88rem"><i class="bi bi-clock text-brand me-2"></i>Hour-by-hour log</div>
            <div style="font-size:.72rem;color:var(--muted);margin-bottom:.75rem">
                <i class="bi bi-info-circle me-1"></i>Click a slot to record what was done · <strong>${KES(a.rate)}/hr</strong>
            </div>
            ${timelineHtml}
        </div>
        <div class="divider"></div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:.88rem"><i class="bi bi-receipt text-accent me-2"></i>Expenses</div>
                <button class="btn btn-sm btn-outline-primary" onclick="toggleDpForm('exp')"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
            <div id="dp-exp-form" class="inline-add-form mb-2" style="display:none">
                <div class="mb-2"><label>Description</label><input type="text" id="dp-e-desc" class="form-control" placeholder="Expense"></div>
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>Category</label><select id="dp-e-cat" class="form-select"><option>Meals</option><option>Materials</option><option>Venue</option><option>Other</option></select></div>
                    <div class="col-6"><label>Amount</label><input type="number" id="dp-e-amt" class="form-control" placeholder="0" min="0"></div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmDpExp()"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleDpForm('exp')">Cancel</button>
                </div>
            </div>
            <div id="dp-exp-list">${dpExpHtml(log)}</div>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:.88rem"><i class="bi bi-truck me-2" style="color:var(--purple)"></i>Logistics</div>
                <button class="btn btn-sm btn-outline-primary" onclick="toggleDpForm('log')"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
            <div id="dp-log-form" class="inline-add-form mb-2" style="display:none">
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>From</label><input type="text" id="dp-l-from" class="form-control" placeholder="Departure"></div>
                    <div class="col-6"><label>To</label><input type="text" id="dp-l-to" class="form-control" placeholder="Destination"></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>Mode</label><select id="dp-l-mode" class="form-select"><option>Bus</option><option>Matatu</option><option>Car</option><option>Boda</option><option>Taxi</option></select></div>
                    <div class="col-6"><label>Cost</label><input type="number" id="dp-l-cost" class="form-control" placeholder="0" min="0"></div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmDpLog()"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleDpForm('log')">Cancel</button>
                </div>
            </div>
            <div id="dp-log-list">${dpLogHtml(log)}</div>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
            <div class="fw-bold mb-2" style="font-size:.88rem"><i class="bi bi-cloud-upload text-brand me-2"></i>Documents</div>
            <div class="upload-drop" onclick="document.getElementById('dp-file').click()" style="padding:.6rem">
                <i class="bi bi-cloud-upload me-1" style="color:var(--muted)"></i>
                <span style="font-size:.8rem;color:var(--muted)">Upload</span>
            </div>
            <input type="file" id="dp-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple style="display:none" onchange="handleDpUpload(this)">
            <div id="dp-doc-list" class="mt-1">
                ${(log.docs||[]).map(d=>'<div style="font-size:.72rem;color:var(--muted);padding:2px 0">📎 '+(typeof d==='string'?d:(d.name||d.file_name||'Document'))+'</div>').join('')}
            </div>
        </div>
        <button class="btn btn-accent w-100 fw-bold" onclick="saveDayLog()" style="padding:.75rem">
            <i class="bi bi-check2-all me-2"></i>Save Day Log
        </button>`;
        return;
    }

    // ── DAY MODE: original form ──
    body.innerHTML = `
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:.88rem"><i class="bi bi-clipboard-check text-brand me-2"></i>Work</div>
                <button class="btn btn-sm btn-brand" onclick="toggleDpForm('work')"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
            <div id="dp-work-form" class="inline-add-form mb-2" style="display:none">
                <div class="mb-2"><label>Description</label><input type="text" id="dp-w-desc" class="form-control" placeholder="What was done?"></div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmDpWork()"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleDpForm('work')">Cancel</button>
                </div>
            </div>
            <div id="dp-work-list">${dpWorkHtml(a, log)}</div>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:.88rem"><i class="bi bi-receipt text-accent me-2"></i>Expenses</div>
                <button class="btn btn-sm btn-outline-primary" onclick="toggleDpForm('exp')"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
            <div id="dp-exp-form" class="inline-add-form mb-2" style="display:none">
                <div class="mb-2"><label>Description</label><input type="text" id="dp-e-desc" class="form-control" placeholder="Expense"></div>
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>Category</label><select id="dp-e-cat" class="form-select"><option>Meals</option><option>Materials</option><option>Venue</option><option>Other</option></select></div>
                    <div class="col-6"><label>Amount</label><input type="number" id="dp-e-amt" class="form-control" placeholder="0" min="0"></div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmDpExp()"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleDpForm('exp')">Cancel</button>
                </div>
            </div>
            <div id="dp-exp-list">${dpExpHtml(log)}</div>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold" style="font-size:.88rem"><i class="bi bi-truck me-2" style="color:var(--purple)"></i>Logistics</div>
                <button class="btn btn-sm btn-outline-primary" onclick="toggleDpForm('log')"><i class="bi bi-plus me-1"></i>Add</button>
            </div>
            <div id="dp-log-form" class="inline-add-form mb-2" style="display:none">
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>From</label><input type="text" id="dp-l-from" class="form-control" placeholder="Departure"></div>
                    <div class="col-6"><label>To</label><input type="text" id="dp-l-to" class="form-control" placeholder="Destination"></div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6"><label>Mode</label><select id="dp-l-mode" class="form-select"><option>Bus</option><option>Matatu</option><option>Car</option><option>Boda</option><option>Taxi</option></select></div>
                    <div class="col-6"><label>Cost</label><input type="number" id="dp-l-cost" class="form-control" placeholder="0" min="0"></div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-add-confirm" onclick="confirmDpLog()"><i class="bi bi-check me-1"></i>Save</button>
                    <button class="btn-cancel" onclick="toggleDpForm('log')">Cancel</button>
                </div>
            </div>
            <div id="dp-log-list">${dpLogHtml(log)}</div>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
            <div class="fw-bold mb-2" style="font-size:.88rem"><i class="bi bi-cloud-upload text-brand me-2"></i>Documents</div>
            <div class="upload-drop" onclick="document.getElementById('dp-file').click()" style="padding:.6rem">
                <i class="bi bi-cloud-upload me-1" style="color:var(--muted)"></i>
                <span style="font-size:.8rem;color:var(--muted)">Upload</span>
            </div>
            <input type="file" id="dp-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple style="display:none" onchange="handleDpUpload(this)">
            <div id="dp-doc-list" class="mt-1">
                ${(log.docs||[]).map(d=>'<div style="font-size:.72rem;color:var(--muted);padding:2px 0">📎 '+(typeof d==='string'?d:(d.name||d.file_name||'Document'))+'</div>').join('')}
            </div>
        </div>
        <button class="btn btn-accent w-100 fw-bold" onclick="saveDayLog()" style="padding:.75rem">
            <i class="bi bi-check2-all me-2"></i>Save
        </button>`;
}

function showDpHourSlotForm(hour, btn) {
    // Close any other open slot forms first
    document.querySelectorAll('[id^="dp-slot-form-"]').forEach(el => {
        el.style.display = 'none';
        el.innerHTML = '';
    });
    const formDiv = document.getElementById('dp-slot-form-' + hour);
    if (!formDiv) return;
    formDiv.style.display = 'block';
    formDiv.innerHTML = `
        <div class="inline-add-form" style="margin-top:0">
             <div class="mb-2"><label>Task from ${hour < 12 ? hour + 'am' : hour === 12 ? '12pm' : (hour-12) + 'pm'}</label>
                <input type="text" id="dp-slot-desc-${hour}" class="form-control" placeholder="Describe the task…">
            </div>
            <div class="mb-2"><label>Duration (hours)</label>
                <input type="number" id="dp-slot-hrs-${hour}" class="form-control" value="1" min="0.5" max="4" step="0.5">
            </div>
            <div class="d-flex gap-2">
                <button class="btn-add-confirm" onclick="confirmDpHourSlot(${hour})"><i class="bi bi-check me-1"></i>Save</button>
                <button class="btn-cancel" onclick="document.getElementById('dp-slot-form-${hour}').style.display='none'">Cancel</button>
            </div>
        </div>`;
    setTimeout(() => document.getElementById('dp-slot-desc-' + hour)?.focus(), 50);
}

function confirmDpHourSlot(hour) {
    const a = activities[currentActivity];
    const desc = document.getElementById('dp-slot-desc-' + hour)?.value.trim();
    const hrs  = parseFloat(document.getElementById('dp-slot-hrs-' + hour)?.value);
    if (!desc || !hrs) return;
    if (!a.logs[currentDayKey]) a.logs[currentDayKey] = {work:[],expenses:[],logistics:[],docs:[]};
    // Remove any existing entry for this slot before adding
    a.logs[currentDayKey].work = (a.logs[currentDayKey].work || []).filter(w => w.startHour !== hour);
    a.logs[currentDayKey].work.push({desc, hours: hrs, startHour: hour});
    if (!a.filledDays.includes(currentDayKey)) a.filledDays.push(currentDayKey);
    openDayPanel(currentDayKey, a);
    showToast('✓ ' + hrs + 'h logged at slot');
}

function removeDpHourSlot(hour) {
    const a = activities[currentActivity];
    if (!a.logs[currentDayKey]?.work) return;
    a.logs[currentDayKey].work = a.logs[currentDayKey].work.filter(w => w.startHour !== hour);
    const l = a.logs[currentDayKey];
    if (!l.work.length && !l.expenses.length && !l.logistics.length && !l.docs.length) {
        a.filledDays = a.filledDays.filter(d => d !== currentDayKey);
    }
    openDayPanel(currentDayKey, a);
    showToast('Slot cleared');
}

function dpWorkHtml(a, log) {
    const ws = log.work || [];
    if (!ws.length) return '<div class="text-muted text-center py-2" style="font-size:.8rem">No work entries</div>';
    return ws.map((w, i) => {
        const d = typeof w === 'string' ? w : w.desc;
        return `<div class="log-entry" style="padding:.55rem;margin-bottom:.3rem">
            <button class="remove-btn" onclick="removeDpItem('work',${i})"><i class="bi bi-x-circle"></i></button>
            <div class="fw-semibold" style="font-size:.82rem">${d}</div>
            ${w.hours?`<div class="text-muted" style="font-size:.68rem"><i class="bi bi-clock me-1"></i>${w.hours}h · <span class="mono text-brand">${KES(w.hours*a.rate)}</span></div>`:''}
        </div>`;
    }).join('');
}
function dpExpHtml(log) {
    const es = log.expenses || [];
    if (!es.length) return '<div class="text-muted text-center py-2" style="font-size:.8rem">No expenses</div>';
    return es.map((e, i) => `<div class="expense-entry d-flex justify-content-between align-items-center" style="padding:.45rem">
        <div><div class="fw-semibold" style="font-size:.8rem">${e.desc}</div><div class="text-muted" style="font-size:.65rem">${e.cat}</div></div>
        <div class="d-flex align-items-center gap-2">
            <span class="mono fw-bold text-brand" style="font-size:.8rem">${KES(e.amount)}</span>
            <button style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:.78rem" onclick="removeDpItem('expenses',${i})"><i class="bi bi-x-circle"></i></button>
        </div>
    </div>`).join('');
}
function dpLogHtml(log) {
    const ls = log.logistics || [];
    if (!ls.length) return '<div class="text-muted text-center py-2" style="font-size:.8rem">No logistics</div>';
    return ls.map((l, i) => `<div class="logistics-entry" style="padding:.45rem">
        <div class="d-flex justify-content-between mb-1">
            <div class="fw-semibold" style="font-size:.8rem"><i class="bi bi-geo-alt-fill text-danger"></i> ${l.from} → ${l.to}</div>
            <div class="d-flex gap-2">
                <span class="mono fw-bold text-brand" style="font-size:.8rem">${KES(l.cost)}</span>
                <button style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:.78rem" onclick="removeDpItem('logistics',${i})"><i class="bi bi-x-circle"></i></button>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="text-muted" style="font-size:.65rem">${l.mode}</span>
            ${l.gps
                ? `<span class="gps-badge gps-ok">${l.gps}</span>`
                : `<span class="gps-badge gps-pend">Pending</span>
                   <button class="btn btn-sm btn-outline-primary" onclick="captureGPSFor(${i})" style="font-size:.65rem;padding:1px 5px"><i class="bi bi-geo-alt"></i>GPS</button>`}
        </div>
    </div>`).join('');
}

function toggleDpForm(type) {
    ['work','exp','log'].forEach(t => {
        const f=document.getElementById('dp-'+t+'-form');
        if (!f) return;
        if(t===type) f.style.display=f.style.display==='none'?'block':'none';
        else f.style.display='none';
    });
    const f = document.getElementById('dp-'+type+'-form');
    if (f && f.style.display==='block') setTimeout(()=>f.querySelector('input')?.focus(), 50);
}

function confirmDpWork() {
    const a = activities[currentActivity];
    const desc = document.getElementById('dp-w-desc')?.value.trim();
    if (!desc) return;
    if (!a.logs[currentDayKey]) a.logs[currentDayKey] = {work:[],expenses:[],logistics:[],docs:[]};
    if (a.engagement === 'hourly' || logMode === 'hours') {
        const hrs = parseFloat(document.getElementById('dp-w-hrs')?.value);
        if (!hrs) return;
        a.logs[currentDayKey].work.push({desc, hours: hrs, startHour: 9});
    } else {
        a.logs[currentDayKey].work.push(desc);
    }
    if (!a.filledDays.includes(currentDayKey)) a.filledDays.push(currentDayKey);
    openDayPanel(currentDayKey, a);
    showToast('✓ Saved');
}
function confirmDpExp() {
    const a = activities[currentActivity];
    const desc = document.getElementById('dp-e-desc')?.value.trim();
    const amt = parseFloat(document.getElementById('dp-e-amt')?.value);
    if (!desc || !amt) return;
    if (!a.logs[currentDayKey]) a.logs[currentDayKey] = {work:[],expenses:[],logistics:[],docs:[]};
    a.logs[currentDayKey].expenses.push({desc, cat:document.getElementById('dp-e-cat').value, amount:amt});
    if (!a.filledDays.includes(currentDayKey)) a.filledDays.push(currentDayKey);
    openDayPanel(currentDayKey, a);
    showToast('✓ Added');
}
function confirmDpLog() {
    const a = activities[currentActivity];
    const from=document.getElementById('dp-l-from')?.value.trim();
    const to=document.getElementById('dp-l-to')?.value.trim();
    const cost=parseFloat(document.getElementById('dp-l-cost')?.value);
    if (!from||!to||!cost) return;
    if (!a.logs[currentDayKey]) a.logs[currentDayKey] = {work:[],expenses:[],logistics:[],docs:[]};
    a.logs[currentDayKey].logistics.push({from, to, mode:document.getElementById('dp-l-mode').value, cost, gps:null});
    if (!a.filledDays.includes(currentDayKey)) a.filledDays.push(currentDayKey);
    openDayPanel(currentDayKey, a);
    showToast('✓ Added');
}
function removeDpItem(type, idx) {
    const a = activities[currentActivity];
    if (a.logs[currentDayKey]?.[type]) {
        a.logs[currentDayKey][type].splice(idx, 1);
        const l = a.logs[currentDayKey];
        if (!l.work.length && !l.expenses.length && !l.logistics.length && !l.docs.length) {
            a.filledDays = a.filledDays.filter(d => d !== currentDayKey);
        }
        openDayPanel(currentDayKey, a);
    }
}
function captureGPSFor(idx) {
    if (!navigator.geolocation) { showToast('GPS unavailable'); return; }
    navigator.geolocation.getCurrentPosition(p => {
        const g = p.coords.latitude.toFixed(6) + ', ' + p.coords.longitude.toFixed(6);
        activities[currentActivity].logs[currentDayKey].logistics[idx].gps = g;
        openDayPanel(currentDayKey, activities[currentActivity]);
        showToast('📍 ' + g);
    }, () => showToast('GPS failed'));
}
async function handleDpUpload(input) {
    if (!input.files.length || currentActivity === null || !currentDayKey) {
        input.value = '';
        return;
    }

    const a = activities[currentActivity];
    if (!a.logs[currentDayKey]) a.logs[currentDayKey] = {work:[],expenses:[],logistics:[],docs:[]};
    if (!Array.isArray(a.logs[currentDayKey].docs)) a.logs[currentDayKey].docs = [];

    let uploadedCount = 0;
    for (const file of Array.from(input.files)) {
        if (!isAllowedDocumentFile(file)) {
            showToast(`Invalid file type: ${file.name}`);
            continue;
        }
        const formData = new FormData();
        formData.append('field_activity_id', a.apiId);
        formData.append('document', file);

        try {
            const res = await fetch('/api/field-activity-documents', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const data = await res.json();
            if (data && data.data) {
                a.logs[currentDayKey].docs.push({
                    id: data.data.id,
                    name: data.data.file_name,
                    file_path: data.data.file_path
                });
                uploadedCount++;
            }
        } catch (e) { /* continue */ }
    }

    if (uploadedCount > 0) {
        if (!a.filledDays.includes(currentDayKey)) a.filledDays.push(currentDayKey);
        await syncLogDateToApi(a, currentDayKey);
        openDayPanel(currentDayKey, a);
        showToast(uploadedCount + ' uploaded');
    } else {
        showToast('Upload failed');
    }
    input.value = '';
}

// ════════════════════════════════════
// SAVE DAY LOG
// ════════════════════════════════════
function saveDayLog() {
    if (currentActivity === null || !currentDayKey) return;
    const a = activities[currentActivity];
    const logData = a.logs[currentDayKey] || { work: [], expenses: [], logistics: [], docs: [] };
    fetch(`/api/field-activities/${a.apiId}/logs`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ date: currentDayKey, log_data: logData })
    })
    .then(res => {
        if (res.status === 403) {
            showToast('❌ Access denied — contact admin');
            return null;
        }
        return res.json();
    })
    .then(data => {
        if (!data) return;
        if (data && data.success) {
            if (!a.filledDays.includes(currentDayKey)) a.filledDays.push(currentDayKey);
            closeDayPanel();
            renderLogsheet();
            showToast('✓ Saved');
        } else {
            showToast('Save failed: ' + (data.message || 'unknown error'));
        }
    })
    .catch(() => showToast('❌ Network error — not saved'));
}

function closeDayPanel() {
    document.getElementById('day-panel-overlay').classList.remove('active');
    document.body.style.overflow='';
    currentDayKey=null;
}
document.getElementById('day-panel-overlay').addEventListener('click', function(e) {
    if(e.target===this) closeDayPanel();
});

// ════════════════════════════════════
// LOG REVIEW (Admin/Manager/Finance)
// ════════════════════════════════════
async function submitLogReview(dk, status) {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    const note = document.getElementById('review-note-' + dk)?.value.trim() || null;

    try {
        const res = await fetch(`/api/field-activities/${a.apiId}/logs/${dk}/review`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status, note })
        });
        const data = await res.json();
        if (data && data.success) {
            if (!a.logs[dk]) a.logs[dk] = {};
            a.logs[dk].review = data.log_data.review;
            renderLogsheet();
            showToast(status === 'approved' ? '✓ Entry approved' : '✗ Entry rejected');
        } else {
            showToast('Review failed');
        }
    } catch (e) {
        showToast('Review failed');
    }
}

// ════════════════════════════════════
// EXPENSES TAB
// ════════════════════════════════════
function renderExpensesTab() {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    let ge=[], le=[];
    Object.entries(a.logs).forEach(([k,l]) => {
        if(l.expenses) l.expenses.forEach(e => ge.push({...e, date:k}));
        if(l.logistics) l.logistics.forEach(e => le.push({...e, date:k}));
    });
    const gt=ge.reduce((s,e)=>s+e.amount,0), lt=le.reduce((s,e)=>s+e.cost,0), wk=calcWork(a);
    document.getElementById('expenses-container').innerHTML = `<div class="row g-3">
        <div class="col-lg-7">
            <div class="section-card mb-2">
                <div class="section-head"><span><i class="bi bi-receipt text-accent me-2"></i>General (${ge.length})</span><span class="mono fw-bold text-accent">${KES(gt)}</span></div>
                <div class="section-body">${ge.length ? ge.map(e=>`<div class="expense-entry">
                    <div class="d-flex justify-content-between">
                        <div class="fw-semibold" style="font-size:.82rem">${e.desc}</div>
                        <span class="mono fw-bold text-brand" style="font-size:.82rem">${KES(e.amount)}</span>
                    </div>
                    <div class="text-muted" style="font-size:.7rem">${e.date} · ${e.cat}</div>
                </div>`).join('') : '<div class="text-muted text-center py-2" style="font-size:.8rem">Add expenses from logsheet day entries</div>'}</div>
            </div>
            <div class="section-card">
                <div class="section-head"><span><i class="bi bi-truck me-2" style="color:var(--purple)"></i>Logistics (${le.length})</span><span class="mono fw-bold" style="color:var(--purple)">${KES(lt)}</span></div>
                <div class="section-body">${le.length ? le.map(l=>`<div class="logistics-entry">
                    <div class="d-flex justify-content-between mb-1">
                        <div class="fw-semibold" style="font-size:.82rem"><i class="bi bi-geo-alt-fill text-danger"></i> ${l.from} → ${l.to}</div>
                        <span class="mono fw-bold text-brand" style="font-size:.82rem">${KES(l.cost)}</span>
                    </div>
                    <div class="text-muted" style="font-size:.7rem">${l.mode} · ${l.date}</div>
                </div>`).join('') : '<div class="text-muted text-center py-2" style="font-size:.8rem">No logistics</div>'}</div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="section-card" style="position:sticky;top:75px">
                <div class="section-head"><i class="bi bi-calculator me-2 text-brand"></i>Totals</div>
                <div class="section-body">
                    <div class="budget-row"><span class="b-label">General</span><span class="b-value mono">${KES(gt)}</span></div>
                    <div class="budget-row"><span class="b-label">Logistics</span><span class="b-value mono">${KES(lt)}</span></div>
                    <div class="divider" style="margin:.4rem 0"></div>
                    <div class="budget-row"><span class="b-label fw-bold">Expenses</span><span class="b-value mono fw-bold">${KES(gt+lt)}</span></div>
                    <div class="budget-row"><span class="b-label">Work Value</span><span class="b-value mono text-brand">${KES(wk)}</span></div>
                    <div class="divider" style="margin:.4rem 0"></div>
                    <div class="d-flex justify-content-between"><span class="fw-bold">Grand Total</span><span class="fw-bold mono text-accent" style="font-size:1rem">${KES(wk+gt+lt)}</span></div>
                </div>
            </div>
        </div>
    </div>`;
}

// ════════════════════════════════════
// DOCS TAB
// ════════════════════════════════════
function renderDocsTab() {
    if (currentActivity === null) return;
    const a = activities[currentActivity];
    let docs = [];
    Object.entries(a.logs).forEach(([k,l]) => {
        if(l.docs) l.docs.forEach((d, docIndex) => docs.push({
            id: d && d.id ? d.id : null,
            name: d && (d.name || d.file_name) ? (d.name || d.file_name) : (typeof d === 'string' ? d : 'Document'),
            date: k,
            docIndex,
            isLegacy: !(d && d.id)
        }));
    });
    const legacyCount = docs.filter(d => d.isLegacy).length;
    const docCards = docs.map(d => {
        const viewUrl = d.id ? `/api/field-activity-documents/${d.id}/view` : null;
        const downloadUrl = d.id ? `/api/field-activity-documents/${d.id}/download` : null;
        const replaceId = `doc-replace-${d.date}-${d.docIndex}`;
        return `<div style="border:1px solid var(--border);border-radius:var(--radius);padding:.55rem;display:flex;align-items:center;gap:.4rem">
            <i class="bi bi-file-earmark-text text-brand" style="font-size:1.1rem"></i>
            <div style="flex:1">
                <div class="fw-semibold" style="font-size:.75rem">${d.name}</div>
                <div style="font-size:.65rem;color:var(--muted)">${d.date}${d.isLegacy ? ' · legacy' : ''}</div>
            </div>
            ${downloadUrl
                ? `<a href="${viewUrl}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:.65rem;padding:2px 6px"><i class="bi bi-eye"></i></a>
                   <a href="${downloadUrl}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size:.65rem;padding:2px 6px"><i class="bi bi-download"></i></a>`
                : `<button class="btn btn-sm btn-outline-warning" style="font-size:.65rem;padding:2px 6px" onclick="document.getElementById('${replaceId}').click()" title="Replace legacy file"><i class="bi bi-arrow-repeat"></i></button>
                   <input type="file" id="${replaceId}" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display:none" onchange="replaceLegacyDoc(this, '${d.date}', ${d.docIndex})">`}
        </div>`;
    }).join('');
    document.getElementById('docs-container').innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="fw-bold"><i class="bi bi-folder2-open me-2 text-brand"></i>Documents (${docs.length})</div>
            <button class="btn btn-brand btn-sm" onclick="document.getElementById('doc-up').click()"><i class="bi bi-upload me-1"></i>Upload</button>
            <input type="file" id="doc-up" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple style="display:none" onchange="handleDocsTabUpload(this)">
        </div>
        ${legacyCount ? `<div class="alert alert-warning py-2 px-3 mb-3" style="font-size:.78rem">${legacyCount} legacy document(s) need re-upload. Use the <strong>refresh</strong> icon on each card.</div>` : ''}
        ${docs.length
            ? `<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.4rem">${docCards}</div>`
            : '<div class="text-muted text-center py-3" style="font-size:.82rem">No documents yet</div>'}`;
}

async function syncLogDateToApi(a, dk) {
    if (!a || !dk) return false;
    const logData = a.logs[dk] || { work: [], expenses: [], logistics: [], docs: [] };
    try {
        const res = await fetch(`/api/field-activities/${a.apiId}/logs`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ date: dk, log_data: logData })
        });
        const data = await res.json();
        if (data && data.success && data.data && data.data.log_data) {
            a.logs[dk] = data.data.log_data;
            return true;
        }
    } catch (e) { /* ignore */ }
    return false;
}

// ════════════════════════════════════
// ADMIN VIEW
// ════════════════════════════════════
function renderAdminView() {
    const sf=document.getElementById('admin-filter-status')?.value||'';
    const tf=document.getElementById('admin-filter-type')?.value||'';
    const pf=document.getElementById('admin-filter-person')?.value||'';
    const newFilters = { status: sf, type: tf, person: pf };
    if (newFilters.status !== adminFilters.status || newFilters.type !== adminFilters.type || newFilters.person !== adminFilters.person) {
        adminPage = 1;
        adminFilters = newFilters;
    }

    let list=[...activities];
    if(sf) list=list.filter(a=>a.adminStatus===sf);
    if(tf) list=list.filter(a=>a.engagement===tf);
    if(pf) list=list.filter(a=>a.person===pf);

    const pending=activities.filter(a=>a.adminStatus==='Pending').length;
    const approved=activities.filter(a=>a.adminStatus==='Approved').length;
    const paid=activities.filter(a=>a.adminStatus==='Paid').length;
    const tp=activities.filter(a=>a.adminStatus==='Approved').reduce((s,a)=>s+calcWork(a)+calcExpenses(a),0);
    const unpaid=activities.filter(a=>!a.paid&&a.adminStatus!=='Rejected').length;
    const rejected=activities.filter(a=>a.adminStatus==='Rejected').length;
    const totalExpenses=activities.reduce((s,a)=>s+calcExpenses(a),0);

    document.getElementById('admin-stats').innerHTML = `
        <div class="stat-card"><div class="stat-icon" style="background:#fef3c7;color:#92400e"><i class="bi bi-hourglass-split"></i></div><div class="stat-value">${pending}</div><div class="stat-label">Pending</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#d1fae5;color:#065f46"><i class="bi bi-check-circle"></i></div><div class="stat-value">${approved}</div><div class="stat-label">Approved</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#dbeafe;color:var(--brand)"><i class="bi bi-cash-coin"></i></div><div class="stat-value">${paid}</div><div class="stat-label">Paid</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="bi bi-x-circle"></i></div><div class="stat-value">${rejected}</div><div class="stat-label">Rejected</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#fce7f3;color:#9d174d"><i class="bi bi-clock-history"></i></div><div class="stat-value">${unpaid}</div><div class="stat-label">Unpaid</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-receipt"></i></div><div class="stat-value" style="font-size:.95rem">${KES(totalExpenses)}</div><div class="stat-label">Total Expenses</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-cash-stack"></i></div><div class="stat-value" style="font-size:1rem">${KES(tp)}</div><div class="stat-label">Payable</div></div>`;

    const tw=document.getElementById('admin-table-wrap');
    if(!list.length){tw.innerHTML='<div class="text-muted text-center py-4 px-3">No matching submissions</div>';return;}

    const totalPages = Math.max(1, Math.ceil(list.length / adminPageSize));
    if (adminPage > totalPages) adminPage = totalPages;
    const pageStart = (adminPage - 1) * adminPageSize;
    const pageItems = list.slice(pageStart, pageStart + adminPageSize);

    tw.innerHTML=`<div style="overflow-x:auto"><table class="admin-table"><thead><tr>
        <th>Person</th><th>Activity</th><th>Type</th><th>Logged</th><th>Work</th><th>Expenses</th><th>Total</th><th>Status</th><th>Action</th>
    </tr></thead><tbody>${pageItems.map(a => {
        const wv=calcWork(a),ex=calcExpenses(a),tp2=wv+ex;
        const stCls=a.adminStatus==='Pending'?'bp-pending':a.adminStatus==='Approved'?'bp-approved':a.adminStatus==='Paid'?'bp-paid':'bp-rejected';
        return `<tr>
            <td class="fw-semibold">${a.person}</td>
            <td style="max-width:160px">${a.title}</td>
            <td>${engTag(a)}</td>
            <td>${a.filledDays.length}/${totalDays(a)}</td>
            <td class="mono text-brand">${KES(wv)}</td>
            <td class="mono">${KES(ex)}</td>
            <td class="mono fw-bold text-accent">${KES(tp2)}</td>
            <td><span class="badge-pill ${stCls}">${a.adminStatus}</span></td>
            <td><div class="d-flex gap-1">
                <button class="btn btn-sm btn-outline-secondary" style="font-size:.68rem;padding:2px 5px"
                    onclick="openActivityDetail(${a.id})" title="View full profile"><i class="bi bi-eye"></i></button>
                ${a.adminStatus==='Pending'
                    ? `<button class="btn btn-sm btn-success" style="font-size:.68rem;padding:2px 5px" onclick="setAdminStatus(${a.id},'approved')"><i class="bi bi-check"></i></button>
                       <button class="btn btn-sm btn-danger" style="font-size:.68rem;padding:2px 5px" onclick="setAdminStatus(${a.id},'rejected')"><i class="bi bi-x"></i></button>`
                    : a.adminStatus==='Approved'
                        ? `<button class="btn btn-sm btn-accent" style="font-size:.68rem;padding:2px 5px" onclick="setAdminStatus(${a.id},'funded')"><i class="bi bi-cash me-1"></i>Pay</button>`
                        : a.adminStatus==='Paid' ? '<span class="text-muted" style="font-size:.68rem">✓</span>' : '—'}
            </div></td>
        </tr>`;
    }).join('')}</tbody></table></div>
    ${buildPagination(adminPage, totalPages, 'gotoAdminPage')}`;
}

async function setAdminStatus(idx, status) {
    const a = activities[idx];
    try {
        const res = await fetch(`/api/field-activities/${a.apiId}/set-status`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ status })
        });
        const data = await res.json();
        if (data && data.data) {
            a.adminStatus = mapAdminStatus(data.data.status);
            if (status === 'funded') a.paid = true;
            renderAdminView();
            showToast('✓ Status updated');
        } else { showToast('Failed to update'); }
    } catch (e) { showToast('Failed to update'); }
}

// ════════════════════════════════════
// REPORTS
// ════════════════════════════════════
// NEW
document.addEventListener('DOMContentLoaded', () => {
    // Logsheet-only users still need Overview to add expenses/logistics —
    // only Docs stays restricted.
    if (APP_USER.permissions.canLogsheetOnly) {
        document.getElementById('tab-btn-docs')?.classList.add('d-none');
    }
    

    document.querySelectorAll('.rpt-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.rpt-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderReports();
        });
    });
});

function populateReportPersonFilter() {
    const sel = document.getElementById('rpt-filter-person');
    if (!sel) return;
    const names = [...new Set(activities.map(a => a.person).filter(Boolean))].sort();
    sel.innerHTML = '<option value="">All Personnel</option>' +
        names.map(n => `<option value="${n}">${n}</option>`).join('');
}

function getReportRows() {
    const person = document.getElementById('rpt-filter-person')?.value || '';
    const type   = document.getElementById('rpt-filter-type')?.value   || '';
    const status = document.getElementById('rpt-filter-status')?.value || '';
    const paid   = document.getElementById('rpt-filter-paid')?.value   || '';
    const from   = document.getElementById('rpt-filter-from')?.value   || '';
    const to     = document.getElementById('rpt-filter-to')?.value     || '';
    return activities.filter(a => {
        if (person && a.person !== person) return false;
        if (type   && a.engagement !== type) return false;
        if (status && a.status !== status) return false;
        if (paid === 'paid'   && !a.paid) return false;
        if (paid === 'unpaid' &&  a.paid) return false;
        if (from && dateKey(a.startDate) < from) return false;
        if (to   && dateKey(a.endDate)   > to)   return false;
        return true;
    });
}

// ════════════════════════════════════
// INVOICE SECTION
// ════════════════════════════════════
function buildInvoiceSection(a, canManage) {
    const inv = a.invoice;
    const approvedAmount = parseFloat(inv?.approved_amount || 0) || (a.dbStatus === 'approved' ? calcWork(a) + calcExpenses(a) : 0);
    const payableAmount = a.dbStatus === 'approved' ? calcWork(a) + calcExpenses(a) : 0;
    const pendingAmount = ['submitted', 'draft', 'pending'].includes(a.dbStatus) ? calcWork(a) + calcExpenses(a) : 0;
    const declinedAmount = inv?.status === 'rejected' ? (parseFloat(inv.claimed_total || 0) || payableAmount) : 0;
    const paidAmount = a.paid ? approvedAmount || payableAmount : 0;

    const summaryRows = `
        <div class="budget-row"><span class="b-label">Approved / Payable</span><span class="b-value mono text-brand">${KES(approvedAmount)}</span></div>
        <div class="budget-row"><span class="b-label">Pending approval</span><span class="b-value mono" style="color:var(--warn)">${KES(pendingAmount)}</span></div>
        <div class="budget-row"><span class="b-label">Declined</span><span class="b-value mono" style="color:var(--danger)">${KES(declinedAmount)}</span></div>
        <div class="budget-row"><span class="b-label">Paid</span><span class="b-value mono text-accent">${KES(paidAmount)}</span></div>`;

    if (a.paid) {
        return `<div style="padding:.85rem;border:1px solid var(--border);border-radius:var(--radius);background:#f8fafc">
            ${summaryRows}
            <div class="divider"></div>
            <div style="font-size:.85rem;color:var(--muted)">This activity has already been paid. Invoice uploads are disabled.</div>
        </div>`;
    }

    if (a.dbStatus !== 'approved' && !inv) {
        return `<div style="padding:.85rem;border:1px solid var(--border);border-radius:var(--radius);background:#f8fafc">
            ${summaryRows}
            <div class="divider"></div>
            <div style="font-size:.85rem;color:var(--muted)">Invoice upload becomes available after activity approval.</div>
        </div>`;
    }

    const invoiceSection = inv ? `
        <div style="padding:.85rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--surface)">
            <div style="display:flex;justify-content:space-between;gap:.75rem;align-items:center;flex-wrap:wrap">
                <div>
                    <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700">Uploaded invoice</div>
                    <div style="font-size:.95rem;font-weight:700">${inv.file_name || 'Invoice file'}</div>
                    <div style="font-size:.82rem;color:var(--muted);margin-top:.25rem">Amount: ${KES(approvedAmount || payableAmount)}</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="openInvoicePreview(${inv.id})">View Invoice</button>
                    ${canManage ? `<button class="btn btn-sm btn-danger" onclick="deleteInvoice(${a.apiId})">Delete invoice</button>` : ''}
                </div>
            </div>
        </div>` : `
        <div id="inv-upload-area-${a.apiId}">
            <div style="border:2px dashed var(--border);border-radius:var(--radius);padding:1.5rem;text-align:center;cursor:pointer;transition:all .2s"
                 onclick="document.getElementById('inv-file-${a.apiId}').click()"
                 id="inv-drop-${a.apiId}"
                 ondragover="event.preventDefault();this.style.borderColor='var(--brand)';this.style.background='var(--brand-lite)'"
                 ondragleave="this.style.borderColor='var(--border)';this.style.background=''"
                 ondrop="handleInvDrop(event,${a.apiId})">
                <input type="file" id="inv-file-${a.apiId}" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                       style="display:none" onchange="handleInvFile(this,${a.apiId})">
                <i class="bi bi-file-earmark-arrow-up" style="font-size:2rem;color:var(--muted)"></i>
                <div style="font-size:.82rem;color:var(--muted);margin-top:.5rem">
                    Drop your invoice here or <span style="color:var(--brand);font-weight:600">click to browse</span>
                </div>
                <div style="font-size:.7rem;color:var(--muted);margin-top:.25rem">
                    Upload invoice for the approved amount. Supported: PDF, JPG, PNG, DOC.
                </div>
            </div>
        </div>`;

    return `<div style="padding:.85rem;border:1px solid var(--border);border-radius:var(--radius);background:#f8fafc">
        ${summaryRows}
        <div class="divider"></div>
        ${invoiceSection}
    </div>`;
}

async function handleInvFile(input, apiId) {
    if (!input.files.length) return;
    const file = input.files[0];
    if (!isAllowedDocumentFile(file)) {
        showToast(`Invalid file type: ${file.name}`);
        input.value = '';
        return;
    }
    const a = activities.find(x => x.apiId === apiId);
    if (!a) return;

    const proc = document.getElementById('inv-processing-' + apiId);
    if (proc) proc.style.display = 'flex';

    const formData = new FormData();
    formData.append('field_activity_id', apiId);
    formData.append('document', file);
    formData.append('type', 'invoice');

    try {
        const res = await fetch('/api/field-activity-documents', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const data = await res.json();
        if (data?.data) {
            const approvedAmount = parseFloat(a.invoice?.approved_amount || 0) || (a.status === 'approved' ? calcWork(a) + calcExpenses(a) : 0);
            a.invoice = {
                id: data.data.id,
                file_name: data.data.file_name,
                file_path: data.data.file_path,
                file_type: data.data.file_type,
                status: 'uploaded',
                approved_amount: approvedAmount,
                claimed_total: approvedAmount
            };
            showToast('✓ Invoice uploaded');
        } else {
            showToast('Invoice upload failed');
        }
    } catch (e) {
        showToast('Invoice upload failed');
    } finally {
        if (proc) proc.style.display = 'none';
        input.value = '';
        renderOverview();
    }
}

async function deleteInvoice(apiId) {
    const a = activities.find(x => x.apiId === apiId);
    if (!a || !a.invoice?.id) return;
    if (!confirm('Delete this invoice so the user can upload again?')) return;
    try {
        const res = await fetch(`/api/field-activity-documents/${a.invoice.id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (res.ok) {
            a.invoice = null;
            showToast('✓ Invoice deleted');
        } else {
            showToast('Failed to delete invoice');
        }
    } catch (e) {
        showToast('Failed to delete invoice');
    }
    renderOverview();
}

function handleInvDrop(event, apiId) {
    event.preventDefault();
    const drop = document.getElementById('inv-drop-' + apiId);
    if (drop) drop.style.borderColor = 'var(--border)';
    const file = event.dataTransfer.files[0];
    if (!file) return;
    if (!isAllowedDocumentFile(file)) {
        showToast(`Invalid file type: ${file.name}`);
        return;
    }
    const inp = document.getElementById('inv-file-' + apiId);
    if (inp) {
        const dt = new DataTransfer();
        dt.items.add(file);
        inp.files = dt.files;
        handleInvFile(inp, apiId);
    }
}

let reportPage = 1;
const reportPageSize = 10;

function renderReports() {
    const tab  = document.querySelector('.rpt-tab-btn.active')?.getAttribute('data-type') || 'summary';
    const allRows = getReportRows();
    const totalPages = Math.max(1, Math.ceil(allRows.length / reportPageSize));
    reportPage = Math.min(Math.max(1, reportPage), totalPages);
    const list = allRows.slice((reportPage - 1) * reportPageSize, reportPage * reportPageSize);
    document.getElementById('rpt-row-count').textContent = allRows.length + ' records';

    const totalWork = allRows.reduce((s,a) => s+calcWork(a), 0);
    const totalExp  = allRows.reduce((s,a) => s+calcExpenses(a), 0);
    const daysLogged = allRows.reduce((s,a) => s+a.filledDays.length, 0);
    document.getElementById('rpt-stat-cards').innerHTML = `
        <div class="stat-card"><div class="stat-icon" style="background:#dbeafe;color:var(--brand)"><i class="bi bi-list-check"></i></div><div class="stat-value">${allRows.length}</div><div class="stat-label">Activities</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#d1fae5;color:#065f46"><i class="bi bi-journal-check"></i></div><div class="stat-value">${daysLogged}</div><div class="stat-label">Days Logged</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#ede9fe;color:#5b21b6"><i class="bi bi-cash-stack"></i></div><div class="stat-value" style="font-size:1rem">${KES(totalWork)}</div><div class="stat-label">Work Value</div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#fce7f3;color:#9d174d"><i class="bi bi-receipt"></i></div><div class="stat-value" style="font-size:1rem">${KES(totalExp)}</div><div class="stat-label">Expenses</div></div>`;

    const labels = {summary:'Activity Summary',finance:'Financial Report',logsheet:'Logsheet Report',expenses:'Expense Report',payment:'Payment Status'};
    document.getElementById('rpt-table-label').innerHTML = `<i class="bi bi-table text-brand me-2"></i>${labels[tab]}`;

    let thead = '', tbody = '', tfoot = '';

    if (tab === 'summary') {
        thead = `<tr><th>#</th><th>Coach</th><th>Activity</th><th>Engagement</th><th>Rate</th><th>Start</th><th>End</th><th>Total Days</th><th>Days Logged</th><th>Work Value</th><th>Expenses</th><th>Total Payable</th><th>Status</th><th>Payment</th></tr>`;
        let tw=0, te=0;
        tbody = list.map((a,i) => {
            const wv=calcWork(a), ex=calcExpenses(a); tw+=wv; te+=ex;
            return `<tr>
                <td>${i+1}</td><td class="fw-semibold">${a.person}</td><td>${a.title}</td><td>${a.engagement}</td>
                <td class="mono">${a.engagement==='hourly'?KES(a.rate)+'/hr':a.engagement==='daily'?KES(a.rate)+'/day':KES(a.rate)}</td>
                <td>${formatDate(a.startDate)}</td><td>${formatDate(a.endDate)}</td>
                <td>${totalDays(a)}</td><td>${a.filledDays.length}</td>
                <td class="mono text-brand">${KES(wv)}</td><td class="mono">${KES(ex)}</td>
                <td class="mono fw-bold text-accent">${KES(wv+ex)}</td>
                <td><span class="badge-pill ${a.adminStatus==='Approved'?'bp-approved':a.adminStatus==='Paid'?'bp-paid':a.adminStatus==='Rejected'?'bp-rejected':'bp-pending'}">${a.adminStatus}</span></td>
                <td><span class="badge-pill ${a.paid?'bp-paid':'bp-pending'}">${a.paid?'Paid':'Unpaid'}</span></td>
            </tr>`;
        }).join('');
        tfoot = `<tr><td colspan="9" class="fw-bold">TOTALS</td><td class="mono fw-bold text-brand">${KES(tw)}</td><td class="mono fw-bold">${KES(te)}</td><td class="mono fw-bold text-accent">${KES(tw+te)}</td><td colspan="2"></td></tr>`;
    } else if (tab === 'finance') {
        thead = `<tr><th>#</th><th>Coach</th><th>Activity</th><th>Type</th><th>Contract Value</th><th>Work Logged</th><th>Expenses</th><th>Total Payable</th><th>Payment</th></tr>`;
        let tw=0, te=0;
        tbody = list.map((a,i) => {
            const wv=calcWork(a), ex=calcExpenses(a); tw+=wv; te+=ex;
            return `<tr><td>${i+1}</td><td class="fw-semibold">${a.person}</td><td>${a.title}</td><td>${a.engagement}</td>
                <td class="mono">${KES(calcContract(a))}</td><td class="mono text-brand">${KES(wv)}</td>
                <td class="mono">${KES(ex)}</td><td class="mono fw-bold text-accent">${KES(wv+ex)}</td>
                <td><span class="badge-pill ${a.paid?'bp-paid':'bp-pending'}">${a.paid?'Paid':'Unpaid'}</span></td></tr>`;
        }).join('');
        tfoot = `<tr><td colspan="5" class="fw-bold">TOTALS</td><td class="mono fw-bold text-brand">${KES(tw)}</td><td class="mono fw-bold">${KES(te)}</td><td class="mono fw-bold text-accent">${KES(tw+te)}</td><td></td></tr>`;
    } else if (tab === 'logsheet') {
        thead = `<tr><th>#</th><th>Coach</th><th>Activity</th><th>Date</th><th>Work Done</th><th>Hours</th><th>Earnings</th></tr>`;
        let rows=[], n=1;
        list.forEach(a => {
            const days=[...a.filledDays].sort();
            if(!days.length){rows.push(`<tr><td>${n++}</td><td>${a.person}</td><td>${a.title}</td><td colspan="4" class="text-muted">No days logged</td></tr>`);return;}
            days.forEach(dk => {
                const log=a.logs[dk]||{}, work=log.work||[];
                let hrs=0;
                const desc=work.map(w=>{if(w.hours)hrs+=w.hours;return typeof w==='string'?w:w.desc;}).join('; ');
                const earn=a.engagement==='hourly'?hrs*a.rate:a.engagement==='daily'?a.rate:0;
                rows.push(`<tr><td>${n++}</td><td>${a.person}</td><td>${a.title}</td>
                    <td>${formatDate(new Date(dk+'T00:00:00'))}</td>
                    <td style="max-width:200px;font-size:.75rem">${desc||'—'}</td>
                    <td class="mono">${hrs||'—'}</td>
                    <td class="mono text-brand">${earn?KES(earn):'—'}</td></tr>`);
            });
        });
        tbody=rows.join('')||'<tr><td colspan="7" class="text-center text-muted py-3">No log entries found</td></tr>';
    } else if (tab === 'expenses') {
        thead = `<tr><th>#</th><th>Coach</th><th>Activity</th><th>Date</th><th>Type</th><th>Description</th><th>Category/Mode</th><th>Amount</th></tr>`;
        let rows=[], n=1, grand=0;
        list.forEach(a => {
            Object.entries(a.logs).forEach(([dk,l]) => {
                (l.expenses||[]).forEach(e => {
                    grand+=e.amount;
                    rows.push(`<tr><td>${n++}</td><td>${a.person}</td><td>${a.title}</td>
                        <td>${formatDate(new Date(dk+'T00:00:00'))}</td>
                        <td><span class="badge-pill" style="background:#fef3c7;color:#92400e">Expense</span></td>
                        <td>${e.desc}</td><td>${e.cat}</td><td class="mono text-brand">${KES(e.amount)}</td></tr>`);
                });
                (l.logistics||[]).forEach(e => {
                    grand+=e.cost;
                    rows.push(`<tr><td>${n++}</td><td>${a.person}</td><td>${a.title}</td>
                        <td>${formatDate(new Date(dk+'T00:00:00'))}</td>
                        <td><span class="badge-pill" style="background:#ede9fe;color:#5b21b6">Transport</span></td>
                        <td>${e.from} → ${e.to}</td><td>${e.mode}</td><td class="mono text-brand">${KES(e.cost)}</td></tr>`);
                });
            });
        });
        tbody=rows.join('')||'<tr><td colspan="8" class="text-center text-muted py-3">No expense entries</td></tr>';
        tfoot=`<tr><td colspan="7" class="fw-bold">TOTAL EXPENSES</td><td class="mono fw-bold text-accent">${KES(grand)}</td></tr>`;
    } else if (tab === 'payment') {
        thead = `<tr><th>#</th><th>Coach</th><th>Activity</th><th>Engagement</th><th>Work Value</th><th>Expenses</th><th>Total Payable</th><th>Admin Status</th><th>Payment</th></tr>`;
        let paidT=0, unpaidT=0;
        tbody=list.map((a,i) => {
            const wv=calcWork(a),ex=calcExpenses(a),tot=wv+ex;
            if(a.paid) paidT+=tot; else unpaidT+=tot;
            return `<tr><td>${i+1}</td><td class="fw-semibold">${a.person}</td><td>${a.title}</td><td>${a.engagement}</td>
                <td class="mono text-brand">${KES(wv)}</td><td class="mono">${KES(ex)}</td>
                <td class="mono fw-bold">${KES(tot)}</td>
                <td><span class="badge-pill ${a.adminStatus==='Approved'?'bp-approved':a.adminStatus==='Paid'?'bp-paid':'bp-pending'}">${a.adminStatus}</span></td>
                <td><span class="badge-pill ${a.paid?'bp-paid':'bp-pending'}">${a.paid?'✓ Paid':'⏳ Unpaid'}</span></td></tr>`;
        }).join('');
        tfoot=`<tr><td colspan="6" class="fw-bold">TOTALS</td><td class="mono fw-bold text-accent">${KES(paidT+unpaidT)}</td>
            <td style="font-size:.72rem;color:var(--muted)">Paid: ${KES(paidT)}</td>
            <td style="font-size:.72rem;color:var(--muted)">Unpaid: ${KES(unpaidT)}</td></tr>`;
    }

    document.getElementById('rpt-thead').innerHTML = thead;
    document.getElementById('rpt-tbody').innerHTML = tbody || `<tr><td colspan="14" class="text-center text-muted py-4">No data matching filters</td></tr>`;
    document.getElementById('rpt-tfoot').innerHTML = tfoot;
    renderReportPagination(allRows.length, totalPages, reportPage);
}

function renderReportPagination(totalRows, totalPages, currentPage) {
    const container = document.getElementById('rpt-pagination');
    if (!container) return;
    if (totalPages <= 1) {
        container.innerHTML = `<div style="font-size:.85rem;color:var(--muted)">Showing all ${totalRows} records</div>`;
        return;
    }
    const pages = [];
    const start = Math.max(1, currentPage - 2);
    const end = Math.min(totalPages, currentPage + 2);
    if (currentPage > 1) {
        pages.push(`<button class="btn btn-sm btn-outline-primary" onclick="goToReportPage(${currentPage - 1})">Prev</button>`);
    }
    for (let p = start; p <= end; p++) {
        pages.push(`<button class="btn btn-sm ${p === currentPage ? 'btn-brand' : 'btn-outline-secondary'}" onclick="goToReportPage(${p})">${p}</button>`);
    }
    if (currentPage < totalPages) {
        pages.push(`<button class="btn btn-sm btn-outline-primary" onclick="goToReportPage(${currentPage + 1})">Next</button>`);
    }
    container.innerHTML = `<div class="d-flex flex-wrap gap-2 align-items-center"><div style="font-size:.85rem;color:var(--muted)">Page ${currentPage} of ${totalPages}</div>${pages.join('')}</div>`;
}

function goToReportPage(page) {
    reportPage = page;
    renderReports();
}

// ════════════════════════════════════
// EXCEL DOWNLOAD
// ════════════════════════════════════
function downloadExcelReport() {
    const tab   = document.querySelector('.rpt-tab-btn.active')?.getAttribute('data-type') || 'summary';
    const list  = getReportRows();
    if (!list.length) { showToast('No data to export'); return; }
    const labels = {summary:'Activity_Summary',finance:'Financial_Report',logsheet:'Logsheet_Report',expenses:'Expense_Report',payment:'Payment_Status'};
    const table  = document.getElementById('rpt-table');
    const wb     = XLSX.utils.book_new();
    const ws     = XLSX.utils.table_to_book(table, {sheet: labels[tab]}).Sheets[labels[tab]];
    const rows   = XLSX.utils.sheet_to_json(ws, {header:1});
    const widths = (rows[0]||[]).map((_,ci) => ({
        wch: Math.min(40, Math.max(10, ...rows.map(r => String(r[ci]??'').length)))
    }));
    ws['!cols'] = widths;
    XLSX.utils.book_append_sheet(wb, ws, labels[tab]);
    XLSX.writeFile(wb, labels[tab] + '_' + new Date().toISOString().slice(0,10) + '.xlsx');
    showToast('✓ Downloaded');
}

// ════════════════════════════════════
// DOCS TAB UPLOAD
// FIX: Loops over all selected files instead of only uploading input.files[0]
// ════════════════════════════════════
async function handleDocsTabUpload(input) {
    if (!input.files.length || currentActivity === null) return;
    const a = activities[currentActivity];
    const dk = dateKey(today);
    if (!a.logs[dk]) a.logs[dk] = { work: [], expenses: [], logistics: [], docs: [] };
    if (!Array.isArray(a.logs[dk].docs)) a.logs[dk].docs = [];

    let uploadedCount = 0;
    for (const file of Array.from(input.files)) {
        if (!isAllowedDocumentFile(file)) {
            showToast(`Invalid file type: ${file.name}`);
            continue;
        }
        const formData = new FormData();
        formData.append('field_activity_id', a.apiId);
        formData.append('document', file);
        try {
            const res = await fetch('/api/field-activity-documents', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const data = await res.json();
            if (data && data.data) {
                a.logs[dk].docs.push({ id: data.data.id, name: data.data.file_name, file_path: data.data.file_path });
                if (!a.filledDays.includes(dk)) a.filledDays.push(dk);
                uploadedCount++;
            }
        } catch (e) { /* continue uploading remaining files */ }
    }

    if (uploadedCount > 0) {
        await syncLogDateToApi(a, dk);
        showToast(`✓ ${uploadedCount} document${uploadedCount>1?'s':''} uploaded`);
        renderDocsTab();
    } else {
        showToast('Upload failed');
    }
    input.value = '';
}

async function replaceLegacyDoc(input, dk, docIndex) {
    if (!input.files.length || currentActivity === null) return;
    const a = activities[currentActivity];
    if (!a.logs[dk] || !Array.isArray(a.logs[dk].docs) || !a.logs[dk].docs[docIndex]) {
        showToast('Legacy entry not found');
        input.value = '';
        return;
    }

    const file = input.files[0];
    if (!isAllowedDocumentFile(file)) {
        showToast(`Invalid file type: ${file.name}`);
        input.value = '';
        return;
    }
    const formData = new FormData();
    formData.append('field_activity_id', a.apiId);
    formData.append('document', file);

    try {
        const res = await fetch('/api/field-activity-documents', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const data = await res.json();
        if (data && data.data) {
            a.logs[dk].docs[docIndex] = {
                id: data.data.id,
                name: data.data.file_name,
                file_path: data.data.file_path
            };
            await syncLogDateToApi(a, dk);
            renderDocsTab();
            renderOverview();
            showToast('✓ Legacy document replaced');
        } else {
            showToast('Replace failed');
        }
    } catch (e) {
        showToast('Replace failed');
    }

    input.value = '';
}

function openInvoicePreview(documentId) {
    const modal = document.getElementById('invoicePreviewModal');
    const frame = document.getElementById('invoice-preview-frame');
    if (!modal || !frame) return;
    frame.src = `/api/field-activity-documents/${documentId}/view`;
    modal.style.display = 'flex';
}

function closeInvoicePreview() {
    const modal = document.getElementById('invoicePreviewModal');
    const frame = document.getElementById('invoice-preview-frame');
    if (!modal || !frame) return;
    frame.src = '';
    modal.style.display = 'none';
}

// ════════════════════════════════════
// INIT
// ════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    fetchActivities();
    const modal = document.getElementById('invoicePreviewModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target.id === 'invoicePreviewModal') closeInvoicePreview();
        });
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeInvoicePreview();
    });
});
</script>
<div id="invoicePreviewModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.7);z-index:9999;padding:1rem;align-items:center;justify-content:center">
    <div style="width:min(100%, 1100px);height:min(92vh, 900px);background:#fff;border-radius:14px;box-shadow:0 20px 45px rgba(0,0,0,.25);display:flex;flex-direction:column;overflow:hidden">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.8rem 1rem;border-bottom:1px solid var(--border)">
            <div style="font-weight:700">Uploaded invoice</div>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closeInvoicePreview()">Close</button>
        </div>
        <iframe id="invoice-preview-frame" title="Uploaded invoice preview" style="flex:1;width:100%;border:0;background:#fff"></iframe>
    </div>
</div>
</body>
</html>