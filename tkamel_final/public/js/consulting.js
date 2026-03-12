/* ══════════════════════════════════
   DATA
══════════════════════════════════ */
const CATEGORIES = [
  { id:'quran',    name:'الجمعيات القرآنية',   icon:'📖', color:'#7b4ea6', desc:'حلقات تحفيظ وتدريس علوم القرآن الكريم' },
  { id:'charity',  name:'خيرية واجتماعية',     icon:'🤝', color:'#2ab8d0', desc:'دعم الأسر المحتاجة وتنمية المجتمع' },
  { id:'health',   name:'صحية وبيئية',         icon:'🌿', color:'#2eaa78', desc:'رعاية صحية وحماية البيئة' },
  { id:'culture',  name:'ثقافية وتعليمية',     icon:'📚', color:'#3a72b8', desc:'نشر المعرفة وتطوير التعليم' },
  { id:'sports',   name:'رياضية وشبابية',      icon:'⚽', color:'#e65100', desc:'دعم الشباب والأنشطة الرياضية' },
  { id:'religious',name:'دينية ودعوية',        icon:'🕌', color:'#1a6b7c', desc:'التوعية الدينية والنشاط الدعوي' },
];

let opportunities = [
  { id:1, catId:'quran',    title:'معلم حلقات تحفيظ القرآن',      desc:'تعليم الأطفال تلاوة وتحفيظ القرآن الكريم في المساجد',         org:'جمعية نور القرآن',     city:'الرياض',  seats:8,  deadline:'2025-05-30', type:'onsite', status:'open' },
  { id:2, catId:'quran',    title:'مدقق تسجيلات صوتية قرآنية',    desc:'مراجعة وتصحيح التسجيلات الصوتية للطلاب عبر الإنترنت',        org:'أكاديمية البيان',      city:'',        seats:15, deadline:'2025-06-15', type:'remote', status:'open' },
  { id:3, catId:'charity',  title:'موزع مساعدات غذائية',          desc:'المشاركة في توزيع السلال الغذائية على الأسر المستحقة',         org:'جمعية الإحسان',        city:'جدة',     seats:20, deadline:'2025-04-30', type:'onsite', status:'open' },
  { id:4, catId:'health',   title:'مرشد بيئي',                    desc:'توعية المجتمع بأهمية المحافظة على البيئة وتنظيف الشواطئ',      org:'جمعية خضراء',          city:'الدمام',  seats:12, deadline:'2025-05-10', type:'onsite', status:'open' },
  { id:5, catId:'culture',  title:'مدرّس محو أمية',               desc:'تعليم القراءة والكتابة للكبار في مراكز التعليم المجتمعي',      org:'جمعية نور المعرفة',    city:'مكة',     seats:6,  deadline:'2025-07-01', type:'both',   status:'open' },
  { id:6, catId:'sports',   title:'مدرّب رياضي للناشئين',         desc:'تدريب الأطفال والشباب على مهارات كرة القدم والألعاب الجماعية', org:'جمعية الشباب الرياضي', city:'الرياض',  seats:4,  deadline:'2025-05-20', type:'onsite', status:'open' },
];

let requests = [
  { id:1, oppId:1, assocName:'جمعية التنمية الاجتماعية', assocCity:'الرياض', message:'لدينا خبرة في تنظيم حلقات التحفيظ ونرغب في الانضمام', date:'2025-03-10', status:'pending' },
  { id:2, oppId:3, assocName:'جمعية الرعاية الأسرية',    assocCity:'جدة',    message:'نعمل في مجال دعم الأسر منذ 5 سنوات',                  date:'2025-03-08', status:'approved' },
  { id:3, oppId:5, assocName:'جمعية النهضة التعليمية',   assocCity:'مكة',    message:'لدينا فريق متخصص في التعليم المجتمعي',                date:'2025-03-12', status:'pending' },
];

let nextOppId     = 7;
let nextReqId     = 4;
let editingOppId  = null;
let deletingOppId = null;
let applyingOppId = null;
let currentCatId  = null;
let currentRole   = 'admin';
let reqFilter     = 'pending';

