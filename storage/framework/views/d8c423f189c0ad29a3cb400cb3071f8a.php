<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>تكامل — الاجتماعات</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/meeting-scoped.css')); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ── User-only overrides ── */

    /* Status tabs (like admin: الكل / قادمة / مكتملة ...) */
    .status-tabs {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 24px;
    }
    .stab {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 7px 18px;
      border-radius: 30px;
      border: 1.5px solid var(--border);
      background: var(--fog);
      font-family: 'Tajawal', sans-serif;
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--muted);
      cursor: pointer;
      transition: all 0.2s;
    }
    .stab .stab-count {
      background: rgba(0,0,0,0.07);
      border-radius: 20px;
      padding: 1px 7px;
      font-size: 0.72rem;
      font-weight: 800;
    }
    .stab:hover { border-color: var(--teal-glow); color: var(--teal); }
    .stab.on {
      background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal) 50%, var(--teal-glow) 100%);
      border-color: transparent;
      color: white;
      box-shadow: 0 3px 12px rgba(42,184,208,0.35);
    }
    .stab.on .stab-count { background: rgba(255,255,255,0.22); }

    /* Join button — used only inside the modal footer */
    .btn-join {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 22px;
      border: none;
      border-radius: 11px;
      background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal) 50%, var(--teal-glow) 100%);
      font-family: 'Tajawal', sans-serif;
      font-size: 0.88rem;
      font-weight: 800;
      color: white;
      cursor: pointer;
      transition: all 0.22s;
      box-shadow: 0 3px 14px rgba(42,184,208,0.4);
      text-decoration: none;
      white-space: nowrap;
    }
    .btn-join:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(42,184,208,0.55);
    }
    .btn-join:disabled, .btn-join.joined {
      background: linear-gradient(135deg, #2eaa78, #22c55e);
      box-shadow: 0 3px 14px rgba(46,170,120,0.4);
      cursor: default;
      transform: none;
    }
    .btn-join.onsite-join {
      background: linear-gradient(135deg, #1d6f9e, #3a72b8);
      box-shadow: 0 3px 14px rgba(58,114,184,0.4);
    }
    .btn-join.onsite-join:hover {
      box-shadow: 0 8px 22px rgba(58,114,184,0.55);
    }

    /* Card: centered "عرض التفاصيل" only */
    .card-foot-user {
      padding: 12px 18px;
      border-top: 1px solid var(--border);
      background: var(--fog);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .btn-details-main {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 28px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--teal-deep) 0%, var(--teal) 50%, var(--teal-glow) 100%);
      font-family: 'Tajawal', sans-serif;
      font-size: 0.82rem;
      font-weight: 800;
      color: white;
      cursor: pointer;
      transition: all 0.22s;
      box-shadow: 0 2px 10px rgba(42,184,208,0.35);
      white-space: nowrap;
      letter-spacing: 0.2px;
    }
    .btn-details-main:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(42,184,208,0.5);
      filter: brightness(1.08);
    }

    /* Joining badge — shown inside modal only */
    .joining-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 12px;
      border-radius: 20px;
      background: rgba(46,170,120,0.1);
      color: var(--green);
      border: 1px solid rgba(46,170,120,0.25);
      font-size: 0.75rem;
      font-weight: 800;
    }

    /* Empty state for user */
    .user-empty {
      grid-column: 1/-1;
      text-align: center;
      padding: 48px 24px;
      color: var(--muted);
    }
    .user-empty-icon {
      font-size: 2.5rem;
      margin-bottom: 12px;
      display: block;
      opacity: 0.5;
    }
    .user-empty h3 { font-size: 1rem; font-weight: 700; margin-bottom: 5px; color: var(--ink); }
    .user-empty p  { font-size: 0.83rem; }

    /* ── REDESIGNED DETAILS MODAL ── */
    .mdl-content {
      background: #fff;
      border-radius: 16px;
      width: 100%;
      max-width: 650px;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }
    .mdl-head {
      padding: 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: flex-start;
      gap: 16px;
    }
    .mdl-icon-box {
      width: 54px;
      height: 54px;
      border-radius: 14px;
      background: rgba(42,184,208,0.1);
      color: var(--teal);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }
    .mdl-head-text { flex: 1; }
    .mdl-title {
      font-size: 1.3rem;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 8px;
    }
    .mdl-badges { display: flex; gap: 8px; flex-wrap: wrap; }
    .mdl-close-btn {
      background: none; border: none; font-size: 1.2rem; color: var(--muted); cursor: pointer; padding: 4px; transition: color 0.2s;
    }
    .mdl-close-btn:hover { color: var(--red); }
    .mdl-body {
      padding: 24px;
      overflow-y: auto;
      flex: 1;
    }
    .mdl-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 24px;
    }
    .m-item { display: flex; gap: 12px; }
    .m-item-icon { color: var(--teal); font-size: 1.1rem; margin-top: 2px; }
    .m-item-lbl { font-size: 0.8rem; color: var(--muted); margin-bottom: 4px; font-weight: 500; }
    .m-item-val { font-size: 0.95rem; font-weight: 700; color: var(--ink); }
    .mdl-section { margin-bottom: 24px; }
    .mdl-sec-title {
      font-size: 0.95rem;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 12px;
    }
    .mdl-desc {
      background: var(--fog);
      border-radius: 10px;
      padding: 16px;
      font-size: 0.9rem;
      color: var(--ink);
      line-height: 1.6;
    }
    .mdl-targets { display: flex; gap: 8px; flex-wrap: wrap; }
    .m-target {
      background: rgba(42,184,208,0.08);
      color: var(--teal-deep);
      border: 1px solid rgba(42,184,208,0.2);
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.85rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .mdl-agenda-list { display: flex; flex-direction: column; gap: 12px; }
    .m-agenda {
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      background: #fafafa;
    }
    .m-ag-num {
      width: 28px; height: 28px; border-radius: 8px; background: var(--teal); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;
    }
    .m-ag-info { flex: 1; }
    .m-ag-title { font-size: 0.9rem; font-weight: 700; color: var(--ink); margin-bottom: 4px; }
    .m-ag-pres { font-size: 0.75rem; color: var(--muted); }
    .m-ag-dur {
      font-size: 0.75rem; color: var(--muted); background: white; border: 1px solid var(--border); padding: 4px 10px; border-radius: 6px; white-space: nowrap;
    }
    .mdl-footer {
      padding: 16px 24px;
      border-top: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fafafa;
    }
    .btn-mdl-close {
      background: none; border: none; color: var(--muted); font-size: 0.95rem; font-weight: 700; cursor: pointer; padding: 8px 16px; border-radius: 8px; transition: all 0.2s;
    }
    .btn-mdl-close:hover { background: var(--border); color: var(--ink); }
    .btn-mdl-join {
      background: linear-gradient(135deg, var(--teal-deep), var(--teal-glow));
      color: white; border: none; border-radius: 10px; padding: 10px 24px; font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(42,184,208,0.3);
    }
    .btn-mdl-join:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(42,184,208,0.4); }
    .btn-mdl-join.joined { background: linear-gradient(135deg, #2eaa78, #22c55e); box-shadow: 0 4px 14px rgba(46,170,120,0.3); cursor: default; transform: none; }
    .mdl-cancel-alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
    .mdl-cancel-alert strong { color: #dc2626; display: block; margin-bottom: 4px; font-size: 0.9rem; }
    .mdl-cancel-alert p { color: #991b1b; font-size: 0.85rem; margin: 0; }

    /* ── Card action icon (video / map) ── */
    .card-action-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      border-radius: 10px;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s;
      flex-shrink: 0;
    }
    .card-action-online {
      background: rgba(42,184,208,0.1);
      border: 1.5px solid rgba(42,184,208,0.25);
      color: var(--teal);
    }
    .card-action-online:hover {
      background: linear-gradient(135deg, var(--teal-deep), var(--teal-glow));
      border-color: transparent;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 14px rgba(42,184,208,0.45);
    }
    .card-action-onsite {
      background: rgba(58,114,184,0.1);
      border: 1.5px solid rgba(58,114,184,0.25);
      color: #3a72b8;
    }
    .card-action-onsite:hover {
      background: linear-gradient(135deg, #1d6f9e, #3a72b8);
      border-color: transparent;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 14px rgba(58,114,184,0.45);
    }
    /* ── Modal Overlay State ── */
    .overlay.open {
      opacity: 1 !important;
      pointer-events: auto !important;
    }
    .overlay.open .mdl-content {
      transform: scale(1);
    }
    .overlay .mdl-content {
      transform: scale(0.95);
      transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
  </style>
</head>

<body>
<div id="view-meetings">
<div class="layout">

  <?php echo $__env->make('layouts.sidebar-user', ['activeNav' => 'meetings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="main">
    <?php echo $__env->make('layouts.topbar', [
      'title'    => 'الاجتماعات',
      'userName' => Auth::user()?->full_name ?? 'مستخدم',
      'userAv'   => mb_substr(Auth::user()?->full_name ?? 'م', 0, 1),
      'showNotif'=> false,
      'userRole' => '<span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700"><i class="fa-solid fa-eye" style="font-size:.6rem"></i> عرض فقط</span>'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="content" style="padding:26px 30px">

      
      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px">
        <div>
          <div class="ph-title" style="font-size:1.55rem;font-weight:900;color:var(--ink);margin-bottom:3px">الاجتماعات</div>
          <div class="ph-sub" style="font-size:0.85rem;color:var(--muted)">تصفح الاجتماعات المتاحة وانضم إليها</div>
        </div>
        
      </div>

      
      <div class="stats-row" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px">
        <div class="stat-card" style="--sc:var(--teal-glow)">
          <div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-regular fa-calendar"></i></div>
          <div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div>
        </div>
        <div class="stat-card" style="--sc:var(--green)">
          <div class="stat-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-circle-play" style="font-size:.85rem"></i></div>
          <div><span class="stat-num" id="s-upcoming">0</span><span class="stat-lbl">القادمة</span></div>
        </div>
        <div class="stat-card" style="--sc:var(--teal-glow)">
          <div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-laptop"></i></div>
          <div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div>
        </div>
        <div class="stat-card" style="--sc:var(--green)">
          <div class="stat-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-building"></i></div>
          <div><span class="stat-num" id="s-onsite">0</span><span class="stat-lbl">حضوري</span></div>
        </div>
      </div>

      
      <div class="status-tabs">
        <button class="stab on" id="stab-all"      onclick="setTab('all')">الكل <span class="stab-count" id="sc-all">0</span></button>
        <button class="stab"    id="stab-upcoming" onclick="setTab('upcoming')">قادمة <span class="stab-count" id="sc-upcoming">0</span></button>
        <button class="stab"    id="stab-past"     onclick="setTab('past')">مكتملة <span class="stab-count" id="sc-past">0</span></button>
        <button class="stab"    id="stab-cancelled"onclick="setTab('cancelled')">ملغاة <span class="stab-count" id="sc-cancelled">0</span></button>
      </div>

      
      <div class="toolbar" style="display:flex;align-items:center;gap:14px;margin-bottom:28px;background:rgba(255,255,255,0.5);backdrop-filter:blur(10px);border:1px solid var(--border);border-radius:20px;padding:14px 20px">
        <div class="search-wrap" style="flex:1;position:relative">
          <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input class="search-input" id="searchQ" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="renderMeetings()" style="width:100%;padding:9px 38px 9px 14px;background:var(--fog);border:1.5px solid transparent;border-radius:10px;font-family:'Tajawal',sans-serif;font-size:.88rem;color:var(--ink);outline:none;direction:rtl;transition:all .2s">
        </div>
        <div style="width:1px;height:28px;background:var(--border)"></div>
        <div style="display:flex;gap:6px">
          <button class="stab on" id="tf-all"    onclick="setTypeF('all')"   >الكل</button>
          <button class="stab"    id="tf-online" onclick="setTypeF('online')">عن بعد</button>
          <button class="stab"    id="tf-onsite" onclick="setTypeF('onsite')">حضوري</button>
        </div>
      </div>

      
      <div class="meetings-grid" id="meetings-grid"></div>

    </div>
  </div>
</div>
</div>


<div class="overlay" id="ov-details" onclick="bgClose(event,'ov-details')" style="position:fixed;inset:0;background:rgba(10,25,35,0.55);backdrop-filter:blur(8px);z-index:300;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .25s">
  <div class="mdl-content" onclick="event.stopPropagation()">
    <div class="mdl-head">
      <div class="mdl-icon-box"><i class="fa-solid fa-building"></i></div>
      <div class="mdl-head-text">
        <div class="mdl-title" id="d-title"></div>
        <div class="mdl-badges" id="d-badges"></div>
      </div>
      <button class="mdl-close-btn" onclick="closeOv('ov-details')"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div class="mdl-body">
      <div id="d-cancel-alert" class="mdl-cancel-alert" style="display:none">
        <strong>تم إلغاء هذا الاجتماع</strong>
        <p id="d-cancel-reason"></p>
      </div>

      <div class="mdl-grid">
        <div class="m-item">
          <div class="m-item-icon"><i class="fa-regular fa-calendar"></i></div>
          <div><div class="m-item-lbl">التاريخ</div><div class="m-item-val" id="d-date"></div></div>
        </div>
        <div class="m-item">
          <div class="m-item-icon"><i class="fa-regular fa-clock"></i></div>
          <div><div class="m-item-lbl">وقت الاجتماع</div><div class="m-item-val" id="d-time"></div></div>
        </div>
        <div class="m-item" id="m-loc-wrap">
          <div class="m-item-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div><div class="m-item-lbl">مكان الاجتماع</div><div class="m-item-val" id="d-loc"></div></div>
        </div>
        <div class="m-item">
          <div class="m-item-icon"><i class="fa-solid fa-microphone-lines"></i></div>
          <div><div class="m-item-lbl">المتحدث الرئيسي</div><div class="m-item-val" id="d-presenter"></div></div>
        </div>
      </div>

      <div class="mdl-section" id="d-desc-sec">
        <div class="mdl-sec-title">الوصف</div>
        <div class="mdl-desc" id="d-desc"></div>
      </div>

      <div class="mdl-section" id="d-targets-sec">
        <div class="mdl-sec-title">المدعوون</div>
        <div class="mdl-targets" id="d-targets"></div>
      </div>

      <div class="mdl-section" id="d-agenda-sec" style="margin-bottom:0">
        <div class="mdl-sec-title">محاور الاجتماع</div>
        <div class="mdl-agenda-list" id="d-agenda"></div>
      </div>
    </div>

    <div class="mdl-footer">
      <button class="btn-mdl-close" onclick="closeOv('ov-details')">إغلاق</button>
      <div id="d-join-action"></div>
    </div>
  </div>
</div>

<div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

<script>
const TODAY     = new Date().toISOString().split('T')[0];
const USER_NAME = <?php echo json_encode(Auth::user()?->full_name ?? 'مستخدم', 15, 512) ?>;
const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content || '';

let meetings  = [];
let tabFilter = 'all';
let typeFilter= 'all';
let viewingId = null;

/* ── joined set (session-persisted via memory) ── */
const joined = new Set(JSON.parse(sessionStorage.getItem('tkamel_joined') || '[]'));

const CAT_BADGE = { خيرية:'b-xairy', ثقافية:'b-thaqafi', صحية:'b-seha', رياضية:'b-riyadhi', تنموية:'b-tanmawi', دينية:'b-dini' };
const ACCENT    = { خيرية:'#2ab8d0', ثقافية:'#7b4ea6', صحية:'#2eaa78', رياضية:'#3a72b8', تنموية:'#e65100', دينية:'#7b4ea6' };

function fmtDate(d) {
  if (!d) return '—';
  return new Date(d+'T00:00:00').toLocaleDateString('ar-SA',{weekday:'short',year:'numeric',month:'long',day:'numeric'});
}
function fmtShort(d) {
  if (!d) return {day:'—',month:''};
  const dt = new Date(d+'T00:00:00');
  return { day: dt.getDate(), month: dt.toLocaleDateString('ar-SA',{month:'short'}) };
}
function ini(n) { const p=n.trim().split(' '); return p.length>=2?p[0][0]+p[1][0]:n[0]; }

/* ── FILTERS ── */
function isCurrent(m){ return m.status==='upcoming'||(m.status==='active'&&m.date>=TODAY); }
function isPast(m)   { return m.status==='past'||(m.status==='active'&&m.date<TODAY); }
function isCancelled(m){ return m.status==='cancelled'; }

function getFiltered() {
  const q  = document.getElementById('searchQ').value.trim().toLowerCase();
  return meetings.filter(m => {
    const mq = !q || m.title.toLowerCase().includes(q) || m.presenter.toLowerCase().includes(q);
    const mt = typeFilter==='all' || m.type===typeFilter;
    const ms = tabFilter==='all'
            || (tabFilter==='upcoming'  && isCurrent(m))
            || (tabFilter==='past'      && isPast(m))
            || (tabFilter==='cancelled' && isCancelled(m));
    return mq && mt && ms;
  });
}

/* ── TABS ── */
function setTab(t) {
  tabFilter = t;
  ['all','upcoming','past','cancelled'].forEach(k=>{
    document.getElementById('stab-'+k).classList.toggle('on', k===t);
  });
  renderMeetings();
}

function setTypeF(t) {
  typeFilter = t;
  ['all','online','onsite'].forEach(k=>{
    document.getElementById('tf-'+k).classList.toggle('on', k===t);
  });
  renderMeetings();
}

/* ── UPDATE COUNTS ── */
function updateCounts() {
  const total = meetings.length;
  const cur   = meetings.filter(isCurrent).length;
  const past  = meetings.filter(isPast).length;
  const canc  = meetings.filter(isCancelled).length;
  const onl   = meetings.filter(m=>m.type==='online').length;
  const ons   = meetings.filter(m=>m.type==='onsite').length;

  document.getElementById('s-total').textContent    = total;
  document.getElementById('s-upcoming').textContent = cur;
  document.getElementById('s-online').textContent   = onl;
  document.getElementById('s-onsite').textContent   = ons;

  document.getElementById('sc-all').textContent       = total;
  document.getElementById('sc-upcoming').textContent  = cur;
  document.getElementById('sc-past').textContent      = past;
  document.getElementById('sc-cancelled').textContent = canc;
}

/* ── RENDER ── */
function renderMeetings() {
  const list = getFiltered();
  const grid = document.getElementById('meetings-grid');

  if (!list.length) {
    grid.innerHTML = `<div class="user-empty">
      <span class="user-empty-icon"><i class="fa-regular fa-calendar-xmark"></i></span>
      <h3>لا توجد اجتماعات</h3>
      <p>لا توجد اجتماعات تطابق معايير البحث الحالية</p>
    </div>`;
    return;
  }

  list.sort((a,b)=>a.date.localeCompare(b.date));
  grid.innerHTML = list.map(m => meetingCard(m)).join('');
}

function meetingCard(m) {
  const acc   = ACCENT[m.cat] || '#2ab8d0';
  const isC   = isCancelled(m);
  const isCur = isCurrent(m);
  const tBadge= m.type==='online' ? 'b-online' : 'b-onsite';
  const tLabel= m.type==='online' ? '<i class="fa-solid fa-wifi" style="font-size:.6rem"></i> عن بعد' : '<i class="fa-solid fa-building" style="font-size:.6rem"></i> حضوري';

  // Status badge
  let statusBadge = '';
  if (isC)       statusBadge = `<span class="badge b-cancelled"><i class="fa-solid fa-ban" style="font-size:.55rem"></i> ملغي</span>`;
  else if (!isCur) statusBadge = `<span class="badge b-done"><i class="fa-solid fa-check" style="font-size:.55rem"></i> مكتمل</span>`;

  // Joined indicator (small badge shown on card if already registered)
  const hasJoined = joined.has(m.id);
  const joinedIndicator = (isCur && !isC && hasJoined)
    ? `<div style="text-align:center;margin:-4px 0 8px"><span class="joining-badge"><i class="fa-solid fa-check"></i> تم تسجيل الحضور</span></div>`
    : '';

  // Action icon: video camera for online → opens meeting link
  //              location pin for onsite → opens Google Maps
  let actionIcon = '';
  if (!isC && isCur) {
    if (m.type === 'online' && m.link) {
      actionIcon = `<a href="${m.link}" target="_blank" class="card-action-icon card-action-online" title="انضم للاجتماع عن بعد" onclick="notifyJoin(${m.id},event)"><i class="fa-solid fa-video"></i></a>`;
    } else if (m.type === 'onsite') {
      // Use admin-saved location_url, or fall back to Google Maps search on the location label
      const locUrl = m.locationUrl || m.location_url ||
        (m.location ? 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(m.location) : '#');
      const hasUrl = locUrl !== '#';
      actionIcon = `<a href="${locUrl}" ${hasUrl ? 'target="_blank"' : 'onclick="return false"'} class="card-action-icon card-action-onsite" title="${m.location ? 'عرض موقع الاجتماع' : 'لم يُحدد موقع'}"><i class="fa-solid fa-location-dot"></i></a>`;
    }
  }

  return `
  <div class="meeting-card" style="opacity:${isC?'0.7':'1'}">
    <div class="card-stripe" style="background:linear-gradient(90deg,${isC?'#c62828':acc},${isC?'#c6282888':acc+'88'})"></div>
    <div class="card-inner">
      <div class="card-row1">
        <div class="card-badges">
          <span class="badge ${tBadge}">${tLabel}</span>
          <span class="badge ${CAT_BADGE[m.cat]||''}">${m.cat}</span>
          ${statusBadge}
        </div>
      </div>
      <div class="card-title">${m.title}</div>
      <div class="card-meta">
        <div class="meta-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          ${fmtDate(m.date)}${m.time?' — '+m.time:''}
        </div>
        ${m.location?`<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>${m.location}</div>`:''}
        ${m.type==='online'&&m.link?`<div class="meta-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg><span style="color:var(--teal);font-size:.75rem;font-weight:700">${(()=>{try{return new URL(m.link).hostname.replace('www.','')}catch{return m.link}})()} </span></div>`:''}
      </div>
    </div>
    ${joinedIndicator}
    <div class="card-foot" style="padding:12px 18px;border-top:1px solid var(--border);background:var(--fog);display:flex;align-items:center;justify-content:space-between;gap:10px">
      <div style="display:flex;align-items:center;gap:8px;min-width:0">
        <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,${isC?'#c62828':acc},${isC?'#c62828aa':acc+'aa'});display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:white;flex-shrink:0">${ini(m.presenter)}</div>
        <div style="min-width:0">
          <div style="font-size:.8rem;font-weight:800;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${m.presenter}</div>
          <div style="font-size:.68rem;color:var(--muted)">المقدم</div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
        ${actionIcon}
        <button class="btn-details-main" onclick="openDetails(${m.id})">التفاصيل</button>
      </div>
    </div>
  </div>`;
}

/* ── NOTIFY JOIN (from card icon — records attendance but does NOT reopen the link) ── */
function notifyJoin(id, ev) {
  if (ev) ev.stopPropagation(); // don't bubble to card; let the <a> handle navigation
  const m = meetings.find(x=>x.id===id);
  if (!m || joined.has(id)) return;
  joined.add(id);
  sessionStorage.setItem('tkamel_joined', JSON.stringify([...joined]));
  renderMeetings();
  fetch('/api/meetings/'+id+'/join', {
    method:'POST',
    headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
    body: JSON.stringify({user_name:USER_NAME}),
  }).catch(()=>{});
  showToast('✅', 'تم تسجيل الانضمام: '+m.title);
}

/* ── JOIN MEETING ── */
async function joinMeeting(id, ev) {
  if (ev) { ev.stopPropagation(); }
  const m = meetings.find(x=>x.id===id);
  if (!m) return;

  // Optimistic UI
  joined.add(id);
  sessionStorage.setItem('tkamel_joined', JSON.stringify([...joined]));
  renderMeetings();

  // Notify admin via API
  try {
    await fetch('/api/meetings/'+id+'/join', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ user_name: USER_NAME }),
    });
  } catch(e) { /* silent – UI already updated */ }

  const action = m.type==='online' ? 'الانضمام إلى' : 'الحضور في';
  showToast('✅', 'تم تسجيل '+action+' الاجتماع: '+m.title);

  // Open link for online meetings
  if (m.type==='online' && m.link) {
    setTimeout(()=>window.open(m.link,'_blank'), 400);
  }
}

/* ── DETAILS MODAL ── */
function openDetails(id) {
  const m = meetings.find(x=>x.id===id);
  if (!m) return;
  viewingId = id;
  const isC   = isCancelled(m);
  const isCur = isCurrent(m);

  document.getElementById('d-title').textContent = m.title;

  // Badges
  const tBadge = m.type === 'online' ? 'b-online' : 'b-onsite';
  const tLabel = m.type === 'online' ? '<i class="fa-solid fa-wifi" style="font-size:0.65rem"></i> عن بعد' : '<i class="fa-solid fa-building" style="font-size:0.65rem"></i> حضوري';
  const sBadge = isC ? `<span class="badge b-cancelled"><i class="fa-solid fa-ban" style="font-size:0.6rem"></i> ملغي</span>`
                     : (!isCur ? `<span class="badge b-done"><i class="fa-solid fa-check" style="font-size:0.6rem"></i> مكتمل</span>`
                               : `<span class="badge" style="background:#e0e7ff;color:#4338ca"><i class="fa-regular fa-calendar-check" style="font-size:0.6rem"></i> قادم</span>`);

  document.getElementById('d-badges').innerHTML = `
    <span class="badge ${CAT_BADGE[m.cat]||''}">${m.cat}</span>
    <span class="badge ${tBadge}">${tLabel}</span>
    ${sBadge}
  `;

  // Cancel alert
  const ca = document.getElementById('d-cancel-alert');
  if (isC && m.cancelReason) { ca.style.display = 'block'; document.getElementById('d-cancel-reason').textContent = m.cancelReason; }
  else ca.style.display = 'none';

  // Info grid
  document.getElementById('d-date').textContent = fmtDate(m.date);
  document.getElementById('d-time').textContent = (m.time || '—') + (m.duration ? ` (${m.duration} دقيقة)` : '');
  document.getElementById('d-presenter').textContent = m.presenter || '—';

  // Location / Link
  const locWrap = document.getElementById('m-loc-wrap');
  if (m.type === 'online') {
    locWrap.innerHTML = `<div class="m-item-icon"><i class="fa-solid fa-link"></i></div><div><div class="m-item-lbl">رابط الاجتماع</div><div class="m-item-val"><a href="${m.link||'#'}" target="_blank" style="color:var(--teal);text-decoration:none">${m.link ? 'انقر هنا للانضمام' : 'لم يُحدد رابط'}</a></div></div>`;
  } else {
    const locUrl = m.locationUrl || m.location_url;
    const locHtml = locUrl ? `<a href="${locUrl}" target="_blank" style="color:var(--ink);text-decoration:none">${m.location||'رابط الموقع'} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.7rem;color:var(--teal)"></i></a>` : (m.location||'—');
    locWrap.innerHTML = `<div class="m-item-icon"><i class="fa-solid fa-location-dot"></i></div><div><div class="m-item-lbl">مكان الاجتماع</div><div class="m-item-val">${locHtml}</div></div>`;
  }

  // Description / Notes
  const ds = document.getElementById('d-desc-sec');
  if (m.description || m.notes) {
    ds.style.display = 'block';
    document.getElementById('d-desc').textContent = m.description || m.notes;
  } else ds.style.display = 'none';

  // Targets
  const ts = document.getElementById('d-targets-sec');
  if (m.targets && m.targets.length > 0) {
    ts.style.display = 'block';
    document.getElementById('d-targets').innerHTML = m.targets.map(t => `<span class="m-target"><i class="fa-solid fa-building-user"></i> ${t}</span>`).join('');
  } else ts.style.display = 'none';

  // Agenda
  const ag = document.getElementById('d-agenda-sec');
  if (m.agendaItems && m.agendaItems.length > 0) {
    ag.style.display = 'block';
    document.getElementById('d-agenda').innerHTML = m.agendaItems.map((a, i) => `
      <div class="m-agenda">
        <div class="m-ag-num">${i+1}</div>
        <div class="m-ag-info">
          <div class="m-ag-title">${a.title}</div>
          <div class="m-ag-pres">${a.presenter || '—'}</div>
        </div>
        ${a.duration ? `<div class="m-ag-dur">${a.duration} دقيقة</div>` : ''}
      </div>
    `).join('');
  } else ag.style.display = 'none';

  // Footer Action (Join button)
  const act = document.getElementById('d-join-action');
  if (isCur && !isC) {
    if (joined.has(id)) {
      act.innerHTML = `<button class="btn-mdl-join joined"><i class="fa-solid fa-check"></i> تم تسجيل الحضور</button>`;
    } else {
      act.innerHTML = `<button class="btn-mdl-join" onclick="joinFromModal()"><i class="fa-solid fa-right-to-bracket"></i> التسجيل في الاجتماع</button>`;
    }
  } else { act.innerHTML = ''; }

  openOv('ov-details');
}

function joinFromModal() {
  if (!viewingId) return;
  joinMeeting(viewingId, null);
  // Update button inline
  const act = document.getElementById('d-join-action');
  if (act) act.innerHTML = `<button class="btn-mdl-join joined"><i class="fa-solid fa-check"></i> تم تسجيل الحضور</button>`;
}

/* ── MODAL UTILS ── */
function openOv(id) { document.getElementById(id).classList.add('open'); }
function closeOv(id){ document.getElementById(id).classList.remove('open'); }
function bgClose(e,id){ if(e.target===document.getElementById(id)) closeOv(id); }
document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeOv('ov-details'); });

/* ── TOAST ── */
let tTimer;
function showToast(icon,msg) {
  const el=document.getElementById('toast');
  document.getElementById('t-icon').textContent=icon;
  document.getElementById('t-msg').textContent=msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer=setTimeout(()=>el.classList.remove('show'),3200);
}

/* ── LOAD DATA ── */
async function loadMeetings() {
  try {
    const res  = await fetch('/api/user/meetings', { headers:{'Accept':'application/json'} });
    const data = await res.json();
    meetings   = Array.isArray(data) ? data.map(m => ({
      ...m,
      locationUrl: m.location_url || m.locationUrl || null,
    })) : [];
  } catch(e) { meetings=[]; }
  updateCounts();
  renderMeetings();
}

document.addEventListener('DOMContentLoaded', loadMeetings);
</script>
</body>
</html>
<?php /**PATH /home/a-22/Documents/tkamel/resources/views/user/meetings.blade.php ENDPATH**/ ?>