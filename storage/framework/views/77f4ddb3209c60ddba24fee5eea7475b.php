<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>صفحة الطلبات</title>
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="<?php echo e(asset('css/orders.css')); ?>">
    
</head>

<body>

    <!-- FLOATING BG ANIMATION -->
    <div class="bg-bubbles">
        <div class="bg-shape bg-teal"></div>
        <div class="bg-shape bg-purple"></div>
        <div class="bg-shape bg-blue"></div>
    </div>

    <aside class="sidebar">
        <div class="sb-logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="" onerror="this.style.display='none'">
            <span>تكامل</span>
        </div>
        <nav class="sb-nav">
            <div class="sb-section">الرئيسية</div>
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
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
            <a href="<?php echo e(route('orders')); ?>" class="nav-item active" onclick="showAdminRequests(this)"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
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
                <div class="tb-title" id="topbar-title">فرص التطوع</div>
                <div class="tb-crumb">تكامل / <span id="topbar-crumb">فرص التطوع</span></div>
            </div>
            <div class="tb-right">
                <div class="notif-btn" id="notif-btn" onclick="toggleNotifs()">
                    <div class="notif-dot" id="notif-dot" style="display:none"></div>
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

        <main>

            <!-- PAGE HEAD -->
            <div class="page-head">
                <div>
                    <h1>📋 صفحة الطلبات</h1>
                    <p>إدارة طلبات إنشاء الحسابات وعرض تصنيفات الجمعيات المسجلة</p>
                </div>
            </div>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">إجمالي الطلبات</div>
                    <div class="stat-value">48</div>
                    <div class="stat-sub text-blue">↑ 12 هذا الشهر</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">قيد المراجعة</div>
                    <div class="stat-value text-yellow">12</div>
                    <div class="stat-sub text-yellow">تنتظر المعالجة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">تمت الموافقة</div>
                    <div class="stat-value text-green">30</div>
                    <div class="stat-sub text-green">↑ 62.5% نسبة القبول</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">مرفوضة</div>
                    <div class="stat-value text-red">6</div>
                    <div class="stat-sub text-red">12.5% نسبة الرفض</div>
                </div>
            </div>

            <!-- TABS -->
            <div class="section-tabs">
                <button class="tab-btn active" onclick="switchTab('requests', this)">طلبات إنشاء الحساب</button>
                <button class="tab-btn" onclick="switchTab('associations', this)">تصنيفات الجمعيات</button>
            </div>

            <!-- TAB: REQUESTS -->
            <div class="tab-content active" id="tab-requests">
                <div class="table-toolbar">
                    <div class="search-box">
                        <span>🔍</span>
                        <input type="text" placeholder="بحث بالاسم أو البريد الإلكتروني..."
                            onkeyup="filterTable(this.value)" />
                    </div>
                    <div class="filter-group">
                        <select onchange="filterByStatus(this.value)">
                            <option value="">جميع الحالات</option>
                            <option value="pending">قيد المراجعة</option>
                            <option value="approved">موافق عليها</option>
                            <option value="review">مراجعة إضافية</option>
                            <option value="rejected">مرفوضة</option>
                        </select>
                        <select>
                            <option>آخر 30 يوم</option>
                            <option>آخر 7 أيام</option>
                            <option>هذا الشهر</option>
                            <option>كل الوقت</option>
                        </select>
                    </div>
                </div>

                <div class="table-wrap">
                    <table id="requestsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>مقدم الطلب</th>
                                <th>نوع الحساب</th>
                                <th>الجمعية</th>
                                <th>تاريخ الطلب</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="requestsTbody">
                            <!-- rows injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: ASSOCIATIONS -->
            <div class="tab-content" id="tab-associations">

                <!-- CATEGORIES -->
                <div class="section-head">
                    <h2>🗂️ تصنيفات الجمعيات</h2>
                    <button class="btn btn-primary">+ إضافة تصنيف</button>
                </div>

                <div class="categories-grid" id="categoriesGrid">
                    <!-- injected by JS -->
                </div>

                <!-- ASSOCIATIONS LIST -->
                <div class="section-head">
                    <h2>🏢 الجمعيات المسجلة</h2>
                    <div class="filter-group">
                        <button class="btn btn-ghost" onclick="filterAssocByCat('')" style="border-width: 2px;">
                            <i class="fas fa-th-large"></i> جميع التصنيفات
                        </button>
                        <select id="catFilter" onchange="filterAssoc()">
                            <option value="">تصفية حسب...</option>
                            <option value="تعليمية">تعليمية</option>
                            <option value="خيرية">خيرية</option>
                            <option value="بيئية">بيئية</option>
                            <option value="صحية">صحية</option>
                            <option value="رياضية">رياضية</option>
                            <option value="ثقافية">ثقافية</option>
                        </select>
                    </div>
                </div>

                <div class="assoc-list" id="assocList">
                    <!-- injected by JS -->
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL -->
    <div class="modal-overlay" id="modal">
        <div class="modal">
            <div class="modal-head">
                <h3>تفاصيل الطلب</h3>
                <button class="btn btn-ghost" onclick="closeModal()" style="padding:.3rem .7rem;">✕</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button class="btn btn-ghost" onclick="closeModal()">إغلاق</button>
                <button class="btn btn-danger" onclick="closeModal()">رفض</button>
                <button class="btn btn-primary" onclick="closeModal()">✓ موافقة</button>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/orders.js')); ?>"></script>
</body>

</html><?php /**PATH /home/a-22/Tkamel_merged/tkamel_merged/resources/views/orders.blade.php ENDPATH**/ ?>