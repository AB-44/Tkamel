/* ═══════════════════════════════════════════════════════
   joint-projects.js  — DB-DRIVEN (API)
   ═══════════════════════════════════════════════════════ */

/* ── HELPERS ── */
function getCsrf() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

async function apiFetch(url, opts = {}) {
  const mergedHeaders = {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': getCsrf(),
    ...(opts.headers || {}),
  };
  const res = await fetch(url, { credentials: 'same-origin', ...opts, headers: mergedHeaders });
  const data = await res.json();
  if (!res.ok) throw data;
  return data;
}

function showToast(msg, type = 'success') {
  let t = document.getElementById('jp-toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'jp-toast';
    t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:9999;'
      + 'padding:12px 24px;border-radius:12px;font-size:.88rem;font-weight:700;'
      + 'font-family:Tajawal,sans-serif;box-shadow:0 8px 28px rgba(0,0,0,.18);'
      + 'transition:opacity .3s;min-width:220px;text-align:center;opacity:0;pointer-events:none;';
    document.body.appendChild(t);
  }
  t.style.background = type === 'success' ? '#0d9488' : type === 'error' ? '#dc2626' : '#1d4ed8';
  t.style.color = '#fff';
  t.textContent = msg;
  t.style.opacity = '1';
  clearTimeout(t._t);
  t._t = setTimeout(() => { t.style.opacity = '0'; }, 3500);
}

/* ── STATE ── */
let _projects   = [];
let _categories = [];
let _activeCat  = 'all';
let _searchQ    = '';
let _editingId  = null;
let _pendingFn  = null;
let myProjRequests = [];

/* ── STATUS MAP ── */
const STATUS_MAP = {
  planning:  { label: 'قيد الإعداد', cls: 'b-prep',     icon: 'fa-clipboard-list' },
  active:    { label: 'مستمر',       cls: 'b-active',   icon: 'fa-rocket' },
  idea:      { label: 'فكرة',        cls: 'b-idea',     icon: 'fa-lightbulb' },
  completed: { label: 'مكتمل',      cls: 'b-done',     icon: 'fa-circle-check' },
  canceled:  { label: 'ملغى',        cls: 'b-canceled', icon: 'fa-ban' },
};

/* ═══════════════════════════════════════════════════════
   LOAD DATA
═══════════════════════════════════════════════════════ */
async function loadAll() {
  try {
    const isUser = document.querySelector('.readonly-notice') || window.location.pathname.includes('/user/');
    const params = new URLSearchParams();
    if (_activeCat !== 'all') params.append('category_id', _activeCat);
    if (_searchQ)             params.append('search', _searchQ);

    const promises = [
      apiFetch('/api/joint-projects?' + params.toString()),
      apiFetch('/api/association-categories')
    ];

    if (isUser) {
      promises.push(apiFetch('/api/my-project-requests').catch(() => ({ requests: [] })));
    }

    const results = await Promise.all(promises);
    const projData = results[0];
    const catData  = results[1];
    
    if (isUser) {
      myProjRequests = results[2]?.requests || [];
    }

    _projects   = projData.projects || [];
    _categories = catData.categories || [];

    renderStats(projData.stats || {});
    buildDropdown();
    renderAll();
    populateCategorySelects();
  } catch (e) {
    console.error('loadAll error', e);
    showToast('تعذّر تحميل البيانات', 'error');
  }
}

