<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تكامل — فرص التطوع</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/meeting-scoped.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/orders-scoped.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/jp-scoped.css')); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <a class="nav-item" id="nav-meetings" onclick="showSection('meetings')"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
          </svg>الاجتماعات</a>
        <a class="nav-item" id="nav-volunteer" data-vol onclick="showSection('volunteer')"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
          </svg>فرص التطوع<span class="nav-badge" id="nb-opps">0</span></a>
        <a class="nav-item" id="nav-orders" onclick="showSection('orders')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
          </svg>الطلبات <span class="nav-badge red" id="nb-reqs">0</span></a>
        <a class="nav-item" id="nav-projects" onclick="showSection('projects')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0">
          <?php echo csrf_field(); ?>
          <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;text-align:right;font-family:inherit;font-size:inherit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
            </svg>تسجيل الخروج
          </button>
        </form>
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
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)">📅</div><div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div></div>
              <div class="stat-card" style="--sc:var(--green)"><div class="stat-icon" style="background:rgba(46,170,120,0.1)">🟢</div><div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">الحالية والقادمة</span></div></div>
              <div class="stat-card" style="--sc:var(--muted)"><div class="stat-icon" style="background:rgba(106,132,148,0.1)">📁</div><div><span class="stat-num" id="s-past">0</span><span class="stat-lbl">السابقة</span></div></div>
              <div class="stat-card" style="--sc:var(--red)"><div class="stat-icon" style="background:rgba(198,40,40,0.08)">🚫</div><div><span class="stat-num" id="s-canc">0</span><span class="stat-lbl">الملغاة</span></div></div>
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)">💻</div><div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div></div>
            </div>

            <!-- TOOLBAR -->
            <div class="toolbar">
              <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
                <input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="renderAll()">
              </div>
              <div class="tb-div"></div>
              <select class="filter-select" id="catFilter" onchange="renderAll()">
                <option value="">🗂 كل التصنيفات</option>
                <option value="خيرية">🤝 خيرية واجتماعية</option>
                <option value="ثقافية">📚 ثقافية وتعليمية</option>
                <option value="صحية">🌿 صحية وبيئية</option>
                <option value="رياضية">⚽ رياضية وشبابية</option>
                <option value="تنموية">📈 تنموية واقتصادية</option>
                <option value="دينية">🕌 دينية ودعوية</option>
              </select>
              <div class="tb-div"></div>
              <div class="chips">
                <div class="chip on" id="chip-all" onclick="setTypeF('all')">الكل</div>
                <div class="chip" id="chip-online" onclick="setTypeF('online')">💻 عن بعد</div>
                <div class="chip" id="chip-onsite" onclick="setTypeF('onsite')">📍 حضوري</div>
              </div>
            </div>

            <div class="sec-wrap">
              <div class="sec-header"><div class="sec-icon" style="background:rgba(42,184,208,0.1)">🟢</div><div class="sec-title">الاجتماعات الحالية والقادمة</div><span class="sec-count sc-current" id="bc-cur">0</span></div>
              <div class="meetings-grid" id="grid-cur"></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('past')"><div class="sec-icon" style="background:rgba(106,132,148,0.1)">📁</div><div class="sec-title">الاجتماعات السابقة</div><span class="sec-count sc-past" id="bc-past">0</span><div class="sec-toggle" id="tog-past"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6" /></svg></div></div>
              <div id="sec-past"><div class="compact-list" id="list-past"></div></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('canc')"><div class="sec-icon" style="background:rgba(198,40,40,0.08)">🚫</div><div class="sec-title">الاجتماعات الملغاة</div><span class="sec-count sc-cancelled" id="bc-canc">0</span><div class="sec-toggle" id="tog-canc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6" /></svg></div></div>
              <div id="sec-canc"><div class="compact-list" id="list-canc"></div></div>
            </div>

          </div>

          <!-- ── MEETINGS MODALS ── -->
          <div class="overlay" id="ov-create" onclick="bgClose(event,'ov-create')"><div class="modal" onclick="event.stopPropagation()"><div class="modal-hd"><div class="modal-hd-icon" id="mhd-icon">📅</div><div class="modal-hd-text"><div class="modal-hd-title" id="mhd-title">إنشاء اجتماع جديد</div><div class="modal-hd-sub" id="mhd-sub">أضف تفاصيل الاجتماع أدناه</div></div><button class="modal-close" onclick="closeOv('ov-create')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
          <div class="modal-body">
            <div class="fg"><label>عنوان الاجتماع <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span><input type="text" id="f-title" placeholder="مثال: اجتماع التخطيط الاستراتيجي"></div></div>
            <div class="row2">
              <div class="fg"><label>التصنيف <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span><select id="f-cat"><option value="">اختر التصنيف</option><option value="خيرية">🤝 خيرية واجتماعية</option><option value="ثقافية">📚 ثقافية وتعليمية</option><option value="صحية">🌿 صحية وبيئية</option><option value="رياضية">⚽ رياضية وشبابية</option><option value="تنموية">📈 تنموية واقتصادية</option><option value="دينية">🕌 دينية ودعوية</option></select></div></div>
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
          <div class="modal-ft"><button class="btn-cancel" onclick="closeOv('ov-create')">إلغاء</button><button class="btn-save" onclick="saveMeeting()"><span id="save-lbl">💾 حفظ الاجتماع</span></button></div>
          </div></div>

          <div class="overlay" id="ov-details" onclick="bgClose(event,'ov-details')"><div class="det-modal" onclick="event.stopPropagation()"><div class="det-banner"><div class="det-banner-bg" id="d-banner-bg"></div><div class="det-banner-pattern"></div><div class="det-banner-content"><div class="det-type-badge" id="d-type-badge"></div></div><button class="det-close" onclick="closeOv('ov-details')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div class="det-body"><div class="det-title" id="d-title"></div><div class="det-grid" id="d-grid"><div class="det-cell"><div class="det-cell-lbl">الفئة</div><div class="det-cell-val" id="d-cat"></div></div><div class="det-cell"><div class="det-cell-lbl">التاريخ</div><div class="det-cell-val" id="d-date"></div></div><div class="det-cell"><div class="det-cell-lbl">الوقت</div><div class="det-cell-val" id="d-time"></div></div><div class="det-cell" id="d-loc-cell"><div class="det-cell-lbl">المكان</div><div class="det-cell-val" id="d-loc"></div></div></div><div class="det-presenter"><div class="dp-av" id="d-av"></div><div><div class="dp-name" id="d-pname"></div><div class="dp-role">مقدم الاجتماع</div></div></div><div id="d-notes-wrap" style="display:none" class="det-block"><div class="det-block-lbl">ملاحظات</div><div class="det-notes" id="d-notes"></div></div><div id="d-report-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--green)">📋 تقرير الاجتماع</div><div class="det-report" id="d-report-content"></div></div><div id="d-cancel-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--red)">سبب الإلغاء</div><div class="det-cancel" id="d-cancel-reason"></div></div></div><div class="det-ft"><button class="btn-cancel" style="flex:1" onclick="closeOv('ov-details')">إغلاق</button><button class="btn-save" id="det-edit-btn" onclick="editFromDet()" style="flex:1">✏️ تعديل</button></div></div></div>

          <div class="overlay" id="ov-delete" onclick="bgClose(event,'ov-delete')"><div class="confirm-box" onclick="event.stopPropagation()"><div class="confirm-icon-wrap" style="background:rgba(229,57,53,0.1)">🗑️</div><div class="confirm-title">حذف الاجتماع نهائياً</div><div class="confirm-desc">هل أنت متأكد؟ سيتم حذف الاجتماع بشكل دائم<br>ولا يمكن التراجع عن هذا الإجراء.</div><div class="confirm-row"><button class="btn-cancel" style="flex:1" onclick="closeOv('ov-delete')">إلغاء</button><button class="btn-danger" onclick="doDelete()">حذف نهائياً</button></div></div></div>

          <div class="overlay" id="ov-cancel" onclick="bgClose(event,'ov-cancel')"><div class="cancel-reason-box" onclick="event.stopPropagation()"><div class="modal-hd"><div class="modal-hd-icon" style="background:rgba(198,40,40,0.1);border-color:rgba(198,40,40,0.2)">🚫</div><div class="modal-hd-text"><div class="modal-hd-title">إلغاء الاجتماع</div><div class="modal-hd-sub">أدخل سبب الإلغاء</div></div><button class="modal-close" onclick="closeOv('ov-cancel')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div class="modal-body"><div class="fg"><label>سبب الإلغاء <span class="req">*</span></label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span><textarea id="f-cancel-reason" placeholder="مثال: تعارض المواعيد، ظروف طارئة..." style="border-color:rgba(198,40,40,0.25)"></textarea></div></div></div><div class="modal-ft"><button class="btn-cancel" onclick="closeOv('ov-cancel')">تراجع</button><button class="btn-danger" onclick="doCancel()">🚫 تأكيد الإلغاء</button></div></div></div>
        </div>

        
        <div class="view" id="view-orders">
          <main>
            <div class="page-head">
              <div><h1>📋 صفحة الطلبات</h1><p>إدارة طلبات إنشاء الحسابات وعرض تصنيفات الجمعيات المسجلة</p></div>
            </div>
            <div class="stats-grid">
              <div class="stat-card"><div class="stat-label">إجمالي الطلبات</div><div class="stat-value">48</div><div class="stat-sub text-blue">↑ 12 هذا الشهر</div></div>
              <div class="stat-card"><div class="stat-label">قيد المراجعة</div><div class="stat-value text-yellow">12</div><div class="stat-sub text-yellow">تنتظر المعالجة</div></div>
              <div class="stat-card"><div class="stat-label">تمت الموافقة</div><div class="stat-value text-green">30</div><div class="stat-sub text-green">↑ 62.5% نسبة القبول</div></div>
              <div class="stat-card"><div class="stat-label">مرفوضة</div><div class="stat-value text-red">6</div><div class="stat-sub text-red">12.5% نسبة الرفض</div></div>
            </div>
            <div class="section-tabs">
              <button class="tab-btn active" onclick="switchTab('requests', this)">طلبات إنشاء الحساب</button>
              <button class="tab-btn" onclick="switchTab('associations', this)">تصنيفات الجمعيات</button>
            </div>
            <div class="tab-content active" id="tab-requests">
              <div class="table-toolbar">
                <div class="search-box"><span>🔍</span><input type="text" placeholder="بحث بالاسم أو البريد الإلكتروني..." onkeyup="filterTable(this.value)" /></div>
                <div class="filter-group">
                  <select onchange="filterByStatus(this.value)"><option value="">جميع الحالات</option><option value="pending">قيد المراجعة</option><option value="approved">موافق عليها</option><option value="review">مراجعة إضافية</option><option value="rejected">مرفوضة</option></select>
                  <select><option>آخر 30 يوم</option><option>آخر 7 أيام</option><option>هذا الشهر</option><option>كل الوقت</option></select>
                </div>
              </div>
              <div class="table-wrap"><table id="requestsTable"><thead><tr><th>#</th><th>مقدم الطلب</th><th>نوع الحساب</th><th>الجمعية</th><th>تاريخ الطلب</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody id="requestsTbody"></tbody></table></div>
            </div>
            <div class="tab-content" id="tab-associations">
              <div class="section-head"><h2>🗂️ تصنيفات الجمعيات</h2><button class="btn btn-primary">+ إضافة تصنيف</button></div>
              <div class="categories-grid" id="categoriesGrid"></div>
              <div class="section-head"><h2>🏢 الجمعيات المسجلة</h2><div class="filter-group"><button class="btn btn-ghost" onclick="filterAssocByCat('')" style="border-width:2px"><i class="fas fa-th-large"></i> جميع التصنيفات</button><select id="catFilter" onchange="filterAssoc()"><option value="">تصفية حسب...</option><option value="تعليمية">تعليمية</option><option value="خيرية">خيرية</option><option value="بيئية">بيئية</option><option value="صحية">صحية</option><option value="رياضية">رياضية</option><option value="ثقافية">ثقافية</option></select></div></div>
              <div class="assoc-list" id="assocList"></div>
            </div>
          </main>
          <!-- ORDERS MODAL -->
          <div class="modal-overlay" id="modal"><div class="modal"><div class="modal-head"><h3>تفاصيل الطلب</h3><button class="btn btn-ghost" onclick="closeModal()" style="padding:.3rem .7rem;">✕</button></div><div class="modal-body" id="modalBody"></div><div class="modal-footer"><button class="btn btn-ghost" onclick="closeModal()">إغلاق</button><button class="btn btn-danger" onclick="closeModal()">رفض</button><button class="btn btn-primary" onclick="closeModal()">✓ موافقة</button></div></div></div>
        </div>

        
        <div class="view" id="view-projects">
          <main class="main-content">
            <div class="ph">
              <div><h1>المشاريع المشتركة</h1><p>المشاريع تُوجَّه تلقائياً للجمعيات بناءً على تطابق التصنيف</p></div>
              <button class="btn-new" id="openNew"><i class="fa-solid fa-plus"></i> مشروع جديد</button>
            </div>
            <div class="stats" id="statsRow"></div>
            <div class="filter-row">
              <div class="dd-wrap" id="ddWrap"><button class="dd-btn" id="ddBtn" type="button"><span class="dd-left"><span class="emoji">🏢</span><span id="ddLabel">كل التصنيفات</span></span><i class="fa-solid fa-chevron-down chevron"></i></button><div class="dd-menu" id="ddMenu"></div></div>
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
          <div class="ov" id="ovNew"><div class="mbox"><div class="mhd"><h2><i class="fa-solid fa-plus" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>إنشاء مشروع مشترك</h2><button class="mcl" id="clNew"><i class="fa-solid fa-xmark"></i></button></div><form id="fNew"><div class="fg"><label>اسم المشروع</label><input id="nN" placeholder="أدخل اسم المشروع..." required></div><div class="fg"><label>تصنيف المشروع</label><select id="nD" required><option value="">— اختر التصنيف —</option><option value="خيرية">🧡 خيرية واجتماعية</option><option value="ثقافية">📚 ثقافية وتعليمية</option><option value="صحية">🌿 صحية وبيئية</option><option value="رياضية">🌍 رياضية وشبابية</option><option value="تنموية">📈 تنموية واقتصادية</option><option value="دينية">🕌 دينية ودعوية</option></select></div><div class="fg"><label>هدف المشروع</label><textarea id="nG" placeholder="اشرح هدف المشروع بوضوح..." required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="nS" required></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="nE" required></div></div><div class="fg"><label>حالة المشروع</label><select id="nSt"><option value="قيد الإعداد">قيد الإعداد والتخطيط</option><option value="مستمر">بدء التنفيذ الفعلي</option><option value="فكرة">فكرة وعصف ذهني</option></select></div><button type="submit" class="bsub"><i class="fa-solid fa-paper-plane"></i> حفظ المشروع</button></form></div></div>
          <div class="ov" id="ovEdit"><div class="mbox"><div class="mhd"><h2><i class="fa-regular fa-pen-to-square" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>تعديل وإضافة تقدم</h2><button class="mcl" id="clEdit"><i class="fa-solid fa-xmark"></i></button></div><form id="fEdit"><input type="hidden" id="eId"><div class="fg"><label>اسم المشروع</label><input id="eN" required></div><div class="fg"><label>الهدف / الوصف</label><textarea id="eG" rows="2" required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="eS"></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="eE"></div></div><div class="fg"><label>نسبة الإنجاز (%)</label><input type="number" id="eP" min="0" max="100"></div><div class="fg"><label>إضافة تقدم جديد للسجل</label><textarea id="eU" placeholder="اكتب آخر ما تم إنجازه..."></textarea></div><button type="submit" class="bsub">حفظ التحديث</button></form></div></div>
          <div class="ov" id="ovConfirm"><div class="mbox cbox"><div class="cico"><i class="fa-solid fa-triangle-exclamation"></i></div><h3 id="cTtl"></h3><p id="cMsg"></p><div class="cbtns"><button class="by" id="cY">تأكيد</button><button class="bn" id="cN">تراجع</button></div></div></div>
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

  <script src="<?php echo e(asset('js/consulting.js')); ?>"></script>
  <script src="<?php echo e(asset('js/meeting.js')); ?>"></script>
  <script src="<?php echo e(asset('js/orders.js')); ?>"></script>
  <script src="<?php echo e(asset('js/joint-projects.js')); ?>"></script>
  <script src="<?php echo e(asset('js/spa-nav.js')); ?>"></script>
</body>

</html><?php /**PATH /home/a-22/Tkamel_project/التعديلات المقترحة/tkamel/resources/views/consulting.blade.php ENDPATH**/ ?>