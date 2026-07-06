{{--
  partials/user-consulting/orders.blade.php
  "My Requests" section for the user SPA — populated dynamically via /api/user/my-requests
  (see public/js/user-orders-spa.js)
--}}
<div class="view" id="view-orders">

  <!-- PAGE HEADER -->
  <div class="mr-header">
    <h1 class="mr-title">طلباتي</h1>
    <p class="mr-sub">جميع طلبات التقديم التي أرسلتها - فرص التطوع والمشاريع المشتركة</p>
  </div>

  <!-- STATS CARDS -->
  <div class="mr-stats-grid">
    <div class="mr-stat-card" style="border-right-color: #0ea5c9;">
      <div class="mr-stat-icon" style="background: rgba(14, 165, 201, 0.1); border-radius: 12px; font-size: 1.6rem; color: #0ea5c9;">
        📋
      </div>
      <div class="mr-stat-text-group">
        <div class="mr-stat-val" id="mr-stat-total">0</div>
        <div class="mr-stat-lbl">إجمالي الطلبات</div>
      </div>
    </div>
    <div class="mr-stat-card" style="border-right-color: #f59e0b;">
      <div class="mr-stat-icon" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; font-size: 1.6rem; color: #f59e0b;">
        ⏳
      </div>
      <div class="mr-stat-text-group">
        <div class="mr-stat-val" id="mr-stat-pending">0</div>
        <div class="mr-stat-lbl">قيد المراجعة</div>
      </div>
    </div>
    <div class="mr-stat-card" style="border-right-color: #10b981;">
      <div class="mr-stat-icon" style="background: rgba(16, 185, 129, 0.1); border-radius: 12px; font-size: 1.6rem; color: #10b981;">
        ✅
      </div>
      <div class="mr-stat-text-group">
        <div class="mr-stat-val" id="mr-stat-approved">0</div>
        <div class="mr-stat-lbl">مقبولة</div>
      </div>
    </div>
    <div class="mr-stat-card" style="border-right-color: #ef4444;">
      <div class="mr-stat-icon" style="background: rgba(239, 68, 68, 0.1); border-radius: 12px; font-size: 1.6rem; color: #ef4444;">
        ❌
      </div>
      <div class="mr-stat-text-group">
        <div class="mr-stat-val" id="mr-stat-rejected">0</div>
        <div class="mr-stat-lbl">مرفوضة</div>
      </div>
    </div>
  </div>

  <!-- TOOLBAR -->
  <div class="mr-toolbar">
    <div class="mr-search">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" id="mrSearchInput" placeholder="ابحث في طلباتك...">
    </div>
    <div class="mr-sep"></div>
    <div class="mr-chips">
      <button class="mr-chip active" data-type="all">الكل</button>
      <button class="mr-chip" data-type="opportunity"><i class="fa-solid fa-user"></i> فرص التطوع</button>
      <button class="mr-chip" data-type="project"><i class="fa-regular fa-star"></i> المشاريع</button>
      <button class="mr-chip" data-type="service"><i class="fa-solid fa-suitcase-medical"></i> خدمات مبادرون</button>
    </div>
    <div class="mr-filter-drop">
      <select id="mrStatusFilter">
        <option value="all">كل الحالات</option>
        <option value="pending">قيد المراجعة</option>
        <option value="approved">مقبولة</option>
        <option value="rejected">مرفوضة</option>
      </select>
    </div>
  </div>

  <!-- TABS -->
  <div class="mr-tabs">
    <button class="mr-tab active" data-tab="all">
      <i class="fa-solid fa-layer-group"></i> جميع الطلبات
      <span class="mr-tab-badge" id="mr-tab-badge-all">0</span>
    </button>
    <button class="mr-tab" data-tab="opportunity">
      <i class="fa-solid fa-user-tie"></i> فرص التطوع
      <span class="mr-tab-badge" id="mr-tab-badge-opportunity">0</span>
    </button>
    <button class="mr-tab" data-tab="project">
      <i class="fa-solid fa-diagram-project"></i> المشاريع المشتركة
      <span class="mr-tab-badge" id="mr-tab-badge-project">0</span>
    </button>
    <button class="mr-tab" data-tab="service">
      <i class="fa-solid fa-suitcase-medical"></i> خدمات مبادرون
      <span class="mr-tab-badge" id="mr-tab-badge-service">0</span>
    </button>
  </div>

  <!-- REQUESTS LIST / EMPTY STATE -->
  <div class="mr-list-container" id="requests-list">
    <div class="mr-empty" id="mr-loading">
      <div style="font-size:4rem; margin-bottom: 1rem;">⏳</div>
      <h3>جاري التحميل...</h3>
    </div>

    <!-- Client Side Empty State (Hidden initially) -->
    <div class="mr-empty" id="client-empty" style="display: none;">
      <div style="font-size:4rem; margin-bottom: 1rem;">📫</div>
      <h3>لا توجد طلبات</h3>
      <p>لم تقدم أي طلبات تطابق الفلتر الحالي</p>
    </div>
  </div>

</div><!-- /view-orders -->

