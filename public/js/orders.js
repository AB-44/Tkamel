// ===================== STATIC DATA (categories & associations tabs only) =====================
const avatarColors = ['#1f6feb', '#2ea043', '#d29922', '#cf222e', '#8957e5', '#0891b2', '#b45309', '#0e7490'];

let categories = [];
const categoryEmojiChoices = ['🏫', '🎓', '🏥', '⚕️', '🌱', '🤲', '🤝', '🕌', '📚', '♿', '💻', '🌍', '🏠', '💼', '🚀', '🎯'];
let selectedCategoryEmoji = '';

async function fetchOrderCategories() {
  try {
    const res = await fetch('/api/association-categories', {
      headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) return;
    const data = await res.json();
    const remote = (data.categories || []).map(c => ({
      id: c.id,
      name: c.name,
      icon: c.icon || '',
      color: c.color || '#2ab8d0',
      bg: (c.color || '#2ab8d0') + '22',
      count: c.total_count || 0,
      fill: c.fill_percentage || 0,
      desc: c.description || c.name,
    }));
    if (remote.length) {
      categories.splice(0, categories.length, ...remote);
    }
  } catch (e) {
    console.error('Error fetching association categories', e);
  }
}


const statusMap = {
  pending: { label: 'قيد المراجعة', cls: 'badge-pending' },
  approved: { label: 'موافق عليها', cls: 'badge-approved' },
  rejected: { label: 'مرفوض', cls: 'badge-rejected' },
  review: { label: 'يحتاج تعديل', cls: 'badge-review' },
};

const catIconMap = {};

function initials(n) { return (n || '').split(' ').slice(0, 2).map(w => w[0]).join(''); }
function formatDate(d) { return new Date(d).toLocaleDateString('ar-SA', { year: 'numeric', month: 'short', day: 'numeric' }); }
function escQ(s) { return (s || '').replace(/'/g, "\\'"); }

// ===================== DB DATA =====================
let dbAssocRequests = [];
let _allData = [];

async function loadAssociationRequests() {
  try {
    const res = await fetch('/api/association-requests', {
      credentials: 'same-origin', headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) return;
    const data = await res.json();

    dbAssocRequests = data.map(a => ({
      id: 'REG-' + String(a.id).padStart(3, '0'),
      _dbId: a.id,
      name: a.manager_name,
      email: a.email,
      type: 'تسجيل جمعية',
      assoc: a.association_name,
      cat: a.category,
      phone: a.phone,
      license: a.license_number,
      notes: a.admin_notes || '',
      date: a.created_at,
      status: a.status || 'pending',
      _source: 'db',
    }));

    _allData = [...dbAssocRequests];
    renderRequests(_allData);
    updateOrderStats();
    updateSidebarBadgesWithDb();

    // Auto-open specific request if req_id is present in URL
    const urlParams = new URLSearchParams(window.location.search);
    const reqIdParam = urlParams.get('req_id');
    const typeParam = urlParams.get('type');
    if (reqIdParam && (!typeParam || typeParam === 'association_registration')) {
      const targetReq = _allData.find(r => String(r._dbId) === String(reqIdParam));
      if (targetReq) {
        setTimeout(() => {
          _activateTab('requests');
          openRequestModal(targetReq);
          window.history.replaceState({}, document.title, window.location.pathname + window.location.hash);
        }, 500);
      }
    }

  } catch (e) { }
}

// ── Live stats for the 4 header cards ────────────────────────────────────────
function updateOrderStats() {
  const data = dbAssocRequests;
  const total = data.length;
  const pending = data.filter(r => r.status === 'pending' || r.status === 'review').length;
  const approved = data.filter(r => r.status === 'approved').length;
  const rejected = data.filter(r => r.status === 'rejected').length;

  // This month count
  const now = new Date();
  const thisMonth = data.filter(r => {
    const d = new Date(r.date);
    return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
  }).length;

  const approvalRate = total > 0 ? ((approved / total) * 100).toFixed(1) : 0;
  const rejectionRate = total > 0 ? ((rejected / total) * 100).toFixed(1) : 0;

  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };

  set('os-total', total);
  set('os-month', thisMonth > 0 ? `↑ ${thisMonth} هذا الشهر` : 'لا طلبات هذا الشهر');
  set('os-pending', pending);
  set('os-approved', approved);
  set('os-approval-rate', total > 0 ? `↑ ${approvalRate}% نسبة القبول` : '—');
  set('os-rejected', rejected);
  set('os-rejection-rate', total > 0 ? `${rejectionRate}% نسبة الرفض` : '—');
}

// ===================== RENDER TABLE =====================
function renderRequests(data) {
  const tbody = document.getElementById('requestsTbody');
  if (!tbody) return;

  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><div class="emoji"></div><div>لا توجد طلبات مطابقة</div></div></td></tr>`;
    return;
  }

  tbody.innerHTML = data.map((r, i) => {
    const s = statusMap[r.status] || { label: r.status, cls: 'badge-pending' };
    const col = avatarColors[i % avatarColors.length];
    const isDb = r._source === 'db';
    const isPending = r.status === 'pending';
    const catIcon = catIconMap[r.cat] || '';

    const actionBtns = isDb ? `
      <button class="action-btn view-btn" title="عرض التفاصيل" onclick='openRequestModal(${JSON.stringify(r)})'>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
      ${isPending ? `
        <button class="action-btn approve-btn" title="قبول الطلب" onclick="doAction('${r._dbId}','approve')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg>
        </button>
        <button class="action-btn reject-btn" title="رفض الطلب" onclick="openActionModal('${r._dbId}','reject','${escQ(r.assoc)}')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <button class="action-btn review-btn" title="طلب تعديل" onclick="openActionModal('${r._dbId}','review','${escQ(r.assoc)}')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      ` : `<span style="font-size:.72rem;color:var(--text-muted)">تمت المعالجة</span>`}
    ` : `<button class="action-btn view-btn" title="عرض التفاصيل" onclick='openRequestModal(${JSON.stringify(r)})'>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>`;

    return `
      <tr>
        <td style="color:var(--text-muted);font-size:.78rem;font-weight:600">${r.id}</td>
        <td>
          <div class="user-cell">
            <div class="avatar" style="background:${col}">${initials(r.name)}</div>
            <div>
              <div class="user-name">${r.name}</div>
              <div class="user-email">${r.email}</div>
            </div>
          </div>
        </td>
        <td><span style="font-size:.8rem">${r.type}</span></td>
        <td>
          <div style="display:flex;align-items:center;gap:6px">
            <span style="font-size:1rem">${catIcon}</span>
            <div>
              <div style="font-weight:600;font-size:.85rem;color:var(--text)">${r.assoc}</div>
              ${r.cat ? `<div style="font-size:.73rem;color:var(--text-muted)">${r.cat}</div>` : ''}
            </div>
          </div>
        </td>
        <td style="font-size:.82rem">${formatDate(r.date)}</td>
        <td><span class="badge ${s.cls}">${s.label}</span></td>
        <td><div class="action-group">${actionBtns}</div></td>
      </tr>`;
  }).join('');
}

// ===================== FILTER =====================
let activeStatus = '', searchVal = '';

function filterTable(val) { searchVal = val.toLowerCase(); applyFilter(); }
function filterByStatus(val) { activeStatus = val; applyFilter(); }
function applyFilter() {
  let data = _allData;
  if (activeStatus) data = data.filter(r => r.status === activeStatus);
  if (searchVal) data = data.filter(r =>
    (r.name || '').toLowerCase().includes(searchVal) ||
    (r.email || '').toLowerCase().includes(searchVal) ||
    (r.assoc || '').toLowerCase().includes(searchVal)
  );
  renderRequests(data);
}

// ===================== ACTION MODAL (reject / review with notes) =====================
let _actionDbId = null, _actionType = null;

function openActionModal(dbId, type, assocName) {
  _actionDbId = dbId; _actionType = type;
  const isReject = type === 'reject';
  const modal = document.getElementById('action-modal'); if (!modal) return;
  const card = modal.querySelector('.modal');

  const iconEl = document.getElementById('action-modal-icon');
  if (iconEl) {
    iconEl.innerHTML = isReject
      ? `<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
           <circle cx="12" cy="12" r="10"></circle>
           <path d="M15 9l-6 6"></path>
           <path d="M9 9l6 6"></path>
         </svg>`
      : `<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
           <path d="M12 20h9"></path>
           <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5z"></path>
         </svg>`;
  }
  document.getElementById('action-modal-title').textContent = isReject ? 'رفض طلب التسجيل' : 'طلب تعديل البيانات';
  document.getElementById('action-modal-sub').textContent = 'جمعية: ' + (assocName || '');
  document.getElementById('action-notes-label').textContent = isReject ? 'سبب الرفض *' : 'التعديلات المطلوبة *';
  const btn = document.getElementById('action-confirm-btn');
  btn.textContent = isReject ? 'تأكيد الرفض' : 'إرسال طلب التعديل';
  btn.className = isReject ? 'btn btn-danger' : 'btn btn-review';
  const ta = document.getElementById('action-notes');
  ta.value = ''; ta.placeholder = isReject ? 'أدخل سبب الرفض بوضوح...' : 'أدخل التعديلات المطلوبة...';

  // Style hook for CSS (identity-aligned reject/review)
  if (card) {
    card.classList.add('action-modal-card');
    card.classList.toggle('is-reject', isReject);
    card.classList.toggle('is-review', !isReject);
  }

  modal.classList.add('open');
  setTimeout(() => ta.focus(), 100);
}

function closeActionModal() {
  document.getElementById('action-modal')?.classList.remove('open');
  _actionDbId = null; _actionType = null;
}

async function confirmAction() {
  if (!_actionDbId || !_actionType) return;
  const notes = document.getElementById('action-notes')?.value.trim() || '';
  if (!notes) {
    showOrdersToast('يرجى إدخال ' + (_actionType === 'reject' ? 'سبب الرفض' : 'ملاحظات التعديل'), 'error');
    document.getElementById('action-notes')?.focus(); return;
  }
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const btn = document.getElementById('action-confirm-btn');
  if (btn) btn.disabled = true;
  try {
    const res = await fetch(`/api/association-requests/${_actionDbId}/${_actionType}`, {
      method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ notes }),
    });
    const data = await res.json();
    if (data.success || res.ok) {
      closeActionModal();
      showOrdersToast(data.message || 'تمت العملية بنجاح', 'success');
      await loadAssociationRequests();
    } else {
      showOrdersToast(data.errors?.notes?.[0] || data.message || 'حدث خطأ', 'error');
    }
  } catch (e) { showOrdersToast('تعذّر الاتصال', 'error'); }
  finally { if (btn) btn.disabled = false; }
}

// ===================== QUICK APPROVE =====================
let _approveDbId = null;

function openApproveModal(dbId, assocName = '') {
  _approveDbId = dbId;
  const sub = document.getElementById('approve-sub');
  if (sub) sub.textContent = assocName ? `هل تريد قبول طلب جمعية: ${assocName}؟` : 'هل تريد قبول هذا الطلب؟';
  document.getElementById('approve-modal')?.classList.add('open');
}

function closeApproveModal() {
  document.getElementById('approve-modal')?.classList.remove('open');
  _approveDbId = null;
}

async function confirmApprove() {
  if (!_approveDbId) return;
  await doAction(_approveDbId, 'approve', { skipConfirm: true });
  closeApproveModal();
}

async function doAction(dbId, action, opts = {}) {
  // action === 'approve' gets a custom confirm modal (no browser confirm)
  if (action === 'approve' && !opts.skipConfirm) {
    const row = dbAssocRequests.find(r => String(r._dbId) === String(dbId));
    openApproveModal(dbId, row?.assoc || '');
    return;
  }
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
  try {
    const res = await fetch(`/api/association-requests/${dbId}/${action}`, {
      method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({}),
    });
    const data = await res.json();
    if (data.success || res.ok) { showOrdersToast(data.message || 'تمت العملية بنجاح', 'success'); await loadAssociationRequests(); }
  } catch (e) { showOrdersToast('تعذّر الاتصال', 'error'); }
}

// ===================== DETAILS MODAL =====================
function openRequestModal(r) {
  const s = statusMap[r.status] || { label: r.status, cls: 'badge-pending' };
  const catIcon = catIconMap[r.cat] || '';
  const isDb = r._source === 'db';
  const isPending = r.status === 'pending';
  const modalBody = document.getElementById('modalBody');
  if (!modalBody) return;

  const modalRoot = document.querySelector('#modal .modal');
  if (modalRoot) modalRoot.classList.add('req-details-modal');

  // Head
  const modalHead = document.querySelector('#modal .modal-head');
  if (modalHead) {
    modalHead.classList.add('req-details-head');
    modalHead.innerHTML = `
      <button class="req-details-close" type="button" onclick="closeModal()" aria-label="إغلاق">✕</button>
      <div class="req-details-head-main">
        <div class="req-details-head-title">تفاصيل طلب التسجيل</div>
        <div class="req-details-head-sub">
          <span class="req-details-pill">رقم الطلب: <b>${r.id}</b></span>
          <span class="req-details-pill">الحالة: <span class="badge ${s.cls}">${s.label}</span></span>
        </div>
      </div>`;
  }

  function makeField(label, value, opts = {}) {
    const dir = opts.dir ? `dir="${opts.dir}"` : '';
    return `
      <div class="req-details-field">
        <div class="req-details-label">${label}</div>
        <div class="req-details-value" ${dir}>${value || '—'}</div>
      </div>`;
  }

  modalBody.classList.add('req-details-body');

  const svg = {
    building: `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M3 21h18"></path>
      <path d="M7 21V7a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14"></path>
      <path d="M10 9h4"></path>
      <path d="M10 13h4"></path>
      <path d="M10 17h4"></path>
    </svg>`,
    check: `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M20 6L9 17l-5-5"></path>
    </svg>`,
    x: `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M18 6L6 18"></path>
      <path d="M6 6l12 12"></path>
    </svg>`,
    pencil: `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M12 20h9"></path>
      <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5z"></path>
    </svg>`,
  };

  modalBody.innerHTML = `
    <div class="req-details-hero">
      <div class="req-details-hero-title">${r.assoc || '—'} <span class="req-details-hero-ic" aria-hidden="true">${svg.building}</span></div>
      ${r.cat ? `<div class="req-details-hero-sub">التصنيف: <b>${r.cat}</b></div>` : ''}
    </div>

    <div class="req-details-grid">
      ${makeField('اسم المسؤول', r.name)}
      ${makeField('البريد الإلكتروني', r.email, { dir: 'ltr' })}
      ${makeField('رقم الجوال', r.phone, { dir: 'ltr' })}
      ${makeField('رقم الترخيص', r.license, { dir: 'ltr' })}
      ${makeField('تاريخ الطلب', formatDate(r.date))}
      ${makeField('نوع الحساب', r.type)}
    </div>

    ${r.notes ? `
      <div class="req-details-notes">
        <div class="req-details-notes-ttl">ملاحظات الإدارة</div>
        <div class="req-details-notes-txt">${r.notes}</div>
      </div>` : ''}`;

  // Footer
  const footer = document.querySelector('#modal .modal-footer');
  if (footer) {
    footer.classList.add('req-details-footer');
    if (isDb && isPending) {
      footer.innerHTML = `
        <button class="btn btn-danger" type="button" onclick="closeModal();openActionModal('${r._dbId}','reject','${escQ(r.assoc)}')">${svg.x} رفض</button>
        <button class="btn btn-review" type="button" onclick="closeModal();openActionModal('${r._dbId}','review','${escQ(r.assoc)}')">${svg.pencil} طلب تعديل</button>
        <button class="btn btn-ghost" type="button" onclick="closeModal()">إلغاء</button>
        <button class="btn btn-success" type="button" onclick="closeModal();doAction('${r._dbId}','approve')">${svg.check} قبول</button>`;
    } else {
      footer.innerHTML = `<button class="btn btn-primary" type="button" onclick="closeModal()">إغلاق</button>`;
    }
  }

  document.getElementById('modal').classList.add('open');
}




function openModal(r) { openRequestModal(r); }
function closeModal() { document.getElementById('modal')?.classList.remove('open'); }

// ===================== TOAST =====================
function showOrdersToast(msg, type) {
  let t = document.getElementById('orders-toast');
  if (!t) {
    t = document.createElement('div'); t.id = 'orders-toast';
    t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:9999;padding:12px 22px;border-radius:12px;font-size:.88rem;font-weight:700;font-family:Tajawal,sans-serif;box-shadow:0 8px 28px rgba(0,0,0,.15);transition:opacity .3s;min-width:240px;text-align:center;';
    document.body.appendChild(t);
  }
  t.style.background = type === 'success' ? '#0d9488' : type === 'error' ? '#dc2626' : '#1d6fa4';
  t.style.color = '#fff'; t.textContent = msg; t.style.opacity = '1';
  clearTimeout(t._t); t._t = setTimeout(() => { t.style.opacity = '0'; }, 3500);
}

// ===================== BADGES =====================
function updateSidebarBadgesWithDb() {
  const n = dbAssocRequests.filter(r => r.status === 'pending').length;
  const nb = document.getElementById('nb-reqs');
  if (nb) nb.textContent = n > 0 ? n : '';
}
function updateSidebarBadges() {
  // nb-opps is managed by consulting.js based on real opportunity data
}

// ===================== CATEGORIES =====================
function renderCategories() {
  const grid = document.getElementById('categoriesGrid'); if (!grid) return;

  const allCard = `
  <div class="cat-card-new cat-card-all" onclick="filterAssocByCat('')">
    <div class="cat-card-new-header">
      <div class="cat-card-new-icon" style="background:rgba(255,255,255,.2)">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="white" stroke-width="2.2">
          <path d="M12 2a10 10 0 100 20 10 10 0 000-20z"/>
          <path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 010 20"/><path d="M12 2a15.3 15.3 0 000 20"/>
        </svg>
      </div>
      <div class="cat-card-new-info">
        <div class="cat-card-new-name" style="color:#fff">جميع التصنيفات</div>
        <div class="cat-card-new-count" style="color:rgba(255,255,255,.75)">${categories.reduce((s, c) => s + (c.count || 0), 0)} جمعية</div>
      </div>
    </div>
  </div>`;

  const catsHtml = categories.map(c => `
  <div class="cat-card-new" style="--cc:${c.color}" onclick="filterAssocByCat('${c.name}')">
    <div class="cat-card-new-accent"></div>
    <div class="cat-card-new-header">
      <div class="cat-card-new-icon" style="background:${c.color}22;font-size:1.3rem">${c.icon || '<i class="fa-solid fa-tag"></i>'}</div>
      <div class="cat-card-new-info">
        <div class="cat-card-new-name">${c.name}</div>
        <div class="cat-card-new-count">${c.count || 0} جمعية مسجلة</div>
      </div>
      <div class="cat-card-new-actions" onclick="event.stopPropagation()">
        <button class="cat-action-btn cat-edit-btn" title="تعديل" onclick="openEditCategoryModal(${c.id})">
          <i class="fa-solid fa-pen-to-square"></i>
        </button>
        <button class="cat-action-btn cat-delete-btn" title="حذف" onclick="deleteCategory(${c.id})">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
    </div>
  </div>`).join('');

  grid.innerHTML = allCard + catsHtml;
  syncCategoryFilterOptions();
}

function syncCategoryFilterOptions() {
  const catFilter = document.getElementById('catFilter');
  if (!catFilter) return;
  const firstOption = catFilter.options[0]?.outerHTML || '<option value="">تصفية حسب...</option>';
  catFilter.innerHTML = firstOption + categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
}

function openAddCategoryModal() {
  const modal = document.getElementById('add-assoc-cat-modal');
  if (!modal) return;
  selectedCategoryEmoji = '';

  const nameInput = document.getElementById('assoc-cat-name-input');
  if (nameInput) nameInput.value = '';

  const colorInput = document.getElementById('assoc-cat-color-input');
  const colorLabel = document.getElementById('assoc-cat-color-label');
  if (colorInput) colorInput.value = '#2ab8d0';
  if (colorLabel) colorLabel.textContent = '#2AB8D0';

  renderEmojiPicker();
  modal.classList.add('open');
}

function closeAddCategoryModal() {
  document.getElementById('add-assoc-cat-modal')?.classList.remove('open');
}

async function saveNewCategory() {
  const nameInput = document.getElementById('assoc-cat-name-input');
  const colorInput = document.getElementById('assoc-cat-color-input');
  const iconInput = document.getElementById('assoc-cat-icon-add-input');
  const name = nameInput?.value.trim();
  const color = colorInput?.value || '#2ab8d0';
  const icon = iconInput?.value.trim() || '';

  if (!name) {
    showOrdersToast('يرجى إدخال اسم التصنيف', 'error');
    nameInput?.focus();
    return;
  }

  const saveBtn = document.querySelector('#add-assoc-cat-modal .btn.btn-primary');
  if (saveBtn) saveBtn.disabled = true;

  try {
    const res = await fetch('/api/association-categories', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
      body: JSON.stringify({ name, icon, color }),
    });
    const data = await res.json();
    if (!res.ok || data.success === false) {
      showOrdersToast(data?.errors?.name?.[0] || data?.message || 'تعذّر حفظ التصنيف', 'error');
      return;
    }
    await fetchOrderCategories();
    renderCategories();
    closeAddCategoryModal();
    showOrdersToast('تمت إضافة التصنيف بنجاح', 'success');
  } catch (e) {
    showOrdersToast('تعذّر الاتصال بالخادم', 'error');
  } finally {
    if (saveBtn) saveBtn.disabled = false;
  }
}

function renderEmojiPicker() {
  const addGrid = document.getElementById('assoc-cat-emoji-grid');
  const editGrid = document.getElementById('edit-assoc-cat-emoji-grid');

  const getEmojiHtml = (isEdit) => categoryEmojiChoices.map(emoji => `
    <button type="button" style="cursor:pointer;font-size:1.3rem;padding:6px;border-radius:8px;border:none;background:${emoji === selectedCategoryEmoji ? 'rgba(42,184,208,0.2)' : 'transparent'};transition:all .2s" onclick="pickCategoryEmoji('${emoji}', ${isEdit})">
      ${emoji}
    </button>
  `).join('');

  if (addGrid) addGrid.innerHTML = getEmojiHtml(false);
  if (editGrid) editGrid.innerHTML = getEmojiHtml(true);
}

function pickCategoryEmoji(emoji, isEdit) {
  selectedCategoryEmoji = emoji;
  const inputId = isEdit ? 'edit-assoc-cat-icon-input' : 'assoc-cat-icon-add-input';
  const input = document.getElementById(inputId);
  if (input) input.value = emoji;
  renderEmojiPicker();
}

// ─── EDIT CATEGORY MODAL ───
let _editCatId = null;
function openEditCategoryModal(id) {
  const cat = categories.find(c => c.id === id || c.id == id);
  if (!cat) { showOrdersToast('تعذّر تحديد التصنيف', 'error'); return; }

  _editCatId = id;
  const modal = document.getElementById('edit-assoc-cat-modal');
  if (!modal) return;

  document.getElementById('edit-assoc-cat-name-input').value = cat.name || '';
  const colorInput = document.getElementById('edit-assoc-cat-color-input');
  const colorLabel = document.getElementById('edit-assoc-cat-color-label');
  if (colorInput) colorInput.value = cat.color || '#2ab8d0';
  if (colorLabel) colorLabel.textContent = (cat.color || '#2ab8d0').toUpperCase();
  document.getElementById('edit-assoc-cat-icon-input').value = cat.icon || '';
  selectedCategoryEmoji = cat.icon || '';
  renderEmojiPicker();

  modal.classList.add('open');
}
function closeEditCategoryModal() {
  document.getElementById('edit-assoc-cat-modal')?.classList.remove('open');
  _editCatId = null;
}
async function saveEditCategory() {
  if (!_editCatId) return;

  const nameInput = document.getElementById('edit-assoc-cat-name-input');
  const colorInput = document.getElementById('edit-assoc-cat-color-input');
  const iconInput = document.getElementById('edit-assoc-cat-icon-input');

  const name = nameInput?.value.trim();
  const color = colorInput?.value || '#2ab8d0';
  const icon = iconInput?.value.trim() || '';

  if (!name) {
    showOrdersToast('يرجى إدخال اسم التصنيف', 'error');
    nameInput?.focus();
    return;
  }

  const saveBtn = document.getElementById('edit-assoc-cat-save-btn');
  if (saveBtn) saveBtn.disabled = true;

  try {
    const res = await fetch(`/api/association-categories/${_editCatId}`, {
      method: 'PUT',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
      body: JSON.stringify({ name, color, icon }),
    });
    const data = await res.json();
    if (!res.ok || data.success === false) { showOrdersToast(data.message || 'تعذّر الحفظ', 'error'); return; }
    await fetchOrderCategories();
    renderCategories();
    closeEditCategoryModal();
    showOrdersToast('تم تحديث التصنيف بنجاح', 'success');
  } catch (e) { showOrdersToast('تعذّر الاتصال', 'error'); }
  finally { if (saveBtn) saveBtn.disabled = false; }
}

// ── Delete Category Modal ──
let _deleteCatId = null;

function deleteCategory(id) {
  const cat = categories.find(c => c.id === id || c.id == id);
  if (!cat) return;
  _deleteCatId = id;

  // Fill modal content
  const badge = document.getElementById('delete-cat-badge');
  if (badge) badge.innerHTML = `${cat.icon ? `<span style="margin-left:8px">${cat.icon}</span>` : ''} ${cat.name}`;

  const msg = document.getElementById('delete-cat-msg');
  if (msg) {
    const count = cat.count || cat.total_count || 0;
    msg.textContent = count > 0
      ? `هذا التصنيف مرتبط بـ ${count} جمعية. لا يمكن التراجع عن هذا الإجراء.`
      : 'هل أنت متأكد من حذف هذا التصنيف؟ لا يمكن التراجع عن هذا الإجراء.';
  }

  const modal = document.getElementById('delete-cat-modal');
  if (modal) modal.classList.add('open');
}

function closeDeleteCatModal() {
  _deleteCatId = null;
  document.getElementById('delete-cat-modal')?.classList.remove('open');
}

async function confirmDeleteCategory() {
  if (!_deleteCatId) return;
  const btn = document.getElementById('confirm-delete-cat-btn');
  if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="margin-left:6px"></i>جاري الحذف...'; }

  try {
    const res = await fetch(`/api/association-categories/${_deleteCatId}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
    });
    const data = await res.json();
    if (!res.ok || data.success === false) {
      showOrdersToast(data.message || 'لا يمكن الحذف', 'error');
    } else {
      closeDeleteCatModal();
      await fetchOrderCategories();
      renderCategories();
      showOrdersToast('تم حذف التصنيف بنجاح', 'success');
    }
  } catch (e) {
    showOrdersToast('تعذّر الاتصال بالخادم', 'error');
  } finally {
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-trash" style="margin-left:6px"></i>تأكيد الحذف'; }
  }
}