/* ═══════════════════════════════════════════════════════
   STATS
═══════════════════════════════════════════════════════ */
function renderStats(stats) {
  document.getElementById('statsRow').innerHTML = `
    <div class="stat">
      <div class="stat-ico" style="background:var(--teal-dim)">
        <i class="fa-solid fa-rocket" style="color:var(--teal)"></i>
      </div>
      <div class="stat-info"><span>مشاريع نشطة</span><strong style="color:var(--teal-glow)">${stats.active ?? 0}</strong></div>
    </div>
    <div class="stat">
      <div class="stat-ico" style="background:var(--green-dim)">
        <i class="fa-solid fa-circle-check" style="color:var(--green)"></i>
      </div>
      <div class="stat-info"><span>مشاريع منتهية</span><strong style="color:var(--teal-glow)">${stats.completed ?? 0}</strong></div>
    </div>
    <div class="stat">
      <div class="stat-ico" style="background:var(--amber-dim)">
        <i class="fa-solid fa-chart-line" style="color:var(--amber)"></i>
      </div>
      <div class="stat-info"><span>متوسط الإنجاز</span><strong style="color:var(--teal-glow)">${stats.avg_progress ?? 0}%</strong></div>
    </div>`;
}

/* ═══════════════════════════════════════════════════════
   DROPDOWN
═══════════════════════════════════════════════════════ */
function buildDropdown() {
  const menu = document.getElementById('ddMenu');
  const total = _projects.length;

  let html = `<div class="dd-item ${_activeCat === 'all' ? 'selected' : ''}" data-cat="all">
    <span class="item-emoji"></span><span>كل التصنيفات</span>
    <span class="item-count">${total}</span></div>`;

  _categories.forEach(c => {
    const cnt = _projects.filter(p => {
      const pCats = String(p.category_id || '').split(',');
      return pCats.includes(String(c.id));
    }).length;
    html += `<div class="dd-item ${_activeCat === String(c.id) ? 'selected' : ''}" data-cat="${c.id}">
      <span class="item-emoji"></span><span>${c.name}</span>
      <span class="item-count">${cnt}</span></div>`;
  });

  menu.innerHTML = html;
  menu.querySelectorAll('.dd-item').forEach(item => {
    item.addEventListener('click', () => {
      _activeCat = item.dataset.cat;
      updateDdLabel();
      closeDd();
      renderAll();
    });
  });
  updateDdLabel();
}

function updateDdLabel() {
  const lbl    = document.getElementById('ddLabel');
  const emoEl  = document.querySelector('#ddBtn .emoji');
  if (_activeCat === 'all') {
    if (lbl) lbl.textContent = 'كل التصنيفات';
    if (emoEl) emoEl.textContent = '';
  } else {
    const c = _categories.find(x => String(x.id) === String(_activeCat));
    if (lbl) lbl.textContent = c?.name || 'تصنيف';
    if (emoEl) emoEl.textContent = '';
  }
}

function openDd() {
  document.getElementById('ddMenu').classList.add('open');
  document.getElementById('ddBtn').classList.add('open');
}
function closeDd() {
  document.getElementById('ddMenu').classList.remove('open');
  document.getElementById('ddBtn').classList.remove('open');
}

/* ═══════════════════════════════════════════════════════
   RENDER PROJECTS
═══════════════════════════════════════════════════════ */
function filterProjects(statusGroup) {
  let f = _projects;
  if (_activeCat !== 'all') f = f.filter(p => {
    const cats = String(p.category_id || '').split(',');
    return cats.includes(String(_activeCat));
  });
  if (_searchQ) {
    const q = _searchQ.toLowerCase();
    f = f.filter(p => p.name.toLowerCase().includes(q) || (p.description || '').toLowerCase().includes(q));
  }
  if (statusGroup === 'active')    return f.filter(p => !['completed','canceled'].includes(p.status));
  if (statusGroup === 'completed') return f.filter(p => p.status === 'completed');
  if (statusGroup === 'canceled')  return f.filter(p => p.status === 'canceled');
  return f;
}

