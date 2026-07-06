/* ── User Dashboard SPA section — populates #view-dashboard from /api/user/dashboard ── */

function udashInit() {
  udashLoadMain();
}

async function udashLoadMain() {
  try {
    const res = await fetch('/api/user/dashboard', {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' },
    });
    if (!res.ok) return;
    const data = await res.json();

    const s = data.stats || {};
    const setNum = (id, val) => {
      const el = document.getElementById(id);
      if (!el) return;
      const target = Number(val || 0);
      el.dataset.target = target;
      udashAnimateCounter(el, target);
    };
    setNum('ud-total-requests', s.total_requests);
    setNum('ud-pending-requests', s.pending_requests);
    setNum('ud-approved-requests', s.approved_requests);
    setNum('ud-projects-count', s.projects_count);
    setNum('ud-upcoming-meetings', s.upcoming_meetings_count);
    setNum('ud-opportunities-count', s.opportunities_count);

    udashRenderRequests(data.latest_requests || []);
    udashRenderMeetings(data.upcoming_meetings || []);
    udashRenderOpportunities(data.latest_opportunities || []);
    udashRenderProjects(data.active_projects || []);
    udashRenderDonut(s);
  } catch (e) { /* silently fail */ }
}

function udashAnimateCounter(el, target) {
  if (!target) { el.textContent = '0'; return; }
  let cur = 0;
  const step = Math.max(1, Math.ceil(target / 30));
  const timer = setInterval(() => {
    cur = Math.min(cur + step, target);
    el.textContent = cur;
    if (cur >= target) clearInterval(timer);
  }, 40);
}

function udashStatusBadge(status) {
  const map = {
    pending:   '<span class="sbdg sb-pending">⏳ قيد المراجعة</span>',
    review:    '<span class="sbdg sb-pending">⏳ قيد المراجعة</span>',
    approved:  '<span class="sbdg sb-approved">✅ مقبول</span>',
    completed: '<span class="sbdg sb-approved">✅ مقبول</span>',
    rejected:  '<span class="sbdg sb-rejected">❌ مرفوض</span>',
  };
  return map[status] || '';
}

function udashTimeAgo(iso) {
  if (!iso) return '';
  return new Date(iso).toLocaleDateString('ar-SA', { day: 'numeric', month: 'short', year: 'numeric' });
}

function udashRenderRequests(items) {
  const list = document.getElementById('recent-reqs');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<div class="dc-empty"><i class="fa-solid fa-paper-plane"></i><p>لا توجد طلبات بعد</p></div>';
    return;
  }
  list.innerHTML = items.map(req => `
    <div class="req-row">
      <div class="rr-type" style="background:${req.color}1a;color:${req.color};">
        <i class="fa-solid ${req.typeIcon}"></i>
      </div>
      <div class="rr-body">
        <div class="rr-title">${req.title}</div>
        <div class="rr-sub">${req.sub}</div>
      </div>
      <div class="rr-right">
        ${udashStatusBadge(req.status)}
        <div class="rr-date">${udashTimeAgo(req.created_at)}</div>
      </div>
    </div>`).join('');
}

function udashRenderMeetings(items) {
  const list = document.getElementById('upcoming-meets');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<div class="dc-empty"><i class="fa-solid fa-calendar"></i><p>لا توجد اجتماعات قادمة</p></div>';
    return;
  }
  list.innerHTML = items.map(m => {
    const d = new Date(m.date_time);
    const day = d.toLocaleDateString('ar-SA', { day: 'numeric' });
    const month = d.toLocaleDateString('ar-SA', { month: 'short' });
    return `
      <a href="#meetings" onclick="if(typeof showSection==='function'){showSection('meetings');return false;}" class="meet-row">
        <div class="mr-date-box">
          <span class="mr-day">${day}</span>
          <span class="mr-month">${month}</span>
        </div>
        <div class="mr-body">
          <div class="mr-title">${m.title}</div>
          <div class="mr-meta">${m.meeting_type === 'online' ? '💻 عن بعد' : '📍 حضوري'}</div>
        </div>
        ${m.link ? '<span class="mr-join">انضم</span>' : ''}
      </a>`;
  }).join('');
}

function udashRenderOpportunities(items) {
  const list = document.getElementById('vol-opps');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<div class="dc-empty"><i class="fa-solid fa-hand-holding-heart"></i><p>لا توجد فرص متاحة</p></div>';
    return;
  }
  list.innerHTML = items.map(o => `
    <a href="#volunteer" onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}" class="opp-row">
      <div class="or-dot" style="background:#2ab8d0"></div>
      <div class="or-body">
        <div class="or-title">${o.title}</div>
        <div class="or-sub">${o.organization || 'تكامل'}</div>
      </div>
      <span class="opp-tag ot-onsite">متاحة</span>
    </a>`).join('');
}

