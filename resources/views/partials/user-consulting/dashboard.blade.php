{{--
  partials/user-consulting/dashboard.blade.php
  Dashboard section for the user SPA — populated dynamically via /api/user/dashboard
  (see public/js/user-dashboard-spa.js)
--}}
<div class="view" id="view-dashboard">

  <!-- WELCOME BANNER -->
  <div class="welcome-banner" id="welcome-banner">
    <div class="wb-pattern"></div>
    <div class="wb-glow"></div>
    <div class="wb-text">
      <div class="wb-greeting" id="wb-greeting">
        {{ (date('H') < 12) ? 'صباح الخير 👋' : ((date('H') < 17) ? 'مساء الخير 👋' : 'أهلاً وسهلاً 👋') }}
      </div>
      <div class="wb-name">{{ Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم' }}</div>
      <div class="wb-sub">إليك ملخص نشاطك على منصة <strong>تكامل</strong> لهذا اليوم</div>
    </div>
    <div class="wb-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
  </div>

  <!-- STAT CARDS -->
  <div class="stats-grid" id="stats-grid">
    <div class="stat-card" style="--sa:var(--teal-glow)">
      <div class="stat-ico-wrap" style="background:rgba(14,165,201,.1)">📋</div>
      <div class="stat-info">
        <div class="stat-num counter" id="ud-total-requests" data-target="0">0</div>
        <div class="stat-lbl">إجمالي طلباتي</div>
      </div>
      <div class="stat-trend up"><i class="fa-solid fa-paper-plane fa-xs"></i></div>
    </div>
    <div class="stat-card" style="--sa:#f59e0b">
      <div class="stat-ico-wrap" style="background:rgba(245,158,11,.1)">⏳</div>
      <div class="stat-info">
        <div class="stat-num counter" id="ud-pending-requests" data-target="0">0</div>
        <div class="stat-lbl">قيد المراجعة</div>
      </div>
      <div class="stat-trend" style="color:#f59e0b"><i class="fa-solid fa-clock fa-xs"></i></div>
    </div>
    <div class="stat-card" style="--sa:var(--green)">
      <div class="stat-ico-wrap" style="background:rgba(13,148,136,.1)">✅</div>
      <div class="stat-info">
        <div class="stat-num counter" id="ud-approved-requests" data-target="0">0</div>
        <div class="stat-lbl">مقبولة</div>
      </div>
      <div class="stat-trend up" style="color:var(--green)"><i class="fa-solid fa-check fa-xs"></i></div>
    </div>
    <div class="stat-card" style="--sa:#6366f1">
      <div class="stat-ico-wrap" style="background:rgba(99,102,241,.1)">🗂️</div>
      <div class="stat-info">
        <div class="stat-num counter" id="ud-projects-count" data-target="0">0</div>
        <div class="stat-lbl">مشاريع نشطة</div>
      </div>
      <div class="stat-trend up" style="color:#6366f1"><i class="fa-solid fa-diagram-project fa-xs"></i></div>
    </div>
    <div class="stat-card" style="--sa:var(--teal)">
      <div class="stat-ico-wrap" style="background:rgba(12,96,128,.1)">📅</div>
      <div class="stat-info">
        <div class="stat-num counter" id="ud-upcoming-meetings" data-target="0">0</div>
        <div class="stat-lbl">اجتماعات قادمة</div>
      </div>
      <div class="stat-trend up"><i class="fa-solid fa-calendar fa-xs"></i></div>
    </div>
    <div class="stat-card" style="--sa:var(--green)">
      <div class="stat-ico-wrap" style="background:rgba(46,170,120,.1)">🤝</div>
      <div class="stat-info">
        <div class="stat-num counter" id="ud-opportunities-count" data-target="0">0</div>
        <div class="stat-lbl">فرص التطوع المتاحة</div>
      </div>
      <div class="stat-trend up" style="color:var(--green)"><i class="fa-solid fa-hand-holding-heart fa-xs"></i>
      </div>
    </div>
  </div>

  <!-- ROW 1: طلباتي + الاجتماعات القادمة -->
  <div class="dash-row">

    <!-- طلبات الأخيرة -->
    <div class="dash-card large">
      <div class="dc-hd">
        <div class="dc-hd-left">
          <div class="dc-icon" style="background:rgba(99,102,241,.1)">
            <i class="fa-solid fa-paper-plane" style="color:#6366f1"></i>
          </div>
          <div>
            <div class="dc-title">آخر طلباتي</div>
            <div class="dc-sub">أحدث طلبات التقديم المُرسَلة</div>
          </div>
        </div>
        <a href="#orders" onclick="if(typeof showSection==='function'){showSection('orders');return false;}" class="dc-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="dc-body" id="recent-reqs">
        <div class="dc-empty"><i class="fa-solid fa-paper-plane"></i><p>جاري التحميل...</p></div>
      </div>
    </div>

    <!-- الاجتماعات القادمة -->
    <div class="dash-card small">
      <div class="dc-hd">
        <div class="dc-hd-left">
          <div class="dc-icon" style="background:rgba(14,165,201,.1)">
            <i class="fa-solid fa-calendar-days" style="color:var(--teal)"></i>
          </div>
          <div>
            <div class="dc-title">الاجتماعات القادمة</div>
            <div class="dc-sub">أقرب الاجتماعات الموجودة</div>
          </div>
        </div>
        <a href="#meetings" onclick="if(typeof showSection==='function'){showSection('meetings');return false;}" class="dc-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="dc-body" id="upcoming-meets">
        <div class="dc-empty"><i class="fa-solid fa-calendar"></i><p>جاري التحميل...</p></div>
      </div>
    </div>

  </div><!-- /row1 -->

  <!-- ROW 2: فرص التطوع + المشاريع النشطة -->
  <div class="dash-row">

    <!-- فرص التطوع -->
    <div class="dash-card small">
      <div class="dc-hd">
        <div class="dc-hd-left">
          <div class="dc-icon" style="background:rgba(46,170,120,.1)">
            <i class="fa-solid fa-hand-holding-heart" style="color:var(--green)"></i>
          </div>
          <div>
            <div class="dc-title">فرص التطوع المتاحة</div>
            <div class="dc-sub">فرص مفتوحة تطابق تصنيفك</div>
          </div>
        </div>
        <a href="#volunteer" onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}" class="dc-link">تصفح الكل <i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="dc-body" id="vol-opps">
        <div class="dc-empty"><i class="fa-solid fa-hand-holding-heart"></i><p>جاري التحميل...</p></div>
      </div>
    </div>

    <!-- المشاريع النشطة -->
    <div class="dash-card large">
      <div class="dc-hd">
        <div class="dc-hd-left">
          <div class="dc-icon" style="background:rgba(245,158,11,.1)">
            <i class="fa-solid fa-diagram-project" style="color:var(--amber)"></i>
          </div>
          <div>
            <div class="dc-title">المشاريع المشتركة النشطة</div>
            <div class="dc-sub">المشاريع المفتوحة للمشاركة</div>
          </div>
        </div>
        <a href="#projects" onclick="if(typeof showSection==='function'){showSection('projects');return false;}" class="dc-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
      </div>
      <div class="dc-body" id="active-projs">
        <div class="dc-empty"><i class="fa-solid fa-diagram-project"></i><p>جاري التحميل...</p></div>
      </div>
    </div>

  </div><!-- /row2 -->

  <!-- ROW 3: تقدم طلباتي + نشاطي -->
  <div class="dash-row">

    <!-- حالة طلباتي (دونت) -->
    <div class="dash-card small">
      <div class="dc-hd">
        <div class="dc-hd-left">
          <div class="dc-icon" style="background:rgba(14,165,201,.08)">
            <i class="fa-solid fa-chart-pie" style="color:var(--teal-glow)"></i>
          </div>
          <div>
            <div class="dc-title">حالة طلباتي</div>
            <div class="dc-sub">توزيع حالات جميع الطلبات</div>
          </div>
        </div>
      </div>
      <div class="dc-body" id="reqs-status">
        <!-- سيتم توليده بالجافاسكربت -->
      </div>
    </div>

    <!-- نشاط سريع / اختصارات -->
    <div class="dash-card large">
      <div class="dc-hd">
        <div class="dc-hd-left">
          <div class="dc-icon" style="background:rgba(251,191,36,.1)">
            <i class="fa-solid fa-bolt" style="color:#fbbf24"></i>
          </div>
          <div>
            <div class="dc-title">وصول سريع</div>
            <div class="dc-sub">الأدوات والصفحات الأكثر استخداماً</div>
          </div>
        </div>
      </div>
      <div class="dc-body shortcuts-grid">
        <a href="#volunteer" onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}" class="shortcut-card">
          <div class="sc-emoji">🤝</div>
          <div class="sc-lbl">فرص التطوع</div>
        </a>
        <a href="#projects" onclick="if(typeof showSection==='function'){showSection('projects');return false;}" class="shortcut-card">
          <div class="sc-emoji">🗂️</div>
          <div class="sc-lbl">المشاريع المشتركة</div>
        </a>
        <a href="#meetings" onclick="if(typeof showSection==='function'){showSection('meetings');return false;}" class="shortcut-card">
          <div class="sc-emoji">📅</div>
          <div class="sc-lbl">الاجتماعات</div>
        </a>
        <a href="#orders" onclick="if(typeof showSection==='function'){showSection('orders');return false;}" class="shortcut-card">
          <div class="sc-emoji">📋</div>
          <div class="sc-lbl">طلباتي</div>
        </a>
        <a href="#services" onclick="if(typeof showSection==='function'){showSection('services');return false;}" class="shortcut-card">
          <div class="sc-emoji">⭐</div>
          <div class="sc-lbl">خدمات مبادرون</div>
        </a>
        <a href="#settings" onclick="if(typeof showSection==='function'){showSection('settings');return false;}" class="shortcut-card">
          <div class="sc-emoji">👤</div>
          <div class="sc-lbl">ملفي الشخصي</div>
        </a>
      </div>
    </div>

  </div><!-- /row3 -->

</div><!-- /view-dashboard -->