/* ══ RENDER CATEGORIES ══ */
function renderCats() {
  const grid  = document.getElementById('cats-grid');
  const agrid = document.getElementById('assoc-cats-grid');

  const adminHtml = CATEGORIES.map(c => {
    const cnt = opportunities.filter(o => o.catId === c.id && o.status === 'open').length;
    return `
    <div class="cat-card" style="--cc:${c.color}">
      <div class="cat-card-header" onclick="openCatAdmin('${c.id}')">
        <div class="cat-card-bg" style="background:${c.color}"></div>
        <span class="cat-card-icon">${c.icon}</span>
        <div class="cat-card-name">${c.name}</div>
        <div class="cat-card-desc">${c.desc}</div>
      </div>
      <div class="cat-card-footer">
        <div class="cat-opp-count" onclick="openCatAdmin('${c.id}')" style="cursor:pointer;flex:1">
          <div class="cat-opp-dot" style="background:${c.color}"></div>
          <span style="color:${c.color}">${cnt}</span> فرصة متاحة
        </div>
      </div>
    </div>`;
  }).join('');

  const assocHtml = CATEGORIES.map(c => {
    const cnt = opportunities.filter(o => o.catId === c.id && o.status === 'open').length;
    return `
    <div class="cat-card" style="--cc:${c.color}" onclick="openCatAssoc('${c.id}')">
      <div class="cat-card-header">
        <div class="cat-card-bg" style="background:${c.color}"></div>
        <span class="cat-card-icon">${c.icon}</span>
        <div class="cat-card-name">${c.name}</div>
        <div class="cat-card-desc">${c.desc}</div>
      </div>
      <div class="cat-card-footer">
        <div class="cat-opp-count">
          <div class="cat-opp-dot" style="background:${c.color}"></div>
          <span style="color:${c.color}">${cnt}</span> فرصة متاحة
        </div>
        <div class="cat-arrow">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M15 18l-6-6 6-6"/></svg>
        </div>
      </div>
    </div>`;
  }).join('');

  if (grid)  grid.innerHTML  = adminHtml;
  if (agrid) agrid.innerHTML = assocHtml;
  updateStats();
}

/* ══ VIEWS ══ */
function showView(id) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}

function showAdminMain() {
  showView('view-admin');
  updateTopbar('فرص التطوع', 'فرص التطوع');
}

function showAdminRequests() {
  showView('view-admin-reqs');
  updateTopbar('طلبات التقديم', 'طلبات التقديم');
  renderRequests();
}

function openCatAdmin(catId) {
  currentCatId = catId;
  const cat = CATEGORIES.find(c => c.id === catId);
  document.getElementById('ao-cat-icon').textContent = cat.icon;
  document.getElementById('ao-cat-name').textContent = cat.name;
  document.getElementById('ao-title').textContent    = 'فرص ' + cat.name;
  document.getElementById('ao-sub').textContent      = cat.desc;
  showView('view-admin-opps');
  updateTopbar(cat.name, cat.name);
  renderAdminOpps();
}

function openCatAssoc(catId) {
  currentCatId = catId;
  const cat = CATEGORIES.find(c => c.id === catId);
  document.getElementById('ao-cat-icon2').textContent = cat.icon;
  document.getElementById('ao-cat-name2').textContent = cat.name;
  document.getElementById('ao-title2').textContent    = 'فرص ' + cat.name;
  showView('view-assoc-opps');
  updateTopbar(cat.name, cat.name);
  renderAssocOpps();
}

function backToCategories() {
  showView('view-admin');
  updateTopbar('فرص التطوع', 'فرص التطوع');
}

function backToAssocCats() {
  showView('view-assoc');
  updateTopbar('فرص التطوع', 'فرص التطوع');
}

function updateTopbar(title, crumb) {
  document.getElementById('topbar-title').textContent = title;
  document.getElementById('topbar-crumb').textContent = crumb;
}

/* ══ ADMIN OPPS ══ */
function renderAdminOpps() {
  const q    = (document.getElementById('admin-opp-search')?.value || '').toLowerCase();
  const list = opportunities.filter(o => o.catId === currentCatId && (!q || o.title.toLowerCase().includes(q)));
  const grid = document.getElementById('admin-opps-grid');
  grid.innerHTML = list.length
    ? list.map(o => oppCardAdmin(o)).join('')
    : `<div class="empty-state"><span class="ei">🔍</span><h3>لا توجد فرص في هذا التصنيف</h3><p>اضغط "إضافة فرصة" لإنشاء أول فرصة</p></div>`;
}