function renderAll() {
  const isUser = document.querySelector('.readonly-notice') || window.location.pathname.includes('/user/');
  
  if (isUser) {
    // For Users
    const activeRaw = filterProjects('active');
    
    // Available: Active/Planning but user doesn't have an approved request
    const fAvail = activeRaw.filter(p => !myProjRequests.some(r => r.projId == p.id && r.status === 'approved'));
    // Approved: Active/Planning and user HAS an approved request
    const fApproved = activeRaw.filter(p => myProjRequests.some(r => r.projId == p.id && r.status === 'approved'));
    
    const fDone = filterProjects('completed').concat(filterProjects('canceled'));
    const total = fAvail.length + fApproved.length + fDone.length;

    if (document.getElementById('tab-active')) fillGrid('tab-active', fAvail, 'fa-star', 'لا توجد مشاريع متاحة', 'avail');
    if (document.getElementById('tab-approved')) fillGrid('tab-approved', fApproved, 'fa-rocket', 'لا توجد مشاريع مشتركة نشطة', 'approved');
    if (document.getElementById('tab-done')) fillGrid('tab-done', fDone, 'fa-clock-rotate-left', 'لا توجد مشاريع منتهية', 'done');

    if (document.getElementById('n-active')) document.getElementById('n-active').textContent = fAvail.length;
    if (document.getElementById('n-approved')) document.getElementById('n-approved').textContent = fApproved.length;
    if (document.getElementById('n-done')) document.getElementById('n-done').textContent = fDone.length;
    if (document.getElementById('resNum')) document.getElementById('resNum').textContent = total;
  } else {
    // For Admins
    const fActive    = filterProjects('active');
    const fDone      = filterProjects('completed');
    const fCanceled  = filterProjects('canceled');
    const total      = fActive.length + fDone.length + fCanceled.length;

    if (document.getElementById('tab-active')) fillGrid('tab-active', fActive, 'fa-rocket', 'لا توجد مشاريع نشطة');
    if (document.getElementById('tab-done')) fillGrid('tab-done', fDone, 'fa-circle-check', 'لا توجد مشاريع منتهية');
    if (document.getElementById('tab-canceled')) fillGrid('tab-canceled', fCanceled, 'fa-ban', 'لا توجد مشاريع ملغاة');

    if (document.getElementById('n-active')) document.getElementById('n-active').textContent   = fActive.length;
    if (document.getElementById('n-done')) document.getElementById('n-done').textContent     = fDone.length;
    if (document.getElementById('n-canceled')) document.getElementById('n-canceled').textContent = fCanceled.length;
    if (document.getElementById('resNum')) document.getElementById('resNum').textContent     = total;
  }
}

function fillGrid(id, projs, ico, emptyMsg, mode = 'admin') {
  const el = document.getElementById(id);
  const isUser = document.querySelector('.readonly-notice') || window.location.pathname.includes('/user/');
  if (!projs.length) {
    if (isUser && false) { // We skip the generic message if empty, just use the empty state
      el.innerHTML = `<div class="ue-card" style="grid-column:1/-1"><div class="ue-header"><div class="ue-title">المشاريع المشتركة</div><button class="ue-refresh" onclick="location.reload()" title="تحديث"><i class="fa-solid fa-arrow-rotate-right"></i></button></div><div class="ue-body"><div class="ue-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div><div class="ue-msg">عذراً، خدمة المشاريع المشتركة غير متاحة حالياً، أو لا توجد مشاريع جديدة.</div></div></div>`;
    } else {
      el.innerHTML = `<div class="empty" style="grid-column:1/-1"><i class="fa-solid ${ico}"></i><p>${emptyMsg}</p></div>`;
    }
    return;
  }
  el.innerHTML = projs.map(p => buildCard(p, mode)).join('');
}

