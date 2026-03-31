<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تكامل | لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="dashboard-body">
    @include('layouts.sidebar-user', ['activeNav' => 'dashboard'])


    <div class="main">
        @include('layouts.topbar', ['title' => 'لوحة التحكم', 'userName' => Auth::user()->full_name, 'userAv' => mb_substr(Auth::user()->full_name, 0, 1), 'showNotif' => false, 'userRole' => '<span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700"><i class="fa-solid fa-eye" style="font-size:.6rem"></i> عرض فقط</span>'])

        </div>

        <div class="content">
            <div class="page-hd">
                <div>
                    <div class="ph-title">مرحباً، {{ Auth::user()->full_name }} 👋</div>
                    <div class="ph-sub">إليك ملخص أهم النشاطات والإحصائيات — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
                </div>
            </div>

            <div class="stats-row" style="margin-bottom:2rem">
                <div class="stat-card" style="--sc:var(--teal-glow)"><div class="s-icon" style="background:rgba(42,184,208,0.1)">🏢</div><div><span class="s-num">85</span><span class="s-lbl">إجمالي الجمعيات</span></div></div>
                <div class="stat-card" style="--sc:var(--green)"><div class="s-icon" style="background:rgba(46,170,120,0.1)">💡</div><div><span class="s-num">1,200</span><span class="s-lbl">الفرص المتاحة</span></div></div>
                <div class="stat-card" style="--sc:var(--blue)"><div class="s-icon" style="background:rgba(29,111,164,0.1)">🤝</div><div><span class="s-num">34</span><span class="s-lbl">شراكة استراتيجية</span></div></div>
                <div class="stat-card" style="--sc:var(--purple)"><div class="s-icon" style="background:rgba(109,40,217,0.1)">💰</div><div><span class="s-num">12.5M</span><span class="s-lbl">الميزانية المشتركة</span></div></div>
            </div>

            <div class="dash-grid">
                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-regular fa-calendar-check"></i> الاجتماعات القادمة</div>
                        <a href="{{ route('user.meetings') }}" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)"><i class="fa-solid fa-video"></i></div>
                            <div class="dw-info"><div class="dw-name">مراجعة الربع الثالث</div><div class="dw-meta">أكتوبر 25 • 10:00 صباحاً</div></div>
                            <span class="dw-badge new">قادم</span>
                        </li>
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-building"></i></div>
                            <div class="dw-info"><div class="dw-name">التخطيط الاستراتيجي 2025</div><div class="dw-meta">أكتوبر 28 • 01:00 مساءً</div></div>
                            <span class="dw-badge new">قادم</span>
                        </li>
                    </ul>
                </div>

                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-chart-pie"></i> المشاريع المشتركة</div>
                        <a href="{{ route('user.joint-projects') }}" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-leaf"></i></div>
                            <div class="dw-info"><div class="dw-name">المبادرة الخضراء</div><div class="dw-meta">نسبة الإنجاز: 90%</div></div>
                            <div class="dw-bar"><div class="dw-bar-fill" style="width:90%;background:var(--green)"></div></div>
                        </li>
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(109,40,217,0.12);color:var(--purple)"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="dw-info"><div class="dw-name">تطبيق الخدمات الرقمية</div><div class="dw-meta">نسبة الإنجاز: 75%</div></div>
                            <div class="dw-bar"><div class="dw-bar-fill" style="width:75%;background:var(--purple)"></div></div>
                        </li>
                    </ul>
                </div>

                <div class="dash-widget">
                    <div class="dw-header">
                        <div class="dw-title"><i class="fa-solid fa-lightbulb"></i> فرص التطوع والدعم</div>
                        <a href="{{ route('user.consulting') }}" class="dw-link">عرض الكل</a>
                    </div>
                    <ul class="dw-list">
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706"><i class="fa-solid fa-box-open"></i></div>
                            <div class="dw-info"><div class="dw-name">توزيع السلال الغذائية</div><div class="dw-meta">نحتاج 50 متطوعاً • تبدأ غداً</div></div>
                            <span class="dw-badge pending">متاحة</span>
                        </li>
                        <li class="dw-item">
                            <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)"><i class="fa-solid fa-chalkboard-user"></i></div>
                            <div class="dw-info"><div class="dw-name">برنامج الدعم الأكاديمي</div><div class="dw-meta">المستهدف 200 طالب</div></div>
                            <span class="dw-badge pending">متاحة</span>
                        </li>
                    </ul>
                </div>


            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
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
