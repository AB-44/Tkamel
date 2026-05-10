const TODAY = new Date().toISOString().split('T')[0];

let meetings = [];

let nextId = 1;
let editingId = null;
let deletingId = null;
let cancelingId = null;
let viewingId = null;
let mType = 'online';
let typeFilter = 'all';

const CAT_EMOJI = { خيرية: '', ثقافية: '', صحية: '', رياضية: '', تنموية: '', دينية: '' };
const CAT_BADGE = { خيرية: 'b-xairy', ثقافية: 'b-thaqafi', صحية: 'b-seha', رياضية: 'b-riyadhi', تنموية: 'b-tanmawi', دينية: 'b-dini' };
const ACCENT = { خيرية: '#2ab8d0', ثقافية: '#7b4ea6', صحية: '#2eaa78', رياضية: '#3a72b8', تنموية: '#e65100', دينية: '#7b4ea6' };

/* ── HELPERS ── */
function isCurrent(m) { return m.status === 'upcoming' || (m.status === 'active' && m.date >= TODAY); }
function isPast(m) { return m.status === 'past' || (m.status === 'active' && m.date < TODAY); }
function isCancelled(m) { return m.status === 'cancelled'; }

function fmtDate(d) {
  if (!d) return '—';
  const dt = new Date(d + 'T00:00:00');
  return dt.toLocaleDateString('ar-SA', { weekday: 'short', year: 'numeric', month: 'long', day: 'numeric' });
}

function fmtDateShort(d) {
  if (!d) return { day: '—', month: '' };
  const dt = new Date(d + 'T00:00:00');
  return { day: dt.getDate(), month: dt.toLocaleDateString('ar-SA', { month: 'short' }) };
}

function ini(n) { const p = n.trim().split(' '); return p.length >= 2 ? p[0][0] + p[1][0] : n[0]; }
function domainShort(url) { try { return new URL(url).hostname.replace('www.', ''); } catch { return url; } }

/* ── FILTER ── */
function getFiltered() {
  const q = document.getElementById('searchInput').value.trim().toLowerCase();
  const ct = document.getElementById('catFilter').value;
  return meetings.filter(m => {
    const mq = !q || m.title.toLowerCase().includes(q) || m.presenter.toLowerCase().includes(q);
    const mc = !ct || m.cat === ct;
    const mt = typeFilter === 'all' || m.type === typeFilter;
    return mq && mc && mt;
  });
}

/* ── RENDER ── */
function renderAll() {
  const list = getFiltered();
  const cur = list.filter(isCurrent).sort((a, b) => a.date.localeCompare(b.date));
  const past = list.filter(isPast).sort((a, b) => b.date.localeCompare(a.date));
  const canc = list.filter(isCancelled).sort((a, b) => b.date.localeCompare(a.date));

  document.getElementById('bc-cur').textContent = cur.length;
  document.getElementById('bc-past').textContent = past.length;
  document.getElementById('bc-canc').textContent = canc.length;

  // current — full cards
  const cg = document.getElementById('grid-cur');
  const isUser = document.querySelector('.readonly-notice') || window.location.pathname.includes('/user/');
  cg.innerHTML = cur.length
    ? cur.map(m => fullCard(m)).join('')
    : (isUser 
        ? `<div class="ue-card" style="grid-column:1/-1"><div class="ue-header"><div class="ue-title">الاجتماعات</div><button class="ue-refresh" onclick="location.reload()" title="تحديث"><i class="fa-solid fa-arrow-rotate-right"></i></button></div><div class="ue-body"><div class="ue-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div><div class="ue-msg">عذراً، خدمة الاجتماعات غير متاحة حالياً، أو لم يتم جدولة اجتماعات بعد.</div></div></div>`
        : '<div class="empty"><span class="empty-emoji"></span><h3>لا توجد اجتماعات حالية</h3><p>أنشئ اجتماعاً جديداً أو عدّل معايير البحث</p></div>');

  // past — compact list
  document.getElementById('list-past').innerHTML = past.length
    ? past.map(m => compactRow(m, false)).join('')
    : '<div class="compact-empty"><span class="compact-empty-emoji"></span><p>لا توجد اجتماعات سابقة</p></div>';

  // cancelled — compact list
  document.getElementById('list-canc').innerHTML = canc.length
    ? canc.map(m => compactRow(m, true)).join('')
    : '<div class="compact-empty"><span class="compact-empty-emoji"></span><p>لا توجد اجتماعات ملغاة</p></div>';

  updateStats();
}