function buildCard(p, mode = 'admin') {
  const catIds = String(p.category_id || '').split(',').filter(Boolean);
  const firstCatId = catIds[0];
  const cat = _categories.find(c => String(c.id) === String(firstCatId));
  const catColor = cat?.color || '#2ab8d0';
  const catIcon  = cat?.icon  || '';
  
  // Create HTML for multiple category badges
  let catBadgesHtml = '';
  if (catIds.length > 0) {
    catBadgesHtml = catIds.map(id => {
      const c = _categories.find(x => String(x.id) === String(id));
      if (!c) return '';
      return `<span class="bdg" style="background:${c.color}1a;color:${c.color};white-space:nowrap;">${c.name}</span>`;
    }).join('');
  } else {
    catBadgesHtml = `<span class="bdg" style="background:#2ab8d01a;color:#2ab8d0;white-space:nowrap;">—</span>`;
  }

  const st    = STATUS_MAP[p.status] || STATUS_MAP['planning'];
  const isFin = ['completed','canceled'].includes(p.status);

  const isUser = document.querySelector('.readonly-notice') || window.location.pathname.includes('/user/');

  let acts = '';
  if (!isUser) {
    acts = isFin
      ? `<button type="button" class="abtn d" onclick="deleteProject(${p.id})" title="حذف"><i class="fa-regular fa-trash-can"></i></button>`
      : `<button type="button" class="abtn e" onclick="openEdit(${p.id})" title="تعديل"><i class="fa-regular fa-pen-to-square"></i></button>
         <button type="button" class="abtn c" onclick="confirmCancel(${p.id})" title="إلغاء"><i class="fa-solid fa-ban"></i></button>
         <button type="button" class="abtn d" onclick="confirmDelete(${p.id})" title="حذف"><i class="fa-regular fa-trash-can"></i></button>`;
  } else if (mode === 'avail') {
    const userReq = myProjRequests.find(r => r.projId == p.id);
    if (userReq && userReq.status === 'pending') {
      acts = `<span class="badge badge-review" style="font-size:0.75rem"><i class="fa-solid fa-hourglass-half"></i> قيد المراجعة</span>`;
    } else if (userReq && userReq.status === 'rejected') {
      acts = `<span class="badge badge-rejected" style="font-size:0.75rem"><i class="fa-solid fa-circle-xmark"></i> تم الرفض</span>`;
    } else {
      acts = `<button type="button" class="btn-primary" style="padding: 6px 14px; font-size: 0.8rem;" onclick="applyForProject(${p.id})"><i class="fa-solid fa-hand-sparkles" style="margin-left: 6px;"></i>تقديم طلب</button>`;
    }
  }

  const updates = (p.updates || []).slice(0, 3).map(u => `
    <div class="tl-item">
      <div class="tl-dot" style="background:${catColor}"></div>
      <div><span class="tl-date">${u.created_at}</span><p class="tl-txt">${u.body}</p></div>
    </div>`).join('') || `<p style="font-size:.79rem;color:var(--muted);font-style:italic">لا توجد تحديثات بعد</p>`;

  const dates = (p.start_date || p.end_date) ? `<div class="cdates">
    ${p.start_date ? `<span class="dchip"><i class="fa-regular fa-calendar" style="color:var(--teal)"></i>البدء: ${p.start_date}</span>` : ''}
    ${p.end_date   ? `<span class="dchip"><i class="fa-regular fa-calendar-check" style="color:var(--amber)"></i>النهاية: ${p.end_date}</span>` : ''}
  </div>` : '';

  const progColor = isFin ? '#475569' : catColor;
  
  // Conditionally hide progress and updates for 'avail' mode
  const showProgress = (mode !== 'avail');

  return `
  <div class="pcard" style="--ca:${catColor}" data-id="${p.id}">
    <div class="ct">
      <div style="flex:1;min-width:0">
        <div class="ctitle" ${isFin ? 'style="color:var(--muted)"' : ''}>
          <span style="font-size:1.1rem">${catIcon}</span>${p.name}
        </div>
        <div class="cbadges">
          <span class="bdg ${st.cls}"><i class="fa-solid fa-circle" style="font-size:.4rem"></i>${st.label}</span>
          ${catBadgesHtml}
        </div>
      </div>
      <div class="cactions" style="position:relative; z-index:10; display:flex; align-items:center; gap:6px;">${acts}</div>
    </div>
    <p class="cdesc" ${isFin ? 'style="color:var(--muted)"' : ''}>${p.description || '—'}</p>
    ${dates}
    ${showProgress ? `
    <div class="prog">
      <div class="prog-lbl"><span>نسبة الإنجاز</span><span>${p.progress}%</span></div>
      <div class="prog-tr"><div class="prog-fi" style="width:${p.progress}%;background:${progColor}"></div></div>
    </div>
    <div class="tl"><div class="tl-hd">سجل التقدمات</div>${updates}</div>
    ` : ''}
  </div>`;
}

