/* ── Dashboard SPA section — populates #view-dashboard from /api/dashboard ── */

function dashInit() {
  dashLoadMain();
  dashRefreshPending();
  if (!window._dashPendingInterval) {
    window._dashPendingInterval = setInterval(dashRefreshPending, 30000);
  }
}

async function dashLoadMain() {
  try {
    const res = await fetch('/api/dashboard', {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' },
    });
    if (!res.ok) return;
    const data = await res.json();

    const s = data.stats || {};
    const setNum = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = Number(val || 0).toLocaleString('en-US'); };
    setNum('dash-assoc-count', s.associations_count);
    setNum('dash-opp-count', s.opportunities_count);
    setNum('dash-proj-count', s.projects_count);
    setNum('dash-completed-count', s.completed_requests);

    dashRenderMeetings(data.upcoming_meetings || []);
    dashRenderProjects(data.active_projects || []);
    dashRenderOpportunities(data.latest_opportunities || []);
    dashRenderOppRequests(data.latest_opp_requests || []);
    dashRenderProjApps(data.latest_proj_apps || []);
  } catch (e) { /* silently fail */ }
}

function dashRenderMeetings(items) {
  const list = document.getElementById('dash-meetings-list');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<p style="text-align:center; padding: 1rem; color: #888;">لا توجد اجتماعات قادمة</p>';
    return;
  }
  list.innerHTML = items.map(m => {
    const d = new Date(m.date_time);
    const day = d.toLocaleDateString('ar-SA', { day: 'numeric', month: 'short' });
    const time = d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    const catStr = m.category ? `🏢 ${m.category} • ` : '';
    const iconClass = m.type === 'online' ? 'fa-video' : 'fa-users';
    return `
      <li class="dw-item">
        <div class="dw-icon" style="background:rgba(29,111,164,0.12);color:var(--blue)"><i class="fa-solid ${iconClass}"></i></div>
        <div class="dw-info">
          <div class="dw-name">${m.title}</div>
          <div class="dw-meta">${catStr}${day} • ${time}</div>
        </div>
        <a href="#meetings" onclick="if(typeof showSection==='function'){showSection('meetings');return false;}" class="dw-action">انضمام</a>
      </li>`;
  }).join('');
}

function dashRenderProjects(items) {
  const list = document.getElementById('dash-projects-list');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<p style="text-align:center; padding: 1rem; color: #888;">لا توجد مشاريع مشتركة نشطة</p>';
    return;
  }
  list.innerHTML = items.map(p => {
    const metaParts = [];
    if (p.category_name) metaParts.push(`${p.category_icon} ${p.category_name}`);
    if (p.start_date) metaParts.push(`بدأ ${p.start_date}`);
    return `
      <li class="dw-item">
        <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-diagram-project"></i></div>
        <div class="dw-info">
          <div class="dw-name">${p.name}</div>
          <div class="dw-meta">${metaParts.join(' • ')}</div>
        </div>
        <span class="dw-badge ${p.status_color}">${p.status_label}</span>
      </li>`;
  }).join('');
}

function dashRenderOpportunities(items) {
  const list = document.getElementById('dash-opps-list');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<p style="text-align:center; padding: 1rem; color: #888;">لا توجد فرص تطوع ومبادرات</p>';
    return;
  }
  list.innerHTML = items.map(o => {
    const dirLabel = o.direction === 'remote' ? '💻 عن بعد' : (o.direction === 'both' ? '🔄 مزدوج' : '📍 حضوري');
    const metaParts = [];
    if (o.category_name) metaParts.push(`${o.category_icon} ${o.category_name}`);
    metaParts.push(dirLabel);
    if (o.deadline) metaParts.push(`حتى ${new Date(o.deadline).toLocaleDateString('ar-SA', { day:'numeric', month:'short' })}`);
    return `
      <li class="dw-item">
        <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706"><i class="fa-solid fa-hand-holding-heart"></i></div>
        <div class="dw-info">
          <div class="dw-name">${o.title}</div>
          <div class="dw-meta">${metaParts.join(' • ')}</div>
        </div>
        <span class="dw-badge ${o.is_closed ? 'rejected' : 'approved'}">${o.is_closed ? 'منتهية' : 'مفتوحة'}</span>
      </li>`;
  }).join('');
}