// Close on backdrop click
document.addEventListener('DOMContentLoaded', () => {
  const dm = document.getElementById('delete-cat-modal');
  if (dm) dm.addEventListener('click', e => { if (e.target === dm) closeDeleteCatModal(); });
});



// ===================== ASSOCIATIONS =====================
let _currentAssocCat = '';

function renderAssoc(data) {
  const list = document.getElementById('assocList'); if (!list) return;
  if (!data.length) {
    list.innerHTML = `<div class="empty-state"><div class="emoji">🏢</div><div>لا توجد جمعيات في هذا التصنيف</div></div>`;
    return;
  }
  list.innerHTML = data.map(a => {
    const cat = categories.find(c => c.name === (a.category || a.cat)) || {};
    const bg = cat.color ? cat.color + '22' : '#2ab8d022';
    const icon = cat.icon || '🏢';
    const statusLabel = a.status === 'approved' ? 'موافق عليها' : a.status === 'pending' ? 'قيد المراجعة' : a.status === 'rejected' ? 'مرفوضة' : 'تحت المراجعة';
    const statusCls = a.status === 'approved' ? 'badge-approved' : a.status === 'pending' ? 'badge-pending' : 'badge-rejected';
    const assocName = a.association_name || a.name || '—';
    return `
    <div class="assoc-item-new">
      <div class="assoc-item-avatar" style="background:${bg};color:${cat.color || '#2ab8d0'};font-size:1.3rem">${icon}</div>
      <div class="assoc-item-info">
        <div class="assoc-item-name">${assocName}</div>
        <div class="assoc-item-cat">${a.category || a.cat || '—'}</div>
        <div class="assoc-item-email">${a.email || '—'}</div>
      </div>
      <div class="assoc-item-meta">
        <span class="badge ${statusCls}">${statusLabel}</span>
        <span class="assoc-item-date">${formatDate(a.created_at || a.date || new Date())}</span>
      </div>
    </div>`;
  }).join('');
}