function udashRenderProjects(items) {
  const list = document.getElementById('active-projs');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<div class="dc-empty"><i class="fa-solid fa-diagram-project"></i><p>لا توجد مشاريع نشطة</p></div>';
    return;
  }
  list.innerHTML = items.map(p => {
    const prog = p.progress || 0;
    return `
      <a href="#projects" onclick="if(typeof showSection==='function'){showSection('projects');return false;}" class="proj-row">
        <div class="pr-emoji">🪴</div>
        <div class="pr-body">
          <div class="pr-title">${p.name}</div>
          ${p.category_name ? `<div style="font-size:0.74rem;color:var(--muted);margin-bottom:5px">${p.category_icon || ''} ${p.category_name}</div>` : ''}
          <div class="pr-prog">
            <div class="pr-prog-tr"><div class="pr-prog-fi" style="width:${prog}%;background:#22d3a5"></div></div>
            <span class="pr-pct">${prog}%</span>
          </div>
        </div>
        <span class="pr-status s-active">مستمر</span>
      </a>`;
  }).join('');
}

function udashRenderDonut(s) {
  const el = document.getElementById('reqs-status');
  if (!el) return;

  const total = Number(s.total_requests || 0);
  const pending = Number(s.pending_requests || 0);
  const approved = Number(s.approved_requests || 0);
  const rejected = Number(s.rejected_requests || 0);

  if (total === 0) {
    el.innerHTML = '<div class="dc-empty"><i class="fa-solid fa-chart-pie"></i><p>لا توجد طلبات بعد</p></div>';
    return;
  }

  const p = Math.round;
  const pPend = p(pending / total * 100), pApp = p(approved / total * 100), pRej = p(rejected / total * 100);

  const r = 60;
  const circ = 2 * Math.PI * r;
  const gap = 4;

  function arc(pct, offset, color) {
    if (pct <= 0) return '';
    const len = Math.max(0, circ * pct / 100 - gap);
    return `<circle cx="70" cy="70" r="${r}" fill="none" stroke="${color}" stroke-width="14"
          stroke-dasharray="${len} ${circ}" stroke-dashoffset="${-circ * offset / 100}"
          stroke-linecap="round"/>`;
  }

  const svg = `
      <svg viewBox="0 0 140 140" class="donut-svg">
          <circle cx="70" cy="70" r="${r}" fill="none" stroke="var(--border)" stroke-width="14"/>
          ${arc(pApp, 0, '#0d9488')}
          ${arc(pPend, pApp, '#f59e0b')}
          ${arc(pRej, pApp + pPend, '#ef5350')}
          <text x="70" y="66" text-anchor="middle" font-family="Tajawal" font-size="20" font-weight="900" fill="var(--ink)">${total}</text>
          <text x="70" y="83" text-anchor="middle" font-family="Tajawal" font-size="9" fill="var(--muted)">طلب</text>
      </svg>`;

  el.innerHTML = `
      <div class="donut-wrap">
          ${svg}
          <div class="donut-legend">
          <div class="dl-item"><span class="dl-dot" style="background:#0d9488"></span><span>مقبولة</span><strong>${approved}</strong></div>
          <div class="dl-item"><span class="dl-dot" style="background:#f59e0b"></span><span>قيد المراجعة</span><strong>${pending}</strong></div>
          <div class="dl-item"><span class="dl-dot" style="background:#ef5350"></span><span>مرفوضة</span><strong>${rejected}</strong></div>
          </div>
      </div>
      <div class="donut-bars">
          <div class="db-item">
          <div class="db-labels"><span>مقبولة</span><span style="color:#0d9488">${pApp}%</span></div>
          <div class="db-track"><div class="db-fill" style="width:${pApp}%;background:#0d9488"></div></div>
          </div>
          <div class="db-item">
          <div class="db-labels"><span>قيد المراجعة</span><span style="color:#f59e0b">${pPend}%</span></div>
          <div class="db-track"><div class="db-fill" style="width:${pPend}%;background:#f59e0b"></div></div>
          </div>
          <div class="db-item">
          <div class="db-labels"><span>مرفوضة</span><span style="color:#ef5350">${pRej}%</span></div>
          <div class="db-track"><div class="db-fill" style="width:${pRej}%;background:#ef5350"></div></div>
          </div>
      </div>`;
}
