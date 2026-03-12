<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تكامل — بوابة الجمعيات</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --teal: #1a6b7c;
      --teal-deep: #0d3d49;
      --teal-glow: #2ab8d0;
      --green: #2eaa78;
      --purple: #7b4ea6;
      --blue: #3a72b8;
      --ink: #0e1f28;
      --fog: #f3f7f9;
      --white: #ffffff;
      --muted: #6a8494;
      --border: rgba(26, 107, 124, 0.18);
      --radius: 14px;
      --shadow-sm: 0 2px 12px rgba(13, 61, 73, 0.07);
      --shadow-md: 0 8px 32px rgba(13, 61, 73, 0.12);
      --shadow-lg: 0 20px 60px rgba(13, 61, 73, 0.18);
      --transition: 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    html, body { height: 100%; font-family: 'Cairo', sans-serif; background: var(--fog); overflow: hidden; }

    .shell { display: flex; height: 100vh; width: 100vw; }

    /* ── BRAND PANEL ── */
    .brand-panel {
      width: 35%; flex-shrink: 0;
      background: var(--teal-deep);
      position: relative; overflow: hidden;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      padding: 48px 40px;
    }
    .brand-panel::before {
      content: ''; position: absolute; inset: -60px;
      background:
        radial-gradient(ellipse 60% 55% at 15% 20%, rgba(42,184,208,.40) 0%, transparent 55%),
        radial-gradient(ellipse 55% 60% at 85% 75%, rgba(46,170,120,.30) 0%, transparent 55%),
        radial-gradient(ellipse 50% 50% at 60%  5%, rgba(123,78,166,.22) 0%, transparent 55%),
        radial-gradient(ellipse 45% 45% at  5% 90%, rgba(58,114,184,.22) 0%, transparent 55%);
      animation: meshFloat 14s ease-in-out infinite alternate;
    }
    .brand-panel::after {
      content: ''; position: absolute; inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size: 48px 48px; pointer-events: none;
    }
    @keyframes meshFloat { from { transform: scale(1) rotate(0deg); } to { transform: scale(1.10) rotate(5deg); } }

    .orb { position: absolute; border-radius: 50%; filter: blur(70px); opacity: .25; pointer-events: none; }
    .orb1 { width:320px; height:320px; background:var(--teal-glow); top:-100px;  left:-80px;  animation: od1 11s ease-in-out infinite alternate; }
    .orb2 { width:260px; height:260px; background:var(--green);     bottom:-80px; right:-50px; animation: od2 15s ease-in-out infinite alternate; }
    .orb3 { width:180px; height:180px; background:var(--purple);    bottom:28%;  left:8%;    animation: od3  9s ease-in-out infinite alternate; }
    @keyframes od1 { to { transform: translate(30px, 50px); } }
    @keyframes od2 { to { transform: translate(-25px,-35px); } }
    @keyframes od3 { to { transform: translate(20px,-25px); } }

    .brand-content { position: relative; z-index: 2; text-align: center; color: white; width: 100%; }

    .brand-logo-wrap { display:flex; align-items:center; justify-content:center; margin-bottom:8px; }
    .brand-logo-img {
      width:280px; height:160px; object-fit:contain;
      filter: brightness(0) invert(1) drop-shadow(0 0 18px rgba(42,184,208,.7));
      animation: lglow 3.5s ease-in-out infinite alternate;
    }
    @keyframes lglow {
      from { filter: brightness(0) invert(1) drop-shadow(0 0 10px rgba(42,184,208,.4)); }
      to   { filter: brightness(0) invert(1) drop-shadow(0 0 30px rgba(42,184,208,1)); }
    }
    .brand-tagline { font-size:.88rem; color:rgba(255,255,255,.5); margin-bottom:40px; letter-spacing:.3px; }

    .brand-features { display:flex; flex-direction:column; gap:12px; margin-bottom:36px; text-align:right; }
    .brand-feat {
      display:flex; align-items:center; gap:12px;
      background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.10);
      border-radius:12px; padding:12px 16px; backdrop-filter:blur(8px);
      transition:background var(--transition);
    }
    .brand-feat:hover { background:rgba(255,255,255,.12); }
    .brand-feat-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .bfi-teal   { background:rgba(42,184,208,.2);  color:var(--teal-glow); }
    .bfi-green  { background:rgba(46,170,120,.2);  color:#4fd9a0; }
    .bfi-purple { background:rgba(123,78,166,.2);  color:#c086f5; }
    .brand-feat-text strong { display:block; font-size:.85rem; color:rgba(255,255,255,.9); font-weight:700; }
    .brand-feat-text span   { font-size:.75rem; color:rgba(255,255,255,.45); }

    .brand-stats { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
    .stat-badge {
      background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.11);
      border-radius:12px; padding:12px 18px; backdrop-filter:blur(8px);
      text-align:center; transition:background var(--transition); flex:1; min-width:80px;
    }
    .stat-badge:hover { background:rgba(255,255,255,.13); }
    .stat-num { font-size:1.35rem; font-weight:900; color:var(--teal-glow); display:block; line-height:1.2; }
    .stat-lbl  { font-size:.7rem; color:rgba(255,255,255,.45); }

    /* ── FORM PANEL ── */
    .form-panel {
      flex:1; overflow-y:auto;
      display:flex; align-items:center; justify-content:center;
      padding:36px 32px; background:var(--fog); position:relative;
    }
    .form-panel::before {
      content:''; position:absolute; inset:0;
      background:
        radial-gradient(ellipse 55% 40% at 85% 10%, rgba(42,184,208,.06) 0%, transparent 60%),
        radial-gradient(ellipse 45% 45% at 15% 90%, rgba(46,170,120,.05) 0%, transparent 60%);
      pointer-events:none;
    }
    .form-box { width:100%; max-width:420px; position:relative; z-index:1; }
    .form-welcome { text-align:center; margin-bottom:24px; }
    .form-welcome-icon {
      width:52px; height:52px;
      background:linear-gradient(135deg, var(--teal-deep), var(--teal));
      border-radius:16px; display:inline-flex; align-items:center; justify-content:center;
      color:white; font-size:1.4rem; margin-bottom:12px;
      box-shadow:0 8px 24px rgba(13,61,73,.25);
    }

    /* Tabs */
    .tabs { display:flex; background:rgba(13,61,73,.07); border-radius:12px; padding:4px; margin-bottom:24px; gap:4px; }
    .tab-btn {
      flex:1; padding:10px 8px; border:none; background:transparent; border-radius:9px;
      font-family:'Cairo',sans-serif; font-size:.9rem; font-weight:700; color:var(--muted);
      cursor:pointer; transition:all var(--transition);
      display:flex; align-items:center; justify-content:center; gap:6px;
    }
    .tab-btn.active { background:var(--white); color:var(--teal-deep); box-shadow:var(--shadow-sm); }

    .form-section { display:none; }
    .form-section.active { display:block; animation:fadeSlide .35s cubic-bezier(.16,1,.3,1); }
    @keyframes fadeSlide { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

    /* Steps */
    .steps { display:flex; align-items:flex-start; margin-bottom:22px; position:relative; }
    .steps-track { position:absolute; top:13px; right:calc(16% + 13px); left:calc(16% + 13px); height:2px; background:var(--border); z-index:0; }
    .steps-progress { height:100%; background:linear-gradient(90deg, var(--teal), var(--teal-glow)); border-radius:2px; transition:width .4s ease; width:0%; }
    .step-item { flex:1; display:flex; flex-direction:column; align-items:center; position:relative; z-index:1; }
    .step-dot {
      width:28px; height:28px; border-radius:50%; border:2px solid var(--border);
      background:var(--fog); display:flex; align-items:center; justify-content:center;
      font-size:.68rem; font-weight:800; color:var(--muted); transition:all .3s;
    }
    .step-item.active .step-dot { border-color:var(--teal); background:var(--teal); color:white; box-shadow:0 0 0 4px rgba(26,107,124,.15); }
    .step-item.done   .step-dot { border-color:var(--teal); background:var(--teal); color:white; }
    .step-lbl { font-size:.68rem; color:var(--muted); margin-top:5px; font-weight:600; }
    .step-item.active .step-lbl { color:var(--teal); font-weight:800; }
    .step-item.done   .step-lbl { color:var(--teal); }

    /* Section heading */
    .sec-head { margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border); }
    .sec-head h2 { font-size:1.45rem; font-weight:800; color:var(--ink); margin-bottom:3px; }
    .sec-head p  { font-size:.84rem; color:var(--muted); }

    /* Form groups */
    .fg { margin-bottom:14px; }
    .fg label { display:flex; align-items:center; gap:4px; font-size:.8rem; font-weight:700; color:var(--teal-deep); margin-bottom:6px; }
    .req { color:#e05252; }
    .field { position:relative; }
    .fi { position:absolute; right:13px; top:50%; transform:translateY(-50%); color:var(--muted); pointer-events:none; display:flex; transition:color var(--transition); }
    .field:focus-within .fi { color:var(--teal); }
    .field input, .field select {
      width:100%; padding:11px 42px 11px 14px;
      background:var(--white); border:1.5px solid var(--border); border-radius:var(--radius);
      font-family:'Cairo',sans-serif; font-size:.9rem; color:var(--ink);
      outline:none; transition:all var(--transition); direction:rtl;
      -webkit-appearance:none; appearance:none;
    }
    .field input:focus, .field select:focus { border-color:var(--teal); box-shadow:0 0 0 4px rgba(26,107,124,.10); }
    .field input::placeholder { color:#b8ccd4; }
    .tpw {
      position:absolute; left:11px; top:50%; transform:translateY(-50%);
      background:none; border:none; cursor:pointer; color:var(--muted);
      padding:4px; display:flex; transition:color var(--transition); border-radius:6px;
    }
    .tpw:hover { color:var(--teal); }
    .hint { font-size:.72rem; color:var(--muted); margin-top:4px; padding-right:2px; display:flex; align-items:center; gap:4px; }
    .hint::before { content:'\f05a'; font-family:'Font Awesome 6 Free'; font-weight:900; font-size:.68rem; color:var(--teal-glow); }
    .row2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }

    /* Category chips */
    .cat-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .cat-chip {
      padding:9px 8px; border:1.5px solid var(--border); border-radius:10px;
      background:var(--white); font-family:'Cairo',sans-serif; font-size:.78rem; font-weight:600;
      color:var(--muted); cursor:pointer; transition:all var(--transition); text-align:center; display:block;
    }
    .cat-chip:hover { border-color:var(--teal-glow); color:var(--teal); background:rgba(42,184,208,.05); }
    .cat-chip.sel { border-color:var(--teal); background:rgba(26,107,124,.07); color:var(--teal-deep); font-weight:700; }
    .cat-chip input { display:none; }

    /* Buttons */
    .btn-row { display:flex; gap:10px; margin-top:20px; }
    .btn-p {
      flex:1; padding:13px;
      background:linear-gradient(135deg, var(--teal-deep) 0%, var(--teal) 60%, var(--teal-glow) 100%);
      color:white; border:none; border-radius:var(--radius);
      font-family:'Cairo',sans-serif; font-size:.95rem; font-weight:700;
      cursor:pointer; transition:all var(--transition);
      box-shadow:0 4px 20px rgba(13,61,73,.25);
      position:relative; overflow:hidden;
      display:flex; align-items:center; justify-content:center; gap:8px;
    }
    .btn-p:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(13,61,73,.35); }
    .btn-p:active { transform:translateY(0); }
    .btn-p:disabled { opacity:.65; cursor:not-allowed; transform:none; }
    .btn-s {
      padding:13px 18px; background:var(--white); color:var(--teal);
      border:1.5px solid var(--border); border-radius:var(--radius);
      font-family:'Cairo',sans-serif; font-size:.88rem; font-weight:700;
      cursor:pointer; transition:all var(--transition);
      display:flex; align-items:center; gap:6px;
    }
    .btn-s:hover { border-color:var(--teal); background:rgba(26,107,124,.04); }

    /* Password strength */
    .pw-str { display:flex; gap:4px; margin-top:6px; }
    .pw-b { height:3px; flex:1; border-radius:4px; background:var(--border); transition:background .3s; }
    .pw-b.weak { background:#ef5350; }
    .pw-b.med  { background:#ffa726; }
    .pw-b.str  { background:var(--green); }
    .pw-hint { font-size:.7rem; margin-top:4px; color:var(--muted); transition:color .3s; }

    /* Summary */
    .summary-box { background:rgba(26,107,124,.04); border:1.5px solid var(--border); border-radius:var(--radius); padding:16px 18px; margin-bottom:16px; font-size:.86rem; color:var(--ink); line-height:2.1; }
    .summary-box strong { color:var(--teal-deep); }

    /* Toast */
    .toast {
      position:fixed; top:24px; left:50%; transform:translateX(-50%);
      z-index:9999; min-width:260px; max-width:380px;
      background:var(--white); border-radius:12px;
      padding:14px 18px; font-size:.88rem;
      box-shadow:var(--shadow-lg); border-right:4px solid var(--teal);
      display:flex; align-items:center; gap:10px;
      opacity:0; pointer-events:none;
      transition:opacity .25s, transform .25s;
    }
    .toast.show { opacity:1; pointer-events:auto; }
    .toast.error   { border-right-color:#ef5350; }
    .toast.success { border-right-color:var(--green); }

    /* Alert */
    .alert-info {
      background:rgba(42,184,208,.08); border:1px solid rgba(42,184,208,.22);
      border-radius:10px; padding:10px 14px; font-size:.8rem; color:var(--teal-deep);
      margin-bottom:16px; display:flex; align-items:center; gap:8px;
    }

    /* Spinner */
    .spin { width:16px; height:16px; border:2px solid rgba(255,255,255,.4); border-top-color:white; border-radius:50%; animation:spin .7s linear infinite; flex-shrink:0; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* Success */
    .success-wrap { display:none; text-align:center; padding:32px 0; animation:fadeSlide .4s ease; }
    .check-circle {
      width:72px; height:72px; border-radius:50%;
      background:linear-gradient(135deg, var(--green), var(--teal));
      display:inline-flex; align-items:center; justify-content:center;
      margin-bottom:18px; box-shadow:0 10px 32px rgba(46,170,120,.40);
      animation:popIn .5s cubic-bezier(.16,1,.3,1);
    }
    @keyframes popIn { from { transform:scale(0); opacity:0; } to { transform:scale(1); opacity:1; } }

    .footer-link { text-align:center; margin-top:18px; font-size:.83rem; color:var(--muted); }
    .footer-link a { color:var(--teal); font-weight:800; cursor:pointer; }
    .footer-link a:hover { text-decoration:underline; }

    .btn-full {
      width:100%; padding:13px;
      background:linear-gradient(135deg, var(--teal-deep) 0%, var(--teal) 60%, var(--teal-glow) 100%);
      color:white; border:none; border-radius:var(--radius);
      font-family:'Cairo',sans-serif; font-size:.97rem; font-weight:700;
      cursor:pointer; transition:all var(--transition);
      box-shadow:0 4px 20px rgba(13,61,73,.25);
      display:flex; align-items:center; justify-content:center; gap:8px;
    }
    .btn-full:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(13,61,73,.35); }
    .btn-full:disabled { opacity:.65; cursor:not-allowed; transform:none; }

    .form-panel::-webkit-scrollbar { width:4px; }
    .form-panel::-webkit-scrollbar-thumb { background:rgba(26,107,124,.18); border-radius:4px; }

    @media (max-width:820px) { .brand-panel { width:42%; padding:32px 28px; } .brand-features { display:none; } }
    @media (max-width:640px) { .brand-panel { display:none; } .form-panel { padding:28px 20px; } html,body { overflow:auto; } }
  </style>
</head>

<body>
  <!-- Toast notification -->
  <div class="toast" id="toast">
    <i id="toast-icon"></i>
    <span id="toast-msg"></span>
  </div>

  <div class="shell">

    <!-- ══ BRAND PANEL ══ -->
    <div class="brand-panel">
      <div class="orb orb1"></div>
      <div class="orb orb2"></div>
      <div class="orb orb3"></div>
      <div class="brand-content">
        <div class="brand-logo-wrap">
          <img class="brand-logo-img" src="{{ asset('images/logo.png.svg') }}" alt="تكامل" onerror="this.style.display='none'">
        </div>
        <div class="brand-tagline">منصة الجمعيات والمنظمات المجتمعية</div>
        <div class="brand-features">
          <div class="brand-feat">
            <div class="brand-feat-icon bfi-teal"><i class="fa-solid fa-hands-holding-child"></i></div>
            <div class="brand-feat-text"><strong>فرص تطوعية مشتركة</strong><span>تنسيق جهود المتطوعين بين الجمعيات</span></div>
          </div>
          <div class="brand-feat">
            <div class="brand-feat-icon bfi-green"><i class="fa-solid fa-layer-group"></i></div>
            <div class="brand-feat-text"><strong>مشاريع مشتركة</strong><span>توحيد الموارد لإنجاز مشاريع أكبر</span></div>
          </div>
          <div class="brand-feat">
            <div class="brand-feat-icon bfi-purple"><i class="fa-regular fa-calendar-check"></i></div>
            <div class="brand-feat-text"><strong>اجتماعات منظمة</strong><span>قرارات استراتيجية موحدة ومرتبة</span></div>
          </div>
        </div>
        <div class="brand-stats">
          <div class="stat-badge"><span class="stat-num">+20</span><span class="stat-lbl">مشروع مشترك</span></div>
          <div class="stat-badge"><span class="stat-num">+50</span><span class="stat-lbl">فرصة تطوعية</span></div>
          <div class="stat-badge"><span class="stat-num">+10</span><span class="stat-lbl">جمعية شريكة</span></div>
        </div>
      </div>
    </div>

    <!-- ══ FORM PANEL ══ -->
    <div class="form-panel">
      <div class="form-box">

        <div class="form-welcome">
          <div class="form-welcome-icon"><i class="fa-solid fa-building-circle-check"></i></div>
        </div>

        <div class="tabs">
          <button class="tab-btn active" onclick="switchTab('login')" id="tab-login">
            <i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول
          </button>
          <button class="tab-btn" onclick="switchTab('register')" id="tab-register">
            <i class="fa-solid fa-user-plus"></i> إنشاء حساب
          </button>
        </div>

        <!-- ═══ LOGIN ═══ -->
        <div class="form-section active" id="sec-login">
          <div class="sec-head">
            <h2>أهلاً بعودتك 👋</h2>
            <p>سجّل الدخول لإدارة جمعيتك ومتابعة مشاريعك</p>
          </div>

          <div class="fg">
            <label><i class="fa-solid fa-envelope" style="color:var(--teal-glow);font-size:.8rem"></i> البريد الإلكتروني</label>
            <div class="field">
              <span class="fi"><i class="fa-solid fa-envelope"></i></span>
              <input type="email" id="l-email" placeholder="البريد الرسمي للجمعية" dir="ltr" style="text-align:right" autocomplete="email">
            </div>
          </div>

          <div class="fg">
            <label><i class="fa-solid fa-lock" style="color:var(--teal-glow);font-size:.8rem"></i> كلمة المرور</label>
            <div class="field">
              <span class="fi"><i class="fa-solid fa-lock"></i></span>
              <input type="password" id="lpw" placeholder="أدخل كلمة المرور" style="padding-left:38px" autocomplete="current-password">
              <button class="tpw" type="button" onclick="tpw('lpw',this)"><i class="fa-regular fa-eye"></i></button>
            </div>
          </div>

          <div style="display:flex;justify-content:space-between;align-items:center;margin:-4px 0 18px">
            <label style="display:flex;align-items:center;gap:6px;font-size:.78rem;color:var(--muted);cursor:pointer;font-weight:500">
              <input type="checkbox" id="remember" style="accent-color:var(--teal);width:14px;height:14px"> تذكرني
            </label>
            <a href="#" style="font-size:.78rem;color:var(--teal);font-weight:700">نسيت كلمة المرور؟</a>
          </div>

          <button class="btn-full" id="btn-login" onclick="doLogin()">
            دخول <i class="fa-solid fa-arrow-left"></i>
          </button>

          <div class="footer-link" style="margin-top:20px">
            جمعية جديدة؟ <a onclick="switchTab('register')">إنشاء حساب</a>
          </div>
        </div>

        <!-- ═══ REGISTER ═══ -->
        <div class="form-section" id="sec-register">

          <div class="steps" id="steps-wrap">
            <div class="steps-track"><div class="steps-progress" id="steps-prog"></div></div>
            <div class="step-item active" id="si1"><div class="step-dot" id="sd1">1</div><div class="step-lbl">الجمعية</div></div>
            <div class="step-item" id="si2"><div class="step-dot" id="sd2">2</div><div class="step-lbl">المسؤول</div></div>
            <div class="step-item" id="si3"><div class="step-dot" id="sd3"><i class="fa-solid fa-check" style="font-size:.6rem"></i></div><div class="step-lbl">التأكيد</div></div>
          </div>

          <!-- Step 1 -->
          <div id="rs1">
            <div class="sec-head"><h2>بيانات الجمعية</h2><p>أدخل المعلومات الأساسية عن جمعيتك</p></div>
            <div class="fg">
              <label>اسم الجمعية <span class="req">*</span></label>
              <div class="field"><span class="fi"><i class="fa-solid fa-building"></i></span><input type="text" id="an" placeholder="الاسم الرسمي للجمعية"></div>
            </div>
            <div class="fg">
              <label>البريد الإلكتروني <span class="req">*</span></label>
              <div class="field"><span class="fi"><i class="fa-solid fa-envelope"></i></span><input type="email" id="ae" placeholder="البريد الرسمي للجمعية" dir="ltr" style="text-align:right"></div>
            </div>
            <div class="fg">
              <label>رقم الترخيص <span class="req">*</span></label>
              <div class="field"><span class="fi"><i class="fa-solid fa-id-card"></i></span><input type="text" id="lic" placeholder="رقم الترخيص الوزاري" dir="ltr" style="text-align:right"></div>
              <div class="hint">10 أرقام — صادر من وزارة الموارد البشرية</div>
            </div>
            <div class="fg">
              <label>تصنيف الجمعية <span class="req">*</span></label>
              <div class="cat-grid">
                <label class="cat-chip"><input type="radio" name="cat" value="خيرية"> 🤝 ضيوف الرحمن</label>
                <label class="cat-chip"><input type="radio" name="cat" value="ثقافية"> 📚 ثقافية وتعليمية</label>
                <label class="cat-chip"><input type="radio" name="cat" value="صحية"> 🌿 صحية وبيئية</label>
                <label class="cat-chip"><input type="radio" name="cat" value="اسرية"> ⚽ أسرية ورياضية</label>
                <label class="cat-chip"><input type="radio" name="cat" value="تنموية"> 📈 تنموية واقتصادية</label>
                <label class="cat-chip"><input type="radio" name="cat" value="دينية"> 🕌 دينية ودعوية</label>
              </div>
            </div>
            <div class="btn-row">
              <button class="btn-p" onclick="goStep(2)">التالي <i class="fa-solid fa-arrow-left"></i></button>
            </div>
          </div>

          <!-- Step 2 -->
          <div id="rs2" style="display:none">
            <div class="sec-head"><h2>بيانات المسؤول</h2><p>معلومات مسؤول الجمعية المعتمد</p></div>
            <div class="fg">
              <label>اسم المسؤول الكامل <span class="req">*</span></label>
              <div class="field"><span class="fi"><i class="fa-solid fa-user"></i></span><input type="text" id="mn" placeholder="الاسم كما في الهوية الوطنية"></div>
            </div>
            <div class="fg">
              <label>رقم الجوال <span class="req">*</span></label>
              <div class="field"><span class="fi"><i class="fa-solid fa-mobile-screen-button"></i></span><input type="tel" id="mp" placeholder="05XXXXXXXX" dir="ltr" style="text-align:right"></div>
            </div>
            <div class="row2">
              <div class="fg" style="margin-bottom:0">
                <label>كلمة المرور <span class="req">*</span></label>
                <div class="field">
                  <span class="fi"><i class="fa-solid fa-lock"></i></span>
                  <input type="password" id="pw1" placeholder="••••••••" style="padding-left:38px" oninput="pwStr(this.value)">
                  <button class="tpw" type="button" onclick="tpw('pw1',this)"><i class="fa-regular fa-eye"></i></button>
                </div>
                <div class="pw-str"><div class="pw-b" id="b1"></div><div class="pw-b" id="b2"></div><div class="pw-b" id="b3"></div><div class="pw-b" id="b4"></div></div>
                <div class="pw-hint" id="pw-hint-txt">أدخل كلمة مرور قوية</div>
              </div>
              <div class="fg" style="margin-bottom:0">
                <label>تأكيد المرور <span class="req">*</span></label>
                <div class="field">
                  <span class="fi"><i class="fa-solid fa-shield-check"></i></span>
                  <input type="password" id="pw2" placeholder="••••••••" style="padding-left:38px">
                  <button class="tpw" type="button" onclick="tpw('pw2',this)"><i class="fa-regular fa-eye"></i></button>
                </div>
              </div>
            </div>
            <div class="btn-row">
              <button class="btn-s" onclick="goStep(1)"><i class="fa-solid fa-arrow-right"></i> رجوع</button>
              <button class="btn-p" onclick="goStep(3)">التالي <i class="fa-solid fa-arrow-left"></i></button>
            </div>
          </div>

          <!-- Step 3 -->
          <div id="rs3" style="display:none">
            <div class="sec-head"><h2>مراجعة وتأكيد</h2><p>تحقق من البيانات قبل الإرسال</p></div>
            <div class="alert-info">
              <i class="fa-solid fa-circle-info" style="color:var(--teal-glow);flex-shrink:0"></i>
              سيتم مراجعة طلبكم وإشعاركم خلال 48 ساعة عمل
            </div>
            <div class="summary-box" id="sumbox"></div>
            <div class="btn-row">
              <button class="btn-s" onclick="goStep(2)"><i class="fa-solid fa-arrow-right"></i> رجوع</button>
              <button class="btn-p" id="btn-register" onclick="doRegister()"><i class="fa-solid fa-check"></i> إنشاء الحساب</button>
            </div>
          </div>

          <!-- Success -->
          <div class="success-wrap" id="reg-ok">
            <div class="check-circle"><i class="fa-solid fa-check" style="color:white;font-size:1.8rem"></i></div>
            <h2 style="font-size:1.4rem;font-weight:800;color:var(--ink);margin-bottom:8px">تم إنشاء الحساب بنجاح!</h2>
            <p style="color:var(--muted);font-size:.86rem;margin-bottom:24px;line-height:1.7">شكراً! سيتم مراجعة طلبكم والتواصل معكم<br>خلال 48 ساعة عمل</p>
            <button class="btn-full" onclick="switchTab('login')" style="max-width:220px;margin:0 auto">
              تسجيل الدخول <i class="fa-solid fa-arrow-left"></i>
            </button>
          </div>

          <div class="footer-link" id="reg-fl">لديك حساب؟ <a onclick="switchTab('login')">سجّل الدخول</a></div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // Laravel CSRF token for AJAX requests
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    /* ── Toast ── */
    let toastTimer;
    function showToast(msg, type = 'info') {
      const t    = document.getElementById('toast');
      const icon = document.getElementById('toast-icon');
      const msgEl = document.getElementById('toast-msg');
      t.className = 'toast show ' + type;
      icon.className = type === 'error' ? 'fa-solid fa-circle-xmark' : type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-info';
      icon.style.color = type === 'error' ? '#ef5350' : type === 'success' ? 'var(--green)' : 'var(--teal-glow)';
      msgEl.textContent = msg;
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => t.classList.remove('show'), 4500);
    }

    /* ── Tab switching ── */
    function switchTab(t) {
      ['register','login'].forEach(id => {
        document.getElementById('tab-' + id).classList.toggle('active', id === t);
        document.getElementById('sec-' + id).classList.toggle('active', id === t);
      });
    }

    /* ── Step navigation ── */
    let step = 1;
    function goStep(n) {
      if (n > step) {
        if (step === 1 && !validateStep1()) return;
        if (step === 2 && !validateStep2()) return;
      }
      document.getElementById('rs' + step).style.display = 'none';
      for (let i = 1; i <= 3; i++) {
        const el = document.getElementById('si' + i);
        el.classList.remove('active','done');
        if (i < n) el.classList.add('done');
        if (i === n) el.classList.add('active');
      }
      document.getElementById('steps-prog').style.width = ((n-1)/2*100) + '%';
      if (n === 3) buildSum();
      step = n;
      const target = document.getElementById('rs' + n);
      target.style.display = 'block';
      target.style.animation = 'none';
      requestAnimationFrame(() => target.style.animation = 'fadeSlide .35s cubic-bezier(.16,1,.3,1)');
    }

    /* ── Validation ── */
    function validateStep1() {
      const an  = document.getElementById('an').value.trim();
      const ae  = document.getElementById('ae').value.trim();
      const lic = document.getElementById('lic').value.trim();
      const cat = document.querySelector('input[name="cat"]:checked');
      if (!an)  { showToast('يرجى إدخال اسم الجمعية', 'error'); return false; }
      if (!ae || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(ae)) { showToast('يرجى إدخال بريد إلكتروني صحيح', 'error'); return false; }
      if (!lic) { showToast('يرجى إدخال رقم الترخيص', 'error'); return false; }
      if (!cat) { showToast('يرجى اختيار تصنيف الجمعية', 'error'); return false; }
      return true;
    }
    function validateStep2() {
      const mn  = document.getElementById('mn').value.trim();
      const mp  = document.getElementById('mp').value.trim();
      const pw1 = document.getElementById('pw1').value;
      const pw2 = document.getElementById('pw2').value;
      if (!mn)           { showToast('يرجى إدخال اسم المسؤول', 'error'); return false; }
      if (!mp)           { showToast('يرجى إدخال رقم الجوال', 'error'); return false; }
      if (pw1.length < 8){ showToast('كلمة المرور يجب أن تكون 8 أحرف على الأقل', 'error'); return false; }
      if (pw1 !== pw2)   { showToast('كلمتا المرور غير متطابقتين', 'error'); return false; }
      return true;
    }

    /* ── Summary ── */
    function buildSum() {
      const an   = document.getElementById('an').value || '—';
      const ae   = document.getElementById('ae').value || '—';
      const lic  = document.getElementById('lic').value || '—';
      const cat  = document.querySelector('input[name="cat"]:checked');
      const catV = cat ? cat.parentElement.textContent.trim() : '—';
      const mn   = document.getElementById('mn').value || '—';
      const mp   = document.getElementById('mp').value || '—';
      document.getElementById('sumbox').innerHTML =
        `<div><strong>اسم الجمعية:</strong> ${an}</div>
         <div><strong>البريد:</strong> <span dir="ltr">${ae}</span></div>
         <div><strong>رقم الترخيص:</strong> <span dir="ltr">${lic}</span></div>
         <div><strong>التصنيف:</strong> ${catV}</div>
         <div><strong>المسؤول:</strong> ${mn}</div>
         <div><strong>الجوال:</strong> <span dir="ltr">${mp}</span></div>`;
    }

    /* ── Register (POST to Laravel) ── */
    async function doRegister() {
      const btn = document.getElementById('btn-register');
      btn.disabled = true;
      btn.innerHTML = '<div class="spin"></div> جاري الإرسال...';

      const pw1 = document.getElementById('pw1').value;

      try {
        const res  = await fetch('{{ route("register.post") }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
          body: JSON.stringify({
            association_name:      document.getElementById('an').value.trim(),
            email:                 document.getElementById('ae').value.trim(),
            license_number:        document.getElementById('lic').value.trim(),
            category:              document.querySelector('input[name="cat"]:checked')?.value || '',
            manager_name:          document.getElementById('mn').value.trim(),
            phone:                 document.getElementById('mp').value.trim(),
            password:              pw1,
            password_confirmation: document.getElementById('pw2').value,
          }),
        });
        const data = await res.json();

        if (data.success) {
          document.getElementById('rs3').style.display = 'none';
          document.getElementById('steps-wrap').style.display = 'none';
          document.getElementById('reg-fl').style.display = 'none';
          const ok = document.getElementById('reg-ok');
          ok.style.display = 'block';
        } else {
          // Show first validation error if available
          const firstErr = data.errors ? Object.values(data.errors)[0][0] : data.message;
          showToast(firstErr || 'حدث خطأ، حاول مجدداً', 'error');
          btn.disabled = false;
          btn.innerHTML = '<i class="fa-solid fa-check"></i> إنشاء الحساب';
        }
      } catch (err) {
        showToast('تعذّر الاتصال بالخادم', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> إنشاء الحساب';
      }
    }

    /* ── Login (POST to Laravel) ── */
    async function doLogin() {
      const email    = document.getElementById('l-email').value.trim();
      const password = document.getElementById('lpw').value;
      const btn      = document.getElementById('btn-login');

      if (!email || !password) { showToast('يرجى إدخال البريد الإلكتروني وكلمة المرور', 'error'); return; }

      btn.disabled = true;
      btn.innerHTML = '<div class="spin"></div> جاري التحقق...';

      // Remember me
      if (document.getElementById('remember').checked) {
        localStorage.setItem('tkamel_email', email);
      } else {
        localStorage.removeItem('tkamel_email');
      }

      try {
        const res  = await fetch('{{ route("login.post") }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
          body: JSON.stringify({ email, password, remember: document.getElementById('remember').checked }),
        });
        const data = await res.json();

        if (data.success) {
          showToast('مرحباً ' + data.name + '! جاري التحويل...', 'success');
          setTimeout(() => { window.location.href = data.redirect; }, 800);
        } else {
          showToast(data.message || 'البريد الإلكتروني أو كلمة المرور غير صحيحة', 'error');
          btn.disabled = false;
          btn.innerHTML = 'دخول <i class="fa-solid fa-arrow-left"></i>';
        }
      } catch (err) {
        showToast('تعذّر الاتصال بالخادم', 'error');
        btn.disabled = false;
        btn.innerHTML = 'دخول <i class="fa-solid fa-arrow-left"></i>';
      }
    }

    /* ── Toggle password ── */
    function tpw(id, btn) {
      const inp  = document.getElementById(id);
      const show = inp.type === 'password';
      inp.type   = show ? 'text' : 'password';
      btn.querySelector('i').className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    }

    /* ── Password strength ── */
    function pwStr(v) {
      const bs = ['b1','b2','b3','b4'];
      bs.forEach(b => document.getElementById(b).className = 'pw-b');
      const hint = document.getElementById('pw-hint-txt');
      if (!v) { hint.textContent = 'أدخل كلمة مرور قوية'; hint.style.color = 'var(--muted)'; return; }
      const s = [/.{8,}/, /[A-Z]/, /[0-9]/, /[^A-Za-z0-9]/].filter(r => r.test(v)).length;
      const c = s <= 1 ? 'weak' : s <= 2 ? 'med' : 'str';
      for (let i = 0; i < s; i++) document.getElementById(bs[i]).classList.add(c);
      const labels = ['ضعيفة','مقبولة','جيدة','قوية جداً'];
      const colors = ['#ef5350','#ffa726','#66bb6a','var(--green)'];
      hint.textContent = labels[s-1] || '';
      hint.style.color = colors[s-1] || 'var(--muted)';
    }

    /* ── Category chips ── */
    document.querySelectorAll('.cat-chip').forEach(chip => {
      chip.addEventListener('click', () => {
        document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('sel'));
        chip.classList.add('sel');
      });
    });

    /* ── Enter key support ── */
    document.getElementById('lpw').addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });
    document.getElementById('l-email').addEventListener('keydown', e => { if (e.key === 'Enter') document.getElementById('lpw').focus(); });

    /* ── Restore remembered email ── */
    const savedEmail = localStorage.getItem('tkamel_email');
    if (savedEmail) {
      document.getElementById('l-email').value = savedEmail;
      document.getElementById('remember').checked = true;
    }
  </script>
</body>
</html>