/* ── FULL CARD (current) ── */
function fullCard(m) {
  const acc = ACCENT[m.cat] || '#2ab8d0';
  const tLabel = m.type === 'online' ? 'عن بعد' : 'حضوري';
  const tBadge = m.type === 'online' ? 'b-online' : 'b-onsite';
  const linkRow = (m.type === 'online' && m.link)
    ? `<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg><a class="link-pill" href="${m.link}" target="_blank">${domainShort(m.link)}</a></div>` : '';

  return `
  <div class="meeting-card">
    <div class="card-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
    <div class="card-inner">
      <div class="card-row1">
        <div class="card-badges">
          <span class="badge ${tBadge}">${tLabel}</span>
          <span class="badge ${CAT_BADGE[m.cat] || ''}">${m.cat}</span>
        </div>
        <div class="card-actions">
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

/* ── COMPACT ROW (past / cancelled) ── */
function compactRow(m, isCnc) {
  const acc = isCnc ? '#c62828' : (ACCENT[m.cat] || '#2ab8d0');
  const ds = fmtDateShort(m.date);
  const tLabel = m.type === 'online' ? 'عن بعد' : 'حضوري';
  const hasReport = !isCnc && m.report;

  const actionBtns = isCnc
    ? `<button class="icn-btn edit" onclick="openEdit(${m.id})" title="تعديل"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
       <button class="icn-btn del" onclick="openDelete(${m.id})" title="حذف"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>`
    : `<button class="icn-btn edit" onclick="openEdit(${m.id})" title="تعديل / إضافة تقرير"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
       <button class="icn-btn del" onclick="openDelete(${m.id})" title="حذف"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg></button>`;

  return `
  <div class="compact-item${isCnc ? ' cancelled-row' : ''}">
    <div class="ci-bar" style="background:${acc}"></div>
    <div class="ci-date"><span class="ci-day">${ds.day}</span><span class="ci-month">${ds.month}</span></div>
    <div class="ci-body">
      <div class="ci-title">${m.title}</div>
      <div class="ci-meta">
        <div class="ci-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          ${m.presenter}
        </div>
        <div class="ci-meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          ${m.time || '—'}
        </div>
        <div class="ci-meta-item" style="color:var(--teal)">${tLabel}</div>
        ${isCnc ? `<div class="ci-meta-item" style="color:var(--red)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>${m.cancelReason ? m.cancelReason.substring(0, 40) + '…' : ''}</div>` : ''}
      </div>
    </div>
    <div class="ci-badges">
      <span class="badge ${CAT_BADGE[m.cat] || ''}" style="font-size:0.65rem">${m.cat}</span>
      ${isCnc ? '<span class="ci-cancelled-badge">ملغي</span>' : ''}
      ${hasReport ? '<span class="badge b-has-report" style="font-size:0.65rem">تقرير</span>' : ''}
    </div>
    <div class="ci-actions">
      ${actionBtns}
      <button class="icn-btn" onclick="openDetails(${m.id})" title="التفاصيل"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></button>
    </div>
  </div>`;
}

/* ── STATS ── */
function updateStats() {
  const all = meetings;
  document.getElementById('s-total').textContent = all.length;
  document.getElementById('s-cur').textContent = all.filter(isCurrent).length;
  document.getElementById('s-past').textContent = all.filter(isPast).length;
  document.getElementById('s-canc').textContent = all.filter(isCancelled).length;
  document.getElementById('s-online').textContent = all.filter(m => m.type === 'online').length;
}

/* ── COLLAPSIBLE SECTIONS ── */
const secState = { past: true, canc: true };

function toggleSec(key) {
  secState[key] = !secState[key];
  const el = document.getElementById('sec-' + key);
  const tog = document.getElementById('tog-' + key);
  if (secState[key]) {
    el.style.maxHeight = el.scrollHeight + 'px';
    el.style.overflow = 'visible';
    tog.classList.remove('collapsed');
    setTimeout(() => { el.style.maxHeight = ''; }, 300);
  } else {
    el.style.maxHeight = el.scrollHeight + 'px';
    el.style.overflow = 'hidden';
    requestAnimationFrame(() => { el.style.maxHeight = '0'; el.style.transition = 'max-height 0.3s ease'; });
    tog.classList.add('collapsed');
  }
}

// init section transitions
document.querySelectorAll('#sec-past, #sec-canc').forEach(el => {
  el.style.transition = 'max-height 0.3s ease';
});

/* ── TYPE FILTER CHIPS ── */
function setTypeF(f) {
  typeFilter = f;
  ['all', 'online', 'onsite'].forEach(x => document.getElementById('chip-' + x).classList.toggle('on', x === f));
  renderAll();
}

/* ── CREATE / EDIT MODAL ── */
function openCreate() {
  editingId = null;
  document.getElementById('mhd-title').textContent = 'إضافة اجتماع';
  document.getElementById('mhd-sub').textContent = 'أدخل تفاصيل الاجتماع الجديد';
  document.getElementById('save-lbl').innerHTML = '<i class="fa-solid fa-calendar-check" style="margin-left:8px"></i> حفظ الاجتماع';
  document.getElementById('report-section').style.display = 'none';
  clearForm();
  renderAgendaList([]);
  setMType('onsite');
  openOv('ov-create');
}

function openEdit(id) {
  const m = meetings.find(x => x.id === id);
  if (!m) return;
  editingId = id;
  document.getElementById('mhd-title').textContent = 'تعديل الاجتماع';
  document.getElementById('mhd-sub').textContent = m.title;
  document.getElementById('save-lbl').innerHTML = '<i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ التعديلات';

  document.getElementById('f-title').value = m.title;
  document.getElementById('f-cat') && (document.getElementById('f-cat').value = m.cat);
  document.getElementById('f-presenter').value = m.presenter;
  document.getElementById('f-date').value = m.date;
  document.getElementById('f-time').value = m.time || '';
  document.getElementById('f-duration').value = m.duration || '';
  document.getElementById('f-invitation').value = m.invitation || 'عام';
  document.getElementById('f-location').value = m.location || '';
  const locUrlEl = document.getElementById('f-location-url');
  if (locUrlEl) locUrlEl.value = m.location_url || m.locationUrl || '';
  document.getElementById('f-link').value = m.link || '';
  document.getElementById('f-notes').value = m.notes || '';

  renderAgendaList(m.agendaItems || []);

  const showReport = isPast(m);
  document.getElementById('report-section').style.display = showReport ? 'block' : 'none';
  if (showReport && m.report) {
    document.getElementById('f-report-summary').value = m.report.summary || '';
    document.getElementById('f-report-decisions').value = m.report.decisions || '';
    document.getElementById('f-report-attendees').value = m.report.attendees || '';
    document.getElementById('f-report-actions').value = m.report.actions || '';
  } else if (showReport) {
    ['f-report-summary', 'f-report-decisions', 'f-report-attendees', 'f-report-actions'].forEach(x => {
      const el = document.getElementById(x); if (el) el.value = '';
    });
  }

  setMType(m.type);
  closeOv('ov-details');
  openOv('ov-create');
}

function clearForm() {
  ['f-title', 'f-presenter', 'f-date', 'f-time', 'f-duration',
   'f-location', 'f-location-url', 'f-link', 'f-notes',
   'f-report-summary', 'f-report-decisions', 'f-report-attendees', 'f-report-actions'].forEach(id => {
    const el = document.getElementById(id); if (el) el.value = '';
  });
  const inv = document.getElementById('f-invitation');
  if (inv) inv.value = 'عام';
}

async function loadMeetingsFromApi() {
  const res = await fetch('/api/meetings', {
    headers: { 'Accept': 'application/json' }
  });
  if (!res.ok) throw new Error(`Failed to load meetings: ${res.status}`);
  const data = await res.json();

  meetings = Array.isArray(data) ? data.map((m) => ({
    ...m,
    cat: m.cat || m.category || '',
    presenter: m.presenter || '',
    cancelReason: m.cancelReason || m.cancel_reason || '',
    report: m.report || null,
  })) : [];

  nextId = meetings.reduce((max, m) => Math.max(max, Number(m.id) || 0), 0) + 1;
}

function saveMeeting() {
  const title = document.getElementById('f-title').value.trim();
  const presenter = document.getElementById('f-presenter').value.trim();
  const date = document.getElementById('f-date').value;

  // Use f-cat if it exists (legacy), otherwise derive from invitation
  const catEl = document.getElementById('f-cat');
  const invEl = document.getElementById('f-invitation');
  const cat = catEl ? catEl.value : (invEl ? invEl.value : '');

  if (!title || !presenter || !date) { showToast('⚠️', 'يرجى ملء الحقول المطلوبة'); return; }

  // Collect agenda items
  const agendaItems = collectAgendaItems();

  const data = {
    title, presenter, date,
    category: cat || 'عام',
    cat: cat || 'عام',
    type: mType,
    time: document.getElementById('f-time').value,
    duration_minutes: parseInt(document.getElementById('f-duration').value) || null,
    invitation_direction: invEl ? invEl.value : 'عام',
    location: document.getElementById('f-location').value.trim(),
    location_url: document.getElementById('f-location-url')?.value.trim() || null,
    link: document.getElementById('f-link').value.trim(),
    notes: document.getElementById('f-notes').value.trim(),
    agenda_items: agendaItems,
  };

  const showReport = document.getElementById('report-section').style.display !== 'none';
  if (showReport) {
    const summary = document.getElementById('f-report-summary').value.trim();
    const decisions = document.getElementById('f-report-decisions').value.trim();
    const attendees = document.getElementById('f-report-attendees').value;
    const actions = document.getElementById('f-report-actions').value.trim();
    data.report = (summary || decisions || attendees || actions)
      ? { summary, decisions, attendees: attendees ? parseInt(attendees) : null, actions }
      : null;
  }

  const method = editingId ? 'PUT' : 'POST';
  const url = editingId ? `/meetings/${editingId}` : '/meetings';

  fetch(url, {
    method,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
    },
    body: JSON.stringify({
      ...data,
      status: editingId ? undefined : 'upcoming',
      report_summary: data.report?.summary || null,
      report_decisions: data.report?.decisions || null,
      report_attendees: data.report?.attendees || null,
      report_actions: data.report?.actions || null,
    }),
  })
  .then(async (res) => {
    const isJson = res.headers.get('content-type')?.includes('application/json');
    const payload = isJson ? await res.json() : null;
    if (!res.ok) throw new Error(payload?.message || `خطأ: ${res.status}`);
    return payload;
  })
  .then(() => loadMeetingsFromApi())
  .then(() => {
    showToast('✅', editingId ? 'تم تعديل الاجتماع بنجاح' : 'تم إنشاء الاجتماع بنجاح');
    closeOv('ov-create');
    renderAll();
  })
  .catch((err) => {
    showToast('', err.message || 'حدث خطأ أثناء الحفظ');
  });
}

/* ── TYPE TOGGLE ── */
function setMType(t) {
  mType = t;
  document.getElementById('tb-online').className = 'type-btn' + (t === 'online' ? ' on-online' : '');
  document.getElementById('tb-onsite').className = 'type-btn' + (t === 'onsite' ? ' on-onsite' : '');
  document.getElementById('fg-link').style.display = t === 'online' ? 'block' : 'none';
  document.getElementById('fg-location').style.display = t === 'onsite' ? 'block' : 'none';
}

function copyLink() {
  const v = document.getElementById('f-link').value.trim();
  if (!v) { showToast('', 'أدخل الرابط أولاً'); return; }
  navigator.clipboard?.writeText(v).then(() => showToast('', 'تم نسخ الرابط'));
}

/* ── DETAILS MODAL ── */
function openDetails(id) {
  const m = meetings.find(x => x.id === id);
  if (!m) return;
  viewingId = id;
  const isC = isCancelled(m);

  document.getElementById('d-title').textContent = m.title;
  document.getElementById('d-cat').textContent = m.cat;
  document.getElementById('d-date').textContent = fmtDate(m.date);
  document.getElementById('d-time').textContent = m.time || '—';
  document.getElementById('d-banner-bg').className = 'det-banner-bg' + (isC ? ' red-bg' : isPast(m) ? ' grey-bg' : '');
  document.getElementById('d-type-badge').textContent = m.type === 'online' ? 'عن بعد' : 'حضوري';
  document.getElementById('d-av').textContent = ini(m.presenter);
  document.getElementById('d-pname').textContent = m.presenter;

  const locCell = document.getElementById('d-loc-cell');
  if (m.type === 'onsite') {
    locCell.style.display = '';
    const locUrl = m.location_url || m.locationUrl;
    if (locUrl) {
      document.getElementById('d-loc').innerHTML = `<a href="${locUrl}" target="_blank" style="color:var(--teal);text-decoration:none">${m.location || 'الرابط'} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.7rem;margin-right:4px"></i></a>`;
    } else {
      document.getElementById('d-loc').textContent = m.location || '—';
    }
  } else {
    locCell.style.display = 'none';
  }

  // link cell
  const old = document.getElementById('d-link-cell');
  if (old) old.remove();
  if (m.type === 'online') {
    const cell = document.createElement('div');
    cell.className = 'det-cell det-link-cell'; cell.id = 'd-link-cell';
    cell.innerHTML = `<div class="det-cell-lbl">رابط الاجتماع</div><div class="det-link-row"><div class="det-link-url">${m.link || 'لم يُضَف رابط'}</div>${m.link ? `<button class="join-btn" onclick="window.open('${m.link}','_blank')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>انضم</button>` : ''}</div>`;
    document.getElementById('d-grid').appendChild(cell);
  }

  // notes
  const nw = document.getElementById('d-notes-wrap');
  if (m.notes) { nw.style.display = 'block'; document.getElementById('d-notes').textContent = m.notes; }
  else nw.style.display = 'none';

  // report
  const rw = document.getElementById('d-report-wrap');
  if (m.report && (m.report.summary || m.report.decisions)) {
    rw.style.display = 'block';
    let html = '';
    if (m.report.summary) html += `<strong>الملخص:</strong> ${m.report.summary}<br><br>`;
    if (m.report.decisions) html += `<strong>القرارات:</strong> ${m.report.decisions}<br><br>`;
    if (m.report.attendees) html += `<strong>عدد الحضور:</strong> ${m.report.attendees} شخصاً<br>`;
    if (m.report.actions) html += `<strong>الإجراءات التالية:</strong> ${m.report.actions}`;
    document.getElementById('d-report-content').innerHTML = html;
  } else rw.style.display = 'none';

  // cancel reason
  const cw = document.getElementById('d-cancel-wrap');
  if (isC && m.cancelReason) { cw.style.display = 'block'; document.getElementById('d-cancel-reason').textContent = m.cancelReason; }
  else cw.style.display = 'none';

  document.getElementById('det-edit-btn').style.display = isC ? 'none' : '';
  openOv('ov-details');
}

