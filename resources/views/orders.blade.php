<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>صفحة الطلبات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
    <style>
        #nb-reqs:empty { display:none!important }

        /* Service Requests */
        .sr-loading { padding:48px; text-align:center; color:#94a3b8; font-size:.92rem; }
        .sr-empty   { padding:48px; text-align:center; color:#94a3b8; font-size:.92rem; }
        #sr-filter-tabs { display:flex; gap:8px; flex-wrap:wrap; }
        .sr-tab-btn { padding:6px 18px; border-radius:20px; border:none; cursor:pointer; font-family:inherit; font-size:.82rem; font-weight:700; background:#f1f5f9; color:#64748b; transition:all .2s; }
        .sr-tab-btn.active { background:#0891b2; color:#fff; box-shadow:0 2px 8px rgba(8,145,178,.3); }

        /* ═══ Category Cards (New Design) ═══ */
        .categories-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:14px; margin-bottom:28px; }
        .cat-card-new {
            position:relative; background:#fff; border-radius:18px;
            border:1.5px solid #e2e8f0; overflow:hidden; cursor:pointer;
            transition:all .22s; box-shadow:0 2px 10px rgba(0,0,0,.05);
        }
        .cat-card-new:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.1); border-color:var(--cc,#2ab8d0); }
        .cat-card-new-accent { position:absolute; top:0; right:0; left:0; height:4px; background:var(--cc,#2ab8d0); }
        .cat-card-new-header { display:flex; align-items:center; gap:12px; padding:16px 14px 10px; }
        .cat-card-new-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .cat-card-new-info { flex:1; min-width:0; }
        .cat-card-new-name { font-weight:800; font-size:.95rem; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .cat-card-new-count { font-size:.76rem; color:#94a3b8; margin-top:2px; }
        .cat-card-new-actions { display:flex; gap:6px; flex-shrink:0; }
        .cat-card-new-bar { margin:0 14px; height:5px; border-radius:99px; background:#f1f5f9; }
        .cat-card-new-fill { height:100%; border-radius:99px; transition:width .4s; }
        .cat-card-new-footer { display:flex; justify-content:space-between; align-items:center; padding:8px 14px 12px; }
        .cat-card-all {
            background:linear-gradient(135deg,#0a5565,#2ab8d0) !important;
            border-color:transparent !important;
        }
        .cat-action-btn {
            width:30px; height:30px; border-radius:8px; border:none; cursor:pointer;
            display:flex; align-items:center; justify-content:center; font-size:.78rem;
            transition:all .18s;
        }
        .cat-edit-btn { background:rgba(8,145,178,.1); color:#0891b2; }
        .cat-edit-btn:hover { background:#0891b2; color:#fff; }
        .cat-delete-btn { background:rgba(239,68,68,.1); color:#ef4444; }
        .cat-delete-btn:hover { background:#ef4444; color:#fff; }

        /* ═══ Association Items (New Design) ═══ */
        .assoc-item-new {
            display:flex; align-items:center; gap:14px; background:#fff;
            border:1.5px solid #e2e8f0; border-radius:14px; padding:14px 16px;
            transition:all .2s;
        }
        .assoc-item-new:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); border-color:#c7d2fe; }
        .assoc-item-avatar { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.3rem; }
        .assoc-item-info { flex:1; min-width:0; }
        .assoc-item-name { font-weight:800; font-size:.9rem; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .assoc-item-cat { font-size:.75rem; color:#64748b; margin-top:2px; }
        .assoc-item-email { font-size:.72rem; color:#94a3b8; margin-top:1px; direction:ltr; text-align:right; }
        .assoc-item-meta { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }
        .assoc-item-date { font-size:.72rem; color:#94a3b8; }

        /* SR status selection buttons inside modal */
        .sr-status-btn {
            flex:1; padding:10px 6px; border:1.5px solid #e2e8f0;
            border-radius:12px; background:#fff; cursor:pointer;
            font-family:inherit; font-weight:700; font-size:.82rem;
            color:#64748b; transition:all .2s;
        }
        .sr-status-btn.sr-status-active {
            border-color:#0891b2; color:#0891b2;
            background:rgba(8,145,178,.06);
        }
        .sr-status-btn[data-status="approved"].sr-status-active { border-color:#10b981; color:#10b981; background:rgba(16,185,129,.06); }
        .sr-status-btn[data-status="rejected"].sr-status-active { border-color:#ef4444; color:#ef4444; background:rgba(239,68,68,.06); }
        .sr-status-btn[data-status="pending"].sr-status-active  { border-color:#3b82f6; color:#3b82f6; background:rgba(59,130,246,.06); }

        /* SR detail modal */
        #sr-modal {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,.45); z-index:9999;
            align-items:center; justify-content:center;
        }
        #sr-modal.open { display:flex; }
        .sr-modal-card {
            width:520px; max-width:95vw; background:#fff;
            border-radius:20px; overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,.15);
            animation:srPop .22s ease;
        }
        @keyframes srPop { from{transform:scale(.93);opacity:0} to{transform:scale(1);opacity:1} }
        .sr-modal-head {
            background:linear-gradient(135deg,#062c35,#0891b2);
            padding:20px 24px; display:flex;
            align-items:center; justify-content:space-between;
        }
        .sr-modal-head h3 { color:#fff; font-size:1.1rem; font-weight:800; margin:0; }
        .sr-modal-close {
            background:rgba(255,255,255,.2); border:none;
            width:32px; height:32px; border-radius:50%;
            color:#fff; cursor:pointer; font-size:1rem;
            display:flex; align-items:center; justify-content:center;
        }
        .sr-modal-body { padding:24px; max-height:72vh; overflow-y:auto; background:#f8fafc; }
        .sr-modal-foot { padding:16px 24px; border-top:1px solid #e2e8f0; background:#fff; }
        .sr-save-btn {
            width:100%; padding:13px; border:none; border-radius:12px;
            background:linear-gradient(135deg,#062c35,#0891b2);
            color:#fff; font-size:1rem; font-weight:800;
            cursor:pointer; font-family:inherit; transition:opacity .2s;
        }
        .sr-save-btn:hover { opacity:.88; }
        .sr-save-btn:disabled { opacity:.6; cursor:not-allowed; }

        .srm-actor {
            background:#fff; border-radius:14px; padding:14px 16px;
            display:flex; align-items:center; gap:14px;
            margin-bottom:14px; border:1px solid #e2e8f0;
            box-shadow:0 2px 6px rgba(0,0,0,.03);
        }
        .srm-av {
            width:46px; height:46px; border-radius:12px;
            background:#0891b2; color:#fff; font-weight:800;
            font-size:1.1rem; display:flex; align-items:center;
            justify-content:center; flex-shrink:0;
        }
        .srm-info-card {
            background:#fff; border-radius:14px; padding:16px;
            margin-bottom:14px; border:1px solid #e2e8f0;
            box-shadow:0 2px 6px rgba(0,0,0,.03);
        }
        .srm-row { margin-bottom:12px; }
        .srm-row:last-child { margin-bottom:0; }
        .srm-label { font-size:.75rem; color:#94a3b8; font-weight:600; margin-bottom:3px; text-align:right; }
        .srm-value { font-size:.92rem; color:#1e293b; font-weight:700; text-align:right; }
        .srm-details-text { white-space:pre-wrap; line-height:1.6; font-weight:500; }
        .srm-meta-row {
            display:flex; justify-content:space-between;
            font-size:.82rem; color:#64748b; margin-bottom:14px; padding:0 2px;
        }
        .srm-status-row {
            background:#fff; border-radius:14px; padding:16px;
            border:1px solid #e2e8f0; box-shadow:0 2px 6px rgba(0,0,0,.03);
        }
        .srm-status-title { font-weight:800; color:#1e293b; margin-bottom:10px; font-size:.9rem; text-align:right; }
        .srm-status-btns { display:flex; gap:8px; }
    </style>
</head>
<body>

<div class="bg-bubbles">
    <div class="bg-shape bg-teal"></div>
    <div class="bg-shape bg-purple"></div>
    <div class="bg-shape bg-blue"></div>
</div>

@include('layouts.sidebar-admin', ['activeNav' => 'orders'])

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="tb-left">
            <div class="tb-title">صفحة الطلبات</div>
            <div class="tb-crumb">تكامل / <span>الطلبات</span></div>
        </div>
        <div class="tb-right">
            <div class="notif-btn" id="notif-btn" onclick="toggleNotifs()">
                <div class="notif-dot" id="notif-dot" style="display:none"></div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </div>
            <div class="tb-user">
                <div>
                    <div class="tu-name" id="tu-name">{{ Auth::user()?->full_name ?? 'المدير' }}</div>
                    <div class="tu-role">مسؤول المنصة</div>
                </div>
                <div class="user-av">{{ mb_substr(Auth::user()?->full_name ?? 'م', 0, 1) }}</div>
            </div>
        </div>
    </div>

    <main>
        <!-- PAGE HEAD -->
        <div class="page-head">
            <div>
                <h1><i class="fa-solid fa-clipboard-list" style="margin-left:8px;color:var(--teal)"></i> صفحة الطلبات</h1>
                <p>إدارة طلبات إنشاء الحسابات وطلبات خدمات مبادرون</p>
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
            <button class="tab-btn" onclick="switchTab('service-requests', this)">
                طلبات الخدمات
                <span id="sr-pending-count" style="background:#ef4444;color:#fff;border-radius:20px;padding:1px 8px;font-size:.72rem;font-weight:800;margin-right:6px;display:none"></span>
            </button>
            <button class="tab-btn" onclick="switchTab('associations', this)">تصنيفات الجمعيات</button>
        </div>

        <!-- TAB: ACCOUNT REQUESTS -->
        <div class="tab-content active" id="tab-requests">
            <div class="table-toolbar">
                <div class="search-box">
                    <span><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" placeholder="بحث بالاسم أو البريد الإلكتروني..." onkeyup="filterTable(this.value)" />
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
                    <tbody id="requestsTbody"></tbody>
                </table>
            </div>
        </div>

        <!-- TAB: SERVICE REQUESTS -->
        <div class="tab-content" id="tab-service-requests">
            <div class="table-toolbar">
                <div class="search-box">
                    <span><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" placeholder="بحث بالاسم أو عنوان الطلب..." oninput="searchSr(this.value)" />
                </div>
                <div id="sr-filter-tabs">
                    <button class="sr-tab-btn active" data-status="all"        onclick="filterSrByStatus('all')">الكل</button>
                    <button class="sr-tab-btn"         data-status="pending"   onclick="filterSrByStatus('pending')">جديد</button>
                    <button class="sr-tab-btn"         data-status="processing"onclick="filterSrByStatus('processing')">قيد المعالجة</button>
                    <button class="sr-tab-btn"         data-status="approved"  onclick="filterSrByStatus('approved')">مقبول</button>
                    <button class="sr-tab-btn"         data-status="rejected"  onclick="filterSrByStatus('rejected')">مرفوض</button>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="text-align:right">مقدم الطلب</th>
                            <th>الطلب</th>
                            <th>نوع الخدمة</th>
                            <th>الحالة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody id="sr-tbody">
                        <tr><td colspan="5" class="sr-loading">جاري التحميل...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-content" id="tab-associations">
            <div class="section-head">
                <h2><i class="fa-solid fa-layer-group" style="margin-left:8px;color:var(--teal)"></i> تصنيفات الجمعيات</h2>
                <button class="btn btn-primary" onclick="openAddCategoryModal()"><i class="fa-solid fa-plus" style="margin-left:6px"></i> إضافة تصنيف</button>
            </div>
            <div class="categories-grid" id="categoriesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:16px;margin-bottom:28px"></div>

            <div class="section-head" style="margin-top:8px">
                <div id="assoc-section-head">
                    <div style="font-weight:800;font-size:1.05rem;color:var(--text)"><i class="fa-solid fa-building" style="color:var(--teal);margin-left:8px"></i>الجمعيات المسجلة</div>
                </div>
                <div class="filter-group">
                    <select id="catFilter" onchange="filterAssoc()" style="padding:7px 12px;border-radius:10px;border:1.5px solid #e2e8f0;font-family:inherit;font-size:.85rem">
                        <option value="">كل التصنيفات</option>
                    </select>
                </div>
            </div>
            <div class="assoc-list" id="assocList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:12px;margin-top:12px"></div>
        </div>

    </main>
</div>

<!-- ── Account Request Details Modal ── -->
<div class="modal-overlay" id="modal">
    <div class="modal">
        <div class="modal-head">
            <h3>تفاصيل الطلب</h3>
            <button class="btn btn-ghost" onclick="closeModal()" style="padding:.3rem .7rem;">✕</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer">
            <button class="btn btn-ghost"    onclick="closeModal()">إغلاق</button>
            <button class="btn btn-danger"   onclick="closeModal()">رفض</button>
            <button class="btn btn-primary"  onclick="closeModal()">✓ موافقة</button>
        </div>
    </div>
</div>

<!-- ── Action Modal (reject/review with notes) ── -->
<div class="modal-overlay" id="action-modal">
    <div class="modal">
        <div class="modal-head" style="display:flex;align-items:center;gap:10px">
            <button class="btn btn-ghost" onclick="closeActionModal()" style="padding:.3rem .7rem;margin-right:auto">✕</button>
            <span id="action-modal-icon"></span>
            <h3 id="action-modal-title" style="margin:0"></h3>
        </div>
        <div class="modal-body" style="padding:1.5rem">
            <div style="font-size:.85rem;color:var(--text-muted);margin-bottom:1rem" id="action-modal-sub"></div>
            <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.5rem" id="action-notes-label">الملاحظات *</label>
            <textarea id="action-notes" rows="4" style="width:100%;padding:.75rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:.9rem;resize:vertical;outline:none" placeholder="..."></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeActionModal()">إلغاء</button>
            <button class="btn btn-danger" id="action-confirm-btn" onclick="confirmAction()">تأكيد</button>
        </div>
    </div>
</div>

<!-- ── Approve Confirmation Modal ── -->
<div class="modal-overlay" id="approve-modal">
    <div class="modal" style="max-width:420px;text-align:center;padding:2rem">
        <div style="width:60px;height:60px;background:#10b981;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" width="28" height="28"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3 style="margin:0 0 .5rem;font-size:1.2rem">تأكيد قبول الطلب</h3>
        <p id="approve-sub" style="color:var(--text-muted);font-size:.9rem;margin-bottom:1.5rem">هل تريد قبول هذا الطلب؟</p>
        <p style="font-size:.8rem;color:#94a3b8;margin-bottom:1.5rem">سيتم إشعار الجمعية بعد القبول.</p>
        <div style="display:flex;gap:10px;justify-content:center">
            <button class="btn btn-success" onclick="confirmApprove()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg>
                قبول الطلب
            </button>
            <button class="btn btn-ghost" onclick="closeApproveModal()">إلغاء</button>
        </div>
    </div>
</div>

<!-- ── Add Category Modal ── -->
<div class="modal-overlay" id="add-cat-modal">
    <div class="modal" style="max-width:440px">
        <div class="modal-head">
            <h3><i class="fa-solid fa-layer-group" style="margin-left:8px;color:var(--teal)"></i> إضافة تصنيف جديد</h3>
            <button class="btn btn-ghost" onclick="closeAddCategoryModal()" style="padding:.3rem .7rem">✕</button>
        </div>
        <div class="modal-body" style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
            <div>
                <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اسم التصنيف <span style="color:#ef4444">*</span></label>
                <input id="cat-name-input" type="text" placeholder="مثال: تعليمية" style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:.9rem;outline:none;box-sizing:border-box">
            </div>
            <div>
                <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اللون</label>
                <div style="display:flex;align-items:center;gap:10px">
                    <input id="cat-color-input" type="color" value="#2ab8d0" style="width:40px;height:40px;border:none;border-radius:8px;cursor:pointer;padding:2px" onchange="document.getElementById('cat-color-label').textContent=this.value.toUpperCase()">
                    <span id="cat-color-label" style="font-size:.85rem;color:var(--text-muted);font-weight:600">#2AB8D0</span>
                </div>
            </div>
            <div>
                <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">الأيقونة (رمز تعبيري)</label>
                <input id="cat-icon-add-input" type="text" placeholder="مثال: 🎓" style="width:100%;padding:.6rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:1.1rem;outline:none;box-sizing:border-box">
            </div>
            <div id="cat-emoji-grid" style="display:flex;flex-wrap:wrap;gap:8px"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeAddCategoryModal()">إلغاء</button>
            <button class="btn btn-primary" onclick="saveNewCategory()"><i class="fa-solid fa-floppy-disk" style="margin-left:6px"></i> حفظ التصنيف</button>
        </div>
    </div>
</div>

<!-- ── Edit Category Modal ── -->
<div class="modal-overlay" id="edit-cat-modal">
    <div class="modal" style="max-width:440px">
        <div class="modal-head" style="background:linear-gradient(135deg,#0a5565,#2ab8d0)">
            <h3 style="color:#fff"><i class="fa-solid fa-pen-to-square" style="margin-left:8px"></i> تعديل التصنيف</h3>
            <button class="btn" onclick="closeEditCategoryModal()" style="padding:.3rem .7rem;background:rgba(255,255,255,.2);color:#fff;border-color:transparent">✕</button>
        </div>
        <div class="modal-body" style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem">
            <div>
                <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اسم التصنيف <span style="color:#ef4444">*</span></label>
                <input id="edit-cat-name-input" type="text" placeholder="اسم التصنيف" style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:.9rem;outline:none;box-sizing:border-box">
            </div>
            <div>
                <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">اللون</label>
                <div style="display:flex;align-items:center;gap:10px">
                    <input id="edit-cat-color-input" type="color" value="#2ab8d0" style="width:40px;height:40px;border:none;border-radius:8px;cursor:pointer;padding:2px" onchange="document.getElementById('edit-cat-color-label').textContent=this.value.toUpperCase()">
                    <span id="edit-cat-color-label" style="font-size:.85rem;color:var(--text-muted);font-weight:600">#2AB8D0</span>
                </div>
            </div>
            <div>
                <label style="font-weight:700;font-size:.85rem;display:block;margin-bottom:.4rem">الأيقونة (رمز تعبيري)</label>
                <input id="edit-cat-icon-input" type="text" placeholder="مثال: 🏫" style="width:100%;padding:.6rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:1.1rem;outline:none;box-sizing:border-box">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeEditCategoryModal()">إلغاء</button>
            <button class="btn btn-primary" id="edit-cat-save-btn" onclick="saveEditCategory()"><i class="fa-solid fa-floppy-disk" style="margin-left:6px"></i> حفظ التعديلات</button>
        </div>
    </div>
</div>

<!-- ── Service Request Detail Modal ── -->
<div id="sr-modal">
    <div class="sr-modal-card">
        <div class="sr-modal-head">
            <h3><i class="fa-solid fa-wand-magic-sparkles" style="margin-left:6px"></i> تفاصيل طلب الخدمة</h3>
            <button class="sr-modal-close" onclick="closeSrModal()">✕</button>
        </div>
        <div class="sr-modal-body">
            <!-- Actor -->
            <div class="srm-actor">
                <div class="srm-av" id="srm-av">م</div>
                <div>
                    <div style="font-weight:800;font-size:1rem;color:#1e293b" id="srm-name"></div>
                    <div style="font-size:.82rem;color:#94a3b8"               id="srm-email"></div>
                </div>
                <div style="margin-right:auto">
                    <span class="badge" id="srm-status"></span>
                </div>
            </div>
            <!-- Details -->
            <div class="srm-info-card">
                <div class="srm-row">
                    <div class="srm-label">نوع الخدمة</div>
                    <div class="srm-value" id="srm-type" style="color:#7c3aed"></div>
                </div>
                <div class="srm-row">
                    <div class="srm-label">عنوان الطلب</div>
                    <div class="srm-value" id="srm-title"></div>
                </div>
                <div class="srm-row">
                    <div class="srm-label">تفاصيل الطلب</div>
                    <div class="srm-value srm-details-text" id="srm-details"></div>
                </div>
            </div>
            <div class="srm-meta-row">
                <span>الميزانية: <strong id="srm-budget"></strong></span>
                <span>التاريخ المفضل: <strong id="srm-date"></strong></span>
            </div>
            <div class="srm-meta-row" style="margin-bottom:14px">
                <span>تاريخ الإرسال: <strong id="srm-created"></strong></span>
            </div>
            <!-- Status selection -->
            <div class="srm-status-row">
                <div class="srm-status-title">تغيير الحالة</div>
                <div class="srm-status-btns">
                    <button class="sr-status-btn" data-status="rejected"   onclick="selectSrStatus('rejected')">رفض</button>
                    <button class="sr-status-btn" data-status="processing" onclick="selectSrStatus('processing')">قيد المعالجة</button>
                    <button class="sr-status-btn" data-status="approved"   onclick="selectSrStatus('approved')">موافقة</button>
                </div>
            </div>
        </div>
        <div class="sr-modal-foot">
            <button class="sr-save-btn" id="sr-save-btn" onclick="saveSrStatus()">حفظ الحالة</button>
        </div>
    </div>
</div>

<script>
// Show pending count badge on the tab button
function updateSrTabBadge() {
  const count = (window.allServiceReqs || []).filter(r => r.status === 'pending').length;
  const el = document.getElementById('sr-pending-count');
  if (el) { el.textContent = count; el.style.display = count > 0 ? 'inline' : 'none'; }
}
// Patch loadServiceRequests to also update the tab badge
document.addEventListener('DOMContentLoaded', () => {
  const orig = window.loadServiceRequests;
  if (orig) {
    window.loadServiceRequests = async function() {
      await orig();
      updateSrTabBadge();
    };
  }
  initOrders();
});
</script>

<script src="{{ asset('js/orders.js') }}?v={{ time() }}"></script>
</body>
</html>
