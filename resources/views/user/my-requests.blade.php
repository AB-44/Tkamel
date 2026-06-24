<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تكامل — طلباتي</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/user-dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/user-my-requests.css') }}?v={{ time() }}">
</head>

<body>

    <!-- ══ SIDEBAR ══ -->
    @include('layouts.sidebar-user', ['activeNav' => 'orders'])

    <!-- ══ MAIN ══ -->
    <div class="main-wrapper">

        <!-- TOPBAR -->
        @include('layouts.topbar', ['title' => 'طلباتي', 'crumb' => 'الرئيسية / طلباتي'])

        <!-- CONTENT -->
        <div class="content mr-content">

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
                        <div class="mr-stat-val">{{ $stats['total_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">إجمالي الطلبات</div>
                    </div>
                </div>
                <div class="mr-stat-card" style="border-right-color: #f59e0b;">
                    <div class="mr-stat-icon" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; font-size: 1.6rem; color: #f59e0b;">
                        ⏳
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['pending_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">قيد المراجعة</div>
                    </div>
                </div>
                <div class="mr-stat-card" style="border-right-color: #10b981;">
                    <div class="mr-stat-icon" style="background: rgba(16, 185, 129, 0.1); border-radius: 12px; font-size: 1.6rem; color: #10b981;">
                        ✅
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['approved_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">مقبولة</div>
                    </div>
                </div>
                <div class="mr-stat-card" style="border-right-color: #ef4444;">
                    <div class="mr-stat-icon" style="background: rgba(239, 68, 68, 0.1); border-radius: 12px; font-size: 1.6rem; color: #ef4444;">
                        ❌
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['rejected_requests'] ?? 0 }}</div>
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
                    <span class="mr-tab-badge">{{ $stats['total_requests'] ?? 0 }}</span>
                </button>
                <button class="mr-tab" data-tab="opportunity">
                    <i class="fa-solid fa-user-tie"></i> فرص التطوع
                    <span class="mr-tab-badge">{{ $allRequests->where('type', 'opportunity')->count() }}</span>
                </button>
                <button class="mr-tab" data-tab="project">
                    <i class="fa-solid fa-diagram-project"></i> المشاريع المشتركة
                    <span class="mr-tab-badge">{{ $allRequests->where('type', 'project')->count() }}</span>
                </button>
                <button class="mr-tab" data-tab="service">
                    <i class="fa-solid fa-suitcase-medical"></i> خدمات مبادرون
                    <span class="mr-tab-badge">{{ $allRequests->where('type', 'service')->count() }}</span>
                </button>
            </div>

            <!-- REQUESTS LIST / EMPTY STATE -->
            <div class="mr-list-container" id="requests-list">
                @forelse($allRequests as $req)
                    @php
                        $statusClass = '';
                        $statusLabel = '';
                        if (in_array($req->status, ['pending', 'review'])) {
                            $statusClass = 'status-pending';
                            $statusLabel = 'قيد المراجعة';
                            $dataStatus = 'pending';
                        } elseif (in_array($req->status, ['approved', 'completed'])) {
                            $statusClass = 'status-approved';
                            $statusLabel = 'مقبول';
                            $dataStatus = 'approved';
                        } else {
                            $statusClass = 'status-rejected';
                            $statusLabel = 'مرفوض';
                            $dataStatus = 'rejected';
                        }
                    @endphp
                    <div class="mr-req-card"
                         data-status="{{ $dataStatus }}"
                         data-type="{{ $req->type }}"
                         data-id="{{ $req->id }}"
                         data-title="{{ e($req->title) }}"
                         data-sub="{{ e($req->sub) }}"
                         data-date="{{ \Carbon\Carbon::parse($req->date)->translatedFormat('d M Y') }}"
                         data-status-label="{{ $statusLabel }}"
                         data-status-class="{{ $statusClass }}"
                         data-color="{{ $req->color }}"
                         data-icon="{{ $req->icon }}"
                         data-notes="{{ e($req->notes ?? '') }}"
                         @if($req->type === 'service')
                         data-service-type="{{ $req->service_type ?? '' }}"
                         data-budget="{{ e($req->budget ?? '') }}"
                         data-preferred-date="{{ $req->preferred_date ? \Carbon\Carbon::parse($req->preferred_date)->translatedFormat('d M Y') : '' }}"
                         data-preferred-date-raw="{{ $req->preferred_date ?? '' }}"
                         data-details="{{ e($req->details ?? '') }}"
                         @endif
                         @if($req->type === 'opportunity')
                         data-deadline="{{ $req->opportunity?->deadline ? \Carbon\Carbon::parse($req->opportunity->deadline)->translatedFormat('d M Y') : '' }}"
                         data-opp-desc="{{ e($req->opportunity?->description ?? '') }}"
                         @endif
                         @if($req->type === 'project')
                         data-project-desc="{{ e($req->project?->description ?? '') }}"
                         @endif
                    >
                        <div class="mr-req-icon" style="background: {{ $req->color }}1a; color: {{ $req->color }};">
                            <i class="fa-solid {{ $req->icon }}"></i>
                        </div>
                        <div class="mr-req-info">
                            <div class="mr-req-title">{{ $req->title }}</div>
                            <div class="mr-req-sub">{{ $req->sub }}</div>
                        </div>
                        <div class="mr-req-date">{{ \Carbon\Carbon::parse($req->date)->translatedFormat('d M Y') }}</div>
                        <div class="mr-req-status">
                            <span class="mr-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <div class="mr-req-actions">
                            <button class="mr-btn-details" onclick="openReqModal(this)">
                                تفاصيل
                                <i class="fa-solid fa-chevron-left" style="font-size:0.7rem; margin-right:2px;"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="mr-empty">
                        <img src="{{ asset('images/mailbox.png') }}" alt="Empty Mailbox" style="width: 80px; margin-bottom: 1rem; opacity: 0.8;" onerror="this.outerHTML='<div style=\'font-size:4rem; margin-bottom: 1rem;\'>📫</div>'">
                        <h3>لا توجد طلبات</h3>
                        <p>لم تقدم أي طلبات تطابق الفلتر الحالي</p>
                    </div>
                @endforelse
                
                <!-- Client Side Empty State (Hidden initially) -->
                <div class="mr-empty" id="client-empty" style="display: none;">
                    <div style="font-size:4rem; margin-bottom: 1rem;">📫</div>
                    <h3>لا توجد طلبات</h3>
                    <p>لم تقدم أي طلبات تطابق الفلتر الحالي</p>
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    @include('layouts.notif-panel-user')

    <!-- ══ REQUEST DETAILS MODAL ══ -->
    <div id="req-modal-overlay"
         style="display:none; position:fixed; inset:0; background:rgba(7,28,45,0.6); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center; padding:20px;"
         onclick="closeReqModal(event)">

      <div id="req-modal-box"
           style="background:#fff; border-radius:20px; width:100%; max-width:580px; box-shadow:0 32px 80px rgba(7,28,45,0.25); overflow:hidden; display:flex; flex-direction:column; max-height:90vh; animation:rmo-in 0.3s cubic-bezier(.4,0,.2,1);">

        <!-- Gradient Header -->
        <div id="req-modal-hdr"
             style="background:linear-gradient(135deg,#071c2d 0%,#0c6080 60%,#0ea5c9 100%); padding:26px 24px 22px; position:relative; overflow:hidden; flex-shrink:0;">
          <!-- Decorative circles -->
          <div style="position:absolute;top:-30px;left:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.05);"></div>
          <div style="position:absolute;bottom:-20px;right:20px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.06);"></div>

          <!-- Close -->
          <button onclick="closeReqModal()" style="position:absolute;top:16px;left:16px;width:32px;height:32px;border-radius:8px;border:none;background:rgba(255,255,255,.12);color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.95rem;transition:background .2s;" onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
            <i class="fa-solid fa-xmark"></i>
          </button>

          <div style="display:flex;align-items:center;gap:14px;">
            <!-- Icon -->
            <div id="rmd-icon" style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:white;flex-shrink:0;"></div>
            <div style="flex:1;min-width:0;">
              <div id="rmd-title" style="font-size:1.1rem;font-weight:900;color:white;margin-bottom:4px;line-height:1.3;"></div>
              <div id="rmd-sub" style="font-size:.8rem;color:rgba(255,255,255,.7);font-weight:500;"></div>
            </div>
            <div id="rmd-badge" style="flex-shrink:0;"></div>
          </div>

          <!-- Meta strip -->
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
      /* Detail rows */
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
      /* Edit form */
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
      /* Footer buttons */
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
      /* Meta pill -->
      .rmd-mpill {
        display:flex; align-items:center; gap:6px;
        font-size:.78rem; color:rgba(255,255,255,.8); font-weight:600;
      }
      .rmd-mpill i { font-size:.75rem; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('mrSearchInput');
            const statusFilter = document.getElementById('mrStatusFilter');
            const chips = document.querySelectorAll('.mr-chip');
            const tabs = document.querySelectorAll('.mr-tab');
            const items = document.querySelectorAll('.mr-req-card');
            const emptyState = document.getElementById('client-empty');
            const serverEmpty = document.querySelector('.mr-empty:not(#client-empty)');

            let currentType = 'all';
            let currentStatus = 'all';
            let currentQuery = '';

            function filterItems() {
                let visibleCount = 0;

                items.forEach(item => {
                    const typeMatch = currentType === 'all' || item.dataset.type === currentType;
                    const statusMatch = currentStatus === 'all' || item.dataset.status === currentStatus;
                    
                    const title = item.querySelector('.mr-req-title').textContent.toLowerCase();
                    const sub = item.querySelector('.mr-req-sub').textContent.toLowerCase();
                    const queryMatch = currentQuery === '' || title.includes(currentQuery) || sub.includes(currentQuery);

                    if (typeMatch && statusMatch && queryMatch) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (serverEmpty) {
                    serverEmpty.style.display = 'none';
                }

                if (visibleCount === 0 && items.length > 0) {
                    emptyState.style.display = 'flex';
                } else {
                    emptyState.style.display = 'none';
                }
            }

            // Sync Chips and Tabs
            function setTypeFilter(type) {
                currentType = type;
                chips.forEach(c => c.classList.toggle('active', c.dataset.type === type));
                tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === type));
                filterItems();
            }

            chips.forEach(chip => {
                chip.addEventListener('click', () => setTypeFilter(chip.dataset.type));
            });
            tabs.forEach(tab => {
                tab.addEventListener('click', () => setTypeFilter(tab.dataset.tab));
            });
            statusFilter.addEventListener('change', (e) => { currentStatus = e.target.value; filterItems(); });
            searchInput.addEventListener('input', (e) => { currentQuery = e.target.value.toLowerCase(); filterItems(); });

            // Auto-open request if req_id is in URL
            const urlParams = new URLSearchParams(window.location.search);
            const reqIdParam = urlParams.get('req_id');
            const typeParam = urlParams.get('type');
            if (reqIdParam) {
                // Determine the correct category if possible
                let cat = 'all';
                if (typeParam === 'service_request_created' || typeParam === 'service_request_approved') cat = 'service';
                else if (typeParam === 'opportunity_approved' || typeParam === 'opportunity_rejected') cat = 'opportunity';
                else if (typeParam === 'project_join_approved' || typeParam === 'project_join_rejected') cat = 'project';
                
                if (cat !== 'all') {
                    setTypeFilter(cat);
                }
                
                const targetCard = Array.from(items).find(i => String(i.dataset.id) === String(reqIdParam));
                if (targetCard) {
                    setTimeout(() => {
                        const btn = targetCard.querySelector('.mr-btn-details');
                        if (btn) btn.click();
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }, 300);
                }
            }
        });

        /* ══════════════ MODAL LOGIC ══════════════ */
        const STATUS_META = {
            'status-pending':  { label:'قيد المراجعة', bg:'rgba(245,158,11,.15)', color:'#d97706', border:'rgba(245,158,11,.35)' },
            'status-approved': { label:'مقبول',        bg:'rgba(16,185,129,.15)', color:'#059669', border:'rgba(16,185,129,.35)' },
            'status-rejected': { label:'مرفوض',        bg:'rgba(239,68,68,.15)',  color:'#dc2626', border:'rgba(239,68,68,.35)'  }
        };
        const TYPE_LABELS = { opportunity:'فرصة تطوع', project:'مشروع مشترك', service:'خدمة مبادرون' };
        const SVC_TYPES   = { units:'بناء وحدات/أنظمة', training:'تدريب المتطوعين', initiatives:'تنسيق المبادرات', consulting:'استشارات متخصصة', other:'طلب آخر' };

        let _currentCard = null;

        function getCsrf() { return document.querySelector('meta[name="csrf-token"]')?.content || ''; }

        /* ── open ── */
        function openReqModal(btn) {
            _currentCard = btn.closest('.mr-req-card');
            const d  = _currentCard.dataset;
            const st = STATUS_META[d.statusClass] || {};

            document.getElementById('rmd-icon').innerHTML  = '<i class="fa-solid ' + d.icon + '"></i>';
            document.getElementById('rmd-title').textContent = d.title;
            document.getElementById('rmd-sub').textContent   = d.sub;
            document.getElementById('rmd-badge').innerHTML   =
                '<span style="background:' + st.bg + ';color:' + st.color + ';border:1px solid ' + st.border + ';padding:5px 16px;border-radius:20px;font-size:.78rem;font-weight:800;font-family:Tajawal,sans-serif;">' + d.statusLabel + '</span>';

            document.getElementById('rmd-meta').innerHTML =
                '<div class="rmd-mpill"><i class="fa-regular fa-calendar"></i>' + d.date + '</div>' +
                '<div class="rmd-mpill"><i class="fa-solid fa-tag"></i>' + (TYPE_LABELS[d.type] || d.type) + '</div>';

            buildViewBody(d);
            buildFooter(d);

            document.getElementById('rmd-body').style.display = 'block';
            document.getElementById('rmd-edit').style.display  = 'none';
            document.getElementById('req-modal-overlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        /* ── view body ── */
        function buildViewBody(d) {
            const row = (icon, bg, color, label, val) => !val ? '' :
                '<div class="rmd-row">' +
                  '<div class="rmd-ico" style="background:' + bg + ';color:' + color + ';"><i class="fa-solid ' + icon + '"></i></div>' +
                  '<div><div class="rmd-lbl">' + label + '</div><div class="rmd-val">' + val + '</div></div>' +
                '</div>';

            let html = '';
            if (d.type === 'opportunity') {
                html += row('fa-align-right','rgba(202,138,4,.1)','#ca8a04','وصف الفرصة', d.oppDesc);
                html += row('fa-calendar-xmark','rgba(225,29,72,.1)','#e11d48','الموعد النهائي', d.deadline);
            }
            if (d.type === 'project') {
                html += row('fa-align-right','rgba(22,163,74,.1)','#16a34a','وصف المشروع', d.projectDesc);
            }
            if (d.type === 'service') {
                html += row('fa-screwdriver-wrench','rgba(124,58,237,.1)','#7c3aed','نوع الخدمة', SVC_TYPES[d.serviceType] || d.serviceType);
                html += row('fa-file-lines','rgba(124,58,237,.1)','#7c3aed','تفاصيل الطلب', d.details);
                html += row('fa-coins','rgba(217,119,6,.1)','#d97706','الميزانية', d.budget ? d.budget + ' ريال' : '');
                html += row('fa-calendar-check','rgba(22,163,74,.1)','#16a34a','التاريخ المفضّل', d.preferredDate);
            }
            html += row('fa-comment-dots','rgba(100,116,139,.1)','#64748b','ملاحظات', d.notes);

            document.getElementById('rmd-body').innerHTML = html ||
                '<p style="color:#94a3b8;text-align:center;padding:2.5rem 1rem;font-family:Tajawal,sans-serif;font-size:.9rem;">لا توجد تفاصيل إضافية.</p>';
        }

        /* ── footer ── */
        function buildFooter(d) {
            const foot = document.getElementById('rmd-footer');
            let html = '';
            if (d.status === 'pending' && d.type === 'service') {
                html += '<button class="rmd-btn rmd-btn-edit" onclick="switchToEdit()"><i class="fa-solid fa-pen-to-square"></i>تعديل الطلب</button>';
            }
            html += '<button class="rmd-btn rmd-btn-cancel" onclick="closeReqModal()"><i class="fa-solid fa-xmark"></i>إغلاق</button>';
            foot.innerHTML = html;
        }

        /* ── switch to edit ── */
        function switchToEdit() {
            const d = _currentCard.dataset;
            document.getElementById('rmd-body').style.display = 'none';
            document.getElementById('rmd-edit').style.display  = 'block';

            document.getElementById('rmd-edit').innerHTML = `
              <div class="rmd-fg">
                <label>عنوان الطلب</label>
                <input type="text" id="ef-title" value="${escHtml(d.title)}" placeholder="عنوان الطلب">
              </div>
              <div class="rmd-fg">
                <label>نوع الخدمة</label>
                <select id="ef-type">
                  <option value="units"       ${d.serviceType==='units'?'selected':''}>بناء وحدات/أنظمة</option>
                  <option value="training"    ${d.serviceType==='training'?'selected':''}>تدريب المتطوعين</option>
                  <option value="initiatives" ${d.serviceType==='initiatives'?'selected':''}>تنسيق المبادرات</option>
                  <option value="consulting"  ${d.serviceType==='consulting'?'selected':''}>استشارات متخصصة</option>
                  <option value="other"       ${d.serviceType==='other'?'selected':''}>طلب آخر</option>
                </select>
              </div>
              <div class="rmd-fg">
                <label>تفاصيل الطلب</label>
                <textarea id="ef-details">${escHtml(d.details || '')}</textarea>
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="rmd-fg">
                  <label>الميزانية (ريال)</label>
                  <input type="number" id="ef-budget" value="${escHtml(d.budget||'')}" placeholder="0" min="0">
                </div>
                <div class="rmd-fg">
                  <label>التاريخ المفضّل</label>
                  <input type="date" id="ef-pdate" value="${escHtml(d.preferredDateRaw||'')}">
                </div>
              </div>
              <div id="ef-msg" style="display:none;padding:10px 14px;border-radius:10px;font-size:.85rem;font-weight:600;font-family:Tajawal,sans-serif;margin-top:4px;"></div>`;

            document.getElementById('rmd-footer').innerHTML =
                '<button class="rmd-btn rmd-btn-save" onclick="saveEdit(' + d.id + ')"><i class="fa-solid fa-floppy-disk"></i>حفظ التعديلات</button>' +
                '<button class="rmd-btn rmd-btn-cancel" onclick="switchToView()"><i class="fa-solid fa-arrow-right"></i>رجوع</button>';
        }

        /* ── switch back to view ── */
        function switchToView() {
            document.getElementById('rmd-body').style.display = 'block';
            document.getElementById('rmd-edit').style.display  = 'none';
            buildViewBody(_currentCard.dataset);
            buildFooter(_currentCard.dataset);
        }

        /* ── save edit ── */
        function saveEdit(id) {
            const msg     = document.getElementById('ef-msg');
            const title   = document.getElementById('ef-title').value.trim();
            const details = document.getElementById('ef-details').value.trim();

            if (!title || !details) {
                showMsg(msg, 'error', 'يرجى تعبئة جميع الحقول المطلوبة.');
                return;
            }

            const saveBtn = document.querySelector('.rmd-btn-save');
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>جارٍ الحفظ...';

            fetch('/user/service-requests/' + id, {
                method: 'PUT',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': getCsrf() },
                body: JSON.stringify({
                    service_type:   document.getElementById('ef-type').value,
                    title:          title,
                    details:        details,
                    budget:         document.getElementById('ef-budget').value || null,
                    preferred_date: document.getElementById('ef-pdate').value || null,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Update card dataset
                    const d = _currentCard.dataset;
                    d.title       = title;
                    d.details     = details;
                    d.budget      = document.getElementById('ef-budget').value;
                    d.preferredDateRaw = document.getElementById('ef-pdate').value;
                    d.serviceType = document.getElementById('ef-type').value;
                    // Update visible card title
                    const ct = _currentCard.querySelector('.mr-req-title');
                    if (ct) ct.textContent = title;
                    // Update modal header title
                    document.getElementById('rmd-title').textContent = title;
                    showMsg(msg, 'success', 'تم حفظ التعديلات بنجاح ✓');
                    setTimeout(switchToView, 1300);
                } else {
                    showMsg(msg, 'error', data.message || 'حدث خطأ غير متوقع.');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>حفظ التعديلات';
                }
            })
            .catch(() => {
                showMsg(msg, 'error', 'تعذّر الاتصال بالخادم.');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>حفظ التعديلات';
            });
        }

        function showMsg(el, type, text) {
            el.style.display    = 'block';
            el.style.background = type === 'success' ? 'rgba(16,185,129,.1)' : 'rgba(239,68,68,.1)';
            el.style.color      = type === 'success' ? '#059669' : '#dc2626';
            el.textContent      = text;
        }

        /* ── close ── */
        function closeReqModal(e) {
            if (e && e.target !== document.getElementById('req-modal-overlay')) return;
            document.getElementById('req-modal-overlay').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.getElementById('req-modal-overlay').style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        function escHtml(s) {
            return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
    </script>
</body>
</html>

