/* ══════════════════════════════════════════════════════
   services.js  —  خدمات مبادرون  (User Side)
   Handles: list, create, edit, delete service requests
══════════════════════════════════════════════════════ */

'use strict';

// ── State ──────────────────────────────────────────────────────────────────────
let serviceRequests   = [];
let currentDetailReq  = null;

// Status labels and styles used by user side
// NOTE: backend returns already-normalized statuses from the controller:
//   pending | review | approved | rejected
const STATUS_CONFIG = {
    pending:  { label: '⏳ قيد المراجعة',   cls: 's-badge-pending'  },
    review:   { label: '🔄 جارٍ المعالجة',  cls: 's-badge-review'   },
    approved: { label: '✅ مقبول',            cls: 's-badge-approved' },
    rejected: { label: '❌ مرفوض',            cls: 's-badge-rejected' },
};

const TYPE_LABELS = {
    units:       'بناء وحدات/أنظمة',
    training:    'تدريب المتطوعين',
    initiatives: 'تنسيق المبادرات',
    consulting:  'استشارات متخصصة',
    other:       'طلب آخر',
};

// ── Bootstrap ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    fetchServiceRequests();
});

// ── API ────────────────────────────────────────────────────────────────────────
function getCsrf() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : '';
}

async function fetchServiceRequests() {
    try {
        const res = await fetch('/user/service-requests', {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        serviceRequests = await res.json();
        updateServicesStats();
        renderMyReqs();
    } catch (err) {
        console.error('fetchServiceRequests:', err);
        showToast('❌', 'تعذّر تحميل الطلبات');
    }
}

// ── Stats ──────────────────────────────────────────────────────────────────────
function updateServicesStats() {
    const byStatus = (s) => serviceRequests.filter(r => r.status === s).length;
    document.getElementById('st-total').textContent    = serviceRequests.length;
    document.getElementById('st-pending').textContent  = byStatus('pending') + byStatus('review');
    document.getElementById('st-approved').textContent = byStatus('approved');
    document.getElementById('st-rejected').textContent = byStatus('rejected');
}

// ── Render list ────────────────────────────────────────────────────────────────
function renderMyReqs() {
    const container   = document.getElementById('s-req-list');
    const emptyState  = document.getElementById('s-empty-state');

    if (!container) return;

    if (serviceRequests.length === 0) {
        container.innerHTML  = '';
        container.style.display = 'none';
        emptyState.style.display = 'flex';
        return;
    }

    emptyState.style.display = 'none';
    container.style.display  = 'flex';

    container.innerHTML = serviceRequests.map(r => {
        const cfg   = STATUS_CONFIG[r.status] || STATUS_CONFIG.pending;
        const label = TYPE_LABELS[r.type] || 'طلب خدمة';
        return `
        <div class="s-req-item" onclick="openDetailModal(${r.id})" style="cursor:pointer">
            <div class="s-req-info">
                <div class="s-req-title">${escHtml(r.title)}</div>
                <div class="s-req-sub">
                    <span>${escHtml(label)}</span>
                    ${r.date ? ' • <span>' + escHtml(r.date) + '</span>' : ''}
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <span class="s-req-badge ${cfg.cls}">${cfg.label}</span>
                <i class="fa-solid fa-chevron-left" style="color:#94a3b8;font-size:.75rem"></i>
            </div>
        </div>`;
    }).join('');
}

// ── New Request Modal ──────────────────────────────────────────────────────────
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
    if (e.target.id === 'req-modal') closeReqModal();
}

function clearReqForm() {
    const f = (id) => document.getElementById(id);
    f('f-title').value   = '';
    f('f-details').value = '';
    f('f-date').value    = '';
    f('f-budget').value  = '0';
    const firstRad = document.querySelector('input[name="svcType"][value="units"]');
    if (firstRad) firstRad.checked = true;
}

async function submitReq() {
    const type    = document.querySelector('input[name="svcType"]:checked')?.value;
    const title   = document.getElementById('f-title').value.trim();
    const details = document.getElementById('f-details').value.trim();
    const date    = document.getElementById('f-date').value;
    const budget  = document.getElementById('f-budget').value;

    if (!title || !details) {
        showToast('⚠️', 'يرجى تعبئة عنوان الطلب وتفاصيله');
        return;
    }

    const btn    = document.querySelector('.s-btn-submit');
    const oldTxt = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الإرسال...';
    btn.disabled  = true;

    try {
        const res = await fetch('/user/service-requests', {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  getCsrf(),
            },
            body: JSON.stringify({ service_type: type, title, details, preferred_date: date, budget }),
        });

        const data = await res.json();
        if (data.success) {
            closeReqModal();
            showSuccessModal(data.message || 'تم إرسال طلبك بنجاح');
            fetchServiceRequests();
        } else {
            showToast('❌', data.message || 'حدث خطأ');
        }
    } catch {
        showToast('❌', 'حدث خطأ في الاتصال');
    } finally {
        btn.innerHTML = oldTxt;
        btn.disabled  = false;
    }
}

