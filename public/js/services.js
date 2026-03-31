/* ══════════════════════════════════
   services.js
   Logic for Mobaderoon Services Dashboard
══════════════════════════════════ */

let serviceRequests = [];
let nextServiceReqId = 1;

document.addEventListener('DOMContentLoaded', () => {
    updateServicesStats();
    renderMyReqs();
});

/* ── UI Toggles ── */
function openNewReq(serviceType = null) {
    clearReqForm();
    if (serviceType) {
        const rad = document.querySelector(`input[name="svcType"][value="${serviceType}"]`);
        if (rad) rad.checked = true;
    }
    document.getElementById('req-modal').classList.add('open');
}

function closeReqModal() {
    document.getElementById('req-modal').classList.remove('open');
}

function bgCloseReq(e) {
    if (e.target.id === 'req-modal') {
        closeReqModal();
    }
}

function clearReqForm() {
    document.getElementById('f-title').value = '';
    document.getElementById('f-details').value = '';
    document.getElementById('f-date').value = '';
    document.getElementById('f-budget').value = '0';
    document.querySelector('input[name="svcType"][value="units"]').checked = true;
}

/* ── Logic ── */
function submitReq() {
    const type = document.querySelector('input[name="svcType"]:checked').value;
    const title = document.getElementById('f-title').value.trim();
    const details = document.getElementById('f-details').value.trim();
    const date = document.getElementById('f-date').value;
    const budget = document.getElementById('f-budget').value;

    if (!title || !details) {
        showToast('⚠️', 'يرجى تعبئة عنوان الطلب وتفاصيله');
        return;
    }

    const typeLabels = {
        'units': 'بناء وحدات/أنظمة',
        'training': 'تدريب المتطوعين',
        'initiatives': 'تنسيق المبادرات',
        'consulting': 'استشارات متخصصة',
        'other': 'طلب آخر'
    };

    const newReq = {
        id: nextServiceReqId++,
        type: type,
        typeLabel: typeLabels[type] || 'طلب خدمة',
        title: title,
        details: details,
        date: date || new Date().toISOString().split('T')[0],
        budget: budget,
        status: 'pending' // pending, approved, rejected
    };

    serviceRequests.unshift(newReq);
    
    closeReqModal();
    showToast('✨', 'تم إرسال الطب بنجاح');
    
    updateServicesStats();
    renderMyReqs();
}

function renderMyReqs() {
    const container = document.getElementById('s-req-list');
    const emptyState = document.getElementById('s-empty-state');
    
    if (serviceRequests.length === 0) {
        container.innerHTML = '';
        container.style.display = 'none';
        emptyState.style.display = 'flex';
        return;
    }

    emptyState.style.display = 'none';
    container.style.display = 'flex';
    
    container.innerHTML = serviceRequests.map(r => {
        let statusBadge = '';
        if (r.status === 'pending') statusBadge = '<span class="s-req-badge s-badge-pending">⏳ قيد المراجعة</span>';
        else if (r.status === 'approved') statusBadge = '<span class="s-req-badge s-badge-approved">✅ مقبول</span>';
        else if (r.status === 'rejected') statusBadge = '<span class="s-req-badge s-badge-rejected">❌ مرفوض</span>';

        return `
        <div class="s-req-item">
            <div class="s-req-info">
                <div class="s-req-title">${r.title}</div>
                <div class="s-req-sub">
                    <span>${r.typeLabel}</span> • <span>${r.date}</span>
                </div>
            </div>
            <div>
                ${statusBadge}
            </div>
        </div>`;
    }).join('');
}

function updateServicesStats() {
    const total = serviceRequests.length;
    const pending = serviceRequests.filter(r => r.status === 'pending').length;
    const approved = serviceRequests.filter(r => r.status === 'approved').length;
    const rejected = serviceRequests.filter(r => r.status === 'rejected').length;

    document.getElementById('st-total').textContent = total;
    document.getElementById('st-pending').textContent = pending;
    document.getElementById('st-approved').textContent = approved;
    document.getElementById('st-rejected').textContent = rejected;
}

/* ══ SIDEBAR SUBMENU ══ */
function toggleServices() {
    const sub = document.getElementById('submenu-services');
    const np  = document.getElementById('np-services');
    if (sub) sub.classList.toggle('open');
    if (np)  np.classList.toggle('open');
}

/* ══ TOAST ══ */
let tTimer;
function showToast(icon, msg) {
  const el = document.getElementById('toast');
  if (!el) return;
  const tIcon = document.getElementById('t-icon');
  const tMsg = document.getElementById('t-msg');
  if (tIcon) tIcon.textContent = icon;
  if (tMsg) tMsg.textContent  = msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer = setTimeout(() => el.classList.remove('show'), 3200);
}
