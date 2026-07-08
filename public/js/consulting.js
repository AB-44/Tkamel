/* ══════════════════════════════════
   DATA & API INTEGRATION
══════════════════════════════════ */
let CATEGORIES = [];

let opportunities = [];
let requests = [];

let editingOppId = null;
let deletingOppId = null;
let applyingOppId = null;
let currentCatId = null;
let currentRole = window.AppRole || 'user';
let reqFilter = 'pending';

// Helper for CSRF
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function fetchOpportunities() {
  try {
    const isAdmin = currentRole === 'admin';
    const endpoint = isAdmin ? '/api/opportunities/admin' : '/api/opportunities';
    const res = await fetch(endpoint, {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    opportunities = data.opportunities.map(o => ({
      id: o.id,
      catIds: (function() {
        if (!o.type) return ['__none__'];
        let types = String(o.type).split(',').map(t => t.trim()).filter(Boolean);
        let ids = [];
        types.forEach(t => {
          if (t === 'all') { ids.push('all'); return; }
          const byId = CATEGORIES.find(c => c.id === t);
          if (byId) { ids.push(byId.id); return; }
          const byLegacy = CATEGORIES.find(c => c.legacyId === t);
          if (byLegacy) { ids.push(byLegacy.id); return; }
          ids.push(t);
        });
        return ids.length ? ids : ['__none__'];
      })(),
      title: o.title,
      desc: o.description,
      org: o.creator?.name || 'مبادرون',
      city: '',
      seats: parseInt(o.requirements) || 0,
      deadline: o.deadline,
      type: o.direction === 'international' ? 'remote' : (o.direction === 'both' ? 'both' : 'onsite'),
      status: (function() {
        const dl = o.deadline;
        if (!dl || dl === '0000-00-00') return 'open';
        const d = new Date(dl);
        if (isNaN(d.getTime())) return 'open';
        // For date-only strings (YYYY-MM-DD), include the full deadline day
        if (/^\d{4}-\d{2}-\d{2}$/.test(dl)) d.setDate(d.getDate() + 1);
        return d >= new Date() ? 'open' : 'closed';
      })(),
      has_applied: o.has_applied || false
    }));
    renderCats();
    renderUserOpps();
    if (currentCatId) {
      isAdmin ? renderAdminOpps() : renderApplicantOpps();
    }
  } catch (e) {
    console.error("Error fetching opportunities", e);
    renderCats();
    renderUserOpps();
  }
}

async function fetchRequests() {
  if (currentRole !== 'admin') {
    try {
      const res = await fetch('/api/my-opportunity-requests', { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      requests = (data.requests || []).map(r => ({
        id: r.id, oppId: r.oppId || r.opportunity_id,
        assocName: r.assocName || r.association?.association_name || r.user?.full_name || '...',
        assocCity: '', message: r.notes || '',
        date: r.created_at ? r.created_at.split('T')[0] : '', status: r.status
      }));
      updateApplicantStats();
      renderUserOpps();
      if (currentCatId) renderApplicantOpps();
    } catch (e) { console.error("Error fetching my requests", e); }
    return;
  }

  try {
    const res = await fetch('/api/opportunity-requests', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    requests = (data.requests || []).map(r => ({
      id: r.id, oppId: r.opportunity_id,
      assocName: r.association?.association_name || r.user?.name || r.user?.full_name || '...',
      assocCity: r.association?.city || '', message: r.notes || '',
      date: r.created_at.split('T')[0], status: r.status
    }));
    renderVolRequests();
    updateStats();
  } catch (e) { console.error("Error fetching requests", e); }
}

/* ══ USER RICH OPP CARD (same format as admin, apply button instead of edit/delete) ══ */
function richOppCardUser(o) {
  const isAll = o.catIds && o.catIds.includes('all');
  const cat = (!isAll && o.catIds && o.catIds.length) ? CATEGORIES.find(c => c.id === o.catIds[0]) : null;
  const acc      = cat?.color || '#2ab8d0';
  const typeLabel= o.type === 'onsite' ? 'حضوري' : o.type === 'remote' ? 'عن بعد' : 'مزدوج';
  const typeCls  = o.type === 'onsite' ? 'rich-opp-tag-onsite' : 'rich-opp-tag-remote';
  const stCls    = o.status === 'open'  ? 'rich-opp-tag-open'  : 'rich-opp-tag-closed';
  const stLabel  = o.status === 'open'  ? 'مفتوحة'             : 'مغلقة';
  const deadline = o.deadline ? o.deadline.replace(/(\d{4})-(\d{2})-(\d{2})/, '$3/$2/$1') : '—';

  // Extract link
  let oppLink = '';
  if (o.desc && o.desc.includes('رابط الفرصة: ')) {
    oppLink = o.desc.split('رابط الفرصة: ')[1].split('\n')[0].trim();
  }

  // Apply / status button
  const myReq = requests.find(r => r.oppId === o.id);
  let applyBtn = '';
  if (o.has_applied || myReq) {
    const st = myReq ? myReq.status : 'pending';
    if (st === 'approved')
      applyBtn = `<span class="rich-apply-badge" style="color:#059669;background:#d1fae5"><i class="fa-solid fa-circle-check" style="font-size:.7rem;margin-left:4px"></i>مقبول</span>`;
    else if (st === 'rejected')
      applyBtn = `<span class="rich-apply-badge" style="color:#dc2626;background:#fee2e2"><i class="fa-solid fa-circle-xmark" style="font-size:.7rem;margin-left:4px"></i>مرفوض</span>`;
    else
      applyBtn = `<span class="rich-apply-badge" style="color:#d97706;background:#fef3c7"><i class="fa-solid fa-clock" style="font-size:.7rem;margin-left:4px"></i>تحت المراجعة</span>`;
  } else {
    if (oppLink) {
      applyBtn = `<button class="btn-primary" style="padding:7px 18px;font-size:0.82rem" onclick="window.open('${oppLink}', '_blank')"><span class="btn-icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>تقديم طلب</button>`;
    } else {
      applyBtn = `<button class="btn-primary" style="padding:7px 18px;font-size:0.82rem" onclick="openApply(${o.id})"><span class="btn-icon">+</span>تقديم طلب</button>`;
    }
  }

  // Build category badge
  const catBadge = isAll
    ? `<div class="opp-cat-badge" style="background:rgba(30,64,175,0.09);color:#1e40af;border-color:rgba(30,64,175,0.18)"><span style="font-size:1rem">🌐</span><span>لكل الجمعيات</span></div>`
    : cat
      ? `<div class="opp-cat-badge" style="background:${cat.color}18;color:${cat.color};border-color:${cat.color}33"><span style="font-size:1rem">${cat.icon}</span><span>${cat.name}</span></div>`
      : '';

  return `
  <div class="rich-opp-card">
    <div class="rich-opp-card-top" style="border-right:4px solid ${acc}">
      <div class="rich-opp-card-title-row">
        <div class="rich-opp-card-title">${o.title}</div>
        <div class="rich-opp-card-actions">${applyBtn}</div>
      </div>
      ${catBadge}
      <div class="rich-opp-card-desc">${o.desc || '—'}</div>
      <div class="rich-opp-card-tags">
        <span class="rich-opp-tag ${stCls}">${stLabel}</span>
        <span class="rich-opp-tag ${typeCls}">${typeLabel}</span>
      </div>
    </div>
    <div class="rich-opp-card-bottom">
      <div class="rich-opp-stat">
        <i class="fa-solid fa-users" style="color:#2ab8d0"></i>
        <div class="rich-opp-stat-val teal">${o.seats > 0 ? o.seats : '—'}</div>
        <div class="rich-opp-stat-lbl">المقاعد</div>
      </div>
      <div class="rich-opp-stat">
        <i class="fa-regular fa-calendar" style="color:#d97706"></i>
        <div class="rich-opp-stat-val gold" style="font-size:0.78rem">${deadline}</div>
        <div class="rich-opp-stat-lbl">الموعد النهائي</div>
      </div>
      <div class="rich-opp-stat">
        <i class="fa-solid fa-location-dot" style="color:#7b4ea6"></i>
        <div class="rich-opp-stat-val" style="color:#7b4ea6;font-size:0.78rem">${o.city || '—'}</div>
        <div class="rich-opp-stat-lbl">المدينة</div>
      </div>
      <div class="rich-opp-stat">
        <i class="fa-solid fa-circle-dot" style="color:${o.status==='open'?'#059669':'#dc2626'}"></i>
        <div class="rich-opp-stat-val ${o.status==='open'?'green':'red'}">${stLabel}</div>
        <div class="rich-opp-stat-lbl">الحالة</div>
      </div>
    </div>
  </div>`;
}

let userOppTab = 'available';

function setOppTab(tab) {
  userOppTab = tab;
  document.querySelectorAll('[data-opp-t]').forEach(btn => {
    btn.classList.toggle('on', btn.getAttribute('data-opp-t') === tab);
  });
  renderUserOpps();
}

function renderUserOpps() {
  const grid = document.getElementById('user-all-opps-grid');
  if (!grid) return;
  const q = (document.getElementById('user-opp-search')?.value || '').toLowerCase();
  
  let cAvail = 0;
  let cActive = 0;
  let cExpired = 0;
  
  const availList = [];
  const activeList = [];
  const expiredList = [];
  
  opportunities.forEach(o => {
    const isApproved = requests.some(r => r.oppId === o.id && r.status === 'approved');
    if (isApproved) {
      cActive++;
      activeList.push(o);
    } else if (o.status === 'open') {
      cAvail++;
      availList.push(o);
    } else {
      cExpired++;
      expiredList.push(o);
    }
  });
  
  const nAvail = document.getElementById('n-opp-avail');
  const nActive = document.getElementById('n-opp-active');
  const nExpired = document.getElementById('n-opp-expired');
  if(nAvail) nAvail.textContent = cAvail;
  if(nActive) nActive.textContent = cActive;
  if(nExpired) nExpired.textContent = cExpired;
  
  let listToRender = userOppTab === 'available' ? availList : (userOppTab === 'active' ? activeList : expiredList);
  listToRender = listToRender.filter(o => !q || o.title.toLowerCase().includes(q));

  if (!listToRender.length) {
    let emptyMsg = userOppTab === 'available' ? 'لا توجد فرص تطوعية متاحة حالياً.' : (userOppTab === 'active' ? 'لا توجد مشاريع تطوعية نشطة حالياً.' : 'لا توجد فرص تطوعية منتهية.');
    let titleStr = userOppTab === 'active' ? 'مشاريع نشطة' : (userOppTab === 'expired' ? 'فرص منتهية' : 'الفرص المتاحة');
    grid.innerHTML = `<div class="ue-card" style="grid-column:1/-1"><div class="ue-header"><div class="ue-title">${titleStr}</div><button class="ue-refresh" onclick="location.reload()" title="تحديث"><i class="fa-solid fa-arrow-rotate-right"></i></button></div><div class="ue-body"><div class="ue-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div><div class="ue-msg">${emptyMsg}</div></div></div>`;
  } else {
    grid.innerHTML = listToRender.map(o => richOppCardUser(o)).join('');
  }
}

/* ══ RENDER CATEGORIES ══ */
function renderCats() {
  const grid = document.getElementById('cats-grid');
  const agrid = document.getElementById('assoc-cats-grid');

  const adminHtml = CATEGORIES.map(c => {
    const cnt = opportunities.filter(o => (o.catIds.includes(c.id) || o.catIds.includes('all')) && o.status === 'open').length;
    return `
    <div class="cat-card" style="--cc:${c.color}">
      <div class="cat-card-header" onclick="openCatAdmin('${c.id}')">
        <div class="cat-card-bg" style="background:${c.color}"></div>
        <span class="cat-card-icon">${c.icon}</span>
        <div class="cat-card-name">${c.name}</div>
        <div class="cat-card-desc">${c.desc}</div>
      </div>
      <div class="cat-card-footer">
        <div class="cat-opp-count" onclick="openCatAdmin('${c.id}')" style="cursor:pointer;flex:1">
          <div class="cat-opp-dot" style="background:${c.color}"></div>
          <span style="color:${c.color}">${cnt}</span> فرصة متاحة
        </div>
      </div>
    </div>`;
  }).join('');

  // Used by both 'association' role and 'user' role
  const applicantHtml = CATEGORIES.map(c => {
    const cnt = opportunities.filter(o => (o.catIds.includes(c.id) || o.catIds.includes('all')) && o.status === 'open').length;
    return `
    <div class="cat-card" style="--cc:${c.color}" onclick="openCatApplicant('${c.id}')">
      <div class="cat-card-header">
        <div class="cat-card-bg" style="background:${c.color}"></div>
        <span class="cat-card-icon">${c.icon}</span>
        <div class="cat-card-name">${c.name}</div>
        <div class="cat-card-desc">${c.desc}</div>
      </div>
      <div class="cat-card-footer">
        <div class="cat-opp-count">
          <div class="cat-opp-dot" style="background:${c.color}"></div>
          <span style="color:${c.color}">${cnt}</span> فرصة متاحة
        </div>
        <div class="cat-arrow">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M15 18l-6-6 6-6"/></svg>
        </div>
      </div>
    </div>`;
  }).join('');

  if (grid) grid.innerHTML = adminHtml;
  if (agrid) agrid.innerHTML = applicantHtml;

  // Populate category filter dropdown in the all-opps toolbar
  const catFilter = document.getElementById('all-opp-cat-filter');
  if (catFilter) {
    catFilter.innerHTML = '<option value="">جميع التصنيفات</option>' +
      CATEGORIES.map(c => `<option value="${c.id}">${c.icon} ${c.name}</option>`).join('');
  }

  // Update pill count badge
  const pillCount = document.getElementById('pill-cats-count');
  if (pillCount) pillCount.textContent = CATEGORIES.length || '';

  updateStats();
  renderAllAdminOpps();
}

/* ══ ADMIN TAB SWITCHER ══ */
function switchAdminTab(tab) {
  const panelOpps = document.getElementById('panel-all-opps');
  const panelCats = document.getElementById('panel-cats');
  const pillOpps  = document.getElementById('pill-opps');
  const pillCats  = document.getElementById('pill-cats');

  if (tab === 'opps') {
    if (panelOpps) panelOpps.style.display = '';
    if (panelCats) panelCats.style.display = 'none';
    if (pillOpps)  pillOpps.classList.add('active');
    if (pillCats)  pillCats.classList.remove('active');
    renderAllAdminOpps();
  } else {
    if (panelOpps) panelOpps.style.display = 'none';
    if (panelCats) panelCats.style.display = '';
    if (pillOpps)  pillOpps.classList.remove('active');
    if (pillCats)  pillCats.classList.add('active');
  }
}

/* ══ RENDER ALL ADMIN OPPORTUNITIES (rich cards) ══ */
function renderAllAdminOpps() {
  const grid = document.getElementById('all-admin-opps-grid');
  if (!grid) return;

  const q          = (document.getElementById('all-opp-search')?.value || '').toLowerCase();
  const catF       = document.getElementById('all-opp-cat-filter')?.value || '';
  const statusF    = document.getElementById('all-opp-status-filter')?.value || '';

  const list = opportunities.filter(o => {
    if (q       && !o.title.toLowerCase().includes(q)) return false;
    if (catF    && o.catId !== catF)                   return false;
    if (statusF && o.status !== statusF)               return false;
    return true;
  });

  grid.innerHTML = list.length
    ? list.map(o => richOppCardAdmin(o)).join('')
    : `<div class="empty-state"><span class="ei"><i class="fa-solid fa-magnifying-glass"></i></span><h3>لا توجد فرص</h3><p>جرّب تغيير معايير البحث أو أضف فرصة جديدة</p></div>`;
}

function richOppCardAdmin(o) {
  const cat      = CATEGORIES.find(c => c.id === o.catId);
  const acc      = cat?.color || '#2ab8d0';
  const appCnt   = requests.filter(r => r.oppId === o.id).length;
  const typeLabel= o.type === 'onsite' ? 'حضوري' : o.type === 'remote' ? 'عن بعد' : 'مزدوج';
  const typeCls  = o.type === 'onsite' ? 'rich-opp-tag-onsite' : 'rich-opp-tag-remote';
  const stCls    = o.status === 'open'  ? 'rich-opp-tag-open'   : 'rich-opp-tag-closed';
  const stLabel  = o.status === 'open'  ? 'مفتوحة'              : 'مغلقة';
  const deadline = o.deadline ? o.deadline.replace(/(\d{4})-(\d{2})-(\d{2})/, '$3/$2/$1') : '—';

  return `
  <div class="rich-opp-card">
    <div class="rich-opp-card-top" style="border-right:4px solid ${acc}">
      <div class="rich-opp-card-title-row">
        <div class="rich-opp-card-title">${o.title}</div>
        <div class="rich-opp-card-actions">
          <button class="icn-btn edit" onclick="openEditOpp(${o.id})" title="تعديل">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="icn-btn del" onclick="openDelOpp(${o.id})" title="حذف">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4h6v2"/></svg>
          </button>
        </div>
      </div>
      <div class="rich-opp-card-desc">${o.desc || '—'}</div>
      <div class="rich-opp-card-tags">
        <span class="rich-opp-tag ${stCls}">${stLabel}</span>
        <span class="rich-opp-tag ${typeCls}">${typeLabel}</span>
        ${cat ? `<span class="rich-opp-tag rich-opp-tag-cat">${cat.icon} ${cat.name}</span>` : ''}
      </div>
    </div>
    <div class="rich-opp-card-bottom">
      <div class="rich-opp-stat">
        <i class="fa-solid fa-users" style="color:#2ab8d0"></i>
        <div class="rich-opp-stat-val teal">${o.seats > 0 ? o.seats : '—'}</div>
        <div class="rich-opp-stat-lbl">المقاعد</div>
      </div>
      <div class="rich-opp-stat">
        <i class="fa-regular fa-calendar" style="color:#d97706"></i>
        <div class="rich-opp-stat-val gold" style="font-size:0.78rem">${deadline}</div>
        <div class="rich-opp-stat-lbl">الموعد النهائي</div>
      </div>
      <div class="rich-opp-stat">
        <i class="fa-solid fa-inbox" style="color:#7b4ea6"></i>
        <div class="rich-opp-stat-val" style="color:#7b4ea6">${appCnt}</div>
        <div class="rich-opp-stat-lbl">المتقدمون</div>
      </div>
      <div class="rich-opp-stat">
        <i class="fa-solid fa-circle-dot" style="color:${o.status==='open'?'#059669':'#dc2626'}"></i>
        <div class="rich-opp-stat-val ${o.status==='open'?'green':'red'}">${stLabel}</div>
        <div class="rich-opp-stat-lbl">الحالة</div>
      </div>
    </div>
  </div>`;
}

/* ══ VIEWS ══ */
function updateTopbar(title, crumb) {
  const t = document.getElementById('topbar-title');
  const c = document.getElementById('topbar-crumb');
  if (t) t.textContent = title;
  if (c) c.textContent = crumb;
}

function showView(id) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}

function showAdminMain() {
  showView('view-admin');
  updateTopbar('فرص التطوع', 'فرص التطوع');
}

function showAdminOppRequests() {
  showView('view-admin-reqs');
  updateTopbar('طلبات التقديم', 'طلبات التقديم');
  renderVolRequests();
}

function openCatAdmin(catId) {
  currentCatId = catId;
  const cat = CATEGORIES.find(c => c.id === catId);
  document.getElementById('ao-cat-icon').textContent = cat.icon;
  document.getElementById('ao-cat-name').textContent = cat.name;
  document.getElementById('ao-title').textContent = 'فرص ' + cat.name;
  document.getElementById('ao-sub').textContent = cat.desc;
  showView('view-admin-opps');
  updateTopbar(cat.name, cat.name);
  renderAdminOpps();
}

function openCatAssoc(catId) {
  currentCatId = catId;
  const cat = CATEGORIES.find(c => c.id === catId);
  document.getElementById('ao-cat-icon2').textContent = cat.icon;
  document.getElementById('ao-cat-name2').textContent = cat.name;
  document.getElementById('ao-title2').textContent = 'فرص ' + cat.name;
  showView('view-assoc-opps');
  updateTopbar(cat.name, cat.name);
  renderApplicantOpps();
}

// Unified opener for both association & user roles
function openCatApplicant(catId) {
  openCatAssoc(catId);
}

function backToCategories() {
  showView('view-admin');
  updateTopbar('فرص التطوع', 'فرص التطوع');
}

function backToAssocCats() {
  showView('view-assoc');
  updateTopbar('فرص التطوع', 'فرص التطوع');
}

function backToApplicantCats() {
  backToAssocCats();
}

/* ══ ADMIN OPPS ══ */
function renderAdminOpps() {
  const q = (document.getElementById('admin-opp-search')?.value || '').toLowerCase();
  const list = opportunities.filter(o => o.catIds && (o.catIds.includes(currentCatId) || o.catIds.includes('all')) && (!q || o.title.toLowerCase().includes(q)));
  const grid = document.getElementById('admin-opps-grid');
  grid.innerHTML = list.length
    ? list.map(o => oppCardAdmin(o)).join('')
    : `<div class="empty-state"><span class="ei"><i class="fa-solid fa-magnifying-glass"></i></span><h3>لا توجد فرص في هذا التصنيف</h3><p>اضغط "إضافة فرصة" لإنشاء أول فرصة</p></div>`;
}

function oppCardAdmin(o) {
  const isAll = o.catIds && o.catIds.includes('all');
  const cat = (!isAll && o.catIds && o.catIds.length) ? CATEGORIES.find(c => c.id === o.catIds[0]) : null;
  const acc = cat?.color || '#2ab8d0';
  const typeLabel = o.type === 'onsite' ? 'حضوري' : o.type === 'remote' ? 'عن بعد' : 'مزدوج';
  const typeBadge = o.type === 'onsite' ? 'b-onsite' : 'b-remote';
  const appCnt = requests.filter(r => r.oppId === o.id).length;
  return `
  <div class="opp-card">
    <div class="opp-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
    <div class="opp-body">
      <div class="opp-row1">
        <div class="opp-badges">
          <span class="badge ${o.status === 'open' ? 'b-open' : 'b-closed'}">${o.status === 'open' ? 'مفتوحة' : 'مغلقة'}</span>
          <span class="badge ${typeBadge}">${typeLabel}</span>
        </div>
        <div class="opp-actions">
          <button class="icn-btn edit" onclick="openEditOpp(${o.id})" title="تعديل">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="icn-btn del" onclick="openDelOpp(${o.id})" title="حذف">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4h6v2"/></svg>
          </button>
        </div>
      </div>
      <div class="opp-title">${o.title}</div>
      <div class="opp-desc">${o.desc}</div>
      <div class="opp-meta">
        ${o.city ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${o.city}</div>` : ''}
        <div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>${o.seats} مقعد</div>
        ${o.deadline ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>حتى ${o.deadline}</div>` : ''}
      </div>
    </div>
    <div class="opp-foot">
      <div class="opp-org"><div class="org-av">${o.org[0]}</div><div><div class="org-name">${o.org}</div></div></div>
      <div style="font-size:0.76rem;font-weight:700;color:var(--teal);background:rgba(42,184,208,0.1);padding:4px 10px;border-radius:8px">${appCnt} طلب</div>
    </div>
  </div>`;
}

/* ══ ASSOC / USER OPPS ══ */
function renderAssocOpps() { renderApplicantOpps(); }
function renderApplicantOpps() {
  const q = (document.getElementById('assoc-opp-search')?.value || '').toLowerCase();
  const list = opportunities.filter(o => (o.catIds.includes(currentCatId) || o.catIds.includes('all')) && o.status === 'open' && (!q || o.title.toLowerCase().includes(q)));
  const grid = document.getElementById('assoc-opps-grid');
  const isUser = document.querySelector('.readonly-notice') || window.location.pathname.includes('/user/');
  grid.innerHTML = list.length
    ? list.map(o => oppCardAssoc(o)).join('')
    : (isUser 
        ? `<div class="ue-card" style="grid-column:1/-1"><div class="ue-header"><div class="ue-title">فرص التطوع</div><button class="ue-refresh" onclick="location.reload()" title="تحديث"><i class="fa-solid fa-arrow-rotate-right"></i></button></div><div class="ue-body"><div class="ue-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div><div class="ue-msg">عذراً، خدمة التطوع غير متاحة حالياً، أو لا توجد فرص تطوعية مطروحة.</div></div></div>`
        : `<div class="empty-state"><span class="ei"><i class="fa-solid fa-magnifying-glass"></i></span><h3>لا توجد فرص متاحة</h3><p>لا توجد فرص مفتوحة في هذا التصنيف حالياً</p></div>`);
}

function oppCardAssoc(o) {
  const isAll = o.catIds && o.catIds.includes('all');
  const cat = (!isAll && o.catIds && o.catIds.length) ? CATEGORIES.find(c => c.id === o.catIds[0]) : null;
  const acc = cat?.color || '#2ab8d0';
  const myReq = requests.find(r => r.oppId === o.id);
  const typeLabel = o.type === 'onsite' ? 'حضوري' : o.type === 'remote' ? 'عن بعد' : 'مزدوج';
  const typeBadge = o.type === 'onsite' ? 'b-onsite' : 'b-remote';

  // Extract link
  let oppLink = '';
  if (o.desc && o.desc.includes('رابط الفرصة: ')) {
    oppLink = o.desc.split('رابط الفرصة: ')[1].split('\n')[0].trim();
  }

  let footBtn = '';
  if (o.has_applied || myReq) {
    let reqStatus = myReq ? myReq.status : 'pending';
    if (reqStatus === 'pending') footBtn = `<span class="btn-applied">تحت المراجعة</span>`;
    else if (reqStatus === 'approved') footBtn = `<span class="btn-applied" style="color:var(--green)">مقبول</span>`;
    else footBtn = `<span class="btn-applied" style="color:var(--red)">مرفوض</span>`;
  } else {
    if (oppLink) {
      footBtn = `<button class="btn-apply" onclick="window.open('${oppLink}', '_blank')"><i class="fa-solid fa-arrow-up-right-from-square" style="margin-left:5px"></i>تقديم طلب</button>`;
    } else {
      footBtn = `<button class="btn-apply" onclick="openApply(${o.id})">تقديم طلب</button>`;
    }
  }

  // Build category badge for assoc card
  const assocCatBadge = isAll
    ? `<div class="opp-cat-badge" style="background:rgba(30,64,175,0.09);color:#1e40af;border-color:rgba(30,64,175,0.18);margin-bottom:8px"><span style="font-size:.95rem">🌐</span><span>لكل الجمعيات</span></div>`
    : cat
      ? `<div class="opp-cat-badge" style="background:${cat.color}18;color:${cat.color};border-color:${cat.color}33;margin-bottom:8px"><span style="font-size:.95rem">${cat.icon}</span><span>${cat.name}</span></div>`
      : '';

  return `
  <div class="opp-card">
    <div class="opp-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
    <div class="opp-body">
      <div class="opp-row1">
        <div class="opp-badges">
          <span class="badge b-open">مفتوحة</span>
          <span class="badge ${typeBadge}">${typeLabel}</span>
        </div>
      </div>
      ${assocCatBadge}
      <div class="opp-title">${o.title}</div>
      <div class="opp-desc">${o.desc}</div>
      <div class="opp-meta">
        ${o.city ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${o.city}</div>` : ''}
        <div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>${o.seats} مقعد</div>
        ${o.deadline ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>حتى ${o.deadline}</div>` : ''}
      </div>
    </div>
    <div class="opp-foot">
      <div class="opp-org"><div class="org-av">${o.org[0]}</div><div><div class="org-name">${o.org}</div></div></div>
      ${footBtn}
    </div>
  </div>`;
}

/* ══ REQUESTS ══ */
function filterReqs(f) {
  reqFilter = f;
  ['pending', 'approved', 'rejected'].forEach(x =>
    document.getElementById('rtab-' + x).classList.toggle('active', x === f)
  );
  renderVolRequests();
}

function renderVolRequests() {
  const filtered = requests.filter(r => r.status === reqFilter);
  const list = document.getElementById('req-list');
  if (!filtered.length) {
    list.innerHTML = `<div class="empty-state"><span class="ei"></span><h3>لا توجد طلبات ${reqFilter === 'pending' ? 'معلقة' : reqFilter === 'approved' ? 'مقبولة' : 'مرفوضة'}</h3></div>`;
    return;
  }
  list.innerHTML = filtered.map(r => {
    const opp = opportunities.find(o => o.id === r.oppId);
    const cat = opp && opp.catIds && opp.catIds.length && opp.catIds[0] !== 'all' ? CATEGORIES.find(c => c.id === opp.catIds[0]) : null;
    const catName = opp && opp.catIds && opp.catIds.includes('all') ? 'للجميع' : (cat ? cat.name : '—');
    const catIcon = opp && opp.catIds && opp.catIds.includes('all') ? '<i class="fa-solid fa-users"></i>' : (cat ? cat.icon : '');
    const barColor = r.status === 'pending' ? '#f59e0b' : r.status === 'approved' ? '#2eaa78' : '#c62828';
    const statusBadge = r.status === 'pending'
      ? `<span class="req-status-badge rsb-pending">معلق</span>`
      : r.status === 'approved'
        ? `<span class="req-status-badge rsb-approved">مقبول</span>`
        : `<span class="req-status-badge rsb-rejected">مرفوض</span>`;
    const actionBtns = r.status === 'pending'
      ? `<button class="btn-approve" onclick="approveReq(${r.id})">قبول</button>
         <button class="btn-reject"  onclick="rejectReq(${r.id})">رفض</button>`
      : '';
    return `
    <div class="req-card">
      <div class="req-card-inner">
        <div class="req-status-bar" style="background:${barColor}"></div>
        <div class="req-opp-info">
          <div class="req-opp-title">${opp?.title || '—'}</div>
          <div class="req-opp-cat">${catIcon} ${catName}</div>
        </div>
        <div class="req-assoc-info">
          <div class="req-assoc-av">${r.assocName[0]}</div>
          <div>
            <div class="req-assoc-name">${r.assocName}</div>
            <div class="req-assoc-sub">${r.assocCity || '—'}</div>
          </div>
        </div>
        <div class="req-meta">
          <div class="req-date">${r.date}</div>
          ${statusBadge}
        </div>
        <div class="req-actions-col">${actionBtns}</div>
      </div>
      ${r.message ? `<div style="padding:0 20px 14px 20px;font-size:0.8rem;color:var(--muted);border-top:1px solid var(--border);margin-top:0;padding-top:10px">"${r.message}"</div>` : ''}
    </div>`;
  }).join('');
  updateReqCounts();
}

function approveReq(id) {
  fetch(`/api/opportunity-requests/${id}/approve`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    }
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        volToast('', 'تم قبول الطلب بنجاح');
        fetchRequests();
      }
    });
}

function rejectReq(id) {
  const reason = prompt("يرجى إدخال سبب الرفض:");
  if (!reason || reason.trim() === '') {
    volToast('', 'سبب الرفض مطلوب');
    return;
  }

  fetch(`/api/opportunity-requests/${id}/reject`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    },
    body: JSON.stringify({ notes: reason })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        volToast('', 'تم رفض الطلب');
        fetchRequests();
      } else {
        volToast('', data.message || 'حدث خطأ');
      }
    });
}

