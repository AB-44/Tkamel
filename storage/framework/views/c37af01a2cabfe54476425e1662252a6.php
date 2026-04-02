
<aside class="sidebar">
  <div class="sb-logo">
    <img src="<?php echo e(asset('images/logo.png.svg')); ?>" alt="تكامل" onerror="this.style.display='none'">
    <span>تكامل</span>
  </div>
  <nav class="sb-nav">
    <div class="sb-section">الرئيسية</div>
    <a href="<?php echo e(route('user.dashboard')); ?>" class="nav-item <?php echo e(($activeNav??'')==='dashboard' ? 'active' : ''); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
      </svg>لوحة التحكم
    </a>

    <div class="sb-section">الأنشطة</div>
    <a href="<?php echo e(route('user.meetings')); ?>" class="nav-item <?php echo e(($activeNav??'')==='meetings' ? 'active' : ''); ?>" id="nav-meetings"
       onclick="if(typeof showSection==='function'){showSection('meetings');return false;}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <path d="M16 2v4M8 2v4M3 10h18"/>
      </svg>الاجتماعات
    </a>
    <a class="nav-item <?php echo e(($activeNav??'')==='volunteer' ? 'active' : ''); ?>" id="nav-volunteer"
       href="<?php echo e(route('user.consulting')); ?>"
       onclick="if(typeof showSection==='function')showSection('volunteer');return false;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
      </svg>فرص التطوع
    </a>
    <a class="nav-item <?php echo e(($activeNav??'')==='projects' ? 'active' : ''); ?>" id="nav-projects"
       href="<?php echo e(route('user.joint-projects')); ?>"
       onclick="if(typeof showSection==='function')showSection('projects');return false;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
      </svg>المشاريع
    </a>

    <div class="sb-section">خدمات مبادرون</div>
    <a href="<?php echo e(route('user.services')); ?>" class="nav-item <?php echo e(($activeNav??'')==='services' ? 'active' : ''); ?>" id="nav-services">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 4 2-7L3 9h7z"/>
      </svg>
      خدمات مبادرون
    </a>

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
<?php /**PATH /home/testuser/Downloads/Tkamel-main/resources/views/layouts/sidebar-user.blade.php ENDPATH**/ ?>