async function filterAssocByCat(cat) {
  _currentAssocCat = cat;

  // لا تغيير التاب — البقاء داخل نفس تاب الجمعيات
  const cf = document.getElementById('catFilter');
  if (cf) cf.value = cat;

  // تحديث رأس القسم
  const secHead = document.getElementById('assoc-section-head');
  if (secHead) {
    const catObj = categories.find(c => c.name === cat);
    secHead.innerHTML = cat
      ? `<div style="display:flex;align-items:center;gap:10px">
           <span style="font-size:1.5rem">${catObj?.icon || '🏢'}</span>
           <div>
             <div style="font-weight:800;font-size:1.05rem;color:var(--text)">${cat}</div>
             <div style="font-size:.78rem;color:#94a3b8">الجمعيات في هذا التصنيف</div>
           </div>
         </div>`
      : `<div style="font-weight:800;font-size:1.05rem;color:var(--text)"><i class="fa-solid fa-building" style="color:var(--teal);margin-left:8px"></i>جميع الجمعيات المسجلة</div>`;
  }

  const list = document.getElementById('assocList');
  if (list) list.innerHTML = `<div class="sr-loading" style="padding:32px;text-align:center;color:#94a3b8">جاري التحميل...</div>`;

  try {
    const url = '/api/associations' + (cat ? `?category=${encodeURIComponent(cat)}` : '');
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error();
    const data = await res.json();
    renderAssoc(data.associations || []);
  } catch (e) {
    if (list) list.innerHTML = `<div style="padding:32px;text-align:center;color:#ef4444">⚠️ تعذّر تحميل الجمعيات</div>`;
  }
}

