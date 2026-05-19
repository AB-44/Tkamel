/**
 * user-meetings.js — User Meetings Page
 * Fully connected to backend data via window.meetingsList
 */

const TODAY = new Date().toISOString().split('T')[0];
const CSRF  = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Data from server (injected via Blade)
let meetings     = (window.meetingsList || []).map(m => ({
    ...m,
    catItem: (window.categoriesList || []).find(c => c.name === m.cat)
}));
let attendingIds = new Set(window.attendingIdsList || []);
let typeFilter   = 'all';
let viewingId    = null;

/* ── ACCENT COLOURS ── */
const ACCENT = {
    خيرية: '#2ab8d0', ثقافية: '#7b4ea6', صحية: '#2eaa78',
    رياضية: '#3a72b8', تنموية: '#e65100', دينية: '#7b4ea6'
};
const CAT_BADGE = {
    خيرية: 'b-xairy', ثقافية: 'b-thaqafi', صحية: 'b-seha',
    رياضية: 'b-riyadhi', تنموية: 'b-tanmawi', دينية: 'b-dini'
};

/* ── HELPERS ── */
function isCurrent(m) {
    if (isCancelled(m)) return false;
    let endDt = new Date(m.date + 'T' + (m.end_time || '23:59') + ':00');
    return endDt >= new Date();
}
function isPast(m) {
    if (isCancelled(m)) return false;
    let endDt = new Date(m.date + 'T' + (m.end_time || '23:59') + ':00');
    return endDt < new Date();
}
function isCancelled(m) { return m.status === 'cancelled' || m.status === 'canceled'; }

function catIcon(m) {
    if (m.catItem && m.catItem.icon) return m.catItem.icon;
    const icons = { خيرية:'🤝', ثقافية:'📚', صحية:'🌿', رياضية:'⚽', تنموية:'📈', دينية:'🕌' };
    return icons[m.cat] || '📁';
}

function catAccent(m) { return ACCENT[m.cat] || '#2ab8d0'; }

function fmtDate(d) {
    if (!d) return '—';
    return new Date(d + 'T00:00:00').toLocaleDateString('ar-SA', {
        weekday: 'short', year: 'numeric', month: 'long', day: 'numeric'
    });
}
function fmtDateShort(d) {
    if (!d) return { day: '—', month: '' };
    const dt = new Date(d + 'T00:00:00');
    return { day: dt.getDate(), month: dt.toLocaleDateString('ar-SA', { month: 'short' }) };
}
function ini(n) {
    n = (n || '?').trim();
    const p = n.split(' ');
    return p.length >= 2 ? (p[0][0] || '') + (p[1][0] || '') : n[0];
}
function domainShort(url) {
    try { return new URL(url).hostname.replace('www.', ''); } catch { return url; }
}

/* ── FILTER ── */
function getFiltered() {
    const q  = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
    return meetings.filter(m => {
        const mq = !q  || m.title.toLowerCase().includes(q) || (m.presenter || '').toLowerCase().includes(q);
        const mt = typeFilter === 'all' || m.type === typeFilter;
        return mq && mt;
    });
}

/* ── STATS ── */
function updateStats() {
    const s = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    s('s-total',     meetings.length);
    s('s-cur',       meetings.filter(isCurrent).length);
    s('s-attending', attendingIds.size);
    s('s-online',    meetings.filter(m => m.type === 'online').length);
}