/* ══ ADD / EDIT OPP ══ */
function openAddOpp(mode) {
  editingOppId = null;
  // When called globally, default to 'فرصة للجميع' if it exists, otherwise the first category
  const isGlobal = (mode === 'global' || !currentCatId);
  if (isGlobal && CATEGORIES.length > 0) {
    const oppForAll = CATEGORIES.find(c => c.name === 'فرصة للجميع');
    currentCatId = oppForAll ? oppForAll.id : CATEGORIES[0].id;
  }
  const cat = CATEGORIES.find(c => c.id === currentCatId) || CATEGORIES[0];
  if (!cat) { volToast('', 'يرجى إضافة تصنيف أولاً'); return; }
  
  document.getElementById('opp-m-icon').innerHTML = '<i class="fa-solid fa-star"></i>';
  document.getElementById('opp-m-title').textContent = 'إضافة فرصة تطوع';
  document.getElementById('opp-m-sub').textContent = isGlobal ? 'أضف تفاصيل الفرصة أدناه' : 'فرصة جديدة في تصنيف: ' + cat.name;
  document.getElementById('opp-save-lbl').innerHTML = '<i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ الفرصة';
  
  const badgeWrap = document.getElementById('opp-cat-badge-wrap');
  const catSelectWrap = document.getElementById('fg-opp-cat');
  const badge = document.getElementById('sel-cat-badge');
  
  // Clear form FIRST before initializing CatPicker
  clearOppForm();

  if (isGlobal) {
    if (badgeWrap) badgeWrap.style.display = 'none';
    if (catSelectWrap) catSelectWrap.style.display = 'block';
    if (window.fCatChoices) { try { window.fCatChoices.destroy(); } catch(e){} }
    window.fCatChoices = new CatPicker({
      containerId : 'f-opp-cat-picker',
      hiddenId    : 'f-opp-cat',
      categories  : CATEGORIES,
      selected    : [],
      multi       : true,
    });
  } else {
    // Read-only UI: show the selected category as a badge (no dropdown to change it).
    if (badgeWrap) badgeWrap.style.display = 'block';
    if (catSelectWrap) catSelectWrap.style.display = 'none';
    if (badge) {
      badge.innerHTML = `${cat.name} <span style="margin-right:6px">${cat.icon}</span>`;
      badge.style.background = cat.color + '1A';
      badge.style.borderColor = cat.color + '33';
      badge.style.color = cat.color;
    }
  }

  openOv('ov-opp');
}

