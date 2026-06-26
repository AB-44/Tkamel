/**
 * meetings.js — Admin Meetings Page
 * Full API integration with Laravel backend
 */
const CSRF  = document.querySelector('meta[name="csrf-token"]')?.content || '';
const TODAY = new Date().toISOString().split('T')[0];

let meetings    = [];
let categories  = [];
let editingId   = null;
let deletingId  = null;
let cancelingId = null;
let viewingId   = null;
let attendeesId = null;
let mType       = 'online';
let typeFilter  = 'all';

/* ─── helpers ─────────────────────────────────────────── */
function renderCatBadges(catStr) {
  if (!catStr) return '<span class="badge" style="background:#e2e8f0;color:#64748b;font-size:0.65rem">📁 بدون تصنيف</span>';
  if (catStr === 'all') return '<span class="badge" style="background:#f0f9ff;color:#0ea5e9;border:1px solid #bae6fd;font-size:0.65rem">🌐 لكل الجمعيات</span>';

  const parts = catStr.split(',').map(s => s.trim()).filter(Boolean);
  return parts.map(p => {
    // try find by ID first, then by name for legacy data
    const c = categories.find(x => x.id === p || x.name === p);
    if (c) {
      return `<span class="badge" style="background:${c.color}15; color:${c.color}; border:1px solid ${c.color}40; font-size:0.65rem">${c.icon || '📁'} ${c.name}</span>`;
    }
    return `<span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.65rem">📁 ${p}</span>`;
  }).join(' ');
}

function isCurrent(m) { 
  if (isCancelled(m)) return false;
  return m.status === 'upcoming';
}
function isPast(m) { 
  if (isCancelled(m)) return false;
  return m.status === 'past';
}
function isCancelled(m){ return m.status === 'cancelled' || m.status === 'canceled'; }

function fmtDate(d) {
  if (!d) return '—';
  return new Date(d + 'T00:00:00').toLocaleDateString('ar-SA', { weekday:'short', year:'numeric', month:'long', day:'numeric' });
}
function fmtDateShort(d) {
  if (!d) return { day:'—', month:'' };
  const dt = new Date(d + 'T00:00:00');
  return { day: dt.getDate(), month: dt.toLocaleDateString('ar-SA', { month:'short' }) };
}
function ini(n) { const p = (n||'').trim().split(' '); return p.length >= 2 ? (p[0][0]||'') + (p[1][0]||'') : (n||'?')[0]; }
function domainShort(u) { try { return new URL(u).hostname.replace('www.',''); } catch { return u; } }

