<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تكامل | لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
</head>

<body class="dashboard-body">
    <?php echo $__env->make('layouts.sidebar-user', ['activeNav' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="main">
        <?php echo $__env->make('layouts.topbar', ['title' => 'لوحة التحكم', 'userName' => $viewerName, 'userAv' => mb_substr($viewerName, 0, 1), 'showNotif' => false, 'userRole' => '<span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700"><i class="fa-solid fa-eye" style="font-size:.6rem"></i> عرض فقط</span>'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="content">
            <div class="page-hd">
                <div>
                    <div class="ph-title">مرحباً، <?php echo e($viewerName); ?></div>
                    <div class="ph-sub">إليك ملخص أهم النشاطات والإحصائيات — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
                </div>
            </div>

            <div class="stats-row" style="margin-bottom:2rem">
                <div class="stat-card" style="--sc:var(--teal-glow)"><div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-building"></i></div><div><span class="s-num"><?php echo e(number_format($stats['associations_count'])); ?></span><span class="s-lbl">إجمالي الجمعيات</span></div></div>
                <div class="stat-card" style="--sc:var(--green)"><div class="s-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-lightbulb"></i></div><div><span class="s-num"><?php echo e(number_format($stats['opportunities_count'])); ?></span><span class="s-lbl">الفرص المتاحة</span></div></div>
                <div class="stat-card" style="--sc:var(--blue)"><div class="s-icon" style="background:rgba(29,111,164,0.1)"><i class="fa-solid fa-handshake"></i></div><div><span class="s-num"><?php echo e(number_format($stats['projects_count'])); ?></span><span class="s-lbl">المشاريع المشتركة</span></div></div>
                <div class="stat-card" style="--sc:var(--purple)"><div class="s-icon" style="background:rgba(109,40,217,0.1)"><i class="fa-solid fa-circle-check"></i></div><div><span class="s-num"><?php echo e(number_format($stats['my_approved_requests'])); ?></span><span class="s-lbl">طلباتي المنجزة</span></div></div>
            </div>

            <div class="dash-grid">
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-regular fa-calendar-check"></i> الاجتماعات القادمة</div>
                        <a href="<?php echo e(route('user.meetings')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)"><i class="fa-solid fa-video"></i></div>
                                <div class="dw-info"><div class="dw-name"><?php echo e($meeting->title); ?></div><div class="dw-meta"><?php echo e(\Carbon\Carbon::parse($meeting->date_time)->translatedFormat('d M Y')); ?> • <?php echo e(\Carbon\Carbon::parse($meeting->date_time)->format('h:i A')); ?></div></div>
                                <span class="dw-badge new">قادم</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد اجتماعات قادمة</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-chart-pie"></i> المشاريع المشتركة</div>
                        <a href="<?php echo e(route('user.joint-projects')); ?>" class="dw-link">عرض الكل</a>
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

                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-lightbulb"></i> فرص التطوع والدعم</div>
                        <a href="<?php echo e(route('user.consulting')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $latestOpportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706"><i class="fa-solid fa-box-open"></i></div>
                                <div class="dw-info"><div class="dw-name"><?php echo e($opp->title); ?></div><div class="dw-meta"><?php echo e($opp->type ?? 'تطوع'); ?> • حتى <?php echo e($opp->deadline ? \Carbon\Carbon::parse($opp->deadline)->format('Y-m-d') : 'مفتوح'); ?></div></div>
                                <span class="dw-badge pending">متاحة</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لا توجد فرص تطوع ومبادرات</p>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-clipboard-list"></i> أحدث الطلبات</div>
                        <a href="<?php echo e(route('user.orders')); ?>" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <?php $__empty_1 = true; $__currentLoopData = $latestRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="dw-item">
                                <div class="dw-icon" style="background:rgba(109,40,217,0.12);color:var(--purple)"><i class="fa-solid fa-file-signature"></i></div>
                                <div class="dw-info"><div class="dw-name"><?php echo e($req->opportunity ? $req->opportunity->title : 'طلب تطوع'); ?></div><div class="dw-meta">مقدم منذ: <?php echo e(\Carbon\Carbon::parse($req->created_at)->diffForHumans()); ?></div></div>
                                <?php if($req->status === 'approved'): ?>
                                    <span class="dw-badge approved">مقبول</span>
                                <?php elseif($req->status === 'rejected'): ?>
                                    <span class="dw-badge rejected">مرفوض</span>
                                <?php else: ?>
                                    <span class="dw-badge pending">قيد المراجعة</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p style="text-align:center; padding: 1rem; color: #888;">لم تقم بتقديم أي طلبات بعد</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
    <style>
        .content{padding:2rem 2.5rem;overflow-y:auto;flex:1}
        .page-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
        .ph-title{font-size:1.45rem;font-weight:800;color:var(--ink)}
        .ph-sub{font-size:.83rem;color:var(--muted);margin-top:3px}
        .stats-row{display:flex;gap:14px;flex-wrap:wrap}
        .stat-card{flex:1;min-width:160px;background:var(--white);border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:14px;border:1.5px solid var(--border);box-shadow:var(--sh-sm);transition:transform .2s,box-shadow .2s}
        .stat-card:hover{transform:translateY(-3px);box-shadow:var(--sh-md)}
        .s-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
        .s-num{display:block;font-size:1.5rem;font-weight:900;color:var(--sc,var(--teal-glow));line-height:1.1}
        .s-lbl{display:block;font-size:.75rem;color:var(--muted);font-weight:500;margin-top:2px}
        .dash-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem}
        @media(max-width:900px){.dash-grid{grid-template-columns:1fr}}
        .dash-widget{background:var(--white);border:1.5px solid var(--border);border-radius:14px;padding:1.25rem 1.4rem;box-shadow:var(--sh-sm);display:flex;flex-direction:column;gap:.75rem;transition:box-shadow .2s}
        .dash-widget:hover{box-shadow:var(--sh-md)}
        .dw-header{display:flex;align-items:center;justify-content:space-between;padding-bottom:.75rem;border-bottom:1px solid var(--border)}
        .dw-title{font-size:.95rem;font-weight:800;color:var(--teal-deep);display:flex;align-items:center;gap:8px}
        .dw-title i{color:var(--teal)}
        .dw-link{font-size:.78rem;font-weight:700;color:var(--teal);text-decoration:none;opacity:.8;transition:opacity .15s}
        .dw-link:hover{opacity:1}
        .dw-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.6rem}
        .dw-item{display:flex;align-items:center;gap:12px;padding:10px 12px;background:var(--fog);border-radius:10px;transition:background .15s}
        .dw-item:hover{background:rgba(14,165,201,.06)}
        .dw-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
        .dw-info{flex:1;min-width:0}
        .dw-name{font-size:.87rem;font-weight:700;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .dw-meta{font-size:.75rem;color:var(--muted);margin-top:2px}
        .dw-bar{width:60px;height:5px;background:rgba(0,0,0,.08);border-radius:4px;flex-shrink:0;overflow:hidden}
        .dw-bar-fill{height:100%;border-radius:4px}
        .dw-badge{font-size:.74rem;font-weight:700;padding:3px 10px;border-radius:20px;white-space:nowrap;flex-shrink:0}
        .dw-badge.pending{background:rgba(245,158,11,.12);color:#d97706}
        .dw-badge.approved{background:rgba(46,170,120,.12);color:var(--green)}
        .dw-badge.new{background:rgba(14,165,201,.1);color:var(--teal)}
        .notif-btn{width:36px;height:36px;border-radius:10px;background:var(--fog);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s}
        .notif-btn:hover{border-color:rgba(42,184,208,.3);background:white}
    </style>
    <script>
        function toggleServices() {
            const sub = document.getElementById('submenu-services');
            const np  = document.getElementById('np-services');
            if (sub) sub.classList.toggle('open');
            if (np)  np.classList.toggle('open');
        }
    </script>
</body>
</html>
<?php /**PATH /home/a-22/Downloads/tkamel-updated (2)/tkamel/resources/views/user/dashboard.blade.php ENDPATH**/ ?>