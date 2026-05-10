<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>تكامل | الإعدادات</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
  <style>
    /* ══ SETTINGS PAGE ══ */
    :root {
      --pg-bg:   rgb(94.12%, 97.65%, 100%);   /* #f0f9ff — page background  */
      --btn-bg:  rgb(5.1%, 49.8%, 63.14%);    /* #0d7f9f — primary button   */
      --btn-hover: #0a6a87;
      --card-bg: #ffffff;
      --border-c: rgba(13, 127, 159, 0.13);
      --active-tab: var(--btn-bg);
      --tab-text-active: #ffffff;
      --tab-bg: rgba(13, 127, 159, 0.07);
      --input-bg: #f4fafc;
      --input-border: rgba(13, 127, 159, 0.18);
      --input-focus: rgba(13, 127, 159, 0.45);
      --label-c: #374151;
      --muted-c: #6b7280;
      --danger-c: #dc2626;
      --success-bg: rgba(13, 127, 159, 0.08);
      --shadow: 0 4px 24px rgba(13, 127, 159, 0.10);
      --shadow-lg: 0 8px 40px rgba(13, 127, 159, 0.14);
    }

    body {
      background: var(--pg-bg) !important;
    }

    /* ── Layout wrapper ── */
    .settings-wrap {
      padding: 2rem 2.2rem;
    }

    .settings-header {
      margin-bottom: 1.8rem;
    }

    .settings-header h1 {
      font-size: 1.55rem;
      font-weight: 900;
      color: var(--ink);
      letter-spacing: -0.4px;
    }

    .settings-header p {
      color: var(--muted-c);
      font-size: 0.88rem;
      margin-top: 3px;
    }

    /* ── Main grid ── */
    .settings-grid {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 1.5rem;
      align-items: start;
    }

    /* ── Tab sidebar ── */
    .settings-tabs {
      background: var(--card-bg);
      border-radius: 16px;
      padding: 10px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border-c);
      position: sticky;
      top: 90px;
    }

    .stab {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 13px;
      border-radius: 10px;
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--muted-c);
      cursor: pointer;
      transition: all 0.18s ease;
      margin-bottom: 2px;
      border: none;
      background: transparent;
      width: 100%;
      text-align: right;
    }

    .stab i {
      width: 18px;
      text-align: center;
      font-size: 0.95rem;
      flex-shrink: 0;
    }

    .stab:hover {
      background: var(--tab-bg);
      color: var(--btn-bg);
    }

    .stab.active {
      background: var(--btn-bg);
      color: #fff;
      box-shadow: 0 4px 14px rgba(13, 127, 159, 0.3);
    }

    /* ── Panel card ── */
    .settings-panel {
      background: var(--card-bg);
      border-radius: 20px;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--border-c);
      padding: 2rem 2.2rem;
      display: none;
    }

    .settings-panel.active { display: block; }

    .panel-title {
      font-size: 1.15rem;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 1.6rem;
      padding-bottom: 0.9rem;
      border-bottom: 1.5px solid var(--border-c);
    }

    /* ── Avatar section ── */
    .avatar-row {
      display: flex;
      align-items: center;
      gap: 1.3rem;
      margin-bottom: 1.8rem;
    }

    .avatar-circle {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--btn-bg) 0%, #0ea5c9 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      font-weight: 900;
      color: #fff;
      flex-shrink: 0;
      box-shadow: 0 4px 18px rgba(13, 127, 159, 0.3);
      letter-spacing: -1px;
      position: relative;
      overflow: hidden;
    }

    .avatar-circle img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .avatar-actions {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .btn-upload {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 16px;
      background: var(--btn-bg);
      color: #fff;
      border: none;
      border-radius: 9px;
      font-size: 0.84rem;
      font-weight: 700;
      cursor: pointer;
      font-family: 'Tajawal', sans-serif;
      transition: background 0.17s;
    }

    .btn-upload:hover { background: var(--btn-hover); }

    .avatar-hint {
      font-size: 0.76rem;
      color: var(--muted-c);
    }

    /* ── Form grid ── */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.1rem 1.4rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-label {
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--label-c);
    }

    .form-input,
    .form-textarea {
      background: var(--input-bg);
      border: 1.5px solid var(--input-border);
      border-radius: 10px;
      padding: 10px 13px;
      font-family: 'Tajawal', sans-serif;
      font-size: 0.88rem;
      color: var(--ink);
      transition: border-color 0.17s, box-shadow 0.17s;
      outline: none;
      width: 100%;
    }

    .form-input:focus,
    .form-textarea:focus {
      border-color: var(--btn-bg);
      box-shadow: 0 0 0 3px var(--input-focus);
    }

    .form-input[readonly] {
      opacity: 0.55;
      cursor: not-allowed;
    }

    .form-input::placeholder,
    .form-textarea::placeholder { color: #9ca3af; }

    .form-textarea {
      resize: vertical;
      min-height: 90px;
    }

    /* ── Toggle row ── */
    .toggle-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--input-bg);
      border: 1.5px solid var(--input-border);
      border-radius: 12px;
      padding: 14px 16px;
      margin-bottom: 12px;
      transition: border-color 0.17s;
    }

    .toggle-row:hover { border-color: rgba(13, 127, 159, 0.3); }

    .toggle-info { display: flex; flex-direction: column; gap: 2px; }

    .toggle-label {
      font-size: 0.9rem;
      font-weight: 700;
      color: var(--ink);
    }

    .toggle-sub {
      font-size: 0.78rem;
      color: var(--muted-c);
    }

    /* iOS-style toggle */
    .toggle {
      position: relative;
      width: 44px;
      height: 24px;
      flex-shrink: 0;
    }

    .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }

    .toggle-track {
      position: absolute;
      inset: 0;
      border-radius: 100px;
      background: #d1d5db;
      cursor: pointer;
      transition: background 0.22s;
    }

    .toggle-track::after {
      content: '';
      position: absolute;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #fff;
      top: 3px;
      right: 3px;
      transition: transform 0.22s;
      box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    .toggle input:checked + .toggle-track { background: var(--btn-bg); }
    .toggle input:checked + .toggle-track::after { transform: translateX(-20px); }

    /* ── Accent colors ── */
    .accent-row {
      margin: 1.2rem 0;
    }

    .accent-label {
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--label-c);
      margin-bottom: 10px;
    }

    .accent-swatches {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .swatch {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      cursor: pointer;
      transition: transform 0.17s, box-shadow 0.17s;
      border: 3px solid transparent;
      outline: 2px solid transparent;
      outline-offset: 2px;
    }

    .swatch:hover { transform: scale(1.12); }

    .swatch.selected {
      outline: 2.5px solid var(--btn-bg);
      box-shadow: 0 0 0 3px rgba(13, 127, 159, 0.18);
    }

    /* ── 2FA box ── */
    .twofa-box {
      background: rgba(245, 158, 11, 0.07);
      border: 1.5px solid rgba(245, 158, 11, 0.28);
      border-radius: 13px;
      padding: 16px 18px;
      margin-top: 1.2rem;
    }

    .twofa-title {
      font-size: 0.92rem;
      font-weight: 800;
      color: #b45309;
      margin-bottom: 4px;
    }

    .twofa-sub {
      font-size: 0.8rem;
      color: #92400e;
      margin-bottom: 12px;
    }

    .btn-twofa {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 16px;
      background: #f59e0b;
      color: #fff;
      border: none;
      border-radius: 9px;
      font-size: 0.84rem;
      font-weight: 700;
      cursor: pointer;
      font-family: 'Tajawal', sans-serif;
      transition: background 0.17s;
    }

    .btn-twofa:hover { background: #d97706; }

    /* ── Notification toggles ── */
    .notif-section-title {
      font-size: 0.78rem;
      font-weight: 800;
      color: var(--muted-c);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin: 1.3rem 0 0.6rem;
    }

    /* ── Language cards ── */
    .lang-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 1.4rem;
    }

    .lang-card {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 13px 15px;
      border-radius: 12px;
      border: 2px solid var(--input-border);
      cursor: pointer;
      transition: all 0.18s;
      background: var(--input-bg);
    }

    .lang-card:hover { border-color: rgba(13, 127, 159, 0.3); }

    .lang-card.selected {
      border-color: var(--btn-bg);
      background: rgba(13, 127, 159, 0.06);
      box-shadow: 0 0 0 3px rgba(13, 127, 159, 0.1);
    }

    .lang-flag { font-size: 1.4rem; }

    .lang-info { display: flex; flex-direction: column; gap: 1px; }

    .lang-name {
      font-size: 0.88rem;
      font-weight: 700;
      color: var(--ink);
    }

    .lang-native {
      font-size: 0.76rem;
      color: var(--muted-c);
    }

    .lang-check {
      margin-right: auto;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 2px solid var(--input-border);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.18s;
      flex-shrink: 0;
    }

    .lang-card.selected .lang-check {
      background: var(--btn-bg);
      border-color: var(--btn-bg);
    }

    .lang-card.selected .lang-check::after {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #fff;
    }

    /* ── Footer action bar ── */
    .panel-footer {
      display: flex;
      justify-content: flex-end;
      padding-top: 1.4rem;
      margin-top: 1.4rem;
      border-top: 1.5px solid var(--border-c);
    }

    .btn-save {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 11px 26px;
      background: var(--btn-bg);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 0.92rem;
      font-weight: 800;
      cursor: pointer;
      font-family: 'Tajawal', sans-serif;
      transition: background 0.17s, transform 0.15s, box-shadow 0.17s;
      box-shadow: 0 4px 14px rgba(13, 127, 159, 0.3);
    }

    .btn-save:hover {
      background: var(--btn-hover);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(13, 127, 159, 0.38);
    }

    .btn-save:active { transform: translateY(0); }

    /* ── Toast (match other pages) ── */
    .toast {
      position: fixed;
      bottom: 28px;
      left: 50%;
      transform: translateX(-50%) translateY(80px);
      background: var(--ink);
      color: white;
      padding: 11px 22px;
      border-radius: 13px;
      font-size: 0.86rem;
      font-weight: 600;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
      z-index: 600;
      transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
      pointer-events: none;
    }

    .toast.show { transform: translateX(-50%) translateY(0); }

    @media (max-width: 900px) {
      .settings-grid { grid-template-columns: 1fr; }
      .form-grid { grid-template-columns: 1fr; }
      .lang-grid { grid-template-columns: 1fr; }
      .settings-tabs { display: flex; flex-wrap: wrap; gap: 4px; position: static; }
      .stab { width: auto; flex: 1; }
    }
  </style>
</head>

<body>
<?php echo $__env->make('layouts.sidebar-admin', ['activeNav' => 'settings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="main">
  <?php echo $__env->make('layouts.topbar', [
    'title'    => 'الإعدادات',
    'crumb'    => 'الإعدادات',
    'userName' => Auth::user()->full_name ?? 'مدير النظام',
    'userAv'   => mb_substr(Auth::user()->full_name ?? 'م', 0, 1),
  ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="content settings-wrap">

    <div class="settings-header">
      <h1>الإعدادات</h1>
      <p>إدارة حسابك وتخصيص تجربتك في المنصة</p>
    </div>

    <div class="settings-grid">

      
      <nav class="settings-tabs" role="tablist">
        <button class="stab active" onclick="switchTab('profile')" id="tab-profile" aria-selected="true">
          <i class="fa-regular fa-user"></i> الملف الشخصي
        </button>
        <button class="stab" onclick="switchTab('notifications')" id="tab-notifications">
          <i class="fa-regular fa-bell"></i> الإشعارات
        </button>
        <button class="stab" onclick="switchTab('security')" id="tab-security">
          <i class="fa-solid fa-lock" style="font-size:.8rem"></i> الأمان
        </button>
        <button class="stab" onclick="switchTab('appearance')" id="tab-appearance">
          <i class="fa-solid fa-palette" style="font-size:.8rem"></i> المظهر
        </button>
        <button class="stab" onclick="switchTab('language')" id="tab-language">
          <i class="fa-solid fa-globe" style="font-size:.8rem"></i> اللغة
        </button>
      </nav>

      
      <div class="settings-panels">

        
        <section class="settings-panel active" id="panel-profile">
          <div class="panel-title">معلومات الملف الشخصي</div>

          <div class="avatar-row">
            <div class="avatar-circle" id="avatar-preview">
              <?php if(!empty(Auth::user()->avatar_path)): ?>
                <img src="<?php echo e(asset('storage/' . Auth::user()->avatar_path)); ?>" alt="avatar">
              <?php else: ?>
                <?php echo e(mb_substr(Auth::user()->full_name ?? 'م', 0, 1)); ?>

              <?php endif; ?>
            </div>
            <div class="avatar-actions">
              <button class="btn-upload" onclick="document.getElementById('avatar-input').click()">
                <i class="fa-solid fa-camera"></i> تغيير الصورة
              </button>
              <span class="avatar-hint">JPG أو PNG أو GIF. الحد الأقصى 2 ميجابايت.</span>
              <input type="file" id="avatar-input" accept="image/*" style="display:none" onchange="previewAvatar(event)">
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">الاسم الكامل</label>
              <input type="text" class="form-input" id="full-name"
                     value="<?php echo e(Auth::user()->full_name ?? 'مدير النظام'); ?>" placeholder="أدخل الاسم الكامل">
            </div>
            <div class="form-group">
              <label class="form-label">البريد الإلكتروني</label>
              <input type="email" class="form-input" id="email"
                     value="<?php echo e(Auth::user()->email ?? 'admin@tkamel.sa'); ?>" placeholder="أدخل البريد الإلكتروني">
            </div>
            <div class="form-group">
              <label class="form-label">الدور</label>
              <input type="text" class="form-input" value="مدير النظام" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">رقم الهاتف</label>
              <input type="tel" class="form-input" id="phone" value="<?php echo e(Auth::user()->phone ?? ''); ?>" placeholder="+966 5X XXX XXXX">
            </div>
            <div class="form-group full">
              <label class="form-label">نبذة تعريفية</label>
              <textarea class="form-textarea" id="bio" placeholder="أخبرنا عن نفسك..."><?php echo e(Auth::user()->bio ?? ''); ?></textarea>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('profile')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        
        <section class="settings-panel" id="panel-notifications">
          <div class="panel-title">إعدادات الإشعارات</div>

          <div class="notif-section-title">الإشعارات العامة</div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">إشعارات طلبات التسجيل</span>
              <span class="toggle-sub">إشعار عند تقديم جمعية طلب تسجيل جديد</span>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">إشعارات طلبات الخدمة</span>
              <span class="toggle-sub">إشعار عند ورود طلب خدمة جديد من مبادرون</span>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">إشعارات الاجتماعات</span>
              <span class="toggle-sub">تذكير قبل موعد الاجتماع بساعة</span>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="notif-section-title" style="margin-top:1.5rem">قنوات الإشعار</div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">إشعارات البريد الإلكتروني</span>
              <span class="toggle-sub">استلام الإشعارات على بريدك الإلكتروني</span>
            </div>
            <label class="toggle">
              <input type="checkbox">
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">الإشعارات داخل المنصة</span>
              <span class="toggle-sub">عرض الإشعارات في قائمة التنبيهات</span>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('notifications')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        
        <section class="settings-panel" id="panel-security">
          <div class="panel-title">إعدادات الأمان</div>

          <div class="form-grid">
            <div class="form-group full">
              <label class="form-label">كلمة المرور الحالية</label>
              <input type="password" class="form-input" placeholder="أدخل كلمة المرور الحالية">
            </div>
            <div class="form-group">
              <label class="form-label">كلمة المرور الجديدة</label>
              <input type="password" class="form-input" id="new-pass" placeholder="أدخل كلمة المرور الجديدة">
            </div>
            <div class="form-group">
              <label class="form-label">تأكيد كلمة المرور الجديدة</label>
              <input type="password" class="form-input" id="confirm-pass" placeholder="أعد إدخال كلمة المرور">
            </div>
          </div>

          <div class="twofa-box">
            <div class="twofa-title">المصادقة الثنائية</div>
            <div class="twofa-sub">أضف طبقة حماية إضافية لحسابك</div>
            <button class="btn-twofa">
              <i class="fa-solid fa-shield-halved"></i> تفعيل المصادقة الثنائية
            </button>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('security')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        
        <section class="settings-panel" id="panel-appearance">
          <div class="panel-title">المظهر</div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">الوضع الداكن</span>
              <span class="toggle-sub">التبديل بين الثيم الفاتح والداكن</span>
            </div>
            <label class="toggle">
              <input type="checkbox" id="dark-mode-toggle">
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="accent-row">
            <div class="accent-label">لون التمييز</div>
            <div class="accent-swatches">
              <div class="swatch selected" style="background:#0d7f9f"    data-color="#0d7f9f"    title="أزرق مائي (الافتراضي)" onclick="selectSwatch(this)"></div>
              <div class="swatch"          style="background:#7c3aed"    data-color="#7c3aed"    title="بنفسجي"               onclick="selectSwatch(this)"></div>
              <div class="swatch"          style="background:#059669"    data-color="#059669"    title="أخضر"                 onclick="selectSwatch(this)"></div>
              <div class="swatch"          style="background:#2563eb"    data-color="#2563eb"    title="أزرق"                 onclick="selectSwatch(this)"></div>
              <div class="swatch"          style="background:#e11d48"    data-color="#e11d48"    title="أحمر وردي"            onclick="selectSwatch(this)"></div>
              <div class="swatch"          style="background:#f59e0b"    data-color="#f59e0b"    title="ذهبي"                 onclick="selectSwatch(this)"></div>
            </div>
          </div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">الوضع المضغوط</span>
              <span class="toggle-sub">تقليل المسافات والحشو لعرض أكثر كثافة</span>
            </div>
            <label class="toggle">
              <input type="checkbox" id="compact-toggle">
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('appearance')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        
        <section class="settings-panel" id="panel-language">
          <div class="panel-title">اللغة والمنطقة الزمنية</div>

          <div class="accent-label" style="margin-bottom:10px">لغة الواجهة</div>
          <div class="lang-grid">
            <div class="lang-card selected" onclick="selectLang(this)">
              <span class="lang-flag">🇸🇦</span>
              <div class="lang-info">
                <span class="lang-name">العربية</span>
                <span class="lang-native">Arabic</span>
              </div>
              <div class="lang-check"></div>
            </div>
            <div class="lang-card" onclick="selectLang(this)">
              <span class="lang-flag">🇺🇸</span>
              <div class="lang-info">
                <span class="lang-name">الإنجليزية</span>
                <span class="lang-native">English</span>
              </div>
              <div class="lang-check"></div>
            </div>
          </div>

          <div class="form-group" style="margin-top:.5rem">
            <label class="form-label">المنطقة الزمنية</label>
            <select class="form-input" style="cursor:pointer">
              <option value="Asia/Riyadh" selected>توقيت الرياض (GMT+3)</option>
              <option value="Asia/Dubai">توقيت دبي (GMT+4)</option>
              <option value="Africa/Cairo">توقيت القاهرة (GMT+2)</option>
              <option value="UTC">UTC (GMT+0)</option>
            </select>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('language')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

      </div>
    </div>
  </div>
</div>


<div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

<?php echo $__env->make('layouts.notif-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="<?php echo e(asset('js/menu.js')); ?>"></script>
<script>
  /* ── Tab switching ── */
  function switchTab(name) {
    document.querySelectorAll('.stab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    document.getElementById('panel-' + name).classList.add('active');
  }

  /* ── Avatar preview ── */
  async function previewAvatar(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { alert('الحد الأقصى للحجم هو 2 ميجابايت'); return; }
    const reader = new FileReader();
    reader.onload = ev => {
      const el = document.getElementById('avatar-preview');
      el.innerHTML = `<img src="${ev.target.result}" alt="avatar">`;
    };
    reader.readAsDataURL(file);

    // Upload immediately so header avatar updates too
    try {
      const fd = new FormData();
      fd.append('avatar', file);
      const res = await fetch('/api/settings/avatar', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: fd,
      });
      const data = await res.json();
      if (!res.ok || data.success === false) {
        const msg = data?.errors?.avatar?.[0] || data?.message || 'تعذر رفع الصورة';
        showToast(msg, 'error');
        return;
      }
      // Update topbar avatar with the uploaded image
      const av = document.getElementById('tu-av');
      if (av && data.avatar_url) av.innerHTML = `<img src="${data.avatar_url}" alt="avatar">`;
      showToast(data.message || 'تم تحديث الصورة', 'success');
    } catch (err) {
      showToast('تعذر الاتصال بالخادم', 'error');
    }
  }

  /* ── Accent swatch selection ── */
  function selectSwatch(el) {
    document.querySelectorAll('.swatch').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
  }

  /* ── Language selection ── */
  function selectLang(el) {
    document.querySelectorAll('.lang-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
  }

  /* ── Save with toast ── */
  async function saveChanges(section) {
    const msgs = {
      profile:       'تم حفظ بيانات الملف الشخصي',
      notifications: 'تم حفظ إعدادات الإشعارات',
      security:      'تم تحديث كلمة المرور بنجاح',
      appearance:    'تم حفظ إعدادات المظهر',
      language:      'تم تحديث اللغة والمنطقة الزمنية',
    };
    try {
      if (section === 'profile') {
        const res = await fetch('/api/settings/profile', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({
            full_name: document.getElementById('full-name')?.value || '',
            email: document.getElementById('email')?.value || '',
            phone: document.getElementById('phone')?.value || '',
            bio: document.getElementById('bio')?.value || '',
          }),
        });
        const data = await res.json();
        if (!res.ok || data.success === false) {
          const msg = data?.errors?.full_name?.[0] || data?.errors?.email?.[0] || data?.message || 'تعذر حفظ البيانات';
          showToast(msg, 'error');
          return;
        }
        // Update topbar name/avatar live
        if (data.user?.full_name) {
          document.getElementById('tu-name') && (document.getElementById('tu-name').textContent = data.user.full_name);
          if (data.user?.avatar_url) {
            document.getElementById('tu-av') && (document.getElementById('tu-av').innerHTML = `<img src="${data.user.avatar_url}" alt="avatar">`);
          } else {
            document.getElementById('tu-av') && (document.getElementById('tu-av').textContent = (data.user.full_name || 'م').trim().slice(0, 1));
          }
        }
        showToast(data.message || msgs[section], 'success');
        return;
      }

      if (section === 'security') {
        const newPass = document.getElementById('new-pass')?.value || '';
        const confirmPass = document.getElementById('confirm-pass')?.value || '';
        const res = await fetch('/api/settings/password', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          },
          body: JSON.stringify({
            new_password: newPass,
            confirm_password: confirmPass,
          }),
        });
        const data = await res.json();
        if (!res.ok || data.success === false) {
          const msg = data?.errors?.new_password?.[0] || data?.errors?.confirm_password?.[0] || data?.message || 'تعذر تحديث كلمة المرور';
          showToast(msg, 'error');
          return;
        }
        document.getElementById('new-pass') && (document.getElementById('new-pass').value = '');
        document.getElementById('confirm-pass') && (document.getElementById('confirm-pass').value = '');
        showToast(data.message || msgs[section], 'success');
        return;
      }

      // other tabs currently UI-only
      showToast(msgs[section] || 'تم حفظ التغييرات', 'success');
    } catch (e) {
      showToast('تعذر الاتصال بالخادم', 'error');
    }
  }

  let _settingsToastTimer;
  function showToast(msg, type = 'success') {
    const el = document.getElementById('toast');
    const icon = type === 'error' ? '⚠️' : '✅';
    const iconEl = document.getElementById('t-icon');
    const msgEl = document.getElementById('t-msg');
    if (iconEl) iconEl.textContent = icon;
    if (msgEl) msgEl.textContent = msg;
    el.classList.add('show');
    clearTimeout(_settingsToastTimer);
    _settingsToastTimer = setTimeout(() => el.classList.remove('show'), 3200);
  }

  /* ── URL hash tab activation on load ── */
  const hash = location.hash.replace('#', '');
  const validTabs = ['profile','notifications','security','appearance','language'];
  if (validTabs.includes(hash)) switchTab(hash);
</script>
</body>
</html>
<?php /**PATH /home/a-22/Documents/tkamel-updated (1)/tkamel/resources/views/settings.blade.php ENDPATH**/ ?>