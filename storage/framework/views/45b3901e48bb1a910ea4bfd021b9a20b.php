<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تكامل | لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
    
</head>

<body>
    <?php echo $__env->make('layouts.sidebar-admin', ['activeNav' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <!-- ══ MAIN ══ -->
    <div class="main">

        <!-- TOPBAR -->
        <?php echo $__env->make('layouts.topbar', ['title' => 'لوحة التحكم'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <div class="content">

            <!-- PAGE HEADER -->
            <div class="page-hd">
                <div>
                    <div class="ph-title">لوحة التحكم</div>
                    <div class="ph-sub">مرحباً بعودتك — إليك ملخص أهم النشاطات والإحصائيات</div>
                </div>
                <div class="ph-actions">
                    <a href="<?php echo e(route('consulting')); ?>" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        فرص التطوع
                    </a>
                </div>
            </div>

            <!-- Stats row — matching consulting.css .stat-card style -->
            <div class="stats-row" style="margin-bottom:2rem">
                <div class="stat-card" style="--sc:var(--teal-glow)">
                    <div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-building"></i></div>
                    <div><span class="s-num" id="dash-assoc-count"><?php echo e(number_format($stats['associations_count'])); ?></span><span class="s-lbl">إجمالي الجمعيات</span></div>
                </div>
                <div class="stat-card" style="--sc:var(--green)">
                    <div class="s-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-lightbulb"></i></div>
                    <div><span class="s-num"><?php echo e(number_format($stats['opportunities_count'])); ?></span><span class="s-lbl">الفرص المتاحة</span></div>
                </div>
                <div class="stat-card" style="--sc:var(--blue)">
                    <div class="s-icon" style="background:rgba(29,111,164,0.1)"><i class="fa-solid fa-handshake"></i></div>
                    <div><span class="s-num"><?php echo e(number_format($stats['projects_count'])); ?></span><span class="s-lbl">المشاريع المشتركة</span></div>
                </div>
                <div class="stat-card" style="--sc:var(--purple)">
                    <div class="s-icon" style="background:rgba(109,40,217,0.1)"><i class="fa-solid fa-circle-check"></i></div>
                    <div><span class="s-num"><?php echo e(number_format($stats['completed_requests'])); ?></span><span class="s-lbl">إجمالي الطلبات المنجزة</span></div>
                </div>
            </div>

            <!-- Registration requests alert — shown when pending DB registrations exist -->
            <div id="dash-pending-alert" style="display:none;align-items:center;gap:12px;background:rgba(245,158,11,0.08);border:1.5px solid rgba(245,158,11,0.22);border-radius:12px;padding:14px 18px;margin-bottom:1.5rem">
                <span style="font-size:1.1rem;color:#b45309"><i class="fa-solid fa-inbox"></i></span>
                <div style="flex:1">
                    <div style="font-size:.9rem;font-weight:800;color:#92400e">طلبات تسجيل جمعيات جديدة بانتظار المراجعة</div>
                    <div id="dash-pending-text" style="font-size:.8rem;color:#b45309;margin-top:2px"></div>
                </div>
                <a href="<?php echo e(route('orders')); ?>" class="btn-primary" style="font-size:.82rem;padding:7px 14px;white-space:nowrap">مراجعة الطلبات</a>
            </div>

            <!-- Dashboard 2-col grid -->
            <div class="dash-grid">

                <!-- Meetings -->
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-regular fa-calendar-check"></i> الاجتماعات القادمة</div>
                        <a href="<?php echo e(route('meetings')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)"><i class="fa-solid fa-video"></i></div>
                                <div class="dw-info"><div class="dw-name"><?php echo e($meeting->title); ?></div><div class="dw-meta"><?php echo e(\Carbon\Carbon::parse($meeting->date_time)->translatedFormat('d M Y')); ?> • <?php echo e(\Carbon\Carbon::parse($meeting->date_time)->format('h:i A')); ?></div></div>
                                <a href="<?php echo e(route('meetings')); ?>" class="dw-action">انضمام</a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد اجتماعات قادمة</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Projects -->
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-chart-pie"></i> المشاريع المشتركة</div>
                        <a href="<?php echo e(route('joint-projects')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $activeProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-leaf"></i></div>
                                <div class="dw-info"><div class="dw-name"><?php echo e($project->name); ?></div><div class="dw-meta">تاريخ البداية: <?php echo e($project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : 'غير محدد'); ?></div></div>
                                <div class="dw-bar"><div class="dw-bar-fill" style="width:<?php echo e(rand(40, 100)); ?>%;background:var(--green)"></div></div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد مشاريع مشتركة نشطة</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Opportunities -->
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-lightbulb"></i> فرص التطوع والدعم</div>
                        <a href="<?php echo e(route('consulting')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $latestOpportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706"><i class="fa-solid fa-box-open"></i></div>
                                <div class="dw-info"><div class="dw-name"><?php echo e($opp->title); ?></div><div class="dw-meta"><?php echo e($opp->type ?? 'تطوع'); ?> • حتى <?php echo e($opp->deadline ? \Carbon\Carbon::parse($opp->deadline)->format('Y-m-d') : 'مفتوح'); ?></div></div>
                                <a href="<?php echo e(route('consulting')); ?>" class="dw-action">تصفح</a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد فرص تطوع ومبادرات</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Requests — live from DB -->
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-clipboard-list"></i> أحدث طلبات التسجيل</div>
                        <a href="<?php echo e(route('orders')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list" id="dash-recent-reqs">
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(109,40,217,0.12);color:var(--purple)"><i class="fa-solid fa-file-signature"></i></div>
                            <div class="dw-info"><div class="dw-name">طلب ترخيص فعالية</div><div class="dw-meta">مقدم من: جمعية الرواد • قبل ساعتين</div></div>
                            <span class="dw-badge pending">قيد المراجعة</span>
                        </li>
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-handshake"></i></div>
                            <div class="dw-info"><div class="dw-name">طلب شراكة دعم فني</div><div class="dw-meta">مقدم من: مؤسسة العطاء • منذ يوم</div></div>
                            <span class="dw-badge approved">مقبول</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
    <style>
        /* ── Dashboard-specific layout (consulting.css handles shared styles) ── */

        /* 2-column widget grid */
        .dash-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:1.25rem; }
        @media(max-width:900px){ .dash-grid{ grid-template-columns:1fr; } }

        /* Widget card */
        .dash-widget {
            background:var(--white); border:1.5px solid var(--border);
            border-radius:14px; padding:1.25rem 1.4rem;
            box-shadow:var(--sh-sm); display:flex; flex-direction:column;
            gap:.75rem; transition:box-shadow .2s;
        }
        .dash-widget:hover { box-shadow:0 8px 24px rgba(0,0,0,.10); }

        .dw-header { display:flex; align-items:center; justify-content:space-between; padding-bottom:.75rem; border-bottom:1px solid var(--border); }
        .dw-title  { font-size:.95rem; font-weight:800; color:var(--teal-deep); display:flex; align-items:center; gap:8px; }
        .dw-title i{ color:var(--teal); }
        .dw-link   { font-size:.78rem; font-weight:700; color:var(--teal); text-decoration:none; opacity:.8; transition:opacity .15s; }
        .dw-link:hover{ opacity:1; }

        .dw-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.6rem; }
        .dw-item { display:flex; align-items:center; gap:12px; padding:10px 12px; background:var(--fog); border-radius:10px; transition:background .15s; }
        .dw-item:hover { background:rgba(14,165,201,.06); }

        .dw-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .dw-info { flex:1; min-width:0; }
        .dw-name { font-size:.87rem; font-weight:700; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .dw-meta { font-size:.75rem; color:var(--muted); margin-top:2px; }
        .dw-action { font-size:.78rem; font-weight:700; color:var(--teal); background:rgba(14,165,201,.1); border:1px solid rgba(14,165,201,.2); border-radius:8px; padding:4px 12px; text-decoration:none; white-space:nowrap; transition:all .15s; }
        .dw-action:hover { background:rgba(14,165,201,.2); }

        /* Progress bar & badges */
        .dw-bar { width:60px; height:5px; background:rgba(0,0,0,.08); border-radius:4px; flex-shrink:0; overflow:hidden; }
        .dw-bar-fill { height:100%; border-radius:4px; }
        .dw-badge { font-size:.74rem; font-weight:700; padding:3px 10px; border-radius:20px; white-space:nowrap; flex-shrink:0; }
        .dw-badge.pending  { background:rgba(245,158,11,.12); color:#d97706; }
        .dw-badge.approved { background:rgba(46,170,120,.12); color:var(--green); }
        .dw-badge.rejected { background:rgba(198,40,40,.1);   color:var(--red); }
        .dw-badge.new      { background:rgba(14,165,201,.1);  color:var(--teal); }
    </style>
    <script>
        // Refresh pending registrations for the dashboard alert + recent list
        async function refreshDashPending() {
            try {
                const res = await fetch('/api/association-requests?status=pending', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();

                const pendingCount = Array.isArray(data) ? data.length : 0;

                // Always update nb-reqs badge (clear when 0, show count when > 0)
                const nb = document.getElementById('nb-reqs');
                if (nb) nb.textContent = pendingCount > 0 ? pendingCount : '';

                // Show/hide alert banner
                const alert = document.getElementById('dash-pending-alert');
                if (alert) {
                    alert.style.display = pendingCount > 0 ? 'flex' : 'none';
                    const txt = document.getElementById('dash-pending-text');
                    if (txt && pendingCount > 0) {
                        txt.textContent = `${pendingCount} طلب تسجيل جمعية جديدة بانتظار موافقتك`;
                    }
                }

                // Update recent requests widget with real DB data (first 3)
                const list = document.getElementById('dash-recent-reqs');
                if (list) {
                    if (pendingCount > 0) {
                        const colors = { pending:'pending', approved:'approved', rejected:'rejected' };
                        const labels = { pending:'قيد المراجعة', approved:'مقبول', rejected:'مرفوض' };
                        const icons  = ['fa-file-signature','fa-handshake','fa-building-circle-check'];
                        const bgIcons= ['rgba(109,40,217,0.12)','rgba(46,170,120,0.12)','rgba(29,111,164,0.12)'];
                        const fgIcons= ['var(--purple)','var(--green)','var(--blue)'];
                        list.innerHTML = data.slice(0,3).map((a,i)=>`
                            <li class="dw-item">
                                <div class="dw-icon" style="background:${bgIcons[i%3]};color:${fgIcons[i%3]}"><i class="fa-solid ${icons[i%3]}"></i></div>
                                <div class="dw-info">
                                    <div class="dw-name">${a.association_name}</div>
                                    <div class="dw-meta">${a.manager_name} • ${a.category}</div>
                                </div>
                                <span class="dw-badge ${colors[a.status]||'pending'}">${labels[a.status]||'معلق'}</span>
                            </li>`).join('');
                    } else {
                        list.innerHTML = '';
                    }
                }
            } catch(e) { /* silently fail */ }
        }

        refreshDashPending();
        setInterval(refreshDashPending, 30000);
    </script>

  <?php echo $__env->make('layouts.notif-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\tkamel-abdullah1\tkamel\resources\views/dashboard.blade.php ENDPATH**/ ?>