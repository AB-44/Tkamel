
<aside class="sidebar">
  <div class="sb-logo">
    <img src="<?php echo e(asset('images/logo.png.svg')); ?>" alt="تكامل" onerror="this.style.display='none'">
    <span>تكامل</span>
  </div>
  <nav class="sb-nav">
    <div class="sb-section">الرئيسية</div>
    <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(($activeNav??'')==='dashboard' ? 'active' : ''); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
      </svg>لوحة التحكم
    </a>

    <div class="sb-section">إدارة الأنشطة</div>
    <a class="nav-item <?php echo e(($activeNav??'')==='meetings' ? 'active' : ''); ?>" id="nav-meetings"
       href="<?php echo e(route('meetings')); ?>" onclick="if(typeof showSection==='function'){showSection('meetings');return false;}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <path d="M16 2v4M8 2v4M3 10h18"/>
      </svg>الاجتماعات
    </a>
    <a class="nav-item <?php echo e(($activeNav??'')==='volunteer' ? 'active' : ''); ?>" id="nav-volunteer" data-vol
       href="<?php echo e(route('volunteer')); ?>"
       onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
      </svg>فرص التطوع<span class="nav-badge" id="nb-opps"></span>
    </a>
    <a class="nav-item <?php echo e(($activeNav??'')==='orders' ? 'active' : ''); ?>" id="nav-orders"
       href="<?php echo e(route('orders')); ?>"
       onclick="if(typeof showSection==='function'){showSection('orders');return false;}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 11l3 3L22 4"/>
        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
      </svg>الطلبات<span class="nav-badge red" id="nb-reqs"></span>
    </a>
    <a class="nav-item <?php echo e(($activeNav??'')==='projects' ? 'active' : ''); ?>" id="nav-projects"
       href="<?php echo e(route('joint-projects')); ?>"
       onclick="if(typeof showSection==='function'){showSection('projects');return false;}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
      </svg>المشاريع
    </a>

    <div class="sb-section">خدمات مبادرون</div>
    <div class="nav-parent" id="np-services" onclick="toggleServices()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 4 2-7L3 9h7z"/>
      </svg>
      خدمات مبادرون
      <svg class="np-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12">
        <path d="M6 9l6 6 6-6"/>
      </svg>
    </div>
    <div class="nav-submenu" id="submenu-services">
      <a class="nav-sub-item" id="sub-units"       href="<?php echo e(route('consulting')); ?>"
         onclick="if(typeof showService==='function'){showService('units');return false;}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        بناء وحدات
      </a>
      <a class="nav-sub-item" id="sub-systems"     href="<?php echo e(route('consulting')); ?>"
         onclick="if(typeof showService==='function'){showService('systems');return false;}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
        بناء أنظمة
      </a>
      <a class="nav-sub-item" id="sub-initiatives" href="<?php echo e(route('consulting')); ?>"
         onclick="if(typeof showService==='function'){showService('initiatives');return false;}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        تنسيق مبادرات
      </a>
      <a class="nav-sub-item" id="sub-training"    href="<?php echo e(route('consulting')); ?>"
         onclick="if(typeof showService==='function'){showService('training');return false;}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        تدريب تطوعي
      </a>
      <a class="nav-sub-item" id="sub-consulting"  href="<?php echo e(route('consulting')); ?>"
         onclick="if(typeof showService==='function'){showService('consulting');return false;}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>
        الاستشارات
      </a>
      <a class="nav-sub-item" id="sub-contact"     href="<?php echo e(route('consulting')); ?>"
         onclick="if(typeof showService==='function'){showService('contact');return false;}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        التواصل معنا
      </a>
    </div>

    <div class="sb-section">النظام</div>
    <a class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="8" r="4"/>
        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
      </svg>الملف الشخصي
    </a>
  </nav>

  <div class="sb-foot">
    <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0">
      <?php echo csrf_field(); ?>
      <button type="submit" class="nav-item logout-btn"
        style="width:100%;cursor:pointer;text-align:right;font-family:inherit;font-size:inherit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
        </svg>تسجيل الخروج
      </button>
    </form>
  </div>
</aside>
<?php /**PATH /home/a-22/Downloads/tkamel-fixed/tkamel/resources/views/layouts/sidebar-admin.blade.php ENDPATH**/ ?>