function oppCardAdmin(o) {
  const cat = CATEGORIES.find(c => c.id === o.catId);
  const acc = cat?.color || '#2ab8d0';
  const typeLabel = o.type === 'onsite' ? '📍 حضوري' : o.type === 'remote' ? '💻 عن بعد' : '🔄 مزدوج';
  const typeBadge = o.type === 'onsite' ? 'b-onsite' : 'b-remote';
  const appCnt = requests.filter(r => r.oppId === o.id).length;
  return `
  <div class="opp-card">
    <div class="opp-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
    <div class="opp-body">
      <div class="opp-row1">
        <div class="opp-badges">
          <span class="badge ${o.status === 'open' ? 'b-open' : 'b-closed'}">${o.status === 'open' ? '✅ مفتوحة' : '🔒 مغلقة'}</span>
          <span class="badge ${typeBadge}">${typeLabel}</span>
        </div>
        <div class="opp-actions">
          <button class="icn-btn edit" onclick="openEditOpp(${o.id})" title="تعديل">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="icn-btn del" onclick="openDelOpp(${o.id})" title="حذف">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6M9 6V4h6v2"/></svg>
          </button>
        </div>
      </div>
      <div class="opp-title">${o.title}</div>
      <div class="opp-desc">${o.desc}</div>
      <div class="opp-meta">
        ${o.city ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${o.city}</div>` : ''}
        <div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>${o.seats} مقعد</div>
        ${o.deadline ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>حتى ${o.deadline}</div>` : ''}
      </div>
    </div>
    <div class="opp-foot">
      <div class="opp-org"><div class="org-av">${o.org[0]}</div><div><div class="org-name">${o.org}</div></div></div>
      <div style="font-size:0.76rem;font-weight:700;color:var(--teal);background:rgba(42,184,208,0.1);padding:4px 10px;border-radius:8px">${appCnt} طلب</div>
    </div>
  </div>`;
}

/* ══ ASSOC OPPS ══ */
function renderAssocOpps() {
  const q    = (document.getElementById('assoc-opp-search')?.value || '').toLowerCase();
  const list = opportunities.filter(o => o.catId === currentCatId && o.status === 'open' && (!q || o.title.toLowerCase().includes(q)));
  const grid = document.getElementById('assoc-opps-grid');
  grid.innerHTML = list.length
    ? list.map(o => oppCardAssoc(o)).join('')
    : `<div class="empty-state"><span class="ei">🔍</span><h3>لا توجد فرص متاحة</h3><p>لا توجد فرص مفتوحة في هذا التصنيف حالياً</p></div>`;
}