<!-- ══ REQUEST DETAILS MODAL ══ -->
<div id="req-modal-overlay"
     style="display:none; position:fixed; inset:0; background:rgba(7,28,45,0.6); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center; padding:20px;"
     onclick="closeReqModal(event)">

  <div id="req-modal-box"
       style="background:#fff; border-radius:20px; width:100%; max-width:580px; box-shadow:0 32px 80px rgba(7,28,45,0.25); overflow:hidden; display:flex; flex-direction:column; max-height:90vh; animation:rmo-in 0.3s cubic-bezier(.4,0,.2,1);">

    <!-- Gradient Header -->
    <div id="req-modal-hdr"
         style="background:linear-gradient(135deg,#071c2d 0%,#0c6080 60%,#0ea5c9 100%); padding:26px 24px 22px; position:relative; overflow:hidden; flex-shrink:0;">
      <div style="position:absolute;top:-30px;left:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.05);"></div>
      <div style="position:absolute;bottom:-20px;right:20px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.06);"></div>

      <button onclick="closeReqModal()" style="position:absolute;top:16px;left:16px;width:32px;height:32px;border-radius:8px;border:none;background:rgba(255,255,255,.12);color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.95rem;transition:background .2s;" onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <div style="display:flex;align-items:center;gap:14px;">
        <div id="rmd-icon" style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:white;flex-shrink:0;"></div>
        <div style="flex:1;min-width:0;">
          <div id="rmd-title" style="font-size:1.1rem;font-weight:900;color:white;margin-bottom:4px;line-height:1.3;"></div>
          <div id="rmd-sub" style="font-size:.8rem;color:rgba(255,255,255,.7);font-weight:500;"></div>
        </div>
        <div id="rmd-badge" style="flex-shrink:0;"></div>
      </div>

      <div id="rmd-meta" style="margin-top:16px;padding-top:14px;border-top:1px solid rgba(255,255,255,.12);display:flex;align-items:center;gap:20px;"></div>
    </div>

    <!-- Body: view mode -->
    <div id="rmd-body" style="padding:22px 24px;overflow-y:auto;flex:1;"></div>

    <!-- Body: edit mode (hidden) -->
    <div id="rmd-edit" style="display:none;padding:22px 24px;overflow-y:auto;flex:1;"></div>

    <!-- Footer -->
    <div id="rmd-footer" style="padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;gap:10px;flex-shrink:0;"></div>
  </div>
</div>

<style>
  @keyframes rmo-in {
    from { opacity:0; transform:translateY(20px) scale(.96); }
    to   { opacity:1; transform:translateY(0) scale(1); }
  }
  .rmd-row {
    display:flex; align-items:flex-start; gap:13px;
    padding:13px 0; border-bottom:1px solid #f1f5f9;
    font-family:'Tajawal',sans-serif;
  }
  .rmd-row:last-child { border-bottom:none; }
  .rmd-ico {
    width:36px; height:36px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:.9rem; flex-shrink:0; margin-top:1px;
  }
  .rmd-lbl { font-size:.72rem; color:#94a3b8; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:.4px; }
  .rmd-val { font-size:.92rem; color:#0f172a; font-weight:600; line-height:1.5; }
  .rmd-fg { margin-bottom:16px; }
  .rmd-fg label { display:block; font-size:.78rem; font-weight:700; color:#475569; margin-bottom:6px; font-family:'Tajawal',sans-serif; }
  .rmd-fg input, .rmd-fg select, .rmd-fg textarea {
    width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
    padding:10px 14px; font-family:'Tajawal',sans-serif; font-size:.9rem; color:#0f172a;
    outline:none; transition:border-color .2s; background:#fafbfc;
  }
  .rmd-fg input:focus, .rmd-fg select:focus, .rmd-fg textarea:focus {
    border-color:#0ea5c9; background:#fff; box-shadow:0 0 0 3px rgba(14,165,201,.1);
  }
  .rmd-fg textarea { resize:vertical; min-height:90px; }
  .rmd-btn {
    height:38px; border-radius:10px; padding:0 20px;
    font-family:'Tajawal',sans-serif; font-size:.85rem; font-weight:700;
    cursor:pointer; border:none; display:inline-flex; align-items:center; gap:7px;
    transition:all .2s;
  }
  .rmd-btn-edit   { background:linear-gradient(135deg,#0c6080,#0ea5c9); color:#fff; }
  .rmd-btn-edit:hover   { box-shadow:0 6px 18px rgba(14,165,201,.35); transform:translateY(-1px); }
  .rmd-btn-save   { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
  .rmd-btn-save:hover   { box-shadow:0 6px 18px rgba(16,185,129,.35); transform:translateY(-1px); }
  .rmd-btn-cancel { background:#f1f5f9; color:#475569; }
  .rmd-btn-cancel:hover { background:#e2e8f0; }
  .rmd-mpill {
    display:flex; align-items:center; gap:6px;
    font-size:.78rem; color:rgba(255,255,255,.8); font-weight:600;
  }
  .rmd-mpill i { font-size:.75rem; }
</style>
