<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تكامل | لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    
</head>

<body class="dashboard-body">
    <aside class="sidebar">
        <div class="sb-logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="" onerror="this.style.display='none'">
            <span>تكامل</span>
        </div>
        <nav class="sb-nav">
            <div class="sb-section">الرئيسية</div>
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-item active"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                </svg>لوحة التحكم</a>
            <div class="sb-section">إدارة الأنشطة</div>
            <a href="<?php echo e(route('meetings')); ?>" class="nav-item" id="nav-meetings" onclick="openMeetingsPage()"><svg
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <path d="M16 2v4M8 2v4M3 10h18" />
                </svg>الاجتماعات</a>
            <a href="<?php echo e(route('consulting')); ?>" class="nav-item" data-vol onclick="backToVolunteer()"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                </svg>فرص التطوع<span class="nav-badge" id="nb-opps">0</span></a>
            <a href="<?php echo e(route('orders')); ?>" class="nav-item" onclick="showAdminRequests()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 11l3 3L22 4" />
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                </svg>الطلبات<span class="nav-badge red" id="nb-reqs">0</span></a>
            <a href="<?php echo e(route('joint-projects')); ?>" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>المشاريع</a>
            <div class="sb-section">خدمات مبادرون</div>
            <div class="nav-parent" id="np-services" onclick="toggleServices()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 4 2-7L3 9h7z" />
                </svg>
                خدمات مبادرون
                <svg class="np-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    width="12" height="12">
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
                <div class="tb-title" id="topbar-title">لوحة التحكم</div>
                <div class="tb-crumb">تكامل / <span id="topbar-crumb">لوحة التحكم</span></div>
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

        <div class="main-content-scroll" style="padding: 2.5rem 3rem; overflow-y: auto;">

            <div class="welcome-section" style="margin-bottom: 2rem;">
                <h1 style="font-size: 1.8rem; color: var(--ink); font-weight: 800; margin-bottom: 6px;">مرحباً بعودتك،
                    جمعية مبادرون</h1>
                <p style="color: var(--muted); font-size: 0.95rem;">إليك ملخص لأهم النشاطات والإحصائيات في المنصة.</p>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon teal"><i class="fa-solid fa-hotel"></i></div>
                    <div class="stat-info">
                        <h3>85</h3>
                        <p>إجمالي الجمعيات</p>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fa-solid fa-lightbulb"></i></div>
                    <div class="stat-info">
                        <h3>1,200</h3>
                        <p>الفرص المتاحة</p>
                    </div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-icon blue"><i class="fa-solid fa-handshake-angle"></i></div>
                    <div class="stat-info">
                        <h3>34</h3>
                        <p>شراكة استراتيجية</p>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon purple"><i class="fa-solid fa-sack-dollar"></i></div>
                    <div class="stat-info">
                        <h3>12.5M</h3>
                        <p>الميزانية المشتركة</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Widgets -->
            <div class="dash-sections">
                <!-- Meetings -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2><i class="fa-regular fa-calendar-check"></i> الاجتماعات القادمة</h2>
                        <a href="<?php echo e(route('meetings')); ?>">عرض الكل</a>
                    </div>
                    <ul class="dash-list">
                        <li class="dash-list-item">
                            <div class="item-icon blue"><i class="fa-solid fa-video"></i></div>
                            <div class="item-details">
                                <h4>مراجعة الربع الثالث</h4>
                                <p>أكتوبر 25 • 10:00 صباحاً</p>
                            </div>
                            <button class="item-action">انضمام</button>
                        </li>
                        <li class="dash-list-item">
                            <div class="item-icon green"><i class="fa-solid fa-building"></i></div>
                            <div class="item-details">
                                <h4>التخطيط الاستراتيجي 2024</h4>
                                <p>أكتوبر 28 • 01:00 مساءً</p>
                            </div>
                            <button class="item-action">التفاصيل</button>
                        </li>
                    </ul>
                </div>

                <!-- Projects -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2><i class="fa-solid fa-chart-pie"></i> المشاريع المشتركة</h2>
                        <a href="<?php echo e(route('joint-projects')); ?>">عرض الكل</a>
                    </div>
                    <ul class="dash-list">
                        <li class="dash-list-item">
                            <div class="item-icon green"><i class="fa-solid fa-leaf"></i></div>
                            <div class="item-details">
                                <h4>المبادرة الخضراء</h4>
                                <p>نسبة الإنجاز: 90%</p>
                            </div>
                            <div style="width: 60px; height: 6px; background: rgba(0,0,0,0.1); border-radius: 3px;">
                                <div style="width: 90%; height: 100%; background: var(--green); border-radius: 3px;">
                                </div>
                            </div>
                        </li>
                        <li class="dash-list-item">
                            <div class="item-icon purple"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="item-details">
                                <h4>تطبيق الخدمات الرقمية</h4>
                                <p>نسبة الإنجاز: 75%</p>
                            </div>
                            <div style="width: 60px; height: 6px; background: rgba(0,0,0,0.1); border-radius: 3px;">
                                <div style="width: 75%; height: 100%; background: var(--purple); border-radius: 3px;">
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Opportunities -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2><i class="fa-solid fa-lightbulb"></i> فرص التطوع والدعم</h2>
                        <a href="<?php echo e(route('consulting')); ?>">عرض الكل</a>
                    </div>
                    <ul class="dash-list">
                        <li class="dash-list-item">
                            <div class="item-icon yellow"><i class="fa-solid fa-box-open"></i></div>
                            <div class="item-details">
                                <h4>توزيع السلال الغذائية</h4>
                                <p>نحتاج 50 متطوعاً • تبدأ غداً</p>
                            </div>
                            <button class="item-action">تقديم</button>
                        </li>
                        <li class="dash-list-item">
                            <div class="item-icon blue"><i class="fa-solid fa-chalkboard-user"></i></div>
                            <div class="item-details">
                                <h4>برنامج الدعم الأكاديمي</h4>
                                <p>فرصة للتدريس والتمكين • المستهدف 200 طالب</p>
                            </div>
                            <button class="item-action">تقديم</button>
                        </li>
                    </ul>
                </div>

                <!-- Requests -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2><i class="fa-solid fa-clipboard-list"></i> أحدث الطلبات</h2>
                        <a href="<?php echo e(route('orders')); ?>">عرض الكل</a>
                    </div>
                    <ul class="dash-list">
                        <li class="dash-list-item">
                            <div class="item-icon purple"><i class="fa-solid fa-file-signature"></i></div>
                            <div class="item-details">
                                <h4>طلب ترخيص فعالية</h4>
                                <p>مقدم من: جمعية الرواد • قبل ساعتين</p>
                            </div>
                            <span
                                style="font-size: 0.8rem; background: rgba(245,158,11,0.15); color: #f59e0b; padding: 4px 10px; border-radius: 20px; font-weight: 600;">قيد
                                المراجعة</span>
                        </li>
                        <li class="dash-list-item">
                            <div class="item-icon green"><i class="fa-solid fa-handshake"></i></div>
                            <div class="item-details">
                                <h4>طلب شراكة دعم فني</h4>
                                <p>مقدم من: مؤسسة العطاء • منذ يوم</p>
                            </div>
                            <span
                                style="font-size: 0.8rem; background: rgba(46,170,120,0.15); color: var(--green); padding: 4px 10px; border-radius: 20px; font-weight: 600;">مقبول</span>
                        </li>
                        <li class="dash-list-item">
                            <div class="item-icon blue"><i class="fa-solid fa-money-bill-wave"></i></div>
                            <div class="item-details">
                                <h4>طلب منحة تطويرية</h4>
                                <p>مقدم من: جمعية الإحسان • منذ يومين</p>
                            </div>
                            <span
                                style="font-size: 0.8rem; background: rgba(26,107,124,0.1); color: var(--teal); padding: 4px 10px; border-radius: 20px; font-weight: 600;">جديد</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
</body>

</html><?php /**PATH /home/a-22/Tkamel_project/المشروع الحالي/Tkamel_merged/tkamel_merged/resources/views/dashboard.blade.php ENDPATH**/ ?>