function oppCardAssoc(o) {
  const cat    = CATEGORIES.find(c => c.id === o.catId);
  const acc    = cat?.color || '#2ab8d0';
  const myReq  = requests.find(r => r.oppId === o.id && r.assocName === 'جمعية التنمية الاجتماعية');
  const typeLabel = o.type === 'onsite' ? '📍 حضوري' : o.type === 'remote' ? '💻 عن بعد' : '🔄 مزدوج';
  const typeBadge = o.type === 'onsite' ? 'b-onsite' : 'b-remote';

  let footBtn = '';
  if (myReq) {
    if (myReq.status === 'pending')       footBtn = `<span class="btn-applied">⏳ تحت المراجعة</span>`;
    else if (myReq.status === 'approved') footBtn = `<span class="btn-applied" style="color:var(--green)">✅ مقبول</span>`;
    else                                  footBtn = `<span class="btn-applied" style="color:var(--red)">❌ مرفوض</span>`;
  } else {
    footBtn = `<button class="btn-apply" onclick="openApply(${o.id})">📝 تقديم</button>`;
  }

  return `
  <div class="opp-card">
    <div class="opp-stripe" style="background:linear-gradient(90deg,${acc},${acc}88)"></div>
    <div class="opp-body">
      <div class="opp-row1">
        <div class="opp-badges">
          <span class="badge b-open">✅ مفتوحة</span>
          <span class="badge ${typeBadge}">${typeLabel}</span>
        </div>
      </div>
      <div class="opp-title">${o.title}</div>
      <div class="opp-desc">${o.desc}</div>
      <div class="opp-meta">
        ${o.city ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${o.city}</div>` : ''}
        <div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>${o.seats} مقعد</div>
        ${o.deadline ? `<div class="opp-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>حتى ${o.deadline}</div>` : ''}
      </div>
    </div>
    <div class="opp-foot">
      <div class="opp-org"><div class="org-av">${o.org[0]}</div><div><div class="org-name">${o.org}</div></div></div>
      ${footBtn}
    </div>
  </div>`;
}

/* ══ REQUESTS ══ */
function filterReqs(f) {
  reqFilter = f;
  ['pending', 'approved', 'rejected'].forEach(x =>
    document.getElementById('rtab-' + x).classList.toggle('active', x === f)
  );
  renderRequests();
}

function renderRequests() {
  const filtered = requests.filter(r => r.status === reqFilter);
  const list = document.getElementById('req-list');
  if (!filtered.length) {
    list.innerHTML = `<div class="empty-state"><span class="ei">${reqFilter === 'pending' ? '⏳' : reqFilter === 'approved' ? '✅' : '❌'}</span><h3>لا توجد طلبات ${reqFilter === 'pending' ? 'معلقة' : reqFilter === 'approved' ? 'مقبولة' : 'مرفوضة'}</h3></div>`;
    return;
  }
  list.innerHTML = filtered.map(r => {
    const opp = opportunities.find(o => o.id === r.oppId);
    const cat = opp ? CATEGORIES.find(c => c.id === opp.catId) : null;
    const barColor    = r.status === 'pending' ? '#f59e0b' : r.status === 'approved' ? '#2eaa78' : '#c62828';
    const statusBadge = r.status === 'pending'
      ? `<span class="req-status-badge rsb-pending">⏳ معلق</span>`
      : r.status === 'approved'
      ? `<span class="req-status-badge rsb-approved">✅ مقبول</span>`
      : `<span class="req-status-badge rsb-rejected">❌ مرفوض</span>`;
    const actionBtns = r.status === 'pending'
      ? `<button class="btn-approve" onclick="approveReq(${r.id})">✅ قبول</button>
         <button class="btn-reject"  onclick="rejectReq(${r.id})">❌ رفض</button>`
      : '';
    return `
    <div class="req-card">
      <div class="req-card-inner">
        <div class="req-status-bar" style="background:${barColor}"></div>
        <div class="req-opp-info">
          <div class="req-opp-title">${opp?.title || '—'}</div>
          <div class="req-opp-cat">${cat?.icon || ''} ${cat?.name || '—'}</div>
        </div>
        <div class="req-assoc-info">
          <div class="req-assoc-av">${r.assocName[0]}</div>
          <div>
            <div class="req-assoc-name">${r.assocName}</div>
            <div class="req-assoc-sub">${r.assocCity || '—'}</div>
          </div>
        </div>
        <div class="req-meta">
          <div class="req-date">${r.date}</div>
          ${statusBadge}
        </div>
        <div class="req-actions-col">${actionBtns}</div>
      </div>
      ${r.message ? `<div style="padding:0 20px 14px 20px;font-size:0.8rem;color:var(--muted);border-top:1px solid var(--border);margin-top:0;padding-top:10px">"${r.message}"</div>` : ''}
    </div>`;
  }).join('');
  updateReqCounts();
}

function approveReq(id) {
  const r = requests.find(x => x.id === id);
  r.status = 'approved';
  renderRequests();
  updateStats();
  showToast('✅', 'تم قبول الطلب وإشعار الجمعية');
}

function rejectReq(id) {
  const r = requests.find(x => x.id === id);
  r.status = 'rejected';
  renderRequests();
  updateStats();
  showToast('❌', 'تم رفض الطلب وإشعار الجمعية');
}

/* ══ ADD / EDIT OPP ══ */
function openAddOpp() {
  editingOppId = null;
  const cat = CATEGORIES.find(c => c.id === currentCatId);
  document.getElementById('opp-m-icon').textContent   = '🌟';
  document.getElementById('opp-m-title').textContent  = 'إضافة فرصة تطوع';
  document.getElementById('opp-m-sub').textContent    = 'فرصة جديدة في تصنيف: ' + cat.name;
  document.getElementById('opp-save-lbl').textContent = '💾 إضافة الفرصة';
  document.getElementById('sel-cat-badge').textContent = cat.icon + ' ' + cat.name;
  clearOppForm();
  openOv('ov-opp');
}

function openEditOpp(id) {
  const o = opportunities.find(x => x.id === id);
  if (!o) return;
  editingOppId = id;
  const cat = CATEGORIES.find(c => c.id === o.catId);
  document.getElementById('opp-m-icon').textContent   = '✏️';
  document.getElementById('opp-m-title').textContent  = 'تعديل الفرصة';
  document.getElementById('opp-m-sub').textContent    = o.title;
  document.getElementById('opp-save-lbl').textContent = '💾 حفظ التعديلات';
  document.getElementById('sel-cat-badge').textContent = cat.icon + ' ' + cat.name;
  document.getElementById('f-opp-title').value    = o.title;
  document.getElementById('f-opp-desc').value     = o.desc;
  document.getElementById('f-opp-org').value      = o.org;
  document.getElementById('f-opp-city').value     = o.city;
  document.getElementById('f-opp-seats').value    = o.seats;
  document.getElementById('f-opp-deadline').value = o.deadline;
  document.getElementById('f-opp-type').value     = o.type;
  document.getElementById('f-opp-status').value   = o.status;
  openOv('ov-opp');
}

function clearOppForm() {
  ['f-opp-title', 'f-opp-desc', 'f-opp-org', 'f-opp-city', 'f-opp-seats', 'f-opp-deadline'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  document.getElementById('f-opp-type').value   = 'onsite';
  document.getElementById('f-opp-status').value = 'open';
}

function saveOpp() {
  const title = document.getElementById('f-opp-title').value.trim();
  const desc  = document.getElementById('f-opp-desc').value.trim();
  const org   = document.getElementById('f-opp-org').value.trim();
  if (!title || !desc || !org) { showToast('⚠️', 'يرجى ملء الحقول المطلوبة'); return; }
  const data = {
    title, desc, org,
    city:     document.getElementById('f-opp-city').value.trim(),
    seats:    parseInt(document.getElementById('f-opp-seats').value) || 0,
    deadline: document.getElementById('f-opp-deadline').value,
    type:     document.getElementById('f-opp-type').value,
    status:   document.getElementById('f-opp-status').value,
    catId:    currentCatId,
  };
  if (editingOppId) {
    const i = opportunities.findIndex(x => x.id === editingOppId);
    opportunities[i] = { ...opportunities[i], ...data };
    showToast('✏️', 'تم تعديل الفرصة بنجاح');
  } else {
    data.id = nextOppId++;
    opportunities.push(data);
    showToast('✅', 'تمت إضافة الفرصة بنجاح');
  }
  closeOv('ov-opp');
  renderAdminOpps();
  renderCats();
  updateStats();
}

/* ══ DELETE OPP ══ */
function openDelOpp(id) { deletingOppId = id; openOv('ov-del'); }

function doDelete() {
  opportunities = opportunities.filter(x => x.id !== deletingOppId);
  closeOv('ov-del');
  renderAdminOpps();
  renderCats();
  updateStats();
  showToast('🗑️', 'تم حذف الفرصة');
}

/* ══ APPLY ══ */
function openApply(oppId) {
  applyingOppId = oppId;
  const o = opportunities.find(x => x.id === oppId);
  const c = CATEGORIES.find(x => x.id === o.catId);
  document.getElementById('apply-opp-title').textContent = o.title;
  document.getElementById('apply-opp-org').textContent   = '🏛 ' + o.org;
  document.getElementById('apply-opp-cat').textContent   = c.icon + ' ' + c.name;
  document.getElementById('f-apply-msg').value = '';
  openOv('ov-apply');
}

function submitApply() {
  const assoc = document.getElementById('f-apply-assoc').value.trim();
  if (!assoc) { showToast('⚠️', 'يرجى إدخال اسم جمعيتك'); return; }
  const req = {
    id:        nextReqId++,
    oppId:     applyingOppId,
    assocName: assoc,
    assocCity: 'الرياض',
    message:   document.getElementById('f-apply-msg').value.trim(),
    date:      new Date().toISOString().split('T')[0],
    status:    'pending',
  };
  requests.push(req);
  closeOv('ov-apply');
  renderAssocOpps();
  updateStats();
  updateReqCounts();
  showToast('📨', 'تم إرسال طلبك! في انتظار موافقة مبادرون');
  setTimeout(() => {
    document.getElementById('notif-dot').style.display = 'block';
  }, 2000);
}

/* ══ STATS ══ */
function updateStats() {
  const open    = opportunities.filter(o => o.status === 'open').length;
  const pending = requests.filter(r => r.status === 'pending').length;
  document.getElementById('st-total').textContent      = opportunities.length;
  document.getElementById('st-open').textContent       = open;
  document.getElementById('st-pending').textContent    = pending;
  document.getElementById('nb-opps').textContent       = open;
  document.getElementById('nb-reqs').textContent       = pending;
  document.getElementById('hdr-req-badge').textContent = pending;
  updateReqCounts();
  updateAssocStats();
}

function updateReqCounts() {
  ['pending', 'approved', 'rejected'].forEach(s => {
    document.getElementById('rc-' + s).textContent = requests.filter(r => r.status === s).length;
  });
}

function updateAssocStats() {
  const myReqs     = requests.filter(r => r.assocName === 'جمعية التنمية الاجتماعية');
  const myApproved = myReqs.filter(r => r.status === 'approved').length;
  const open       = opportunities.filter(o => o.status === 'open').length;
  if (document.getElementById('ast-total'))    document.getElementById('ast-total').textContent    = open;
  if (document.getElementById('ast-applied'))  document.getElementById('ast-applied').textContent  = myReqs.length;
  if (document.getElementById('ast-approved')) document.getElementById('ast-approved').textContent = myApproved;
}

/* ══ NOTIFICATIONS ══ */
function toggleNotifs() {
  document.getElementById('notif-dot').style.display = 'none';
}

function showNotifBanner(title, sub) {
  const b = document.getElementById('assoc-notif-banner');
  document.getElementById('notif-banner-title').textContent = title;
  document.getElementById('notif-banner-sub').textContent   = sub;
  b.style.display = 'flex';
}

/* ══ OVERLAY ══ */
function openOv(id)  { document.getElementById(id).classList.add('open'); }
function closeOv(id) { document.getElementById(id).classList.remove('open'); }
function bgClose(e, id) { if (e.target === document.getElementById(id)) closeOv(id); }

/* ══ TOAST ══ */
let tTimer;
function showToast(icon, msg) {
  const el = document.getElementById('toast');
  document.getElementById('t-icon').textContent = icon;
  document.getElementById('t-msg').textContent  = msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer = setTimeout(() => el.classList.remove('show'), 3200);
}

/* ══ MEETINGS PANEL ══ */
function openMeetingsPage() {
  const frame = document.getElementById('meetings-frame');
  if (!frame.src || frame.src === window.location.href) {
    frame.src = 'takamol-meetings.html';
  }
  document.getElementById('meetings-panel').classList.add('open');
  document.getElementById('meetings-overlay').classList.add('open');
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
  document.getElementById('nav-meetings').classList.add('active');
}

function backToVolunteer() {
  closeMeetingsPage();
}

function closeMeetingsPage() {
  document.getElementById('meetings-panel').classList.remove('open');
  document.getElementById('meetings-overlay').classList.remove('open');
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
  document.querySelector('[data-vol]')?.classList.add('active');
}

/* ══ SERVICES SUBMENU ══ */
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
  document.getElementById('meetings-panel')?.classList.remove('open');
  document.getElementById('meetings-overlay')?.classList.remove('open');

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

  document.getElementById('submenu-services').classList.add('open');
  document.getElementById('np-services').classList.add('open');
}

/* ══ CONTACT ══ */
function sendContactMsg() {
  showToast('📨', 'تم إرسال رسالتك! سنردّ خلال 24 ساعة');
}

/* ══ KEYBOARD ══ */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') ['ov-opp', 'ov-apply', 'ov-del'].forEach(closeOv);
});

/* ══ INIT ══ */
renderCats();
updateStats();
