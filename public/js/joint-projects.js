/* ══════════ DATA ══════════ */
const CATS = {
  'خيرية': { label: 'خيرية واجتماعية', emoji: '🧡', color: '#f472b6', bg: 'rgba(244,114,182,.12)', icon: 'fa-heart' },
  'ثقافية': { label: 'ثقافية وتعليمية', emoji: '📚', color: '#818cf8', bg: 'rgba(129,140,248,.12)', icon: 'fa-book-open' },
  'صحية': { label: 'صحية وبيئية', emoji: '🌿', color: '#22d3a5', bg: 'rgba(34,211,165,.12)', icon: 'fa-seedling' },
  'رياضية': { label: 'رياضية وشبابية', emoji: '🌍', color: '#38bdf8', bg: 'rgba(56,189,248,.12)', icon: 'fa-globe' },
  'تنموية': { label: 'تنموية واقتصادية', emoji: '📈', color: '#00c2a8', bg: 'rgba(0,194,168,.12)', icon: 'fa-chart-line' },
  'دينية': { label: 'دينية ودعوية', emoji: '🕌', color: '#fbbf24', bg: 'rgba(251,191,36,.12)', icon: 'fa-mosque' },
};

const SEED_PROJ = [
  {
    id: 'p1', name: 'المبادرة الخضراء', cat: 'صحية', status: 'مستمر', progress: 70,
    goal: 'مشروع بيئي يهدف لزراعة 1000 شجرة بالتعاون مع جمعيات في مناطق مختلفة.',
    start: '2025-01-01', end: '2025-12-31',
    updates: [{ date: '2025-03-10', txt: 'الانتهاء من زراعة المرحلة الأولى.' }, { date: '2025-01-20', txt: 'اكتمال التجهيزات اللوجستية.' }]
  },
  {
    id: 'p2', name: 'المنصة الرقمية الموحدة', cat: 'تنموية', status: 'قيد الإعداد', progress: 35,
    goal: 'توحيد خدمات الجمعيات للمستفيدين تحت تطبيق واحد.',
    start: '2025-02-01', end: '2026-03-01',
    updates: [{ date: '2025-03-05', txt: 'توقيع عقود التصميم المبدئي (UI/UX).' }]
  },
  {
    id: 'p3', name: 'سلال العطاء الرمضانية', cat: 'خيرية', status: 'مكتمل', progress: 100,
    goal: 'حملة توزيع 5000 سلة غذائية في رمضان.',
    start: '2024-03-01', end: '2024-04-20',
    updates: [{ date: '2024-04-15', txt: 'إغلاق المشروع وتسليم التقارير.' }, { date: '2024-03-25', txt: 'توزيع 5000 سلة بنجاح.' }]
  },
  {
    id: 'p4', name: 'ملتقى الشباب الثقافي', cat: 'ثقافية', status: 'فكرة', progress: 5,
    goal: 'ملتقى سنوي يجمع الشباب لتبادل الأفكار والإبداع الثقافي.',
    start: '2025-06-01', end: '2025-09-01',
    updates: [{ date: '2025-02-20', txt: 'اعتماد فكرة المشروع من قِبل المجلس.' }]
  },
  {
    id: 'p5', name: 'برنامج التوعية الدينية', cat: 'دينية', status: 'مستمر', progress: 55,
    goal: 'سلسلة محاضرات ودروس دينية في المدن الرئيسية.',
    start: '2025-01-15', end: '2025-11-30',
    updates: [{ date: '2025-03-01', txt: 'انطلاق المحاضرات في الرياض وجدة.' }, { date: '2025-01-15', txt: 'بدء التنسيق مع الجمعيات المشاركة.' }]
  },
];

function getProjects() {
  const d = localStorage.getItem('tkm_proj');
  if (!d) { localStorage.setItem('tkm_proj', JSON.stringify(SEED_PROJ)); return JSON.parse(JSON.stringify(SEED_PROJ)); }
  return JSON.parse(d);
}
function saveProjects(a) { localStorage.setItem('tkm_proj', JSON.stringify(a)); }
function addProject(p) {
  const a = getProjects();
  p.id = 'p' + Date.now();
  p.updates = [{ date: today(), txt: 'تم إنشاء المشروع.' }];
  a.unshift(p);
  saveProjects(a);
  return p;
}
function updateProject(id, ch) {
  const a = getProjects();
  const i = a.findIndex(p => p.id === id);
  if (i > -1) { a[i] = { ...a[i], ...ch }; saveProjects(a); return a[i]; }
}
function deleteProject(id) { saveProjects(getProjects().filter(p => p.id !== id)); }
function addUpdate(id, txt) {
  const a = getProjects();
  const p = a.find(x => x.id === id);
  if (p) { p.updates.unshift({ date: today(), txt }); saveProjects(a); }
}
function today() { return new Date().toISOString().slice(0, 10); }