function filterAssoc() {
  const v = document.getElementById('catFilter')?.value || '';
  filterAssocByCat(v);
}

// ===================== TABS =====================
// Lightweight tab activation — switches visible tab WITHOUT re-loading data
function _activateTab(id) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id)?.classList.add('active');
  // Also activate the matching button
  document.querySelectorAll('.tab-btn').forEach(b => {
    const onclick = b.getAttribute('onclick') || '';
    if (onclick.includes(`'${id}'`) || onclick.includes(`"${id}"`)) b.classList.add('active');
  });
  // Swap stats grids
  const grids = { 'requests': 'stats-grid-requests', 'services': 'stats-grid-services', 'my-associations': 'stats-grid-associations', 'opp-requests': 'stats-grid-opp-requests', 'proj-requests': 'stats-grid-proj-requests' };
  Object.values(grids).forEach(gid => { const el = document.getElementById(gid); if (el) el.style.display = 'none'; });
  if (grids[id]) { const el = document.getElementById(grids[id]); if (el) el.style.display = ''; }
  // Update page title
  const titles = { 'requests': ['صفحة الطلبات', 'إدارة طلبات إنشاء الحسابات'], 'services': ['صفحة الخدمات', 'إدارة ومتابعة طلبات خدمات الجمعيات'], 'my-associations': ['الجمعيات', 'إدارة كافة الجمعيات المضافة في النظام'], 'opp-requests': ['طلبات فرص التطوع', 'مراجعة وإدارة طلبات التطوع المقدمة'], 'proj-requests': ['طلبات المشاريع', 'مراجعة وإدارة طلبات الانضمام للمشاريع'] };
  const t = titles[id];
  if (t) { const te = document.getElementById('orders-page-title-text'); const se = document.getElementById('orders-page-sub'); if (te) te.textContent = t[0]; if (se) se.textContent = t[1]; }
}

function switchTab(id, btn) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id)?.classList.add('active');
  if (btn) btn.classList.add('active');

  // ── Swap page title, subtitle, and stats grid based on active tab ──
  const titleEl = document.getElementById('orders-page-title-text');
  const subEl = document.getElementById('orders-page-sub');
  const gridReq = document.getElementById('stats-grid-requests');
  const gridSvc = document.getElementById('stats-grid-services');
  const gridAssoc = document.getElementById('stats-grid-associations');
  const gridOpp = document.getElementById('stats-grid-opp-requests');
  const gridProj = document.getElementById('stats-grid-proj-requests');

  // Hide all grids first
  if (gridReq) gridReq.style.display = 'none';
  if (gridSvc) gridSvc.style.display = 'none';
  if (gridAssoc) gridAssoc.style.display = 'none';
  if (gridOpp) gridOpp.style.display = 'none';
  if (gridProj) gridProj.style.display = 'none';

  if (id === 'services') {
    if (titleEl) titleEl.textContent = 'صفحة الخدمات';
    if (subEl) subEl.textContent = 'إدارة ومتابعة طلبات خدمات الجمعيات';
    if (gridSvc) gridSvc.style.display = '';
    loadServiceRequests();
  } else if (id === 'requests') {
    if (titleEl) titleEl.textContent = 'صفحة الطلبات';
    if (subEl) subEl.textContent = 'إدارة طلبات إنشاء الحسابات';
    if (gridReq) gridReq.style.display = '';
  } else if (id === 'my-associations') {
    if (titleEl) titleEl.textContent = 'الجمعيات';
    if (subEl) subEl.textContent = 'إدارة كافة الجمعيات المضافة في النظام';
    if (gridAssoc) gridAssoc.style.display = '';
    loadMyAssociations();
  } else if (id === 'associations') {
    if (titleEl) titleEl.textContent = 'تصنيفات الجمعيات';
    if (subEl) subEl.textContent = 'إدارة التصنيفات الخاصة بالجمعيات';
  } else if (id === 'opp-requests') {
    if (titleEl) titleEl.textContent = 'طلبات فرص التطوع';
    if (subEl) subEl.textContent = 'مراجعة وإدارة طلبات التطوع المقدمة';
    if (gridOpp) gridOpp.style.display = '';
    loadOppRequests();
  } else if (id === 'proj-requests') {
    if (titleEl) titleEl.textContent = 'طلبات المشاريع';
    if (subEl) subEl.textContent = 'مراجعة وإدارة طلبات الانضمام للمشاريع';
    if (gridProj) gridProj.style.display = '';
    loadProjRequests();
  }
}

