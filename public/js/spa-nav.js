/* ══════════════════════════════════════════════════
   spa-nav.js  —  تكامل Single-Page Navigation
   يتحكم في التنقل الفوري بين الأقسام بدون إعادة تحميل
══════════════════════════════════════════════════ */

const SECTION_META = {
  dashboard: { title: 'لوحة التحكم',        crumb: 'لوحة التحكم',        navId: 'nav-dashboard', viewId: 'view-dashboard' },
  volunteer: { title: 'فرص التطوع',         crumb: 'فرص التطوع',         navId: 'nav-volunteer', viewId: 'view-admin' },
  meetings:  { title: 'إدارة الاجتماعات',   crumb: 'إدارة الاجتماعات',   navId: 'nav-meetings',  viewId: 'view-meetings' },
  orders:    { title: 'الطلبات',             crumb: 'الطلبات',             navId: 'nav-orders',    viewId: 'view-orders' },
  projects:  { title: 'المشاريع المشتركة',   crumb: 'المشاريع المشتركة',   navId: 'nav-projects',  viewId: 'view-projects' },
  settings:  { title: 'الملف الشخصي',        crumb: 'الملف الشخصي',        navId: 'nav-settings',  viewId: 'view-settings' },
};

let _initializedSections = new Set();

/* ── منع تسابق الطلبات عند التنقل السريع بين الأقسام ──
   كل تبديل سريع يلغي مؤقت التحميل السابق ويبدأ مؤقتًا جديدًا،
   فلا يُنفَّذ التحميل الفعلي إلا بعد استقرار المستخدم على قسم واحد
   لمدة قصيرة. هذا يمنع وصول استجابات قديمة (stale responses)
   بترتيب عشوائي وتسبّبها في اختفاء/فلاش البيانات. */
let _sectionLoadTimer = null;
let _loadGeneration = 0;
function _scheduleSectionLoad(fn, delay = 180) {
  const myGen = ++_loadGeneration;
  clearTimeout(_sectionLoadTimer);
  _sectionLoadTimer = setTimeout(() => {
    /* تأكد أن هذا آخر طلب جدولة (أي لم يحدث تنقل أحدث منه) */
    if (myGen === _loadGeneration) fn();
  }, delay);
}

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

  /* 6. تهيئة القسم عند أول زيارة + تحديث البيانات عند كل زيارة
        (مجدولة عبر مؤقت لتفادي تسابق الطلبات عند التنقل السريع) */
  if (!_initializedSections.has(key)) {
    _initializedSections.add(key);
    _scheduleSectionLoad(() => _bootSection(key));
  } else {
    // إعادة تحميل البيانات عند كل عودة للقسم لتجنب اختفاء البيانات
    _scheduleSectionLoad(() => _refreshSection(key));
  }

  /* 7. تحديث URL hash للسماح بزر الرجوع */
  if (window.location.hash !== '#' + key) {
    history.pushState(null, '', '#' + key);
  }
}

/* ── تهيئة أولى لكل قسم ── */
function _bootSection(key) {
  switch (key) {
    case 'dashboard':
      if (typeof dashInit === 'function') dashInit();
      break;
    case 'volunteer':
      if (typeof renderCats   === 'function') renderCats();
      if (typeof updateStats  === 'function') updateStats();
      break;
    case 'meetings':
      if (typeof mtgLoadMeetings === 'function') mtgLoadMeetings();
      break;
    case 'orders':
      if (typeof initOrders   === 'function') initOrders();
      break;
    case 'projects':
      if (typeof loadAll === 'function') loadAll();
      break;
    case 'settings':
      if (typeof settingsInit === 'function') settingsInit();
      break;
  }
}

/* ── تحديث البيانات عند العودة للقسم (لمنع اختفاء البيانات) ── */
function _refreshSection(key) {
  switch (key) {
    case 'dashboard':
      // إعادة جلب بيانات لوحة التحكم عند العودة
      if (typeof dashLoadMain === 'function') dashLoadMain();
      break;
    case 'volunteer':
      // إعادة جلب الفرص والطلبات عند العودة لضمان البيانات محدّثة
      if (typeof fetchOpportunities === 'function') fetchOpportunities();
      if (typeof fetchRequests      === 'function') fetchRequests();
      break;
    case 'orders':
      // إعادة جلب طلبات الجمعيات عند العودة
      if (typeof loadAssociationRequests === 'function') loadAssociationRequests();
      break;
    case 'meetings':
      // إعادة جلب الاجتماعات عند العودة
      if (typeof mtgLoadMeetings === 'function') mtgLoadMeetings();
      break;
    case 'projects':
      // إعادة جلب المشاريع عند العودة
      if (typeof loadAll === 'function') loadAll();
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
  /* اقرأ الـ hash من URL لتحديد القسم الابتدائي */
  const hash = window.location.hash.replace('#', '');
  const validSections = Object.keys(SECTION_META);

  if (hash && validSections.includes(hash)) {
    /* للمشاريع أو الطلبات: لا تُهيئ volunteer أولاً */
    _initializedSections.add('volunteer');
    showSection(hash);
  } else {
    /* الافتراضي: فرص التطوع */
    showSection('volunteer');
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
