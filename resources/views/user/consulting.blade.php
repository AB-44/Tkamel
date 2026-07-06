<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تكامل | لوحة المستخدم</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  {{-- ── Shell / shared ── --}}
  <link rel="stylesheet" href="{{ asset('css/consulting.css') }}">

  {{-- ── Per-section styles ── --}}
  <link rel="stylesheet" href="{{ asset('css/user-dashboard.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('css/user-meetings.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('css/user-my-requests.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('css/jp-scoped.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/services.css') }}">

  <style>
    /* ── Read-only notice banner (فرص التطوع) ── */
    .readonly-notice {
      display: flex; align-items: center; gap: 10px;
      background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25);
      border-radius: 10px; padding: 10px 16px; font-size: 0.82rem; color: #92400e;
      font-weight: 600; margin-bottom: 18px;
    }
    .role-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(245, 158, 11, 0.12); color: #b45309;
      border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 20px;
      padding: 3px 10px; font-size: 0.72rem; font-weight: 700;
    }
  </style>
  <style>
    /* ── Rich Opportunity Cards — User View ── */
    .all-opps-grid { display:flex; flex-direction:column; gap:14px; }

    .rich-opp-card { background:#fff; border:1px solid #e8edf2; border-radius:18px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.04); transition:transform 0.2s, box-shadow 0.2s; }
    .rich-opp-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.08); }

    .rich-opp-card-top { padding:18px 20px 14px; }
    .rich-opp-card-title-row { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:8px; }
    .rich-opp-card-title { font-size:1.05rem; font-weight:800; color:#0f172a; line-height:1.3; flex:1; }
    .rich-opp-card-actions { display:flex; gap:6px; flex-shrink:0; align-items:center; }
    .rich-opp-card-desc { font-size:0.86rem; color:#64748b; line-height:1.6; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .rich-opp-card-tags { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:4px; }

    .rich-opp-tag { font-size:0.75rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .rich-opp-tag-open   { background:rgba(16,185,129,0.1);  color:#059669; }
    .rich-opp-tag-closed { background:rgba(239,68,68,0.08);  color:#dc2626; }
    .rich-opp-tag-onsite { background:rgba(42,184,208,0.1);  color:#0e7490; }
    .rich-opp-tag-remote { background:rgba(99,102,241,0.1);  color:#4f46e5; }
    .rich-opp-tag-cat    { background:rgba(123,78,166,0.1);  color:#7b4ea6; }

    .rich-opp-card-bottom { display:grid; grid-template-columns:repeat(4,1fr); border-top:1px solid #f1f5f9; }
    .rich-opp-stat { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:12px 8px; border-left:1px solid #f1f5f9; gap:3px; }
    .rich-opp-stat:last-child { border-left:none; }
    .rich-opp-stat-lbl { font-size:0.72rem; color:#94a3b8; font-weight:600; }
    .rich-opp-stat-val { font-size:0.92rem; font-weight:800; color:#1e293b; }
    .rich-opp-stat-val.gold  { color:#d97706; }
    .rich-opp-stat-val.teal  { color:#0e7490; }
    .rich-opp-stat-val.green { color:#059669; }
    .rich-opp-stat-val.red   { color:#dc2626; }
    .rich-opp-stat i { font-size:1rem; margin-bottom:2px; }

    /* Apply badge */
    .rich-apply-badge { display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:20px; font-size:0.78rem; font-weight:700; white-space:nowrap; }

    /* Search toolbar */
    .toolbar .sw { display:flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:8px 14px; min-width:220px; }
    .toolbar .sw svg { flex-shrink:0; color:#94a3b8; }
    .toolbar .s-inp { border:none; outline:none; background:transparent; font-family:inherit; font-size:0.88rem; width:100%; color:#374151; }

    /* ── Modal Overlay (shared by volunteer/projects/meetings/services) ── */
    .overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,0.45); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center; padding:20px; }
    .overlay.open { display:flex; }
    .modal { background:#fff; border-radius:20px; overflow:hidden; max-width:520px; width:100%; box-shadow:0 24px 64px rgba(0,0,0,0.18); }
    .m-hd { background:linear-gradient(135deg,#0d3d49,#1a6b7c,#2ab8d0); padding:20px 24px; display:flex; align-items:center; gap:14px; }
    .m-hd-icon { width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:1.3rem; color:#fff; flex-shrink:0; }
    .m-hd-text { flex:1; }
    .m-hd-title { font-size:1.05rem; font-weight:800; color:#fff; }
    .m-hd-sub { font-size:0.8rem; color:rgba(255,255,255,0.75); margin-top:2px; }
    .m-close { background:rgba(255,255,255,0.15); border:none; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#fff; margin-right:auto; }
    .m-close:hover { background:rgba(255,255,255,0.28); }
    .m-body { padding:22px 24px; display:flex; flex-direction:column; gap:16px; }
    .m-ft { padding:0 24px 20px; display:flex; gap:10px; }
    .apply-opp-preview { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px 16px; }
    .aop-title { font-size:1rem; font-weight:800; color:#0f172a; margin-bottom:6px; }
    .aop-meta { display:flex; gap:10px; font-size:0.82rem; color:#64748b; }
    .fg { display:flex; flex-direction:column; gap:6px; }
    .fg label { font-size:0.83rem; font-weight:700; color:#374151; }
    .req-span { color:#dc2626; margin-right:2px; }
    .fld { position:relative; display:flex; align-items:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; }
    .fi { position:absolute; right:12px; color:#94a3b8; display:flex; align-items:center; pointer-events:none; }
    .fi.top { top:12px; align-items:flex-start; }
    .fld input, .fld textarea { width:100%; border:none; outline:none; background:transparent; padding:10px 36px 10px 12px; font-family:inherit; font-size:0.88rem; color:#0f172a; resize:vertical; }
    .fld textarea { min-height:90px; padding-top:10px; }
    .btn-cancel { flex:1; padding:11px; border:1px solid #e2e8f0; border-radius:12px; background:#fff; font-family:inherit; font-size:0.88rem; font-weight:700; cursor:pointer; color:#64748b; }
    .btn-cancel:hover { background:#f8fafc; }
    .btn-save { flex:2; padding:11px; border:none; border-radius:12px; background:linear-gradient(135deg,#0d3d49,#2ab8d0); font-family:inherit; font-size:0.88rem; font-weight:800; cursor:pointer; color:#fff; }
    .btn-save:hover { opacity:0.92; }

    /* ── toast (shared) ── */
    .toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(80px); background: var(--ink,#0f172a); color: white; padding: 11px 22px; border-radius: 13px; font-size: 0.86rem; font-weight: 600; box-shadow: 0 8px 30px rgba(0,0,0,0.25); z-index: 600; transition: transform 0.35s cubic-bezier(0.16,1,0.3,1); display: flex; align-items: center; gap: 8px; white-space: nowrap; pointer-events: none; }
    .toast.show { transform: translateX(-50%) translateY(0); }
  </style>
  <style>#nb-reqs:empty{display:none!important}</style>
</head>

<body>
  <div class="layout">

    <!-- ══ SIDEBAR ══ -->
    @include('layouts.sidebar-user', ['activeNav' => 'dashboard'])

    <!-- ══ MAIN ══ -->
    <div class="main">

      <!-- TOPBAR -->
      @include('layouts.topbar', ['title' => 'لوحة التحكم', 'userName' => (Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'), 'userAv' => mb_substr((Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'), 0, 1), 'showNotif' => true])
      @include('layouts.notif-panel-user')

      <!-- CONTENT: كل الأقسام محمّلة مسبقًا، والتبديل بينها يتم عبر JS بدون إعادة تحميل -->
      <div class="content">

        @include('partials.user-consulting.dashboard')
        @include('partials.user-consulting.meetings')
        @include('partials.user-consulting.volunteer')
        @include('partials.user-consulting.services')
        @include('partials.user-consulting.orders')
        @include('partials.user-consulting.projects')
        @include('partials.user-consulting.settings')

        {{-- ══ خدمات مبادرون — الأقسام الفرعية ══ --}}
        @include('partials.user-consulting.units')
        @include('partials.user-consulting.systems')
        @include('partials.user-consulting.initiatives')
        @include('partials.user-consulting.training')
        @include('partials.user-consulting.consulting-svc')
        @include('partials.user-consulting.contact')

      </div><!-- /content -->
    </div><!-- /main -->
  </div><!-- /layout -->

  {{-- ══ MODAL: تقديم طلب التطوع ══ --}}
  <div class="overlay" id="ov-apply" onclick="bgClose(event,'ov-apply')">
    <div class="modal" onclick="event.stopPropagation()">
      <div class="m-hd">
        <div class="m-hd-icon"><i class="fa-regular fa-paper-plane"></i></div>
        <div class="m-hd-text">
          <div class="m-hd-title">تقديم طلب تطوع</div>
          <div class="m-hd-sub">سيُراجَع طلبك من قِبَل الإدارة وستُخطَر بالنتيجة</div>
        </div>
        <button class="m-close" onclick="closeOv('ov-apply')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
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
          <label id="apply-name-label">اسم المتقدم <span class="req-span">*</span></label>
          <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><input type="text" id="f-apply-assoc" readonly></div>
        </div>

        <div class="fg">
          <label>رسالة للإدارة</label>
          <div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span><textarea id="f-apply-msg" placeholder="اشرح سبب اهتمامك بهذه الفرصة وما يمكنك تقديمه..."></textarea></div>
        </div>

        <div style="background:rgba(42,184,208,0.07);border:1.5px solid rgba(42,184,208,0.2);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:10px">
          <span style="font-size:1.1rem"><i class="fa-solid fa-circle-info" style="color:#2ab8d0"></i></span>
          <div style="font-size:0.82rem;color:var(--ink);line-height:1.6">سيصل طلبك إلى <strong>إدارة مبادرون</strong> للمراجعة، وستصلك إشعار بالقرار.</div>
        </div>
      </div>
      <div class="m-ft">
        <button class="btn-cancel" onclick="closeOv('ov-apply')">إلغاء</button>
        <button class="btn-save" onclick="submitApply()"><i class="fa-regular fa-paper-plane" style="margin-left:8px"></i> إرسال الطلب</button>
      </div>
    </div>
  </div>

  {{-- ══ MODAL: تقديم طلب الانضمام لمشروع مشترك ══ --}}
  <div class="overlay" id="ov-project-apply" onclick="bgClose(event,'ov-project-apply')">
    <div class="modal" onclick="event.stopPropagation()">
      <div class="m-hd">
        <div class="m-hd-icon"><i class="fa-solid fa-diagram-project"></i></div>
        <div class="m-hd-text">
          <div class="m-hd-title">تقديم طلب انضمام</div>
          <div class="m-hd-sub">سيُراجَع طلبك من قِبَل الإدارة وستُخطَر بالنتيجة</div>
        </div>
        <button class="m-close" onclick="closeProjectApplyModal()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <div class="m-body">
        <div class="apply-opp-preview">
          <div class="aop-title" id="proj-apply-title"></div>
          <div class="aop-meta">
            <span id="proj-apply-cat"></span>
          </div>
        </div>

        <div class="fg">
          <label>اسم المتقدم <span class="req-span">*</span></label>
          <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><input type="text" id="proj-apply-name" readonly></div>
        </div>

        <div class="fg">
          <label>رسالة للإدارة</label>
          <div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span><textarea id="proj-apply-msg" placeholder="اشرح سبب رغبتك في الانضمام لهذا المشروع وما يمكنك تقديمه..."></textarea></div>
        </div>

        <div style="background:rgba(42,184,208,0.07);border:1.5px solid rgba(42,184,208,0.2);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:10px">
          <span style="font-size:1.1rem"><i class="fa-solid fa-circle-info" style="color:#2ab8d0"></i></span>
          <div style="font-size:0.82rem;color:var(--ink);line-height:1.6">سيصل طلبك إلى <strong>إدارة مبادرون</strong> للمراجعة، وستصلك إشعار بالقرار.</div>
        </div>
      </div>
      <div class="m-ft">
        <button class="btn-cancel" onclick="closeProjectApplyModal()">إلغاء</button>
        <button class="btn-save" onclick="submitProjectApply()"><i class="fa-regular fa-paper-plane" style="margin-left:8px"></i> إرسال الطلب</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

  {{-- ══ SECTION SCRIPTS ══ --}}
  <script src="{{ asset('js/consulting.js') }}"></script>
  <script src="{{ asset('js/joint-projects.js') }}"></script>
  <script src="{{ asset('js/services.js') }}"></script>
  <script src="{{ asset('js/user-meetings.js') }}?v={{ time() }}"></script>
  <script src="{{ asset('js/user-dashboard-spa.js') }}?v={{ time() }}"></script>
  <script src="{{ asset('js/user-orders-spa.js') }}?v={{ time() }}"></script>
  <script src="{{ asset('js/user-settings-spa.js') }}?v={{ time() }}"></script>

  {{-- ══ NAVIGATION ENGINE (يجب أن يُحمَّل أخيرًا) ══ --}}
  <script src="{{ asset('js/user-spa-nav.js') }}?v={{ time() }}"></script>

  <script>
    window.AppRole = 'user';
    window.AppApplicantName = '{{ Auth::user()?->full_name ?? session("association.name") ?? "مستخدم" }}';
    window.AppApplicantLabel = 'اسم المتقدم';

    function showReadOnlyToast() {
      const t   = document.getElementById('toast');
      const msg = document.getElementById('t-msg');
      const ico = document.getElementById('t-icon');
      if (!t) return;
      ico.textContent = '';
      msg.textContent = 'أنت في وضع العرض فقط. هذه الصلاحية متاحة للمدير.';
      t.style.background = 'rgba(245,158,11,0.12)';
      t.style.borderRight = '4px solid #f59e0b';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 3500);
    }

    // Block admin-only actions but allow apply
    window.openCreate  = () => showReadOnlyToast();
    window.openAddOpp  = () => showReadOnlyToast();
    window.editFromDet = () => showReadOnlyToast();

    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('openNew');
      if (btn) btn.addEventListener('click', e => { e.stopImmediatePropagation(); showReadOnlyToast(); });

      // Re-render user opps whenever fetchOpportunities completes
      const _orig = window.fetchOpportunities;
      if (typeof _orig === 'function') {
        window.fetchOpportunities = async function() {
          await _orig();
          if (typeof renderUserOpps === 'function') renderUserOpps();
        };
      }
    });
  </script>
</body>

</html>