// ===================== MY ASSOCIATIONS =====================
let allMyAssociations = [];

async function loadMyAssociations() {
  try {
    const res = await fetch('/api/associations?status=approved', {
      headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) return;
    const data = await res.json();
    allMyAssociations = data.associations || [];
    filterMyAssociations();
    updateMyAssociationsStats(allMyAssociations);
  } catch (e) {
    console.error('Error fetching associations', e);
  }
}

function updateMyAssociationsStats(data) {
  const elTotal = document.getElementById('assoc-stat-total');
  const elCats = document.getElementById('assoc-stat-cats');
  const elAvg = document.getElementById('assoc-stat-avg');

  if (elTotal) elTotal.textContent = data.length;

  if (elCats) {
    const cats = new Set(data.map(a => a.category).filter(Boolean));
    elCats.textContent = cats.size || 0;
  }

  if (elAvg) {
    // just a fake average based on length for demonstration
    elAvg.textContent = Math.max(1, Math.floor(data.length / 3));
  }
}

function filterMyAssociations() {
  const search = (document.getElementById('myAssocSearch')?.value || '').toLowerCase();
  const cat = document.getElementById('myAssocCatFilter')?.value || '';

  const filtered = allMyAssociations.filter(a => {
    if (cat && a.category !== cat) return false;
    if (search) {
      return (a.association_name || '').toLowerCase().includes(search) ||
        (a.email || '').toLowerCase().includes(search) ||
        (a.phone || '').toLowerCase().includes(search) ||
        (a.category || '').toLowerCase().includes(search);
    }
    return true;
  });

  renderMyAssociations(filtered);
}

function renderMyAssociations(data) {
  const tbody = document.getElementById('myAssocTbody');
  if (!tbody) return;
  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><div class="emoji"></div><div>لا توجد جمعيات تطابق بحثك</div></div></td></tr>`;
    return;
  }

  tbody.innerHTML = data.map((assoc, i) => {
    const col = avatarColors[i % avatarColors.length];
    const cat = assoc.category || 'عام';
    const catIcon = catIconMap[cat] || '';
    const name = assoc.manager_name || 'مدير الجمعية';
    const email = assoc.email || '—';
    const phone = assoc.phone || '—';
    const date = formatDate(assoc.created_at || new Date());
    const license = assoc.license_number || '—';

    // Using a nice active status badge since these are "approved"
    const statusHtml = `<span class="badge badge-approved" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16,185,129,0.2);">
      <span style="display:inline-block;width:6px;height:6px;background:#059669;border-radius:50%;margin-left:4px;"></span>نشطة
    </span>`;

    const actionBtns = `
      <button class="action-btn view-btn" title="عرض التفاصيل" onclick="alert('عرض تفاصيل: ${assoc.association_name}')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
      <button class="action-btn reject-btn" title="حذف" onclick="alert('حذف الجمعية: ${assoc.association_name}')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
      </button>
    `;

    return `
      <tr>
        <td style="color:var(--text-muted);font-size:.78rem;font-weight:600" dir="ltr">${license}</td>
        <td>
          <div class="user-cell">
            <div class="avatar" style="background:${col}">${initials(name)}</div>
            <div>
              <div class="user-name">${name}</div>
              <div class="user-email">${assoc.manager_email || email}</div>
            </div>
          </div>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:6px">
            <span style="font-size:1rem">${catIcon}</span>
            <div>
              <div style="font-weight:600;font-size:.85rem;color:var(--text)">${assoc.association_name}</div>
              <div style="font-size:.73rem;color:var(--text-muted)">${cat}</div>
            </div>
          </div>
        </td>
        <td>
          <div style="display:flex;flex-direction:column;gap:2px;">
            ${email !== '—' ? `<div style="font-size:0.75rem;color:var(--text);display:flex;align-items:center;gap:4px;"><i class="fa-regular fa-envelope" style="color:var(--text-muted);"></i> <a href="mailto:${email}" style="color:var(--text);text-decoration:none;" dir="ltr">${email}</a></div>` : ''}
            ${phone !== '—' ? `<div style="font-size:0.75rem;color:var(--text);display:flex;align-items:center;gap:4px;"><i class="fa-solid fa-phone" style="color:var(--text-muted);"></i> <a href="tel:${phone}" style="color:var(--text);text-decoration:none;" dir="ltr">${phone}</a></div>` : ''}
          </div>
        </td>
        <td style="font-size:.82rem">${date}</td>
        <td>${statusHtml}</td>
        <td><div class="action-group">${actionBtns}</div></td>
      </tr>`;
  }).join('');
}

// ===================== SIDEBAR =====================
function toggleServices() {
  const p = document.getElementById('np-services'), s = document.getElementById('submenu-services');
  if (!s) return; const o = s.classList.contains('open');
  s.classList.toggle('open', !o); if (p) p.classList.toggle('open', !o);
}
function showAdminRequests(el) { switchTab('requests', document.querySelectorAll('.tab-btn')[0]); }
function showToast(i, m) { } function toggleNotifs() { if (typeof window._realToggleNotifs === 'function') window._realToggleNotifs(); } function openMeetingsPage() { } function backToVolunteer() { }

// ===================== VOLUNTEER OPPORTUNITY REQUESTS =====================
let allOppReqs = [], oppReqFilter = 'all';

async function loadOppRequests() {
  const tbody = document.getElementById('opp-req-tbody');
  if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="sr-loading">جاري التحميل...</td></tr>`;
  try {
    const res = await fetch('/api/opportunity-requests', {
      credentials: 'same-origin', headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    allOppReqs = data.requests || [];
    const pending = allOppReqs.filter(r => r.status === 'pending').length;
    const cnt = document.getElementById('opp-req-count');
    if (cnt) cnt.textContent = pending > 0 ? pending : '';
    updateOppStats();
    renderOppReqs();

    // Auto-switch to this tab if needed
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    const reqIdParam = urlParams.get('req_id');
    if (typeParam === 'opportunity' || typeParam === 'volunteer_request') {
      setTimeout(() => {
        _activateTab('opp-requests');
        if (reqIdParam) {
          const row = document.querySelector(`#opp-req-tbody tr[data-id='${reqIdParam}']`);
          if (row) row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        window.history.replaceState({}, document.title, window.location.pathname + window.location.hash);
      }, 500);
    }
  } catch (e) { if (tbody) tbody.innerHTML = `<tr><td colspan="6">تعذّر التحميل</td></tr>`; }
}

function updateOppStats() {
  const data = allOppReqs;
  const total = data.length;
  const pending = data.filter(r => r.status === 'pending').length;
  const approved = data.filter(r => r.status === 'approved').length;
  const rejected = data.filter(r => r.status === 'rejected').length;
  const now = new Date();
  const thisMonth = data.filter(r => {
    const d = new Date(r.created_at);
    return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
  }).length;
  const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
  set('opp-stat-total', total);
  set('opp-stat-pending', pending);
  set('opp-stat-approved', approved);
  set('opp-stat-rejected', rejected);
  set('opp-stat-month', thisMonth > 0 ? `↑ ${thisMonth} هذا الشهر` : 'لا طلبات هذا الشهر');
  set('opp-stat-rate', total > 0 ? `↑ ${((approved / total) * 100).toFixed(1)}% نسبة القبول` : '—');
  set('opp-stat-rej-rate', total > 0 ? `${((rejected / total) * 100).toFixed(1)}% نسبة الرفض` : '—');
}

function filterOppReqs(status) {
  if (status !== undefined) {
    oppReqFilter = status;
    document.querySelectorAll('#opp-req-filter-tabs .sr-tab-btn').forEach(b => {
      const active = b.getAttribute('onclick')?.includes(`'${status}'`);
      b.style.background = active ? '#d97706' : '#f1f5f9';
      b.style.color = active ? '#fff' : '#64748b';
    });
  }
  renderOppReqs();
}

function renderOppReqs() {
  const tbody = document.getElementById('opp-req-tbody');
  if (!tbody) return;
  const q = (document.getElementById('opp-req-search')?.value || '').toLowerCase();
  let list = allOppReqs;
  if (oppReqFilter && oppReqFilter !== 'all') list = list.filter(r => r.status === oppReqFilter);
  if (q) list = list.filter(r =>
    (r.opportunity?.title || '').toLowerCase().includes(q) ||
    (r.user?.full_name || r.association?.association_name || '').toLowerCase().includes(q)
  );
  if (!list.length) {
    tbody.innerHTML = `<tr><td colspan="6"><div class="empty-state"><div>لا توجد طلبات في هذه الفئة</div></div></td></tr>`;
    return;
  }
  const statusLabels = { pending: { l: 'جديد', cls: 'badge-pending' }, processing: { l: 'قيد المعالجة', cls: 'badge-processing' }, approved: { l: 'مقبول', cls: 'badge-approved' }, rejected: { l: 'مرفوض', cls: 'badge-rejected' } };
  tbody.innerHTML = list.map((r, i) => {
    const col = avatarColors[i % avatarColors.length];
    const userName = r.user?.full_name || r.association?.association_name || '—';
    const oppTitle = r.opportunity?.title || '—';
    const oppCat = r.opportunity?.type || '—';
    const date = r.created_at ? formatDate(r.created_at) : '—';
    const s = statusLabels[r.status] || { l: r.status, cls: 'badge-pending' };
    const actions = `
      <button class="action-btn view-btn" title="عرض التفاصيل" onclick="openOppReqModal(${r.id})">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </button>`;
    return `<tr>
      <td><div class="user-cell"><div class="avatar" style="background:${col}">${initials(userName)}</div><div class="user-name">${userName}</div></div></td>
      <td style="font-weight:600;font-size:.87rem">${oppTitle}</td>
      <td style="font-size:.8rem;color:#64748b">${oppCat}</td>
      <td style="font-size:.82rem">${date}</td>
      <td><span class="badge ${s.cls}">${s.l}</span></td>
      <td><div class="action-group">${actions}</div></td>
    </tr>`;
  }).join('');
}

let selectedOppReqId = null;

function openOppReqModal(id) {
  const r = allOppReqs.find(req => req.id === id);
  if (!r) return;
  selectedOppReqId = id;

  const applicant = r.user || r.association || {};
  const isAssoc = !!r.association;
  const name = isAssoc ? r.association.association_name : (r.user?.full_name || '—');
  const email = applicant.email || '—';
  const phone = applicant.phone || '—';

  const statusLabels = { pending: { l: 'جديد', cls: 'badge-pending' }, processing: { l: 'قيد المعالجة', cls: 'badge-processing' }, approved: { l: 'مقبول', cls: 'badge-approved' }, rejected: { l: 'مرفوض', cls: 'badge-rejected' } };
  const s = statusLabels[r.status] || { l: r.status, cls: 'badge-pending' };

  document.getElementById('opp-req-modal-av').textContent = initials(name);
  document.getElementById('opp-req-modal-name').textContent = name + (isAssoc ? ' (جمعية)' : ' (متطوع)');
  document.getElementById('opp-req-modal-email').textContent = email;
  document.getElementById('opp-req-modal-phone').textContent = phone;
  document.getElementById('opp-req-modal-title').textContent = r.opportunity?.title || '—';
  document.getElementById('opp-req-modal-type').textContent = r.opportunity?.type || '—';
  document.getElementById('opp-req-modal-notes').textContent = r.notes || 'لا توجد ملاحظات';
  document.getElementById('opp-req-modal-created').textContent = r.created_at ? formatDate(r.created_at) : '—';

  const statusEl = document.getElementById('opp-req-modal-status');
  statusEl.textContent = s.l;
  statusEl.className = 'badge ' + s.cls;

  const actionSection = document.getElementById('opp-req-action-section');
  if (actionSection) {
    if (r.status === 'pending') {
      actionSection.style.display = 'block';
      const procBtn = document.querySelector('#opp-req-action-section .btn-pill-process');
      if (procBtn) procBtn.style.display = 'flex';
      document.getElementById('opp-req-reject-reason-wrap').style.display = 'none';
      document.getElementById('opp-req-reject-confirm-btn-wrap').style.display = 'none';
      document.getElementById('opp-req-reject-reason').value = '';
    } else if (r.status === 'processing') {
      actionSection.style.display = 'block';
      const procBtn = document.querySelector('#opp-req-action-section .btn-pill-process');
      if (procBtn) procBtn.style.display = 'none';
      document.getElementById('opp-req-reject-reason-wrap').style.display = 'none';
      document.getElementById('opp-req-reject-confirm-btn-wrap').style.display = 'none';
      document.getElementById('opp-req-reject-reason').value = '';
    } else {
      actionSection.style.display = 'none';
    }
  }

  document.getElementById('opp-req-modal').classList.add('open');
}

function closeOppReqModal() {
  document.getElementById('opp-req-modal')?.classList.remove('open');
  selectedOppReqId = null;
}

function toggleOppReqRejectInput() {
  const wrap = document.getElementById('opp-req-reject-reason-wrap');
  const confirmBtnWrap = document.getElementById('opp-req-reject-confirm-btn-wrap');
  if (wrap.style.display === 'none') {
    wrap.style.display = 'block';
    confirmBtnWrap.style.display = 'block';
    document.getElementById('opp-req-reject-reason').focus();
  } else {
    wrap.style.display = 'none';
    confirmBtnWrap.style.display = 'none';
  }
}

async function submitOppReqStatus(action) {
  if (!selectedOppReqId) return;

  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const body = {};

  if (action === 'reject') {
    const reason = document.getElementById('opp-req-reject-reason').value.trim();
    if (!reason || reason.length < 5) {
      showOrdersToast('يرجى إدخال سبب الرفض (5 أحرف على الأقل)', 'error');
      return;
    }
    body.notes = reason;
  }

  let btnClass = '.btn-pill-approve';
  if (action === 'process') btnClass = '.btn-pill-process';
  if (action === 'reject') btnClass = '.btn-danger';

  const btn = document.querySelector(`#opp-req-modal ${btnClass}`);
  if (btn) btn.disabled = true;

  try {
    const res = await fetch(`/api/opportunity-requests/${selectedOppReqId}/${action}`, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify(body),
    });
    const data = await res.json();
    if (res.ok && (data.success || data.success !== false)) {
      closeOppReqModal();
      showOrdersToast(data.message || 'تم تحديث حالة الطلب بنجاح', 'success');
      await loadOppRequests();
    } else {
      showOrdersToast(data.message || 'حدث خطأ أثناء حفظ الحالة', 'error');
    }
  } catch (e) {
    showOrdersToast('تعذّر الاتصال بالخادم', 'error');
  } finally {
    if (btn) btn.disabled = false;
  }
}

// ===================== PROJECT JOIN REQUESTS =====================
let allProjReqs = [], projReqFilter = 'all';

async function loadProjRequests() {
  const tbody = document.getElementById('proj-req-tbody');
  if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="sr-loading">جاري التحميل...</td></tr>`;
  try {
    const res = await fetch('/api/project-join-requests', {
      credentials: 'same-origin', headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    allProjReqs = data.requests || [];
    const pending = allProjReqs.filter(r => r.status === 'pending').length;
    const cnt = document.getElementById('proj-req-count');
    if (cnt) cnt.textContent = pending > 0 ? pending : '';
    updateProjStats();
    renderProjReqs();

    // Auto-switch to this tab if needed
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    const reqIdParam = urlParams.get('req_id');
    if (typeParam === 'project_join') {
      setTimeout(() => {
        _activateTab('proj-requests');
        if (reqIdParam) {
          const row = document.querySelector(`#proj-req-tbody tr[data-id='${reqIdParam}']`);
          if (row) row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        window.history.replaceState({}, document.title, window.location.pathname + window.location.hash);
      }, 500);
    }
  } catch (e) { if (tbody) tbody.innerHTML = `<tr><td colspan="5">تعذّر التحميل</td></tr>`; }
}

