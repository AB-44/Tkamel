/* ══════════════════════════════════════════════════
   user-spa-nav.js  —  تكامل Single-Page Navigation (حساب المستخدم)
   نسخة مستقلة عن spa-nav.js (الخاص بالأدمن) تتحكم في التنقل
   الفوري بين أقسام حساب المستخدم/الجمعية بدون إعادة تحميل.
══════════════════════════════════════════════════ */

const USER_SECTION_META = {
  dashboard: { title: 'لوحة التحكم',      crumb: 'لوحة التحكم',      navId: 'unav-dashboard', viewId: 'view-dashboard' },
  meetings:  { title: 'الاجتماعات',        crumb: 'الاجتماعات',        navId: 'unav-meetings',  viewId: 'view-meetings' },
  volunteer: { title: 'فرص التطوع',        crumb: 'فرص التطوع',        navId: 'unav-volunteer', viewId: 'view-admin' },
  services:  { title: 'خدمات مبادرون',     crumb: 'خدمات مبادرون',     navId: 'unav-services',  viewId: 'view-services' },
  orders:    { title: 'طلباتي',            crumb: 'طلباتي',            navId: 'unav-orders',    viewId: 'view-orders' },
  projects:  { title: 'المشاريع المشتركة', crumb: 'المشاريع المشتركة', navId: 'unav-projects',  viewId: 'view-projects' },
  settings:  { title: 'الإعدادات',         crumb: 'الإعدادات',         navId: 'unav-settings',  viewId: 'view-settings' },
};

let _uInitializedSections = new Set();

/* ── منع تسابق الطلبات عند التنقل السريع بين الأقسام ── */
let _uSectionLoadTimer = null;
let _uLoadGeneration = 0;
function _uScheduleSectionLoad(fn, delay = 180) {
  const myGen = ++_uLoadGeneration;
  clearTimeout(_uSectionLoadTimer);
  _uSectionLoadTimer = setTimeout(() => {
    if (myGen === _uLoadGeneration) fn();
  }, delay);
}

/* ── الدالة الرئيسية للتنقل (متوافقة مع onclick="showSection(...)" الموجودة في partials) ── */
function showSection(key) {
  const meta = USER_SECTION_META[key];
  if (!meta) return;

  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));

  const target = document.getElementById(meta.viewId);
  if (target) target.classList.add('active');

  const titleEl = document.getElementById('topbar-title');
  const crumbEl = document.getElementById('topbar-crumb');
  if (titleEl) titleEl.textContent = meta.title;
  if (crumbEl) crumbEl.textContent = meta.crumb;

  document.querySelectorAll('.nav-item, .nav-sub-item').forEach(el => el.classList.remove('active'));
  const navEl = document.getElementById(meta.navId);
  if (navEl) navEl.classList.add('active');

  /* إغلاق submenu الخدمات إن كانت مفتوحة (ما لم يكن القسم خدمة) */
  const submenu = document.getElementById('submenu-services');
  const parent  = document.getElementById('np-services');
  if (submenu && !['units','systems','initiatives','training','consulting','contact','services'].includes(key)) {
    submenu.classList.remove('open');
    if (parent) parent.classList.remove('open');
  }

  if (!_uInitializedSections.has(key)) {
    _uInitializedSections.add(key);
    _uScheduleSectionLoad(() => _uBootSection(key));
  } else {
    _uScheduleSectionLoad(() => _uRefreshSection(key));
  }

  if (window.location.hash !== '#' + key) {
    history.pushState(null, '', '#' + key);
  }
}

/* ── تهيئة أولى لكل قسم ── */
function _uBootSection(key) {
  switch (key) {
    case 'dashboard':
      if (typeof udashInit === 'function') udashInit();
      break;
    case 'meetings':
      if (typeof mtgUserInit === 'function') mtgUserInit();
      break;
    case 'volunteer':
      if (typeof renderCats  === 'function') renderCats();
      if (typeof updateStats === 'function') updateStats();
      break;
    case 'services':
      if (typeof fetchServiceRequests === 'function') fetchServiceRequests();
      break;
    case 'orders':
      if (typeof ordersUserInit === 'function') ordersUserInit();
      break;
    case 'projects':
      if (typeof loadAll === 'function') loadAll();
      break;
    case 'settings':
      if (typeof settingsUserInit === 'function') settingsUserInit();
      break;
  }
}

/* ── تحديث البيانات عند العودة للقسم ── */
function _uRefreshSection(key) {
  switch (key) {
    case 'dashboard':
      if (typeof udashLoadMain === 'function') udashLoadMain();
      break;
    case 'meetings':
      if (typeof mtgUserRefresh === 'function') mtgUserRefresh();
      break;
    case 'volunteer':
      if (typeof fetchOpportunities === 'function') fetchOpportunities();
      if (typeof fetchRequests      === 'function') fetchRequests();
      break;
    case 'services':
      if (typeof fetchServiceRequests === 'function') fetchServiceRequests();
      break;
    case 'orders':
      if (typeof ordersUserRefresh === 'function') ordersUserRefresh();
      break;
    case 'projects':
      if (typeof loadAll === 'function') loadAll();
      break;
    case 'settings':
      if (typeof settingsUserRefresh === 'function') settingsUserRefresh();
      break;
  }
}

/* ══ تحديث showService لتتعامل مع النظام الموحد (أقسام خدمات مبادرون الفرعية) ══ */
function showService(key) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const target = document.getElementById('view-' + key);
  if (target) target.classList.add('active');

  const labels = {
    units:       'بناء وحدات',
    systems:     'بناء أنظمة',
    initiatives: 'تنسيق مبادرات',
    training:    'تدريب تطوعي',
    consulting:  'الاستشارات',
    contact:     'التواصل معنا',
  };
  const titleEl = document.getElementById('topbar-title');
  const crumbEl = document.getElementById('topbar-crumb');
  if (titleEl) titleEl.textContent = labels[key] || key;
  if (crumbEl) crumbEl.textContent = labels[key] || key;

  document.querySelectorAll('.nav-item, .nav-sub-item').forEach(el => el.classList.remove('active'));
  const subEl = document.getElementById('sub-' + key);
  if (subEl) subEl.classList.add('active');

  const submenu = document.getElementById('submenu-services');
  const parent  = document.getElementById('np-services');
  if (submenu) submenu.classList.add('open');
  if (parent)  parent.classList.add('open');
}

/* ══ backToVolunteer: العودة لفرص التطوع ══ */
function backToVolunteer() {
  showSection('volunteer');
}

/* ══ تهيئة: تشغيل القسم الصحيح عند التحميل ══ */
document.addEventListener('DOMContentLoaded', () => {
  const hash = window.location.hash.replace('#', '');
  const validSections = Object.keys(USER_SECTION_META);

  if (hash && validSections.includes(hash)) {
    showSection(hash);
  } else {
    showSection('dashboard');
  }

  window.addEventListener('hashchange', () => {
    const newHash = window.location.hash.replace('#', '');
    if (newHash && validSections.includes(newHash)) {
      showSection(newHash);
    }
  });

  document.addEventListener('click', () => {
    if (typeof closeDd === 'function') closeDd();
  });
});
