<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تكامل | لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php echo $__env->make('layouts.sidebar-admin', ['activeNav' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- ══ MAIN ══ -->
    <div class="main">

        <!-- TOPBAR -->
        <?php echo $__env->make('layouts.topbar', ['title' => 'لوحة التحكم', 'userName' => 'مبادرون (أدمن)', 'userAv' => 'م'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="content">

            <!-- PAGE HEADER -->
            <div class="page-hd">
                <div>
                    <div class="ph-title">لوحة التحكم</div>
                    <div class="ph-sub">مرحباً بعودتك — إليك ملخص أهم النشاطات والإحصائيات</div>
                </div>

            </div>

            <!-- Stats row — matching consulting.css .stat-card style -->
            <div class="stats-row" style="margin-bottom:2rem">
                <div class="stat-card" style="--sc:var(--teal-glow)">
                    <div class="s-icon" style="background:rgba(42,184,208,0.1)">🏢</div>
                    <div><span class="s-num" id="dash-assoc-count"><?php echo e(number_format($stats['associations_count'])); ?></span><span class="s-lbl">إجمالي الجمعيات</span></div>
                </div>
                <div class="stat-card" style="--sc:var(--green)">
                    <div class="s-icon" style="background:rgba(46,170,120,0.1)">💡</div>
                    <div><span class="s-num"><?php echo e(number_format($stats['opportunities_count'])); ?></span><span class="s-lbl">الفرص المتاحة</span></div>
                </div>
                <div class="stat-card" style="--sc:var(--blue)">
                    <div class="s-icon" style="background:rgba(29,111,164,0.1)">🤝</div>
                    <div><span class="s-num"><?php echo e(number_format($stats['projects_count'])); ?></span><span class="s-lbl">المشاريع المشتركة</span></div>
                </div>
                <div class="stat-card" style="--sc:var(--purple)">
                    <div class="s-icon" style="background:rgba(109,40,217,0.1)">✅</div>
                    <div><span class="s-num"><?php echo e(number_format($stats['completed_requests'])); ?></span><span class="s-lbl">إجمالي الطلبات المنجزة</span></div>
                </div>
            </div>

            <!-- Registration requests alert — shown when pending DB registrations exist -->
            <div id="dash-pending-alert" style="display:none;align-items:center;gap:12px;background:rgba(245,158,11,0.08);border:1.5px solid rgba(245,158,11,0.22);border-radius:12px;padding:14px 18px;margin-bottom:1.5rem">
                <span style="font-size:1.3rem">📬</span>
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
                    <ul class="dw-list" id="dash-meetings-list">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)">
                                    <i class="fa-solid <?php echo e($meeting->direction === 'online' ? 'fa-video' : 'fa-users'); ?>"></i>
                                </div>
                                <div class="dw-info">
                                    <div class="dw-name"><?php echo e($meeting->title); ?></div>
                                    <div class="dw-meta">
                                        <?php if($meeting->category): ?>
                                            🏢 <?php echo e(is_string($meeting->category) ? $meeting->category : ($meeting->category->name ?? 'عام')); ?> •
                                        <?php endif; ?>
                                        <?php echo e(\Carbon\Carbon::parse($meeting->date_time)->translatedFormat('d M')); ?>

                                        • <?php echo e(\Carbon\Carbon::parse($meeting->date_time)->format('h:i A')); ?>

                                    </div>
                                </div>
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
                                <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-diagram-project"></i></div>
                                <div class="dw-info">
                                    <div class="dw-name"><?php echo e($project->name); ?></div>
                                    <div class="dw-meta">
                                        <?php if($project->category): ?>
                                            <?php echo e($project->category->icon ?? '🏢'); ?> <?php echo e($project->category->name); ?>  •
                                        <?php endif; ?>
                                        <?php if($project->start_date): ?>
                                            بدأ <?php echo e($project->start_date->format('Y-m-d')); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                                    $statusLabels = ['active'=>'نشط','planning'=>'تخطيط','idea'=>'فكرة','completed'=>'مكتمل','canceled'=>'ملغى'];
                                    $statusColors = ['active'=>'approved','planning'=>'pending','idea'=>'new','completed'=>'approved','canceled'=>'rejected'];
                                    $sl = $statusLabels[$project->status] ?? $project->status;
                                    $sc = $statusColors[$project->status] ?? 'new';
                                ?>
                                <span class="dw-badge <?php echo e($sc); ?>"><?php echo e($sl); ?></span>
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
                        <a href="<?php echo e(route('volunteer')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $latestOpportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isClosed = $opp->deadline && \Carbon\Carbon::parse($opp->deadline)->isPast();
                            ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706"><i class="fa-solid fa-hand-holding-heart"></i></div>
                                <div class="dw-info">
                                    <div class="dw-name"><?php echo e($opp->title); ?></div>
                                    <div class="dw-meta">
                                        <?php if($opp->category): ?>
                                            <?php echo e($opp->category->icon ?? '💡'); ?> <?php echo e($opp->category->name); ?> •
                                        <?php endif; ?>
                                        <?php echo e($opp->direction === 'remote' ? '💻 عن بعد' : ($opp->direction === 'both' ? '🔄 مزدوج' : '📍 حضوري')); ?>

                                        <?php if($opp->deadline): ?>
                                            • حتى <?php echo e(\Carbon\Carbon::parse($opp->deadline)->translatedFormat('d M')); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="dw-badge <?php echo e($isClosed ? 'rejected' : 'approved'); ?>"><?php echo e($isClosed ? 'منتهية' : 'مفتوحة'); ?></span>
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

            </div><!-- /.dash-grid row 1 -->

            <!-- ══ ROW 2: Requests ══ -->
            <div class="dash-grid" style="margin-top:1.25rem">

                <!-- طلبات الفرص -->
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-hand-holding-heart"></i> طلبات فرص التطوع</div>
                        <a href="<?php echo e(route('volunteer')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $latestOppRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $applicant = $req->association?->association_name ?? $req->user?->full_name ?? $req->user?->name ?? '—';
                                $oppTitle  = $req->opportunity?->title ?? 'فرصة محذوفة';
                            ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <div class="dw-info">
                                    <div class="dw-name"><?php echo e($oppTitle); ?></div>
                                    <div class="dw-meta">مقدم من: <?php echo e($applicant); ?> • <?php echo e(\Carbon\Carbon::parse($req->created_at)->diffForHumans()); ?></div>
                                </div>
                                <span class="dw-badge pending">⏳ معلق</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد طلبات فرص معلقة ✅</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- طلبات المشاريع -->
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-diagram-project"></i> طلبات المشاريع المشتركة</div>
                        <a href="<?php echo e(route('joint-projects')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $latestProjApps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $applicant  = $app->association?->association_name ?? $app->user?->full_name ?? $app->user?->name ?? '—';
                                $projTitle  = $app->project?->name ?? 'مشروع محذوف';
                            ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)">
                                    <i class="fa-solid fa-people-group"></i>
                                </div>
                                <div class="dw-info">
                                    <div class="dw-name"><?php echo e($projTitle); ?></div>
                                    <div class="dw-meta">مقدم من: <?php echo e($applicant); ?> • <?php echo e(\Carbon\Carbon::parse($app->created_at)->diffForHumans()); ?></div>
                                </div>
                                <span class="dw-badge pending">⏳ معلق</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد طلبات مشاريع معلقة ✅</p>
                        <?php endif; ?>
                    </ul>
                </div>

            </div><!-- /.dash-grid row 2 -->

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

        // Refresh upcoming meetings for the dashboard
        async function refreshDashMeetings() {
            try {
                const res = await fetch('/api/meetings', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                
                const list = document.getElementById('dash-meetings-list');
                if (!list) return;

                const upcoming = (Array.isArray(data) ? data : (data.meetings || []))
                    .filter(m => m.status === 'upcoming' || !m.status)
                    .slice(0, 5); // display max 5 meetings
                
                if (upcoming.length > 0) {
                    list.innerHTML = upcoming.map(m => {
                        const iconClass = m.type === 'online' ? 'fa-video' : 'fa-users';
                        const catStr = m.cat ? `🏢 ${m.cat} • ` : '';
                        const dateObj = new Date(m.date + 'T' + (m.time || '00:00') + ':00');
                        
                        // Basic format, could use Intl.DateTimeFormat
                        const day = dateObj.toLocaleDateString('ar-SA', { day: 'numeric', month: 'short' });
                        const time = m.time ? dateObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : '';
                        
                        return `
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)">
                                    <i class="fa-solid ${iconClass}"></i>
                                </div>
                                <div class="dw-info">
                                    <div class="dw-name">${m.title}</div>
                                    <div class="dw-meta">${catStr}${day} ${time ? '• ' + time : ''}</div>
                                </div>
                                <a href="/meetings" class="dw-action">انضمام</a>
                            </li>
                        `;
                    }).join('');
                } else {
                    list.innerHTML = '<p style="text-align:center; padding: 1rem; color: #888;">لا توجد اجتماعات قادمة</p>';
                }
            } catch(e) { /* silently fail */ }
        }

        refreshDashPending();
        refreshDashMeetings();
        setInterval(refreshDashPending, 30000);
        setInterval(refreshDashMeetings, 30000); // refresh every 30s
    </script>

  <?php echo $__env->make('layouts.notif-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\tkamel-abdullah1\tkamel\resources\views/dashboard.blade.php ENDPATH**/ ?>