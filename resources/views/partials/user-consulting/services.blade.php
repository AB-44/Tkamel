{{--
  partials/user-consulting/services.blade.php
  "Mubadiroon Services" section for the user SPA — driven by public/js/services.js
--}}
<div class="view" id="view-services">
  <div class="content services-content">

    <!-- PAGE HEADER -->
    <div class="page-header">
      <div class="ph-text">
        <h1>خدمات مبادرون</h1>
        <p>اختر الخدمة المناسبة لجمعيتك وقدّم طلبك في خطوات بسيطة</p>
      </div>
    </div>

    <!-- TABS -->
    <div class="tabs-bar">
      <button class="tabx on" data-tab="tab-services" onclick="switchTab('tab-services', this)">
        <i class="fa-solid fa-grid-2"></i> الخدمات المتاحة
      </button>
      <button class="tabx" data-tab="tab-mine" onclick="switchTab('tab-mine', this)">
        <i class="fa-solid fa-paper-plane"></i> طلباتي
        <span class="tn" id="tn-mine">0</span>
      </button>
    </div>

    <!-- TAB: SERVICES GRID -->
    <div id="tab-services" class="tab-pane active-pane">
      <div class="services-grid" id="services-grid"></div>
    </div>

    <!-- TAB: MY REQUESTS -->
    <div id="tab-mine" class="tab-pane">
      <div class="my-reqs-list" id="my-reqs-list"></div>
    </div>

  </div><!-- /content -->
</div><!-- /view-services -->

<!-- ══ SERVICE MODAL (Rich Form) ══ -->
<div class="svc-overlay" id="ov-service" onclick="bgClose(event,'ov-service')">
  <div class="svc-modal" onclick="event.stopPropagation()">
    <div class="sm-header" id="sm-header">
      <div class="sm-header-bg" id="sm-header-bg"></div>
      <div class="sm-header-content">
        <div class="sm-icon-wrap" id="sm-icon-wrap"></div>
        <div class="sm-hd-text">
          <div class="sm-title" id="sm-title"></div>
          <div class="sm-sub"   id="sm-sub"></div>
        </div>
      </div>
      <button class="sm-close" onclick="closeOv('ov-service')">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="sm-body">
      <div class="sm-progress-bar">
        <div class="sm-progress-fill" id="sm-prog-fill"></div>
      </div>
      <div class="sm-form-fields" id="sm-fields"></div>
    </div>
    <div class="sm-footer">
      <button class="sm-btn-cancel" onclick="closeOv('ov-service')">إلغاء</button>
      <button class="sm-btn-submit" id="sm-submit" onclick="submitService()">
        <i class="fa-solid fa-paper-plane"></i> إرسال الطلب
      </button>
    </div>
  </div>
</div>

<!-- ══ REQUEST DETAIL MODAL ══ -->
<div class="svc-overlay" id="ov-detail" onclick="bgClose(event,'ov-detail')">
  <div class="det-modal" onclick="event.stopPropagation()">
    <div class="det-hd" id="det-hd">
      <div>
        <div class="det-hd-title" id="det-hd-title"></div>
        <div class="det-hd-sub"   id="det-hd-sub"></div>
      </div>
      <button class="sm-close" onclick="closeOv('ov-detail')">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="det-body" id="det-body"></div>
    <div class="det-ft" id="det-footer">
      <button class="sm-btn-cancel" onclick="closeOv('ov-detail')">إغلاق</button>
    </div>
  </div>
</div>

<!-- ══ DELETE CONFIRM MODAL ══ -->
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

<!-- ══ SUCCESS MODAL ══ -->
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