/* ── RENDER ALL ── */
window.renderAll = function renderAll() {
    const list = getFiltered();
    const cur  = list.filter(isCurrent).sort((a, b) => a.date.localeCompare(b.date));
    const past = list.filter(isPast).sort((a, b) => b.date.localeCompare(a.date));
    const canc = list.filter(isCancelled).sort((a, b) => b.date.localeCompare(a.date));

    const s = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    s('bc-cur',  cur.length);
    s('bc-past', past.length);
    s('bc-canc', canc.length);

    // Current meetings — full cards
    const cg = document.getElementById('grid-cur');
    if (cg) cg.innerHTML = cur.length
        ? cur.map((m, i) => fullCard(m, i)).join('')
        : '<div class="empty"><span class="empty-emoji">📋</span><h3>لا توجد اجتماعات قادمة</h3><p>ستظهر الاجتماعات الجديدة هنا فور إضافتها</p></div>';

    // Past meetings — compact rows
    const lp = document.getElementById('list-past');
    if (lp) lp.innerHTML = past.length
        ? past.map((m, i) => compactRow(m, false, i)).join('')
        : '<div class="compact-empty"><span class="compact-empty-emoji">📁</span><p>لا توجد اجتماعات سابقة</p></div>';

    // Cancelled meetings — compact rows
    const lc = document.getElementById('list-canc');
    if (lc) lc.innerHTML = canc.length
        ? canc.map((m, i) => compactRow(m, true, i)).join('')
        : '<div class="compact-empty"><span class="compact-empty-emoji">✅</span><p>لا توجد اجتماعات ملغاة</p></div>';

    updateStats();
};

/* ── FULL CARD ── */
function fullCard(m, i) {
    const acc    = catAccent(m);
    const isAtt  = attendingIds.has(m.id);
    const tLabel = m.type === 'online' ? '💻 عن بعد' : '📍 حضوري';
    const tBadge = m.type === 'online' ? 'b-online' : 'b-onsite';
    const ic     = catIcon(m);
    const cb     = CAT_BADGE[m.cat] || 'b-online';

    const locationRow = (m.type === 'onsite' && m.location)
        ? `<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${m.location}</div>` : '';
    const linkRow = (m.type === 'online' && m.link)
        ? `<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg><a class="link-pill" href="${m.link}" target="_blank">🔗 ${domainShort(m.link)}</a></div>` : '';

    return `
    <div class="meeting-card" style="animation-delay:${i * 0.06}s">
      <div class="card-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
      <div class="card-inner">
        <div class="card-badges">
          <span class="badge ${tBadge}">${tLabel}</span>
          <span class="badge ${cb}">${ic} ${m.cat}</span>
        </div>
        <div class="card-title">${m.title}</div>
        <div class="card-meta">
          <div class="meta-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            ${fmtDate(m.date)}${m.time ? ' — ' + m.time : ''}
          </div>
          ${locationRow}${linkRow}
        </div>
      </div>
      <div class="card-foot">
        <div class="presenter">
          <div class="p-av">${ini(m.presenter)}</div>
          <div><div class="p-name">${m.presenter}</div><div class="p-role">المقدم</div></div>
        </div>
        <div class="card-foot-actions">
          ${isAtt
            ? `<button class="btn-attending" onclick="quickToggleAttend(${m.id})">✅ سأحضر</button>`
            : `<button class="btn-attend-card" onclick="quickToggleAttend(${m.id})">＋ سأحضر</button>`}
          <button class="btn-view" onclick="openDetails(${m.id})">التفاصيل</button>
        </div>
      </div>
    </div>`;
}

/* ── COMPACT ROW ── */
function compactRow(m, isCnc, i) {
    const acc    = isCnc ? '#c62828' : catAccent(m);
    const ds     = fmtDateShort(m.date);
    const ic     = catIcon(m);
    const cb     = CAT_BADGE[m.cat] || 'b-online';
    const hasRep = !isCnc && m.report;

    return `
    <div class="compact-item${isCnc ? ' cancelled-row' : ''}" style="animation-delay:${i * 0.05}s">
      <div class="ci-bar" style="background:${acc}"></div>
      <div class="ci-date"><span class="ci-day">${ds.day}</span><span class="ci-month">${ds.month}</span></div>
      <div class="ci-body">
        <div class="ci-title">${m.title}</div>
        <div class="ci-meta">
          <div class="ci-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>${m.presenter}</div>
          <div class="ci-meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>${m.time || '—'}${m.end_time ? ' - ' + m.end_time : ''}</div>
          <div class="ci-meta-item" style="color:var(--teal)">${m.type === 'online' ? '💻 عن بعد' : '📍 حضوري'}</div>
          ${isCnc && m.cancelReason ? `<div class="ci-meta-item" style="color:var(--red)">🚫 ${m.cancelReason.substring(0,40)}…</div>` : ''}
        </div>
      </div>
      <div class="ci-badges">
        <span class="badge ${cb}" style="font-size:0.65rem">${ic} ${m.cat}</span>
        ${isCnc ? '<span class="ci-cancelled-badge">🚫 ملغي</span>' : ''}
        ${hasRep ? '<span class="badge b-has-report" style="font-size:0.65rem">📋 تقرير</span>' : ''}
      </div>
      <div class="ci-actions">
        <button class="icn-btn" onclick="openDetails(${m.id})" title="التفاصيل">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </button>
      </div>
    </div>`;
}

