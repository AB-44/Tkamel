<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تكامل | خدمات مبادرون</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
</head>

<body class="dashboard-body">
    @include('layouts.sidebar-user', ['activeNav' => 'services'])

    <div class="main">
        @include('layouts.topbar', ['title' => 'خدمات مبادرون', 'userName' => Auth::user()->full_name, 'userAv' => mb_substr(Auth::user()->full_name, 0, 1), 'showNotif' => false, 'userRole' => '<span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700"><i class="fa-solid fa-eye" style="font-size:.6rem"></i> عرض فقط</span>'])

        <div class="content services-content">
            <!-- Stats -->
            <div class="s-stats-grid">
              <div class="s-stat-card">
                  <div class="s-stat-value text-blue" id="st-total">0</div>
                  <div class="s-stat-label">إجمالي الطلبات</div>
              </div>
              <div class="s-stat-card">
                  <div class="s-stat-value text-orange" id="st-pending">0</div>
                  <div class="s-stat-label">قيد المراجعة</div>
              </div>
              <div class="s-stat-card">
                  <div class="s-stat-value text-green" id="st-approved">0</div>
                  <div class="s-stat-label">مقبولة</div>
              </div>
              <div class="s-stat-card">
                  <div class="s-stat-value text-red" id="st-rejected">0</div>
                  <div class="s-stat-label">مرفوضة</div>
              </div>
            </div>

            <!-- Categories -->
            <div class="s-services-cats">
               <button class="s-svc-btn svc-blue" onclick="openNewReq('units')">
                  <i class="fa-solid fa-building"></i>
                  بناء وحدات/أنظمة
               </button>
               <button class="s-svc-btn svc-green" onclick="openNewReq('training')">
                  <i class="fa-solid fa-users"></i>
                  تدريب المتطوعين
               </button>
               <button class="s-svc-btn svc-yellow" onclick="openNewReq('initiatives')">
                  <i class="fa-solid fa-handshake"></i>
                  تنسيق المبادرات
               </button>
               <button class="s-svc-btn svc-purple" onclick="openNewReq('consulting')">
                  <i class="fa-regular fa-lightbulb"></i>
                  استشارات متخصصة
               </button>
               <button class="s-svc-btn svc-gray" onclick="openNewReq('other')">
                  <i class="fa-regular fa-circle-question"></i>
                  طلب آخر
               </button>
            </div>

            <!-- Requests List -->
            <div class="s-req-section">
               <div class="s-rs-header">
                  <h3>طلباتي</h3>
                  <button class="s-refresh-btn" onclick="renderMyReqs()"><i class="fa-solid fa-rotate-right"></i></button>
               </div>
               
               <div id="s-req-list"></div>
               
               <div class="s-empty-state" id="s-empty-state">
                  <i class="fa-solid fa-wand-magic-sparkles"></i>
                  <p>لا توجد طلبات بعد</p>
                  <button class="s-btn-primary-light" onclick="openNewReq('other')">أرسل أول طلب</button>
               </div>
            </div>
            
        </div>
    </div>

    <!-- Modal -->
    <div class="s-modal-overlay" id="req-modal" onclick="bgCloseReq(event)">
        <div class="s-custom-modal">
            <div class="s-cm-header">
                <h2><i class="fa-solid fa-wand-magic-sparkles"></i> طلب خدمة جديد</h2>
                <button class="s-cm-close" onclick="closeReqModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="s-cm-body">
                <div class="s-form-group">
                    <label>نوع الخدمة <span class="s-req">*</span></label>
                    <div class="s-svc-selector">
                        <label class="s-sr-radio"><input type="radio" name="svcType" value="units" checked><span><i class="fa-solid fa-building"></i> بناء وحدات/أنظمة</span></label>
                        <label class="s-sr-radio"><input type="radio" name="svcType" value="training"><span><i class="fa-solid fa-users"></i> تدريب المتطوعين</span></label>
                        <label class="s-sr-radio"><input type="radio" name="svcType" value="initiatives"><span><i class="fa-solid fa-handshake"></i> تنسيق المبادرات</span></label>
                        <label class="s-sr-radio"><input type="radio" name="svcType" value="consulting"><span><i class="fa-regular fa-lightbulb"></i> استشارات متخصصة</span></label>
                        <label class="s-sr-radio"><input type="radio" name="svcType" value="other"><span><i class="fa-regular fa-circle-question"></i> طلب آخر</span></label>
                    </div>
                </div>

                <div class="s-form-group">
                    <label>عنوان الطلب <span class="s-req">*</span></label>
                    <input type="text" id="f-title" placeholder="مثال: تطوير نظام متابعة المتطوعين">
                </div>

                <div class="s-form-group">
                    <label>تفاصيل الطلب <span class="s-req">*</span></label>
                    <textarea id="f-details" placeholder="اشرح ما تحتاجه بالتفصيل..."></textarea>
                </div>

                <div class="s-form-row">
                    <div class="s-form-group s-half">
                        <label>التاريخ المفضل</label>
                        <input type="date" id="f-date">
                    </div>
                    <div class="s-form-group s-half">
                        <label>الميزانية (ر.س)</label>
                        <input type="number" id="f-budget" value="0">
                    </div>
                </div>
            </div>
            <div class="s-cm-footer">
                <button class="s-btn-submit" onclick="submitReq()"><i class="fa-solid fa-wand-magic-sparkles"></i> إرسال الطلب</button>
                <button class="s-btn-cancel" onclick="closeReqModal()">إلغاء</button>
            </div>
        </div>
    </div>
    
    <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

    <script src="{{ asset('js/services.js') }}"></script>
</body>
</html>
