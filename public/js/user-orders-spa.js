/* ══════════════════════════════════════════════════
   user-orders-spa.js — "My Requests" section (SPA)
   Fetches /api/user/my-requests and renders request cards,
   filters/search, and the details/edit modal.
══════════════════════════════════════════════════ */

let _mrAllRequests = [];
let _mrCurrentType = 'all';
let _mrCurrentStatus = 'all';
let _mrCurrentQuery = '';
let _mrCurrentCard = null; // holds the plain-object request being viewed in the modal

const MR_STATUS_META = {
  'status-pending':  { label: 'قيد المراجعة', bg: 'rgba(245,158,11,.15)', color: '#d97706', border: 'rgba(245,158,11,.35)' },
  'status-approved': { label: 'مقبول',        bg: 'rgba(16,185,129,.15)', color: '#059669', border: 'rgba(16,185,129,.35)' },
  'status-rejected': { label: 'مرفوض',        bg: 'rgba(239,68,68,.15)',  color: '#dc2626', border: 'rgba(239,68,68,.35)'  },
};
const MR_TYPE_LABELS = { opportunity: 'فرصة تطوع', project: 'مشروع مشترك', service: 'خدمة مبادرون' };
const MR_SVC_TYPES = { units: 'بناء وحدات/أنظمة', training: 'تدريب المتطوعين', initiatives: 'تنسيق المبادرات', consulting: 'استشارات متخصصة', other: 'طلب آخر' };

function mrGetCsrf() { return document.querySelector('meta[name="csrf-token"]')?.content || ''; }

function mrStatusClass(status) {
  if (['pending', 'review'].includes(status)) return { cls: 'status-pending', label: 'قيد المراجعة', data: 'pending' };
  if (['approved', 'completed'].includes(status)) return { cls: 'status-approved', label: 'مقبول', data: 'approved' };
  return { cls: 'status-rejected', label: 'مرفوض', data: 'rejected' };
}

function mrFmtDate(iso) {
  if (!iso) return '';
  return new Date(iso).toLocaleDateString('ar-SA', { day: 'numeric', month: 'short', year: 'numeric' });
}

/* ══ INIT / REFRESH — called by spa-nav.js ══ */
function ordersUserInit() {
  mrWireToolbar();
  mrFetchRequests();
}
function ordersUserRefresh() {
  mrFetchRequests();
}

async function mrFetchRequests() {
  const loading = document.getElementById('mr-loading');
  if (loading) loading.style.display = 'flex';
  try {
    const res = await fetch('/api/user/my-requests', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    if (!res.ok) return;
    const data = await res.json();
    _mrAllRequests = data.requests || [];
    mrRenderStats(data.stats || {});
    mrRenderTabBadges();
    mrRenderList();
  } catch (e) { /* silently fail */ }
}

function mrRenderStats(stats) {
  const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = Number(v || 0); };
  set('mr-stat-total', stats.total_requests);
  set('mr-stat-pending', stats.pending_requests);
  set('mr-stat-approved', stats.approved_requests);
  set('mr-stat-rejected', stats.rejected_requests);
}

function mrRenderTabBadges() {
  const counts = { all: _mrAllRequests.length, opportunity: 0, project: 0, service: 0 };
  _mrAllRequests.forEach(r => { if (counts[r.type] !== undefined) counts[r.type]++; });
  Object.keys(counts).forEach(k => {
    const el = document.getElementById('mr-tab-badge-' + k);
    if (el) el.textContent = counts[k];
  });
}