/* ══════════ STATE ══════════ */
let activeCat = 'all';
let searchQ = '';

/* ══════════ DROPDOWN ══════════ */
function buildDropdown() {
  const projs = getProjects();
  const menu = document.getElementById('ddMenu');
  const allCount = projs.length;

  let html = `<div class="dd-item ${activeCat === 'all' ? 'selected' : ''}" data-cat="all">
    <span class="item-emoji">🏢</span><span>كل التصنيفات</span>
    <span class="item-count">${allCount}</span></div>`;

  Object.entries(CATS).forEach(([key, c]) => {
    const cnt = projs.filter(p => p.cat === key).length;
    html += `<div class="dd-item ${activeCat === key ? 'selected' : ''}" data-cat="${key}">
      <span class="item-emoji">${c.emoji}</span><span>${c.label}</span>
      <span class="item-count">${cnt}</span></div>`;
  });
  menu.innerHTML = html;

  menu.querySelectorAll('.dd-item').forEach(item => {
    item.addEventListener('click', () => {
      activeCat = item.dataset.cat;
      updateDdLabel();
      closeDd();
      renderAll();
    });
  });
}

function updateDdLabel() {
  const lbl = document.getElementById('ddLabel');
  const btn = document.getElementById('ddBtn');
  const emoEl = btn.querySelector('.emoji');
  if (activeCat === 'all') {
    lbl.textContent = 'كل التصنيفات'; emoEl.textContent = '🏢';
  } else {
    const c = CATS[activeCat];
    lbl.textContent = c.label; emoEl.textContent = c.emoji;
  }
}

function openDd() {
  buildDropdown();
  document.getElementById('ddMenu').classList.add('open');
  document.getElementById('ddBtn').classList.add('open');
}
function closeDd() {
  document.getElementById('ddMenu').classList.remove('open');
  document.getElementById('ddBtn').classList.remove('open');
}


/* ══════════ RENDER ══════════ */
const STATUS = {
  'مستمر': { cls: 'b-active', lbl: 'مستمر' },
  'قيد الإعداد': { cls: 'b-prep', lbl: 'قيد الإعداد' },
  'فكرة': { cls: 'b-idea', lbl: 'فكرة' },
  'مكتمل': { cls: 'b-done', lbl: 'مكتمل' },
  'ملغى': { cls: 'b-canceled', lbl: 'ملغى' },
};

function filterProjects(projs) {
  let f = projs;
  if (activeCat !== 'all') f = f.filter(p => p.cat === activeCat);
  if (searchQ) {
    const q = searchQ.toLowerCase();
    f = f.filter(p => p.name.includes(q) || p.goal?.includes(q));
  }
  return f;
}

function renderAll() {
  updateDdLabel();
  buildDropdown();
  const all = getProjects();

  const fActive = filterProjects(all.filter(p => !['مكتمل', 'ملغى'].includes(p.status)));
  const fDone = filterProjects(all.filter(p => p.status === 'مكتمل'));
  const fCanceled = filterProjects(all.filter(p => p.status === 'ملغى'));
  const total = fActive.length + fDone.length + fCanceled.length;

  fillGrid('tab-active', fActive, 'fa-rocket', 'لا توجد مشاريع نشطة تطابق الفلتر');
  fillGrid('tab-done', fDone, 'fa-circle-check', 'لا توجد مشاريع منتهية تطابق الفلتر');
  fillGrid('tab-canceled', fCanceled, 'fa-ban', 'لا توجد مشاريع ملغاة تطابق الفلتر');

  document.getElementById('n-active').textContent = fActive.length;
  document.getElementById('n-done').textContent = fDone.length;
  document.getElementById('n-canceled').textContent = fCanceled.length;
  document.getElementById('resNum').textContent = total;
  renderStats(all);
}