// ── Detail / Edit Modal ────────────────────────────────────────────────────────
function openDetailModal(id) {
    currentDetailReq = serviceRequests.find(r => r.id === id);
    if (!currentDetailReq) return;

    const r   = currentDetailReq;
    const cfg = STATUS_CONFIG[r.status] || STATUS_CONFIG.pending;

    document.getElementById('d-type-label').textContent = TYPE_LABELS[r.type] || r.type;
    document.getElementById('d-title').textContent      = r.title;
    document.getElementById('d-details').textContent    = r.details;
    document.getElementById('d-date').textContent       = r.date   || '—';
    document.getElementById('d-budget').textContent     = r.budget ? r.budget + ' ر.س' : '—';
    document.getElementById('d-status').innerHTML       = `<span class="s-req-badge ${cfg.cls}">${cfg.label}</span>`;

    // Show/hide edit button only when pending
    const editBtn = document.getElementById('d-edit-btn');
    if (editBtn) editBtn.style.display = r.status === 'pending' ? '' : 'none';

    switchToViewMode();
    document.getElementById('detail-modal').classList.add('open');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.remove('open');
    currentDetailReq = null;
}

function bgCloseDetail(e) {
    if (e.target.id === 'detail-modal') closeDetailModal();
}

function switchToViewMode() {
    document.getElementById('d-view-section').style.display  = '';
    document.getElementById('d-edit-section').style.display  = 'none';
    document.getElementById('d-view-footer').style.display   = '';
    document.getElementById('d-edit-footer').style.display   = 'none';
    document.getElementById('d-modal-title').textContent     = 'تفاصيل الطلب';
}

function switchToEditMode() {
    if (!currentDetailReq || currentDetailReq.status !== 'pending') {
        showToast('⚠️', 'لا يمكن تعديل هذا الطلب بعد مراجعته');
        return;
    }
    const r = currentDetailReq;
    const rad = document.querySelector(`input[name="editSvcType"][value="${r.type}"]`);
    if (rad) rad.checked = true;
    document.getElementById('e-title').value   = r.title;
    document.getElementById('e-details').value = r.details;
    document.getElementById('e-date').value    = r.date   || '';
    document.getElementById('e-budget').value  = r.budget || 0;

    document.getElementById('d-view-section').style.display = 'none';
    document.getElementById('d-edit-section').style.display = '';
    document.getElementById('d-view-footer').style.display  = 'none';
    document.getElementById('d-edit-footer').style.display  = '';
    document.getElementById('d-modal-title').textContent    = 'تعديل الطلب';
}

async function saveEdits() {
    if (!currentDetailReq) return;

    const type    = document.querySelector('input[name="editSvcType"]:checked')?.value;
    const title   = document.getElementById('e-title').value.trim();
    const details = document.getElementById('e-details').value.trim();
    const date    = document.getElementById('e-date').value;
    const budget  = document.getElementById('e-budget').value;

    if (!title || !details) {
        showToast('⚠️', 'يرجى تعبئة العنوان والتفاصيل');
        return;
    }

    const btn    = document.getElementById('e-save-btn');
    const oldTxt = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الحفظ...';
    btn.disabled  = true;

    try {
        const res = await fetch(`/user/service-requests/${currentDetailReq.id}`, {
            method:  'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: JSON.stringify({ service_type: type, title, details, preferred_date: date, budget }),
        });

        const data = await res.json();
        if (data.success) {
            closeDetailModal();
            showToast('✅', data.message || 'تم تعديل الطلب');
            fetchServiceRequests();
        } else {
            showToast('❌', data.message || 'حدث خطأ');
        }
    } catch {
        showToast('❌', 'حدث خطأ في الاتصال');
    } finally {
        btn.innerHTML = oldTxt;
        btn.disabled  = false;
    }
}

// ── Delete ─────────────────────────────────────────────────────────────────────
function confirmDeleteReq() {
    document.getElementById('delete-confirm-modal').classList.add('open');
}

function closeDeleteConfirm() {
    document.getElementById('delete-confirm-modal').classList.remove('open');
}

async function executeDelete() {
    if (!currentDetailReq) return;

    try {
        const res = await fetch(`/user/service-requests/${currentDetailReq.id}`, {
            method:  'DELETE',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
        });

        const data = await res.json();
        closeDeleteConfirm();
        closeDetailModal();
        if (data.success) {
            showToast('🗑️', data.message || 'تم حذف الطلب');
            fetchServiceRequests();
        } else {
            showToast('❌', data.message || 'حدث خطأ');
        }
    } catch {
        closeDeleteConfirm();
        showToast('❌', 'حدث خطأ في الاتصال');
    }
}

// ── Toast ──────────────────────────────────────────────────────────────────────
let _toastTimer;
function showToast(icon, msg) {
    const el   = document.getElementById('toast');
    if (!el) return;
    document.getElementById('t-icon').textContent = icon;
    document.getElementById('t-msg').textContent  = msg;
    el.classList.add('show');
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(() => el.classList.remove('show'), 3200);
}

// ── Success Modal ──────────────────────────────────────────────────────────────
function showSuccessModal(msg) {
    const el = document.getElementById('success-modal');
    const t  = document.getElementById('success-title');
    if (t)  t.textContent = msg;
    if (el) el.classList.add('open');
}

function closeSuccessModal() {
    const el = document.getElementById('success-modal');
    if (el) el.classList.remove('open');
}

// ── Sidebar submenu toggle ─────────────────────────────────────────────────────
function toggleServices() {
    document.getElementById('submenu-services')?.classList.toggle('open');
    document.getElementById('np-services')?.classList.toggle('open');
}

// ── Util ───────────────────────────────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