function updateProjStats() {
  const data = allProjReqs;
  const total = data.length;
  const pending = data.filter(r => r.status === 'pending').length;
  const approved = data.filter(r => r.status === 'approved').length;
  const rejected = data.filter(r => r.status === 'rejected').length;
  const now = new Date();
  const thisMonth = data.filter(r => {
    const d = new Date(r.created_at);
    return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
  }).length;
  const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
  set('proj-stat-total', total);
  set('proj-stat-pending', pending);
  set('proj-stat-approved', approved);
  set('proj-stat-rejected', rejected);
  set('proj-stat-month', thisMonth > 0 ? `↑ ${thisMonth} هذا الشهر` : 'لا طلبات هذا الشهر');
  set('proj-stat-rate', total > 0 ? `↑ ${((approved / total) * 100).toFixed(1)}% نسبة القبول` : '—');
  set('proj-stat-rej-rate', total > 0 ? `${((rejected / total) * 100).toFixed(1)}% نسبة الرفض` : '—');
}

function filterProjReqs(status) {
  if (status !== undefined) {
    projReqFilter = status;
    document.querySelectorAll('#proj-req-filter-tabs .sr-tab-btn').forEach(b => {
      const active = b.getAttribute('onclick')?.includes(`'${status}'`);
      b.style.background = active ? '#7b4ea6' : '#f1f5f9';
      b.style.color = active ? '#fff' : '#64748b';
    });
  }
  renderProjReqs();
}