function openEditOpp(id) {
  const o = opportunities.find(x => x.id === id);
  if (!o) return;
  editingOppId = id;
  const cat = o.catIds && o.catIds.length ? CATEGORIES.find(c => c.id === o.catIds[0]) : null;
  document.getElementById('opp-m-icon').textContent = '✏️';
  document.getElementById('opp-m-title').textContent = 'تعديل الفرصة';
  document.getElementById('opp-m-sub').textContent = o.title;
  document.getElementById('opp-save-lbl').innerHTML = '<i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ التعديلات';
  
  const badgeWrap = document.getElementById('opp-cat-badge-wrap');
  const catSelectWrap = document.getElementById('fg-opp-cat');
  if (badgeWrap) badgeWrap.style.display = 'none';
  if (catSelectWrap) catSelectWrap.style.display = 'block';

  if (window.fCatChoices) { try { window.fCatChoices.destroy(); } catch(e){} }
  window.fCatChoices = new CatPicker({
    containerId : 'f-opp-cat-picker',
    hiddenId    : 'f-opp-cat',
    categories  : CATEGORIES,
    selected    : o.catIds ? o.catIds.filter(c => c !== '__none__') : [],
    multi       : true,
  });
  
  document.getElementById('f-opp-title').value = o.title;
  // Extract clean description if the suffix was added
  let cleanDesc = o.desc;
  let extractedLink = '';
  
  if (o.desc.includes('رابط الفرصة: ')) {
      extractedLink = o.desc.split('رابط الفرصة: ')[1].split('\n')[0].trim();
  }
  
  if (cleanDesc.includes('الجهة المستضيفة:')) {
    cleanDesc = cleanDesc.split('\n\nالجهة المستضيفة:')[0].replace(/\\n\\nالجهة المستضيفة:.*/, '').trim();
  }
  document.getElementById('f-opp-desc').value = cleanDesc;
  
  const linkEl = document.getElementById('f-opp-link');
  if (linkEl) linkEl.value = extractedLink;
  
  // Also try to extract city from the suffix
  let extractedCity = o.city;
  if (o.desc.includes(' - ') && !o.desc.includes('رابط الفرصة: ')) {
      extractedCity = o.desc.split(' - ').pop();
  } else if (o.desc.includes(' - ') && o.desc.includes('رابط الفرصة: ')) {
      extractedCity = o.desc.split('رابط الفرصة:')[0].split(' - ').pop().trim();
  }
  
  document.getElementById('f-opp-org').value = o.org;
  document.getElementById('f-opp-city').value = extractedCity;
  document.getElementById('f-opp-seats').value = o.seats > 0 ? o.seats : parseInt(o.requirements) || '';
  document.getElementById('f-opp-deadline').value = o.deadline;
  document.getElementById('f-opp-type').value = o.type;
  document.getElementById('f-opp-status').value = o.status;
  openOv('ov-opp');
}