/* ─── API ─────────────────────────────────────────────── */
async function loadCategories() {
  try {
    const res = await fetch('/api/association-categories', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    categories = (data.categories || []).map(c => ({
      id: String(c.id), name: c.name, icon: c.icon || '📁', color: c.color || '#2ab8d0'
    }));
    populateCategoryFilters();
  } catch (e) { console.error('Failed to load categories', e); }
}

function populateCategoryFilters() {
  const catFilter = document.getElementById('catFilter');
  if (catFilter) {
    catFilter.innerHTML = '<option value="">كل التصنيفات</option>' + categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
  }
  const fCat = document.getElementById('f-cat');
  if (document.getElementById('f-cat-picker')) {
    if (window.catChoices) { try { window.catChoices.destroy(); } catch(e){} }
    window.catChoices = new CatPicker({
      containerId : 'f-cat-picker',
      hiddenId    : 'f-cat',
      categories  : categories,
      selected    : [],
      multi       : true,
    });
  }
  const fInv = document.getElementById('f-invitation');
  if (fInv) {
    fInv.innerHTML = '<option value="عام">عام (جميع الجمعيات)</option>' + 
                     '<option value="تقني">تقني (الجمعيات التقنية)</option>' + 
                     '<option value="ميداني">ميداني (الجمعيات الميدانية)</option>' + 
                     categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
  }
}

async function loadMeetings() {
  try {
    const res  = await fetch('/api/meetings', { credentials:'same-origin', headers:{ Accept:'application/json' } });
    if (!res.ok) throw new Error();
    const data = await res.json();
    meetings = Array.isArray(data) ? data : (data.meetings || []);
    renderAll();
  } catch {
    showToast('⚠️','فشل تحميل الاجتماعات');
  }
}

/* ─── filter ──────────────────────────────────────────── */
function getFiltered() {
  const q  = (document.getElementById('searchInput')?.value||'').trim().toLowerCase();
  const ct = document.getElementById('catFilter')?.value || '';
  return meetings.filter(m => {
    const mq = !q || m.title.toLowerCase().includes(q) || (m.presenter||'').toLowerCase().includes(q);
    const mc = !ct || m.cat === ct;
    const mt = typeFilter === 'all' || m.type === typeFilter;
    return mq && mc && mt;
  });
}

/* ─── render ──────────────────────────────────────────── */
function renderAll() {
  const list = getFiltered();
  const cur  = list.filter(isCurrent).sort((a,b) => a.date.localeCompare(b.date));
  const past = list.filter(isPast).sort((a,b) => b.date.localeCompare(a.date));
  const canc = list.filter(isCancelled).sort((a,b) => b.date.localeCompare(a.date));

  document.getElementById('bc-cur').textContent  = cur.length;
  document.getElementById('bc-past').textContent = past.length;
  document.getElementById('bc-canc').textContent = canc.length;

  document.getElementById('grid-cur').innerHTML = cur.length
    ? cur.map(m => fullCard(m)).join('')
    : '<div class="empty"><span class="empty-emoji">📋</span><h3>لا توجد اجتماعات حالية</h3><p>أنشئ اجتماعاً جديداً أو عدّل معايير البحث</p></div>';

  document.getElementById('list-past').innerHTML = past.length
    ? past.map(m => compactRow(m, false)).join('')
    : '<div class="compact-empty"><span class="compact-empty-emoji">📁</span><p>لا توجد اجتماعات سابقة</p></div>';

  document.getElementById('list-canc').innerHTML = canc.length
    ? canc.map(m => compactRow(m, true)).join('')
    : '<div class="compact-empty"><span class="compact-empty-emoji">✅</span><p>لا توجد اجتماعات ملغاة</p></div>';

  updateStats();
}

/* ─── full card ────────────────────────────────────────── */
function fullCard(m) {
  let catColor = '#2ab8d0';
  if (m.cat && m.cat !== 'all') {
    const firstCatId = m.cat.split(',')[0].trim();
    const firstCatObj = categories.find(c => c.id === firstCatId || c.name === firstCatId);
    if (firstCatObj) catColor = firstCatObj.color || '#2ab8d0';
  }
  const acc = catColor;
  const tLabel = m.type === 'online' ? '💻 عن بعد' : '📍 حضوري';
  const tBadge = m.type === 'online' ? 'b-online' : 'b-onsite';
  const linkRow = (m.type === 'online' && m.link)
    ? `<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg><a class="link-pill" href="${m.link}" target="_blank">🔗 ${domainShort(m.link)}</a></div>` : '';
  return `
  <div class="meeting-card">
    <div class="card-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
    <div class="card-inner">
      <div class="card-row1">
        <div class="card-badges">
          <span class="badge ${tBadge}">${tLabel}</span>
          ${renderCatBadges(m.cat)}
        </div>
        <div class="card-actions">
          <button class="icn-btn attendees-btn" onclick="event.stopPropagation(); openAttendees(${m.id})" title="الحاضرون" style="position:relative">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            ${(m.attendee_count||0) > 0 ? `<span style="position:absolute;top:-5px;left:-5px;background:#22c55e;color:#fff;border-radius:50%;width:16px;height:16px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;">${m.attendee_count}</span>` : ''}
          </button>
          <button class="icn-btn edit" onclick="openEdit(${m.id})" title="تعديل"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
          <button class="icn-btn cancel-btn" onclick="openCancel(${m.id})" title="إلغاء"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>
          <button class="icn-btn del" onclick="openDelete(${m.id})" title="حذف"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>
        </div>
      </div>
      <div class="card-title">${m.title}</div>
      <div class="card-meta">
        <div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>${fmtDate(m.date)}${m.time ? ' — ' + m.time : ''}</div>
        ${m.location ? `<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${m.location}</div>` : ''}
        ${linkRow}
      </div>
    </div>
    <div class="card-foot">
      <div class="presenter">
        <div class="p-av">${ini(m.presenter)}</div>
        <div><div class="p-name">${m.presenter}</div><div class="p-role">المقدم</div></div>
      </div>
      <button class="btn-view" onclick="openDetails(${m.id})">التفاصيل</button>
    </div>
  </div>`;
}

/* ─── compact row ──────────────────────────────────────── */
function compactRow(m, isCnc) {
  let catColor = '#2ab8d0';
  if (m.cat && m.cat !== 'all') {
    const firstCatId = m.cat.split(',')[0].trim();
    const firstCatObj = categories.find(c => c.id === firstCatId || c.name === firstCatId);
    if (firstCatObj) catColor = firstCatObj.color || '#2ab8d0';
  }
  const acc = isCnc ? '#c62828' : catColor;
  const ds  = fmtDateShort(m.date);
  const tLabel = m.type === 'online' ? '💻 عن بعد' : '📍 حضوري';
  const hasReport = !isCnc && m.report;
  const actionBtns = isCnc
    ? `<button class="icn-btn attendees-btn" onclick="event.stopPropagation(); openAttendees(${m.id})" title="الحاضرون" style="position:relative">
         <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
         ${(m.attendee_count||0) > 0 ? `<span style="position:absolute;top:-5px;left:-5px;background:#22c55e;color:#fff;border-radius:50%;width:16px;height:16px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;">${m.attendee_count}</span>` : ''}
       </button>
       <button class="icn-btn edit" onclick="openEdit(${m.id})" title="تعديل"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
       <button class="icn-btn del" onclick="openDelete(${m.id})" title="حذف"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>`
    : `<button class="icn-btn attendees-btn" onclick="event.stopPropagation(); openAttendees(${m.id})" title="الحاضرون" style="position:relative">
         <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
         ${(m.attendee_count||0) > 0 ? `<span style="position:absolute;top:-5px;left:-5px;background:#22c55e;color:#fff;border-radius:50%;width:16px;height:16px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;">${m.attendee_count}</span>` : ''}
       </button>
       <button class="icn-btn edit" onclick="openEdit(${m.id})" title="تعديل / إضافة تقرير"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
       <button class="icn-btn del" onclick="openDelete(${m.id})" title="حذف"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>`;
  return `
  <div class="compact-item${isCnc?' cancelled-row':''}">
    <div class="ci-bar" style="background:${acc}"></div>
    <div class="ci-date"><span class="ci-day">${ds.day}</span><span class="ci-month">${ds.month}</span></div>
    <div class="ci-body">
      <div class="ci-title">${m.title}</div>
      <div class="ci-meta">
        <div class="ci-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>${m.presenter}</div>
        <div class="ci-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>${m.time||'—'}${m.end_time ? ' - ' + m.end_time : ''}</div>
        <div class="ci-meta-item" style="color:var(--teal)">${tLabel}</div>
        ${isCnc && m.cancelReason ? `<div class="ci-meta-item" style="color:var(--red)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>${m.cancelReason.substring(0,40)}…</div>` : ''}
      </div>
    </div>
    <div class="ci-badges">
      ${renderCatBadges(m.cat)}
      ${isCnc ? '<span class="ci-cancelled-badge">🚫 ملغي</span>' : ''}
      ${hasReport ? '<span class="badge b-has-report" style="font-size:0.65rem">📋 تقرير</span>' : ''}
    </div>
    <div class="ci-actions">
      ${actionBtns}
      <button class="icn-btn" onclick="openDetails(${m.id})" title="التفاصيل"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></button>
    </div>
  </div>`;
}

/* ─── stats ────────────────────────────────────────────── */
function updateStats() {
  document.getElementById('s-total').textContent  = meetings.length;
  document.getElementById('s-cur').textContent    = meetings.filter(isCurrent).length;
  document.getElementById('s-past').textContent   = meetings.filter(isPast).length;
  document.getElementById('s-canc').textContent   = meetings.filter(isCancelled).length;
  document.getElementById('s-online').textContent = meetings.filter(m => m.type === 'online').length;
}

/* ─── collapsible sections ─────────────────────────────── */
const secState = { past:true, canc:true };
function toggleSec(key) {
  secState[key] = !secState[key];
  const el  = document.getElementById('sec-' + key);
  const tog = document.getElementById('tog-' + key);
  if (secState[key]) {
    el.style.maxHeight = el.scrollHeight + 'px';
    el.style.overflow  = 'visible';
    tog.classList.remove('collapsed');
    setTimeout(() => { el.style.maxHeight = ''; }, 300);
  } else {
    el.style.maxHeight = el.scrollHeight + 'px';
    el.style.overflow  = 'hidden';
    requestAnimationFrame(() => { el.style.maxHeight = '0'; el.style.transition = 'max-height 0.3s ease'; });
    tog.classList.add('collapsed');
  }
}
document.querySelectorAll('#sec-past, #sec-canc').forEach(el => { el.style.transition = 'max-height 0.3s ease'; });

/* ─── type filter ──────────────────────────────────────── */
function setTypeF(f) {
  typeFilter = f;
  ['all','online','onsite'].forEach(x => document.getElementById('chip-' + x)?.classList.toggle('on', x === f));
  renderAll();
}

/* ─── CREATE / EDIT MODAL ──────────────────────────────── */
function openCreate() {
  editingId = null;
  document.getElementById('mhd-icon').innerHTML  = '<i class="fa-regular fa-calendar-plus" style="color:white"></i>';
  document.getElementById('mhd-title').textContent = 'إنشاء اجتماع جديد';
  document.getElementById('mhd-sub').textContent   = 'أضف تفاصيل الاجتماع أدناه';
  document.getElementById('save-lbl').textContent  = '💾 حفظ الاجتماع';
  document.getElementById('report-section').style.display = 'none';
  clearForm();
  setMType('online');
  openOv('ov-create');
}

function openEdit(id) {
  const m = meetings.find(x => x.id === id);
  if (!m) return;
  editingId = id;
  document.getElementById('mhd-icon').innerHTML = '<i class="fa-regular fa-pen-to-square" style="color:white"></i>';
  document.getElementById('mhd-title').textContent = 'تعديل الاجتماع';
  document.getElementById('mhd-sub').textContent   = m.title;
  document.getElementById('save-lbl').innerHTML    = '<i class="fa-solid fa-calendar-check" style="margin-left:8px"></i> حفظ التعديلات';

  document.getElementById('f-title').value         = m.title;
  document.getElementById('f-presenter').value     = m.presenter || '';
  document.getElementById('f-date').value          = m.date || '';
  if(document.getElementById('f-end-date')) document.getElementById('f-end-date').value = m.end_date || '';
  document.getElementById('f-time').value          = m.time || '';
  if(document.getElementById('f-end-time')) document.getElementById('f-end-time').value = m.end_time || '';
  document.getElementById('f-location').value      = m.location || '';
  document.getElementById('f-location-url').value  = m.location_url || '';
  document.getElementById('f-link').value          = m.link || '';
  document.getElementById('f-notes').value         = m.notes || '';

  // Category - use CatPicker if available
  if (window.catChoices) {
    try { window.catChoices.reset(); if (m.cat) window.catChoices.setChoiceByValue(m.cat); } catch(e){}
  } else {
    const h = document.getElementById('f-cat');
    if (h) h.value = m.cat || '';
  }

  // Invitation direction
  const invSel = document.getElementById('f-invitation');
  if (invSel) invSel.value = m.invitation || '';

  // Populate agenda items
  const agendaList = document.getElementById('agenda-list');
  if (agendaList) {
    agendaList.innerHTML = '';
    (m.agendaItems || []).forEach(a => {
      addAgendaItemWithData(a.title, a.duration, a.presenter);
    });
  }

  const showReport = isPast(m);
  document.getElementById('report-section').style.display = showReport ? 'block' : 'none';
  if (showReport && m.report) {
    document.getElementById('f-report-summary').value   = m.report.summary || '';
    document.getElementById('f-report-decisions').value = m.report.decisions || '';
    document.getElementById('f-report-attendees').value = m.report.attendees || '';
    document.getElementById('f-report-actions').value   = m.report.actions || '';
  }

  setMType(m.type || 'online');
  closeOv('ov-details');
  openOv('ov-create');
}

function clearForm() {
  ['f-title','f-presenter','f-date','f-end-date','f-time','f-end-time','f-location','f-location-url',
   'f-link','f-notes','f-report-summary','f-report-decisions','f-report-attendees','f-report-actions'].forEach(id => {
    const el = document.getElementById(id); if (el) el.value = '';
  });
  // Clear CatPicker
  if (window.catChoices) { try { window.catChoices.reset(); } catch(e){} }
  const agendaList = document.getElementById('agenda-list');
  if (agendaList) agendaList.innerHTML = '';
  const invSel = document.getElementById('f-invitation');
  if (invSel) invSel.value = 'عام';
}

async function saveMeeting() {
  const title     = (document.getElementById('f-title')?.value || '').trim();
  const presenter = (document.getElementById('f-presenter')?.value || '').trim();
  const date      = document.getElementById('f-date')?.value || '';
  let category;
  if (window.catChoices) {
    const vals = window.catChoices.getValues();
    category = Array.isArray(vals) ? vals.join(',') : vals;
  } else {
    category = document.getElementById('f-cat')?.value || null;
  }
  const mType     = document.getElementById('tb-online')?.classList.contains('on-online') ? 'online' : 'onsite';

  if (!title || !presenter || !date || !category) { 
    showToast('⚠️', 'يرجى ملء الحقول المطلوبة (العنوان، المتحدث، التاريخ، والتصنيف)'); 
    return; 
  }

  // Collect agenda items
  const agendaRows = document.querySelectorAll('#agenda-list .agenda-item');
  const agenda_items = [];
  agendaRows.forEach(row => {
    const t = (row.querySelector('.ag-title')?.value || '').trim();
    if (t) agenda_items.push({
      title    : t,
      duration : parseInt(row.querySelector('.ag-duration')?.value) || null,
      presenter: (row.querySelector('.ag-presenter')?.value || '').trim() || null,
    });
  });

  const payload = {
    title, presenter, date, type: mType,
    category,
    invitation_direction : document.getElementById('f-invitation')?.value || null,
    time                 : document.getElementById('f-time')?.value || null,
    end_date             : document.getElementById('f-end-date')?.value || null,
    end_time             : document.getElementById('f-end-time')?.value || null,
    location             : (document.getElementById('f-location')?.value || '').trim() || null,
    location_url         : (document.getElementById('f-location-url')?.value || '').trim() || null,
    link                 : (document.getElementById('f-link')?.value || '').trim() || null,
    notes                : (document.getElementById('f-notes')?.value || '').trim() || null,
    agenda_items,
  };

  // Report fields (shown for past meetings)
  if (document.getElementById('report-section')?.style.display !== 'none') {
    payload.report_summary   = (document.getElementById('f-report-summary')?.value || '').trim() || null;
    payload.report_decisions = (document.getElementById('f-report-decisions')?.value || '').trim() || null;
    payload.report_attendees = parseInt(document.getElementById('f-report-attendees')?.value) || null;
    payload.report_actions   = (document.getElementById('f-report-actions')?.value || '').trim() || null;
  }

  const saveBtn = document.getElementById('save-lbl');
  if (saveBtn) saveBtn.textContent = '⏳ جاري الحفظ...';

  try {
    const url    = editingId ? `/meetings/${editingId}` : '/meetings';
    const method = editingId ? 'PUT' : 'POST';
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(payload)
    });

    if (!res.ok) {
      if (res.status === 422) {
        const data = await res.json();
        console.error('Validation errors:', data.errors);
        showToast('❌', 'يرجى التأكد من إدخال جميع الحقول المطلوبة بشكل صحيح');
      } else {
        showToast('❌', 'حدث خطأ أثناء الحفظ');
      }
      return;
    }
    showToast(editingId ? '✏️':'✅', editingId ? 'تم تعديل الاجتماع بنجاح' : 'تم إنشاء الاجتماع بنجاح');
    closeOv('ov-create');
    await loadMeetings();
  } catch (e) {
    showToast('❌', e.message || 'حدث خطأ');
  } finally {
    if (saveBtn) saveBtn.textContent = editingId ? '💾 حفظ التعديلات' : '💾 حفظ الاجتماع';
  }
}

/* ─── AGENDA ITEMS ─────────────────────────────────────── */
let agendaCount = 0;

function addAgendaItem() {
  addAgendaItemWithData('', null, '');
}

function addAgendaItemWithData(title, duration, presenter) {
  const idx = agendaCount++;
  const list = document.getElementById('agenda-list');
  if (!list) return;
  const div = document.createElement('div');
  div.className = 'agenda-item';
  div.style.cssText = 'display:flex;gap:8px;align-items:center;margin-bottom:8px;background:var(--fog);border-radius:10px;padding:10px 12px;border:1px solid var(--border)';
  div.innerHTML = `
    <span style="width:20px;height:20px;border-radius:6px;background:var(--teal);color:white;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;flex-shrink:0">${(list.children.length + 1)}</span>
    <input class="ag-title" type="text" placeholder="عنوان المحور..." value="${title||''}" style="flex:2;padding:7px 10px;border:1px solid var(--border);border-radius:7px;font-family:'Tajawal',sans-serif;font-size:.82rem">
    <input class="ag-presenter" type="text" placeholder="المقدم (اختياري)..." value="${presenter||''}" style="flex:1;padding:7px 10px;border:1px solid var(--border);border-radius:7px;font-family:'Tajawal',sans-serif;font-size:.82rem">
    <input class="ag-duration" type="number" placeholder="دقائق" value="${duration||''}" min="1" style="width:70px;padding:7px 8px;border:1px solid var(--border);border-radius:7px;font-family:'Tajawal',sans-serif;font-size:.82rem">
    <button type="button" onclick="this.closest('.agenda-item').remove()" style="background:none;border:none;cursor:pointer;color:var(--muted);padding:4px;border-radius:6px;font-size:1rem;flex-shrink:0">×</button>
  `;
  list.appendChild(div);
}

/* ─── type toggle ──────────────────────────────────────── */
function setMType(t) {
  mType = t;
  document.getElementById('tb-online').className = 'type-btn' + (t === 'online' ? ' on-online' : '');
  document.getElementById('tb-onsite').className = 'type-btn' + (t === 'onsite' ? ' on-onsite' : '');
  document.getElementById('fg-link').style.display     = t === 'online' ? 'block' : 'none';
  document.getElementById('fg-location').style.display = t === 'onsite' ? 'block' : 'none';
}

function copyLink() {
  const v = document.getElementById('f-link')?.value.trim();
  if (!v) { showToast('⚠️','أدخل الرابط أولاً'); return; }
  navigator.clipboard?.writeText(v).then(() => showToast('📋','تم نسخ الرابط'));
}

/* ─── DETAILS MODAL ────────────────────────────────────── */
function openDetails(id) {
  const m = meetings.find(x => x.id === id);
  if (!m) return;
  viewingId = id;
  const firstCatId = m.cat ? m.cat.split(',')[0].trim() : null;
  const firstCatObj = firstCatId === 'all' ? null : categories.find(c => c.id === firstCatId || c.name === firstCatId);
  const acc = firstCatObj ? (firstCatObj.color || '#2ab8d0') : '#2ab8d0';

  const isC = isCancelled(m);

  document.getElementById('d-title').textContent = m.title;
  document.getElementById('d-cat').textContent   = catIcon(m) + ' ' + m.cat;
  document.getElementById('d-date').textContent  = fmtDate(m.date);
  document.getElementById('d-time').textContent  = m.time || '—';
  document.getElementById('d-banner-bg').className = 'det-banner-bg' + (isC ? ' red-bg' : isPast(m) ? ' grey-bg' : '');
  document.getElementById('d-type-badge').textContent = m.type === 'online' ? '💻 عن بعد' : '📍 حضوري';
  document.getElementById('d-av').textContent    = ini(m.presenter);
  document.getElementById('d-pname').textContent = m.presenter;

  const locCell = document.getElementById('d-loc-cell');
  if (m.type === 'onsite') {
    locCell.style.display = '';
    document.getElementById('d-loc').textContent = m.location || '—';
  } else {
    locCell.style.display = 'none';
  }

  // link
  const old = document.getElementById('d-link-cell');
  if (old) old.remove();
  if (m.type === 'online') {
    const cell = document.createElement('div');
    cell.className = 'det-cell det-link-cell'; cell.id = 'd-link-cell';
    cell.innerHTML = `<div class="det-cell-lbl">رابط الاجتماع</div><div class="det-link-row"><div class="det-link-url">${m.link||'لم يُضَف رابط'}</div>${m.link?`<button class="join-btn" onclick="window.open('${m.link}','_blank')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>انضم</button>`:''}</div>`;
    document.getElementById('d-grid').appendChild(cell);
  }

  const nw = document.getElementById('d-notes-wrap');
  if (m.notes) { nw.style.display='block'; document.getElementById('d-notes').textContent = m.notes; }
  else nw.style.display = 'none';

  const rw = document.getElementById('d-report-wrap');
  if (m.report && (m.report.summary || m.report.decisions)) {
    rw.style.display = 'block';
    let html = '';
    if (m.report.summary)   html += `<strong>الملخص:</strong> ${m.report.summary}<br><br>`;
    if (m.report.decisions) html += `<strong>القرارات:</strong> ${m.report.decisions}<br><br>`;
    if (m.report.attendees) html += `<strong>عدد الحضور:</strong> ${m.report.attendees} شخصاً<br>`;
    if (m.report.actions)   html += `<strong>الإجراءات التالية:</strong> ${m.report.actions}`;
    document.getElementById('d-report-content').innerHTML = html;
  } else rw.style.display = 'none';

  const cw = document.getElementById('d-cancel-wrap');
  if (isC && m.cancelReason) { cw.style.display='block'; document.getElementById('d-cancel-reason').textContent = m.cancelReason; }
  else cw.style.display = 'none';

  document.getElementById('det-edit-btn').style.display = isC ? 'none' : '';
  openOv('ov-details');
}
function editFromDet() { if (viewingId) openEdit(viewingId); }

/* ─── DELETE ───────────────────────────────────────────── */
function openDelete(id) { deletingId = id; openOv('ov-delete'); }
async function doDelete() {
  try {
    await fetch(`/meetings/${deletingId}`, {
      method:'DELETE', credentials:'same-origin',
      headers:{ 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' }
    });
    showToast('🗑️','تم حذف الاجتماع');
    closeOv('ov-delete');
    await loadMeetings();
  } catch { showToast('❌','فشل الحذف'); }
  deletingId = null;
}

/* ─── CANCEL ───────────────────────────────────────────── */
function openCancel(id) { cancelingId = id; if(document.getElementById('f-cancel-reason')) document.getElementById('f-cancel-reason').value = ''; openOv('ov-cancel'); }
async function doCancel() {
  const r = (document.getElementById('f-cancel-reason')?.value || '').trim();
  if (!r) { showToast('⚠️','يرجى إدخال سبب الإلغاء'); return; }
  try {
    await fetch(`/meetings/${cancelingId}/cancel`, {
      method:'POST', credentials:'same-origin',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
      body: JSON.stringify({ cancel_reason: r })
    });
    showToast('🚫','تم إلغاء الاجتماع');
    closeOv('ov-cancel');
    await loadMeetings();
  } catch { showToast('❌','فشل الإلغاء'); }
  cancelingId = null;
}

/* ─── MODAL UTILS ──────────────────────────────────────── */
function openOv(id)       { document.getElementById(id)?.classList.add('open'); }
function closeOv(id)      { document.getElementById(id)?.classList.remove('open'); }
function bgClose(e, id)   { if (e.target === document.getElementById(id)) closeOv(id); }

/* ─── TOAST ────────────────────────────────────────────── */
let tTimer;
function showToast(icon, msg) {
  const el = document.getElementById('toast');
  if (!el) return;
  document.getElementById('t-icon').textContent = icon;
  document.getElementById('t-msg').textContent  = msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer = setTimeout(() => el.classList.remove('show'), 3000);
}

/* ─── KEYBOARD ─────────────────────────────────────────── */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') ['ov-create','ov-details','ov-delete','ov-cancel','ov-attendees'].forEach(closeOv);
});

/* ─── ATTENDEES MODAL ───────────────────────────────────── */
async function openAttendees(id) {
  attendeesId = id;
  const m = meetings.find(x => x.id === id);
  const titleEl = document.getElementById('att-meeting-title');
  const countEl = document.getElementById('att-total-count');
  const listEl  = document.getElementById('att-list');

  if (titleEl) titleEl.textContent = m ? m.title : '...';
  if (countEl) countEl.textContent = '...';
  if (listEl)  listEl.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-muted)">⏳ جاري التحميل...</div>';

  openOv('ov-attendees');

  try {
    const res = await fetch(`/api/meetings/${id}/attendees`, {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    if (!res.ok) throw new Error();
    const data = await res.json();

    if (countEl) countEl.textContent = data.total;

    if (!data.total) {
      listEl.innerHTML = `
        <div style="text-align:center;padding:3rem;color:var(--text-muted)">
          <div style="font-size:2.5rem;margin-bottom:12px">📭</div>
          <div style="font-weight:700;font-size:1rem">لا توجد جمعيات مسجّلة للحضور بعد</div>
          <div style="font-size:.85rem;margin-top:4px">عندما تسجّل جمعية حضورها سيظهر اسمها هنا</div>
        </div>`;
      return;
    }

    listEl.innerHTML = data.associations.map((a, i) => {
      const initials = a.association_name ? a.association_name.trim().split(' ').map(w => w[0]).slice(0,2).join('') : '?';
      const colors   = ['#2ab8d0','#22c55e','#a855f7','#f59e0b','#ef4444','#3b82f6'];
      const color    = colors[i % colors.length];
      const dateStr = a.registered_at
        ? new Date(a.registered_at).toLocaleString('ar-SA', {
            year: 'numeric', month: 'short', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
          })
        : 'قديم التسجيل';
      return `
      <div style="display:flex;align-items:center;gap:14px;padding:14px 16px;border-bottom:1px solid rgba(0,0,0,0.05);transition:background .15s" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background=''">
        ${a.avatar 
          ? `<img src="${a.avatar}" alt="avatar" style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0;">` 
          : `<div style="width:42px;height:42px;border-radius:50%;background:${color};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.85rem;flex-shrink:0">${initials}</div>`
        }
        <div style="flex:1;min-width:0">
          <div style="font-weight:700;font-size:.9rem;color:var(--text)">${a.association_name}</div>
          <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">${a.manager_name||''} ${a.email ? '· ' + a.email : ''}</div>
        </div>
        <div style="text-align:left;flex-shrink:0">
          <div style="font-size:.7rem;color:var(--text-muted)">سجّلت في</div>
          <div style="font-size:.78rem;font-weight:600;color:var(--teal)">${dateStr}</div>
        </div>
      </div>`;
    }).join('');

  } catch {
    if (listEl) listEl.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--red)">❌ تعذّر تحميل البيانات</div>';
  }
}

function closeAttendees() { closeOv('ov-attendees'); attendeesId = null; }

/* ─── INIT ─────────────────────────────────────────────── */
setMType('online');
loadCategories().then(() => loadMeetings()).then(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const reqIdParam = urlParams.get('req_id');
  const typeParam = urlParams.get('type');
  if (reqIdParam && typeParam === 'meeting_created') {
    const targetMeeting = meetings.find(m => String(m.id) === String(reqIdParam));
    if (targetMeeting) {
      setTimeout(() => {
        openDetails(targetMeeting.id);
        window.history.replaceState({}, document.title, window.location.pathname);
      }, 300);
    }
  }
});
setInterval(loadMeetings, 60000); // auto-refresh every 60s