function renderProjReqs() {
  const tbody = document.getElementById('proj-req-tbody');
  if (!tbody) return;
  const q = (document.getElementById('proj-req-search')?.value || '').toLowerCase();
  let list = allProjReqs;
  if (projReqFilter && projReqFilter !== 'all') list = list.filter(r => r.status === projReqFilter);
  if (q) list = list.filter(r =>
    (r.project?.title || '').toLowerCase().includes(q) ||
    (r.user?.full_name || r.association?.association_name || '').toLowerCase().includes(q)
  );
  if (!list.length) {
    tbody.innerHTML = `<tr><td colspan="5"><div class="empty-state"><div>لا توجد طلبات في هذه الفئة</div></div></td></tr>`;
    return;
  }
  const statusLabels = { pending: { l: 'جديد', cls: 'badge-pending' }, processing: { l: 'قيد المعالجة', cls: 'badge-processing' }, approved: { l: 'مقبول', cls: 'badge-approved' }, rejected: { l: 'مرفوض', cls: 'badge-rejected' } };
  tbody.innerHTML = list.map((r, i) => {
    const col = avatarColors[i % avatarColors.length];
    const user = r.user?.full_name || r.association?.association_name || '—';
    const proj = r.project?.title || '—';
    const date = r.created_at ? formatDate(r.created_at) : '—';
    const s = statusLabels[r.status] || { l: r.status, cls: 'badge-pending' };
    const actions = `
      <button class="action-btn view-btn" title="عرض التفاصيل" onclick="openProjReqModal(${r.id})">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </button>`;
    return `<tr>
      <td><div class="user-cell"><div class="avatar" style="background:${col}">${initials(user)}</div><div class="user-name">${user}</div></div></td>
      <td style="font-weight:600;font-size:.87rem">${proj}</td>
      <td style="font-size:.82rem">${date}</td>
      <td><span class="badge ${s.cls}">${s.l}</span></td>
      <td><div class="action-group">${actions}</div></td>
    </tr>`;
  }).join('');
}

let selectedProjReqId = null;

function openProjReqModal(id) {
  const r = allProjReqs.find(req => req.id === id);
  if (!r) return;
  selectedProjReqId = id;

  const applicant = r.user || r.association || {};
  const isAssoc = !!r.association;
  const name = isAssoc ? r.association.association_name : (r.user?.full_name || '—');
  const email = applicant.email || '—';
  const phone = applicant.phone || '—';

  const statusLabels = { pending: { l: 'جديد', cls: 'badge-pending' }, processing: { l: 'قيد المعالجة', cls: 'badge-processing' }, approved: { l: 'مقبول', cls: 'badge-approved' }, rejected: { l: 'مرفوض', cls: 'badge-rejected' } };
  const s = statusLabels[r.status] || { l: r.status, cls: 'badge-pending' };

  document.getElementById('proj-req-modal-av').textContent = initials(name);
  document.getElementById('proj-req-modal-name').textContent = name + (isAssoc ? ' (جمعية)' : ' (متطوع)');
  document.getElementById('proj-req-modal-email').textContent = email;
  document.getElementById('proj-req-modal-phone').textContent = phone;
  document.getElementById('proj-req-modal-title').textContent = r.project?.title || '—';
  document.getElementById('proj-req-modal-notes').textContent = r.notes || 'لا توجد ملاحظات';
  document.getElementById('proj-req-modal-created').textContent = r.created_at ? formatDate(r.created_at) : '—';

  const statusEl = document.getElementById('proj-req-modal-status');
  statusEl.textContent = s.l;
  statusEl.className = 'badge ' + s.cls;

  const actionSection = document.getElementById('proj-req-action-section');
  if (actionSection) {
    if (r.status === 'pending') {
      actionSection.style.display = 'block';
      const procBtn = document.querySelector('#proj-req-action-section .btn-pill-process');
      if (procBtn) procBtn.style.display = 'flex';
    } else if (r.status === 'processing') {
      actionSection.style.display = 'block';
      const procBtn = document.querySelector('#proj-req-action-section .btn-pill-process');
      if (procBtn) procBtn.style.display = 'none';
    } else {
      actionSection.style.display = 'none';
    }
  }

  document.getElementById('proj-req-modal').classList.add('open');
}

function closeProjReqModal() {
  document.getElementById('proj-req-modal')?.classList.remove('open');
  selectedProjReqId = null;
}

async function submitProjReqStatus(action) {
  if (!selectedProjReqId) return;

  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

  let btnClass = '.btn-pill-approve';
  if (action === 'process') btnClass = '.btn-pill-process';
  if (action === 'reject') btnClass = '.btn-pill-reject';

  const btn = document.querySelector(`#proj-req-modal ${btnClass}`);
  if (btn) btn.disabled = true;

  try {
    const res = await fetch(`/api/project-join-requests/${selectedProjReqId}/${action}`, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({}),
    });
    const data = await res.json();
    if (res.ok && (data.success || data.success !== false)) {
      closeProjReqModal();
      showOrdersToast(data.message || 'تم تحديث حالة الطلب بنجاح', 'success');
      await loadProjRequests();
    } else {
      showOrdersToast(data.message || 'حدث خطأ أثناء حفظ الحالة', 'error');
    }
  } catch (e) {
    showOrdersToast('تعذّر الاتصال بالخادم', 'error');
  } finally {
    if (btn) btn.disabled = false;
  }
}

// ===================== INIT =====================
function initOrders() {
  renderRequests([]);
  fetchOrderCategories().finally(() => {
    renderCategories();
  });
  renderAssoc(associations);
  loadAssociationRequests();
  loadServiceRequests();
  loadMyAssociations();
  loadOppRequests();
  loadProjRequests();

  // If deep-linking via URL params, don't switch to default tab
  // The individual load functions will handle switching to the correct tab
  const urlParams = new URLSearchParams(window.location.search);
  const reqIdParam = urlParams.get('req_id');
  const typeParam = urlParams.get('type');

  if (!reqIdParam) {
    // No deep-link — go to the default tab
    const defaultTabBtn = document.querySelector('.tab-btn[onclick*="my-associations"]');
    if (defaultTabBtn) {
      switchTab('my-associations', defaultTabBtn);
    }
  }
  // else: let loadAssociationRequests / loadServiceRequests / loadOppRequests / loadProjRequests
  // handle switchTab + openModal via their own setTimeout blocks
}

document.addEventListener('DOMContentLoaded', () => {
  const m = document.getElementById('modal');
  if (m) m.addEventListener('click', e => { if (e.target === m) closeModal(); });
  const am = document.getElementById('action-modal');
  if (am) am.addEventListener('click', e => { if (e.target === am) closeActionModal(); });
  const ap = document.getElementById('approve-modal');
  if (ap) ap.addEventListener('click', e => { if (e.target === ap) closeApproveModal(); });
  const acm = document.getElementById('add-cat-modal');
  if (acm) acm.addEventListener('click', e => { if (e.target === acm) closeAddCategoryModal(); });
  const colorInput = document.getElementById('cat-color-input');
  if (colorInput) {
    colorInput.addEventListener('input', (e) => {
      const label = document.getElementById('cat-color-label');
      if (label) label.textContent = String(e.target.value || '').toUpperCase();
    });
  }
});