function renderStats(all) {
  const active = all.filter(p => !['مكتمل', 'ملغى'].includes(p.status));
  const avg = active.length ? Math.round(active.reduce((s, p) => s + (p.progress || 0), 0) / active.length) : 0;
  document.getElementById('statsRow').innerHTML = `
    <div class="stat"><div class="stat-ico" style="background:var(--teal-dim)"><i class="fa-solid fa-rocket" style="color:var(--teal)"></i></div>
      <div class="stat-info"><span>مشاريع نشطة</span><strong style="color:var(--teal-glow)">${active.length}</strong></div></div>
    <div class="stat"><div class="stat-ico" style="background:var(--green-dim)"><i class="fa-solid fa-circle-check" style="color:var(--green)"></i></div>
      <div class="stat-info"><span>مشاريع منتهية</span><strong style="color:var(--teal-glow)">${all.filter(p => p.status === 'مكتمل').length}</strong></div></div>
    <div class="stat"><div class="stat-ico" style="background:var(--amber-dim)"><i class="fa-solid fa-chart-line" style="color:var(--amber)"></i></div>
      <div class="stat-info"><span>متوسط الإنجاز</span><strong style="color:var(--teal-glow)">${avg}%</strong></div></div>
  `;
}

function fillGrid(id, projs, ico, emptyMsg) {
  const el = document.getElementById(id);
  if (!projs.length) {
    el.innerHTML = `<div class="empty"><i class="fa-solid ${ico}"></i><p>${emptyMsg}</p></div>`;
    return;
  }
  el.innerHTML = projs.map(buildCard).join('');
  el.querySelectorAll('.pcard').forEach(attachEvents);
}

function buildCard(p) {
  const cat = CATS[p.cat] || CATS['تنموية'];
  const st = STATUS[p.status] || STATUS['قيد الإعداد'];
  const fin = p.status === 'مكتمل' || p.status === 'ملغى';
  const acts = fin
    ? `<button class="abtn d" data-id="${p.id}"><i class="fa-regular fa-trash-can"></i></button>`
    : `<button class="abtn e" data-id="${p.id}"><i class="fa-regular fa-pen-to-square"></i></button>
       <button class="abtn c" data-id="${p.id}"><i class="fa-solid fa-ban"></i></button>
       <button class="abtn d" data-id="${p.id}"><i class="fa-regular fa-trash-can"></i></button>`;
  const tl = (p.updates || []).slice(0, 3).map(u => `
    <div class="tl-item"><div class="tl-dot" style="background:${cat.color}"></div>
    <div><span class="tl-date">${u.date}</span><p class="tl-txt">${u.txt}</p></div></div>`).join('')
    || `<p style="font-size:.79rem;color:var(--muted);font-style:italic">لا توجد تقدمات بعد</p>`;
  const dates = (p.start || p.end) ? `<div class="cdates">
    ${p.start ? `<span class="dchip"><i class="fa-regular fa-calendar" style="color:var(--teal)"></i>البدء: ${p.start}</span>` : ''}
    ${p.end ? `<span class="dchip"><i class="fa-regular fa-calendar-check" style="color:var(--amber)"></i>النهاية: ${p.end}</span>` : ''}
  </div>` : '';
  return `
  <div class="pcard" style="--ca:${cat.color}" data-id="${p.id}">
    <div class="ct">
      <div style="flex:1;min-width:0">
        <div class="ctitle" ${fin ? 'style="color:var(--muted)"' : ''}>
          <span style="font-size:1.1rem">${cat.emoji}</span>${p.name}
        </div>
        <div class="cbadges">
          <span class="bdg ${st.cls}"><i class="fa-solid fa-circle" style="font-size:.4rem"></i>${st.lbl}</span>
          <span class="bdg" style="background:${cat.bg};color:${cat.color}">
            <i class="fa-solid ${cat.icon}"></i>${cat.label}
          </span>
        </div>
      </div>
      <div class="cactions">${acts}</div>
    </div>
    <p class="cdesc" ${fin ? 'style="color:var(--muted)"' : ''}>${p.goal}</p>
    ${dates}
    <div class="prog">
      <div class="prog-lbl"><span>نسبة الإنجاز</span><span>${p.progress || 0}%</span></div>
      <div class="prog-tr"><div class="prog-fi" style="width:${p.progress || 0}%;${fin ? 'background:#475569' : ''}"></div></div>
    </div>
    <div class="tl"><div class="tl-hd">سجل التقدمات</div>${tl}</div>
  </div>`;
}

function attachEvents(card) {
  const id = card.dataset.id;
  card.querySelector('.abtn.e')?.addEventListener('click', () => openEdit(id));
  card.querySelector('.abtn.c')?.addEventListener('click', () =>
    confirm2('تأكيد الإلغاء', 'هل أنت متأكد من إلغاء هذا المشروع؟', () => { updateProject(id, { status: 'ملغى' }); renderAll(); }));
  card.querySelector('.abtn.d')?.addEventListener('click', () =>
    confirm2('حذف نهائي', 'هل أنت متأكد؟ لا يمكن التراجع.', () => { deleteProject(id); renderAll(); }));
}

