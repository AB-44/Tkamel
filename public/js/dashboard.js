/* ══════════════════════════════════
   DATA (For Menu Badges)
══════════════════════════════════ */
let opportunities = [
  { id:1, catId:'quran',    title:'معلم حلقات تحفيظ القرآن',      desc:'تعليم الأطفال تلاوة وتحفيظ القرآن الكريم في المساجد',         org:'جمعية نور القرآن',     city:'الرياض',  seats:8,  deadline:'2025-05-30', type:'onsite', status:'open' },
  { id:2, catId:'quran',    title:'مدقق تسجيلات صوتية قرآنية',    desc:'مراجعة وتصحيح التسجيلات الصوتية للطلاب عبر الإنترنت',        org:'أكاديمية البيان',      city:'',        seats:15, deadline:'2025-06-15', type:'remote', status:'open' },
  { id:3, catId:'charity',  title:'موزع مساعدات غذائية',          desc:'المشاركة في توزيع السلال الغذائية على الأسر المستحقة',         org:'جمعية الإحسان',        city:'جدة',     seats:20, deadline:'2025-04-30', type:'onsite', status:'open' },
];

let requests = [
  { id:1, oppId:1, assocName:'جمعية التنمية الاجتماعية', assocCity:'الرياض', message:'لدينا خبرة في تنظيم حلقات التحفيظ ونرغب في الانضمام', date:'2025-03-10', status:'pending' },
];

/* ══ SIDEBAR & TOPBAR INTERACTION ══ */
function toggleServices() {
  const parent  = document.getElementById('np-services');
  const submenu = document.getElementById('submenu-services');
  const isOpen  = submenu.classList.contains('open');
  if (isOpen) {
    submenu.classList.remove('open');
    parent.classList.remove('open');
  } else {
    submenu.classList.add('open');
    parent.classList.add('open');
  }
}

function showService(key) {
  // Update Topbar
  const labels = {
    units:       'بناء وحدات',
    systems:     'بناء أنظمة',
    initiatives: 'تنسيق مبادرات',
    training:    'تدريب تطوعي',
    consulting:  'الاستشارات',
    contact:     'التواصل معنا',
  };
  updateTopbar(labels[key] || key, labels[key] || key);

  // Update Active State
  document.querySelectorAll('.nav-item, .nav-sub-item').forEach(el => el.classList.remove('active'));
  const subEl = document.getElementById('sub-' + key);
  if (subEl) subEl.classList.add('active');

  // Ensure submenu stays open
  document.getElementById('submenu-services').classList.add('open');
  document.getElementById('np-services').classList.add('open');
}

function updateTopbar(title, crumb) {
  const tTitle = document.getElementById('topbar-title');
  const tCrumb = document.getElementById('topbar-crumb');
  if (tTitle) tTitle.textContent = title;
  if (tCrumb) tCrumb.textContent = crumb;
}

function backToVolunteer() {
  updateTopbar('فرص التطوع', 'فرص التطوع');
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
  document.querySelector('[data-vol]')?.classList.add('active');
}

function openMeetingsPage() {
  // Navigation is handled by the href attribute on the <a> tag
  // This function just updates visual state (the href navigates to consulting#meetings)
}

function showAdminRequests() {
  updateTopbar('طلبات التقديم', 'طلبات التقديم');
}

function toggleNotifs() {
  if (typeof window._realToggleNotifs === 'function') window._realToggleNotifs();
}

/* ══ STATS & BADGES ══ */
function updateStats() {
  const open    = opportunities.filter(o => o.status === 'open').length;
  const pending = requests.filter(r => r.status === 'pending').length;
  
  const nbOpps = document.getElementById('nb-opps');
  const nbReqs = document.getElementById('nb-reqs');
  
  if (nbOpps) nbOpps.textContent = open;
  if (nbReqs) nbReqs.textContent = pending;
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

/* ══ CONTACT ══ */
function sendContactMsg() {
  showToast('📨', 'تم إرسال رسالتك! سنردّ خلال 24 ساعة');
}

/* ══ KEYBOARD ══ */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
      // Close sidebars or overlays if needed
  }
});

/* ══ INIT ══ */
document.addEventListener('DOMContentLoaded', () => {
  updateStats();
});
