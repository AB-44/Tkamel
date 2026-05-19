<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>تكامل — الاجتماعات</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/user-meetings.css')); ?>?v=<?php echo e(time()); ?>">
  <script>
    window.meetingsList    = <?php echo json_encode($formattedMeetings, 15, 512) ?>;
    window.attendingIdsList= <?php echo json_encode($attendingIds, 15, 512) ?>;
    window.categoriesList  = <?php echo json_encode($categories ?? [], 15, 512) ?>;
  </script>
</head>

<body>
  <div class="layout">

    <!-- ══ SIDEBAR ══ -->
    <?php echo $__env->make('layouts.sidebar-user', ['activeNav' => 'meetings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- ══ MAIN ══ -->
    <div class="main">
      <!-- TOPBAR -->
      <?php echo $__env->make('layouts.topbar', ['title' => 'الاجتماعات', 'breadcrumbs' => 'الاجتماعات'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      <!-- CONTENT -->
      <div class="content">

        <!-- PAGE HEADER -->
        <div class="page-header">
          <div>
            <div class="ph-title">الاجتماعات</div>
            <div class="ph-sub">تصفح الاجتماعات المتاحة وسجّل حضورك</div>
          </div>
        </div>

        <!-- STATS -->
        <div class="stats-row">
          <div class="stat-card" style="--sc:var(--teal-glow)">
            <div class="stat-icon" style="background:rgba(42,184,208,0.1)">📅</div>
            <div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div>
          </div>
          <div class="stat-card" style="--sc:var(--green)">
            <div class="stat-icon" style="background:rgba(46,170,120,0.1)">🟢</div>
            <div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">القادمة والحالية</span></div>
          </div>
          <div class="stat-card" style="--sc:var(--gold)">
            <div class="stat-icon" style="background:rgba(245,158,11,0.1)">✅</div>
            <div><span class="stat-num" id="s-attending">0</span><span class="stat-lbl">سأحضر</span></div>
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
            <input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="renderAll()">
          </div>
          <div class="tb-div"></div>
          <div class="chips">
            <div class="chip on" id="chip-all" onclick="setTypeF('all')">الكل</div>
            <div class="chip" id="chip-online" onclick="setTypeF('online')">💻 عن بعد</div>
            <div class="chip" id="chip-onsite" onclick="setTypeF('onsite')">📍 حضوري</div>
          </div>
        </div>

        <!-- CURRENT meetings -->
        <div class="sec-wrap">
          <div class="sec-header">
            <div class="sec-icon" style="background:rgba(42,184,208,0.1)">🟢</div>
            <div class="sec-title">الاجتماعات الحالية والقادمة</div>
            <span class="sec-count sc-current" id="bc-cur">0</span>
          </div>
          <div class="meetings-grid" id="grid-cur"></div>
        </div>

        <!-- PAST meetings -->
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

        <!-- CANCELLED meetings -->
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
    </div><!-- /main -->
  </div><!-- /layout -->

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

        <!-- زر الحضور (للاجتماعات القادمة فقط) -->
        <div id="d-attend-wrap" class="det-attend-wrap">
          <button class="btn-attend" id="btn-attend" onclick="toggleAttend()"></button>
        </div>
      </div>
      <div class="det-ft">
        <button class="btn-cancel-modal" onclick="closeOv('ov-details')">إغلاق</button>
        <button class="btn-join" id="btn-join-det" style="display:none" onclick="joinMeeting()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
            <polyline points="15 3 21 3 21 9" />
            <line x1="10" y1="14" x2="21" y2="3" />
          </svg>
          انضم للاجتماع
        </button>
      </div>
    </div>
  </div>

  <!-- ══ TOAST ══ -->
  <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

  <?php echo $__env->make('layouts.notif-panel-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <script src="<?php echo e(asset('js/user-meetings.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\tkamel-abdullah1\tkamel\resources\views/user/meetings.blade.php ENDPATH**/ ?>