// ===================== SERVICE REQUESTS TAB =====================
let allServiceReqs = [];
let srFilter = 'all';
let srSearch = '';
let selectedSrId = null;

const srTypeLabels = {
  units: 'بناء وحدات/أنظمة',
  training: 'تدريب المتطوعين',
  initiatives: 'تنسيق المبادرات',
  consulting: 'استشارات متخصصة',
  other: 'طلب آخر',
};

const srStatusMap = {
  pending: { label: 'جديد', cls: 'badge-pending' },
  processing: { label: 'قيد المعالجة', cls: 'badge-review' },
  approved: { label: 'مقبول', cls: 'badge-approved' },
  rejected: { label: 'مرفوض', cls: 'badge-rejected' },
};

async function loadServiceRequests() {
  const tbody = document.getElementById('sr-tbody');
  if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="sr-loading">جاري التحميل...</td></tr>`;
  try {
    const res = await fetch('/api/orders/services', {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    });
    if (!res.ok) {
      const txt = await res.text();
      if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="sr-empty">خطأ ${res.status}: ${txt.substring(0, 120)}</td></tr>`;
      return;
    }
    allServiceReqs = await res.json();
    renderSrTable();
    updateSrStats();
    updateServiceStats();

    // Auto-open specific request if req_id is present in URL
    const urlParams = new URLSearchParams(window.location.search);
    const reqIdParam = urlParams.get('req_id');
    const typeParam = urlParams.get('type');
    if (reqIdParam && (typeParam === 'service_request' || typeParam === 'service_request_created')) {
      const targetReq = allServiceReqs.find(r => String(r.id) === String(reqIdParam));
      if (targetReq) {
        setTimeout(() => {
          _activateTab('services');
          openSrModal(targetReq.id);
          window.history.replaceState({}, document.title, window.location.pathname + window.location.hash);
        }, 500);
      }
    }

  } catch (e) {
    if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="sr-empty">تعذّر الاتصال بالخادم</td></tr>`;
  }
}

function renderSrTable() {
  const tbody = document.getElementById('sr-tbody');
  if (!tbody) return;

  const term = srSearch.toLowerCase();
  const filtered = allServiceReqs.filter(sr => {
    if (srFilter !== 'all' && sr.status !== srFilter) return false;
    const name = (sr.requester_name || sr.association_name || '').toLowerCase();
    const title = (sr.title || '').toLowerCase();
    return !term || name.includes(term) || title.includes(term);
  });

  if (!filtered.length) {
    tbody.innerHTML = `<tr><td colspan="5" class="sr-empty">لا توجد طلبات في هذه الفئة</td></tr>`;
    return;
  }

  tbody.innerHTML = filtered.map(sr => {
    const s = srStatusMap[sr.status] || srStatusMap.pending;
    const name = sr.requester_name || sr.association_name || 'مجهول';
    const col = avatarColors[sr.id % avatarColors.length];
    return `
      <tr>
        <td>
          <div class="user-cell">
            <div class="avatar" style="background:${col}">${initials(name)}</div>
            <div>
              <div class="user-name">${name}</div>
              <div class="user-email">${sr.requester_email || sr.association_email || ''}</div>
            </div>
          </div>
        </td>
        <td style="font-weight:600;font-size:.88rem;color:var(--text)">${sr.title}</td>
        <td style="font-size:.82rem;color:var(--text-muted)">${srTypeLabels[sr.service_type] || 'طلب عام'}</td>
        <td><span class="badge ${s.cls}">${s.label}</span></td>
        <td>
          <div class="action-group">
            <button class="action-btn view-btn" title="معالجة الطلب" onclick="openSrModal(${sr.id})">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </td>
      </tr>`;
  }).join('');
}

function updateSrStats() {
  const el = document.getElementById('sr-pending-count');
  if (el) el.textContent = allServiceReqs.filter(r => r.status === 'pending').length;
}

function updateServiceStats() {
  const data = allServiceReqs;
  const total = data.length;
  const pending = data.filter(r => r.status === 'pending').length;
  const approved = data.filter(r => r.status === 'approved').length;
  const rejected = data.filter(r => r.status === 'rejected').length;

  const now = new Date();
  const thisMonth = data.filter(r => {
    const d = new Date(r.created_at);
    return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
  }).length;

  const approvalRate = total > 0 ? ((approved / total) * 100).toFixed(1) : 0;
  const rejectionRate = total > 0 ? ((rejected / total) * 100).toFixed(1) : 0;

  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  set('ss-total', total);
  set('ss-month', thisMonth > 0 ? `↑ ${thisMonth} هذا الشهر` : 'لا طلبات هذا الشهر');
  set('ss-pending', pending);
  set('ss-approved', approved);
  set('ss-approval-rate', total > 0 ? `↑ ${approvalRate}% نسبة القبول` : '—');
  set('ss-rejected', rejected);
  set('ss-rejection-rate', total > 0 ? `${rejectionRate}% نسبة الرفض` : '—');
}

function filterSrByStatus(status) {
  srFilter = status;
  // update tab buttons
  document.querySelectorAll('#sr-filter-tabs .sr-tab-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.status === status);
  });
  renderSrTable();
}

function searchSr(val) {
  srSearch = val;
  renderSrTable();
}

// ── SR Detail Modal ───────────────────────────────────────────────────────────

function openSrModal(id) {
  const sr = allServiceReqs.find(r => r.id === id);
  if (!sr) return;
  selectedSrId = id;

  const name = sr.requester_name || sr.association_name || 'مجهول';
  const email = sr.requester_email || sr.association_email || '';
  const s = srStatusMap[sr.status] || srStatusMap.pending;

  document.getElementById('srm-av').textContent = initials(name);
  document.getElementById('srm-name').textContent = name;
  document.getElementById('srm-email').textContent = email;
  document.getElementById('srm-type').textContent = srTypeLabels[sr.service_type] || 'طلب عام';
  document.getElementById('srm-title').textContent = sr.title;
  document.getElementById('srm-details').textContent = sr.details || '—';
  document.getElementById('srm-budget').textContent = sr.budget ? sr.budget + ' ر.س' : '—';
  document.getElementById('srm-date').textContent = sr.preferred_date || '—';
  document.getElementById('srm-created').textContent = sr.created_at || '—';

  const statusEl = document.getElementById('srm-status');
  statusEl.textContent = s.label;
  statusEl.className = 'badge ' + s.cls;

  const actionSection = document.getElementById('sr-action-section');
  if (actionSection) {
    if (sr.status === 'pending') {
      actionSection.style.display = 'block';
      const procBtn = document.querySelector('#sr-action-section .btn-pill-process');
      if (procBtn) procBtn.style.display = 'flex';
    } else if (sr.status === 'processing') {
      actionSection.style.display = 'block';
      const procBtn = document.querySelector('#sr-action-section .btn-pill-process');
      if (procBtn) procBtn.style.display = 'none';
    } else {
      actionSection.style.display = 'none';
    }
  }

  document.getElementById('sr-modal').classList.add('open');
}

function closeSrModal() {
  document.getElementById('sr-modal')?.classList.remove('open');
  selectedSrId = null;
}

async function submitSrStatus(status) {
  if (!selectedSrId) return;
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

  let btnClass = '.btn-pill-approve';
  if (status === 'processing') btnClass = '.btn-pill-process';
  if (status === 'rejected') btnClass = '.btn-pill-reject';

  const btn = document.querySelector(`#sr-action-section ${btnClass}`);
  if (btn) btn.disabled = true;

  try {
    const res = await fetch(`/api/orders/services/${selectedSrId}/status`, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ status: status }),
    });
    const data = await res.json();
    if (res.ok && (data.success || data.success !== false)) {
      closeSrModal();
      showOrdersToast(data.message || 'تم تحديث الحالة بنجاح', 'success');
      await loadServiceRequests();
    } else {
      showOrdersToast(data.message || 'حدث خطأ', 'error');
    }
  } catch (e) {
    showOrdersToast('تعذّر الاتصال بالخادم', 'error');
  } finally {
    if (btn) btn.disabled = false;
  }
}

// close modals on backdrop click
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('sr-modal')?.addEventListener('click', e => {
    if (e.target.id === 'sr-modal') closeSrModal();
  });
  document.getElementById('opp-req-modal')?.addEventListener('click', e => {
    if (e.target.id === 'opp-req-modal') closeOppReqModal();
  });
  document.getElementById('proj-req-modal')?.addEventListener('click', e => {
    if (e.target.id === 'proj-req-modal') closeProjReqModal();
  });
});
