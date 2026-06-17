<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تكامل — فرص التطوع</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/consulting.css') }}">
  <link rel="stylesheet" href="{{ asset('css/meeting-scoped.css') }}">
  <link rel="stylesheet" href="{{ asset('css/orders-scoped.css') }}">
  <link rel="stylesheet" href="{{ asset('css/jp-scoped.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ── Read-only notice banner ── */
    .readonly-notice {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(245, 158, 11, 0.08);
      border: 1px solid rgba(245, 158, 11, 0.25);
      border-radius: 10px;
      padding: 10px 16px;
      font-size: 0.82rem;
      color: #92400e;
      font-weight: 600;
      margin-bottom: 18px;
    }
    .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(245, 158, 11, 0.12);
      color: #b45309;
      border: 1px solid rgba(245, 158, 11, 0.3);
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 0.72rem;
      font-weight: 700;
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

    /* ── Modal Overlay ── */
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
  </style>
<style>#nb-reqs:empty{display:none!important}</style>
</head>

<body>
  <div class="layout">

    <!-- ══ SIDEBAR ══ -->
    @include('layouts.sidebar-user', ['activeNav' => 'volunteer'])


    <!-- ══ MAIN ══ -->
    <div class="main">

      <!-- TOPBAR -->
      @include('layouts.topbar', ['title' => 'فرص التطوع', 'userName' => (Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'), 'userAv' => mb_substr((Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'), 0, 1), 'showNotif' => true])
      @include('layouts.notif-panel-user')


      <!-- CONTENT -->
      <div class="content">

        {{-- ══ فرص التطوع — عرض مسطح بدون تصنيفات ══ --}}
        <div class="view" id="view-admin">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">تصفح الفرص المتاحة</div>
            </div>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)"><div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-star"></i></div><div><span class="s-num" id="st-total">0</span><span class="s-lbl">إجمالي الفرص</span></div></div>
            <div class="stat-card" style="--sc:var(--green)"><div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div><div><span class="s-num" id="st-open">0</span><span class="s-lbl">فرص مفتوحة</span></div></div>
            <div class="stat-card" style="--sc:var(--gold)"><div class="s-icon" style="background:rgba(245,158,11,0.1)">⏳</div><div><span class="s-num" id="st-pending">0</span><span class="s-lbl">طلباتي</span></div></div>
            <div class="stat-card" style="--sc:var(--purple)"><div class="s-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-tag"></i></div><div><span class="s-num" id="st-cats">0</span><span class="s-lbl">التصنيفات</span></div></div>
          </div>


          <div class="tabs" style="margin-bottom:16px;">
            <button class="tab on" data-opp-t="available" onclick="setOppTab('available')"><i class="fa-solid fa-star"></i>الفرص المتاحة<span class="n" id="n-opp-avail">0</span></button>
            <button class="tab" data-opp-t="active" onclick="setOppTab('active')"><i class="fa-solid fa-rocket"></i>مشاريع تطوعية نشطة<span class="n" id="n-opp-active">0</span></button>
            <button class="tab" data-opp-t="expired" onclick="setOppTab('expired')"><i class="fa-solid fa-clock-rotate-left"></i>الفرص المنتهية<span class="n" id="n-opp-expired">0</span></button>
          </div>

          <div class="toolbar" style="margin-bottom:16px">
            <div class="sw">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input class="s-inp" id="user-opp-search" type="text" placeholder="ابحث عن فرصة..." oninput="renderUserOpps()">
            </div>
          </div>

          <div class="all-opps-grid" id="user-all-opps-grid"></div>
        </div>

        {{-- ══ صفحات التصنيفات (مخفية في وضع المستخدم — تُستخدم للمنطق فقط) ══ --}}
        <div class="view" id="view-admin-opps" style="display:none"></div>
        <div class="view" id="view-admin-reqs" style="display:none"></div>
        <div class="view" id="view-assoc" style="display:none"></div>
        <div class="view" id="view-assoc-opps" style="display:none">
          <div id="assoc-opps-grid"></div>
        </div>
        {{-- Hidden elements required by JS --}}
        <div style="display:none">
          <div id="cats-grid"></div>
          <div id="assoc-cats-grid"></div>
          <div id="admin-opps-grid"></div>
          <div id="req-list"></div>
          <input id="admin-opp-search">
          <input id="assoc-opp-search">
          <span id="ao-cat-icon"></span><span id="ao-cat-name"></span>
          <span id="ao-title"></span><span id="ao-sub"></span>
          <span id="ao-cat-icon2"></span><span id="ao-cat-name2"></span>
          <span id="ao-title2"></span>
          <span id="ast-total">0</span><span id="ast-applied">0</span>
          <span id="ast-approved">0</span><span id="ast-cats">0</span>
          <span id="rc-pending">0</span><span id="rc-approved">0</span><span id="rc-rejected">0</span>
          <span id="nb-reqs"></span>
          <span id="all-opp-search"></span>
          <select id="all-opp-cat-filter"></select>
          <select id="all-opp-status-filter"></select>
          <div id="all-admin-opps-grid"></div>
          <div id="panel-all-opps"></div>
          <div id="panel-cats"></div>
          <span id="pill-opps"></span><span id="pill-cats"></span>
          <span id="pill-cats-count"></span>
        </div>

        {{-- ══ SERVICE VIEWS (same, no changes needed) ══ --}}

        <div class="view" id="view-units">
          <div class="svc-hero"><span class="svc-hero-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span><div class="svc-hero-title">بناء وحدات</div><div class="svc-hero-sub">نساعد الجمعيات في تأسيس وحداتها التنظيمية وتطوير هياكلها الإدارية لتحقيق الكفاءة والفاعلية.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-bullseye"></i></span><div class="svc-card-title">تحليل الاحتياجات</div><div class="svc-card-desc">دراسة وضع الجمعية الحالي وتحديد الوحدات التنظيمية المطلوبة لتحقيق أهدافها الاستراتيجية.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-compass-drafting"></i></span><div class="svc-card-title">تصميم الهيكل التنظيمي</div><div class="svc-card-desc">بناء هياكل تنظيمية واضحة تحدد الأدوار والمسؤوليات وخطوط الإبلاغ بشكل فعّال.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-gears"></i></span><div class="svc-card-title">تفعيل الوحدات</div><div class="svc-card-desc">دعم الجمعية في إطلاق وحداتها الجديدة وتزويد فرقها بالأدوات والموارد اللازمة للعمل.</div></div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon"><i class="fa-solid fa-chart-column"></i></span><div class="svc-card-title">قياس الأداء</div><div class="svc-card-desc">وضع مؤشرات أداء واضحة لمتابعة تطور الوحدات وقياس مدى تحقيقها لأهدافها المرسومة.</div></div>
          </div>
        </div>

        <div class="view" id="view-systems">
          <div class="svc-hero" style="background:linear-gradient(135deg,#0d3d49,#1a6b7c,#3a72b8)"><span class="svc-hero-icon"><i class="fa-solid fa-gear"></i></span><div class="svc-hero-title">بناء أنظمة</div><div class="svc-hero-sub">تطوير الأنظمة والإجراءات التشغيلية التي تضمن استدامة عمل الجمعيات وتحسين جودة خدماتها.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon"><i class="fa-solid fa-clipboard-list"></i></span><div class="svc-card-title">أنظمة الحوكمة</div><div class="svc-card-desc">بناء أنظمة إدارية واضحة تضمن الشفافية والمساءلة وحسن اتخاذ القرار.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-rotate"></i></span><div class="svc-card-title">العمليات التشغيلية</div><div class="svc-card-desc">توثيق وتطوير الإجراءات التشغيلية لضمان الكفاءة وتقليل الأخطاء وتوحيد العمل.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-database"></i></span><div class="svc-card-title">أنظمة المعلومات</div><div class="svc-card-desc">تصميم أنظمة لإدارة البيانات والمعلومات وضمان سهولة الوصول واتخاذ القرار المبني على البيانات.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-shield-halved"></i></span><div class="svc-card-title">أنظمة الجودة</div><div class="svc-card-desc">تطبيق معايير الجودة ومراجعتها بشكل دوري لضمان الارتقاء المستمر بمستوى الخدمات.</div></div>
          </div>
        </div>

        <div class="view" id="view-initiatives">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1d3d1a,#2eaa78,#34d399)"><span class="svc-hero-icon"><i class="fa-solid fa-handshake-angle"></i></span><div class="svc-hero-title">تنسيق مبادرات</div><div class="svc-hero-sub">تيسير التعاون بين الجمعيات وتنسيق المبادرات المشتركة لتعظيم الأثر المجتمعي.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-globe"></i></span><div class="svc-card-title">الشبكات التعاونية</div><div class="svc-card-desc">ربط الجمعيات ذات الاهتمامات المشتركة لتبادل الموارد والخبرات وتنفيذ مشاريع مشتركة.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-map-location-dot"></i></span><div class="svc-card-title">خرائط المبادرات</div><div class="svc-card-desc">توثيق وتصنيف المبادرات القائمة وتجنب التكرار وضمان التكامل بين الجهود المختلفة.</div></div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon"><i class="fa-solid fa-bolt"></i></span><div class="svc-card-title">مبادرات الطوارئ</div><div class="svc-card-desc">التنسيق السريع بين الجمعيات في حالات الأزمات لتقديم استجابة فعّالة ومنظمة.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-chart-line"></i></span><div class="svc-card-title">قياس الأثر المشترك</div><div class="svc-card-desc">تطوير أطر مشتركة لقياس الأثر الاجتماعي للمبادرات التعاونية وإعداد التقارير.</div></div>
          </div>
        </div>

        <div class="view" id="view-training">
          <div class="svc-hero" style="background:linear-gradient(135deg,#4a1942,#7b4ea6,#a78bfa)"><span class="svc-hero-icon"><i class="fa-solid fa-graduation-cap"></i></span><div class="svc-hero-title">تدريب تطوعي</div><div class="svc-hero-sub">برامج تدريبية متخصصة لتأهيل المتطوعين وبناء قدراتهم لأداء أعمال تطوعية ذات أثر حقيقي.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-seedling"></i></span><div class="svc-card-title">تأهيل المتطوعين الجدد</div><div class="svc-card-desc">برامج استقبال وتوجيه تزود المتطوعين الجدد بالمعرفة والمهارات اللازمة للمشاركة الفعّالة.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-trophy"></i></span><div class="svc-card-title">تطوير القيادات</div><div class="svc-card-desc">برامج قيادية متخصصة لإعداد جيل جديد من قادة العمل التطوعي في القطاع غير الربحي.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span><div class="svc-card-title">مهارات تقنية</div><div class="svc-card-desc">تدريب المتطوعين على الأدوات الرقمية وتقنيات إدارة المشاريع والتواصل الفعّال.</div></div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon"><i class="fa-solid fa-lightbulb"></i></span><div class="svc-card-title">الابتكار الاجتماعي</div><div class="svc-card-desc">ورش عمل تحفّز على التفكير الإبداعي وتطوير حلول مبتكرة للتحديات المجتمعية.</div></div>
          </div>
        </div>

        <div class="view" id="view-consulting">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1e3a5f,#3a72b8,#60a5fa)"><span class="svc-hero-icon"><i class="fa-solid fa-lightbulb"></i></span><div class="svc-hero-title">الاستشارات</div><div class="svc-hero-sub">خدمات استشارية متخصصة تدعم الجمعيات في اتخاذ قراراتها الاستراتيجية وتطوير قدراتها المؤسسية.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon"><i class="fa-solid fa-map-location-dot"></i></span><div class="svc-card-title">التخطيط الاستراتيجي</div><div class="svc-card-desc">مساعدة الجمعيات في صياغة رؤيتها ورسالتها وأهدافها الاستراتيجية للسنوات القادمة.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon"><i class="fa-solid fa-chart-column"></i></span><div class="svc-card-title">التقييم المؤسسي</div><div class="svc-card-desc">تشخيص شامل للوضع المؤسسي وتحديد نقاط القوة والضعف وفرص التطوير.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon"><i class="fa-solid fa-sack-dollar"></i></span><div class="svc-card-title">الاستدامة المالية</div><div class="svc-card-desc">استشارات في تنويع مصادر التمويل وبناء نماذج عمل مستدامة للجمعيات.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon"><i class="fa-solid fa-link"></i></span><div class="svc-card-title">الشراكات الاستراتيجية</div><div class="svc-card-desc">تطوير استراتيجيات للشراكة مع القطاعات الحكومية والخاصة لتعزيز الأثر.</div></div>
          </div>
        </div>

        <div class="view" id="view-contact">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1a2f1a,#2eaa78,#6ee7b7)"><span class="svc-hero-icon"><i class="fa-solid fa-envelope-open-text"></i></span><div class="svc-hero-title">التواصل معنا</div><div class="svc-hero-sub">نحن هنا لمساعدتك. تواصل معنا وسيردّ فريقنا خلال 24 ساعة.</div></div>
          <div class="contact-wrap">
            <div class="contact-info">
              <div class="contact-card"><div class="cc-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-envelope"></i></div><div><div class="cc-label">البريد الإلكتروني</div><div class="cc-value">info@mubadiroon.sa</div></div></div>
              <div class="contact-card"><div class="cc-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-phone"></i></div><div><div class="cc-label">الجوال والواتساب</div><div class="cc-value">966-50-000-0000+</div></div></div>
              <div class="contact-card"><div class="cc-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-location-dot"></i></div><div><div class="cc-label">الموقع</div><div class="cc-value">الرياض، المملكة العربية السعودية</div></div></div>
              <div class="contact-card"><div class="cc-icon" style="background:rgba(245,158,11,0.1)"><i class="fa-regular fa-clock"></i></div><div><div class="cc-label">أوقات العمل</div><div class="cc-value">الأحد – الخميس، ٨ص – ٥م</div></div></div>
            </div>
            <div class="contact-form-card">
              <div class="cfc-title">أرسل لنا رسالة</div>
              <div class="fg"><label>الاسم الكامل <span class="req-span">*</span></label><div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><input type="text" placeholder="أدخل اسمك الكامل"></div></div>
              <div class="fg"><label>البريد الإلكتروني <span class="req-span">*</span></label><div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span><input type="email" placeholder="example@mail.com" dir="ltr" style="text-align:right"></div></div>
              <div class="fg"><label>نوع الطلب</label><div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span><select><option>استشارة</option><option>شراكة</option><option>تدريب</option><option>تنسيق مبادرة</option><option>استفسار عام</option></select></div></div>
              <div class="fg"><label>الرسالة <span class="req-span">*</span></label><div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span><textarea placeholder="اكتب رسالتك هنا..." style="min-height:100px"></textarea></div></div>
              <button class="btn-save" style="width:100%" onclick="sendContactMsg()"><i class="fa-regular fa-paper-plane" style="margin-left:8px"></i> إرسال الرسالة</button>
            </div>
          </div>
        </div>

        {{-- ══ MEETINGS — عرض فقط (بدون زر إنشاء اجتماع) ══ --}}
        <div class="view" id="view-meetings">
          <div class="content">
            <div class="page-header">
              <div>
                <div class="ph-title">الاجتماعات</div>
                <div class="ph-sub">عرض الاجتماعات — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
              </div>
              {{-- لا يوجد زر "إنشاء اجتماع" --}}
            </div>

            <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-regular fa-calendar"></i></div><div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div></div>
              <div class="stat-card" style="--sc:var(--green)"><div class="stat-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-circle" style="font-size:.7rem"></i></div><div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">الحالية والقادمة</span></div></div>
              <div class="stat-card" style="--sc:var(--muted)"><div class="stat-icon" style="background:rgba(106,132,148,0.1)"><i class="fa-regular fa-folder"></i></div><div><span class="stat-num" id="s-past">0</span><span class="stat-lbl">السابقة</span></div></div>
              <div class="stat-card" style="--sc:var(--red)"><div class="stat-icon" style="background:rgba(198,40,40,0.08)"><i class="fa-solid fa-ban"></i></div><div><span class="stat-num" id="s-canc">0</span><span class="stat-lbl">الملغاة</span></div></div>
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-laptop"></i></div><div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div></div>
            </div>

            <div class="toolbar">
              <div class="search-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="renderAll()"></div>
              <div class="tb-div"></div>
              <select class="filter-select" id="catFilter" onchange="renderAll()">
                <option value="">كل التصنيفات</option>
                <option value="خيرية">خيرية واجتماعية</option>
                <option value="ثقافية">ثقافية وتعليمية</option>
                <option value="صحية">صحية وبيئية</option>
                <option value="رياضية">رياضية وشبابية</option>
                <option value="تنموية">تنموية واقتصادية</option>
                <option value="دينية">دينية ودعوية</option>
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
              <div class="sec-header collapsible" onclick="toggleSec('past')"><div class="sec-icon" style="background:rgba(106,132,148,0.1)"><i class="fa-regular fa-folder"></i></div><div class="sec-title">الاجتماعات السابقة</div><span class="sec-count sc-past" id="bc-past">0</span><div class="sec-toggle" id="tog-past"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6"/></svg></div></div>
              <div id="sec-past"><div class="compact-list" id="list-past"></div></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('canc')"><div class="sec-icon" style="background:rgba(198,40,40,0.08)"><i class="fa-solid fa-ban"></i></div><div class="sec-title">الاجتماعات الملغاة</div><span class="sec-count sc-cancelled" id="bc-canc">0</span><div class="sec-toggle" id="tog-canc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6"/></svg></div></div>
              <div id="sec-canc"><div class="compact-list" id="list-canc"></div></div>
            </div>
          </div>

          {{-- Details modal (read-only — edit button hidden) --}}
          <div class="overlay" id="ov-details" onclick="bgClose(event,'ov-details')">
            <div class="det-modal" onclick="event.stopPropagation()">
              <div class="det-banner"><div class="det-banner-bg" id="d-banner-bg"></div><div class="det-banner-pattern"></div><div class="det-banner-content"><div class="det-type-badge" id="d-type-badge"></div></div><button class="det-close" onclick="closeOv('ov-details')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
              <div class="det-body">
                <div class="det-title" id="d-title"></div>
                <div class="det-grid" id="d-grid"><div class="det-cell"><div class="det-cell-lbl">الفئة</div><div class="det-cell-val" id="d-cat"></div></div><div class="det-cell"><div class="det-cell-lbl">التاريخ</div><div class="det-cell-val" id="d-date"></div></div><div class="det-cell"><div class="det-cell-lbl">الوقت</div><div class="det-cell-val" id="d-time"></div></div><div class="det-cell" id="d-loc-cell"><div class="det-cell-lbl">المكان</div><div class="det-cell-val" id="d-loc"></div></div></div>
                <div class="det-presenter"><div class="dp-av" id="d-av"></div><div><div class="dp-name" id="d-pname"></div><div class="dp-role">مقدم الاجتماع</div></div></div>
                <div id="d-notes-wrap" style="display:none" class="det-block"><div class="det-block-lbl">ملاحظات</div><div class="det-notes" id="d-notes"></div></div>
                <div id="d-report-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--green)"><i class="fa-solid fa-clipboard-list" style="margin-left:6px"></i> تقرير الاجتماع</div><div class="det-report" id="d-report-content"></div></div>
                <div id="d-cancel-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--red)">سبب الإلغاء</div><div class="det-cancel" id="d-cancel-reason"></div></div>
              </div>
              {{-- إغلاق فقط — لا يوجد زر تعديل --}}
              <div class="det-ft"><button class="btn-cancel" style="flex:1" onclick="closeOv('ov-details')">إغلاق</button></div>
            </div>
          </div>
        </div>{{-- /view-meetings --}}



        {{-- ══ PROJECTS — عرض فقط (بدون زر مشروع جديد) ══ --}}
        <div class="view" id="view-projects">
          <main class="main-content">
            <div class="ph">
              <div>
                <h1>المشاريع المشتركة</h1>
                <p>عرض المشاريع المشتركة</p>
              </div>
              {{-- لا يوجد زر "مشروع جديد" --}}
            </div>
            <div class="stats" id="statsRow"></div>
            <div class="tabs" style="margin-bottom:16px;">
              <button class="tab on" data-t="tab-active"><i class="fa-solid fa-star"></i>المشاريع المتاحة<span class="n" id="n-active">0</span></button>
              <button class="tab" data-t="tab-approved"><i class="fa-solid fa-rocket"></i>مشاريع مشتركة نشطة<span class="n" id="n-approved">0</span></button>
              <button class="tab" data-t="tab-done"><i class="fa-solid fa-clock-rotate-left"></i>المشاريع المنتهية<span class="n" id="n-done">0</span></button>
            </div>
            <div class="filter-row">
              <div class="dd-wrap" id="ddWrap"><button class="dd-btn" id="ddBtn" type="button"><span class="dd-left"><span class="emoji"><i class="fa-solid fa-building"></i></span><span id="ddLabel">كل التصنيفات</span></span><i class="fa-solid fa-chevron-down chevron"></i></button><div class="dd-menu" id="ddMenu"></div></div>
              <div class="search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input type="text" class="sinput" id="searchQ" placeholder="ابحث باسم المشروع..."></div>
              <span class="res-badge" id="resBadge"><span id="resNum">0</span> مشروع</span>
            </div>
            <div id="tab-active" class="pane on grid"></div>
            <div id="tab-approved" class="pane grid"></div>
            <div id="tab-done" class="pane grid"></div>
          </main>
          {{-- عرض تفاصيل فقط — بدون نماذج تعديل أو إنشاء --}}
          <div class="ov" id="ovConfirm" style="display:none"></div>
        </div>{{-- /view-projects --}}

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

  <script src="{{ asset('js/consulting.js') }}"></script>
  <script src="{{ asset('js/meeting.js') }}"></script>
  <script src="{{ asset('js/orders.js') }}"></script>
  <script src="{{ asset('js/joint-projects.js') }}"></script>
  <script src="{{ asset('js/spa-nav.js') }}"></script>
  <script>
    window.AppRole = 'user';
    window.AppApplicantName = '{{ Auth::user()?->full_name ?? "مستخدم" }}';
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

    // Block the "new project" button by ID
    // Trigger user opps render after JS loads
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('openNew');
      if (btn) btn.addEventListener('click', e => { e.stopImmediatePropagation(); showReadOnlyToast(); });

      // Re-render user opps whenever fetchOpportunities completes
      const _orig = window.fetchOpportunities;
      if (typeof _orig === 'function') {
        window.fetchOpportunities = async function() {
          await _orig();
          renderUserOpps();
        };
      }
    });
  </script>
</body>

</html>
