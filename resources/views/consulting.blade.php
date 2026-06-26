<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تكامل — فرص التطوع</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/consulting.css') }}?v=2">
  <link rel="stylesheet" href="{{ asset('css/meeting-scoped.css') }}">
  <link rel="stylesheet" href="{{ asset('css/orders-scoped.css') }}">
  <link rel="stylesheet" href="{{ asset('css/jp-scoped.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* My Associations Tab Styles */
    .my-assoc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(330px, 1fr)); gap: 20px; margin-top: 24px; }
    .my-assoc-card { background-color: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; padding: 22px; color: #1e293b; display: flex; flex-direction: column; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s; position: relative; overflow: hidden; }
    .my-assoc-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #0284c7, #38bdf8); opacity: 0; transition: opacity 0.3s; }
    .my-assoc-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.1); }
    .my-assoc-card:hover::before { opacity: 1; }
    .my-assoc-header { display: flex; gap: 14px; margin-bottom: 16px; }
    .my-assoc-logo { width: 60px; height: 60px; border-radius: 16px; object-fit: cover; background-color: #f8fafc; border: 1px solid #e2e8f0; flex-shrink: 0; }
    .my-assoc-logo-fallback { width: 60px; height: 60px; border-radius: 16px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #64748b; font-weight: 800; flex-shrink: 0; border: 1px solid #e2e8f0; font-family: Arial, sans-serif; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
    .my-assoc-title-wrap { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
    .my-assoc-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.2; }
    .my-assoc-desc { font-size: 0.9rem; color: #64748b; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 500; }
    .my-assoc-info { display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; background: #f8fafc; padding: 16px; border-radius: 16px; border: 1px solid #f1f5f9; }
    .my-assoc-info-row { display: flex; align-items: center; gap: 14px; font-size: 0.95rem; color: #475569; font-weight: 500; }
    .my-assoc-info-row i { color: #94a3b8; width: 20px; text-align: center; font-size: 1.1rem; }
    .my-assoc-info-row a, .my-assoc-info-row span { color: #475569; text-decoration: none; transition: color 0.2s; font-family: Arial, sans-serif; }
    .my-assoc-info-row a[dir="rtl"] { font-family: inherit; }
    .my-assoc-info-row a:hover { color: #0284c7; }
    .my-assoc-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    .my-assoc-status { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #059669; font-weight: 700; background: rgba(16, 185, 129, 0.1); padding: 6px 12px; border-radius: 20px; }
    .my-assoc-actions { display: flex; gap: 10px; }
    .btn-edit-assoc { background-color: #f8fafc; color: #1e293b; border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px 16px; font-size: 0.9rem; font-family: inherit; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; }
    .btn-edit-assoc:hover { background-color: #f1f5f9; border-color: #cbd5e1; }
    .btn-delete-assoc { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px; padding: 8px 16px; font-size: 0.9rem; font-family: inherit; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; }
    .btn-delete-assoc:hover { background-color: #ef4444; color: #fff; }

    /* ── Toggle Pill ── */
    .vt-pill-wrap { display:flex; gap:0; background:#f1f5f9; border-radius:14px; padding:4px; width:fit-content; margin-bottom:20px; }
    .vt-pill { display:flex; align-items:center; gap:8px; padding:9px 22px; border:none; border-radius:10px; font-family:inherit; font-size:0.88rem; font-weight:700; cursor:pointer; background:transparent; color:#64748b; transition:all 0.22s cubic-bezier(0.4,0,0.2,1); position:relative; }
    .vt-pill.active { background:#fff; color:#0f172a; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .vt-pill.active i { color:#2ab8d0; }
    .vt-pill-count { background:#2ab8d0; color:#fff; border-radius:20px; padding:1px 7px; font-size:0.72rem; font-weight:800; margin-right:2px; }

    /* ── Toolbar ── */
    .vt-toolbar { display:flex; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap; }
    .vt-search-wrap { flex:1; min-width:200px; display:flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:8px 14px; }
    .vt-search-wrap i { color:#94a3b8; font-size:0.85rem; }
    .vt-search-wrap input { border:none; outline:none; background:transparent; font-family:inherit; font-size:0.88rem; width:100%; }
    .vt-filter-sel { padding:9px 14px; border:1px solid #e2e8f0; border-radius:12px; font-family:inherit; font-size:0.85rem; background:#fff; outline:none; cursor:pointer; color:#374151; }

    /* ── Rich Opportunity Cards Grid ── */
    .rich-opps-grid { display:flex; flex-direction:column; gap:14px; }
    .rich-opp-card { background:#fff; border:1px solid #e8edf2; border-radius:18px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.04); transition:transform 0.2s, box-shadow 0.2s; }
    .rich-opp-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.08); }
    .rich-opp-card-top { padding:18px 20px 14px; }
    .rich-opp-card-title-row { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:8px; }
    .rich-opp-card-title { font-size:1.05rem; font-weight:800; color:#0f172a; line-height:1.3; }
    .rich-opp-card-actions { display:flex; gap:6px; flex-shrink:0; }
    .rich-opp-card-desc { font-size:0.86rem; color:#64748b; line-height:1.6; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .rich-opp-card-tags { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:4px; }
    .rich-opp-tag { font-size:0.75rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .rich-opp-tag-open { background:rgba(16,185,129,0.1); color:#059669; }
    .rich-opp-tag-closed { background:rgba(239,68,68,0.08); color:#dc2626; }
    .rich-opp-tag-onsite { background:rgba(42,184,208,0.1); color:#0e7490; }
    .rich-opp-tag-remote { background:rgba(99,102,241,0.1); color:#4f46e5; }
    .rich-opp-tag-cat { background:rgba(123,78,166,0.1); color:#7b4ea6; }
    .rich-opp-card-bottom { display:grid; grid-template-columns:repeat(4,1fr); border-top:1px solid #f1f5f9; }
    .rich-opp-stat { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:12px 8px; border-left:1px solid #f1f5f9; gap:3px; }
    .rich-opp-stat:last-child { border-left:none; }
    .rich-opp-stat-lbl { font-size:0.72rem; color:#94a3b8; font-weight:600; }
    .rich-opp-stat-val { font-size:0.92rem; font-weight:800; color:#1e293b; }
    .rich-opp-stat-val.gold { color:#d97706; }
    .rich-opp-stat-val.teal { color:#0e7490; }
    .rich-opp-stat-val.green { color:#059669; }
    .rich-opp-stat-val.red { color:#dc2626; }
    .rich-opp-stat i { font-size:1rem; margin-bottom:2px; }

    /* ── Redesigned Add/Edit Modal ── */
    .opp-modal-redesign { border-radius:20px; overflow:hidden; max-width:560px; width:100%; max-height: 90vh; display: flex; flex-direction: column; }
    .opp-modal-hd { background:linear-gradient(135deg,#0d3d49 0%,#1a6b7c 60%,#2ab8d0 100%); padding:20px 24px; display:flex; align-items:center; justify-content:space-between; flex-shrink: 0; }
    .opp-modal-hd-inner { display:flex; align-items:center; gap:14px; }
    .opp-modal-hd-icon { width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:1.3rem; color:#fff; flex-shrink:0; }
    .opp-modal-hd-title { font-size:1.05rem; font-weight:800; color:#fff; }
    .opp-modal-hd-sub { font-size:0.8rem; color:rgba(255,255,255,0.75); margin-top:2px; }
    .opp-modal-close { background:rgba(255,255,255,0.15); border:none; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#fff; transition:background 0.2s; flex-shrink:0; }
    .opp-modal-close:hover { background:rgba(255,255,255,0.28); }
    /* Chip row */
    .opp-chip-row { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; margin-top:16px; }
    .opp-chip { display:flex; align-items:center; gap:10px; background:#f8fafc; border:1px solid #e8edf2; border-radius:14px; padding:10px 14px; }
    .opp-chip-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .opp-chip-field { display:flex; flex-direction:column; gap:2px; flex:1; min-width:0; }
    .opp-chip-lbl { font-size:0.7rem; color:#94a3b8; font-weight:700; }
    .opp-chip-inp { border:none; background:transparent; outline:none; font-family:inherit; font-size:0.88rem; font-weight:700; color:#1e293b; width:100%; padding:0; }
    .opp-chip-inp option { font-family:inherit; }

    /* Modal Form Styles */
    .fg { display:flex; flex-direction:column; gap:6px; margin-bottom: 12px; flex-shrink: 0; }
    .fg label { font-size:0.83rem; font-weight:700; color:#374151; }
    .req-span { color:#dc2626; margin-right:2px; }
    .fld { position:relative; display:flex; align-items:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; flex-shrink: 0; }
    .fi { position:absolute; right:12px; color:#94a3b8; display:flex; align-items:center; pointer-events:none; }
    .fi.top { top:12px; align-items:flex-start; }
    .fld input, .fld textarea, .fld select { width:100%; border:none; outline:none; background:transparent; padding:10px 36px 10px 12px; font-family:inherit; font-size:0.88rem; color:#0f172a; resize:vertical; }
    .fld textarea { min-height:90px; padding-top:10px; }
    .row2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom: 12px; flex-shrink: 0; }

    /* Modal Body & Footer for Add Opp */
    .m-body { padding:22px 24px; display:flex; flex-direction:column; gap:16px; overflow-y:auto; flex:1; }
    .m-ft { padding:0 24px 20px; display:flex; gap:10px; flex-shrink:0; margin-top: auto; }
    .btn-cancel { flex:1; padding:11px; border:1px solid #e2e8f0; border-radius:12px; background:#fff; font-family:inherit; font-size:0.88rem; font-weight:700; cursor:pointer; color:#64748b; }
    .btn-cancel:hover { background:#f8fafc; }
    .btn-save { flex:2; padding:11px; border:none; border-radius:12px; background:linear-gradient(135deg,#0d3d49,#2ab8d0); font-family:inherit; font-size:0.88rem; font-weight:800; cursor:pointer; color:#fff; }
    .btn-save:hover { opacity:0.92; }

    #nb-reqs:empty{display:none!important}
    .cat-picker-wrap { display:flex; flex-wrap:wrap; gap:8px; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; min-height:48px; transition:border-color .18s; }
    .cat-picker-wrap:focus-within { border-color:#0d9488; box-shadow:0 0 0 3px rgba(13,148,136,0.12); }
    .cat-pill { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:50px; border:1.5px solid rgba(0,0,0,0.09); background:#fff; cursor:pointer; font-family:'Tajawal',sans-serif; font-size:0.83rem; font-weight:600; color:#475569; transition:all 0.18s ease; user-select:none; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
    .cat-pill:hover { border-color:rgba(13,148,136,0.4); background:rgba(13,148,136,0.06); color:#0d9488; transform:translateY(-1px); box-shadow:0 4px 10px rgba(13,148,136,0.12); }
    .cat-pill.active { background:linear-gradient(135deg,#0d9488,#0891b2); border-color:transparent; color:#fff; box-shadow:0 4px 14px rgba(13,148,136,0.35); transform:translateY(-1px); }
    .cat-pill-all.active { background:linear-gradient(135deg,#1e40af,#0d9488); box-shadow:0 4px 14px rgba(30,64,175,0.3); }
    .cat-pill-ico { font-size:1rem; line-height:1; }
    .cat-pill-lbl { white-space:nowrap; }

    /* ── Category Cards (New Design) ── */
    .categories-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:14px; margin-bottom:28px; }
    .cat-card-new {
        position:relative; background:#fff; border-radius:18px;
        border:1.5px solid #e2e8f0; overflow:hidden; cursor:pointer;
        transition:all .22s; box-shadow:0 2px 10px rgba(0,0,0,.05);
        min-height: 110px; display: flex; flex-direction: column; justify-content: center;
    }
    .cat-card-new:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.1); border-color:var(--cc,#2ab8d0); }
    .cat-card-new-accent { position:absolute; top:0; right:0; left:0; height:4px; background:var(--cc,#2ab8d0); }
    .cat-card-new-header { display:flex; align-items:center; gap:12px; padding:18px 16px; }
    .cat-card-new-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .cat-card-new-info { flex:1; min-width:0; }
    .cat-card-new-name { font-weight:800; font-size:.95rem; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .cat-card-new-count { font-size:.76rem; color:#94a3b8; margin-top:2px; }
    .cat-card-new-actions { display:flex; gap:6px; flex-shrink:0; }
    .cat-card-new-bar { margin:0 14px; height:5px; border-radius:99px; background:#f1f5f9; }
    .cat-card-new-fill { height:100%; border-radius:99px; transition:width .4s; }
    .cat-card-new-footer { display:flex; justify-content:space-between; align-items:center; padding:8px 14px 12px; }
    .cat-card-all {
        background:linear-gradient(135deg,#0a5565,#2ab8d0) !important;
        border-color:transparent !important;
    }
    .cat-action-btn {
        width:30px; height:30px; border-radius:8px; border:none; cursor:pointer;
        display:flex; align-items:center; justify-content:center; font-size:.78rem;
        transition:all .18s;
    }
    .cat-edit-btn { background:rgba(8,145,178,.1); color:#0891b2; }
    .cat-edit-btn:hover { background:#0891b2; color:#fff; }
    .cat-delete-btn { background:rgba(239,68,68,.1); color:#ef4444; }
    .cat-delete-btn:hover { background:#ef4444; color:#fff; }

    /* ── Association Items (New Design) ── */
    .assoc-item-new {
        display:flex; align-items:center; gap:14px; background:#fff;
        border:1.5px solid #e2e8f0; border-radius:14px; padding:14px 16px;
        transition:all .2s;
    }
    .assoc-item-new:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); border-color:#c7d2fe; }
    .assoc-item-avatar { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.3rem; }
    .assoc-item-info { flex:1; min-width:0; }
    .assoc-item-name { font-weight:800; font-size:.9rem; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .assoc-item-cat { font-size:.75rem; color:#64748b; margin-top:2px; }
    .assoc-item-email { font-size:.72rem; color:#94a3b8; margin-top:1px; direction:ltr; text-align:right; }
    .assoc-item-meta { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }
    .assoc-item-date { font-size:.72rem; color:#94a3b8; }

    /* ── Category Modals (add-assoc-cat / edit-assoc-cat) ── */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(10,25,35,0.6);
        backdrop-filter: blur(8px);
        z-index: 9000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.open { display: flex; }
    .modal-overlay .modal {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 32px 80px rgba(0,0,0,0.25);
        animation: modalSlideIn .25s cubic-bezier(0.16,1,0.3,1);
    }
    @keyframes modalSlideIn {
        from { transform: translateY(24px) scale(0.96); opacity: 0; }
        to   { transform: translateY(0)    scale(1);    opacity: 1; }
    }
    .modal-overlay .modal-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px; border-bottom: 1px solid #e2e8f0;
        background: #f8fafc; border-radius: 20px 20px 0 0;
    }
    .modal-overlay .modal-head h3 { font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0; }
    .modal-overlay .modal-body { padding: 20px 22px; }
    .modal-overlay .modal-footer {
        display: flex; gap: 10px; justify-content: flex-end;
        padding: 14px 22px; border-top: 1px solid #e2e8f0;
        background: #f8fafc; border-radius: 0 0 20px 20px;
    }
    .modal-overlay .btn { padding: 9px 20px; border-radius: 10px; font-family: inherit; font-size: .88rem; font-weight: 700; cursor: pointer; border: 1.5px solid transparent; transition: all .18s; }
    .modal-overlay .btn-ghost { background: #fff; border-color: #e2e8f0; color: #64748b; }
    .modal-overlay .btn-ghost:hover { background: #f1f5f9; }
    .modal-overlay .btn-primary { background: linear-gradient(135deg,#0d3d49,#2ab8d0); color: #fff; border-color: transparent; box-shadow: 0 3px 12px rgba(42,184,208,.35); }
    .modal-overlay .btn-primary:hover { opacity: .92; }
  </style>
</head>

<body>
  <div class="layout">

    @include('layouts.sidebar-admin', ['activeNav' => 'volunteer'])


    <!-- ══ MAIN ══ -->
    <div class="main">

      <!-- TOPBAR -->
      @include('layouts.topbar', ['title' => 'فرص التطوع'])


      <!-- CONTENT -->
      <div class="content">

        <!-- ADMIN VIEW: Categories + Add Opportunities -->
        <div class="view active" id="view-admin">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">إدارة التصنيفات وإضافة فرص التطوع لكل تصنيف</div>
            </div>
            <div class="ph-actions">
              <button class="btn-primary" onclick="openAddOpp('global')">
                <div class="btn-icon-wrap" style="background:rgba(255,255,255,0.22);color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-weight:bold">+</div>
                نشر فرصة
              </button>
            </div>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)">
              <div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-star"></i></div>
              <div><span class="s-num" id="st-total">0</span><span class="s-lbl">إجمالي الفرص</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--green)">
              <div class="s-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-circle-check"></i></div>
              <div><span class="s-num" id="st-open">0</span><span class="s-lbl">فرص مفتوحة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--gold)">
              <div class="s-icon" style="background:rgba(245,158,11,0.1)"><i class="fa-solid fa-hourglass-half"></i></div>
              <div><span class="s-num" id="st-pending">0</span><span class="s-lbl">طلبات معلقة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--purple)">
              <div class="s-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-tag"></i></div>
              <div><span class="s-num" id="st-cats">6</span><span class="s-lbl">التصنيفات</span></div>
            </div>
          </div>

          {{-- ── Toggle pill ── --}}
          <div class="vt-pill-wrap">
            <button class="vt-pill active" id="pill-opps" onclick="switchAdminTab('opps')">
              <i class="fa-solid fa-list-ul"></i> الفرص
            </button>
            <button class="vt-pill" id="pill-cats" onclick="switchAdminTab('cats')">
              <i class="fa-solid fa-tag"></i> التصنيفات
              <span class="vt-pill-count" id="pill-cats-count"></span>
            </button>
          </div>

          {{-- ── All Opportunities Panel ── --}}
          <div id="panel-all-opps">
            <div class="vt-toolbar">
              <div class="vt-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="all-opp-search" placeholder="ابحث عن فرصة..." oninput="renderAllAdminOpps()">
              </div>
              <select class="vt-filter-sel" id="all-opp-cat-filter" onchange="renderAllAdminOpps()">
                <option value="">جميع التصنيفات</option>
              </select>
              <select class="vt-filter-sel" id="all-opp-status-filter" onchange="renderAllAdminOpps()">
                <option value="">كل الحالات</option>
                <option value="open">مفتوحة</option>
                <option value="closed">مغلقة</option>
              </select>
            </div>
            <div class="rich-opps-grid" id="all-admin-opps-grid"></div>
          </div>

          {{-- ── Categories Panel ── --}}
          <div id="panel-cats" style="display:none">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
              <div style="font-size:1rem;font-weight:800;color:var(--ink)">التصنيفات</div>
              <div style="font-size:0.82rem;color:var(--muted)">اختر تصنيفاً لعرض فرص التطوع الخاصة به أو إضافة فرصة جديدة</div>
            </div>
            <div class="cats-grid" id="cats-grid"></div>
          </div>
        </div>

        <!-- ADMIN: Opportunities for a specific category -->
        <div class="view" id="view-admin-opps">
          <div class="opp-view-header">
            <button class="back-btn" onclick="backToCategories()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M19 12H5M12 5l7 7-7 7" />
              </svg>
              التصنيفات
            </button>
            <div class="cat-title-badge">
              <span class="ctb-icon" id="ao-cat-icon"></span>
              <span class="ctb-name" id="ao-cat-name"></span>
            </div>
          </div>
          <div class="page-hd">
            <div>
              <div class="ph-title" id="ao-title">الفرص</div>
              <div class="ph-sub" id="ao-sub"></div>
            </div>
            <button class="btn-primary" onclick="openAddOpp()">
              <div class="btn-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                  width="14" height="14">
                  <line x1="12" y1="5" x2="12" y2="19" />
                  <line x1="5" y1="12" x2="19" y2="12" />
                </svg></div>
              إضافة فرصة
            </button>
          </div>
          <div class="toolbar">
            <div class="sw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15"
                height="15">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
              </svg><input class="s-inp" id="admin-opp-search" type="text" placeholder="ابحث عن فرصة..."
                oninput="renderAdminOpps()"></div>
          </div>
          <div class="opps-grid" id="admin-opps-grid"></div>
        </div>

        <!-- ADMIN: Requests -->
        <div class="view" id="view-admin-reqs">
          <div class="page-hd">
            <div>
              <div class="ph-title">طلبات التقديم</div>
              <div class="ph-sub">مراجعة طلبات الجمعيات والبت فيها</div>
            </div>
            <button class="back-btn" onclick="showAdminMain()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M19 12H5M12 5l7 7-7 7" />
              </svg>
              فرص التطوع
            </button>
          </div>

          <div class="req-msg-card">
            <div class="req-msg-icon"><i class="fa-solid fa-inbox"></i></div>
            <div>
              <div class="req-msg-title">صلاحية الأدمن</div>
              <div class="req-msg-sub">أنت وحدك من يملك صلاحية قبول أو رفض طلبات الجمعيات. تأتي الطلبات تلقائياً عند
                تقديم جمعية طلب تطوع.</div>
            </div>
          </div>

          <div class="req-tabs">
            <button class="req-tab active" id="rtab-pending" onclick="filterReqs('pending')">⏳ معلقة <span class="rc"
                id="rc-pending">0</span></button>
            <button class="req-tab" id="rtab-approved" onclick="filterReqs('approved')">✅ مقبولة <span class="rc"
                id="rc-approved">0</span></button>
            <button class="req-tab" id="rtab-rejected" onclick="filterReqs('rejected')">❌ مرفوضة <span class="rc"
                id="rc-rejected">0</span></button>
          </div>

          <div class="req-list" id="req-list"></div>
        </div>

        <!-- ASSOCIATION VIEW: Browse by category -->
        <div class="view" id="view-assoc">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">تصفح الفرص المتاحة وقدّم طلبك — سيتم مراجعة طلبك من قِبَل مبادرون</div>
            </div>
          </div>

          <div id="assoc-notif-banner" style="display:none" class="notif-banner">
            <div class="nb-icon"><i class="fa-solid fa-bell"></i></div>
            <div class="nb-text">
              <div class="nb-title" id="notif-banner-title">تم قبول طلبك</div>
              <div class="nb-sub" id="notif-banner-sub">تم قبول طلب تقديمك بنجاح من قِبَل الإدارة</div>
            </div>
            <button class="nb-close"
              onclick="document.getElementById('assoc-notif-banner').style.display='none'">إغلاق</button>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)">
              <div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-star"></i></div>
              <div><span class="s-num" id="ast-total">0</span><span class="s-lbl">فرص متاحة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--gold)">
              <div class="s-icon" style="background:rgba(245,158,11,0.1)"><i class="fa-regular fa-pen-to-square"></i></div>
              <div><span class="s-num" id="ast-applied">0</span><span class="s-lbl">طلباتي</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--green)">
              <div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div>
              <div><span class="s-num" id="ast-approved">0</span><span class="s-lbl">مقبولة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--purple)">
              <div class="s-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-tag"></i></div>
              <div><span class="s-num" id="ast-cats">6</span><span class="s-lbl">التصنيفات</span></div>
            </div>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div style="font-size:1rem;font-weight:800;color:var(--ink)">تصفح التصنيفات</div>
          </div>
          <div class="cats-grid" id="assoc-cats-grid"></div>
        </div>

        <!-- ASSOCIATION: Opportunities for a category -->
        <div class="view" id="view-assoc-opps">
          <div class="opp-view-header">
            <button class="back-btn" onclick="backToAssocCats()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M19 12H5M12 5l7 7-7 7" />
              </svg>
              التصنيفات
            </button>
            <div class="cat-title-badge">
              <span class="ctb-icon" id="ao-cat-icon2"></span>
              <span class="ctb-name" id="ao-cat-name2"></span>
            </div>
          </div>
          <div class="page-hd">
            <div>
              <div class="ph-title" id="ao-title2">الفرص</div>
              <div class="ph-sub">اضغط على "تقديم" للتقدم. سيُرسَل طلبك لمراجعة مبادرون.</div>
            </div>
          </div>
          <div class="toolbar">
            <div class="sw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15"
                height="15">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
              </svg><input class="s-inp" id="assoc-opp-search" type="text" placeholder="ابحث عن فرصة..."
                oninput="renderAssocOpps()"></div>
          </div>
          <div class="opps-grid" id="assoc-opps-grid"></div>
        </div>

        <!-- ══════════════════════════════════
         SERVICE VIEWS
    ══════════════════════════════════ -->

        <!-- بناء وحدات -->
        <div class="view" id="view-units">
          <div class="svc-hero">
            <span class="svc-hero-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
            <div class="svc-hero-title">بناء وحدات</div>
            <div class="svc-hero-sub">نساعد الجمعيات في تأسيس وحداتها التنظيمية وتطوير هياكلها الإدارية لتحقيق الكفاءة
              والفاعلية.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-bullseye"></i></span>
              <div class="svc-card-title">تحليل الاحتياجات</div>
              <div class="svc-card-desc">دراسة وضع الجمعية الحالي وتحديد الوحدات التنظيمية المطلوبة لتحقيق أهدافها
                الاستراتيجية.</div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-compass-drafting"></i></span>
              <div class="svc-card-title">تصميم الهيكل التنظيمي</div>
              <div class="svc-card-desc">بناء هياكل تنظيمية واضحة تحدد الأدوار والمسؤوليات وخطوط الإبلاغ بشكل فعّال.
              </div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">⚙️</span>
              <div class="svc-card-title">تفعيل الوحدات</div>
              <div class="svc-card-desc">دعم الجمعية في إطلاق وحداتها الجديدة وتزويد فرقها بالأدوات والموارد اللازمة
                للعمل.</div>
            </div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon"><i class="fa-solid fa-chart-column"></i></span>
              <div class="svc-card-title">قياس الأداء</div>
              <div class="svc-card-desc">وضع مؤشرات أداء واضحة لمتابعة تطور الوحدات وقياس مدى تحقيقها لأهدافها المرسومة.
              </div>
            </div>
          </div>
          <div class="svc-steps">
            <div class="svc-step">
              <div class="svc-step-num">١</div>
              <div>
                <div class="svc-step-title">جلسة التشخيص</div>
                <div class="svc-step-desc">لقاء استكشافي مع فريق الجمعية لفهم الوضع الحالي والطموحات المستقبلية.</div>
              </div>
            </div>
            <div class="svc-step">
              <div class="svc-step-num">٢</div>
              <div>
                <div class="svc-step-title">التخطيط والتصميم</div>
                <div class="svc-step-desc">إعداد خطة بناء الوحدات وتصميم الهيكل التنظيمي المناسب مع تحديد الأدوار.</div>
              </div>
            </div>
            <div class="svc-step">
              <div class="svc-step-num">٣</div>
              <div>
                <div class="svc-step-title">التطبيق والمتابعة</div>
                <div class="svc-step-desc">الإشراف على تطبيق الخطة وتقديم الدعم المستمر حتى تستقر الوحدات وتعمل
                  باستقلالية.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- بناء أنظمة -->
        <div class="view" id="view-systems">
          <div class="svc-hero" style="background:linear-gradient(135deg,#0d3d49,#1a6b7c,#3a72b8)">
            <span class="svc-hero-icon"><i class="fa-solid fa-gear"></i></span>
            <div class="svc-hero-title">بناء أنظمة</div>
            <div class="svc-hero-sub">تطوير الأنظمة والإجراءات التشغيلية التي تضمن استدامة عمل الجمعيات وتحسين جودة
              خدماتها.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon"><i class="fa-solid fa-clipboard-list"></i></span>
              <div class="svc-card-title">أنظمة الحوكمة</div>
              <div class="svc-card-desc">بناء أنظمة إدارية واضحة تضمن الشفافية والمساءلة وحسن اتخاذ القرار.</div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-rotate"></i></span>
              <div class="svc-card-title">العمليات التشغيلية</div>
              <div class="svc-card-desc">توثيق وتطوير الإجراءات التشغيلية لضمان الكفاءة وتقليل الأخطاء وتوحيد العمل.
              </div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-database"></i></span>
              <div class="svc-card-title">أنظمة المعلومات</div>
              <div class="svc-card-desc">تصميم أنظمة لإدارة البيانات والمعلومات وضمان سهولة الوصول واتخاذ القرار المبني
                على البيانات.</div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-shield-halved"></i></span>
              <div class="svc-card-title">أنظمة الجودة</div>
              <div class="svc-card-desc">تطبيق معايير الجودة ومراجعتها بشكل دوري لضمان الارتقاء المستمر بمستوى الخدمات.
              </div>
            </div>
          </div>
        </div>

        <!-- تنسيق مبادرات -->
        <div class="view" id="view-initiatives">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1d3d1a,#2eaa78,#34d399)">
            <span class="svc-hero-icon"><i class="fa-solid fa-handshake-angle"></i></span>
            <div class="svc-hero-title">تنسيق مبادرات</div>
            <div class="svc-hero-sub">تيسير التعاون بين الجمعيات وتنسيق المبادرات المشتركة لتعظيم الأثر المجتمعي.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-globe"></i></span>
              <div class="svc-card-title">الشبكات التعاونية</div>
              <div class="svc-card-desc">ربط الجمعيات ذات الاهتمامات المشتركة لتبادل الموارد والخبرات وتنفيذ مشاريع
                مشتركة.</div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-map-location-dot"></i></span>
              <div class="svc-card-title">خرائط المبادرات</div>
              <div class="svc-card-desc">توثيق وتصنيف المبادرات القائمة وتجنب التكرار وضمان التكامل بين الجهود المختلفة.
              </div>
            </div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">⚡</span>
              <div class="svc-card-title">مبادرات الطوارئ</div>
              <div class="svc-card-desc">التنسيق السريع بين الجمعيات في حالات الأزمات لتقديم استجابة فعّالة ومنظمة.
              </div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-chart-line"></i></span>
              <div class="svc-card-title">قياس الأثر المشترك</div>
              <div class="svc-card-desc">تطوير أطر مشتركة لقياس الأثر الاجتماعي للمبادرات التعاونية وإعداد التقارير.
              </div>
            </div>
          </div>
        </div>

        <!-- تدريب تطوعي -->
        <div class="view" id="view-training">
          <div class="svc-hero" style="background:linear-gradient(135deg,#4a1942,#7b4ea6,#a78bfa)">
            <span class="svc-hero-icon"><i class="fa-solid fa-graduation-cap"></i></span>
            <div class="svc-hero-title">تدريب تطوعي</div>
            <div class="svc-hero-sub">برامج تدريبية متخصصة لتأهيل المتطوعين وبناء قدراتهم لأداء أعمال تطوعية ذات أثر
              حقيقي.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-seedling"></i></span>
              <div class="svc-card-title">تأهيل المتطوعين الجدد</div>
              <div class="svc-card-desc">برامج استقبال وتوجيه تزود المتطوعين الجدد بالمعرفة والمهارات اللازمة للمشاركة
                الفعّالة.</div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-trophy"></i></span>
              <div class="svc-card-title">تطوير القيادات</div>
              <div class="svc-card-desc">برامج قيادية متخصصة لإعداد جيل جديد من قادة العمل التطوعي في القطاع غير الربحي.
              </div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
              <div class="svc-card-title">مهارات تقنية</div>
              <div class="svc-card-desc">تدريب المتطوعين على الأدوات الرقمية وتقنيات إدارة المشاريع والتواصل الفعّال.
              </div>
            </div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon"><i class="fa-solid fa-lightbulb"></i></span>
              <div class="svc-card-title">الابتكار الاجتماعي</div>
              <div class="svc-card-desc">ورش عمل تحفّز على التفكير الإبداعي وتطوير حلول مبتكرة للتحديات المجتمعية.</div>
            </div>
          </div>
          <div class="svc-steps">
            <div class="svc-step">
              <div class="svc-step-num">١</div>
              <div>
                <div class="svc-step-title">تقييم المتطوعين</div>
                <div class="svc-step-desc">فهم مستوى المتطوعين وتحديد احتياجاتهم التدريبية وأهدافهم الشخصية.</div>
              </div>
            </div>
            <div class="svc-step">
              <div class="svc-step-num">٢</div>
              <div>
                <div class="svc-step-title">تصميم البرنامج</div>
                <div class="svc-step-desc">إعداد برنامج تدريبي مخصص يجمع بين النظرية والتطبيق العملي الميداني.</div>
              </div>
            </div>
            <div class="svc-step">
              <div class="svc-step-num">٣</div>
              <div>
                <div class="svc-step-title">التدريب والتطبيق</div>
                <div class="svc-step-desc">تنفيذ البرنامج بإشراف متخصصين وتوفير تغذية راجعة مستمرة للمتطوعين.</div>
              </div>
            </div>
            <div class="svc-step">
              <div class="svc-step-num">٤</div>
              <div>
                <div class="svc-step-title">الشهادة والمتابعة</div>
                <div class="svc-step-desc">منح شهادات معتمدة ومتابعة المتطوعين لضمان توظيف ما تعلموه في عملهم.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- الاستشارات -->
        <div class="view" id="view-consulting">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1e3a5f,#3a72b8,#60a5fa)">
            <span class="svc-hero-icon"><i class="fa-solid fa-lightbulb"></i></span>
            <div class="svc-hero-title">الاستشارات</div>
            <div class="svc-hero-sub">خدمات استشارية متخصصة تدعم الجمعيات في اتخاذ قراراتها الاستراتيجية وتطوير قدراتها
              المؤسسية.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon"><i class="fa-solid fa-map-location-dot"></i></span>
              <div class="svc-card-title">التخطيط الاستراتيجي</div>
              <div class="svc-card-desc">مساعدة الجمعيات في صياغة رؤيتها ورسالتها وأهدافها الاستراتيجية للسنوات القادمة.
              </div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-chart-column"></i></span>
              <div class="svc-card-title">التقييم المؤسسي</div>
              <div class="svc-card-desc">تشخيص شامل للوضع المؤسسي وتحديد نقاط القوة والضعف وفرص التطوير.</div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-sack-dollar"></i></span>
              <div class="svc-card-title">الاستدامة المالية</div>
              <div class="svc-card-desc">استشارات في تنويع مصادر التمويل وبناء نماذج عمل مستدامة للجمعيات.</div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-link"></i></span>
              <div class="svc-card-title">الشراكات الاستراتيجية</div>
              <div class="svc-card-desc">تطوير استراتيجيات للشراكة مع القطاعات الحكومية والخاصة لتعزيز الأثر.</div>
            </div>
          </div>
        </div>

        <!-- التواصل معنا -->
        <div class="view" id="view-contact">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1a2f1a,#2eaa78,#6ee7b7)">
            <span class="svc-hero-icon"><i class="fa-solid fa-envelope-open-text"></i></span>
            <div class="svc-hero-title">التواصل معنا</div>
            <div class="svc-hero-sub">نحن هنا لمساعدتك. تواصل معنا وسيردّ فريقنا خلال 24 ساعة.</div>
          </div>
          <div class="contact-wrap">
            <div class="contact-info">
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-envelope"></i></div>
                <div>
                  <div class="cc-label">البريد الإلكتروني</div>
                  <div class="cc-value">info@mubadiroon.sa</div>
                </div>
              </div>
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-phone"></i></div>
                <div>
                  <div class="cc-label">الجوال والواتساب</div>
                  <div class="cc-value">966-50-000-0000+</div>
                </div>
              </div>
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-location-dot"></i></div>
                <div>
                  <div class="cc-label">الموقع</div>
                  <div class="cc-value">الرياض، المملكة العربية السعودية</div>
                </div>
              </div>
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(245,158,11,0.1)"><i class="fa-regular fa-clock"></i></div>
                <div>
                  <div class="cc-label">أوقات العمل</div>
                  <div class="cc-value">الأحد – الخميس، ٨ص – ٥م</div>
                </div>
              </div>
            </div>
            <div class="contact-form-card">
              <div class="cfc-title">أرسل لنا رسالة</div>
              <div class="fg"><label>الاسم الكامل <span class="req-span">*</span></label>
                <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2" width="15" height="15">
                      <circle cx="12" cy="8" r="4" />
                      <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                    </svg></span><input type="text" placeholder="أدخل اسمك الكامل"></div>
              </div>
              <div class="fg"><label>البريد الإلكتروني <span class="req-span">*</span></label>
                <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2" width="15" height="15">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                      <polyline points="22,6 12,13 2,6" />
                    </svg></span><input type="email" placeholder="example@mail.com" dir="ltr" style="text-align:right">
                </div>
              </div>
              <div class="fg"><label>نوع الطلب</label>
                <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2" width="15" height="15">
                      <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                      <line x1="7" y1="7" x2="7.01" y2="7" />
                    </svg></span><select>
                    <option>استشارة</option>
                    <option>شراكة</option>
                    <option>تدريب</option>
                    <option>تنسيق مبادرة</option>
                    <option>استفسار عام</option>
                  </select></div>
              </div>
              <div class="fg"><label>الرسالة <span class="req-span">*</span></label>
                <div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="2" width="15" height="15">
                      <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                    </svg></span><textarea placeholder="اكتب رسالتك هنا..." style="min-height:100px"></textarea></div>
              </div>
              <button class="btn-save" style="width:100%" onclick="sendContactMsg()"><i class="fa-regular fa-paper-plane" style="margin-left:8px"></i> إرسال الرسالة</button>
            </div>
          </div>
        </div>

        {{-- ══ MEETINGS SECTION ══ --}}
        <div class="view" id="view-meetings">
          <div class="content">

            <!-- PAGE HEADER -->
            <div class="page-header">
              <div>
                <div class="ph-title">إدارة الاجتماعات</div>
                <div class="ph-sub">تنظيم ومتابعة اجتماعات الجمعيات المجتمعية</div>
              </div>
              <button class="btn-create" onclick="openCreate()">
                <div class="btn-create-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                    <line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
                  </svg>
                </div>
                إنشاء اجتماع
              </button>
            </div>

            <!-- STATS -->
            <div class="stats-row">
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-regular fa-calendar"></i></div><div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div></div>
              <div class="stat-card" style="--sc:var(--green)"><div class="stat-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-circle"></i></div><div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">الحالية والقادمة</span></div></div>
              <div class="stat-card" style="--sc:var(--muted)"><div class="stat-icon" style="background:rgba(106,132,148,0.1)"><i class="fa-regular fa-folder"></i></div><div><span class="stat-num" id="s-past">0</span><span class="stat-lbl">السابقة</span></div></div>
              <div class="stat-card" style="--sc:var(--red)"><div class="stat-icon" style="background:rgba(198,40,40,0.08)"><i class="fa-solid fa-ban"></i></div><div><span class="stat-num" id="s-canc">0</span><span class="stat-lbl">الملغاة</span></div></div>
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-laptop"></i></div><div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div></div>
            </div>

            <!-- TOOLBAR -->
            <div class="toolbar">
              <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
                <input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="renderAll()">
              </div>
              <div class="tb-div"></div>
              <select class="filter-select" id="catFilter" onchange="renderAll()">
                <option value="">كل التصنيفات</option>
              </select>
              <div class="tb-div"></div>
              <div class="chips">
                <div class="chip on" id="chip-all" onclick="setTypeF('all')">الكل</div>
                <div class="chip" id="chip-online" onclick="setTypeF('online')">عن بعد</div>
                <div class="chip" id="chip-onsite" onclick="setTypeF('onsite')">حضوري</div>
              </div>
            </div>

            <div class="sec-wrap">
              <div class="sec-header"><div class="sec-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-circle" style="font-size:.7rem"></i></div><div class="sec-title">الاجتماعات الحالية والقادمة</div><span class="sec-count sc-current" id="bc-cur">0</span></div>
              <div class="meetings-grid" id="grid-cur"></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('past')"><div class="sec-icon" style="background:rgba(106,132,148,0.1)"><i class="fa-regular fa-folder"></i></div><div class="sec-title">الاجتماعات السابقة</div><span class="sec-count sc-past" id="bc-past">0</span><div class="sec-toggle" id="tog-past"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6" /></svg></div></div>
              <div id="sec-past"><div class="compact-list" id="list-past"></div></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('canc')"><div class="sec-icon" style="background:rgba(198,40,40,0.08)"><i class="fa-solid fa-ban"></i></div><div class="sec-title">الاجتماعات الملغاة</div><span class="sec-count sc-cancelled" id="bc-canc">0</span><div class="sec-toggle" id="tog-canc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6" /></svg></div></div>
              <div id="sec-canc"><div class="compact-list" id="list-canc"></div></div>
            </div>

          </div>{{-- /meetings content --}}

          <!-- ── MEETINGS MODALS ── -->
          <div class="overlay" id="ov-create" onclick="bgClose(event,'ov-create')"><div class="modal" onclick="event.stopPropagation()"><div class="modal-hd"><div class="modal-hd-icon" id="mhd-icon"><i class="fa-regular fa-calendar"></i></div><div class="modal-hd-text"><div class="modal-hd-title" id="mhd-title">إنشاء اجتماع جديد</div><div class="modal-hd-sub" id="mhd-sub">أضف تفاصيل الاجتماع أدناه</div></div><button class="modal-close" onclick="closeOv('ov-create')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
          <div class="modal-body">
            <div class="fg"><label>عنوان الاجتماع <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span><input type="text" id="f-title" placeholder="مثال: اجتماع التخطيط الاستراتيجي"></div></div>
            <div class="row2">
              <div class="fg"><label>التصنيف <span class="req">*</span></label><div id="f-cat-picker"></div><input type="hidden" id="f-cat" /></div>
              <div class="fg"><label>اسم المقدم <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><input type="text" id="f-presenter" placeholder="اسم المقدم"></div></div>
            </div>
            <div class="row2">
              <div class="fg"><label>التاريخ <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span><input type="date" id="f-date"></div></div>
              <div class="fg"><label>الوقت</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span><input type="time" id="f-time"></div></div>
            </div>
            <div class="form-divider">نوع الاجتماع</div>
            <div class="fg"><label>نوع الاجتماع <span class="req">*</span></label><div class="type-toggle"><button class="type-btn" id="tb-online" onclick="setMType('online')" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>عن بعد</button><button class="type-btn" id="tb-onsite" onclick="setMType('onsite')" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>حضوري</button></div></div>
            <div class="fg" id="fg-link"><label>رابط الاجتماع</label><div class="fld link-copy-wrap"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></span><input type="url" id="f-link" placeholder="https://meet.google.com/xxx-xxxx-xxx" dir="ltr" style="text-align:right;padding-left:76px"><button class="link-copy-btn" type="button" onclick="copyLink()">نسخ</button></div></div>
            <div class="fg" id="fg-location" style="display:none"><label>المكان</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span><input type="text" id="f-location" placeholder="مثال: قاعة الاجتماعات الرئيسية — الرياض"></div></div>
            <div class="fg"><label>ملاحظات</label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span><textarea id="f-notes" placeholder="أضف أي ملاحظات أو تفاصيل إضافية..."></textarea></div></div>
            <div id="report-section" class="report-section" style="display:none">
              <div class="report-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>تقرير الاجتماع</div>
              <div class="fg" style="margin-bottom:12px"><label>ملخص ما تم مناقشته</label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span><textarea id="f-report-summary" placeholder="اكتب ملخصاً لما تمت مناقشته في الاجتماع..." style="min-height:90px"></textarea></div></div>
              <div class="fg" style="margin-bottom:12px"><label>القرارات المتخذة</label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></span><textarea id="f-report-decisions" placeholder="اذكر القرارات الرئيسية التي تم اتخاذها..." style="min-height:80px"></textarea></div></div>
              <div class="row2">
                <div class="fg" style="margin-bottom:0"><label>عدد الحضور</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg></span><input type="number" id="f-report-attendees" placeholder="مثال: 12" min="0"></div></div>
                <div class="fg" style="margin-bottom:0"><label>الإجراءات التالية</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><polyline points="9 18 15 12 9 6"/></svg></span><input type="text" id="f-report-actions" placeholder="مثال: اجتماع متابعة في مارس"></div></div>
              </div>
            </div>
          </div>
          <div class="modal-ft"><button class="btn-cancel" onclick="closeOv('ov-create')">إلغاء</button><button class="btn-save" onclick="saveMeeting()"><span id="save-lbl"><i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ الاجتماع</span></button></div>
          </div></div>

          <div class="overlay" id="ov-details" onclick="bgClose(event,'ov-details')"><div class="det-modal" onclick="event.stopPropagation()"><div class="det-banner"><div class="det-banner-bg" id="d-banner-bg"></div><div class="det-banner-pattern"></div><div class="det-banner-content"><div class="det-type-badge" id="d-type-badge"></div></div><button class="det-close" onclick="closeOv('ov-details')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div class="det-body"><div class="det-title" id="d-title"></div><div class="det-grid" id="d-grid"><div class="det-cell"><div class="det-cell-lbl">الفئة</div><div class="det-cell-val" id="d-cat"></div></div><div class="det-cell"><div class="det-cell-lbl">التاريخ</div><div class="det-cell-val" id="d-date"></div></div><div class="det-cell"><div class="det-cell-lbl">الوقت</div><div class="det-cell-val" id="d-time"></div></div><div class="det-cell" id="d-loc-cell"><div class="det-cell-lbl">المكان</div><div class="det-cell-val" id="d-loc"></div></div></div><div class="det-presenter"><div class="dp-av" id="d-av"></div><div><div class="dp-name" id="d-pname"></div><div class="dp-role">مقدم الاجتماع</div></div></div><div id="d-notes-wrap" style="display:none" class="det-block"><div class="det-block-lbl">ملاحظات</div><div class="det-notes" id="d-notes"></div></div><div id="d-report-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--green)"><i class="fa-solid fa-clipboard-list" style="margin-left:6px"></i> تقرير الاجتماع</div><div class="det-report" id="d-report-content"></div></div><div id="d-cancel-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--red)">سبب الإلغاء</div><div class="det-cancel" id="d-cancel-reason"></div></div></div><div class="det-ft"><button class="btn-cancel" style="flex:1" onclick="closeOv('ov-details')">إغلاق</button><button class="btn-save" id="det-edit-btn" onclick="editFromDet()" style="flex:1"><i class="fa-regular fa-pen-to-square" style="margin-left:8px"></i> تعديل</button></div></div></div>

          <div class="overlay" id="ov-delete" onclick="bgClose(event,'ov-delete')"><div class="confirm-box" onclick="event.stopPropagation()"><div class="confirm-icon-wrap" style="background:rgba(229,57,53,0.1)"><i class="fa-solid fa-trash-can" style="color:#e11d48"></i></div><div class="confirm-title">حذف الاجتماع نهائياً</div><div class="confirm-desc">هل أنت متأكد؟ سيتم حذف الاجتماع بشكل دائم<br>ولا يمكن التراجع عن هذا الإجراء.</div><div class="confirm-row"><button class="btn-cancel" style="flex:1" onclick="closeOv('ov-delete')">إلغاء</button><button class="btn-danger" onclick="doDelete()">حذف نهائياً</button></div></div></div>

          <div class="overlay" id="ov-cancel" onclick="bgClose(event,'ov-cancel')"><div class="cancel-reason-box" onclick="event.stopPropagation()"><div class="modal-hd"><div class="modal-hd-icon" style="background:rgba(198,40,40,0.1);border-color:rgba(198,40,40,0.2)"><i class="fa-solid fa-ban" style="color:#e11d48"></i></div><div class="modal-hd-text"><div class="modal-hd-title">إلغاء الاجتماع</div><div class="modal-hd-sub">أدخل سبب الإلغاء</div></div><button class="modal-close" onclick="closeOv('ov-cancel')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div class="modal-body"><div class="fg"><label>سبب الإلغاء <span class="req">*</span></label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span><textarea id="f-cancel-reason" placeholder="مثال: تعارض المواعيد، ظروف طارئة..." style="border-color:rgba(198,40,40,0.25)"></textarea></div></div></div><div class="modal-ft"><button class="btn-cancel" onclick="closeOv('ov-cancel')">تراجع</button><button class="btn-danger" onclick="doCancel()"><i class="fa-solid fa-ban" style="margin-left:8px"></i> تأكيد الإلغاء</button></div></div></div>
        </div>{{-- /view-meetings --}}

        {{-- ══ ORDERS SECTION ══ --}}
        <div class="view" id="view-orders">
          <main>
              <div class="page-head">
              <div><h1 id="orders-page-title"><i class="fa-solid fa-clipboard-list" style="margin-left:8px;color:var(--teal)"></i> <span id="orders-page-title-text">صفحة الطلبات</span></h1><p id="orders-page-sub">إدارة طلبات إنشاء الحسابات وعرض تصنيفات الجمعيات المسجلة</p></div>
            </div>
            {{-- Stats for registration requests (default) --}}
            <div class="stats-grid" id="stats-grid-requests">
              <div class="stat-card"><div class="stat-label">إجمالي الطلبات</div><div class="stat-value" id="os-total">—</div><div class="stat-sub text-blue" id="os-month">—</div></div>
              <div class="stat-card"><div class="stat-label">قيد المراجعة</div><div class="stat-value text-yellow" id="os-pending">—</div><div class="stat-sub text-yellow">تنتظر المعالجة</div></div>
              <div class="stat-card"><div class="stat-label">تمت الموافقة</div><div class="stat-value text-green" id="os-approved">—</div><div class="stat-sub text-green" id="os-approval-rate">—</div></div>
              <div class="stat-card"><div class="stat-label">مرفوضة</div><div class="stat-value text-red" id="os-rejected">—</div><div class="stat-sub text-red" id="os-rejection-rate">—</div></div>
            </div>
            {{-- Stats for service requests (shown when services tab active) --}}
            <div class="stats-grid" id="stats-grid-services" style="display:none">
              <div class="stat-card"><div class="stat-label">إجمالي الطلبات</div><div class="stat-value" id="ss-total">—</div><div class="stat-sub text-blue" id="ss-month">—</div></div>
              <div class="stat-card"><div class="stat-label">جديدة</div><div class="stat-value text-yellow" id="ss-pending">—</div><div class="stat-sub text-yellow">تنتظر المعالجة</div></div>
              <div class="stat-card"><div class="stat-label">مقبولة</div><div class="stat-value text-green" id="ss-approved">—</div><div class="stat-sub text-green" id="ss-approval-rate">—</div></div>
              <div class="stat-card"><div class="stat-label">مرفوضة</div><div class="stat-value text-red" id="ss-rejected">—</div><div class="stat-sub text-red" id="ss-rejection-rate">—</div></div>
            </div>
            {{-- Stats for associations (shown when my-associations tab active) --}}
            <div class="stats-grid" id="stats-grid-associations" style="display:none">
              <div class="stat-card"><div class="stat-label">إجمالي الجمعيات</div><div class="stat-value" id="assoc-stat-total">—</div><div class="stat-sub text-blue" id="assoc-stat-month"><i class="fa-solid fa-arrow-up"></i> — هذا الشهر</div></div>
              <div class="stat-card"><div class="stat-label">التصنيفات النشطة</div><div class="stat-value text-blue" id="assoc-stat-cats">—</div><div class="stat-sub">تصنيف مختلف مضاف</div></div>
              <div class="stat-card"><div class="stat-label">متوسط التسجيل</div><div class="stat-value text-green" id="assoc-stat-avg">—</div><div class="stat-sub text-green">شهرياً في النظام</div></div>
            </div>
            {{-- Stats for opportunity requests --}}
            <div class="stats-grid" id="stats-grid-opp-requests" style="display:none">
              <div class="stat-card"><div class="stat-label">إجمالي الطلبات</div><div class="stat-value" id="opp-stat-total">0</div><div class="stat-sub" id="opp-stat-month">لا طلبات هذا الشهر</div></div>
              <div class="stat-card"><div class="stat-label">جديدة</div><div class="stat-value text-blue" id="opp-stat-pending">0</div><div class="stat-sub">تنتظر المعالجة</div></div>
              <div class="stat-card"><div class="stat-label">مقبولة</div><div class="stat-value text-green" id="opp-stat-approved">0</div><div class="stat-sub text-green" id="opp-stat-rate">—</div></div>
              <div class="stat-card"><div class="stat-label">مرفوضة</div><div class="stat-value text-red" id="opp-stat-rejected">0</div><div class="stat-sub text-red" id="opp-stat-rej-rate">—</div></div>
            </div>
            {{-- Stats for project requests --}}
            <div class="stats-grid" id="stats-grid-proj-requests" style="display:none">
              <div class="stat-card"><div class="stat-label">إجمالي الطلبات</div><div class="stat-value" id="proj-stat-total">0</div><div class="stat-sub" id="proj-stat-month">لا طلبات هذا الشهر</div></div>
              <div class="stat-card"><div class="stat-label">جديدة</div><div class="stat-value text-blue" id="proj-stat-pending">0</div><div class="stat-sub">تنتظر المعالجة</div></div>
              <div class="stat-card"><div class="stat-label">مقبولة</div><div class="stat-value text-green" id="proj-stat-approved">0</div><div class="stat-sub text-green" id="proj-stat-rate">—</div></div>
              <div class="stat-card"><div class="stat-label">مرفوضة</div><div class="stat-value text-red" id="proj-stat-rejected">0</div><div class="stat-sub text-red" id="proj-stat-rej-rate">—</div></div>
            </div>
            <div class="section-tabs">
              <button class="tab-btn active" onclick="switchTab('my-associations', this)">الجمعيات</button>
              <button class="tab-btn" onclick="switchTab('requests', this)">طلبات إنشاء الحساب</button>
              <button class="tab-btn" onclick="switchTab('services', this)">
                طلبات الخدمات
                <span id="sr-pending-count" style="display:inline-flex;align-items:center;justify-content:center;background:rgba(59,130,246,0.15);color:#3b82f6;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:800;margin-right:6px"></span>
              </button>
              <button class="tab-btn" onclick="switchTab('opp-requests', this)">
                طلبات فرص التطوع
                <span id="opp-req-count" style="display:inline-flex;align-items:center;justify-content:center;background:rgba(245,158,11,0.15);color:#d97706;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:800;margin-right:6px"></span>
              </button>
              <button class="tab-btn" onclick="switchTab('proj-requests', this)">
                طلبات المشاريع
                <span id="proj-req-count" style="display:inline-flex;align-items:center;justify-content:center;background:rgba(123,78,166,0.15);color:#7b4ea6;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:800;margin-right:6px"></span>
              </button>
            </div>
            
            {{-- ══ MY ASSOCIATIONS TAB (Now Categories + Associations) ══ --}}
            <div class="tab-content active" id="tab-my-associations">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
                    <div style="text-align:right">
                        <h2 style="color:var(--ink);font-weight:800;font-size:1.3rem;margin:0 0 4px 0">
                            <i class="fa-solid fa-layer-group" style="margin-left:8px;color:var(--teal)"></i>
                            تصنيفات الجمعيات
                        </h2>
                        <p style="color:var(--muted);font-size:0.9rem;margin:0">إدارة التصنيفات والجمعيات المرتبطة بها</p>
                    </div>
                    <button class="btn-primary" onclick="openAddCategoryModal()" style="padding:10px 16px;font-size:0.9rem;border-radius:10px;display:flex;align-items:center;gap:6px">
                        <i class="fa-solid fa-plus"></i> إضافة تصنيف
                    </button>
                </div>

                <div class="categories-grid" id="categoriesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:16px;margin-bottom:28px"></div>

                <div class="section-head" style="margin-top:8px;display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                    <div id="assoc-section-head">
                        <div style="font-weight:800;font-size:1.05rem;color:var(--text)"><i class="fa-solid fa-building" style="color:var(--teal);margin-left:8px"></i>الجمعيات المسجلة</div>
                    </div>
                    <div class="filter-group">
                        <select id="catFilter" onchange="filterAssoc()" style="padding:7px 12px;border-radius:10px;border:1.5px solid #e2e8f0;font-family:inherit;font-size:.85rem">
                            <option value="">كل التصنيفات</option>
                        </select>
                    </div>
                </div>
                <div class="assoc-list" id="assocList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:12px;margin-top:12px"></div>
            </div>

            {{-- ══ ACCOUNT CREATION REQUESTS TAB ══ --}}
            <div class="tab-content" id="tab-requests">
              <div class="table-toolbar">
                <div class="search-box"><span><i class="fa-solid fa-magnifying-glass"></i></span><input type="text" placeholder="بحث بالاسم أو البريد الإلكتروني..." onkeyup="filterTable(this.value)" /></div>
                <div class="filter-group">
                  <select onchange="filterByStatus(this.value)"><option value="">جميع الحالات</option><option value="pending">قيد المراجعة</option><option value="approved">موافق عليها</option><option value="review">مراجعة إضافية</option><option value="rejected">مرفوضة</option></select>
                  <select><option>آخر 30 يوم</option><option>آخر 7 أيام</option><option>هذا الشهر</option><option>كل الوقت</option></select>
                </div>
              </div>
              <div class="table-wrap"><table id="requestsTable"><thead><tr><th>#</th><th>مقدم الطلب</th><th>نوع الحساب</th><th>الجمعية</th><th>تاريخ الطلب</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody id="requestsTbody"></tbody></table></div>
            </div>
            <div class="tab-content" id="tab-services">
              {{-- Header --}}
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
                <div style="text-align:right">
                  <h2 style="color:var(--ink);font-weight:800;font-size:1.3rem;margin:0 0 4px 0">
                    <i class="fa-solid fa-clipboard-list" style="margin-left:8px;color:var(--teal)"></i>
                    طلبات الخدمات
                  </h2>
                  <p style="color:var(--muted);font-size:0.9rem;margin:0">راجع وأدر طلبات خدمات الجمعيات</p>
                </div>
                <button class="btn btn-ghost" onclick="loadServiceRequests()" style="display:flex;align-items:center;gap:6px;font-size:0.85rem">
                  <i class="fa-solid fa-arrows-rotate"></i> تحديث
                </button>
              </div>

              {{-- Search & Filter Bar --}}
              <div style="background:#fff;border-radius:14px;padding:12px 16px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border);box-shadow:0 1px 4px rgba(0,0,0,0.03)">
                <div id="sr-filter-tabs" style="display:flex;gap:6px;flex-wrap:wrap">
                  <button class="sr-tab-btn active" data-status="all"     onclick="filterSrByStatus('all')"        style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#3b82f6;color:#fff;font-size:0.82rem">الكل</button>
                  <button class="sr-tab-btn"        data-status="pending"    onclick="filterSrByStatus('pending')"    style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">جديد</button>
                  <button class="sr-tab-btn"        data-status="processing" onclick="filterSrByStatus('processing')" style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">قيد المعالجة</button>
                  <button class="sr-tab-btn"        data-status="approved"   onclick="filterSrByStatus('approved')"   style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مقبول</button>
                  <button class="sr-tab-btn"        data-status="rejected"   onclick="filterSrByStatus('rejected')"   style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مرفوض</button>
                </div>
                <div class="search-box" style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:6px 12px;width:240px">
                  <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8"></i>
                  <input type="text" id="sr-search-input" placeholder="بحث عن جمعية أو طلب..." oninput="searchSr(this.value)"
                    style="border:none;background:transparent;width:100%;font-family:inherit;outline:none;font-size:0.85rem;direction:rtl">
                </div>
              </div>

              {{-- Table --}}
              <div style="background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:0 2px 8px rgba(0,0,0,0.03);overflow:hidden">
                <table style="width:100%;border-collapse:collapse;text-align:center" dir="rtl">
                  <thead>
                    <tr style="border-bottom:1px solid var(--border);background:#fafbfc">
                      <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem;text-align:right">الجمعية</th>
                      <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">عنوان الطلب</th>
                      <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">نوع الخدمة</th>
                      <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">الحالة</th>
                      <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">إجراء</th>
                    </tr>
                  </thead>
                  <tbody id="sr-tbody"></tbody>
                </table>
              </div>
            </div>{{-- /tab-services --}}

            {{-- ══ TAB: طلبات فرص التطوع ══ --}}
            <div class="tab-content" id="tab-opp-requests">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <div>
                  <div style="font-size:1.1rem;font-weight:800;color:#0f172a">طلبات فرص التطوع</div>
                  <div style="font-size:0.82rem;color:#64748b;margin-top:4px">راجع وأدر طلبات التطوع المقدمة من المستخدمين</div>
                </div>
                <button class="btn-primary" style="padding:9px 18px;font-size:0.83rem" onclick="loadOppRequests()">
                  <i class="fa-solid fa-rotate-right" style="margin-left:6px"></i>تحديث
                </button>
              </div>



              <div style="background:#fff;border-radius:14px;padding:12px 16px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
                <div style="display:flex;gap:6px;flex-wrap:wrap" id="opp-req-filter-tabs">
                  <button class="sr-tab-btn active" onclick="filterOppReqs('all')"     style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#d97706;color:#fff;font-size:0.82rem">الكل</button>
                  <button class="sr-tab-btn"         onclick="filterOppReqs('pending')"  style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">جديد</button>
                  <button class="sr-tab-btn"         onclick="filterOppReqs('approved')" style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مقبول</button>
                  <button class="sr-tab-btn"         onclick="filterOppReqs('rejected')" style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مرفوض</button>
                </div>
                <div class="search-box" style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:6px 12px;width:240px">
                  <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.82rem"></i>
                  <input type="text" id="opp-req-search" placeholder="ابحث عن طلب أو مستخدم..." oninput="filterOppReqs()" style="border:none;outline:none;background:transparent;font-family:inherit;font-size:0.83rem;width:100%">
                </div>
              </div>

              <div style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid var(--border)">
                <table style="width:100%;border-collapse:collapse">
                  <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid var(--border)">
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">المستخدم</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">عنوان الفرصة</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">التصنيف</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">تاريخ الطلب</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">الحالة</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">إجراء</th>
                    </tr>
                  </thead>
                  <tbody id="opp-req-tbody"></tbody>
                </table>
              </div>
            </div>{{-- /tab-opp-requests --}}

            {{-- ══ TAB: طلبات المشاريع المشتركة ══ --}}
            <div class="tab-content" id="tab-proj-requests">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <div>
                  <div style="font-size:1.1rem;font-weight:800;color:#0f172a">طلبات المشاريع المشتركة</div>
                  <div style="font-size:0.82rem;color:#64748b;margin-top:4px">راجع وأدر طلبات الانضمام للمشاريع المشتركة</div>
                </div>
                <button class="btn-primary" style="padding:9px 18px;font-size:0.83rem" onclick="loadProjRequests()">
                  <i class="fa-solid fa-rotate-right" style="margin-left:6px"></i>تحديث
                </button>
              </div>



              <div style="background:#fff;border-radius:14px;padding:12px 16px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
                <div style="display:flex;gap:6px;flex-wrap:wrap" id="proj-req-filter-tabs">
                  <button class="sr-tab-btn active" onclick="filterProjReqs('all')"     style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#7b4ea6;color:#fff;font-size:0.82rem">الكل</button>
                  <button class="sr-tab-btn"         onclick="filterProjReqs('pending')"  style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">جديد</button>
                  <button class="sr-tab-btn"         onclick="filterProjReqs('approved')" style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مقبول</button>
                  <button class="sr-tab-btn"         onclick="filterProjReqs('rejected')" style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مرفوض</button>
                </div>
                <div class="search-box" style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:6px 12px;width:240px">
                  <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.82rem"></i>
                  <input type="text" id="proj-req-search" placeholder="ابحث عن مشروع أو مستخدم..." oninput="filterProjReqs()" style="border:none;outline:none;background:transparent;font-family:inherit;font-size:0.83rem;width:100%">
                </div>
              </div>

              <div style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid var(--border)">
                <table style="width:100%;border-collapse:collapse">
                  <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid var(--border)">
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">المستخدم</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">عنوان المشروع</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">تاريخ الطلب</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">الحالة</th>
                      <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">إجراء</th>
                    </tr>
                  </thead>
                  <tbody id="proj-req-tbody"></tbody>
                </table>
              </div>
            </div>{{-- /tab-proj-requests --}}

          </main>

          {{-- ══ SR DETAIL MODAL (used by orders.js) ══ --}}
          <div class="modal-overlay" id="sr-modal">
            <div class="modal" style="max-width:520px;border-radius:24px;overflow:hidden" onclick="event.stopPropagation()">
              {{-- Header --}}
              <div style="background:linear-gradient(135deg,var(--teal),var(--blue));padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
                <button style="background:rgba(255,255,255,0.2);border:none;width:32px;height:32px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer" onclick="closeSrModal()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <h3 style="color:#fff;font-weight:800;font-size:1.1rem;margin:0;display:flex;align-items:center;gap:8px">
                  <i class="fa-solid fa-clipboard-list"></i> تفاصيل الطلب
                </h3>
              </div>

              {{-- Body --}}
              <div style="padding:20px;max-height:65vh;overflow-y:auto;background:#f8fafc">
                {{-- Applicant info --}}
                <div style="background:#fff;border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:14px;margin-bottom:14px;border:1px solid var(--border)">
                  <div id="srm-av" style="width:44px;height:44px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0"></div>
                  <div style="text-align:right;flex:1">
                    <div id="srm-name" style="font-weight:800;font-size:1rem;color:var(--ink)"></div>
                    <div id="srm-email" style="color:var(--muted);font-size:0.82rem"></div>
                  </div>
                  <span id="srm-status" class="badge"></span>
                </div>

                {{-- Request details --}}
                <div style="background:#fff;border-radius:14px;padding:16px;margin-bottom:14px;border:1px solid var(--border);text-align:right">
                  <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">نوع الخدمة</div>
                  <div id="srm-type" style="font-weight:800;color:var(--purple);font-size:0.95rem;margin-bottom:12px"></div>

                  <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">عنوان الطلب</div>
                  <div id="srm-title" style="font-weight:700;color:var(--ink);font-size:0.95rem;margin-bottom:12px"></div>

                  <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">التفاصيل</div>
                  <div id="srm-details" style="color:var(--ink);font-size:0.9rem;line-height:1.6;white-space:pre-wrap;margin-bottom:12px"></div>

                  <div style="display:flex;gap:16px;flex-wrap:wrap">
                    <div><div style="font-size:0.78rem;color:var(--muted)">الميزانية</div><div id="srm-budget" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div></div>
                    <div><div style="font-size:0.78rem;color:var(--muted)">التاريخ المفضل</div><div id="srm-date" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div></div>
                    <div><div style="font-size:0.78rem;color:var(--muted)">تاريخ الطلب</div><div id="srm-created" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div></div>
                  </div>
                </div>

                {{-- Status selector --}}
                <div style="background:#fff;border-radius:14px;padding:16px;border:1px solid var(--border)">
                  <div style="font-weight:800;color:var(--ink);margin-bottom:10px;text-align:right">تغيير الحالة</div>
                  <div style="display:flex;gap:8px" dir="rtl">
                    <button class="sr-status-btn" data-status="processing" onclick="selectSrStatus('processing')"
                      style="flex:1;background:#fff;border:1.5px solid var(--border);border-radius:10px;padding:9px 6px;font-weight:700;color:var(--muted);cursor:pointer;font-family:inherit;font-size:0.82rem;transition:all 0.2s">
                      قيد المعالجة
                    </button>
                    <button class="sr-status-btn" data-status="approved" onclick="selectSrStatus('approved')"
                      style="flex:1;background:#fff;border:1.5px solid var(--border);border-radius:10px;padding:9px 6px;font-weight:700;color:var(--muted);cursor:pointer;font-family:inherit;font-size:0.82rem;transition:all 0.2s">
                      موافقة
                    </button>
                    <button class="sr-status-btn" data-status="rejected" onclick="selectSrStatus('rejected')"
                      style="flex:1;background:#fff;border:1.5px solid var(--border);border-radius:10px;padding:9px 6px;font-weight:700;color:var(--muted);cursor:pointer;font-family:inherit;font-size:0.82rem;transition:all 0.2s">
                      رفض
                    </button>
                  </div>
                </div>
              </div>

              {{-- Footer --}}
              <div style="padding:16px 20px;border-top:1px solid var(--border);background:#fff;display:flex;gap:10px">
                <button onclick="closeSrModal()" style="flex:1;background:#f1f5f9;color:#64748b;border:none;border-radius:10px;padding:12px;font-family:inherit;font-weight:700;cursor:pointer;font-size:0.9rem">إغلاق</button>
                <button id="sr-save-btn" onclick="saveSrStatus()"
                  style="flex:2;background:linear-gradient(135deg,var(--teal),var(--blue));color:#fff;border:none;border-radius:10px;padding:12px;font-family:inherit;font-weight:800;cursor:pointer;font-size:0.9rem;display:flex;align-items:center;justify-content:center;gap:8px">
                  <i class="fa-solid fa-floppy-disk"></i> حفظ الحالة
                </button>
              </div>
            </div>
          </div>{{-- /sr-modal --}}

          <style>
            .sr-status-active { border-color: var(--blue) !important; color: var(--blue) !important; background: rgba(29,111,164,0.06) !important; }
            #sr-filter-tabs .sr-tab-btn.active { background: #3b82f6 !important; color: #fff !important; }
            #sr-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center; }
            #sr-modal.open { display:flex; }
          </style>

          <!-- ORDERS MODAL -->
          <div class="modal-overlay" id="modal">
            <div class="modal">
              <div class="modal-head">
                <h3>تفاصيل طلب التسجيل</h3>
                <button class="btn btn-ghost" onclick="closeModal()" style="padding:.3rem .7rem;">✕</button>
              </div>
              <div class="modal-body" id="modalBody"></div>
              <div class="modal-footer" id="modal-footer-btns">
                <button class="btn btn-ghost" onclick="closeModal()">إغلاق</button>
              </div>
            </div>
          </div>

          <!-- ── Action Modal (reject / review with reason) ── -->
          <div class="modal-overlay" id="action-modal">
            <div class="modal action-modal-card">
              <div class="modal-head action-modal-head">
                <button class="action-modal-close" type="button" onclick="closeActionModal()" aria-label="إغلاق">✕</button>
                <div class="action-modal-head-main">
                  <span id="action-modal-title" class="action-modal-title">طلب تعديل البيانات</span>
                  <span id="action-modal-icon" class="action-modal-icon"></span>
                </div>
              </div>
              <div class="action-modal-subwrap">
                <div id="action-modal-sub" class="action-modal-sub">جمعية: الاتحاد</div>
              </div>
              <div class="modal-body action-modal-body">
                <div class="action-modal-field">
                  <label id="action-notes-label" class="action-modal-label">التعديلات المطلوبة *</label>
                  <textarea id="action-notes" rows="4" class="action-modal-textarea"
                    placeholder="أدخل التعديلات المطلوبة..."></textarea>
                </div>
              </div>
              <div class="modal-footer action-modal-footer">
                <button class="btn btn-ghost" type="button" onclick="closeActionModal()">إلغاء</button>
                <button id="action-confirm-btn" class="btn btn-review" type="button" onclick="confirmAction()">إرسال طلب التعديل</button>
              </div>
            </div>
          </div>

          <!-- ── Add Category Modal ── -->
          <div class="modal-overlay" id="add-cat-modal">
            <div class="modal add-cat-modal" style="max-width:560px;overflow:hidden">
              <div class="add-cat-head">
                <button class="add-cat-close" onclick="closeAddCategoryModal()" type="button">✕</button>
                <div class="add-cat-title-wrap">
                  <h3>إضافة تصنيف جديد</h3>
                  <p>أنشئ تصنيف جمعية بشكل متناسق مع تصميم المنصة</p>
                </div>
              </div>
              <div class="modal-body add-cat-body">
                <div class="add-cat-field">
                  <label>اسم التصنيف</label>
                  <input id="cat-name-input" type="text" placeholder="مثال: اجتماعية تنمية بيئية" />
                </div>

                <div class="add-cat-field">
                  <label>الأيقونة (Emoji)</label>
                  <div class="emoji-grid" id="cat-emoji-grid"></div>
                </div>

                <div class="add-cat-field">
                  <label>لون التصنيف</label>
                  <div class="color-row">
                    <input id="cat-color-input" type="color" value="#2ab8d0" />
                    <span id="cat-color-label">#2AB8D0</span>
                  </div>
                </div>
              </div>
              <div class="modal-footer add-cat-footer">
                <button class="btn btn-ghost" onclick="closeAddCategoryModal()" type="button">إلغاء</button>
                <button class="btn btn-primary" onclick="saveNewCategory()" type="button"><i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ التصنيف</button>
              </div>
            </div>
          </div>

          <!-- ── Approve Confirm Modal (replace browser confirm) ── -->
          <div class="modal-overlay" id="approve-modal">
            <div class="approve-card" role="dialog" aria-modal="true">
              <button class="approve-x" onclick="closeApproveModal()" type="button" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button>
              <div class="approve-check"><i class="fa-solid fa-check"></i></div>
              <div class="approve-ttl">تأكيد قبول الطلب</div>
              <div class="approve-sub2" id="approve-sub">هل تريد قبول هذا الطلب؟</div>
              <div class="approve-hint">سيتم إشعار الجمعية بعد القبول.</div>
              <div class="approve-actions">
                <button class="btn-pill-cancel" onclick="closeApproveModal()" type="button">إلغاء</button>
                <button class="btn-pill-approve" id="approve-confirm-btn" onclick="confirmApprove()" type="button"><i class="fa-solid fa-check" style="margin-left:8px"></i> قبول الطلب</button>
              </div>
            </div>
          </div>
        </div>{{-- /view-orders --}}

        {{-- ══ PROJECTS SECTION ══ --}}
        <div class="view" id="view-projects">
          <main class="main-content">
            <div class="ph">
              <div><h1>المشاريع المشتركة</h1><p>المشاريع تُوجَّه تلقائياً للجمعيات بناءً على تطابق التصنيف</p></div>
              <button class="btn-primary" id="openNew" style="margin-right:auto; padding: 10px 24px;">
                <div class="btn-icon-wrap" style="background:rgba(255,255,255,0.22);color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-weight:bold;margin-left:8px">+</div>
                مشروع جديد
              </button>
            </div>
            <div class="stats" id="statsRow"></div>
            <div class="filter-row">
              <div class="dd-wrap" id="ddWrap"><button class="dd-btn" id="ddBtn" type="button"><span class="dd-left"><span class="emoji"><i class="fa-solid fa-building"></i></span><span id="ddLabel">كل التصنيفات</span></span><i class="fa-solid fa-chevron-down chevron"></i></button><div class="dd-menu" id="ddMenu"></div></div>
              <div class="search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input type="text" class="sinput" id="searchQ" placeholder="ابحث باسم المشروع..."></div>
              <span class="res-badge" id="resBadge"><span id="resNum">0</span> مشروع</span>
            </div>
            <div class="tabs">
              <button class="tab on" data-t="tab-active"><i class="fa-solid fa-rocket"></i>الحالية<span class="n" id="n-active">0</span></button>
              <button class="tab" data-t="tab-done"><i class="fa-solid fa-circle-check"></i>المنتهية<span class="n" id="n-done">0</span></button>
              <button class="tab" data-t="tab-canceled"><i class="fa-solid fa-ban"></i>الملغاة<span class="n" id="n-canceled">0</span></button>
            </div>
            <div id="tab-active" class="pane on grid"></div>
            <div id="tab-done" class="pane grid"></div>
            <div id="tab-canceled" class="pane grid"></div>
          </main>
          <!-- PROJECTS MODALS -->
<<<<<<< Updated upstream
          <div class="ov" id="ovNew"><div class="mbox"><div class="mhd"><h2><i class="fa-solid fa-plus" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>إنشاء مشروع مشترك</h2><button class="mcl" id="clNew"><i class="fa-solid fa-xmark"></i></button></div><form id="fNew"><div class="fg"><label>اسم المشروع</label><input id="nN" placeholder="أدخل اسم المشروع..." required></div><div class="fg"><label>تصنيف المشروع</label><select id="nD" required><option value="">— اختر التصنيف —</option></select></div><div class="fg"><label>هدف المشروع</label><textarea id="nG" placeholder="اشرح هدف المشروع بوضوح..." required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="nS" required></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="nE" required></div></div><div class="fg"><label>حالة المشروع</label><select id="nSt"><option value="planning">قيد الإعداد والتخطيط</option><option value="active">بدء التنفيذ الفعلي</option><option value="idea">فكرة وعصف ذهني</option></select></div><button type="submit" class="bsub"><i class="fa-solid fa-paper-plane"></i> حفظ المشروع</button></form></div></div>
=======
          <div class="ov" id="ovNew"><div class="mbox"><div class="mhd"><h2><i class="fa-solid fa-plus" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>إنشاء مشروع مشترك</h2><button class="mcl" id="clNew"><i class="fa-solid fa-xmark"></i></button></div><form id="fNew"><div class="fg"><label>اسم المشروع</label><input id="nN" placeholder="أدخل اسم المشروع..." required></div><div class="fg"><label>تصنيف المشروع <span style="color:#e11d48">*</span></label><div id="nD-picker" style="display:flex;flex-wrap:wrap;gap:8px;padding:10px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;min-height:48px;"></div><input type="hidden" id="nD" required /></div><div class="fg"><label>هدف المشروع</label><textarea id="nG" placeholder="اشرح هدف المشروع بوضوح..." required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="nS" required></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="nE" required></div></div><div class="fg"><label>حالة المشروع</label><select id="nSt"><option value="قيد الإعداد">قيد الإعداد والتخطيط</option><option value="مستمر">بدء التنفيذ الفعلي</option><option value="فكرة">فكرة وعصف ذهني</option></select></div><button type="submit" class="bsub"><i class="fa-solid fa-paper-plane"></i> حفظ المشروع</button></form></div></div>

>>>>>>> Stashed changes
          <div class="ov" id="ovEdit"><div class="mbox"><div class="mhd"><h2><i class="fa-regular fa-pen-to-square" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>تعديل وإضافة تقدم</h2><button class="mcl" id="clEdit"><i class="fa-solid fa-xmark"></i></button></div><form id="fEdit"><input type="hidden" id="eId"><div class="fg"><label>اسم المشروع</label><input id="eN" required></div><div class="fg"><label>الهدف / الوصف</label><textarea id="eG" rows="2" required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="eS"></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="eE"></div></div><div class="fg"><label>نسبة الإنجاز (%)</label><input type="number" id="eP" min="0" max="100"></div><div class="fg"><label>إضافة تقدم جديد للسجل</label><textarea id="eU" placeholder="اكتب آخر ما تم إنجازه..."></textarea></div><button type="submit" class="bsub">حفظ التحديث</button></form></div></div>
          <div class="ov" id="ovConfirm"><div class="mbox cbox"><div class="cico"><i class="fa-solid fa-triangle-exclamation"></i></div><h3 id="cTtl"></h3><p id="cMsg"></p><div class="cbtns"><button class="by" id="cY">تأكيد</button><button class="bn" id="cN">تراجع</button></div></div></div>
        </div>{{-- /view-projects --}}


      </div><!-- /content -->
    </div><!-- /main -->
  </div><!-- /layout -->

  <!-- ══ ADD / EDIT OPP MODAL ══ -->
  <div class="overlay" id="ov-opp" onclick="bgClose(event,'ov-opp')">
    <div class="modal opp-modal-redesign" onclick="event.stopPropagation()">

      {{-- ── Gradient Header ── --}}
      <div class="opp-modal-hd">
        <div class="opp-modal-hd-inner">
          <div class="opp-modal-hd-icon" id="opp-m-icon"><i class="fa-solid fa-star"></i></div>
          <div>
            <div class="opp-modal-hd-title" id="opp-m-title">إضافة فرصة تطوع</div>
            <div class="opp-modal-hd-sub" id="opp-m-sub">أضف تفاصيل الفرصة أدناه</div>
          </div>
        </div>
        <button class="opp-modal-close" onclick="closeOv('ov-opp')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      {{-- ── Body ── --}}
      <div class="m-body">

        {{-- Category (badge or select) --}}
        <div id="opp-cat-badge-wrap" style="display:none;margin-bottom:16px">
          <div class="selected-cat-badge" id="sel-cat-badge"></div>
        </div>
        <div class="fg" id="fg-opp-cat">
          <label>تصنيف الفرصة <span class="req-span">*</span></label>
          <div id="f-opp-cat-picker"></div>
          <input type="hidden" id="f-opp-cat" />
        </div>

        {{-- Title --}}
        <div class="fg">
          <label>عنوان الفرصة <span class="req-span">*</span></label>
          <div class="fld">
            <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span>
            <input type="text" id="f-opp-title" placeholder="مثال: تعليم القرآن للأطفال">
          </div>
        </div>

        {{-- Description --}}
        <div class="fg">
          <label>وصف الفرصة <span class="req-span">*</span></label>
          <div class="fld">
            <span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
            <textarea id="f-opp-desc" placeholder="اشرح طبيعة عمل المتطوع ومتطلبات الانضمام..."></textarea>
          </div>
        </div>

        {{-- Link --}}
        <div class="fg">
          <label>رابط الفرصة (اختياري)</label>
          <div class="fld">
            <span class="fi"><i class="fa-solid fa-link" style="font-size:15px;color:currentColor"></i></span>
            <input type="url" id="f-opp-link" placeholder="https://example.com/opportunity" style="text-align:left;direction:ltr;">
          </div>
        </div>

        {{-- Org + City --}}
        <div class="row2">
          <div class="fg">
            <label>الجهة المستضيفة <span class="req-span">*</span></label>
            <div class="fld">
              <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M3 21h18M9 8h1m5 0h1M9 12h1m5 0h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg></span>
              <input type="text" id="f-opp-org" placeholder="اسم الجمعية المنظّمة">
            </div>
          </div>
          <div class="fg">
            <label>المدينة</label>
            <div class="fld">
              <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
              <input type="text" id="f-opp-city" placeholder="الرياض، جدة...">
            </div>
          </div>
        </div>

        {{-- ── Bottom chip row ── --}}
        <div class="opp-chip-row">
          {{-- Seats --}}
          <div class="opp-chip">
            <div class="opp-chip-icon" style="background:rgba(42,184,208,0.12);color:#0e7490">
              <i class="fa-solid fa-users"></i>
            </div>
            <div class="opp-chip-field">
              <label class="opp-chip-lbl">عدد المتطوعين</label>
              <input type="number" id="f-opp-seats" placeholder="0" min="0" class="opp-chip-inp">
            </div>
          </div>
          {{-- Deadline --}}
          <div class="opp-chip">
            <div class="opp-chip-icon" style="background:rgba(245,158,11,0.12);color:#d97706">
              <i class="fa-regular fa-calendar"></i>
            </div>
            <div class="opp-chip-field">
              <label class="opp-chip-lbl">الموعد النهائي</label>
              <input type="date" id="f-opp-deadline" class="opp-chip-inp">
            </div>
          </div>
          {{-- Type --}}
          <div class="opp-chip">
            <div class="opp-chip-icon" style="background:rgba(99,102,241,0.12);color:#4f46e5">
              <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="opp-chip-field">
              <label class="opp-chip-lbl">نوع التطوع</label>
              <select id="f-opp-type" class="opp-chip-inp">
                <option value="onsite">حضوري</option>
                <option value="remote">عن بعد</option>
                <option value="both">حضوري وعن بعد</option>
              </select>
            </div>
          </div>
          {{-- Status --}}
          <div class="opp-chip">
            <div class="opp-chip-icon" style="background:rgba(16,185,129,0.12);color:#059669">
              <i class="fa-solid fa-circle-dot"></i>
            </div>
            <div class="opp-chip-field">
              <label class="opp-chip-lbl">الحالة</label>
              <select id="f-opp-status" class="opp-chip-inp">
                <option value="open">مفتوحة</option>
                <option value="closed">مغلقة</option>
              </select>
            </div>
          </div>
        </div>

      </div>{{-- /m-body --}}

      <div class="m-ft">
        <button class="btn-cancel" onclick="closeOv('ov-opp')">إلغاء</button>
        <button class="btn-save" onclick="saveOpp()"><span id="opp-save-lbl"><i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ الفرصة</span></button>
      </div>
    </div>
  </div>

  <!-- ══ APPLY MODAL ══ -->
  <div class="overlay" id="ov-apply" onclick="bgClose(event,'ov-apply')">
    <div class="modal" onclick="event.stopPropagation()">
      <div class="m-hd">
        <div class="m-hd-icon"><i class="fa-regular fa-pen-to-square"></i></div>
        <div class="m-hd-text">
          <div class="m-hd-title">التقديم على الفرصة</div>
          <div class="m-hd-sub">سيُراجَع طلبك من قِبَل مبادرون ويُخطَر بالنتيجة</div>
        </div>
        <button class="m-close" onclick="closeOv('ov-apply')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" width="15" height="15">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg></button>
      </div>
      <div class="m-body">
        <div class="apply-opp-preview">
          <div class="aop-title" id="apply-opp-title"></div>
          <div class="aop-meta">
            <span id="apply-opp-org"></span>
            <span id="apply-opp-cat"></span>
          </div>
        </div>

        <div class="fg">
          <label>اسم الجمعية المتقدمة <span class="req-span">*</span></label>
          <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                width="15" height="15">
                <path d="M3 21h18M9 8h1m5 0h1M9 12h1m5 0h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16" />
              </svg></span><input type="text" id="f-apply-assoc" value="جمعية التنمية الاجتماعية"></div>
        </div>

        <div class="fg">
          <label>رسالة للإدارة</label>
          <div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" width="15" height="15">
                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
              </svg></span><textarea id="f-apply-msg"
              placeholder="اشرح سبب اهتمامكم بهذه الفرصة وما يمكنكم تقديمه..."></textarea></div>
        </div>

        <div
          style="background:rgba(245,158,11,0.08);border:1.5px solid rgba(245,158,11,0.2);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:10px">
          <span style="font-size:1.1rem">⚠️</span>
          <div style="font-size:0.82rem;color:var(--ink);line-height:1.6">سيصل طلبكم إلى <strong>مبادرون</strong>
            للمراجعة والموافقة أو الرفض. ستصلكم إشعار بالقرار.</div>
        </div>
      </div>
      <div class="m-ft">
        <button class="btn-cancel" onclick="closeOv('ov-apply')">إلغاء</button>
        <button class="btn-save" onclick="submitApply()"><i class="fa-regular fa-paper-plane" style="margin-left:8px"></i> إرسال الطلب</button>
      </div>
    </div>
  </div>

  <!-- DELETE CONFIRM -->
  <div class="overlay" id="ov-del" onclick="bgClose(event,'ov-del')">
    <div class="confirm-box" onclick="event.stopPropagation()">
      <div class="ci-wrap" style="background:rgba(229,57,53,0.1)"><i class="fa-solid fa-trash-can" style="color:#e11d48"></i></div>
      <div class="ci-title">حذف الفرصة</div>
      <div class="ci-desc">هل أنت متأكد من حذف هذه الفرصة؟<br>لا يمكن التراجع عن هذا الإجراء.</div>
      <div class="ci-row">
        <button class="btn-cancel" style="flex:1" onclick="closeOv('ov-del')">إلغاء</button>
        <button class="btn-danger" onclick="doDelete()">حذف</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

  <script>
    window.AppRole = '{{ session("association") ? "association" : (Auth::check() && Auth::user()->role->name === "admin" ? "admin" : "user") }}';
    @if(session('association'))
      window.AppApplicantName = '{{ session("association.name") ?? "" }}';
      window.AppApplicantLabel = 'اسم الجمعية المتقدمة';
    @elseif(Auth::check())
      window.AppApplicantName = '{{ Auth::user()->full_name ?? Auth::user()->name ?? "" }}';
      window.AppApplicantLabel = 'اسم المتقدم';
    @else
      window.AppApplicantName = '';
      window.AppApplicantLabel = 'اسم المتقدم';
    @endif
  </script>

  <!-- ── Delete Category Confirm Modal ── -->
  <div class="modal-overlay" id="delete-cat-modal">
      <div class="modal" style="max-width:400px;text-align:center">
          <div style="padding:36px 28px 28px;display:flex;flex-direction:column;align-items:center;gap:16px">
              <div style="width:72px;height:72px;border-radius:50%;background:rgba(239,68,68,.1);display:flex;align-items:center;justify-content:center;margin-bottom:4px">
                  <i class="fa-solid fa-trash" style="font-size:1.8rem;color:#ef4444"></i>
              </div>
              <div>
                  <div style="font-size:1.15rem;font-weight:900;color:#0f172a;margin-bottom:6px">حذف التصنيف</div>
                  <div id="delete-cat-msg" style="font-size:.88rem;color:#64748b;line-height:1.6">هل أنت متأكد من حذف هذا التصنيف؟ لا يمكن التراجع عن هذا الإجراء.</div>
              </div>
              <div id="delete-cat-badge" style="background:rgba(239,68,68,.07);border:1.5px solid rgba(239,68,68,.18);border-radius:12px;padding:10px 20px;font-size:.95rem;font-weight:800;color:#dc2626;width:100%;box-sizing:border-box"></div>
              <div style="display:flex;gap:10px;width:100%;margin-top:4px">
                  <button onclick="closeDeleteCatModal()" style="flex:1;padding:11px;border-radius:12px;font-family:inherit;font-size:.9rem;font-weight:700;cursor:pointer;border:1.5px solid #e2e8f0;background:#f8fafc;color:#64748b">إلغاء</button>
                  <button onclick="confirmDeleteCategory()" id="confirm-delete-cat-btn" style="flex:1;padding:11px;border-radius:12px;font-family:inherit;font-size:.9rem;font-weight:800;cursor:pointer;border:none;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;box-shadow:0 4px 16px rgba(239,68,68,.35)">
                      <i class="fa-solid fa-trash" style="margin-left:6px"></i>تأكيد الحذف
                  </button>
              </div>
          </div>
      </div>
  </div>

  <!-- ── Add Category Modal ── -->
  <div class="modal-overlay" id="add-assoc-cat-modal">
      <div class="modal" style="max-width:440px">
          <div class="modal-head">
              <h3><i class="fa-solid fa-layer-group" style="margin-left:8px;color:var(--teal)"></i> إضافة تصنيف جديد</h3>
              <button class="btn btn-ghost" onclick="closeAddCategoryModal()" style="padding:.3rem .7rem">✕</button>
          </div>
          <div class="modal-body" style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
              <div>
                  <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اسم التصنيف <span style="color:#ef4444">*</span></label>
                  <input id="assoc-cat-name-input" type="text" placeholder="مثال: تعليمية" style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:.9rem;outline:none;box-sizing:border-box">
              </div>
              <div>
                  <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اللون</label>
                  <div style="display:flex;align-items:center;gap:10px">
                      <input id="assoc-cat-color-input" type="color" value="#2ab8d0" style="width:40px;height:40px;border:none;border-radius:8px;cursor:pointer;padding:2px" onchange="document.getElementById('assoc-cat-color-label').textContent=this.value.toUpperCase()">
                      <span id="assoc-cat-color-label" style="font-size:.85rem;color:var(--text-muted);font-weight:600">#2AB8D0</span>
                  </div>
              </div>
              <div>
                  <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">الأيقونة (رمز تعبيري)</label>
                  <input id="assoc-cat-icon-add-input" type="text" placeholder="مثال: 🎓" style="width:100%;padding:.6rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:1.1rem;outline:none;box-sizing:border-box">
              </div>
              <div id="assoc-cat-emoji-grid" style="display:flex;flex-wrap:wrap;gap:8px"></div>
          </div>
          <div class="modal-footer">
              <button class="btn btn-ghost" onclick="closeAddCategoryModal()">إلغاء</button>
              <button class="btn btn-primary" onclick="saveNewCategory()"><i class="fa-solid fa-floppy-disk" style="margin-left:6px"></i> حفظ التصنيف</button>
          </div>
      </div>
  </div>

  <!-- ── Edit Category Modal ── -->
  <div class="modal-overlay" id="edit-assoc-cat-modal">
      <div class="modal" style="max-width:440px">
          <div class="modal-head" style="background:linear-gradient(135deg,#0a5565,#2ab8d0)">
              <h3 style="color:#fff"><i class="fa-solid fa-pen-to-square" style="margin-left:8px"></i> تعديل التصنيف</h3>
              <button class="btn" onclick="closeEditCategoryModal()" style="padding:.3rem .7rem;background:rgba(255,255,255,.2);color:#fff;border-color:transparent">✕</button>
          </div>
          <div class="modal-body" style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
              <div>
                  <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اسم التصنيف <span style="color:#ef4444">*</span></label>
                  <input id="edit-assoc-cat-name-input" type="text" placeholder="اسم التصنيف" style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:.9rem;outline:none;box-sizing:border-box">
              </div>
              <div>
                  <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اللون</label>
                  <div style="display:flex;align-items:center;gap:10px">
                      <input id="edit-assoc-cat-color-input" type="color" value="#2ab8d0" style="width:40px;height:40px;border:none;border-radius:8px;cursor:pointer;padding:2px" onchange="document.getElementById('edit-assoc-cat-color-label').textContent=this.value.toUpperCase()">
                      <span id="edit-assoc-cat-color-label" style="font-size:.85rem;color:var(--text-muted);font-weight:600">#2AB8D0</span>
                  </div>
              </div>
              <div>
                  <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">الأيقونة (رمز تعبيري)</label>
                  <input id="edit-assoc-cat-icon-input" type="text" placeholder="مثال: 🏫" style="width:100%;padding:.6rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:1.1rem;outline:none;box-sizing:border-box">
              </div>
              <div id="edit-assoc-cat-emoji-grid" style="display:flex;flex-wrap:wrap;gap:8px"></div>
          </div>
          <div class="modal-footer">
              <button class="btn btn-ghost" onclick="closeEditCategoryModal()">إلغاء</button>
              <button class="btn btn-primary" id="edit-assoc-cat-save-btn" onclick="saveEditCategory()"><i class="fa-solid fa-floppy-disk" style="margin-left:6px"></i> حفظ التعديلات</button>
          </div>
      </div>
  </div>

  <script src="{{ asset('js/cat-picker.js') }}"></script>
  <script src="{{ asset('js/consulting.js') }}"></script>
  <script src="{{ asset('js/menu.js') }}"></script>
  <script src="{{ asset('js/meeting.js') }}"></script>
  <script src="{{ asset('js/orders.js') }}?v={{ rand() }}"></script>
  <script src="{{ asset('js/joint-projects.js') }}"></script>
  <script src="{{ asset('js/spa-nav.js') }}"></script>
  <script>
    // Wire showAdminRequests here — AFTER spa-nav.js is loaded
    window.showAdminRequests = function() {
      if (typeof showSection === 'function') {
        showSection('orders');
        // Switch to the services tab inside orders
        setTimeout(function() {
          const btn = Array.from(document.querySelectorAll('#view-orders .tab-btn'))
            .find(b => b.textContent.includes('طلبات الخدمات'));
          if (btn) switchTab('services', btn);
        }, 50);
      }
    };

    // Also trigger opening the services tab if the page loaded directly on #service-requests
    document.addEventListener('DOMContentLoaded', function() {
      if (window.location.hash === '#service-requests') {
        if (typeof showSection === 'function') {
          showSection('orders');
          setTimeout(function() {
            const btn = Array.from(document.querySelectorAll('#view-orders .tab-btn'))
              .find(b => b.textContent.includes('طلبات الخدمات'));
            if (btn) switchTab('services', btn);
          }, 100);
        }
      }
    });
  </script>

  <style>
    /* ── Action buttons in requests table ── */
    .action-group { display:flex; align-items:center; gap:5px; flex-wrap:wrap; }

    .action-btn {
      width:30px; height:30px; border-radius:8px; border:none;
      display:inline-flex; align-items:center; justify-content:center;
      cursor:pointer; transition:all .15s; font-size:.85rem; flex-shrink:0;
    }
    .view-btn    { background:rgba(14,165,201,.1);  color:var(--teal);  }
    .view-btn:hover    { background:rgba(14,165,201,.2); transform:scale(1.1); }

    .approve-btn { background:rgba(13,148,136,.1);  color:#0d9488; }
    .approve-btn:hover { background:rgba(13,148,136,.2); transform:scale(1.1); }

    .reject-btn  { background:rgba(220,38,38,.08);  color:#dc2626; }
    .reject-btn:hover  { background:rgba(220,38,38,.16); transform:scale(1.1); }

    .review-btn  { background:rgba(217,119,6,.1);   color:#d97706; }
    .review-btn:hover  { background:rgba(217,119,6,.2);  transform:scale(1.1); }

    /* Review button in modal footer */
    .btn-review {
      background: rgba(217,119,6,.1); color:#d97706;
      border:1.5px solid rgba(217,119,6,.25); border-radius:9px;
      padding:8px 16px; font-family:'Tajawal',sans-serif;
      font-size:.85rem; font-weight:700; cursor:pointer; transition:all .15s;
    }
    .btn-review:hover { background:rgba(217,119,6,.2); }

    /* Improve modal footer layout for 4 buttons */
    #modal .modal-footer { display:flex; gap:12px; flex-wrap:wrap; justify-content:center; align-items:center; padding-top: 24px; border-top: 1px solid var(--border-color); }
    #modal .modal-footer .btn { flex-shrink:0; }

    /* New Pill Buttons for Modals */
    .btn-pill-accept { background: #0f172a; color: #fff; border:none; border-radius: 50px; padding: 10px 28px; font-weight:700; font-family:'Tajawal',sans-serif; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
    .btn-pill-accept:hover { background: #1e293b; transform:translateY(-1px); }
    
    .btn-pill-reject { background: #1f2937; color: #fff; border:none; border-radius: 50px; padding: 10px 28px; font-weight:700; font-family:'Tajawal',sans-serif; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
    .btn-pill-reject:hover { background: #374151; transform:translateY(-1px); }
    
    .btn-pill-review { background: linear-gradient(135deg, #f59e0b, #ea580c); color: #fff; border:none; border-radius: 50px; padding: 10px 28px; font-weight:700; font-family:'Tajawal',sans-serif; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
    .btn-pill-review:hover { opacity:0.9; transform:translateY(-1px); }
    
    .btn-pill-cancel { background: transparent; color: #0ea5e9; border: 1.5px solid rgba(14,165,201,0.4); border-radius: 50px; padding: 10px 28px; font-weight:700; font-family:'Tajawal',sans-serif; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
    .btn-pill-cancel:hover { background: rgba(14,165,201,0.05); }

    /* Detail rows grid */
    #modalBody .detail-row {
      display:flex; flex-direction:column; gap:3px;
      padding:8px 0; border-bottom:1px solid rgba(14,165,201,.07);
    }
    #modalBody .detail-row:last-child { border-bottom:none; }
    #modalBody .detail-label { font-size:.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.3px; }
    #modalBody .detail-value { font-size:.95rem; color:var(--text); font-weight:600; }

    /* Action modal overlay */
    #action-modal { z-index:1100; }
    #action-modal .modal { animation:slideUp .2s ease; }
    @keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
  </style>

  <!-- Notification system is provided by layouts/notif-panel -->
  <!-- ── Notification panel (fixed position, anchored via JS to bell button) ── -->
  @include('layouts.notif-panel')

</body>

</html>