function mrRenderList() {
  const container = document.getElementById('requests-list');
  const clientEmpty = document.getElementById('client-empty');
  if (!container) return;

  const filtered = _mrAllRequests.filter(req => {
    const st = mrStatusClass(req.status);
    const typeMatch = _mrCurrentType === 'all' || req.type === _mrCurrentType;
    const statusMatch = _mrCurrentStatus === 'all' || st.data === _mrCurrentStatus;
    const q = _mrCurrentQuery.toLowerCase();
    const queryMatch = !q || (req.title || '').toLowerCase().includes(q) || (req.sub || '').toLowerCase().includes(q);
    return typeMatch && statusMatch && queryMatch;
  });

  // Remove previously rendered cards (keep the client-empty node)
  Array.from(container.querySelectorAll('.mr-req-card')).forEach(el => el.remove());
  const loading = document.getElementById('mr-loading');
  if (loading) loading.remove();

  if (!filtered.length) {
    if (clientEmpty) clientEmpty.style.display = 'flex';
    return;
  }
  if (clientEmpty) clientEmpty.style.display = 'none';

  const cardsHtml = filtered.map(req => {
    const st = mrStatusClass(req.status);
    return `
      <div class="mr-req-card" data-id="${req.id}" data-status="${st.data}" data-type="${req.type}">
        <div class="mr-req-icon" style="background: ${req.color}1a; color: ${req.color};">
          <i class="fa-solid ${req.icon}"></i>
        </div>
        <div class="mr-req-info">
          <div class="mr-req-title">${mrEsc(req.title)}</div>
          <div class="mr-req-sub">${mrEsc(req.sub)}</div>
        </div>
        <div class="mr-req-date">${mrFmtDate(req.date)}</div>
        <div class="mr-req-status">
          <span class="mr-badge ${st.cls}">${st.label}</span>
        </div>
        <div class="mr-req-actions">
          <button class="mr-btn-details" onclick="mrOpenReqModal(${req.id})">
            تفاصيل
            <i class="fa-solid fa-chevron-left" style="font-size:0.7rem; margin-right:2px;"></i>
          </button>
        </div>
      </div>`;
  }).join('');

  container.insertAdjacentHTML('afterbegin', cardsHtml);
}

function mrEsc(s) {
  return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/* ══ TOOLBAR WIRING (search / chips / tabs / status filter) ══ */
function mrWireToolbar() {
  const searchInput = document.getElementById('mrSearchInput');
  const statusFilter = document.getElementById('mrStatusFilter');
  const chips = document.querySelectorAll('#view-orders .mr-chip');
  const tabs = document.querySelectorAll('#view-orders .mr-tab');

  if (searchInput && !searchInput._mrWired) {
    searchInput._mrWired = true;
    searchInput.addEventListener('input', e => { _mrCurrentQuery = e.target.value; mrRenderList(); });
  }
  if (statusFilter && !statusFilter._mrWired) {
    statusFilter._mrWired = true;
    statusFilter.addEventListener('change', e => { _mrCurrentStatus = e.target.value; mrRenderList(); });
  }

  function setTypeFilter(type) {
    _mrCurrentType = type;
    chips.forEach(c => c.classList.toggle('active', c.dataset.type === type));
    tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === type));
    mrRenderList();
  }

  chips.forEach(chip => {
    if (chip._mrWired) return;
    chip._mrWired = true;
    chip.addEventListener('click', () => setTypeFilter(chip.dataset.type));
  });
  tabs.forEach(tab => {
    if (tab._mrWired) return;
    tab._mrWired = true;
    tab.addEventListener('click', () => setTypeFilter(tab.dataset.tab));
  });
}

