{{-- layouts/notif-panel-user.blade.php — notification panel for regular users & associations --}}

<div id="notif-panel" class="notif-panel-container" style="display:none;">
  <!-- Header -->
  <div class="notif-header">
    <div class="notif-header-title">الإشعارات</div>
    <div style="display: flex; align-items: center; gap: 8px;">
      <button onclick="window.markAllRead()" style="background: none; border: none; color: #0ea5c9; font-size: 0.85rem; font-weight: 700; cursor: pointer; padding: 0;">
        تحديد الكل كمقروء
      </button>
    </div>
  </div>

  <div id="notif-list" class="notif-list-container">
    <div style="padding:48px 16px;text-align:center;color:#64748b;font-size:.9rem;font-weight:600;">لا توجد إشعارات</div>
  </div>
</div>

<style>
  /* ── Panel Container ── */
  .notif-panel-container {
    flex-direction: column; position: fixed; top: 0; right: auto; left: auto;
    width: 290px; max-height: 370px; background: #f8fafc;
    border: none; border-radius: 12px;
    box-shadow: 0 20px 50px rgba(0,0,0,.15), 0 0 0 1px rgba(0,0,0,.05);
    z-index: 99999; overflow: hidden;
  }

  /* ── Header ── */
  .notif-header {
    background: white;
    padding: 10px 14px 8px;
    display: flex; justify-content: space-between; align-items: center;
    color: #0f172a; flex-shrink: 0;
    border-bottom: 1px solid #f1f5f9;
  }
  .notif-header-title {
    font-family: 'Tajawal', sans-serif; font-size: 0.95rem; font-weight: 800;
  }

  /* ── List Area ── */
  .notif-list-container {
    overflow-y: auto; flex: 1; padding: 6px; display: flex; flex-direction: column; gap: 6px;
  }

  /* ── Notification rows ── */
  .notif-card {
    background: white; border-radius: 8px; padding: 8px 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.02); border: 1px solid #f1f5f9;
    cursor: pointer; transition: all .2s; position: relative;
    display: flex; justify-content: space-between; align-items: flex-start;
  }
  .notif-card:hover { border-color: #cbd5e1; box-shadow: 0 2px 8px rgba(0,0,0,.06); transform: translateY(-1px); }
  .notif-card.unread { border-right: 3px solid #f59e0b; background: #fffcf8; }

  .notif-card-right {
    display: flex; gap: 8px; align-items: flex-start; width: 100%;
  }
  .notif-avatar {
    width: 32px; height: 32px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; flex-shrink: 0;
  }
  .notif-avatar.purple { background: #f3e8ff; color: #a855f7; }
  .notif-avatar.blue   { background: #eff6ff; color: #3b82f6; }
  .notif-avatar.green  { background: #ecfdf5; color: #10b981; }
  .notif-avatar.amber  { background: #fef3c7; color: #f59e0b; }

  .notif-details { display: flex; flex-direction: column; gap: 1px; flex: 1; min-width: 0; }
  .notif-title { font-weight: 800; color: #0f172a; font-size: 0.8rem; line-height: 1.3; }
  .notif-subtitle { color: #64748b; font-size: 0.72rem; line-height: 1.4; margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .notif-time { color: #9ca3af; font-size: 0.65rem; margin-top: 4px; font-weight: 600; }

  /* X delete button */
  .notif-delete-btn {
    position: absolute; top: 6px; left: 6px;
    width: 18px; height: 18px; border-radius: 50%;
    background: transparent; border: none;
    color: #cbd5e1; font-size: 0.75rem; line-height: 1;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; padding: 0;
  }
  .notif-delete-btn:hover { background: #fee2e2; color: #ef4444; }

  /* ── Notification bell button ── */
  .notif-btn {
    position:relative; width:36px; height:36px; border-radius:10px;
    background:var(--fog,#f0f9ff); border:1px solid var(--border,rgba(0,0,0,.08));
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; transition:all .2s;
  }
  .notif-btn:hover { border-color:rgba(14,165,201,.3); background:#fff; }
  .notif-btn svg { color:var(--muted,#64748b); }
  .notif-btn.has-unread svg { color:var(--teal,#0c6080); }

  .notif-dot {
    position:absolute; top:6px; right:6px;
    width:8px; height:8px; background:#ef5350;
    border-radius:50%; border:2px solid white; pointer-events:none;
  }
  .notif-count-badge {
    position:absolute; top:-4px; right:-4px;
    min-width:18px; height:18px;
    background:#ef5350; color:white;
    border-radius:20px; border:2px solid white;
    font-size:.62rem; font-weight:900;
    display:flex; align-items:center; justify-content:center;
    padding:0 4px; pointer-events:none;
  }
</style>

<script>
(function() {
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
  let notifOpen     = false;
  let notifications = [];

  function timeAgo(d) {
    const diff = Math.floor((Date.now() - new Date(d)) / 1000);
    if (diff < 60)    return 'الآن';
    if (diff < 3600)  return `منذ ${Math.floor(diff/60)} دقيقة`;
    if (diff < 86400) return `منذ ${Math.floor(diff/3600)} ساعة`;
    return `منذ ${Math.floor(diff/86400)} يوم`;
  }

  function getCategoryConfig(n) {
    if (n.type === 'meeting_created') {
      return { colorClass: 'blue', label: 'اجتماع جديد', icon: '<i class="fa-solid fa-calendar-check"></i>' };
    }
    if (n.type === 'service_request_approved') {
      return { colorClass: 'green', label: 'تمت الموافقة', icon: '<i class="fa-solid fa-circle-check"></i>' };
    }
    if (n.type === 'opportunity_approved' || n.type === 'project_join_approved') {
      return { colorClass: 'green', label: 'تمت الموافقة', icon: '<i class="fa-solid fa-circle-check"></i>' };
    }
    if (n.type === 'opportunity_rejected' || n.type === 'project_join_rejected') {
      return { colorClass: 'amber', label: 'تم الرفض', icon: '<i class="fa-solid fa-circle-xmark"></i>' };
    }
    if (n.type === 'service_request_created' || n.title?.includes('طلب خدمة')) {
      return { colorClass: 'purple', label: 'طلب خدمة', icon: '<i class="fa-solid fa-wand-magic-sparkles"></i>' };
    }
    return { colorClass: 'blue', label: 'إشعار', icon: '<i class="fa-solid fa-bell"></i>' };
  }

  function renderNotifs() {
    const list = document.getElementById('notif-list');
    if (!list) return;
    if (!notifications.length) {
      list.innerHTML = '<div style="padding:48px 16px;text-align:center;color:#64748b;font-size:.9rem;font-weight:600;">لا توجد إشعارات</div>';
      return;
    }

    list.innerHTML = notifications.map(n => {
      const { colorClass, label, icon } = getCategoryConfig(n);
      return `
      <div class="notif-card ${n.is_read ? '' : 'unread'}" onclick="window._onNotifClick('${n.id}', ${n.is_read}, '${n.type}')">
        <button class="notif-delete-btn" onclick="event.stopPropagation(); window._deleteNotif('${n.id}')" title="حذف">×</button>
        <div class="notif-card-right">
          <div class="notif-avatar ${colorClass}">
            ${icon}
          </div>
          <div class="notif-details">
            <div class="notif-title">${n.title}</div>
            <div class="notif-subtitle">${n.body}</div>
            <div class="notif-time">${timeAgo(n.created_at)}</div>
          </div>
        </div>
      </div>`;
    }).join('');
  }

  function updateBadge(count) {
    const dot   = document.getElementById('notif-dot');
    const badge = document.getElementById('notif-count-badge');
    const btn   = document.getElementById('notif-btn');
    const headerWrapper = document.getElementById('notif-new-count-badge-wrapper');
    const headerBadge   = document.getElementById('notif-new-count-badge');

    if (count > 0) {
      if (dot)   dot.style.display   = 'block';
      if (badge) { badge.style.display = 'flex'; badge.textContent = count > 99 ? '99+' : count; }
      if (btn)   btn.classList.add('has-unread');
      if (headerWrapper && headerBadge) {
        headerWrapper.style.display = 'flex';
        headerBadge.textContent = count;
      }
    } else {
      if (dot)   dot.style.display   = 'none';
      if (badge) badge.style.display = 'none';
      if (btn)   btn.classList.remove('has-unread');
      if (headerWrapper) headerWrapper.style.display = 'none';
    }
  }

  async function loadNotifs() {
    try {
      const res = await fetch('/api/user/notifications', {
        credentials: 'same-origin', headers: { 'Accept': 'application/json' }
      });
      if (!res.ok) return;
      const data = await res.json();
      notifications = data.notifications || [];
      updateBadge(data.unread_count || 0);
      renderNotifs();
    } catch(e) {}
  }

  function _positionNotifPanel() {
    const btn   = document.getElementById('notif-btn');
    const panel = document.getElementById('notif-panel');
    if (!btn || !panel) return;
    const rect    = btn.getBoundingClientRect();
    const margin  = 10;
    const panelW  = 380;
    const panelH  = 520;

    let top = rect.bottom + 12;
    if (top + panelH > window.innerHeight - margin) top = rect.top - panelH - 12;
    top = Math.max(margin, top);

    let left = rect.left;
    left = Math.min(left, window.innerWidth - panelW - margin);
    left = Math.max(margin, left);

    panel.style.top  = top + 'px';
    panel.style.left = left + 'px';
    panel.style.right = 'auto';
  }

  // _realToggleNotifs is what proxy wrappers in dashboard.js / consulting.js / menu.js call
  window._realToggleNotifs = function() {
    notifOpen = !notifOpen;
    const panel = document.getElementById('notif-panel');
    if (!panel) return;
    if (notifOpen) {
      panel.style.display = 'flex';
      _positionNotifPanel();
      loadNotifs();
    } else {
      panel.style.display = 'none';
    }
  };

  // Also wire toggleNotifs directly — but use the proxy pattern so late-loading JS
  // files (orders.js etc.) that redefine toggleNotifs() as proxy still work.
  window.toggleNotifs = function() {
    if (typeof window._realToggleNotifs === 'function') window._realToggleNotifs();
  };

  window.addEventListener('resize', () => { if (notifOpen) _positionNotifPanel(); });

  window._onNotifClick = async function(id, isRead, type) {
    if (!isRead) {
      await fetch(`/api/user/notifications/${id}/read`, {
        method: 'POST', credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
      }).catch(() => {});
      const target = notifications.find(x => String(x.id) === String(id));
      if (target) target.is_read = true;
      updateBadge(notifications.filter(x => !x.is_read).length);
      renderNotifs();
    }
    // Navigate to the relevant page
    const targetNotif = notifications.find(x => String(x.id) === String(id));
    const reqId = targetNotif ? (targetNotif.related_id || '') : '';
    const q = `?req_id=${reqId}&type=${type}`;

    if (type === 'meeting_created') {
      window.location.href = '/user/meetings' + q;
    } else {
      window.location.href = '/user/my-requests' + q;
    }
  };

  window.markAllRead = async function() {
    await fetch('/api/user/notifications/read-all', {
      method: 'POST', credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    }).catch(() => {});
    notifications.forEach(n => n.is_read = true);
    updateBadge(0);
    renderNotifs();
  };

  window._deleteNotif = async function(id) {
    await fetch(`/api/user/notifications/${id}`, {
      method: 'DELETE', credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    }).catch(() => {});
    notifications = notifications.filter(x => String(x.id) !== String(id));
    updateBadge(notifications.filter(x => !x.is_read).length);
    renderNotifs();
  };

  window.clearAllNotifs = async function() {
    await fetch('/api/user/notifications/clear-all', {
      method: 'POST', credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    }).catch(() => {});
    notifications = [];
    updateBadge(0);
    renderNotifs();
  };

  // Close when clicking outside
  document.addEventListener('click', e => {
    if (notifOpen && !e.target.closest('#notif-btn') && !e.target.closest('#notif-panel')) {
      notifOpen = false;
      const panel = document.getElementById('notif-panel');
      if (panel) panel.style.display = 'none';
    }
  });

  loadNotifs();
  setInterval(loadNotifs, 30000);
  document.addEventListener('visibilitychange', () => { if (!document.hidden) loadNotifs(); });
})();
</script>
