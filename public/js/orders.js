// ===================== STATIC DATA (categories & associations tabs only) =====================
const avatarColors = ['#1f6feb', '#2ea043', '#d29922', '#cf222e', '#8957e5', '#0891b2', '#b45309', '#0e7490'];

const categories = [
  { name: 'تعليمية', icon: '🎓', color: '#1f6feb', bg: 'rgba(31,111,235,.15)', count: 8,  fill: 80  },
  { name: 'خيرية',   icon: '❤️', color: '#f85149', bg: 'rgba(207,34,46,.15)',  count: 14, fill: 100 },
  { name: 'بيئية',   icon: '🌿', color: '#3fb950', bg: 'rgba(46,160,67,.15)',  count: 5,  fill: 36  },
  { name: 'صحية',    icon: '🏥', color: '#58a6ff', bg: 'rgba(31,111,235,.12)', count: 7,  fill: 50  },
  { name: 'رياضية',  icon: '⚽', color: '#bc8cff', bg: 'rgba(137,87,229,.15)', count: 10, fill: 71  },
  { name: 'ثقافية',  icon: '🎭', color: '#e3b341', bg: 'rgba(210,153,34,.15)', count: 6,  fill: 43  },
];

const associations = [
  { name: 'جمعية البر والتقوى',    cat: 'خيرية',   icon: '❤️', bg: 'rgba(207,34,46,.15)',  members: 240, status: 'نشطة',   date: '2020-03-10' },
  { name: 'جمعية رواد المعرفة',     cat: 'تعليمية', icon: '🎓', bg: 'rgba(31,111,235,.15)', members: 185, status: 'نشطة',   date: '2019-07-22' },
  { name: 'جمعية البيئة الخضراء',   cat: 'بيئية',   icon: '🌿', bg: 'rgba(46,160,67,.15)',  members: 97,  status: 'نشطة',   date: '2021-01-15' },
  { name: 'جمعية الأمل الصحي',      cat: 'صحية',    icon: '🏥', bg: 'rgba(31,111,235,.12)', members: 312, status: 'نشطة',   date: '2018-11-05' },
  { name: 'جمعية الرياضة للجميع',   cat: 'رياضية',  icon: '⚽', bg: 'rgba(137,87,229,.15)', members: 420, status: 'نشطة',   date: '2017-04-30' },
  { name: 'جمعية الثقافة والفنون',  cat: 'ثقافية',  icon: '🎭', bg: 'rgba(210,153,34,.15)', members: 130, status: 'نشطة',   date: '2022-06-18' },
  { name: 'جمعية التعليم المستدام', cat: 'تعليمية', icon: '📚', bg: 'rgba(31,111,235,.15)', members: 220, status: 'نشطة',   date: '2016-09-01' },
  { name: 'جمعية بناة الوطن',       cat: 'خيرية',   icon: '🏗️', bg: 'rgba(207,34,46,.15)', members: 158, status: 'موقوفة', date: '2015-02-14' },
  { name: 'جمعية سلامة الغذاء',     cat: 'صحية',    icon: '🥗', bg: 'rgba(31,111,235,.12)', members: 89,  status: 'نشطة',   date: '2023-03-20' },
  { name: 'جمعية الشباب الرياضي',   cat: 'رياضية',  icon: '🏆', bg: 'rgba(137,87,229,.15)', members: 375, status: 'نشطة',   date: '2019-12-11' },
];

const statusMap = {
  pending:  { label: 'قيد المراجعة', cls: 'badge-pending'  },
  approved: { label: 'موافق عليها',   cls: 'badge-approved' },
  rejected: { label: 'مرفوض',         cls: 'badge-rejected' },
  review:   { label: 'يحتاج تعديل',  cls: 'badge-review'   },
};

const catIconMap = {
  'خيرية':'❤️','تعليمية':'🎓','بيئية':'🌿','صحية':'🏥','رياضية':'⚽',
  'ثقافية':'🎭','دينية':'🕌','تنموية':'📈','اسرية':'👨‍👩‍👧',
  'ثقافية وتعليمية':'📚','صحية وبيئية':'🌱',
};