/* ══ MODAL LOGIC ══ */
function mrOpenReqModal(id) {
  const req = _mrAllRequests.find(r => String(r.id) === String(id));
  if (!req) return;
  _mrCurrentCard = req;
  const st = mrStatusClass(req.status);
  const meta = MR_STATUS_META[st.cls] || {};

  document.getElementById('rmd-icon').innerHTML = '<i class="fa-solid ' + req.icon + '"></i>';
  document.getElementById('rmd-title').textContent = req.title;
  document.getElementById('rmd-sub').textContent = req.sub;
  document.getElementById('rmd-badge').innerHTML =
    '<span style="background:' + meta.bg + ';color:' + meta.color + ';border:1px solid ' + meta.border + ';padding:5px 16px;border-radius:20px;font-size:.78rem;font-weight:800;font-family:Tajawal,sans-serif;">' + st.label + '</span>';

  document.getElementById('rmd-meta').innerHTML =
    '<div class="rmd-mpill"><i class="fa-regular fa-calendar"></i>' + mrFmtDate(req.date) + '</div>' +
    '<div class="rmd-mpill"><i class="fa-solid fa-tag"></i>' + (MR_TYPE_LABELS[req.type] || req.type) + '</div>';

  mrBuildViewBody(req);
  mrBuildFooter(req, st.data);

  document.getElementById('rmd-body').style.display = 'block';
  document.getElementById('rmd-edit').style.display = 'none';
  document.getElementById('req-modal-overlay').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function mrBuildViewBody(req) {
  const row = (icon, bg, color, label, val) => !val ? '' :
    '<div class="rmd-row">' +
      '<div class="rmd-ico" style="background:' + bg + ';color:' + color + ';"><i class="fa-solid ' + icon + '"></i></div>' +
      '<div><div class="rmd-lbl">' + label + '</div><div class="rmd-val">' + mrEsc(val) + '</div></div>' +
    '</div>';

  let html = '';
  if (req.type === 'opportunity') {
    html += row('fa-align-right', 'rgba(202,138,4,.1)', '#ca8a04', 'وصف الفرصة', req.opportunity_desc);
    html += row('fa-calendar-xmark', 'rgba(225,29,72,.1)', '#e11d48', 'الموعد النهائي', req.deadline ? mrFmtDate(req.deadline) : '');
  }
  if (req.type === 'project') {
    html += row('fa-align-right', 'rgba(22,163,74,.1)', '#16a34a', 'وصف المشروع', req.project_desc);
  }
  if (req.type === 'service') {
    html += row('fa-screwdriver-wrench', 'rgba(124,58,237,.1)', '#7c3aed', 'نوع الخدمة', MR_SVC_TYPES[req.service_type] || req.service_type);
    html += row('fa-file-lines', 'rgba(124,58,237,.1)', '#7c3aed', 'تفاصيل الطلب', req.details);
    html += row('fa-coins', 'rgba(217,119,6,.1)', '#d97706', 'الميزانية', req.budget ? req.budget + ' ريال' : '');
    html += row('fa-calendar-check', 'rgba(22,163,74,.1)', '#16a34a', 'التاريخ المفضّل', req.preferred_date ? mrFmtDate(req.preferred_date) : '');
  }
  html += row('fa-comment-dots', 'rgba(100,116,139,.1)', '#64748b', 'ملاحظات', req.notes);

  document.getElementById('rmd-body').innerHTML = html ||
    '<p style="color:#94a3b8;text-align:center;padding:2.5rem 1rem;font-family:Tajawal,sans-serif;font-size:.9rem;">لا توجد تفاصيل إضافية.</p>';
}

function mrBuildFooter(req, statusData) {
  const foot = document.getElementById('rmd-footer');
  let html = '';
  if (statusData === 'pending' && req.type === 'service') {
    html += '<button class="rmd-btn rmd-btn-edit" onclick="mrSwitchToEdit()"><i class="fa-solid fa-pen-to-square"></i>تعديل الطلب</button>';
  }
  html += '<button class="rmd-btn rmd-btn-cancel" onclick="mrCloseReqModal()"><i class="fa-solid fa-xmark"></i>إغلاق</button>';
  foot.innerHTML = html;
}

function mrSwitchToEdit() {
  const req = _mrCurrentCard;
  document.getElementById('rmd-body').style.display = 'none';
  document.getElementById('rmd-edit').style.display = 'block';

  document.getElementById('rmd-edit').innerHTML = `
    <div class="rmd-fg">
      <label>عنوان الطلب</label>
      <input type="text" id="ef-title" value="${mrEsc(req.title)}" placeholder="عنوان الطلب">
    </div>
    <div class="rmd-fg">
      <label>نوع الخدمة</label>
      <select id="ef-type">
        <option value="units"       ${req.service_type === 'units' ? 'selected' : ''}>بناء وحدات/أنظمة</option>
        <option value="training"    ${req.service_type === 'training' ? 'selected' : ''}>تدريب المتطوعين</option>
        <option value="initiatives" ${req.service_type === 'initiatives' ? 'selected' : ''}>تنسيق المبادرات</option>
        <option value="consulting"  ${req.service_type === 'consulting' ? 'selected' : ''}>استشارات متخصصة</option>
        <option value="other"       ${req.service_type === 'other' ? 'selected' : ''}>طلب آخر</option>
      </select>
    </div>
    <div class="rmd-fg">
      <label>تفاصيل الطلب</label>
      <textarea id="ef-details">${mrEsc(req.details || '')}</textarea>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
      <div class="rmd-fg">
        <label>الميزانية (ريال)</label>
        <input type="number" id="ef-budget" value="${mrEsc(req.budget || '')}" placeholder="0" min="0">
      </div>
      <div class="rmd-fg">
        <label>التاريخ المفضّل</label>
        <input type="date" id="ef-pdate" value="${mrEsc(req.preferred_date || '')}">
      </div>
    </div>
    <div id="ef-msg" style="display:none;padding:10px 14px;border-radius:10px;font-size:.85rem;font-weight:600;font-family:Tajawal,sans-serif;margin-top:4px;"></div>`;

  document.getElementById('rmd-footer').innerHTML =
    '<button class="rmd-btn rmd-btn-save" onclick="mrSaveEdit(' + req.id + ')"><i class="fa-solid fa-floppy-disk"></i>حفظ التعديلات</button>' +
    '<button class="rmd-btn rmd-btn-cancel" onclick="mrSwitchToView()"><i class="fa-solid fa-arrow-right"></i>رجوع</button>';
}

function mrSwitchToView() {
  document.getElementById('rmd-body').style.display = 'block';
  document.getElementById('rmd-edit').style.display = 'none';
  const st = mrStatusClass(_mrCurrentCard.status);
  mrBuildViewBody(_mrCurrentCard);
  mrBuildFooter(_mrCurrentCard, st.data);
}

function mrSaveEdit(id) {
  const msg = document.getElementById('ef-msg');
  const title = document.getElementById('ef-title').value.trim();
  const details = document.getElementById('ef-details').value.trim();

  if (!title || !details) {
    mrShowMsg(msg, 'error', 'يرجى تعبئة جميع الحقول المطلوبة.');
    return;
  }

  const saveBtn = document.querySelector('.rmd-btn-save');
  saveBtn.disabled = true;
  saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>جارٍ الحفظ...';

  fetch('/user/service-requests/' + id, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': mrGetCsrf() },
    body: JSON.stringify({
      service_type: document.getElementById('ef-type').value,
      title: title,
      details: details,
      budget: document.getElementById('ef-budget').value || null,
      preferred_date: document.getElementById('ef-pdate').value || null,
    }),
  })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        // Update local cache + re-render
        const req = _mrCurrentCard;
        req.title = title;
        req.details = details;
        req.budget = document.getElementById('ef-budget').value;
        req.preferred_date = document.getElementById('ef-pdate').value;
        req.service_type = document.getElementById('ef-type').value;
        document.getElementById('rmd-title').textContent = title;
        mrShowMsg(msg, 'success', 'تم حفظ التعديلات بنجاح ✓');
        setTimeout(() => { mrSwitchToView(); mrRenderList(); }, 1300);
      } else {
        mrShowMsg(msg, 'error', data.message || 'حدث خطأ غير متوقع.');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>حفظ التعديلات';
      }
    })
    .catch(() => {
      mrShowMsg(msg, 'error', 'تعذّر الاتصال بالخادم.');
      saveBtn.disabled = false;
      saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>حفظ التعديلات';
    });
}

function mrShowMsg(el, type, text) {
  el.style.display = 'block';
  el.style.background = type === 'success' ? 'rgba(16,185,129,.1)' : 'rgba(239,68,68,.1)';
  el.style.color = type === 'success' ? '#059669' : '#dc2626';
  el.textContent = text;
}

function mrCloseReqModal(e) {
  if (e && e.target !== document.getElementById('req-modal-overlay')) return;
  document.getElementById('req-modal-overlay').style.display = 'none';
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    const ov = document.getElementById('req-modal-overlay');
    if (ov) { ov.style.display = 'none'; document.body.style.overflow = ''; }
  }
});

/* Backward-compat alias used by inline onclick in the partial if needed */
function closeReqModal(e) { mrCloseReqModal(e); }
function openReqModal(btn) {
  const card = btn.closest('.mr-req-card');
  if (card) mrOpenReqModal(card.dataset.id);
}
