<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تكامل | خدمات مبادرون</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/services.css')); ?>">
</head>

<body class="dashboard-body">
    <?php echo $__env->make('layouts.sidebar-user', ['activeNav' => 'services'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main">
        <?php echo $__env->make('layouts.topbar', ['title' => 'خدمات مبادرون', 'userName' => (Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'), 'userAv' => mb_substr((Auth::user()?->full_name ?? session('association.name') ?? 'مستخدم'), 0, 1), 'showNotif' => false, 'userRole' => '<span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700"><i class="fa-solid fa-eye" style="font-size:.6rem"></i> عرض فقط</span>'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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

    <!-- Request Detail / Edit Modal -->
    <div class="s-modal-overlay" id="detail-modal" onclick="bgCloseDetail(event)">
        <div class="s-custom-modal">
            <div class="s-cm-header" style="background: linear-gradient(135deg,#1e293b,#334155)">
                <h2><i class="fa-solid fa-file-lines"></i> <span id="d-modal-title">تفاصيل الطلب</span></h2>
                <button class="s-cm-close" onclick="closeDetailModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="s-cm-body" id="d-view-section">
                <div class="d-cards-grid">
                    <div class="d-info-card">
                        <span class="d-card-label">نوع الخدمة</span>
                        <span id="d-type-label" class="d-card-value d-highlight"></span>
                    </div>
                    <div class="d-info-card">
                        <span class="d-card-label">العنوان</span>
                        <span id="d-title" class="d-card-value"></span>
                    </div>
                    <div class="d-info-card">
                        <span class="d-card-label">التفاصيل</span>
                        <span id="d-details" class="d-card-value"></span>
                    </div>
                    <div class="d-info-card">
                        <span class="d-card-label">التاريخ المفضل</span>
                        <span id="d-date" class="d-card-value"></span>
                    </div>
                    <div class="d-info-card">
                        <span class="d-card-label">الميزانية (ر.س)</span>
                        <span id="d-budget" class="d-card-value"></span>
                    </div>
                    <div class="d-info-card">
                        <span class="d-card-label">الحالة الحالية</span>
                        <span id="d-status" class="d-card-value"></span>
                    </div>
                </div>
            </div>
            <div class="s-cm-body" id="d-edit-section" style="display:none">
                <!-- Edit mode -->
                <div class="s-form-group">
                    <label>نوع الخدمة <span class="s-req">*</span></label>
                    <div class="s-svc-selector">
                        <label class="s-sr-radio"><input type="radio" name="editSvcType" value="units" checked><span><i class="fa-solid fa-building"></i> بناء وحدات/أنظمة</span></label>
                        <label class="s-sr-radio"><input type="radio" name="editSvcType" value="training"><span><i class="fa-solid fa-users"></i> تدريب المتطوعين</span></label>
                        <label class="s-sr-radio"><input type="radio" name="editSvcType" value="initiatives"><span><i class="fa-solid fa-handshake"></i> تنسيق المبادرات</span></label>
                        <label class="s-sr-radio"><input type="radio" name="editSvcType" value="consulting"><span><i class="fa-regular fa-lightbulb"></i> استشارات متخصصة</span></label>
                        <label class="s-sr-radio"><input type="radio" name="editSvcType" value="other"><span><i class="fa-regular fa-circle-question"></i> طلب آخر</span></label>
                    </div>
                </div>
                <div class="s-form-group">
                    <label>عنوان الطلب <span class="s-req">*</span></label>
                    <input type="text" id="e-title">
                </div>
                <div class="s-form-group">
                    <label>تفاصيل الطلب <span class="s-req">*</span></label>
                    <textarea id="e-details"></textarea>
                </div>
                <div class="s-form-row">
                    <div class="s-form-group s-half">
                        <label>التاريخ المفضل</label>
                        <input type="date" id="e-date">
                    </div>
                    <div class="s-form-group s-half">
                        <label>الميزانية (ر.س)</label>
                        <input type="number" id="e-budget" value="0">
                    </div>
                </div>
            </div>
            <div class="s-cm-footer" id="d-view-footer">
                <button class="s-btn-submit" id="d-edit-btn" onclick="switchToEditMode()"><i class="fa-solid fa-pen"></i> تعديل</button>
                <button class="s-btn-delete" onclick="confirmDeleteReq()"><i class="fa-solid fa-trash"></i> حذف</button>
                <button class="s-btn-cancel" onclick="closeDetailModal()">إغلاق</button>
            </div>
            <div class="s-cm-footer" id="d-edit-footer" style="display:none">
                <button class="s-btn-submit" id="e-save-btn" onclick="saveEdits()"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
                <button class="s-btn-cancel" onclick="switchToViewMode()">إلغاء</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="s-success-overlay" id="delete-confirm-modal">
        <div class="s-success-card">
            <div class="s-success-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h2 class="s-success-title">هل أنت متأكد؟</h2>
            <p class="s-success-sub">سيتم حذف الطلب نهائياً ولا يمكن التراجع عن هذا الإجراء</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <button class="s-success-btn" style="background:linear-gradient(135deg,#ef4444,#dc2626)" onclick="executeDelete()">نعم، احذف</button>
                <button class="s-success-btn" style="background:#64748b" onclick="closeDeleteConfirm()">إلغاء</button>
            </div>
        </div>
    </div>

    <style>
    .s-btn-delete {
        padding: 10px 20px;
        background: linear-gradient(135deg,#ef4444,#dc2626);
        color:#fff; border:none; border-radius:10px;
        font-size:.9rem; font-weight:700; cursor:pointer;
        font-family:inherit; transition:opacity .2s;
        display:inline-flex; align-items:center; gap:6px;
    }
    .s-btn-delete:hover { opacity:.85; }
    .d-cards-grid { display:flex; flex-direction:column; gap:10px; }
    .d-info-card {
        display:flex; flex-direction:column; align-items:flex-end;
        gap:4px; padding:12px 14px;
        background:#f8fafc; border-radius:12px;
        border: 1px solid #e2e8f0;
    }
    .d-card-label { font-size:.78rem; color:#94a3b8; font-weight:600; }
    .d-card-value { font-size:.95rem; color:#1e293b; font-weight:700; text-align:right; }
    .d-highlight { color: #6366f1; }
    </style>

    <!-- Success Modal -->
    <div class="s-success-overlay" id="success-modal">
        <div class="s-success-card">
            <div class="s-success-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 class="s-success-title" id="success-title">تم تسجيل طلبك</h2>
            <p class="s-success-sub" id="success-sub">سيتم مراجعة طلبك من قِبل الفريق المختص</p>
            <button class="s-success-btn" onclick="closeSuccessModal()">حسناً</button>
        </div>
    </div>

    <style>
    .s-success-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .s-success-overlay.open { display: flex; }
    .s-success-card {
        background: #fff;
        border-radius: 20px;
        padding: 48px 40px 40px;
        text-align: center;
        max-width: 420px;
        width: 90%;
        animation: successPop .3s ease;
    }
    @keyframes successPop {
        from { transform: scale(.85); opacity: 0; }
        to   { transform: scale(1);  opacity: 1; }
    }
    .s-success-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2ab89a, #1a9b80);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    .s-success-icon i {
        font-size: 32px;
        color: #fff;
    }
    .s-success-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 12px;
    }
    .s-success-sub {
        font-size: .95rem;
        color: #64748b;
        margin: 0 0 28px;
        line-height: 1.6;
    }
    .s-success-btn {
        display: inline-block;
        padding: 12px 40px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: opacity .2s;
    }
    .s-success-btn:hover { opacity: .88; }
    </style>

    <script src="<?php echo e(asset('js/services.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/a-22/Documents/tkamel/resources/views/user/services.blade.php ENDPATH**/ ?>