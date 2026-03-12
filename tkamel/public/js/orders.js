// ===================== DATA =====================
const avatarColors = ['#1f6feb', '#2ea043', '#d29922', '#cf222e', '#8957e5', '#0891b2', '#b45309', '#0e7490'];

const requests = [
  { id: 'REQ-001', name: 'أحمد محمد الزهراني', email: 'ahmed.z@example.com', type: 'مسؤول جمعية', assoc: 'جمعية البر والتقوى', date: '2025-12-01', status: 'pending' },
  { id: 'REQ-002', name: 'فاطمة علي السلمي', email: 'fatima.s@example.com', type: 'عضو', assoc: 'جمعية رواد المعرفة', date: '2025-12-03', status: 'approved' },
  { id: 'REQ-003', name: 'خالد إبراهيم العمري', email: 'khalid.o@example.com', type: 'مسؤول جمعية', assoc: 'جمعية البيئة الخضراء', date: '2025-12-05', status: 'review' },
  { id: 'REQ-004', name: 'نورا سعد القرني', email: 'nora.q@example.com', type: 'عضو', assoc: 'جمعية الأمل الصحي', date: '2025-12-06', status: 'approved' },
  { id: 'REQ-005', name: 'عبدالله يوسف المالكي', email: 'abd.m@example.com', type: 'مشرف', assoc: 'جمعية الرياضة للجميع', date: '2025-12-08', status: 'rejected' },
  { id: 'REQ-006', name: 'ريم محمد الدوسري', email: 'reem.d@example.com', type: 'عضو', assoc: 'جمعية الثقافة والفنون', date: '2025-12-09', status: 'pending' },
  { id: 'REQ-007', name: 'بدر سلمان الحربي', email: 'badr.h@example.com', type: 'مسؤول جمعية', assoc: 'جمعية التعليم المستدام', date: '2025-12-10', status: 'approved' },
  { id: 'REQ-008', name: 'هند عبدالعزيز الشمري', email: 'hend.sh@example.com', type: 'عضو', assoc: 'جمعية البر والتقوى', date: '2025-12-11', status: 'pending' },
  { id: 'REQ-009', name: 'طارق فهد العتيبي', email: 'tarek.a@example.com', type: 'مشرف', assoc: 'جمعية بناة الوطن', date: '2025-12-12', status: 'review' },
  { id: 'REQ-010', name: 'منى حسن القحطاني', email: 'mona.q@example.com', type: 'عضو', assoc: 'جمعية الأمل الصحي', date: '2025-12-13', status: 'approved' },
];

const categories = [
  { name: 'تعليمية', icon: '🎓', color: '#1f6feb', bg: 'rgba(31,111,235,.15)', count: 8, fill: 80 },
  { name: 'خيرية', icon: '❤️', color: '#f85149', bg: 'rgba(207,34,46,.15)', count: 14, fill: 100 },
  { name: 'بيئية', icon: '🌿', color: '#3fb950', bg: 'rgba(46,160,67,.15)', count: 5, fill: 36 },
  { name: 'صحية', icon: '🏥', color: '#58a6ff', bg: 'rgba(31,111,235,.12)', count: 7, fill: 50 },
  { name: 'رياضية', icon: '⚽', color: '#bc8cff', bg: 'rgba(137,87,229,.15)', count: 10, fill: 71 },
  { name: 'ثقافية', icon: '🎭', color: '#e3b341', bg: 'rgba(210,153,34,.15)', count: 6, fill: 43 },
];

const associations = [
  { name: 'جمعية البر والتقوى', cat: 'خيرية', icon: '❤️', bg: 'rgba(207,34,46,.15)', members: 240, status: 'نشطة', date: '2020-03-10' },
  { name: 'جمعية رواد المعرفة', cat: 'تعليمية', icon: '🎓', bg: 'rgba(31,111,235,.15)', members: 185, status: 'نشطة', date: '2019-07-22' },
  { name: 'جمعية البيئة الخضراء', cat: 'بيئية', icon: '🌿', bg: 'rgba(46,160,67,.15)', members: 97, status: 'نشطة', date: '2021-01-15' },
  { name: 'جمعية الأمل الصحي', cat: 'صحية', icon: '🏥', bg: 'rgba(31,111,235,.12)', members: 312, status: 'نشطة', date: '2018-11-05' },
  { name: 'جمعية الرياضة للجميع', cat: 'رياضية', icon: '⚽', bg: 'rgba(137,87,229,.15)', members: 420, status: 'نشطة', date: '2017-04-30' },
  { name: 'جمعية الثقافة والفنون', cat: 'ثقافية', icon: '🎭', bg: 'rgba(210,153,34,.15)', members: 130, status: 'نشطة', date: '2022-06-18' },
  { name: 'جمعية التعليم المستدام', cat: 'تعليمية', icon: '📚', bg: 'rgba(31,111,235,.15)', members: 220, status: 'نشطة', date: '2016-09-01' },
  { name: 'جمعية بناة الوطن', cat: 'خيرية', icon: '🏗️', bg: 'rgba(207,34,46,.15)', members: 158, status: 'موقوفة', date: '2015-02-14' },
  { name: 'جمعية سلامة الغذاء', cat: 'صحية', icon: '🥗', bg: 'rgba(31,111,235,.12)', members: 89, status: 'نشطة', date: '2023-03-20' },
  { name: 'جمعية الشباب الرياضي', cat: 'رياضية', icon: '🏆', bg: 'rgba(137,87,229,.15)', members: 375, status: 'نشطة', date: '2019-12-11' },
];

