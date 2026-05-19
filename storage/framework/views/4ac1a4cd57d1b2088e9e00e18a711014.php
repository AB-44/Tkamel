
<?php
  $nav = $activeNav ?? '';
?>

<style>
/* ═══════════════════════════════════════════════════════════
   SIDEBAR-ADMIN — Premium Glassmorphic Design System
   Using "aside.sidebar" prefix for high specificity to prevent
   any external CSS (meetings.css, dashboard.css, etc.) bleeding in.
   ═══════════════════════════════════════════════════════════ */

aside.sidebar {
  width: 264px !important;
  flex-shrink: 0;
  background: linear-gradient(175deg, #071c2d 0%, #0a2a40 40%, #0c3350 100%) !important;
  display: flex !important;
  flex-direction: column !important;
  position: fixed !important;
  top: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  z-index: 50 !important;
  border-left: 1px solid rgba(14,165,201,0.12);
  box-shadow: -6px 0 40px rgba(0,0,0,0.3);
  overflow: hidden;
}

/* Ambient glow layers */
aside.sidebar::before {
  content: '';
  position: absolute;
  top: -80px;
  right: -60px;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(14,165,201,0.14) 0%, transparent 65%) !important;
  pointer-events: none;
  z-index: 0;
}
aside.sidebar::after {
  content: '';
  position: absolute;
  bottom: -60px;
  left: -40px;
  width: 240px;
  height: 240px;
  background: radial-gradient(circle, rgba(13,148,136,0.12) 0%, transparent 65%) !important;
  pointer-events: none;
  z-index: 0;
}

/* ── LOGO ── */
aside.sidebar .sb-logo {
  padding: 22px 20px 18px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  position: relative;
  z-index: 1;
  flex-shrink: 0;
}

aside.sidebar .sb-logo-mark {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0ea5c9 0%, #0d9488 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.15rem;
  font-weight: 900;
  color: white;
  box-shadow: 0 4px 16px rgba(14,165,201,0.45);
  flex-shrink: 0;
  font-family: 'Tajawal', sans-serif;
}

aside.sidebar .sb-logo-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

aside.sidebar .sb-logo-name {
  font-size: 1.18rem;
  font-weight: 900;
  color: white;
  letter-spacing: -0.5px;
  line-height: 1;
  font-family: 'Tajawal', sans-serif;
}

aside.sidebar .sb-logo-sub {
  font-size: 0.62rem;
  font-weight: 600;
  color: rgba(255,255,255,0.3);
  letter-spacing: 1px;
  text-transform: uppercase;
  font-family: 'Tajawal', sans-serif;
}

/* Admin badge in logo area */
aside.sidebar .sb-admin-badge {
  margin: 0 20px 0;
  padding: 5px 12px;
  background: linear-gradient(135deg, rgba(109,40,217,0.3), rgba(109,40,217,0.15));
  border: 1px solid rgba(109,40,217,0.35);
  border-radius: 20px;
  font-size: 0.64rem;
  font-weight: 800;
  color: rgba(196,170,255,0.9);
  letter-spacing: 1px;
  text-transform: uppercase;
  display: inline-block;
  font-family: 'Tajawal', sans-serif;
}

/* ── ADMIN PROFILE CARD ── */
aside.sidebar .sb-profile {
  margin: 14px 12px 6px;
  padding: 11px 13px;
  background: rgba(255,255,255,0.055);
  border: 1px solid rgba(14,165,201,0.18);
  border-radius: 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  position: relative;
  z-index: 1;
  flex-shrink: 0;
  backdrop-filter: blur(10px);
  transition: border-color 0.2s;
}

aside.sidebar .sb-profile:hover {
  border-color: rgba(14,165,201,0.35);
}

aside.sidebar .sb-profile-av {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: linear-gradient(135deg, #6d28d9, #4f46e5);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.88rem;
  font-weight: 900;
  color: white;
  flex-shrink: 0;
  box-shadow: 0 2px 10px rgba(109,40,217,0.4);
  font-family: 'Tajawal', sans-serif;
}

aside.sidebar .sb-profile-info { flex: 1; min-width: 0; }

aside.sidebar .sb-profile-name {
  font-size: 0.83rem;
  font-weight: 800;
  color: white;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.2;
  font-family: 'Tajawal', sans-serif;
}

aside.sidebar .sb-profile-role {
  font-size: 0.67rem;
  color: rgba(196,170,255,0.7);
  margin-top: 2px;
  font-weight: 500;
  font-family: 'Tajawal', sans-serif;
}

aside.sidebar .sb-status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #22c55e;
  box-shadow: 0 0 8px rgba(34,197,94,0.65);
  flex-shrink: 0;
}

