<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تكامل | المشاريع المشتركة</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/joint-projects.css') }}">
  
<style>
  #nb-reqs:empty{display:none!important}
  .cat-picker-wrap { display:flex; flex-wrap:wrap; gap:8px; padding:10px 12px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; min-height:48px; transition: border-color .18s; }
  .cat-picker-wrap:focus-within { border-color:#0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,0.12); }
  .cat-pill { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:50px; border:1.5px solid rgba(0,0,0,0.09); background:#fff; cursor:pointer; font-family:'Tajawal',sans-serif; font-size:0.83rem; font-weight:600; color:#475569; transition:all 0.18s ease; user-select:none; box-shadow:0 1px 3px rgba(0,0,0,0.04); }
  .cat-pill:hover { border-color:rgba(13,148,136,0.4); background:rgba(13,148,136,0.06); color:#0d9488; transform:translateY(-1px); box-shadow:0 4px 10px rgba(13,148,136,0.12); }
  .cat-pill.active { background:linear-gradient(135deg,#0d9488,#0891b2); border-color:transparent; color:#fff; box-shadow:0 4px 14px rgba(13,148,136,0.35); transform:translateY(-1px); }
  .cat-pill-all.active { background:linear-gradient(135deg,#1e40af,#0d9488); box-shadow:0 4px 14px rgba(30,64,175,0.3); }
  .cat-pill-ico { font-size:1rem; line-height:1; }
  .cat-pill-lbl { white-space:nowrap; }
</style>
</head>

<body>

  <aside class="sidebar">
    <div class="sb-logo">
      <img src="{{ asset('images/logo.png') }}" alt="" onerror="this.style.display='none'">
      <span>تكامل</span>
    </div>
    <nav class="sb-nav">
      <div class="sb-section">الرئيسية</div>
      <a href="{{ route('dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2">
          <rect x="3" y="3" width="7" height="7" rx="1.5" />
          <rect x="14" y="3" width="7" height="7" rx="1.5" />
          <rect x="3" y="14" width="7" height="7" rx="1.5" />
          <rect x="14" y="14" width="7" height="7" rx="1.5" />
        </svg>لوحة التحكم</a>
      <div class="sb-section">إدارة الأنشطة</div>
      <a href="{{ route('meetings') }}" class="nav-item" id="nav-meetings" onclick="openMeetingsPage()"><svg
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" />
          <path d="M16 2v4M8 2v4M3 10h18" />
        </svg>الاجتماعات</a>
      <a href="{{ route('consulting') }}" class="nav-item" data-vol onclick="backToVolunteer()"><svg
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
        </svg>فرص التطوع</a>
      <a href="{{ route('orders') }}" class="nav-item" onclick="showAdminRequests()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2">
          <path d="M9 11l3 3L22 4" />
          <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
        </svg>الطلبات<span class="nav-badge red" id="nb-reqs"></span></a>
      <a href="{{ route('joint-projects') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </svg>المشاريع</a>
      <div class="sb-section">خدمات مبادرون</div>
      <div class="nav-parent" id="np-services" onclick="toggleSubmenu('services')">
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
        <div class="tb-title" id="topbar-title">المشاريع المشتركة</div>
        <div class="tb-crumb">تكامل / <span id="topbar-crumb">المشاريع المشتركة</span></div>
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

    <main class="main-content">
      <div class="ph">
        <div>
          <h1>المشاريع المشتركة</h1>
          <p>المشاريع تُوجَّه تلقائياً للجمعيات بناءً على تطابق التصنيف</p>
        </div>
        <button class="btn-primary" id="openNew" style="margin-right:auto; padding: 10px 24px;">
          <div class="btn-icon-wrap" style="background:rgba(255,255,255,0.22);color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-weight:bold;margin-left:8px">+</div>
          مشروع جديد
        </button>
      </div>

      <div class="stats" id="statsRow"></div>

      <!-- Tabs -->
      <div class="tabs" style="margin-bottom:16px;">
        <button class="tab on" data-t="tab-active">
          <i class="fa-solid fa-rocket"></i>الحالية<span class="n" id="n-active">0</span>
        </button>
        <button class="tab" data-t="tab-done">
          <i class="fa-solid fa-circle-check"></i>المنتهية<span class="n" id="n-done">0</span>
        </button>
        <button class="tab" data-t="tab-canceled">
          <i class="fa-solid fa-ban"></i>الملغاة<span class="n" id="n-canceled">0</span>
        </button>
      </div>

      <!-- Filter Row -->
      <div class="filter-row">
        <!-- Category Dropdown -->
        <div class="dd-wrap" id="ddWrap">
          <button class="dd-btn" id="ddBtn" type="button">
            <span class="dd-left">
              <span class="emoji"><i class="fa-solid fa-building"></i></span>
              <span id="ddLabel">كل التصنيفات</span>
            </span>
            <i class="fa-solid fa-chevron-down chevron"></i>
          </button>
          <div class="dd-menu" id="ddMenu"></div>
        </div>
        <!-- Search -->
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" class="sinput" id="searchQ" placeholder="ابحث باسم المشروع...">
        </div>
        <span class="res-badge" id="resBadge"><span id="resNum">0</span> مشروع</span>
      </div>

      <div id="tab-active" class="pane on grid"></div>
      <div id="tab-done" class="pane grid"></div>
      <div id="tab-canceled" class="pane grid"></div>
    </main>

    <!-- New Project Modal -->
    <div class="ov" id="ovNew">
      <div class="mbox">
        <div class="mhd">
          <h2><i class="fa-solid fa-plus" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>إنشاء مشروع
            مشترك
          </h2>
          <button class="mcl" id="clNew"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="fNew">
          <div class="fg"><label>اسم المشروع</label><input id="nN" placeholder="أدخل اسم المشروع..." required></div>
          <div class="fg">
            <label>تصنيف المشروع</label>
            <div id="nD-picker"></div>
            <input type="hidden" id="nD" required />
          </div>
          <div class="fg"><label>هدف المشروع</label><textarea id="nG" placeholder="اشرح هدف المشروع بوضوح..."
              required></textarea></div>
          <div class="frow">
            <div class="fg"><label>تاريخ البدء</label><input type="date" id="nS" required></div>
            <div class="fg"><label>تاريخ النهاية</label><input type="date" id="nE" required></div>
          </div>
          <div class="fg">
            <label>حالة المشروع</label>
            <select id="nSt">
              <option value="planning">قيد الإعداد والتخطيط</option>
              <option value="active">بدء التنفيذ الفعلي</option>
              <option value="idea">فكرة وعصف ذهني</option>
            </select>
          </div>
          <button type="submit" class="bsub"><i class="fa-solid fa-paper-plane"></i> حفظ المشروع</button>
        </form>
      </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="ov" id="ovEdit">
      <div class="mbox">
        <div class="mhd">
          <h2><i class="fa-regular fa-pen-to-square" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>تعديل
            وإضافة تقدم</h2>
          <button class="mcl" id="clEdit"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="fEdit">
          <input type="hidden" id="eId">
          <div class="fg"><label>اسم المشروع</label><input id="eN" required></div>
          <div class="fg">
            <label>تصنيف المشروع</label>
            <div id="eDCat-picker"></div>
            <input type="hidden" id="eDCat" />
          </div>
          <div class="fg"><label>الهدف / الوصف</label><textarea id="eG" rows="2" required></textarea></div>
          <div class="frow">
            <div class="fg"><label>تاريخ البدء</label><input type="date" id="eS"></div>
            <div class="fg"><label>تاريخ النهاية</label><input type="date" id="eE"></div>
          </div>
          <div class="fg"><label>نسبة الإنجاز (%)</label><input type="number" id="eP" min="0" max="100"></div>
          <div class="fg">
            <label>حالة المشروع</label>
            <select id="eSt">
              <option value="planning">قيد الإعداد</option>
              <option value="active">مستمر</option>
              <option value="idea">فكرة</option>
              <option value="completed">مكتمل</option>
              <option value="canceled">ملغى</option>
            </select>
          </div>
          <div class="fg"><label>إضافة تقدم جديد للسجل</label><textarea id="eU"
              placeholder="اكتب آخر ما تم إنجازه..."></textarea></div>
          <button type="submit" class="bsub">حفظ التحديث</button>
        </form>
      </div>
    </div>

    <!-- Confirm Modal -->
    <div class="ov" id="ovConfirm">
      <div class="mbox cbox">
        <div class="cico"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h3 id="cTtl"></h3>
        <p id="cMsg"></p>
        <div class="cbtns">
          <button class="by" id="cY">تأكيد</button>
          <button class="bn" id="cN">تراجع</button>
        </div>
      </div>
    </div>

    <script src="{{ asset('js/cat-picker.js') }}"></script>
    <script src="{{ asset('js/joint-projects.js') }}"></script>
</body>

</html>