function editFromDet() { if (viewingId) openEdit(viewingId); }

/* ── DELETE ── */
function openDelete(id) { deletingId = id; openOv('ov-delete'); }
function doDelete() {
  fetch(`/meetings/${deletingId}`, {
    method: 'DELETE',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
    },
  })
  .then(async (res) => {
    const isJson = res.headers.get('content-type')?.includes('application/json');
    const payload = isJson ? await res.json() : null;
    if (!res.ok) throw new Error(payload?.message || `خطأ: ${res.status}`);
    return payload;
  })
  .then(() => loadMeetingsFromApi())
  .then(() => {
    closeOv('ov-delete');
    renderAll();
    showToast('', 'تم حذف الاجتماع');
    deletingId = null;
  })
  .catch((err) => showToast('', err.message || 'تعذر حذف الاجتماع'));
}

/* ── CANCEL ── */
function openCancel(id) { cancelingId = id; document.getElementById('f-cancel-reason').value = ''; openOv('ov-cancel'); }
function doCancel() {
  const r = document.getElementById('f-cancel-reason').value.trim();
  if (!r) { showToast('', 'يرجى إدخال سبب الإلغاء'); return; }
  fetch(`/meetings/${cancelingId}/cancel`, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
    },
    body: JSON.stringify({ cancel_reason: r }),
  })
  .then(async (res) => {
    const isJson = res.headers.get('content-type')?.includes('application/json');
    const payload = isJson ? await res.json() : null;
    if (!res.ok) throw new Error(payload?.message || `خطأ: ${res.status}`);
    return payload;
  })
  .then(() => loadMeetingsFromApi())
  .then(() => {
    closeOv('ov-cancel');
    renderAll();
    showToast('', 'تم إلغاء الاجتماع');
    cancelingId = null;
  })
  .catch((err) => showToast('', err.message || 'تعذر إلغاء الاجتماع'));
}

