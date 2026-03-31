<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تكامل — فرص التطوع</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
</head>

<body>
  <div class="layout">

    <!-- ══ SIDEBAR ══ -->
    <aside class="sidebar">
      <div class="sb-logo">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="" onerror="this.style.display='none'">
        <span>تكامل</span>
      </div>
      <nav class="sb-nav">
        <div class="sb-section">الرئيسية</div>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1.5" />
            <rect x="14" y="3" width="7" height="7" rx="1.5" />
            <rect x="3" y="14" width="7" height="7" rx="1.5" />
            <rect x="14" y="14" width="7" height="7" rx="1.5" />
          </svg>لوحة التحكم</a>
        <div class="sb-section">إدارة الأنشطة</div>
        <a href="<?php echo e(route('meetings')); ?>" class="nav-item" id="nav-meetings" onclick="openMeetingsPage()"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
          </svg>الاجتماعات</a>
        <a class="nav-item" data-vol onclick="backToVolunteer()"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
          </svg>فرص التطوع<span class="nav-badge" id="nb-opps">0</span></a>
        <a href="<?php echo e(route('orders')); ?>" class="nav-item" onclick="showAdminRequests()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
          </svg>الطلبات <span class="nav-badge red" id="nb-reqs">0</span></a>
        <a href="<?php echo e(route('joint-projects')); ?>" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
          </svg>المشاريع</a>
        <div class="sb-section">خدمات مبادرون</div>
        <div class="nav-parent" id="np-services" onclick="toggleServices()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 4 2-7L3 9h7z" />
          </svg>
          خدمات مبادرون
          <svg class="np-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12"
            height="12">
            <path d="M6 9l6 6 6-6" />
          </svg>
        </div>
        <div class="nav-submenu" id="submenu-services">
          <a class="nav-sub-item" id="sub-units" onclick="showService('units')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1" />
              <rect x="14" y="3" width="7" height="7" rx="1" />
              <rect x="3" y="14" width="7" height="7" rx="1" />
              <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>
            بناء وحدات
          </a>
          <a class="nav-sub-item" id="sub-systems" onclick="showService('systems')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="3" />
              <path
                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
            </svg>
            بناء أنظمة
          </a>
          <a class="nav-sub-item" id="sub-initiatives" onclick="showService('initiatives')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
            </svg>
            تنسيق مبادرات
          </a>
          <a class="nav-sub-item" id="sub-training" onclick="showService('training')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
              <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </svg>
            تدريب تطوعي
          </a>
          <a class="nav-sub-item" id="sub-consulting" onclick="showService('consulting')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01" />
            </svg>
            الاستشارات
          </a>
          <a class="nav-sub-item" id="sub-contact" onclick="showService('contact')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
            </svg>
            التواصل معنا
          </a>
        </div>

        <div class="sb-section">النظام</div>
        <a class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
          </svg>الملف الشخصي</a>
      </nav>
      <div class="sb-foot">
        <a class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
          </svg>تسجيل الخروج</a>
      </div>
    </aside>

    <!-- ══ MAIN ══ -->
    <div class="main">

      <!-- TOPBAR -->
      <div class="topbar">
        <div class="tb-left">
          <div class="tb-title" id="topbar-title">فرص التطوع</div>
          <div class="tb-crumb">تكامل / <span id="topbar-crumb">فرص التطوع</span></div>
        </div>
        <div class="tb-right">
          <div class="notif-btn" id="notif-btn" onclick="toggleNotifs()">
            <div class="notif-dot" id="notif-dot"></div>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17">
              <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0" />
            </svg>
          </div>
          <div class="tb-user">
            <div>
              <div class="tu-name" id="tu-name">مبادرون (أدمن)</div>
              <div class="tu-role" id="tu-role">مسؤول المنصة</div>
            </div>
            <div class="user-av" id="tu-av">م</div>
          </div>
        </div>
      </div>

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
              <button class="btn-primary" onclick="showAdminRequests()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                  <path d="M9 11l3 3L22 4" />
                  <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                </svg>
                الطلبات
                <span
                  style="background:rgba(255,255,255,0.25);border-radius:20px;padding:1px 8px;font-size:0.78rem;font-weight:800"
                  id="hdr-req-badge">0</span>
              </button>
            </div>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)">
              <div class="s-icon" style="background:rgba(42,184,208,0.1)">🌟</div>
              <div><span class="s-num" id="st-total">0</span><span class="s-lbl">إجمالي الفرص</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--green)">
              <div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div>
              <div><span class="s-num" id="st-open">0</span><span class="s-lbl">فرص مفتوحة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--gold)">
              <div class="s-icon" style="background:rgba(245,158,11,0.1)">⏳</div>
              <div><span class="s-num" id="st-pending">0</span><span class="s-lbl">طلبات معلقة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--purple)">
              <div class="s-icon" style="background:rgba(123,78,166,0.1)">🏷️</div>
              <div><span class="s-num" id="st-cats">6</span><span class="s-lbl">التصنيفات</span></div>
            </div>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div style="font-size:1rem;font-weight:800;color:var(--ink)">التصنيفات</div>
            <div style="font-size:0.82rem;color:var(--muted)">اختر تصنيفاً لعرض فرص التطوع الخاصة به أو إضافة فرصة جديدة
            </div>
          </div>
          <div class="cats-grid" id="cats-grid"></div>
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
            <div class="req-msg-icon">📬</div>
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
            <div class="nb-icon">🔔</div>
            <div class="nb-text">
              <div class="nb-title" id="notif-banner-title">تم قبول طلبك</div>
              <div class="nb-sub" id="notif-banner-sub">تم قبول طلب تقديمك بنجاح من قِبَل الإدارة</div>
            </div>
            <button class="nb-close"
              onclick="document.getElementById('assoc-notif-banner').style.display='none'">إغلاق</button>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)">
              <div class="s-icon" style="background:rgba(42,184,208,0.1)">🌟</div>
              <div><span class="s-num" id="ast-total">0</span><span class="s-lbl">فرص متاحة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--gold)">
              <div class="s-icon" style="background:rgba(245,158,11,0.1)">📝</div>
              <div><span class="s-num" id="ast-applied">0</span><span class="s-lbl">طلباتي</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--green)">
              <div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div>
              <div><span class="s-num" id="ast-approved">0</span><span class="s-lbl">مقبولة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--purple)">
              <div class="s-icon" style="background:rgba(123,78,166,0.1)">🏷️</div>
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
            <span class="svc-hero-icon">🏗️</span>
            <div class="svc-hero-title">بناء وحدات</div>
            <div class="svc-hero-sub">نساعد الجمعيات في تأسيس وحداتها التنظيمية وتطوير هياكلها الإدارية لتحقيق الكفاءة
              والفاعلية.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🎯</span>
              <div class="svc-card-title">تحليل الاحتياجات</div>
              <div class="svc-card-desc">دراسة وضع الجمعية الحالي وتحديد الوحدات التنظيمية المطلوبة لتحقيق أهدافها
                الاستراتيجية.</div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">📐</span>
              <div class="svc-card-title">تصميم الهيكل التنظيمي</div>
              <div class="svc-card-desc">بناء هياكل تنظيمية واضحة تحدد الأدوار والمسؤوليات وخطوط الإبلاغ بشكل فعّال.
              </div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">⚙️</span>
              <div class="svc-card-title">تفعيل الوحدات</div>
              <div class="svc-card-desc">دعم الجمعية في إطلاق وحداتها الجديدة وتزويد فرقها بالأدوات والموارد اللازمة
                للعمل.</div>
            </div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">📊</span>
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
            <span class="svc-hero-icon">⚙️</span>
            <div class="svc-hero-title">بناء أنظمة</div>
            <div class="svc-hero-sub">تطوير الأنظمة والإجراءات التشغيلية التي تضمن استدامة عمل الجمعيات وتحسين جودة
              خدماتها.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon">📋</span>
              <div class="svc-card-title">أنظمة الحوكمة</div>
              <div class="svc-card-desc">بناء أنظمة إدارية واضحة تضمن الشفافية والمساءلة وحسن اتخاذ القرار.</div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🔄</span>
              <div class="svc-card-title">العمليات التشغيلية</div>
              <div class="svc-card-desc">توثيق وتطوير الإجراءات التشغيلية لضمان الكفاءة وتقليل الأخطاء وتوحيد العمل.
              </div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">💾</span>
              <div class="svc-card-title">أنظمة المعلومات</div>
              <div class="svc-card-desc">تصميم أنظمة لإدارة البيانات والمعلومات وضمان سهولة الوصول واتخاذ القرار المبني
                على البيانات.</div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">🛡️</span>
              <div class="svc-card-title">أنظمة الجودة</div>
              <div class="svc-card-desc">تطبيق معايير الجودة ومراجعتها بشكل دوري لضمان الارتقاء المستمر بمستوى الخدمات.
              </div>
            </div>
          </div>
        </div>

        <!-- تنسيق مبادرات -->
        <div class="view" id="view-initiatives">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1d3d1a,#2eaa78,#34d399)">
            <span class="svc-hero-icon">🤝</span>
            <div class="svc-hero-title">تنسيق مبادرات</div>
            <div class="svc-hero-sub">تيسير التعاون بين الجمعيات وتنسيق المبادرات المشتركة لتعظيم الأثر المجتمعي.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">🌐</span>
              <div class="svc-card-title">الشبكات التعاونية</div>
              <div class="svc-card-desc">ربط الجمعيات ذات الاهتمامات المشتركة لتبادل الموارد والخبرات وتنفيذ مشاريع
                مشتركة.</div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🗺️</span>
              <div class="svc-card-title">خرائط المبادرات</div>
              <div class="svc-card-desc">توثيق وتصنيف المبادرات القائمة وتجنب التكرار وضمان التكامل بين الجهود المختلفة.
              </div>
            </div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">⚡</span>
              <div class="svc-card-title">مبادرات الطوارئ</div>
              <div class="svc-card-desc">التنسيق السريع بين الجمعيات في حالات الأزمات لتقديم استجابة فعّالة ومنظمة.
              </div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">📈</span>
              <div class="svc-card-title">قياس الأثر المشترك</div>
              <div class="svc-card-desc">تطوير أطر مشتركة لقياس الأثر الاجتماعي للمبادرات التعاونية وإعداد التقارير.
              </div>
            </div>
          </div>
        </div>

        <!-- تدريب تطوعي -->
        <div class="view" id="view-training">
          <div class="svc-hero" style="background:linear-gradient(135deg,#4a1942,#7b4ea6,#a78bfa)">
            <span class="svc-hero-icon">🎓</span>
            <div class="svc-hero-title">تدريب تطوعي</div>
            <div class="svc-hero-sub">برامج تدريبية متخصصة لتأهيل المتطوعين وبناء قدراتهم لأداء أعمال تطوعية ذات أثر
              حقيقي.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">🌱</span>
              <div class="svc-card-title">تأهيل المتطوعين الجدد</div>
              <div class="svc-card-desc">برامج استقبال وتوجيه تزود المتطوعين الجدد بالمعرفة والمهارات اللازمة للمشاركة
                الفعّالة.</div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🏆</span>
              <div class="svc-card-title">تطوير القيادات</div>
              <div class="svc-card-desc">برامج قيادية متخصصة لإعداد جيل جديد من قادة العمل التطوعي في القطاع غير الربحي.
              </div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">🛠️</span>
              <div class="svc-card-title">مهارات تقنية</div>
              <div class="svc-card-desc">تدريب المتطوعين على الأدوات الرقمية وتقنيات إدارة المشاريع والتواصل الفعّال.
              </div>
            </div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">💡</span>
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
            <span class="svc-hero-icon">💡</span>
            <div class="svc-hero-title">الاستشارات</div>
            <div class="svc-hero-sub">خدمات استشارية متخصصة تدعم الجمعيات في اتخاذ قراراتها الاستراتيجية وتطوير قدراتها
              المؤسسية.</div>
          </div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon">🗺️</span>
              <div class="svc-card-title">التخطيط الاستراتيجي</div>
              <div class="svc-card-desc">مساعدة الجمعيات في صياغة رؤيتها ورسالتها وأهدافها الاستراتيجية للسنوات القادمة.
              </div>
            </div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">📊</span>
              <div class="svc-card-title">التقييم المؤسسي</div>
              <div class="svc-card-desc">تشخيص شامل للوضع المؤسسي وتحديد نقاط القوة والضعف وفرص التطوير.</div>
            </div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">💰</span>
              <div class="svc-card-title">الاستدامة المالية</div>
              <div class="svc-card-desc">استشارات في تنويع مصادر التمويل وبناء نماذج عمل مستدامة للجمعيات.</div>
            </div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">🔗</span>
              <div class="svc-card-title">الشراكات الاستراتيجية</div>
              <div class="svc-card-desc">تطوير استراتيجيات للشراكة مع القطاعات الحكومية والخاصة لتعزيز الأثر.</div>
            </div>
          </div>
        </div>

        <!-- التواصل معنا -->
        <div class="view" id="view-contact">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1a2f1a,#2eaa78,#6ee7b7)">
            <span class="svc-hero-icon">📬</span>
            <div class="svc-hero-title">التواصل معنا</div>
            <div class="svc-hero-sub">نحن هنا لمساعدتك. تواصل معنا وسيردّ فريقنا خلال 24 ساعة.</div>
          </div>
          <div class="contact-wrap">
            <div class="contact-info">
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(42,184,208,0.1)">📧</div>
                <div>
                  <div class="cc-label">البريد الإلكتروني</div>
                  <div class="cc-value">info@mubadiroon.sa</div>
                </div>
              </div>
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(46,170,120,0.1)">📱</div>
                <div>
                  <div class="cc-label">الجوال والواتساب</div>
                  <div class="cc-value">966-50-000-0000+</div>
                </div>
              </div>
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(123,78,166,0.1)">📍</div>
                <div>
                  <div class="cc-label">الموقع</div>
                  <div class="cc-value">الرياض، المملكة العربية السعودية</div>
                </div>
              </div>
              <div class="contact-card">
                <div class="cc-icon" style="background:rgba(245,158,11,0.1)">🕐</div>
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
              <button class="btn-save" style="width:100%" onclick="sendContactMsg()">📨 إرسال الرسالة</button>
            </div>
          </div>
        </div>

      </div><!-- /content -->
    </div><!-- /main -->
  </div><!-- /layout -->

  <!-- ══ ADD / EDIT OPP MODAL ══ -->
  <div class="overlay" id="ov-opp" onclick="bgClose(event,'ov-opp')">
    <div class="modal" onclick="event.stopPropagation()">
      <div class="m-hd">
        <div class="m-hd-icon" id="opp-m-icon">🌟</div>
        <div class="m-hd-text">
          <div class="m-hd-title" id="opp-m-title">إضافة فرصة تطوع</div>
          <div class="m-hd-sub" id="opp-m-sub">أضف تفاصيل الفرصة أدناه</div>
        </div>
        <button class="m-close" onclick="closeOv('ov-opp')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" width="15" height="15">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg></button>
      </div>
      <div class="m-body">
        <div class="selected-cat-badge" id="sel-cat-badge">📖 ثقافية وتعليمية</div>

        <div class="fg">
          <label>عنوان الفرصة <span class="req-span">*</span></label>
          <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                width="15" height="15">
                <path
                  d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
              </svg></span><input type="text" id="f-opp-title" placeholder="مثال: تعليم القرآن للأطفال"></div>
        </div>

        <div class="fg">
          <label>وصف الفرصة <span class="req-span">*</span></label>
          <div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" width="15" height="15">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
              </svg></span><textarea id="f-opp-desc"
              placeholder="اشرح طبيعة عمل المتطوع ومتطلبات الانضمام..."></textarea></div>
        </div>

        <div class="row2">
          <div class="fg">
            <label>الجهة المستضيفة <span class="req-span">*</span></label>
            <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" width="15" height="15">
                  <path d="M3 21h18M9 8h1m5 0h1M9 12h1m5 0h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16" />
                </svg></span><input type="text" id="f-opp-org" placeholder="اسم الجمعية المنظّمة"></div>
          </div>
          <div class="fg">
            <label>المدينة</label>
            <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" width="15" height="15">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg></span><input type="text" id="f-opp-city" placeholder="الرياض، جدة..."></div>
          </div>
        </div>

        <div class="row2">
          <div class="fg">
            <label>عدد المتطوعين المطلوبين</label>
            <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" width="15" height="15">
                  <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                  <path d="M23 21v-2a4 4 0 00-3-3.87" />
                </svg></span><input type="number" id="f-opp-seats" placeholder="مثال: 10" min="1"></div>
          </div>
          <div class="fg">
            <label>تاريخ الانتهاء</label>
            <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" width="15" height="15">
                  <rect x="3" y="4" width="18" height="18" rx="2" />
                  <path d="M16 2v4M8 2v4M3 10h18" />
                </svg></span><input type="date" id="f-opp-deadline"></div>
          </div>
        </div>

        <div class="f-div">تفاصيل إضافية</div>

        <div class="row2">
          <div class="fg">
            <label>نوع التطوع</label>
            <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" width="15" height="15">
                  <rect x="2" y="3" width="20" height="14" rx="2" />
                  <line x1="8" y1="21" x2="16" y2="21" />
                </svg></span>
              <select id="f-opp-type">
                <option value="onsite">حضوري</option>
                <option value="remote">عن بعد</option>
                <option value="both">حضوري وعن بعد</option>
              </select>
            </div>
          </div>
          <div class="fg">
            <label>الحالة</label>
            <div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" width="15" height="15">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg></span>
              <select id="f-opp-status">
                <option value="open">مفتوحة</option>
                <option value="closed">مغلقة</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="m-ft">
        <button class="btn-cancel" onclick="closeOv('ov-opp')">إلغاء</button>
        <button class="btn-save" onclick="saveOpp()"><span id="opp-save-lbl">💾 إضافة الفرصة</span></button>
      </div>
    </div>
  </div>

  <!-- ══ APPLY MODAL ══ -->
  <div class="overlay" id="ov-apply" onclick="bgClose(event,'ov-apply')">
    <div class="modal" onclick="event.stopPropagation()">
      <div class="m-hd">
        <div class="m-hd-icon">📝</div>
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
        <button class="btn-save" onclick="submitApply()">📨 إرسال الطلب</button>
      </div>
    </div>
  </div>

  <!-- DELETE CONFIRM -->
  <div class="overlay" id="ov-del" onclick="bgClose(event,'ov-del')">
    <div class="confirm-box" onclick="event.stopPropagation()">
      <div class="ci-wrap" style="background:rgba(229,57,53,0.1)">🗑️</div>
      <div class="ci-title">حذف الفرصة</div>
      <div class="ci-desc">هل أنت متأكد من حذف هذه الفرصة؟<br>لا يمكن التراجع عن هذا الإجراء.</div>
      <div class="ci-row">
        <button class="btn-cancel" style="flex:1" onclick="closeOv('ov-del')">إلغاء</button>
        <button class="btn-danger" onclick="doDelete()">حذف</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

  <!-- ══ MEETINGS PANEL ══ -->
  <div class="meetings-panel-overlay" id="meetings-overlay" onclick="closeMeetingsPage()"></div>
  <div class="meetings-panel" id="meetings-panel">
    <div class="meetings-panel-header">
      <div class="mph-left">
        <div>
          <div class="mph-title">الاجتماعات</div>
          <div class="mph-crumb">تكامل / <span>الاجتماعات</span></div>
        </div>
      </div>
      <button class="mph-close" onclick="closeMeetingsPage()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
        إغلاق
      </button>
    </div>
    <iframe class="meetings-panel-frame" id="meetings-frame" src="" title="الاجتماعات"></iframe>
  </div>

  <script src="<?php echo e(asset('js/consulting.js')); ?>"></script>
</body>

</html><?php /**PATH /home/a-22/Tkamel_project/المشروع الحالي/Tkamel/tkamel/resources/views/consulting.blade.php ENDPATH**/ ?>