function dashTimeAgo(iso) {
  const diffMs = Date.now() - new Date(iso).getTime();
  const mins = Math.floor(diffMs / 60000);
  if (mins < 1) return 'الآن';
  if (mins < 60) return `قبل ${mins} دقيقة`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `قبل ${hrs} ساعة`;
  const days = Math.floor(hrs / 24);
  return `قبل ${days} يوم`;
}

function dashRenderOppRequests(items) {
  const list = document.getElementById('dash-opp-reqs-list');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<p style="text-align:center; padding: 1rem; color: #888;">لا توجد طلبات فرص معلقة ✅</p>';
    return;
  }
  list.innerHTML = items.map(r => `
    <li class="dw-item">
      <div class="dw-icon" style="background:rgba(245,158,11,0.12);color:#d97706"><i class="fa-solid fa-user-check"></i></div>
      <div class="dw-info">
        <div class="dw-name">${r.title}</div>
        <div class="dw-meta">مقدم من: ${r.applicant} • ${dashTimeAgo(r.created_at)}</div>
      </div>
      <span class="dw-badge pending">⏳ معلق</span>
    </li>`).join('');
}

function dashRenderProjApps(items) {
  const list = document.getElementById('dash-proj-apps-list');
  if (!list) return;
  if (!items.length) {
    list.innerHTML = '<p style="text-align:center; padding: 1rem; color: #888;">لا توجد طلبات مشاريع معلقة ✅</p>';
    return;
  }
  list.innerHTML = items.map(a => `
    <li class="dw-item">
      <div class="dw-icon" style="background:rgba(46,170,120,0.12);color:var(--green)"><i class="fa-solid fa-people-group"></i></div>
      <div class="dw-info">
        <div class="dw-name">${a.title}</div>
        <div class="dw-meta">مقدم من: ${a.applicant} • ${dashTimeAgo(a.created_at)}</div>
      </div>
      <span class="dw-badge pending">⏳ معلق</span>
    </li>`).join('');
}

async function dashRefreshPending() {
  try {
    const res = await fetch('/api/association-requests?status=pending', {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' },
    });
    if (!res.ok) return;
    const data = await res.json();
    const pendingCount = Array.isArray(data) ? data.length : 0;

    const nb = document.getElementById('nb-reqs');
    if (nb) nb.textContent = pendingCount > 0 ? pendingCount : '';

    const alertEl = document.getElementById('dash-pending-alert');
    if (alertEl) {
      alertEl.style.display = pendingCount > 0 ? 'flex' : 'none';
      const txt = document.getElementById('dash-pending-text');
      if (txt && pendingCount > 0) {
        txt.textContent = `${pendingCount} طلب تسجيل جمعية جديدة بانتظار موافقتك`;
      }
    }

    const list = document.getElementById('dash-recent-reqs');
    if (list) {
      if (pendingCount > 0) {
        const colors = { pending:'pending', approved:'approved', rejected:'rejected' };
        const labels = { pending:'قيد المراجعة', approved:'مقبول', rejected:'مرفوض' };
        const icons  = ['fa-file-signature','fa-handshake','fa-building-circle-check'];
        const bgIcons= ['rgba(109,40,217,0.12)','rgba(46,170,120,0.12)','rgba(29,111,164,0.12)'];
        const fgIcons= ['var(--purple)','var(--green)','var(--blue)'];
        list.innerHTML = data.slice(0,3).map((a,i)=>`
          <li class="dw-item">
            <div class="dw-icon" style="background:${bgIcons[i%3]};color:${fgIcons[i%3]}"><i class="fa-solid ${icons[i%3]}"></i></div>
            <div class="dw-info">
              <div class="dw-name">${a.association_name}</div>
              <div class="dw-meta">${a.manager_name} • ${a.category}</div>
            </div>
            <span class="dw-badge ${colors[a.status]||'pending'}">${labels[a.status]||'معلق'}</span>
          </li>`).join('');
      } else {
        list.innerHTML = '';
      }
    }
  } catch (e) { /* silently fail */ }
}