/* ══════════ TABS ══════════ */
/* ══════════ EDIT PROJECT ══════════ */
function openEdit(id) {
  const p = getProjects().find(x => x.id === id);
  if (!p) return;
  document.getElementById('eId').value = p.id;
  document.getElementById('eN').value = p.name;
  document.getElementById('eG').value = p.goal;
  document.getElementById('eS').value = p.start || '';
  document.getElementById('eE').value = p.end || '';
  document.getElementById('eP').value = p.progress || 0;
  document.getElementById('eU').value = '';
  document.getElementById('ovEdit').classList.add('open');
}

/* ══════════ TABS ══════════ */
function initProjectTabs() {
  document.querySelectorAll('#view-projects .tab').forEach(t => t.addEventListener('click', () => {
    document.querySelectorAll('#view-projects .tab').forEach(x => x.classList.remove('on'));
    document.querySelectorAll('#view-projects .pane').forEach(x => x.classList.remove('on'));
    t.classList.add('on');
    document.getElementById(t.dataset.t).classList.add('on');
  }));
}

/* ══════════ CONFIRM ══════════ */
let _pa = null;
function confirm2(ttl, msg, fn) {
  document.getElementById('cTtl').textContent = ttl;
  document.getElementById('cMsg').textContent = msg;
  _pa = fn;
  document.getElementById('ovConfirm').classList.add('open');
}
function initProjects() {
  document.getElementById('cY').onclick = () => { if (_pa) _pa(); document.getElementById('ovConfirm').classList.remove('open'); _pa = null; };
  document.getElementById('cN').onclick = () => { document.getElementById('ovConfirm').classList.remove('open'); _pa = null; };
  document.querySelectorAll('#view-projects .ov').forEach(o => o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); }));

  document.getElementById('openNew').onclick = () => document.getElementById('ovNew').classList.add('open');
  document.getElementById('clNew').onclick = () => document.getElementById('ovNew').classList.remove('open');
  document.getElementById('clEdit').onclick = () => document.getElementById('ovEdit').classList.remove('open');

  document.getElementById('ddBtn').addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = document.getElementById('ddMenu').classList.contains('open');
    isOpen ? closeDd() : openDd();
  });
  document.getElementById('ddMenu').addEventListener('click', e => e.stopPropagation());
  document.getElementById('searchQ').addEventListener('input', e => {
    searchQ = e.target.value.trim();
    renderAll();
  });

  document.getElementById('fNew').addEventListener('submit', e => {
    e.preventDefault();
    addProject({
      name: document.getElementById('nN').value,
      cat: document.getElementById('nD').value,
      goal: document.getElementById('nG').value,
      start: document.getElementById('nS').value,
      end: document.getElementById('nE').value,
      status: document.getElementById('nSt').value,
      progress: 0
    });
    document.getElementById('ovNew').classList.remove('open');
    document.getElementById('fNew').reset();
    renderAll();
  });

  document.getElementById('fEdit').addEventListener('submit', e => {
    e.preventDefault();
    const id = document.getElementById('eId').value;
    const prog = parseInt(document.getElementById('eP').value) || 0;
    const upd = document.getElementById('eU').value.trim();
    updateProject(id, {
      name: document.getElementById('eN').value,
      goal: document.getElementById('eG').value,
      start: document.getElementById('eS').value,
      end: document.getElementById('eE').value,
      progress: prog,
      ...(prog >= 100 ? { status: 'مكتمل' } : {})
    });
    if (upd) addUpdate(id, upd);
    document.getElementById('ovEdit').classList.remove('open');
    renderAll();
  });

  initProjectTabs();
  buildDropdown();
  renderAll();
}

/* ── SIDEBAR FUNCTIONS ── */
function toggleSubmenu(id) {
  const menu = document.getElementById('submenu-' + id);
  const parent = document.getElementById('np-' + id);

  // Close other submenus (optional, but keep it clean)
  document.querySelectorAll('.nav-submenu').forEach(m => {
    if (m.id !== 'submenu-' + id) {
      m.classList.remove('open');
      m.previousElementSibling.classList.remove('open');
    }
  });

  if (menu) {
    menu.classList.toggle('open');
    parent.classList.toggle('open');
  }
}