function initials(n) { return (n||'').split(' ').slice(0,2).map(w=>w[0]).join(''); }
function formatDate(d) { return new Date(d).toLocaleDateString('ar-SA',{year:'numeric',month:'short',day:'numeric'}); }
function escQ(s) { return (s||'').replace(/'/g,"\\'"); }

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
      id: 'REG-' + String(a.id).padStart(3,'0'),
      _dbId:   a.id,
      name:    a.manager_name,
      email:   a.email,
      type:    'تسجيل جمعية',
      assoc:   a.association_name,
      cat:     a.category,
      phone:   a.phone,
      license: a.license_number,
      notes:   a.admin_notes || '',
      date:    a.created_at,
      status:  a.status || 'pending',
      _source: 'db',
    }));

    _allData = [...dbAssocRequests];
    renderRequests(_allData);
    updateSidebarBadgesWithDb();
  } catch(e) {}
}

// ===================== RENDER TABLE =====================
function renderRequests(data) {
  const tbody = document.getElementById('requestsTbody');
  if (!tbody) return;

  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><div class="emoji">📭</div><div>لا توجد طلبات مطابقة</div></div></td></tr>`;
    return;
  }

  tbody.innerHTML = data.map((r, i) => {
    const s       = statusMap[r.status] || { label: r.status, cls: 'badge-pending' };
    const col     = avatarColors[i % avatarColors.length];
    const isDb    = r._source === 'db';
    const isPending = r.status === 'pending';
    const catIcon = catIconMap[r.cat] || '🏢';

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
    ` : `<button class="action-btn view-btn" onclick='openRequestModal(${JSON.stringify(r)})'>👁️</button>`;

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
  if (searchVal)    data = data.filter(r =>
    (r.name||'').toLowerCase().includes(searchVal) ||
    (r.email||'').toLowerCase().includes(searchVal) ||
    (r.assoc||'').toLowerCase().includes(searchVal)
  );
  renderRequests(data);
}

// ===================== ACTION MODAL (reject / review with notes) =====================
let _actionDbId = null, _actionType = null;

function openActionModal(dbId, type, assocName) {
  _actionDbId = dbId; _actionType = type;
  const isReject = type === 'reject';
  const modal    = document.getElementById('action-modal'); if (!modal) return;

  document.getElementById('action-modal-icon').textContent  = isReject ? '❌' : '✏️';
  document.getElementById('action-modal-title').textContent = isReject ? 'رفض طلب التسجيل' : 'طلب تعديل البيانات';
  document.getElementById('action-modal-sub').textContent   = 'جمعية: ' + (assocName || '');
  document.getElementById('action-notes-label').textContent = isReject ? 'سبب الرفض *' : 'التعديلات المطلوبة *';
  const btn = document.getElementById('action-confirm-btn');
  btn.textContent = isReject ? '❌ تأكيد الرفض' : '✏️ إرسال طلب التعديل';
  btn.className   = isReject ? 'btn btn-danger' : 'btn btn-review';
  const ta = document.getElementById('action-notes');
  ta.value = ''; ta.placeholder = isReject ? 'أدخل سبب الرفض بوضوح...' : 'أدخل التعديلات المطلوبة...';
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
  const btn  = document.getElementById('action-confirm-btn');
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
  } catch(e) { showOrdersToast('تعذّر الاتصال', 'error'); }
  finally { if (btn) btn.disabled = false; }
}

// ===================== QUICK APPROVE =====================
async function doAction(dbId, action) {
  if (!confirm('هل تريد قبول هذا الطلب؟')) return;
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
  try {
    const res = await fetch(`/api/association-requests/${dbId}/${action}`, {
      method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({}),
    });
    const data = await res.json();
    if (data.success || res.ok) { showOrdersToast(data.message || 'تمت العملية بنجاح', 'success'); await loadAssociationRequests(); }
  } catch(e) { showOrdersToast('تعذّر الاتصال', 'error'); }
}

