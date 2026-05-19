<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تكامل — الرئيسية</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo e(asset('css/user-dashboard.css')); ?>?v=<?php echo e(time()); ?>">
</head>

<body>

  <!-- ══ SIDEBAR ══ -->
  <?php echo $__env->make('layouts.sidebar-user', ['activeNav' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- ══ MAIN ══ -->
  <div class="main-wrapper">

    <!-- TOPBAR -->
    <?php echo $__env->make('layouts.topbar', ['title' => 'لوحة التحكم', 'crumb' => 'الرئيسية'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- CONTENT -->
    <div class="content">

      <!-- WELCOME BANNER -->
      <div class="welcome-banner" id="welcome-banner">
        <div class="wb-pattern"></div>
        <div class="wb-glow"></div>
        <div class="wb-text">
          <div class="wb-greeting" id="wb-greeting">
            <?php echo e((date('H') < 12) ? 'صباح الخير 👋' : ((date('H') < 17) ? 'مساء الخير 👋' : 'أهلاً وسهلاً 👋')); ?>

          </div>
          <div class="wb-name"><?php echo e(Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'); ?></div>
          <div class="wb-sub">إليك ملخص نشاطك على منصة <strong>تكامل</strong> لهذا اليوم</div>
        </div>
        <div class="wb-date"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></div>
      </div>

      <!-- STAT CARDS -->
      <div class="stats-grid" id="stats-grid">
        <div class="stat-card" style="--sa:var(--teal-glow)">
          <div class="stat-ico-wrap" style="background:rgba(14,165,201,.1)">📋</div>
          <div class="stat-info">
            <div class="stat-num counter" data-target="<?php echo e($stats['total_requests'] ?? 0); ?>">0</div>
            <div class="stat-lbl">إجمالي طلباتي</div>
          </div>
          <div class="stat-trend up"><i class="fa-solid fa-paper-plane fa-xs"></i></div>
        </div>
        <div class="stat-card" style="--sa:#f59e0b">
          <div class="stat-ico-wrap" style="background:rgba(245,158,11,.1)">⏳</div>
          <div class="stat-info">
            <div class="stat-num counter" data-target="<?php echo e($stats['pending_requests'] ?? 0); ?>">0</div>
            <div class="stat-lbl">قيد المراجعة</div>
          </div>
          <div class="stat-trend" style="color:#f59e0b"><i class="fa-solid fa-clock fa-xs"></i></div>
        </div>
        <div class="stat-card" style="--sa:var(--green)">
          <div class="stat-ico-wrap" style="background:rgba(13,148,136,.1)">✅</div>
          <div class="stat-info">
            <div class="stat-num counter" data-target="<?php echo e($stats['approved_requests'] ?? 0); ?>">0</div>
            <div class="stat-lbl">مقبولة</div>
          </div>
          <div class="stat-trend up" style="color:var(--green)"><i class="fa-solid fa-check fa-xs"></i></div>
        </div>
        <div class="stat-card" style="--sa:#6366f1">
          <div class="stat-ico-wrap" style="background:rgba(99,102,241,.1)">🗂️</div>
          <div class="stat-info">
            <div class="stat-num counter" data-target="<?php echo e($stats['projects_count'] ?? 0); ?>">0</div>
            <div class="stat-lbl">مشاريع نشطة</div>
          </div>
          <div class="stat-trend up" style="color:#6366f1"><i class="fa-solid fa-diagram-project fa-xs"></i></div>
        </div>
        <div class="stat-card" style="--sa:var(--teal)">
          <div class="stat-ico-wrap" style="background:rgba(12,96,128,.1)">📅</div>
          <div class="stat-info">
            <div class="stat-num counter" data-target="<?php echo e($stats['upcoming_meetings_count'] ?? 0); ?>">0</div>
            <div class="stat-lbl">اجتماعات قادمة</div>
          </div>
          <div class="stat-trend up"><i class="fa-solid fa-calendar fa-xs"></i></div>
        </div>
        <div class="stat-card" style="--sa:var(--green)">
          <div class="stat-ico-wrap" style="background:rgba(46,170,120,.1)">🤝</div>
          <div class="stat-info">
            <div class="stat-num counter" data-target="<?php echo e($stats['opportunities_count'] ?? 0); ?>">0</div>
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
            <a href="<?php echo e(route('user.orders')); ?>" class="dc-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
          </div>
          <div class="dc-body" id="recent-reqs">
            <?php $__empty_1 = true; $__currentLoopData = $latestRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <div class="req-row">
                <div class="rr-type" style="background: <?php echo e($req->color); ?>1a; color: <?php echo e($req->color); ?>;">
                  <i class="fa-solid <?php echo e($req->typeIcon); ?>"></i>
                </div>
                <div class="rr-body">
                  <div class="rr-title"><?php echo e($req->title); ?></div>
                  <div class="rr-sub"><?php echo e($req->sub); ?></div>
                </div>
                <div class="rr-right">
                  <?php if($req->status === 'pending'): ?>
                    <span class="sbdg sb-pending">⏳ قيد المراجعة</span>
                  <?php elseif($req->status === 'approved'): ?>
                    <span class="sbdg sb-approved">✅ مقبول</span>
                  <?php elseif($req->status === 'rejected'): ?>
                    <span class="sbdg sb-rejected">❌ مرفوض</span>
                  <?php endif; ?>
                  <div class="rr-date"><?php echo e(\Carbon\Carbon::parse($req->created_at)->translatedFormat('d M Y')); ?></div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="dc-empty"><i class="fa-solid fa-paper-plane"></i>
                <p>لا توجد طلبات بعد</p>
              </div>
            <?php endif; ?>
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
            <a href="<?php echo e(route('user.meetings')); ?>" class="dc-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
          </div>
          <div class="dc-body" id="upcoming-meets">
            <?php $__empty_1 = true; $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <a href="<?php echo e(route('user.meetings')); ?>" class="meet-row">
                <div class="mr-date-box">
                  <span class="mr-day"><?php echo e(\Carbon\Carbon::parse($meet->date_time)->format('d')); ?></span>
                  <span class="mr-month"><?php echo e(\Carbon\Carbon::parse($meet->date_time)->translatedFormat('M')); ?></span>
                </div>
                <div class="mr-body">
                  <div class="mr-title"><?php echo e($meet->title); ?></div>
                  <div class="mr-meta">
                    <?php echo e(($meet->meeting_type == 'online') ? '💻 عن بعد' : '📍 حضوري'); ?>

                    · <?php echo e(\Carbon\Carbon::parse($meet->date_time)->format('h:i A')); ?><?php echo e($meet->end_date_time ? ' - ' . \Carbon\Carbon::parse($meet->end_date_time)->format('h:i A') : ''); ?>

                  </div>
                </div>
                <?php if($meet->link): ?>
                  <span class="mr-join">انضم</span>
                <?php endif; ?>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="dc-empty"><i class="fa-solid fa-calendar"></i>
                <p>لا توجد اجتماعات قادمة</p>
              </div>
            <?php endif; ?>
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
            <a href="<?php echo e(route('user.consulting')); ?>" class="dc-link">تصفح الكل <i
                class="fa-solid fa-arrow-left"></i></a>
          </div>
          <div class="dc-body" id="vol-opps">
            <?php $__empty_1 = true; $__currentLoopData = $latestOpportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <a href="<?php echo e(route('user.consulting')); ?>" class="opp-row">
                <div class="or-dot" style="background:#2ab8d0"></div>
                <div class="or-body">
                  <div class="or-title"><?php echo e($opp->title); ?></div>
                  <div class="or-sub"><?php echo e($opp->organization ?? 'تكامل'); ?></div>
                </div>
                <span class="opp-tag ot-onsite">متاحة</span>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="dc-empty"><i class="fa-solid fa-hand-holding-heart"></i>
                <p>لا توجد فرص متاحة</p>
              </div>
            <?php endif; ?>
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
            <a href="<?php echo e(route('user.joint-projects')); ?>" class="dc-link">عرض الكل <i
                class="fa-solid fa-arrow-left"></i></a>
          </div>
          <div class="dc-body" id="active-projs">
            <?php $__empty_1 = true; $__currentLoopData = $activeProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php $prog = $proj->progress ?? 0; ?>
              <a href="<?php echo e(route('user.joint-projects')); ?>" class="proj-row">
                <div class="pr-emoji">🪴</div>
                <div class="pr-body">
                  <div class="pr-title"><?php echo e($proj->name); ?></div>
                  <?php if($proj->category): ?>
                    <div style="font-size:0.74rem;color:var(--muted);margin-bottom:5px">
                      <?php echo e($proj->category->icon ?? ''); ?> <?php echo e($proj->category->name); ?>

                    </div>
                  <?php endif; ?>
                  <div class="pr-prog">
                    <div class="pr-prog-tr">
                      <div class="pr-prog-fi" style="width:<?php echo e($prog); ?>%;background:#22d3a5"></div>
                    </div>
                    <span class="pr-pct"><?php echo e($prog); ?>%</span>
                  </div>
                </div>
                <span class="pr-status s-active">مستمر</span>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="dc-empty"><i class="fa-solid fa-diagram-project"></i>
                <p>لا توجد مشاريع نشطة</p>
              </div>
            <?php endif; ?>
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
            <a href="<?php echo e(route('user.consulting')); ?>" class="shortcut-card">
              <div class="sc-emoji">🤝</div>
              <div class="sc-lbl">فرص التطوع</div>
            </a>
            <a href="<?php echo e(route('user.joint-projects')); ?>" class="shortcut-card">
              <div class="sc-emoji">🗂️</div>
              <div class="sc-lbl">المشاريع المشتركة</div>
            </a>
            <a href="<?php echo e(route('user.meetings')); ?>" class="shortcut-card">
              <div class="sc-emoji">📅</div>
              <div class="sc-lbl">الاجتماعات</div>
            </a>
            <a href="<?php echo e(route('user.orders')); ?>" class="shortcut-card">
              <div class="sc-emoji">📋</div>
              <div class="sc-lbl">طلباتي</div>
            </a>
            <a href="<?php echo e(route('user.services')); ?>" class="shortcut-card">
              <div class="sc-emoji">⭐</div>
              <div class="sc-lbl">خدمات مبادرون</div>
            </a>
            <a href="<?php echo e(route('user.settings')); ?>" class="shortcut-card">
              <div class="sc-emoji">👤</div>
              <div class="sc-lbl">ملفي الشخصي</div>
            </a>
          </div>
        </div>

      </div><!-- /row3 -->

    </div><!-- /content -->
  </div><!-- /main -->

  <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>
  
  <?php echo $__env->make('layouts.notif-panel-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // أنيميشن العداد
      document.querySelectorAll('.counter').forEach(el => {
        const target = +el.dataset.target;
        if (target === 0) return;
        let cur = 0;
        const step = Math.max(1, Math.ceil(target / 30));
        const timer = setInterval(() => {
          cur = Math.min(cur + step, target);
          el.textContent = cur;
          if (cur >= target) clearInterval(timer);
        }, 40);
      });

      // حالة الطلبات (Donut Chart)
      const total = <?php echo e($stats['total_requests'] ?? 0); ?>;
      const pending = <?php echo e($stats['pending_requests'] ?? 0); ?>;
      const approved = <?php echo e($stats['approved_requests'] ?? 0); ?>;
      const rejected = <?php echo e($stats['rejected_requests'] ?? 0); ?>;
      const el = document.getElementById('reqs-status');

      if (total === 0) {
        el.innerHTML = '<div class="dc-empty"><i class="fa-solid fa-chart-pie"></i><p>لا توجد طلبات بعد</p></div>';
      } else {
        const p = Math.round;
        const pPend = p(pending / total * 100), pApp = p(approved / total * 100), pRej = p(rejected / total * 100);

        const r = 60;
        const circ = 2 * Math.PI * r;
        const gap = 4;

        function arc(pct, offset, color) {
          if (pct <= 0) return '';
          const len = Math.max(0, circ * pct / 100 - gap);
          return `<circle cx="70" cy="70" r="${r}" fill="none" stroke="${color}" stroke-width="14"
                stroke-dasharray="${len} ${circ}" stroke-dashoffset="${-circ * offset / 100}"
                stroke-linecap="round"/>`;
        }

        const svg = `
            <svg viewBox="0 0 140 140" class="donut-svg">
                <circle cx="70" cy="70" r="${r}" fill="none" stroke="var(--border)" stroke-width="14"/>
                ${arc(pApp, 0, '#0d9488')}
                ${arc(pPend, pApp, '#f59e0b')}
                ${arc(pRej, pApp + pPend, '#ef5350')}
                <text x="70" y="66" text-anchor="middle" font-family="Tajawal" font-size="20" font-weight="900" fill="var(--ink)">${total}</text>
                <text x="70" y="83" text-anchor="middle" font-family="Tajawal" font-size="9" fill="var(--muted)">طلب</text>
            </svg>`;

        el.innerHTML = `
            <div class="donut-wrap">
                ${svg}
                <div class="donut-legend">
                <div class="dl-item"><span class="dl-dot" style="background:#0d9488"></span><span>مقبولة</span><strong>${approved}</strong></div>
                <div class="dl-item"><span class="dl-dot" style="background:#f59e0b"></span><span>قيد المراجعة</span><strong>${pending}</strong></div>
                <div class="dl-item"><span class="dl-dot" style="background:#ef5350"></span><span>مرفوضة</span><strong>${rejected}</strong></div>
                </div>
            </div>
            <div class="donut-bars">
                <div class="db-item">
                <div class="db-labels"><span>مقبولة</span><span style="color:#0d9488">${pApp}%</span></div>
                <div class="db-track"><div class="db-fill" style="width:${pApp}%;background:#0d9488"></div></div>
                </div>
                <div class="db-item">
                <div class="db-labels"><span>قيد المراجعة</span><span style="color:#f59e0b">${pPend}%</span></div>
                <div class="db-track"><div class="db-fill" style="width:${pPend}%;background:#f59e0b"></div></div>
                </div>
                <div class="db-item">
                <div class="db-labels"><span>مرفوضة</span><span style="color:#ef5350">${pRej}%</span></div>
                <div class="db-track"><div class="db-fill" style="width:${pRej}%;background:#ef5350"></div></div>
                </div>
            </div>`;
      }
    });
  </script>
</body>

</html><?php /**PATH C:\xampp\htdocs\tkamel-abdullah1\tkamel\resources\views/user/dashboard.blade.php ENDPATH**/ ?>