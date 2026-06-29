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
  <link rel="stylesheet" href="{{ asset('css/dashboard-scoped.css') }}">
  <link rel="stylesheet" href="{{ asset('css/settings-scoped.css') }}">
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
        @include('partials.consulting.admin')

        <!-- ADMIN: Opportunities for a specific category -->
        @include('partials.consulting.admin-opps')

        <!-- ADMIN: Requests -->
        @include('partials.consulting.admin-reqs')

        <!-- ASSOCIATION VIEW: Browse by category -->
        @include('partials.consulting.assoc')

        <!-- ASSOCIATION: Opportunities for a category -->
        @include('partials.consulting.assoc-opps')

        <!-- ══════════════════════════════════
         SERVICE VIEWS
    ══════════════════════════════════ -->

        <!-- بناء وحدات -->
        @include('partials.consulting.units')

        <!-- بناء أنظمة -->
        @include('partials.consulting.systems')

        <!-- تنسيق مبادرات -->
        @include('partials.consulting.initiatives')

        <!-- تدريب تطوعي -->
        @include('partials.consulting.training')

        <!-- الاستشارات -->
        @include('partials.consulting.consulting')

        <!-- التواصل معنا -->
        @include('partials.consulting.contact')

        {{-- ══ DASHBOARD SECTION ══ --}}
        @include('partials.consulting.dashboard')


        {{-- ══ MEETINGS SECTION ══ --}}
        @include('partials.consulting.meetings')

        {{-- ══ ORDERS SECTION ══ --}}
        @include('partials.consulting.orders')

        {{-- ══ PROJECTS SECTION ══ --}}
        @include('partials.consulting.projects')

        {{-- ══ SETTINGS SECTION ══ --}}
        @include('partials.consulting.settings')



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
  <script src="{{ asset('js/meetings.js') }}?v={{ rand() }}"></script>
  <script src="{{ asset('js/orders.js') }}?v={{ rand() }}"></script>
  <script src="{{ asset('js/joint-projects.js') }}"></script>
  <script src="{{ asset('js/dashboard-spa.js') }}"></script>
  <script src="{{ asset('js/settings-spa.js') }}"></script>
  <script src="{{ asset('js/spa-nav.js') }}?v={{ rand() }}"></script>
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