/* ── TYPE FILTER ── */
function setTypeF(f) {
    typeFilter = f;
    ['all', 'online', 'onsite'].forEach(x => {
        document.getElementById('chip-' + x)?.classList.toggle('on', x === f);
    });
    renderAll();
}

/* ── COLLAPSE SECTIONS ── */
const secState = { past: true, canc: true };
function toggleSec(key) {
    secState[key] = !secState[key];
    const el  = document.getElementById('sec-' + key);
    const tog = document.getElementById('tog-' + key);
    if (!el) return;
    if (secState[key]) {
        el.style.maxHeight = el.scrollHeight + 'px';
        el.style.overflow  = 'visible';
        tog?.classList.remove('collapsed');
        setTimeout(() => { el.style.maxHeight = ''; }, 300);
    } else {
        el.style.maxHeight = el.scrollHeight + 'px';
        el.style.overflow  = 'hidden';
        requestAnimationFrame(() => {
            el.style.maxHeight  = '0';
            el.style.transition = 'max-height 0.3s ease';
        });
        tog?.classList.add('collapsed');
    }
}
document.querySelectorAll('#sec-past, #sec-canc').forEach(el => {
    el.style.transition = 'max-height 0.3s ease';
});

/* ── DETAILS MODAL ── */
function openDetails(id) {
    const m = meetings.find(x => x.id === id);
    if (!m) return;
    viewingId = id;
    const isC   = isCancelled(m);
    const isCur = isCurrent(m);

    document.getElementById('d-title').textContent    = m.title;
    document.getElementById('d-cat').textContent      = catIcon(m) + ' ' + m.cat;
    document.getElementById('d-date').textContent     = fmtDate(m.date);
    document.getElementById('d-time').textContent     = (m.time || '—') + (m.end_time ? ' - ' + m.end_time : '');
    document.getElementById('d-banner-bg').className  = 'det-banner-bg' + (isC ? ' red-bg' : isPast(m) ? ' grey-bg' : '');
    document.getElementById('d-type-badge').textContent = m.type === 'online' ? '💻 عن بعد' : '📍 حضوري';
    document.getElementById('d-av').textContent       = ini(m.presenter);
    document.getElementById('d-pname').textContent    = m.presenter;

    // Location
    const locCell = document.getElementById('d-loc-cell');
    if (locCell) {
        locCell.style.display = (m.type === 'onsite') ? '' : 'none';
        document.getElementById('d-loc').textContent = m.location || '—';
    }

    // Link cell
    const oldLink = document.getElementById('d-link-cell');
    if (oldLink) oldLink.remove();
    if (m.type === 'online' && m.link) {
        const cell = document.createElement('div');
        cell.className = 'det-cell'; cell.id = 'd-link-cell';
        cell.innerHTML = `<div class="det-cell-lbl">رابط الاجتماع</div><div class="det-link-row"><div class="det-link-url">${m.link}</div><button class="join-btn" onclick="window.open('${m.link}','_blank')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>انضم</button></div>`;
        document.getElementById('d-grid')?.appendChild(cell);
    }

    // Notes
    const nw = document.getElementById('d-notes-wrap');
    if (nw) { nw.style.display = m.notes ? 'block' : 'none'; }
    const dn = document.getElementById('d-notes');
    if (dn) dn.textContent = m.notes || '';

    // Report
    const rw = document.getElementById('d-report-wrap');
    if (rw) {
        if (m.report && (m.report.summary || m.report.decisions)) {
            rw.style.display = 'block';
            let html = '';
            if (m.report.summary)   html += `<strong>الملخص:</strong> ${m.report.summary}<br><br>`;
            if (m.report.decisions) html += `<strong>القرارات:</strong> ${m.report.decisions}<br><br>`;
            if (m.report.attendees) html += `<strong>عدد الحضور:</strong> ${m.report.attendees} شخصاً<br>`;
            if (m.report.actions)   html += `<strong>الإجراءات التالية:</strong> ${m.report.actions}`;
            document.getElementById('d-report-content').innerHTML = html;
        } else { rw.style.display = 'none'; }
    }

    // Cancel reason
    const cw = document.getElementById('d-cancel-wrap');
    if (cw) {
        cw.style.display = (isC && m.cancelReason) ? 'block' : 'none';
        const cr = document.getElementById('d-cancel-reason');
        if (cr) cr.textContent = m.cancelReason || '';
    }

    // Join button (online + upcoming)
    const jb = document.getElementById('btn-join-det');
    if (jb) {
        jb.style.display = (m.type === 'online' && m.link && isCur) ? '' : 'none';
    }

    // Attend button
    const aw = document.getElementById('d-attend-wrap');
    if (aw) aw.style.display = (isCur && !isC) ? '' : 'none';
    updateAttendBtn();

    openOv('ov-details');
}