// ===================== RENDER REQUESTS =====================
const statusMap = {
  pending: { label: 'قيد المراجعة', cls: 'badge-pending' },
  approved: { label: 'موافق عليها', cls: 'badge-approved' },
  rejected: { label: 'مرفوض', cls: 'badge-rejected' },
  review: { label: 'مراجعة إضافية', cls: 'badge-review' },
};

function initials(name) {
  return name.split(' ').slice(0, 2).map(w => w[0]).join('');
}

function renderRequests(data) {
  const tbody = document.getElementById('requestsTbody');
  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><div class="emoji">📭</div><div>لا توجد طلبات مطابقة</div></div></td></tr>`;
    return;
  }
  tbody.innerHTML = data.map((r, i) => {
    const s = statusMap[r.status];
    const col = avatarColors[i % avatarColors.length];
    return `
      <tr>
        <td style="color:var(--text-muted);font-size:.8rem;">${r.id}</td>
        <td>
          <div class="user-cell">
            <div class="avatar" style="background:${col}">${initials(r.name)}</div>
            <div>
              <div class="user-name">${r.name}</div>
              <div class="user-email">${r.email}</div>
            </div>
          </div>
        </td>
        <td>${r.type}</td>
        <td style="color:var(--text)">${r.assoc}</td>
        <td>${formatDate(r.date)}</td>
        <td><span class="badge ${s.cls}">${s.label}</span></td>
        <td>
          <div class="action-group">
            <button class="action-btn" title="عرض التفاصيل" onclick='openModal(${JSON.stringify(r)})'>👁️</button>
            <button class="action-btn approve" title="موافقة">✓</button>
            <button class="action-btn reject" title="رفض">✕</button>
          </div>
        </td>
      </tr>`;
  }).join('');
}

function formatDate(d) {
  const dt = new Date(d);
  return dt.toLocaleDateString('ar-SA', { year: 'numeric', month: 'short', day: 'numeric' });
}

// ===================== FILTER =====================
let activeStatus = '';
let searchVal = '';

function filterTable(val) {
  searchVal = val.toLowerCase();
  applyFilter();
}

function filterByStatus(val) {
  activeStatus = val;
  applyFilter();
}

function applyFilter() {
  let data = requests;
  if (activeStatus) data = data.filter(r => r.status === activeStatus);
  if (searchVal) data = data.filter(r =>
    r.name.toLowerCase().includes(searchVal) ||
    r.email.toLowerCase().includes(searchVal) ||
    r.assoc.toLowerCase().includes(searchVal)
  );
  renderRequests(data);
}

// ===================== RENDER CATEGORIES =====================
function renderCategories() {
  const allCard = `
    <div class="category-card" onclick="filterAssocByCat('')" style="display:flex; align-items:center; gap:1.5rem; background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal) 100%); color: white; border: none;">
      <div class="category-icon" style="background:rgba(255,255,255,0.2); margin-bottom:0; font-size:1.5rem;">🌍</div>
      <div>
        <div class="category-name" style="color:white; margin-bottom:0;">جميع التصنيفات</div>
        <div class="category-count" style="color:rgba(255,255,255,0.7);">عرض كل الجمعيات</div>
      </div>
    </div>
  `;
  document.getElementById('categoriesGrid').innerHTML = allCard + categories.map(c => `
    <div class="category-card" onclick="filterAssocByCat('${c.name}')">
      <div class="category-icon" style="background:${c.bg};">${c.icon}</div>
      <div class="category-name">${c.name}</div>
      <div class="category-count">${c.count} جمعية مسجلة</div>
      <div class="category-bar">
        <div class="category-fill" style="width:${c.fill}%;background:${c.color};"></div>
      </div>
    </div>
  `).join('');
}

// ===================== RENDER ASSOCIATIONS =====================
function renderAssoc(data) {
  document.getElementById('assocList').innerHTML = data.map(a => `
    <div class="assoc-item">
      <div class="assoc-logo" style="background:${a.bg};">${a.icon}</div>
      <div class="assoc-info">
        <div class="assoc-name">${a.name}</div>
        <div class="assoc-cat">📁 ${a.cat}</div>
      </div>
      <div class="assoc-meta">
        <span>👥 ${a.members} عضو</span>
        <span>📅 ${formatDate(a.date)}</span>
        <span class="badge ${a.status === 'نشطة' ? 'badge-approved' : 'badge-rejected'}">${a.status}</span>
      </div>
    </div>
  `).join('');
}

function filterAssoc() {
  const val = document.getElementById('catFilter').value;
  renderAssoc(val ? associations.filter(a => a.cat === val) : associations);
}

function filterAssocByCat(cat) {
  switchTab('associations', document.querySelectorAll('.tab-btn')[1]);
  document.getElementById('catFilter').value = cat;
  renderAssoc(cat ? associations.filter(a => a.cat === cat) : associations);
  if (cat) {
    setTimeout(() => document.getElementById('assocList').scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
  }
}

// ===================== TABS =====================
function switchTab(id, btn) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).classList.add('active');
  btn.classList.add('active');
}

// ===================== MODAL =====================
function openModal(r) {
  const s = statusMap[r.status];
  document.getElementById('modalBody').innerHTML = `
    <div class="detail-row"><span class="detail-label">رقم الطلب</span><span class="detail-value">${r.id}</span></div>
    <div class="detail-row"><span class="detail-label">الاسم الكامل</span><span class="detail-value">${r.name}</span></div>
    <div class="detail-row"><span class="detail-label">البريد الإلكتروني</span><span class="detail-value">${r.email}</span></div>
    <div class="detail-row"><span class="detail-label">نوع الحساب</span><span class="detail-value">${r.type}</span></div>
    <div class="detail-row"><span class="detail-label">الجمعية</span><span class="detail-value">${r.assoc}</span></div>
    <div class="detail-row"><span class="detail-label">تاريخ الطلب</span><span class="detail-value">${formatDate(r.date)}</span></div>
    <div class="detail-row"><span class="detail-label">الحالة الحالية</span><span class="detail-value"><span class="badge ${s.cls}">${s.label}</span></span></div>
  `;
  document.getElementById('modal').classList.add('open');
}

function closeModal() {
  document.getElementById('modal').classList.remove('open');
}

document.getElementById('modal').addEventListener('click', function (e) {
  if (e.target === this) closeModal();
});

// ===================== SIDEBAR & MENU =====================
function toggleServices() {
  const parent = document.getElementById('np-services');
  const submenu = document.getElementById('submenu-services');
  const isOpen = submenu.classList.contains('open');
  if (isOpen) {
    submenu.classList.remove('open');
    parent.classList.remove('open');
  } else {
    submenu.classList.add('open');
    parent.classList.add('open');
  }
}

function showService(key) {
  // Logic to show different services or views
  // For this page, we might just update the topbar or navigate
  const labels = {
    units: 'بناء وحدات',
    systems: 'بناء أنظمة',
    initiatives: 'تنسيق مبادرات',
    training: 'تدريب تطوعي',
    consulting: 'الاستشارات',
    contact: 'التواصل معنا',
  };
  updateTopbar(labels[key] || key, labels[key] || key);

  document.querySelectorAll('.nav-item, .nav-sub-item').forEach(el => el.classList.remove('active'));
  const subEl = document.getElementById('sub-' + key);
  if (subEl) subEl.classList.add('active');

  document.getElementById('submenu-services').classList.add('open');
  document.getElementById('np-services').classList.add('open');

  showToast('ℹ️', 'جاري الانتقال إلى: ' + (labels[key] || key));
}

function updateTopbar(title, crumb) {
  const tTitle = document.getElementById('topbar-title');
  const tCrumb = document.getElementById('topbar-crumb');
  if (tTitle) tTitle.textContent = title;
  if (tCrumb) tCrumb.textContent = crumb;
}

function showAdminRequests(el) {
  switchTab('requests', document.querySelectorAll('.tab-btn')[0]);
  updateTopbar('طلبات إنشاء الحساب', 'طلبات إنشاء الحساب');

  document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
  if (el) {
    el.classList.add('active');
  } else {
    // Fallback if called without element (e.g. from script)
    document.querySelector('a[onclick="showAdminRequests(this)"]')?.classList.add('active');
  }
}

function openMeetingsPage() {
  showToast('📅', 'جاري فتح صفحة الاجتماعات...');
  // Navigation would happen here
}

function backToVolunteer() {
  showToast('🏠', 'العودة للرئيسية');
}

function toggleNotifs() {
  const dot = document.getElementById('notif-dot');
  if (dot) dot.style.display = 'none';
  showToast('🔔', 'لا توجد تنبيهات جديدة');
}

function updateSidebarBadges() {
  const pendingCount = requests.filter(r => r.status === 'pending').length;
  const nbReqs = document.getElementById('nb-reqs');
  if (nbReqs) nbReqs.textContent = pendingCount;

  const nbOpps = document.getElementById('nb-opps');
  if (nbOpps) nbOpps.textContent = categories.length;
}

function showToast(icon, msg) {
  // Simple toast implementation if needed, or just console log
  console.log(`${icon} ${msg}`);
}

// ===================== INIT =====================
document.addEventListener('DOMContentLoaded', () => {
  renderRequests(requests);
  renderCategories();
  renderAssoc(associations);
  updateSidebarBadges();
  updateTopbar('صفحة الطلبات', 'إدارة الطلبات');
});