/* ═══════════════════════════════════════════════════════
   MODALS
═══════════════════════════════════════════════════════ */
function openOv(id) { document.getElementById(id)?.classList.add('open'); }
function closeOv(id) { document.getElementById(id)?.classList.remove('open'); }

function populateCategorySelects() {
  // Create CatPicker for New Project modal
  if (document.getElementById('nD-picker')) {
    if (window.newProjCatPicker) { try { window.newProjCatPicker.destroy(); } catch(e){} }
    window.newProjCatPicker = new CatPicker({
      containerId : 'nD-picker',
      hiddenId    : 'nD',
      categories  : _categories,
      selected    : [],
      multi       : true,
    });
  }
  // Create CatPicker for Edit Project modal
  if (document.getElementById('eDCat-picker')) {
    if (window.editProjCatPicker) { try { window.editProjCatPicker.destroy(); } catch(e){} }
    window.editProjCatPicker = new CatPicker({
      containerId : 'eDCat-picker',
      hiddenId    : 'eDCat',
      categories  : _categories,
      selected    : [],
      multi       : true,
    });
  }
}

/* ── NEW PROJECT ── */
function openNewModal() {
  document.getElementById('fNew').reset();
  // Init CatPicker fresh for new project
  if (document.getElementById('nD-picker')) {
    if (window.newProjCatPicker) { try { window.newProjCatPicker.destroy(); } catch(e){} }
    window.newProjCatPicker = new CatPicker({
      containerId : 'nD-picker',
      hiddenId    : 'nD',
      categories  : _categories,
      selected    : [],
      multi       : true,
    });
  }
  openOv('ovNew');
}

/* ── EDIT PROJECT ── */
function openEdit(id) {
  try {
    const p = _projects.find(x => x.id == id);
    if (!p) {
      showToast('خطأ: لم يتم العثور على المشروع', 'error');
      return;
    }
    _editingId = id;
    document.getElementById('eId').value  = p.id;
    document.getElementById('eN').value   = p.name || '';
    document.getElementById('eG').value   = p.description || '';
    document.getElementById('eS').value   = p.start_date  || '';
    document.getElementById('eE').value   = p.end_date    || '';
    document.getElementById('eP').value   = p.progress    || 0;
    
    if (document.getElementById('eSt')) {
      document.getElementById('eSt').value = p.status || 'planning';
    }
    if (document.getElementById('eU')) {
      document.getElementById('eU').value = '';
    }

    // Init CatPicker with current category
    if (document.getElementById('eDCat-picker')) {
      if (window.editProjCatPicker) { try { window.editProjCatPicker.destroy(); } catch(e){} }
      window.editProjCatPicker = new CatPicker({
        containerId : 'eDCat-picker',
        hiddenId    : 'eDCat',
        categories  : _categories,
        selected    : p.category_id ? String(p.category_id).split(',') : [],
        multi       : true,
      });
    }

    openOv('ovEdit');
  } catch (err) {
    console.error('Error opening edit modal:', err);
    showToast('حدث خطأ أثناء فتح شاشة التعديل', 'error');
  }
}

/* ── CONFIRM ── */
function confirm2(ttl, msg, fn) {
  document.getElementById('cTtl').textContent = ttl;
  document.getElementById('cMsg').textContent = msg;
  _pendingFn = fn;
  openOv('ovConfirm');
}

