/* ══════════════════════════════════════════════════
   spa-nav.js  —  تكامل Single-Page Navigation
   يتحكم في التنقل الفوري بين الأقسام بدون إعادة تحميل
══════════════════════════════════════════════════ */

const SECTION_META = {
  volunteer: { title: 'فرص التطوع',         crumb: 'فرص التطوع',         navId: 'nav-volunteer', viewId: 'view-admin' },
  meetings:  { title: 'إدارة الاجتماعات',   crumb: 'إدارة الاجتماعات',   navId: 'nav-meetings',  viewId: 'view-meetings' },
  orders:    { title: 'الطلبات',             crumb: 'الطلبات',             navId: 'nav-orders',    viewId: 'view-orders' },
  projects:  { title: 'المشاريع المشتركة',   crumb: 'المشاريع المشتركة',   navId: 'nav-projects',  viewId: 'view-projects' },
};

let _initializedSections = new Set();

/* ── الدالة الرئيسية للتنقل ── */
function showSection(key) {
  const meta = SECTION_META[key];
  if (!meta) return;

  /* 1. إخفاء كل الـ views */
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));

  /* 2. إظهار الـ view المطلوب */
  const target = document.getElementById(meta.viewId);
  if (target) target.classList.add('active');

  /* 3. تحديث شريط العنوان */
  const titleEl = document.getElementById('topbar-title');
  const crumbEl = document.getElementById('topbar-crumb');
  if (titleEl) titleEl.textContent = meta.title;
  if (crumbEl) crumbEl.textContent = meta.crumb;

  /* 4. تحديث الـ nav الجانبي */
  document.querySelectorAll('.nav-item, .nav-sub-item').forEach(el => el.classList.remove('active'));
  const navEl = document.getElementById(meta.navId);
  if (navEl) navEl.classList.add('active');

  /* 5. إغلاق submenu الخدمات إن كانت مفتوحة (ما لم يكن القسم خدمة) */
  const submenu = document.getElementById('submenu-services');
  const parent  = document.getElementById('np-services');
  if (submenu && !['units','systems','initiatives','training','consulting','contact'].includes(key)) {
    submenu.classList.remove('open');
    if (parent) parent.classList.remove('open');
  }

  /* 6. تهيئة القسم عند أول زيارة */
  if (!_initializedSections.has(key)) {
    _initializedSections.add(key);
    _bootSection(key);
  }

  /* 7. تحديث URL hash للسماح بزر الرجوع */
  if (window.location.hash !== '#' + key) {
    history.pushState(null, '', '#' + key);
  }
}

/* ── تهيئة أولى لكل قسم ── */
function _bootSection(key) {
  switch (key) {
    case 'volunteer':
      if (typeof renderCats   === 'function') renderCats();
      if (typeof updateStats  === 'function') updateStats();
      break;
    case 'meetings':
      if (typeof initMeetings === 'function') initMeetings();
      break;
    case 'orders':
      if (typeof initOrders   === 'function') initOrders();
      break;
    case 'projects':
      if (typeof initProjects === 'function') initProjects();
      break;
  }
}

/* ══ تحديث showService لتتعامل مع النظام الموحد ══ */
const _origShowService = typeof showService !== 'undefined' ? showService : null;
function showService(key) {
  /* إخفاء كل الـ views وإظهار الخدمة المطلوبة */
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
  updateTopbar(labels[key] || key, labels[key] || key);

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
  /* علّم volunteer كمهيأ مسبقاً (consulting.js يهيّأه بنفسه) */
  _initializedSections.add('volunteer');

  /* اقرأ الـ hash من URL لتحديد القسم الابتدائي */
  const hash = window.location.hash.replace('#', '');
  const validSections = Object.keys(SECTION_META);

  if (hash && validSections.includes(hash)) {
    showSection(hash);
  } else {
    /* الافتراضي: فرص التطوع نشطة */
    document.getElementById('nav-volunteer')?.classList.add('active');
  }

  /* مستمع للـ hash changes لتتيح التنقل بزر الرجوع في المتصفح */
  window.addEventListener('hashchange', () => {
    const newHash = window.location.hash.replace('#', '');
    if (newHash && validSections.includes(newHash)) {
      showSection(newHash);
    }
  });

  /* نشط document-level click للـ dropdown في joint-projects */
  document.addEventListener('click', () => {
    if (typeof closeDd === 'function') closeDd();
  });
});