/* ── MODAL UTILS ── */
function openOv(id) { document.getElementById(id).classList.add('open'); }
function closeOv(id) { document.getElementById(id).classList.remove('open'); }
function bgClose(e, id) { if (e.target === document.getElementById(id)) closeOv(id); }

/* ── TOAST ── */
let tTimer;
function showToast(icon, msg) {
  const el = document.getElementById('toast');
  document.getElementById('t-icon').textContent = icon;
  document.getElementById('t-msg').textContent = msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer = setTimeout(() => el.classList.remove('show'), 3000);
}

/* ── KEYBOARD ── */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') ['ov-create', 'ov-details', 'ov-delete', 'ov-cancel'].forEach(closeOv);
});

/* ── AGENDA ITEMS ── */
let agendaCounter = 0;

function renderAgendaList(items) {
  agendaCounter = 0;
  const list = document.getElementById('agenda-list');
  if (!list) return;
  if (!items || items.length === 0) {
    list.innerHTML = `<div class="agenda-empty"><i class="fa-regular fa-calendar-xmark" style="margin-left:6px;opacity:.5"></i>لم تُضف محاور بعد — اضغط "إضافة محور" لإضافة جلسة</div>`;
    return;
  }
  list.innerHTML = items.map((item, i) => buildAgendaItemHTML(i, item)).join('');
  agendaCounter = items.length;
}