function updateAttendBtn() {
    const btn = document.getElementById('btn-attend');
    if (!btn || viewingId === null) return;
    const isAtt = attendingIds.has(viewingId);
    btn.textContent  = isAtt ? '✅ سأحضر — إلغاء الحضور' : '🗓 تسجيل الحضور';
    btn.className    = isAtt ? 'btn-attend att' : 'btn-attend';
}

/* ── ATTENDANCE TOGGLE ── */
async function toggleAttend() {
    if (viewingId === null) return;
    await doToggle(viewingId);
    updateAttendBtn();
    renderAll();
}

async function quickToggleAttend(id) {
    await doToggle(id);
    if (viewingId === id) updateAttendBtn();
    renderAll();
}

async function doToggle(id) {
    const wasAtt = attendingIds.has(id);
    // Optimistic
    wasAtt ? attendingIds.delete(id) : attendingIds.add(id);
    updateStats();
    try {
        const res = await fetch(`/user/meetings/${id}/attendance`, {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        if (!res.ok) throw new Error('Server error');
        const data = await res.json();
        showToast(data.status === 'attached' ? '✅' : '↩️', data.message);
    } catch {
        // Rollback
        wasAtt ? attendingIds.add(id) : attendingIds.delete(id);
        updateStats();
        showToast('❌', 'حدث خطأ، حاول مجدداً');
    }
}

function joinMeeting() {
    const m = meetings.find(x => x.id === viewingId);
    if (m && m.link) window.open(m.link, '_blank');
}

/* ── MODAL UTILS ── */
function openOv(id)     { document.getElementById(id)?.classList.add('open'); }
function closeOv(id)    { document.getElementById(id)?.classList.remove('open'); }
function bgClose(e, id) { if (e.target === document.getElementById(id)) closeOv(id); }

/* ── TOAST ── */
let tTimer;
function showToast(icon, msg) {
    const el = document.getElementById('toast');
    if (!el) return;
    const ic = document.getElementById('t-icon');
    const tx = document.getElementById('t-msg');
    if (ic) ic.textContent = icon;
    if (tx) tx.textContent = msg;
    el.classList.add('show');
    clearTimeout(tTimer);
    tTimer = setTimeout(() => el.classList.remove('show'), 3000);
}

/* ── KEYBOARD ── */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeOv('ov-details');
});

/* ── INIT ── */
renderAll();
