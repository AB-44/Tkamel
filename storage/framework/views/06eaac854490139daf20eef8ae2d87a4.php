<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تكامل — الاجتماعات</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/meeting.css')); ?>">
</head>

<body>
  <div class="layout">

    <aside class="sidebar">
      <div class="sb-logo">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="" onerror="this.style.display='none'">
        <span>تكامل</span>
      </div>
      <nav class="sb-nav">
        <div class="sb-section">الرئيسية</div>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1.5" />
            <rect x="14" y="3" width="7" height="7" rx="1.5" />
            <rect x="3" y="14" width="7" height="7" rx="1.5" />
            <rect x="14" y="14" width="7" height="7" rx="1.5" />
          </svg>لوحة التحكم</a>
        <div class="sb-section">إدارة الأنشطة</div>
        <a href="<?php echo e(route('meetings')); ?>" class="nav-item active" id="nav-meetings" onclick="openMeetingsPage()"><svg
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4M8 2v4M3 10h18" />
          </svg>الاجتماعات</a>
        <a href="<?php echo e(route('consulting')); ?>" class="nav-item" data-vol onclick="backToVolunteer()"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
          </svg>فرص التطوع<span class="nav-badge" id="nb-opps">0</span></a>
        <a href="<?php echo e(route('orders')); ?>" class="nav-item" onclick="showAdminRequests()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
          </svg>الطلبات<span class="nav-badge red" id="nb-reqs">0</span></a>
        <a href="<?php echo e(route('joint-projects')); ?>" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
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
          <button class="mobile-menu-btn" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <div>
            <div class="tb-title" id="topbar-title">إدارة الاجتماعات</div>
            <div class="tb-crumb">تكامل / <span id="topbar-crumb">إدارة الاجتماعات</span></div>
          </div>
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

        <!-- PAGE HEADER -->
        <div class="page-header">
          <div>
            <div class="ph-title">إدارة الاجتماعات</div>
            <div class="ph-sub">تنظيم ومتابعة اجتماعات الجمعيات المجتمعية</div>
          </div>
          <button class="btn-create" onclick="openCreate()">
            <div class="btn-create-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg>
            </div>
            إنشاء اجتماع
          </button>
        </div>

        <!-- STATS -->
        <div class="stats-row">
          <div class="stat-card" style="--sc:var(--teal-glow)">
            <div class="stat-icon" style="background:rgba(42,184,208,0.1)">📅</div>
            <div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div>
          </div>
          <div class="stat-card" style="--sc:var(--green)">
            <div class="stat-icon" style="background:rgba(46,170,120,0.1)">🟢</div>
            <div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">الحالية والقادمة</span></div>
          </div>
          <div class="stat-card" style="--sc:var(--muted)">
            <div class="stat-icon" style="background:rgba(106,132,148,0.1)">📁</div>
            <div><span class="stat-num" id="s-past">0</span><span class="stat-lbl">السابقة</span></div>
          </div>
          <div class="stat-card" style="--sc:var(--red)">
            <div class="stat-icon" style="background:rgba(198,40,40,0.08)">🚫</div>
            <div><span class="stat-num" id="s-canc">0</span><span class="stat-lbl">الملغاة</span></div>
          </div>
          <div class="stat-card" style="--sc:var(--teal-glow)">
            <div class="stat-icon" style="background:rgba(42,184,208,0.1)">💻</div>
            <div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div>
          </div>
        </div>

        <!-- TOOLBAR -->
        <div class="toolbar">
          <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..."
              oninput="renderAll()">
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

        <!-- CURRENT — full cards -->
        <div class="sec-wrap">
          <div class="sec-header">
            <div class="sec-icon" style="background:rgba(42,184,208,0.1)">🟢</div>
            <div class="sec-title">الاجتماعات الحالية والقادمة</div>
            <span class="sec-count sc-current" id="bc-cur">0</span>
          </div>
          <div class="meetings-grid" id="grid-cur"></div>
        </div>

        <!-- PAST — compact collapsible -->
        <div class="sec-wrap">
          <div class="sec-header collapsible" onclick="toggleSec('past')">
            <div class="sec-icon" style="background:rgba(106,132,148,0.1)">📁</div>
            <div class="sec-title">الاجتماعات السابقة</div>
            <span class="sec-count sc-past" id="bc-past">0</span>
            <div class="sec-toggle" id="tog-past">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </div>
          </div>
          <div id="sec-past">
            <div class="compact-list" id="list-past"></div>
          </div>
        </div>

        <!-- CANCELLED — compact collapsible -->
        <div class="sec-wrap">
          <div class="sec-header collapsible" onclick="toggleSec('canc')">
            <div class="sec-icon" style="background:rgba(198,40,40,0.08)">🚫</div>
            <div class="sec-title">الاجتماعات الملغاة</div>
            <span class="sec-count sc-cancelled" id="bc-canc">0</span>
            <div class="sec-toggle" id="tog-canc">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12">
                <path d="M6 9l6 6 6-6" />
              </svg>
            </div>
          </div>
          <div id="sec-canc">
            <div class="compact-list" id="list-canc"></div>
          </div>
        </div>

      </div><!-- /content -->
    </div><!-- /layout -->

    <!-- ══ CREATE / EDIT MODAL ══ -->
    <div class="overlay" id="ov-create" onclick="bgClose(event,'ov-create')">
      <div class="modal" onclick="event.stopPropagation()">
        <div class="modal-hd">
          <div class="modal-hd-icon" id="mhd-icon">📅</div>
          <div class="modal-hd-text">
            <div class="modal-hd-title" id="mhd-title">إنشاء اجتماع جديد</div>
            <div class="modal-hd-sub" id="mhd-sub">أضف تفاصيل الاجتماع أدناه</div>
          </div>
          <button class="modal-close" onclick="closeOv('ov-create')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <!-- Title -->
          <div class="fg">
            <label>عنوان الاجتماع <span class="req">*</span></label>
            <div class="fld">
              <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  width="15" height="15">
                  <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                  <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg></span>
              <input type="text" id="f-title" placeholder="مثال: اجتماع التخطيط الاستراتيجي">
            </div>
          </div>

          <!-- Category & Presenter -->
          <div class="row2">
            <div class="fg">
              <label>التصنيف <span class="req">*</span></label>
              <div class="fld">
                <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    width="15" height="15">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                    <line x1="7" y1="7" x2="7.01" y2="7" />
                  </svg></span>
                <select id="f-cat">
                  <option value="">اختر التصنيف</option>
                  <option value="خيرية">🤝 خيرية واجتماعية</option>
                  <option value="ثقافية">📚 ثقافية وتعليمية</option>
                  <option value="صحية">🌿 صحية وبيئية</option>
                  <option value="رياضية">⚽ رياضية وشبابية</option>
                  <option value="تنموية">📈 تنموية واقتصادية</option>
                  <option value="دينية">🕌 دينية ودعوية</option>
                </select>
              </div>
            </div>
            <div class="fg">
              <label>اسم المقدم <span class="req">*</span></label>
              <div class="fld">
                <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    width="15" height="15">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                  </svg></span>
                <input type="text" id="f-presenter" placeholder="اسم المقدم">
              </div>
            </div>
          </div>

          <!-- Date & Time -->
          <div class="row2">
            <div class="fg">
              <label>التاريخ <span class="req">*</span></label>
              <div class="fld">
                <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    width="15" height="15">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <path d="M16 2v4M8 2v4M3 10h18" />
                  </svg></span>
                <input type="date" id="f-date">
              </div>
            </div>
            <div class="fg">
              <label>الوقت</label>
              <div class="fld">
                <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    width="15" height="15">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                  </svg></span>
                <input type="time" id="f-time">
              </div>
            </div>
          </div>

          <div class="form-divider">نوع الاجتماع</div>

          <!-- Type Toggle -->
          <div class="fg">
            <label>نوع الاجتماع <span class="req">*</span></label>
            <div class="type-toggle">
              <button class="type-btn" id="tb-online" onclick="setMType('online')" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                  <rect x="2" y="3" width="20" height="14" rx="2" />
                  <line x1="8" y1="21" x2="16" y2="21" />
                  <line x1="12" y1="17" x2="12" y2="21" />
                </svg>
                عن بعد
              </button>
              <button class="type-btn" id="tb-onsite" onclick="setMType('onsite')" type="button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                حضوري
              </button>
            </div>
          </div>

          <!-- Link field (online) -->
          <div class="fg" id="fg-link">
            <label>رابط الاجتماع</label>
            <div class="fld link-copy-wrap">
              <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  width="15" height="15">
                  <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" />
                  <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" />
                </svg></span>
              <input type="url" id="f-link" placeholder="https://meet.google.com/xxx-xxxx-xxx" dir="ltr"
                style="text-align:right;padding-left:76px">
              <button class="link-copy-btn" type="button" onclick="copyLink()">نسخ</button>
            </div>
          </div>

          <!-- Location field (onsite) -->
          <div class="fg" id="fg-location" style="display:none">
            <label>المكان</label>
            <div class="fld">
              <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  width="15" height="15">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg></span>
              <input type="text" id="f-location" placeholder="مثال: قاعة الاجتماعات الرئيسية — الرياض">
            </div>
          </div>

          <!-- Notes -->
          <div class="fg">
            <label>ملاحظات</label>
            <div class="fld">
              <span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  width="15" height="15">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                  <polyline points="14 2 14 8 20 8" />
                </svg></span>
              <textarea id="f-notes" placeholder="أضف أي ملاحظات أو تفاصيل إضافية..."></textarea>
            </div>
          </div>

          <!-- REPORT SECTION — shown only when editing a past meeting -->
          <div id="report-section" class="report-section" style="display:none">
            <div class="report-section-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
                <polyline points="10 9 9 9 8 9" />
              </svg>
              تقرير الاجتماع
            </div>
            <div class="fg" style="margin-bottom:12px">
              <label>ملخص ما تم مناقشته</label>
              <div class="fld">
                <span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    width="15" height="15">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                  </svg></span>
                <textarea id="f-report-summary" placeholder="اكتب ملخصاً لما تمت مناقشته في الاجتماع..."
                  style="min-height:90px"></textarea>
              </div>
            </div>
            <div class="fg" style="margin-bottom:12px">
              <label>القرارات المتخذة</label>
              <div class="fld">
                <span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    width="15" height="15">
                    <polyline points="9 11 12 14 22 4" />
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                  </svg></span>
                <textarea id="f-report-decisions" placeholder="اذكر القرارات الرئيسية التي تم اتخاذها..."
                  style="min-height:80px"></textarea>
              </div>
            </div>
            <div class="row2">
              <div class="fg" style="margin-bottom:0">
                <label>عدد الحضور</label>
                <div class="fld">
                  <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                      width="15" height="15">
                      <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                      <circle cx="9" cy="7" r="4" />
                      <path d="M23 21v-2a4 4 0 00-3-3.87" />
                    </svg></span>
                  <input type="number" id="f-report-attendees" placeholder="مثال: 12" min="0">
                </div>
              </div>
              <div class="fg" style="margin-bottom:0">
                <label>الإجراءات التالية</label>
                <div class="fld">
                  <span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                      width="15" height="15">
                      <polyline points="9 18 15 12 9 6" />
                    </svg></span>
                  <input type="text" id="f-report-actions" placeholder="مثال: اجتماع متابعة في مارس">
                </div>
              </div>
            </div>
          </div>
        </div><!-- /modal-body -->

        <div class="modal-ft">
          <button class="btn-cancel" onclick="closeOv('ov-create')">إلغاء</button>
          <button class="btn-save" onclick="saveMeeting()"><span id="save-lbl">💾 حفظ الاجتماع</span></button>
        </div>
      </div>
    </div>

    <!-- ══ DETAILS MODAL ══ -->
    <div class="overlay" id="ov-details" onclick="bgClose(event,'ov-details')">
      <div class="det-modal" onclick="event.stopPropagation()">
        <div class="det-banner">
          <div class="det-banner-bg" id="d-banner-bg"></div>
          <div class="det-banner-pattern"></div>
          <div class="det-banner-content">
            <div class="det-type-badge" id="d-type-badge"></div>
          </div>
          <button class="det-close" onclick="closeOv('ov-details')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>
        <div class="det-body">
          <div class="det-title" id="d-title"></div>
          <div class="det-grid" id="d-grid">
            <div class="det-cell">
              <div class="det-cell-lbl">الفئة</div>
              <div class="det-cell-val" id="d-cat"></div>
            </div>
            <div class="det-cell">
              <div class="det-cell-lbl">التاريخ</div>
              <div class="det-cell-val" id="d-date"></div>
            </div>
            <div class="det-cell">
              <div class="det-cell-lbl">الوقت</div>
              <div class="det-cell-val" id="d-time"></div>
            </div>
            <div class="det-cell" id="d-loc-cell">
              <div class="det-cell-lbl">المكان</div>
              <div class="det-cell-val" id="d-loc"></div>
            </div>
          </div>
          <div class="det-presenter">
            <div class="dp-av" id="d-av"></div>
            <div>
              <div class="dp-name" id="d-pname"></div>
              <div class="dp-role">مقدم الاجتماع</div>
            </div>
          </div>
          <div id="d-notes-wrap" style="display:none" class="det-block">
            <div class="det-block-lbl">ملاحظات</div>
            <div class="det-notes" id="d-notes"></div>
          </div>
          <div id="d-report-wrap" style="display:none" class="det-block">
            <div class="det-block-lbl" style="color:var(--green)">📋 تقرير الاجتماع</div>
            <div class="det-report" id="d-report-content"></div>
          </div>
          <div id="d-cancel-wrap" style="display:none" class="det-block">
            <div class="det-block-lbl" style="color:var(--red)">سبب الإلغاء</div>
            <div class="det-cancel" id="d-cancel-reason"></div>
          </div>
        </div>
        <div class="det-ft">
          <button class="btn-cancel" style="flex:1" onclick="closeOv('ov-details')">إغلاق</button>
          <button class="btn-save" id="det-edit-btn" onclick="editFromDet()" style="flex:1">✏️ تعديل</button>
        </div>
      </div>
    </div>

    <!-- ══ DELETE CONFIRM ══ -->
    <div class="overlay" id="ov-delete" onclick="bgClose(event,'ov-delete')">
      <div class="confirm-box" onclick="event.stopPropagation()">
        <div class="confirm-icon-wrap" style="background:rgba(229,57,53,0.1)">🗑️</div>
        <div class="confirm-title">حذف الاجتماع نهائياً</div>
        <div class="confirm-desc">هل أنت متأكد؟ سيتم حذف الاجتماع بشكل دائم<br>ولا يمكن التراجع عن هذا الإجراء.</div>
        <div class="confirm-row">
          <button class="btn-cancel" style="flex:1" onclick="closeOv('ov-delete')">إلغاء</button>
          <button class="btn-danger" onclick="doDelete()">حذف نهائياً</button>
        </div>
      </div>
    </div>

    <!-- ══ CANCEL REASON MODAL ══ -->
    <div class="overlay" id="ov-cancel" onclick="bgClose(event,'ov-cancel')">
      <div class="cancel-reason-box" onclick="event.stopPropagation()">
        <div class="modal-hd">
          <div class="modal-hd-icon" style="background:rgba(198,40,40,0.1);border-color:rgba(198,40,40,0.2)">🚫</div>
          <div class="modal-hd-text">
            <div class="modal-hd-title">إلغاء الاجتماع</div>
            <div class="modal-hd-sub">أدخل سبب الإلغاء</div>
          </div>
          <button class="modal-close" onclick="closeOv('ov-cancel')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>
        <div class="modal-body">
          <div class="fg">
            <label>سبب الإلغاء <span class="req">*</span></label>
            <div class="fld">
              <span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  width="15" height="15">
                  <circle cx="12" cy="12" r="10" />
                  <line x1="12" y1="8" x2="12" y2="12" />
                  <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg></span>
              <textarea id="f-cancel-reason" placeholder="مثال: تعارض المواعيد، ظروف طارئة..."
                style="border-color:rgba(198,40,40,0.25)"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-ft">
          <button class="btn-cancel" onclick="closeOv('ov-cancel')">تراجع</button>
          <button class="btn-danger" onclick="doCancel()">🚫 تأكيد الإلغاء</button>
        </div>
      </div>
    </div>

    <!-- ══ TOAST ══ -->
    <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

    <script src="<?php echo e(asset('js/menu.js')); ?>"></script>
    <script src="<?php echo e(asset('js/meeting.js')); ?>"></script>
</body>

</html><?php /**PATH /home/a-22/Tkamel_project/المشروع الحالي/Tkamel/tkamel/resources/views/meetings.blade.php ENDPATH**/ ?>