function buildAgendaItemHTML(index, item) {
  const n = index + 1;
  return `
  <div class="agenda-item" id="agenda-item-${index}">
    <div class="agenda-item-hd">
      <span class="agenda-item-num">المحور ${n}</span>
      <button type="button" class="btn-del-agenda" onclick="removeAgendaItem(${index})">
        <i class="fa-solid fa-trash-can" style="font-size:.65rem"></i> حذف
      </button>
    </div>
    <div class="agenda-row">
      <div class="fg" style="margin-bottom:0">
        <div class="fld">
          <span class="fld-icon"><i class="fa-regular fa-comment" style="font-size:.72rem"></i></span>
          <input type="text" id="ai-title-${index}" placeholder="أدخل عنوان المحور..." value="${item?.title || ''}">
        </div>
      </div>
      <div class="fg" style="margin-bottom:0">
        <div class="fld">
          <span class="fld-icon"><i class="fa-regular fa-clock" style="font-size:.72rem"></i></span>
          <input type="number" id="ai-dur-${index}" placeholder="15" min="1" max="180" value="${item?.duration || 15}" style="padding-right:34px">
        </div>
      </div>
    </div>
    <div class="fg agenda-presenter" style="margin-bottom:0">
      <div class="fld">
        <span class="fld-icon"><i class="fa-solid fa-user" style="font-size:.72rem"></i></span>
        <input type="text" id="ai-pres-${index}" placeholder="اسم المقدم..." value="${item?.presenter || ''}">
      </div>
    </div>
  </div>`;
}