// ===================== DETAILS MODAL =====================
function openRequestModal(r) {
  const s       = statusMap[r.status] || { label: r.status, cls: 'badge-pending' };
  const catIcon = catIconMap[r.cat] || '🏢';
  const isDb    = r._source === 'db';
  const isPending = r.status === 'pending';
  const modalBody = document.getElementById('modalBody');
  if (!modalBody) return;

  // Update modal head to show a gradient header
  const modalHead = document.querySelector('#modal .modal-head');
  if (modalHead) {
    modalHead.style.cssText = 'background:linear-gradient(135deg,#1a6b7c,#2ab8d0);padding:18px 20px;border-bottom:none;border-radius:18px 18px 0 0;';
    modalHead.innerHTML = `
      <div style="font-size:1.1rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:8px;">
        🗂️ تفاصيل طلب التسجيل
      </div>
      <button onclick="closeModal()" style="background:rgba(255,255,255,.15);border:none;border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;font-size:1rem;transition:all .2s;" onmouseover="this.style.background='rgba(255,255,255,.25)'" onmouseout="this.style.background='rgba(255,255,255,.15)'">✕</button>`;
  }

  function makeCard(label, value, dir='') {
    return `<div style="background:#f8fafc;border-radius:14px;padding:14px 16px;text-align:right;border:1px solid #edf2f7;">
      <div style="font-size:.75rem;color:#94a3b8;font-weight:700;margin-bottom:4px">${label}</div>
      <div style="font-size:.95rem;color:#1e293b;font-weight:700;" ${dir}>${value||'—'}</div>
    </div>`;
  }

  modalBody.style.cssText = 'padding:20px;display:flex;flex-direction:column;gap:12px;background:#fff;';
  modalBody.innerHTML = `
    <div style="text-align:center;padding:8px 0 4px;">
      <div style="font-size:1.3rem;font-weight:800;color:#1e293b;">${catIcon} ${r.assoc}</div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
      ${makeCard('رقم الطلب', r.id)}
      ${makeCard('التصنيف', r.cat)}
      ${makeCard('اسم المسؤول', r.name)}
      ${makeCard('البريد الإلكتروني', r.email, 'dir="ltr"')}
      ${makeCard('رقم الجوال', r.phone, 'dir="ltr"')}
      ${makeCard('رقم الترخيص', r.license, 'dir="ltr"')}
      ${makeCard('تاريخ الطلب', formatDate(r.date))}
      <div style="background:#f8fafc;border-radius:14px;padding:14px 16px;text-align:right;border:1px solid #edf2f7;">
        <div style="font-size:.75rem;color:#94a3b8;font-weight:700;margin-bottom:4px">الحالة</div>
        <span class="badge ${s.cls}">${s.label}</span>
      </div>
    </div>
    ${r.notes ? `<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:14px 16px;text-align:right;">
      <div style="font-size:.75rem;color:#92400e;font-weight:700;margin-bottom:4px">ملاحظات الإدارة</div>
      <div style="font-size:.9rem;color:#78350f;line-height:1.7">${r.notes}</div>
    </div>` : ''}`;

  const footer = document.querySelector('#modal .modal-footer');
  if (footer) {
    footer.style.cssText = 'padding:16px 20px;border-top:1px solid #e2e8f0;display:flex;gap:10px;justify-content:center;flex-wrap:wrap;background:#fff;border-radius:0 0 18px 18px;';
    if (isDb && isPending) {
      footer.innerHTML = `
        <button onclick="closeModal();openActionModal('${r._dbId}','reject','${escQ(r.assoc)}')" style="background:#1e293b;color:#fff;border:none;border-radius:50px;padding:11px 22px;font-weight:700;font-family:'Tajawal',sans-serif;cursor:pointer;font-size:.9rem;display:flex;align-items:center;gap:6px;transition:all .2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'"><span>✕</span> رفض</button>
        <button onclick="closeModal();openActionModal('${r._dbId}','review','${escQ(r.assoc)}')" style="background:linear-gradient(135deg,#f59e0b,#ea580c);color:#fff;border:none;border-radius:50px;padding:11px 22px;font-weight:700;font-family:'Tajawal',sans-serif;cursor:pointer;font-size:.9rem;transition:all .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">✏️ طلب تعديل</button>
        <button onclick="closeModal()" style="background:transparent;color:#0891b2;border:none;border-radius:50px;padding:11px 18px;font-weight:700;font-family:'Tajawal',sans-serif;cursor:pointer;font-size:.9rem;">إلغاء</button>
        <button onclick="closeModal();doAction('${r._dbId}','approve')" style="background:linear-gradient(135deg,#3a72b8,#7b4ea6);color:#fff;border:none;border-radius:50px;padding:11px 26px;font-weight:700;font-family:'Tajawal',sans-serif;cursor:pointer;font-size:.9rem;transition:all .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">✓ قبول</button>`;
    } else {
      footer.innerHTML = `<button onclick="closeModal()" style="background:linear-gradient(135deg,#3a72b8,#7b4ea6);color:#fff;border:none;border-radius:50px;padding:11px 40px;font-weight:700;font-family:'Tajawal',sans-serif;cursor:pointer;font-size:.9rem;">إغلاق</button>`;
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
  t.style.background = type==='success'?'#0d9488':type==='error'?'#dc2626':'#1d6fa4';
  t.style.color='#fff'; t.textContent=msg; t.style.opacity='1';
  clearTimeout(t._t); t._t=setTimeout(()=>{t.style.opacity='0';},3500);
}

// ===================== BADGES =====================
function updateSidebarBadgesWithDb() {
  const n = dbAssocRequests.filter(r=>r.status==='pending').length;
  const nb = document.getElementById('nb-reqs');
  if (nb) nb.textContent = n>0 ? n : '';
}
function updateSidebarBadges() {
  // nb-opps is managed by consulting.js based on real opportunity data
}

// ===================== CATEGORIES =====================
function renderCategories() {
  const grid = document.getElementById('categoriesGrid'); if (!grid) return;
  grid.innerHTML = `<div class="category-card" onclick="filterAssocByCat('')" style="display:flex;align-items:center;gap:1.5rem;background:linear-gradient(135deg,var(--teal-deep),var(--teal));color:white;border:none;"><div class="category-icon" style="background:rgba(255,255,255,.2);margin-bottom:0;font-size:1.5rem;">🌍</div><div><div class="category-name" style="color:white;margin-bottom:0;">جميع التصنيفات</div><div class="category-count" style="color:rgba(255,255,255,.7);">عرض كل الجمعيات</div></div></div>`
    + categories.map(c=>`<div class="category-card" onclick="filterAssocByCat('${c.name}')"><div class="category-icon" style="background:${c.bg}">${c.icon}</div><div class="category-name">${c.name}</div><div class="category-count">${c.count} جمعية مسجلة</div><div class="category-bar"><div class="category-fill" style="width:${c.fill}%;background:${c.color}"></div></div></div>`).join('');
}

// ===================== ASSOCIATIONS =====================
function renderAssoc(data) {
  const list = document.getElementById('assocList'); if (!list) return;
  list.innerHTML = data.map(a=>`<div class="assoc-item"><div class="assoc-logo" style="background:${a.bg}">${a.icon}</div><div class="assoc-info"><div class="assoc-name">${a.name}</div><div class="assoc-cat">📁 ${a.cat}</div></div><div class="assoc-meta"><span>👥 ${a.members} عضو</span><span>📅 ${formatDate(a.date)}</span><span class="badge ${a.status==='نشطة'?'badge-approved':'badge-rejected'}">${a.status}</span></div></div>`).join('');
}
function filterAssoc() { const v=document.getElementById('catFilter')?.value; renderAssoc(v?associations.filter(a=>a.cat===v):associations); }
function filterAssocByCat(cat) {
  switchTab('associations',document.querySelectorAll('.tab-btn')[1]);
  const cf=document.getElementById('catFilter'); if(cf) cf.value=cat;
  renderAssoc(cat?associations.filter(a=>a.cat===cat):associations);
  if(cat) setTimeout(()=>document.getElementById('assocList')?.scrollIntoView({behavior:'smooth',block:'start'}),100);
}

// ===================== TABS =====================
function switchTab(id,btn) {
  document.querySelectorAll('.tab-content').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  document.getElementById('tab-'+id)?.classList.add('active');
  if(btn) btn.classList.add('active');
}

// ===================== SIDEBAR =====================
function toggleServices() {
  const p=document.getElementById('np-services'), s=document.getElementById('submenu-services');
  if(!s) return; const o=s.classList.contains('open');
  s.classList.toggle('open',!o); if(p) p.classList.toggle('open',!o);
}
function showAdminRequests(el) { switchTab('requests',document.querySelectorAll('.tab-btn')[0]); }
function showToast(i,m){} function toggleNotifs(){} function openMeetingsPage(){} function backToVolunteer(){}

// ===================== INIT =====================
function initOrders() {
  renderRequests([]);
  renderCategories();
  renderAssoc(associations);
  loadAssociationRequests();
}

document.addEventListener('DOMContentLoaded', () => {
  const m=document.getElementById('modal');
  if(m) m.addEventListener('click',e=>{if(e.target===m)closeModal();});
  const am=document.getElementById('action-modal');
  if(am) am.addEventListener('click',e=>{if(e.target===am)closeActionModal();});
});
