<div class="view" id="view-orders">
  <main>
    <div class="page-head">
      <div>
        <h1 id="orders-page-title"><i class="fa-solid fa-clipboard-list" style="margin-left:8px;color:var(--teal)"></i>
          <span id="orders-page-title-text">صفحة الطلبات</span>
        </h1>
        <p id="orders-page-sub">إدارة طلبات إنشاء الحسابات وعرض تصنيفات الجمعيات المسجلة</p>
      </div>
    </div>
    {{-- Stats for registration requests (default) --}}
    <div class="stats-grid" id="stats-grid-requests">
      <div class="stat-card">
        <div class="stat-label">إجمالي الطلبات</div>
        <div class="stat-value" id="os-total">—</div>
        <div class="stat-sub text-blue" id="os-month">—</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">قيد المراجعة</div>
        <div class="stat-value text-yellow" id="os-pending">—</div>
        <div class="stat-sub text-yellow">تنتظر المعالجة</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">تمت الموافقة</div>
        <div class="stat-value text-green" id="os-approved">—</div>
        <div class="stat-sub text-green" id="os-approval-rate">—</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مرفوضة</div>
        <div class="stat-value text-red" id="os-rejected">—</div>
        <div class="stat-sub text-red" id="os-rejection-rate">—</div>
      </div>
    </div>
    {{-- Stats for service requests (shown when services tab active) --}}
    <div class="stats-grid" id="stats-grid-services" style="display:none">
      <div class="stat-card">
        <div class="stat-label">إجمالي الطلبات</div>
        <div class="stat-value" id="ss-total">—</div>
        <div class="stat-sub text-blue" id="ss-month">—</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">جديدة</div>
        <div class="stat-value text-yellow" id="ss-pending">—</div>
        <div class="stat-sub text-yellow">تنتظر المعالجة</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مقبولة</div>
        <div class="stat-value text-green" id="ss-approved">—</div>
        <div class="stat-sub text-green" id="ss-approval-rate">—</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مرفوضة</div>
        <div class="stat-value text-red" id="ss-rejected">—</div>
        <div class="stat-sub text-red" id="ss-rejection-rate">—</div>
      </div>
    </div>
    {{-- Stats for associations (shown when my-associations tab active) --}}
    <div class="stats-grid" id="stats-grid-associations" style="display:none">
      <div class="stat-card">
        <div class="stat-label">إجمالي الجمعيات</div>
        <div class="stat-value" id="assoc-stat-total">—</div>
        <div class="stat-sub text-blue" id="assoc-stat-month"><i class="fa-solid fa-arrow-up"></i> — هذا الشهر</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">التصنيفات النشطة</div>
        <div class="stat-value text-blue" id="assoc-stat-cats">—</div>
        <div class="stat-sub">تصنيف مختلف مضاف</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">متوسط التسجيل</div>
        <div class="stat-value text-green" id="assoc-stat-avg">—</div>
        <div class="stat-sub text-green">شهرياً في النظام</div>
      </div>
    </div>
    {{-- Stats for opportunity requests --}}
    <div class="stats-grid" id="stats-grid-opp-requests" style="display:none">
      <div class="stat-card">
        <div class="stat-label">إجمالي الطلبات</div>
        <div class="stat-value" id="opp-stat-total">0</div>
        <div class="stat-sub" id="opp-stat-month">لا طلبات هذا الشهر</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">جديدة</div>
        <div class="stat-value text-blue" id="opp-stat-pending">0</div>
        <div class="stat-sub">تنتظر المعالجة</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مقبولة</div>
        <div class="stat-value text-green" id="opp-stat-approved">0</div>
        <div class="stat-sub text-green" id="opp-stat-rate">—</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مرفوضة</div>
        <div class="stat-value text-red" id="opp-stat-rejected">0</div>
        <div class="stat-sub text-red" id="opp-stat-rej-rate">—</div>
      </div>
    </div>
    {{-- Stats for project requests --}}
    <div class="stats-grid" id="stats-grid-proj-requests" style="display:none">
      <div class="stat-card">
        <div class="stat-label">إجمالي الطلبات</div>
        <div class="stat-value" id="proj-stat-total">0</div>
        <div class="stat-sub" id="proj-stat-month">لا طلبات هذا الشهر</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">جديدة</div>
        <div class="stat-value text-blue" id="proj-stat-pending">0</div>
        <div class="stat-sub">تنتظر المعالجة</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مقبولة</div>
        <div class="stat-value text-green" id="proj-stat-approved">0</div>
        <div class="stat-sub text-green" id="proj-stat-rate">—</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">مرفوضة</div>
        <div class="stat-value text-red" id="proj-stat-rejected">0</div>
        <div class="stat-sub text-red" id="proj-stat-rej-rate">—</div>
      </div>
    </div>
    <div class="section-tabs">
      <button class="tab-btn active" onclick="switchTab('my-associations', this)">الجمعيات</button>
      <button class="tab-btn" onclick="switchTab('requests', this)">طلبات إنشاء الحساب</button>
      <button class="tab-btn" onclick="switchTab('services', this)">
        طلبات الخدمات
        <span id="sr-pending-count"
          style="display:inline-flex;align-items:center;justify-content:center;background:rgba(59,130,246,0.15);color:#3b82f6;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:800;margin-right:6px"></span>
      </button>
      <button class="tab-btn" onclick="switchTab('opp-requests', this)">
        طلبات فرص التطوع
        <span id="opp-req-count"
          style="display:inline-flex;align-items:center;justify-content:center;background:rgba(245,158,11,0.15);color:#d97706;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:800;margin-right:6px"></span>
      </button>
      <button class="tab-btn" onclick="switchTab('proj-requests', this)">
        طلبات المشاريع
        <span id="proj-req-count"
          style="display:inline-flex;align-items:center;justify-content:center;background:rgba(123,78,166,0.15);color:#7b4ea6;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:800;margin-right:6px"></span>
      </button>
    </div>

    {{-- ══ MY ASSOCIATIONS TAB ══ --}}
    <div class="tab-content active" id="tab-my-associations">

      {{-- ── Page Header ── --}}
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem">
        <div style="text-align:right">
          <h2 style="color:var(--ink);font-weight:800;font-size:1.3rem;margin:0 0 4px 0">
            <i class="fa-solid fa-building-user" style="margin-left:8px;color:var(--teal)"></i>
            الجمعيات
          </h2>
          <p style="color:var(--muted);font-size:0.9rem;margin:0">إدارة كافة الجمعيات وتصنيفاتها في النظام</p>
        </div>
        <div>
          <button class="btn-primary" onclick="openAddCategoryModal()"
            style="padding:9px 14px;font-size:0.86rem;border-radius:10px;display:flex;align-items:center;gap:6px">
            <i class="fa-solid fa-plus"></i> إضافة تصنيف
          </button>
        </div>
      </div>

      <div class="assoc-dual-layout">

        {{-- ── Right: Associations Grid ── --}}
        <div class="assoc-main-panel">
          {{-- Search bar --}}
          <div style="background:#fff;border-radius:14px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:10px;border:1px solid var(--border);box-shadow:0 1px 4px rgba(0,0,0,0.03)">
            <div style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:7px 12px;flex:1">
              <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.82rem"></i>
              <input type="text" id="assocMainSearch" placeholder="ابحث عن جمعية بالاسم أو البريد..."
                oninput="filterAssocMain()"
                style="border:none;outline:none;background:transparent;font-family:inherit;font-size:0.85rem;width:100%;direction:rtl">
            </div>
            <div id="assoc-active-cat-badge" style="display:none;align-items:center;gap:6px;background:rgba(42,184,208,0.1);border:1px solid rgba(42,184,208,0.3);border-radius:10px;padding:6px 12px;font-size:0.82rem;font-weight:700;color:var(--teal)">
              <span id="assoc-active-cat-label"></span>
              <button onclick="selectAssocCat('')" style="background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;padding:0;font-size:0.9rem">
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
          </div>

          {{-- Associations list --}}
          <div id="assocMainGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:12px">
            {{-- Filled by JS --}}
          </div>

          {{-- Pagination --}}
          <div id="assocPaginationWrap" style="display:none;margin-top:20px;display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap">
            <button id="assoc-prev-btn" onclick="changeAssocPage(-1)"
              style="display:flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-family:inherit;font-size:0.83rem;font-weight:700;cursor:pointer;color:#374151;transition:all 0.18s">
              <i class="fa-solid fa-chevron-right"></i> السابق
            </button>
            <div id="assocPageNumbers" style="display:flex;gap:4px;align-items:center">
              {{-- Page numbers filled by JS --}}
            </div>
            <button id="assoc-next-btn" onclick="changeAssocPage(1)"
              style="display:flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-family:inherit;font-size:0.83rem;font-weight:700;cursor:pointer;color:#374151;transition:all 0.18s">
              التالي <i class="fa-solid fa-chevron-left"></i>
            </button>
          </div>
        </div>

        {{-- ── Left: Categories Sidebar ── --}}
        <aside class="assoc-cats-sidebar">
          <div class="assoc-sidebar-header">
            <span class="assoc-sidebar-title">
              <i class="fa-solid fa-tag" style="color:var(--teal)"></i> التصنيفات
            </span>
            <span class="vt-pill-count" id="assoc-sidebar-cats-count" style="margin-right:6px"></span>
          </div>
          <div id="assocSidebarCats" class="assoc-sidebar-cats-list">
            {{-- Filled by JS --}}
          </div>
        </aside>

      </div>

    </div>

    <style>
      /* ── Dual layout ── */
      .assoc-dual-layout { display:flex; gap:18px; align-items:flex-start; }

      /* ── Categories Sidebar ── */
      .assoc-cats-sidebar {
        width:220px; flex-shrink:0;
        background:#fff; border-radius:16px;
        border:1.5px solid #e2e8f0;
        box-shadow:0 2px 10px rgba(0,0,0,0.04);
        overflow:hidden; position:sticky; top:80px;
      }
      .assoc-sidebar-header {
        display:flex; align-items:center; justify-content:space-between;
        padding:14px 16px; background:#f8fafc;
        border-bottom:1px solid #e2e8f0; font-weight:800;
        font-size:0.9rem; color:#0f172a;
      }
      .assoc-sidebar-title { display:flex; align-items:center; gap:8px; }
      .assoc-sidebar-cats-list { padding:8px; display:flex; flex-direction:column; gap:4px; }

      .assoc-sidebar-cat-item {
        display:flex; align-items:center; gap:10px;
        padding:10px 12px; border-radius:10px; cursor:pointer;
        font-size:0.85rem; font-weight:600; color:#374151;
        transition:all 0.18s; border:1.5px solid transparent;
      }
      .assoc-sidebar-cat-item:hover { background:#f1f5f9; border-color:#e2e8f0; }
      .assoc-sidebar-cat-item.active {
        background:rgba(42,184,208,0.08);
        border-color:rgba(42,184,208,0.35);
        color:#0e7490; font-weight:800;
      }
      .assoc-sidebar-cat-icon { font-size:1.15rem; width:22px; text-align:center; flex-shrink:0; }
      .assoc-sidebar-cat-count {
        margin-right:auto; background:#f1f5f9; color:#64748b;
        border-radius:20px; padding:2px 8px; font-size:0.72rem; font-weight:800;
      }
      .assoc-sidebar-cat-item.active .assoc-sidebar-cat-count {
        background:rgba(42,184,208,0.15); color:#0e7490;
      }

      /* ── Main panel ── */
      .assoc-main-panel { flex:1; min-width:0; }

      /* ── Assoc card (main grid) ── */
      .assoc-main-card {
        background:#fff; border:1.5px solid #e2e8f0; border-radius:16px;
        padding:16px; display:flex; align-items:center; gap:14px;
        transition:all 0.2s; cursor:default;
      }
      .assoc-main-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.08); border-color:#c7d2fe; transform:translateY(-2px); }
      .assoc-main-card-avatar {
        width:48px; height:48px; border-radius:14px; display:flex;
        align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;
      }
      .assoc-main-card-info { flex:1; min-width:0; }
      .assoc-main-card-name { font-weight:800; font-size:0.92rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
      .assoc-main-card-cat { font-size:0.75rem; color:#64748b; margin-top:2px; }
      .assoc-main-card-contact { font-size:0.72rem; color:#94a3b8; margin-top:3px; direction:ltr; text-align:right; }
      .assoc-main-card-meta { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }

      /* ── Pagination buttons ── */
      .assoc-page-btn {
        width:36px; height:36px; border-radius:10px; border:1.5px solid #e2e8f0;
        background:#fff; font-family:inherit; font-size:0.85rem; font-weight:700;
        cursor:pointer; color:#374151; transition:all 0.18s;
        display:flex; align-items:center; justify-content:center;
      }
      .assoc-page-btn:hover { background:#f1f5f9; border-color:#cbd5e1; }
      .assoc-page-btn.active-page {
        background:linear-gradient(135deg,#0d3d49,#2ab8d0);
        border-color:transparent; color:#fff;
        box-shadow:0 3px 10px rgba(42,184,208,0.35);
      }
      #assoc-prev-btn:hover, #assoc-next-btn:hover { background:#f1f5f9; border-color:#cbd5e1; }
      #assoc-prev-btn:disabled, #assoc-next-btn:disabled { opacity:0.4; cursor:not-allowed; }

      @media (max-width: 800px) {
        .assoc-dual-layout { flex-direction:column; }
        .assoc-cats-sidebar { width:100%; position:static; }
        .assoc-sidebar-cats-list { flex-direction:row; flex-wrap:wrap; padding:8px; }
        .assoc-sidebar-cat-item { flex-shrink:0; }
      }
    </style>

    {{-- ══ ACCOUNT CREATION REQUESTS TAB ══ --}}
    <div class="tab-content" id="tab-requests">
      <div class="table-toolbar">
        <div class="search-box"><span><i class="fa-solid fa-magnifying-glass"></i></span><input type="text"
            placeholder="بحث بالاسم أو البريد الإلكتروني..." onkeyup="filterTable(this.value)" /></div>
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
          <tbody id="requestsTbody"></tbody>
        </table>
      </div>
    </div>
    <div class="tab-content" id="tab-services">
      {{-- Header --}}
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
        <div style="text-align:right">
          <h2 style="color:var(--ink);font-weight:800;font-size:1.3rem;margin:0 0 4px 0">
            <i class="fa-solid fa-clipboard-list" style="margin-left:8px;color:var(--teal)"></i>
            طلبات الخدمات
          </h2>
          <p style="color:var(--muted);font-size:0.9rem;margin:0">راجع وأدر طلبات خدمات الجمعيات</p>
        </div>
        <button class="btn btn-ghost" onclick="loadServiceRequests()"
          style="display:flex;align-items:center;gap:6px;font-size:0.85rem">
          <i class="fa-solid fa-arrows-rotate"></i> تحديث
        </button>
      </div>

      {{-- Search & Filter Bar --}}
      <div
        style="background:#fff;border-radius:14px;padding:12px 16px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border);box-shadow:0 1px 4px rgba(0,0,0,0.03)">
        <div id="sr-filter-tabs" style="display:flex;gap:6px;flex-wrap:wrap">
          <button class="sr-tab-btn active" data-status="all" onclick="filterSrByStatus('all')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#3b82f6;color:#fff;font-size:0.82rem">الكل</button>
          <button class="sr-tab-btn" data-status="pending" onclick="filterSrByStatus('pending')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">جديد</button>
          <button class="sr-tab-btn" data-status="processing" onclick="filterSrByStatus('processing')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">قيد
            المعالجة</button>
          <button class="sr-tab-btn" data-status="approved" onclick="filterSrByStatus('approved')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مقبول</button>
          <button class="sr-tab-btn" data-status="rejected" onclick="filterSrByStatus('rejected')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مرفوض</button>
        </div>
        <div class="search-box"
          style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:6px 12px;width:240px">
          <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8"></i>
          <input type="text" id="sr-search-input" placeholder="بحث عن جمعية أو طلب..." oninput="searchSr(this.value)"
            style="border:none;background:transparent;width:100%;font-family:inherit;outline:none;font-size:0.85rem;direction:rtl">
        </div>
      </div>

      {{-- Table --}}
      <div
        style="background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:0 2px 8px rgba(0,0,0,0.03);overflow:hidden">
        <table style="width:100%;border-collapse:collapse;text-align:center" dir="rtl">
          <thead>
            <tr style="border-bottom:1px solid var(--border);background:#fafbfc">
              <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem;text-align:right">الجمعية
              </th>
              <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">عنوان الطلب</th>
              <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">نوع الخدمة</th>
              <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">الحالة</th>
              <th style="padding:14px 20px;font-weight:700;color:#64748b;font-size:0.85rem">إجراء</th>
            </tr>
          </thead>
          <tbody id="sr-tbody"></tbody>
        </table>
      </div>
    </div>{{-- /tab-services --}}

    {{-- ══ TAB: طلبات فرص التطوع ══ --}}
    <div class="tab-content" id="tab-opp-requests">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <div>
          <div style="font-size:1.1rem;font-weight:800;color:#0f172a">طلبات فرص التطوع</div>
          <div style="font-size:0.82rem;color:#64748b;margin-top:4px">راجع وأدر طلبات التطوع المقدمة من المستخدمين</div>
        </div>
        <button class="btn-primary" style="padding:9px 18px;font-size:0.83rem" onclick="loadOppRequests()">
          <i class="fa-solid fa-rotate-right" style="margin-left:6px"></i>تحديث
        </button>
      </div>



      <div
        style="background:#fff;border-radius:14px;padding:12px 16px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
        <div style="display:flex;gap:6px;flex-wrap:wrap" id="opp-req-filter-tabs">
          <button class="sr-tab-btn active" onclick="filterOppReqs('all')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#d97706;color:#fff;font-size:0.82rem">الكل</button>
          <button class="sr-tab-btn" onclick="filterOppReqs('pending')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">جديد</button>
          <button class="sr-tab-btn" onclick="filterOppReqs('approved')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مقبول</button>
          <button class="sr-tab-btn" onclick="filterOppReqs('rejected')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مرفوض</button>
        </div>
        <div class="search-box"
          style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:6px 12px;width:240px">
          <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.82rem"></i>
          <input type="text" id="opp-req-search" placeholder="ابحث عن طلب أو مستخدم..." oninput="filterOppReqs()"
            style="border:none;outline:none;background:transparent;font-family:inherit;font-size:0.83rem;width:100%">
        </div>
      </div>

      <div style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid var(--border)">
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid var(--border)">
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">المستخدم</th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">عنوان الفرصة
              </th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">التصنيف</th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">تاريخ الطلب
              </th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">الحالة</th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">إجراء</th>
            </tr>
          </thead>
          <tbody id="opp-req-tbody"></tbody>
        </table>
      </div>
    </div>{{-- /tab-opp-requests --}}

    {{-- ══ TAB: طلبات المشاريع المشتركة ══ --}}
    <div class="tab-content" id="tab-proj-requests">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <div>
          <div style="font-size:1.1rem;font-weight:800;color:#0f172a">طلبات المشاريع المشتركة</div>
          <div style="font-size:0.82rem;color:#64748b;margin-top:4px">راجع وأدر طلبات الانضمام للمشاريع المشتركة</div>
        </div>
        <button class="btn-primary" style="padding:9px 18px;font-size:0.83rem" onclick="loadProjRequests()">
          <i class="fa-solid fa-rotate-right" style="margin-left:6px"></i>تحديث
        </button>
      </div>



      <div
        style="background:#fff;border-radius:14px;padding:12px 16px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;border:1px solid var(--border)">
        <div style="display:flex;gap:6px;flex-wrap:wrap" id="proj-req-filter-tabs">
          <button class="sr-tab-btn active" onclick="filterProjReqs('all')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#7b4ea6;color:#fff;font-size:0.82rem">الكل</button>
          <button class="sr-tab-btn" onclick="filterProjReqs('pending')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">جديد</button>
          <button class="sr-tab-btn" onclick="filterProjReqs('approved')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مقبول</button>
          <button class="sr-tab-btn" onclick="filterProjReqs('rejected')"
            style="font-family:inherit;font-weight:700;border-radius:20px;padding:5px 16px;cursor:pointer;border:none;background:#f1f5f9;color:#64748b;font-size:0.82rem">مرفوض</button>
        </div>
        <div class="search-box"
          style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:6px 12px;width:240px">
          <i class="fa-solid fa-magnifying-glass" style="color:#94a3b8;font-size:0.82rem"></i>
          <input type="text" id="proj-req-search" placeholder="ابحث عن مشروع أو مستخدم..." oninput="filterProjReqs()"
            style="border:none;outline:none;background:transparent;font-family:inherit;font-size:0.83rem;width:100%">
        </div>
      </div>

      <div style="background:#fff;border-radius:16px;overflow:hidden;border:1px solid var(--border)">
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid var(--border)">
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">المستخدم</th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">عنوان المشروع
              </th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">تاريخ الطلب
              </th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">الحالة</th>
              <th style="padding:12px 16px;text-align:right;font-size:.8rem;color:#64748b;font-weight:700">إجراء</th>
            </tr>
          </thead>
          <tbody id="proj-req-tbody"></tbody>
        </table>
      </div>
    </div>{{-- /tab-proj-requests --}}

  </main>

  {{-- ══ SR DETAIL MODAL (used by orders.js) ══ --}}
  <div class="modal-overlay" id="sr-modal">
    <div class="modal" style="max-width:520px;border-radius:24px;overflow:hidden" onclick="event.stopPropagation()">
      {{-- Header --}}
      <div
        style="background:linear-gradient(135deg,var(--teal),var(--blue));padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
        <button
          style="background:rgba(255,255,255,0.2);border:none;width:32px;height:32px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer"
          onclick="closeSrModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
        <h3 style="color:#fff;font-weight:800;font-size:1.1rem;margin:0;display:flex;align-items:center;gap:8px">
          <i class="fa-solid fa-clipboard-list"></i> تفاصيل الطلب
        </h3>
      </div>

      {{-- Body --}}
      <div style="padding:20px;max-height:65vh;overflow-y:auto;background:#f8fafc">
        {{-- Applicant info --}}
        <div
          style="background:#fff;border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:14px;margin-bottom:14px;border:1px solid var(--border)">
          <div id="srm-av"
            style="width:44px;height:44px;border-radius:50%;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0">
          </div>
          <div style="text-align:right;flex:1">
            <div id="srm-name" style="font-weight:800;font-size:1rem;color:var(--ink)"></div>
            <div id="srm-email" style="color:var(--muted);font-size:0.82rem"></div>
          </div>
          <span id="srm-status" class="badge"></span>
        </div>

        {{-- Request details --}}
        <div
          style="background:#fff;border-radius:14px;padding:16px;margin-bottom:14px;border:1px solid var(--border);text-align:right">
          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">نوع الخدمة</div>
          <div id="srm-type" style="font-weight:800;color:var(--purple);font-size:0.95rem;margin-bottom:12px"></div>

          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">عنوان الطلب</div>
          <div id="srm-title" style="font-weight:700;color:var(--ink);font-size:0.95rem;margin-bottom:12px"></div>

          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">التفاصيل</div>
          <div id="srm-details"
            style="color:var(--ink);font-size:0.9rem;line-height:1.6;white-space:pre-wrap;margin-bottom:12px"></div>

          <div style="display:flex;gap:16px;flex-wrap:wrap">
            <div>
              <div style="font-size:0.78rem;color:var(--muted)">الميزانية</div>
              <div id="srm-budget" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div>
            </div>
            <div>
              <div style="font-size:0.78rem;color:var(--muted)">التاريخ المفضل</div>
              <div id="srm-date" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div>
            </div>
            <div>
              <div style="font-size:0.78rem;color:var(--muted)">تاريخ الطلب</div>
              <div id="srm-created" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div>
            </div>
          </div>
        </div>

        {{-- Status actions --}}
        <div style="background:#fff;border-radius:14px;padding:16px;border:1px solid var(--border)"
          id="sr-action-section">
          <div style="font-weight:800;color:var(--ink);margin-bottom:10px;text-align:right">اتخاذ إجراء</div>
          <div style="display:flex;gap:8px" dir="rtl">
            <button class="sr-action-btn-direct btn-pill-approve" onclick="submitSrStatus('approved')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#10b981;color:#fff">
              <i class="fa-solid fa-check"></i> قبول الطلب
            </button>
            <button class="sr-action-btn-direct btn-pill-process" onclick="submitSrStatus('processing')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#3b82f6;color:#fff">
              <i class="fa-solid fa-spinner"></i> قيد المعالجة
            </button>
            <button class="sr-action-btn-direct btn-pill-reject" onclick="submitSrStatus('rejected')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#ef4444;color:#fff">
              <i class="fa-solid fa-xmark"></i> رفض الطلب
            </button>
          </div>
        </div>
      </div>

      {{-- Footer --}}
      <div style="padding:16px 20px;border-top:1px solid var(--border);background:#fff;display:flex;gap:10px">
        <button onclick="closeSrModal()"
          style="width:100%;background:#f1f5f9;color:#64748b;border:none;border-radius:10px;padding:12px;font-family:inherit;font-weight:700;cursor:pointer;font-size:0.9rem">إغلاق</button>
      </div>
    </div>
  </div>{{-- /sr-modal --}}

  {{-- ══ OPPORTUNITY REQUEST DETAIL MODAL ══ --}}
  <div class="modal-overlay" id="opp-req-modal">
    <div class="modal" style="max-width:520px;border-radius:24px;overflow:hidden" onclick="event.stopPropagation()">
      {{-- Header --}}
      <div
        style="background:linear-gradient(135deg,#d97706,var(--teal));padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
        <button
          style="background:rgba(255,255,255,0.2);border:none;width:32px;height:32px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer"
          onclick="closeOppReqModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
        <h3 style="color:#fff;font-weight:800;font-size:1.1rem;margin:0;display:flex;align-items:center;gap:8px">
          <i class="fa-solid fa-handshake-angle"></i> تفاصيل طلب التطوع
        </h3>
      </div>

      {{-- Body --}}
      <div style="padding:20px;max-height:65vh;overflow-y:auto;background:#f8fafc">
        {{-- Applicant info --}}
        <div
          style="background:#fff;border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:14px;margin-bottom:14px;border:1px solid var(--border)">
          <div id="opp-req-modal-av"
            style="width:44px;height:44px;border-radius:50%;background:#d97706;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0">
          </div>
          <div style="text-align:right;flex:1">
            <div id="opp-req-modal-name" style="font-weight:800;font-size:1rem;color:var(--ink)"></div>
            <div id="opp-req-modal-email" style="color:var(--muted);font-size:0.82rem"></div>
            <div id="opp-req-modal-phone" style="color:var(--muted);font-size:0.82rem"></div>
          </div>
          <span id="opp-req-modal-status" class="badge"></span>
        </div>

        {{-- Opportunity details --}}
        <div
          style="background:#fff;border-radius:14px;padding:16px;margin-bottom:14px;border:1px solid var(--border);text-align:right">
          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">عنوان فرصة التطوع</div>
          <div id="opp-req-modal-title" style="font-weight:800;color:#d97706;font-size:0.95rem;margin-bottom:12px">
          </div>

          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">تصنيف الفرصة</div>
          <div id="opp-req-modal-type" style="font-weight:700;color:var(--ink);font-size:0.95rem;margin-bottom:12px">
          </div>

          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">رسالة التطوع / ملاحظات المتقدم</div>
          <div id="opp-req-modal-notes"
            style="color:var(--ink);font-size:0.9rem;line-height:1.6;white-space:pre-wrap;margin-bottom:12px"></div>

          <div style="display:flex;gap:16px;flex-wrap:wrap">
            <div>
              <div style="font-size:0.78rem;color:var(--muted)">تاريخ تقديم الطلب</div>
              <div id="opp-req-modal-created" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div>
            </div>
          </div>
        </div>

        {{-- Status actions --}}
        <div style="background:#fff;border-radius:14px;padding:16px;border:1px solid var(--border)"
          id="opp-req-action-section">
          <div style="font-weight:800;color:var(--ink);margin-bottom:10px;text-align:right">اتخاذ إجراء</div>

          {{-- Rejection reason (only shown if Rejecting) --}}
          <div id="opp-req-reject-reason-wrap" style="display:none;margin-bottom:12px;text-align:right">
            <label style="font-size:0.78rem;color:var(--muted);display:block;margin-bottom:4px">سبب الرفض (مطلوب - 5
              أحرف على الأقل) *</label>
            <textarea id="opp-req-reject-reason" rows="2"
              style="width:100%;padding:8px 12px;border-radius:8px;border:1px solid var(--border);font-family:inherit;font-size:0.85rem;outline:none"
              placeholder="أدخل سبب الرفض هنا..."></textarea>
          </div>

          <div style="display:flex;gap:8px" dir="rtl">
            <button class="opp-req-status-btn btn-pill-approve" onclick="submitOppReqStatus('approve')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#10b981;color:#fff">
              <i class="fa-solid fa-check"></i> قبول الطلب
            </button>
            <button class="opp-req-status-btn btn-pill-process" onclick="submitOppReqStatus('process')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#3b82f6;color:#fff">
              <i class="fa-solid fa-spinner"></i> قيد المعالجة
            </button>
            <button class="opp-req-status-btn btn-pill-reject" onclick="toggleOppReqRejectInput()"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#ef4444;color:#fff">
              <i class="fa-solid fa-xmark"></i> رفض الطلب
            </button>
          </div>
          <div id="opp-req-reject-confirm-btn-wrap" style="display:none;margin-top:10px">
            <button class="btn btn-danger" onclick="submitOppReqStatus('reject')"
              style="width:100%;padding:10px;border-radius:10px;font-family:inherit;font-weight:800;font-size:0.85rem">
              تأكيد الرفض مع السبب
            </button>
          </div>
        </div>
      </div>

      {{-- Footer --}}
      <div style="padding:16px 20px;border-top:1px solid var(--border);background:#fff;display:flex;gap:10px">
        <button onclick="closeOppReqModal()"
          style="width:100%;background:#f1f5f9;color:#64748b;border:none;border-radius:10px;padding:12px;font-family:inherit;font-weight:700;cursor:pointer;font-size:0.9rem">إغلاق</button>
      </div>
    </div>
  </div>

  {{-- ══ PROJECT REQUEST DETAIL MODAL ══ --}}
  <div class="modal-overlay" id="proj-req-modal">
    <div class="modal" style="max-width:520px;border-radius:24px;overflow:hidden" onclick="event.stopPropagation()">
      {{-- Header --}}
      <div
        style="background:linear-gradient(135deg,#7b4ea6,var(--teal));padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
        <button
          style="background:rgba(255,255,255,0.2);border:none;width:32px;height:32px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer"
          onclick="closeProjReqModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
        <h3 style="color:#fff;font-weight:800;font-size:1.1rem;margin:0;display:flex;align-items:center;gap:8px">
          <i class="fa-solid fa-briefcase"></i> تفاصيل طلب المشروع المشترك
        </h3>
      </div>

      {{-- Body --}}
      <div style="padding:20px;max-height:65vh;overflow-y:auto;background:#f8fafc">
        {{-- Applicant info --}}
        <div
          style="background:#fff;border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:14px;margin-bottom:14px;border:1px solid var(--border)">
          <div id="proj-req-modal-av"
            style="width:44px;height:44px;border-radius:50%;background:#7b4ea6;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0">
          </div>
          <div style="text-align:right;flex:1">
            <div id="proj-req-modal-name" style="font-weight:800;font-size:1rem;color:var(--ink)"></div>
            <div id="proj-req-modal-email" style="color:var(--muted);font-size:0.82rem"></div>
            <div id="proj-req-modal-phone" style="color:var(--muted);font-size:0.82rem"></div>
          </div>
          <span id="proj-req-modal-status" class="badge"></span>
        </div>

        {{-- Project details --}}
        <div
          style="background:#fff;border-radius:14px;padding:16px;margin-bottom:14px;border:1px solid var(--border);text-align:right">
          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">عنوان المشروع</div>
          <div id="proj-req-modal-title" style="font-weight:800;color:#7b4ea6;font-size:0.95rem;margin-bottom:12px">
          </div>

          <div style="font-size:0.78rem;color:var(--muted);margin-bottom:2px">ملاحظات الانضمام / الرسالة</div>
          <div id="proj-req-modal-notes"
            style="color:var(--ink);font-size:0.9rem;line-height:1.6;white-space:pre-wrap;margin-bottom:12px"></div>

          <div style="display:flex;gap:16px;flex-wrap:wrap">
            <div>
              <div style="font-size:0.78rem;color:var(--muted)">تاريخ تقديم الطلب</div>
              <div id="proj-req-modal-created" style="font-weight:700;color:var(--ink);font-size:0.88rem"></div>
            </div>
          </div>
        </div>

        {{-- Status actions --}}
        <div style="background:#fff;border-radius:14px;padding:16px;border:1px solid var(--border)"
          id="proj-req-action-section">
          <div style="font-weight:800;color:var(--ink);margin-bottom:10px;text-align:right">اتخاذ إجراء</div>
          <div style="display:flex;gap:8px" dir="rtl">
            <button class="proj-req-status-btn btn-pill-approve" onclick="submitProjReqStatus('approve')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#10b981;color:#fff">
              <i class="fa-solid fa-check"></i> قبول الطلب
            </button>
            <button class="proj-req-status-btn btn-pill-process" onclick="submitProjReqStatus('process')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#3b82f6;color:#fff">
              <i class="fa-solid fa-spinner"></i> قيد المعالجة
            </button>
            <button class="proj-req-status-btn btn-pill-reject" onclick="submitProjReqStatus('reject')"
              style="flex:1;border-radius:10px;padding:10px 6px;font-weight:700;cursor:pointer;font-family:inherit;font-size:0.85rem;display:flex;align-items:center;justify-content:center;gap:6px;border:none;background:#ef4444;color:#fff">
              <i class="fa-solid fa-xmark"></i> رفض الطلب
            </button>
          </div>
        </div>
      </div>

      {{-- Footer --}}
      <div style="padding:16px 20px;border-top:1px solid var(--border);background:#fff;display:flex;gap:10px">
        <button onclick="closeProjReqModal()"
          style="width:100%;background:#f1f5f9;color:#64748b;border:none;border-radius:10px;padding:12px;font-family:inherit;font-weight:700;cursor:pointer;font-size:0.9rem">إغلاق</button>
      </div>
    </div>
  </div>

  <style>
    .sr-status-active {
      border-color: var(--blue) !important;
      color: var(--blue) !important;
      background: rgba(29, 111, 164, 0.06) !important;
    }

    #sr-filter-tabs .sr-tab-btn.active {
      background: #3b82f6 !important;
      color: #fff !important;
    }

    #sr-modal,
    #opp-req-modal,
    #proj-req-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    #sr-modal.open,
    #opp-req-modal.open,
    #proj-req-modal.open {
      display: flex;
    }

    .badge-processing {
      background: rgba(59, 130, 246, 0.1) !important;
      color: #2563eb !important;
      border: 1px solid rgba(59, 130, 246, 0.2) !important;
    }
  </style>

  <!-- ORDERS MODAL -->
  <div class="modal-overlay" id="modal">
    <div class="modal">
      <div class="modal-head">
        <h3>تفاصيل طلب التسجيل</h3>
        <button class="btn btn-ghost" onclick="closeModal()" style="padding:.3rem .7rem;">✕</button>
      </div>
      <div class="modal-body" id="modalBody"></div>
      <div class="modal-footer" id="modal-footer-btns">
        <button class="btn btn-ghost" onclick="closeModal()">إغلاق</button>
      </div>
    </div>
  </div>

  <!-- ── Action Modal (reject / review with reason) ── -->
  <div class="modal-overlay" id="action-modal">
    <div class="modal action-modal-card">
      <div class="modal-head action-modal-head">
        <button class="action-modal-close" type="button" onclick="closeActionModal()" aria-label="إغلاق">✕</button>
        <div class="action-modal-head-main">
          <span id="action-modal-title" class="action-modal-title">طلب تعديل البيانات</span>
          <span id="action-modal-icon" class="action-modal-icon"></span>
        </div>
      </div>
      <div class="action-modal-subwrap">
        <div id="action-modal-sub" class="action-modal-sub">جمعية: الاتحاد</div>
      </div>
      <div class="modal-body action-modal-body">
        <div class="action-modal-field">
          <label id="action-notes-label" class="action-modal-label">التعديلات المطلوبة *</label>
          <textarea id="action-notes" rows="4" class="action-modal-textarea"
            placeholder="أدخل التعديلات المطلوبة..."></textarea>
        </div>
      </div>
      <div class="modal-footer action-modal-footer">
        <button class="btn btn-ghost" type="button" onclick="closeActionModal()">إلغاء</button>
        <button id="action-confirm-btn" class="btn btn-review" type="button" onclick="confirmAction()">إرسال طلب
          التعديل</button>
      </div>
    </div>
  </div>

  <!-- ── Add Category Modal ── -->
  <div class="modal-overlay" id="add-cat-modal">
    <div class="modal add-cat-modal" style="max-width:560px;overflow:hidden">
      <div class="add-cat-head">
        <button class="add-cat-close" onclick="closeAddCategoryModal()" type="button">✕</button>
        <div class="add-cat-title-wrap">
          <h3>إضافة تصنيف جديد</h3>
          <p>أنشئ تصنيف جمعية بشكل متناسق مع تصميم المنصة</p>
        </div>
      </div>
      <div class="modal-body add-cat-body">
        <div class="add-cat-field">
          <label>اسم التصنيف</label>
          <input id="cat-name-input" type="text" placeholder="مثال: اجتماعية تنمية بيئية" />
        </div>

        <div class="add-cat-field">
          <label>الأيقونة (Emoji)</label>
          <div class="emoji-grid" id="cat-emoji-grid"></div>
        </div>

        <div class="add-cat-field">
          <label>لون التصنيف</label>
          <div class="color-row">
            <input id="cat-color-input" type="color" value="#2ab8d0" />
            <span id="cat-color-label">#2AB8D0</span>
          </div>
        </div>
      </div>
      <div class="modal-footer add-cat-footer">
        <button class="btn btn-ghost" onclick="closeAddCategoryModal()" type="button">إلغاء</button>
        <button class="btn btn-primary" onclick="saveNewCategory()" type="button"><i class="fa-solid fa-floppy-disk"
            style="margin-left:8px"></i> حفظ التصنيف</button>
      </div>
    </div>
  </div>

  <!-- ── Approve Confirm Modal (replace browser confirm) ── -->
  <div class="modal-overlay" id="approve-modal">
    <div class="approve-card" role="dialog" aria-modal="true">
      <button class="approve-x" onclick="closeApproveModal()" type="button" aria-label="إغلاق"><i
          class="fa-solid fa-xmark"></i></button>
      <div class="approve-check"><i class="fa-solid fa-check"></i></div>
      <div class="approve-ttl">تأكيد قبول الطلب</div>
      <div class="approve-sub2" id="approve-sub">هل تريد قبول هذا الطلب؟</div>
      <div class="approve-hint">سيتم إشعار الجمعية بعد القبول.</div>
      <div class="approve-actions">
        <button class="btn-pill-cancel" onclick="closeApproveModal()" type="button">إلغاء</button>
        <button class="btn-pill-approve" id="approve-confirm-btn" onclick="confirmApprove()" type="button"><i
            class="fa-solid fa-check" style="margin-left:8px"></i> قبول الطلب</button>
      </div>
    </div>
  </div>
</div>{{-- /view-orders --}}