function addAgendaItem() {
  const list = document.getElementById('agenda-list');
  if (!list) return;

  // Remove empty state if present
  const empty = list.querySelector('.agenda-empty');
  if (empty) empty.remove();

  const index = agendaCounter++;
  const div = document.createElement('div');
  div.innerHTML = buildAgendaItemHTML(index, null);
  list.appendChild(div.firstElementChild);

  // Focus the title input
  const titleInput = document.getElementById(`ai-title-${index}`);
  if (titleInput) setTimeout(() => titleInput.focus(), 50);
}

function removeAgendaItem(index) {
  const el = document.getElementById(`agenda-item-${index}`);
  if (el) {
    el.style.opacity = '0';
    el.style.transform = 'scale(0.95)';
    el.style.transition = 'all 0.2s';
    setTimeout(() => {
      el.remove();
      // Show empty state if no items remain
      const list = document.getElementById('agenda-list');
      if (list && list.querySelectorAll('.agenda-item').length === 0) {
        list.innerHTML = `<div class="agenda-empty"><i class="fa-regular fa-calendar-xmark" style="margin-left:6px;opacity:.5"></i>لم تُضف محاور بعد — اضغط "إضافة محور" لإضافة جلسة</div>`;
      }
    }, 200);
  }
}

function collectAgendaItems() {
  const list = document.getElementById('agenda-list');
  if (!list) return [];
  const items = [];
  list.querySelectorAll('.agenda-item').forEach(el => {
    const id = el.id.replace('agenda-item-', '');
    const title = document.getElementById(`ai-title-${id}`)?.value.trim();
    if (!title) return;
    items.push({
      title,
      duration: parseInt(document.getElementById(`ai-dur-${id}`)?.value) || 15,
      presenter: document.getElementById(`ai-pres-${id}`)?.value.trim() || null,
    });
  });
  return items;
}

/* ── INIT ── */
function initMeetings() {
  setMType('onsite');
  loadMeetingsFromApi()
    .then(() => renderAll())
    .catch(() => {
      renderAll();
      showToast('⚠️', 'تعذر تحميل الاجتماعات');
    });
}

document.addEventListener('DOMContentLoaded', () => {
  initMeetings();
});