function clearOppForm() {
  ['f-opp-title', 'f-opp-desc', 'f-opp-org', 'f-opp-city', 'f-opp-seats', 'f-opp-deadline', 'f-opp-link'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('f-opp-type').value = 'onsite';
  document.getElementById('f-opp-status').value = 'open';
}

function saveOpp() {
  const title = document.getElementById('f-opp-title').value.trim();
  const desc = document.getElementById('f-opp-desc').value.trim();
  const org = document.getElementById('f-opp-org').value.trim();
  const city = document.getElementById('f-opp-city').value.trim();
  const seats = document.getElementById('f-opp-seats').value.trim();
  const link = document.getElementById('f-opp-link')?.value.trim() || '';

  // Fix literal \n issue and avoid duplicating the appended string on edit
  let fullDesc = desc;
  if (!editingOppId || !fullDesc.includes('الجهة المستضيفة:')) {
    fullDesc = desc + '\n\nالجهة المستضيفة: ' + org + (city ? ' - ' + city : '');
    if (link) fullDesc += '\nرابط الفرصة: ' + link;
  } else if (editingOppId && fullDesc.includes('الجهة المستضيفة:')) {
    // If editing, try to replace the existing org/city suffix with the new one
    fullDesc = desc.replace(/\n\nالجهة المستضيفة: .*/g, '') + '\n\nالجهة المستضيفة: ' + org + (city ? ' - ' + city : '');
    if (link) fullDesc += '\nرابط الفرصة: ' + link;
  }

  const isGlobal = document.getElementById('fg-opp-cat') && document.getElementById('fg-opp-cat').style.display !== 'none';
  let selectedType = currentCatId;
  if (isGlobal && window.fCatChoices) {
    const vals = window.fCatChoices.getValues();
    selectedType = Array.isArray(vals) ? vals.join(',') : (vals || currentCatId);
  } else if (isGlobal) {
    const h = document.getElementById('f-opp-cat');
    if (h) selectedType = h.value || currentCatId;
  }

  const payload = {
    title: title,
    description: fullDesc,
    type: selectedType,
    requirements: seats ? seats + ' مقاعد' : 'غير محدد',
    deadline: document.getElementById('f-opp-deadline').value,
    direction: document.getElementById('f-opp-type').value === 'remote' ? 'international' : 'local'
  };

  const url = editingOppId ? `/api/opportunities/${editingOppId}` : '/api/opportunities';
  const method = editingOppId ? 'PUT' : 'POST';

  fetch(url, {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      volToast('', data.message);
      closeOv('ov-opp');
      fetchOpportunities();
    } else {
      volToast('', 'حدث خطأ أثناء الحفظ');
    }
  })
  .catch(err => {
    console.error(err);
    volToast('', 'حدث خطأ في الاتصال بالخادم');
  });
}