/* ── NAV ── */
aside.sidebar .sb-nav {
  flex: 1;
  padding: 8px 10px;
  overflow-y: auto;
  position: relative;
  z-index: 1;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

aside.sidebar .sb-nav::-webkit-scrollbar { display: none; }

aside.sidebar .sb-section {
  font-size: 0.59rem;
  font-weight: 800;
  color: rgba(255,255,255,0.2);
  padding: 14px 10px 5px;
  letter-spacing: 2px;
  text-transform: uppercase;
  user-select: none;
  font-family: 'Tajawal', sans-serif;
}

/* ── NAV ITEM ── */
aside.sidebar .nav-item {
  display: flex !important;
  align-items: center !important;
  gap: 10px !important;
  padding: 9px 11px !important;
  border-radius: 12px !important;
  margin-bottom: 2px;
  font-size: 0.875rem !important;
  font-weight: 600 !important;
  color: rgba(255,255,255,0.48) !important;
  cursor: pointer;
  transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease, border-color 0.2s ease !important;
  text-decoration: none !important;
  background: transparent !important;
  border: 1px solid transparent !important;
  width: 100%;
  font-family: 'Tajawal', sans-serif !important;
  text-align: right;
  position: relative;
  overflow: hidden;
  box-shadow: none !important;
  -webkit-appearance: none;
  appearance: none;
}

aside.sidebar .nav-item:hover {
  background: rgba(255,255,255,0.075) !important;
  color: rgba(255,255,255,0.88) !important;
  transform: translateX(-3px) !important;
  border-color: rgba(255,255,255,0.06) !important;
  box-shadow: none !important;
}

aside.sidebar .nav-item.active {
  background: linear-gradient(135deg, rgba(14,165,201,0.22) 0%, rgba(13,148,136,0.14) 100%) !important;
  color: #7de8f5 !important;
  border-color: rgba(14,165,201,0.25) !important;
  font-weight: 700 !important;
  box-shadow: 0 2px 14px rgba(14,165,201,0.12), inset 0 1px 0 rgba(255,255,255,0.06) !important;
  transform: none !important;
}

/* Active indicator bar */
aside.sidebar .nav-item.active::after {
  content: '';
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%) !important;
  width: 3px;
  height: 55%;
  border-radius: 3px 0 0 3px;
  background: linear-gradient(180deg, #0ea5c9, #0d9488);
}

/* ── NAV ICON WRAPPER ── */
aside.sidebar .nav-icon {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background: rgba(255,255,255,0.055);
  transition: background 0.2s ease, transform 0.2s ease;
}

aside.sidebar .nav-item.active .nav-icon {
  background: rgba(14,165,201,0.22);
}

aside.sidebar .nav-item:hover .nav-icon {
  background: rgba(255,255,255,0.11);
  transform: scale(1.05);
}

/* ── NAV SVG — Critical: force size to prevent huge icons ── */
aside.sidebar .nav-icon svg {
  width: 15px !important;
  height: 15px !important;
  opacity: 0.58;
  transition: opacity 0.2s ease;
  flex-shrink: 0;
  filter: none !important;
  display: block;
}

aside.sidebar .nav-item.active .nav-icon svg {
  opacity: 1;
}

aside.sidebar .nav-item:hover .nav-icon svg {
  opacity: 0.92;
}

/* ── NAV BADGE ── */
aside.sidebar .nav-badge {
  margin-right: auto;
  background: rgba(239,68,68,0.25);
  border: 1px solid rgba(239,68,68,0.3);
  border-radius: 20px;
  padding: 1px 7px;
  font-size: 0.63rem;
  font-weight: 800;
  color: #f87171;
  min-width: 18px;
  text-align: center;
  font-family: 'Tajawal', sans-serif;
}

aside.sidebar .nav-badge:empty { display: none !important; }
aside.sidebar .nav-badge.red { background: rgba(239,68,68,0.25); border-color: rgba(239,68,68,0.3); color: #f87171; }

/* ── FOOTER ── */
aside.sidebar .sb-foot {
  padding: 10px 10px 14px !important;
  border-top: 1px solid rgba(255,255,255,0.05) !important;
  position: relative;
  z-index: 1;
  flex-shrink: 0;
}

/* ── LOGOUT BUTTON ── */
aside.sidebar .sb-foot .logout-btn {
  color: rgba(255,255,255,0.42) !important;
  background: transparent !important;
  border: 1px solid transparent !important;
  -webkit-appearance: none !important;
  appearance: none !important;
  font-size: 0.875rem !important;
  font-family: 'Tajawal', sans-serif !important;
  box-shadow: none !important;
  transform: none !important;
}

aside.sidebar .sb-foot .logout-btn:hover {
  background: rgba(239,68,68,0.1) !important;
  color: #f87171 !important;
  border-color: rgba(239,68,68,0.18) !important;
  transform: translateX(-3px) !important;
  box-shadow: none !important;
}

aside.sidebar .sb-foot .logout-btn .nav-icon {
  background: rgba(255,255,255,0.04);
}

aside.sidebar .sb-foot .logout-btn:hover .nav-icon {
  background: rgba(239,68,68,0.15) !important;
  transform: none !important;
}

aside.sidebar .sb-foot .logout-btn .nav-icon svg {
  opacity: 0.5 !important;
}

aside.sidebar .sb-foot .logout-btn:hover .nav-icon svg {
  opacity: 0.88 !important;
}

/* ── MAIN OFFSET — ensures all pages have correct margin ── */
.main {
  flex: 1;
  min-width: 0;
  margin-right: 264px;
  width: calc(100vw - 264px);
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  overflow-x: hidden;
}

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  aside.sidebar { width: 220px !important; }
  .main { margin-right: 220px; width: calc(100vw - 220px); }
}
</style>

<aside class="sidebar">

  
  <div class="sb-logo">
    <div class="sb-logo-mark">ت</div>
    <div class="sb-logo-text">
      <div class="sb-logo-name">تكامل</div>
      <div class="sb-logo-sub">لوحة الإدارة</div>
    </div>
  </div>

  
  <div class="sb-profile">
    <div class="sb-profile-av">م</div>
    <div class="sb-profile-info">
      <div class="sb-profile-name">مدير النظام</div>
      <div class="sb-profile-role">مسؤول المنصة</div>
    </div>
    <div class="sb-status-dot" title="متصل"></div>
  </div>

  
  <nav class="sb-nav">

    <div class="sb-section">الرئيسية</div>

    <a href="<?php echo e(route('dashboard')); ?>"
       class="nav-item <?php echo e($nav==='dashboard' ? 'active' : ''); ?>"
       id="nav-dashboard">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1.5"/>
          <rect x="14" y="3" width="7" height="7" rx="1.5"/>
          <rect x="3" y="14" width="7" height="7" rx="1.5"/>
          <rect x="14" y="14" width="7" height="7" rx="1.5"/>
        </svg>
      </div>
      لوحة التحكم
    </a>

    <div class="sb-section">إدارة الأنشطة</div>

    <a class="nav-item <?php echo e($nav==='orders' ? 'active' : ''); ?>" id="nav-orders"
       href="<?php echo e(route('orders')); ?>"
       onclick="if(typeof showSection==='function'){showSection('orders');return false;}">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 11l3 3L22 4"/>
          <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
        </svg>
      </div>
      الجمعيات والطلبات
      <span class="nav-badge red" id="nb-reqs"></span>
    </a>

    <a class="nav-item <?php echo e($nav==='meetings' ? 'active' : ''); ?>" id="nav-meetings"
       href="<?php echo e(route('meetings')); ?>">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
      </div>
      الاجتماعات
    </a>

    <a class="nav-item <?php echo e($nav==='volunteer' ? 'active' : ''); ?>" id="nav-volunteer" data-vol
       href="<?php echo e(route('volunteer')); ?>"
       onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
      </div>
      فرص التطوع
    </a>

    <a class="nav-item <?php echo e($nav==='projects' ? 'active' : ''); ?>" id="nav-projects"
       href="<?php echo e(route('joint-projects')); ?>"
       onclick="if(typeof showSection==='function'){showSection('projects');return false;}">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
      </div>
      المشاريع المشتركة
    </a>

    <div class="sb-section">النظام</div>

    <a class="nav-item <?php echo e($nav==='settings' ? 'active' : ''); ?>"
       href="<?php echo e(route('settings')); ?>">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
        </svg>
      </div>
      الإعدادات
    </a>

  </nav>

  
  <div class="sb-foot">
    <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0">
      <?php echo csrf_field(); ?>
      <button type="submit" class="nav-item logout-btn" style="width:100%;cursor:pointer;">
        <div class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
          </svg>
        </div>
        تسجيل الخروج
      </button>
    </form>
  </div>

</aside>
<?php /**PATH C:\xampp\htdocs\tkamel-abdullah1\tkamel\resources\views/layouts/sidebar-admin.blade.php ENDPATH**/ ?>