/* ═══════════════════════════════════════════════════════
   API ACTIONS
═══════════════════════════════════════════════════════ */
async function createProject(payload) {
  try {
    const data = await apiFetch('/api/joint-projects', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    showToast(data.message || 'تم إنشاء المشروع');
    closeOv('ovNew');
    await loadAll();
  } catch (e) {
    const msg = e?.errors ? Object.values(e.errors)[0][0] : (e?.message || 'حدث خطأ');
    showToast(msg, 'error');
  }
}

async function saveEdit(payload) {
  try {
    const data = await apiFetch(`/api/joint-projects/${_editingId}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    showToast(data.message || 'تم التحديث');
    closeOv('ovEdit');
    _editingId = null;
    await loadAll();
  } catch (e) {
    const msg = e?.errors ? Object.values(e.errors)[0][0] : (e?.message || 'حدث خطأ');
    showToast(msg, 'error');
  }
}

async function cancelProject(id) {
  try {
    const data = await apiFetch(`/api/joint-projects/${id}/cancel`, { method: 'POST' });
    showToast(data.message || 'تم الإلغاء');
    await loadAll();
  } catch (e) {
    showToast('حدث خطأ', 'error');
  }
}

async function deleteProject(id) {
  try {
    const data = await apiFetch(`/api/joint-projects/${id}`, { method: 'DELETE' });
    showToast(data.message || 'تم الحذف');
    await loadAll();
  } catch (e) {
    showToast('حدث خطأ', 'error');
  }
}

/* ═══════════════════════════════════════════════════════
   EVENT DELEGATION (for dynamically-rendered cards)
═══════════════════════════════════════════════════════ */
function confirmCancel(id) {
  confirm2('تأكيد الإلغاء', 'هل أنت متأكد من إلغاء هذا المشروع؟', () => cancelProject(id));
}

function confirmDelete(id) {
  confirm2('حذف نهائي', 'هل أنت متأكد؟ لا يمكن التراجع.', () => deleteProject(id));
}

/* ═══════════════════════════════════════════════════════
   USER: APPLY FOR PROJECT — Modal flow
═══════════════════════════════════════════════════════ */
let _applyingProjectId = null;

function applyForProject(id) {
  const project = _projects.find(p => p.id == id);
  if (!project) return;

  _applyingProjectId = id;

  // Fill modal fields
  const titleEl = document.getElementById('proj-apply-title');
  const catEl   = document.getElementById('proj-apply-cat');
  const nameEl  = document.getElementById('proj-apply-name');
  const msgEl   = document.getElementById('proj-apply-msg');

  if (titleEl) titleEl.textContent = project.name;
  if (catEl)   catEl.textContent   = (project.category?.name || '');
  if (nameEl)  nameEl.value        = window.AppApplicantName || '';
  if (msgEl)   msgEl.value         = '';

  // Open the modal
  const modal = document.getElementById('ov-project-apply');
  if (modal) modal.classList.add('open');
}

function closeProjectApplyModal() {
  const modal = document.getElementById('ov-project-apply');
  if (modal) modal.classList.remove('open');
  _applyingProjectId = null;
}

async function submitProjectApply() {
  if (!_applyingProjectId) return;
  const notes = document.getElementById('proj-apply-msg')?.value?.trim() || null;

  try {
    const data = await apiFetch(`/api/joint-projects/${_applyingProjectId}/apply`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ notes }),
    });

    if (data.success) {
      showToast(data.message || 'تم إرسال طلبك بنجاح!', 'success');
      myProjRequests.push({ projId: _applyingProjectId, status: 'pending' });
      closeProjectApplyModal();
      renderAll();
    } else {
      showToast(data.message || 'لم يتم إرسال الطلب', 'error');
    }
  } catch (e) {
    showToast(e?.message || 'حدث خطأ أثناء إرسال الطلب', 'error');
  }
}

/* ═══════════════════════════════════════════════════════
   TABS
═══════════════════════════════════════════════════════ */
function initTabs() {
  document.querySelectorAll('.tab[data-t]').forEach(t => {
    t.addEventListener('click', () => {
      document.querySelectorAll('.tab[data-t]').forEach(x => x.classList.remove('on'));
      document.querySelectorAll('.pane[id^="tab-"]').forEach(x => x.classList.remove('on'));
      t.classList.add('on');
      document.getElementById(t.dataset.t)?.classList.add('on');
    });
  });
}

/* ═══════════════════════════════════════════════════════
   SIDEBAR HELPERS (kept for compatibility)
═══════════════════════════════════════════════════════ */
function toggleSubmenu(id) {
  const menu = document.getElementById('submenu-' + id);
  const parent = document.getElementById('np-' + id);
  document.querySelectorAll('.nav-submenu').forEach(m => {
    if (m.id !== 'submenu-' + id) { m.classList.remove('open'); m.previousElementSibling?.classList.remove('open'); }
  });
  if (menu) { menu.classList.toggle('open'); parent?.classList.toggle('open'); }
}
function toggleServices() { toggleSubmenu('services'); }

/* ═══════════════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {

  /* Tabs */
  initTabs();

  /* Dropdown */
  document.getElementById('ddBtn')?.addEventListener('click', e => {
    e.stopPropagation();
    document.getElementById('ddMenu').classList.contains('open') ? closeDd() : openDd();
  });
  document.getElementById('ddMenu')?.addEventListener('click', e => e.stopPropagation());
  document.addEventListener('click', closeDd);

  /* Search */
  document.getElementById('searchQ')?.addEventListener('input', e => {
    _searchQ = e.target.value.trim();
    renderAll();
  });

  /* New Project button */
  document.getElementById('openNew')?.addEventListener('click', openNewModal);
  document.getElementById('clNew')?.addEventListener('click', () => closeOv('ovNew'));

  /* Edit close */
  document.getElementById('clEdit')?.addEventListener('click', () => { closeOv('ovEdit'); _editingId = null; });

  /* Confirm modal */
  document.getElementById('cY')?.addEventListener('click', () => {
    if (_pendingFn) _pendingFn();
    closeOv('ovConfirm');
    _pendingFn = null;
  });
  document.getElementById('cN')?.addEventListener('click', () => { closeOv('ovConfirm'); _pendingFn = null; });

  /* Close modals on backdrop click */
  ['ovNew','ovEdit','ovConfirm'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', e => {
      if (e.target === document.getElementById(id)) {
        closeOv(id);
        if (id === 'ovEdit') _editingId = null;
        if (id === 'ovConfirm') _pendingFn = null;
      }
    });
  });

  /* Close project-apply modal on Escape */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeProjectApplyModal();
  });

  /* New Project Form Submit */
  document.getElementById('fNew')?.addEventListener('submit', e => {
    e.preventDefault();
    createProject({
      name:        document.getElementById('nN').value.trim(),
      category_id: document.getElementById('nD').value,
      description: document.getElementById('nG').value.trim(),
      start_date:  document.getElementById('nS').value || null,
      end_date:    document.getElementById('nE').value || null,
      status:      document.getElementById('nSt').value || 'planning',
    });
  });

  /* Edit Project Form Submit */
  document.getElementById('fEdit')?.addEventListener('submit', e => {
    e.preventDefault();
    const prog = parseInt(document.getElementById('eP').value) || 0;
    saveEdit({
      name:        document.getElementById('eN').value.trim(),
      category_id: document.getElementById('eDCat')?.value || undefined,
      description: document.getElementById('eG').value.trim(),
      start_date:  document.getElementById('eS').value  || null,
      end_date:    document.getElementById('eE').value  || null,
      progress:    prog,
      status:      document.getElementById('eSt')?.value || undefined,
      update_note: document.getElementById('eU').value.trim() || null,
    });
  });

  /* Initial data load */
  loadAll();
});