/* ══ DELETE OPP ══ */
function openDelOpp(id) { deletingOppId = id; openOv('ov-del'); }

function doDelete() {
  fetch(`/api/opportunities/${deletingOppId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': getCsrfToken() }
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        volToast('', data.message);
        closeOv('ov-del');
        fetchOpportunities();
      }
    });
}

/* ══ APPLY ══ */
function openApply(oppId) {
  applyingOppId = oppId;
  const o = opportunities.find(x => x.id === oppId);
  const c = CATEGORIES.find(x => x.id === o.catId);
  document.getElementById('apply-opp-title').textContent = o.title;
  document.getElementById('apply-opp-org').textContent = o.org;
  document.getElementById('apply-opp-cat').textContent = c.icon + ' ' + c.name;
  document.getElementById('f-apply-msg').value = '';
  // Set applicant name & label
  const nameInput = document.getElementById('f-apply-assoc');
  const nameLabel = document.getElementById('apply-name-label');
  if (nameInput) nameInput.value = window.AppApplicantName || '';
  if (nameLabel) nameLabel.innerHTML = (window.AppApplicantLabel || 'اسم المتقدم') + ' <span class="req-span">*</span>';
  openOv('ov-apply');
}

function submitApply() {
  const notes = document.getElementById('f-apply-msg')?.value?.trim() || null;
  fetch(`/api/opportunities/${applyingOppId}/apply`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    },
    body: JSON.stringify({ notes })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      volToast('', data.message);
      closeOv('ov-apply');
      fetchOpportunities();
      fetchRequests();
    } else {
      volToast('', data.message);
    }
  })
  .catch(err => {
    console.error(err);
    volToast('', 'حدث خطأ في الاتصال بالخادم');
  });
}

/* ══ STATS ══ */
function updateStats() {
  const open = opportunities.filter(o => o.status === 'open').length;
  const pending = requests.filter(r => r.status === 'pending').length;
  const catCount = CATEGORIES.length;
  document.getElementById('st-total').textContent = opportunities.length;
  document.getElementById('st-open').textContent = open;
  document.getElementById('st-pending').textContent = pending;
  const stCats = document.getElementById('st-cats');
  if (stCats) stCats.textContent = catCount;
  const astCats = document.getElementById('ast-cats');
  if (astCats) astCats.textContent = catCount;
  const nbOppsEl = document.getElementById('nb-opps');
  if (nbOppsEl) nbOppsEl.textContent = open > 0 ? open : '';
  const hdrBadge = document.getElementById('hdr-req-badge');
  if (hdrBadge) hdrBadge.textContent = pending;
  updateReqCounts();
  updateApplicantStats();
  renderAllAdminOpps();
}

function updateReqCounts() {
  ['pending', 'approved', 'rejected'].forEach(s => {
    const el = document.getElementById('rc-' + s);
    if (el) el.textContent = requests.filter(r => r.status === s).length;
  });
}

function updateApplicantStats() {
  const myApproved = requests.filter(r => r.status === 'approved').length;
  const open = opportunities.filter(o => o.status === 'open').length;
  const astTotal    = document.getElementById('ast-total');
  const astApplied  = document.getElementById('ast-applied');
  const astApproved = document.getElementById('ast-approved');
  if (astTotal)    astTotal.textContent    = open;
  if (astApplied)  astApplied.textContent  = requests.length;
  if (astApproved) astApproved.textContent = myApproved;
}

// Backward-compat alias kept so any stray calls don't crash
function updateAssocStats() { updateApplicantStats(); }

/* ══ NOTIFICATIONS ══ */
// toggleNotifs / showNotifBanner — defined once in menu.js to avoid redefinition conflicts

/* ══ OVERLAY ══ */
function openOv(id) { document.getElementById(id).classList.add('open'); }
function closeOv(id) { document.getElementById(id).classList.remove('open'); }
function bgClose(e, id) { if (e.target === document.getElementById(id)) closeOv(id); }

/* ══ TOAST ══ */
let tTimer;
function volToast(icon, msg) {
  const el = document.getElementById('toast');
  document.getElementById('t-icon').textContent = icon;
  document.getElementById('t-msg').textContent = msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer = setTimeout(() => el.classList.remove('show'), 3200);
}

/* ══ MEETINGS PANEL ══ */
/* ══ SERVICES SUBMENU — defined once in menu.js to avoid redefinition conflicts ══ */

/* ══ CONTACT ══ */
function sendContactMsg() {
  volToast('', 'تم إرسال رسالتك! سنردّ خلال 24 ساعة');
}

/* ══ KEYBOARD ══ */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') ['ov-opp', 'ov-apply', 'ov-del'].forEach(closeOv);
});

async function fetchCategories() {
  try {
    const res = await fetch('/api/association-categories', {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    CATEGORIES = (data.categories || []).map(c => ({
      id: String(c.id),
      // support legacy textual IDs mapping for old DB records temporarily
      legacyId: c.name === 'خيرية واجتماعية' ? 'charity' : 
                c.name === 'الجمعيات القرآنية' ? 'quran' : 
                c.name === 'صحية وبيئية' ? 'health' : 
                c.name === 'ثقافية وتعليمية' ? 'culture' : 
                c.name === 'رياضية وشبابية' ? 'sports' : 
                c.name === 'دينية ودعوية' ? 'religious' : null,
      name: c.name,
      icon: c.icon || '',
      color: c.color || '#2ab8d0',
      desc: c.description || c.name
    })).sort((a, b) => {
      if (a.name === 'فرصة للجميع') return -1;
      if (b.name === 'فرصة للجميع') return 1;
      return 0;
    });
  } catch (e) {
    console.error("Error fetching categories", e);
  }
}

/* ══ INIT ══ */
document.addEventListener('DOMContentLoaded', async () => {
  await fetchCategories();
  fetchOpportunities();
  fetchRequests();
});
