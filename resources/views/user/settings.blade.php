<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تكامل | الإعدادات</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/consulting.css') }}">
  <style>
    :root {
      --pg-bg:   rgb(94.12%, 97.65%, 100%);
      --btn-bg:  rgb(5.1%, 49.8%, 63.14%);
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
    body { background: var(--pg-bg) !important; display:flex; min-height:100vh; }
    .layout { display:flex; width:100%; min-height:100vh; }
    .settings-wrap { padding: 2rem 2.2rem; }
    .settings-header { margin-bottom: 1.8rem; }
    .settings-header h1 { font-size: 1.55rem; font-weight: 900; color: var(--ink); letter-spacing: -0.4px; }
    .settings-header p { color: var(--muted-c); font-size: 0.88rem; margin-top: 3px; }
    .settings-grid { display: grid; grid-template-columns: 220px 1fr; gap: 1.5rem; align-items: start; }
    .settings-tabs { background: var(--card-bg); border-radius: 16px; padding: 10px; box-shadow: var(--shadow); border: 1px solid var(--border-c); position: sticky; top: 90px; }
    .stab { display: flex; align-items: center; gap: 10px; padding: 11px 13px; border-radius: 10px; font-size: 0.88rem; font-weight: 600; color: var(--muted-c); cursor: pointer; transition: all 0.18s ease; margin-bottom: 2px; border: none; background: transparent; width: 100%; text-align: right; }
    .stab i { width: 18px; text-align: center; font-size: 0.95rem; flex-shrink: 0; }
    .stab:hover { background: var(--tab-bg); color: var(--btn-bg); }
    .stab.active { background: var(--btn-bg); color: #fff; box-shadow: 0 4px 14px rgba(13, 127, 159, 0.3); }
    .settings-panel { background: var(--card-bg); border-radius: 20px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-c); padding: 2rem 2.2rem; display: none; }
    .settings-panel.active { display: block; }
    .panel-title { font-size: 1.15rem; font-weight: 800; color: var(--ink); margin-bottom: 1.6rem; padding-bottom: 0.9rem; border-bottom: 1.5px solid var(--border-c); }
    .avatar-row { display: flex; align-items: center; gap: 1.3rem; margin-bottom: 1.8rem; }
    .avatar-circle { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--btn-bg) 0%, #0ea5c9 100%); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 900; color: #fff; flex-shrink: 0; box-shadow: 0 4px 18px rgba(13, 127, 159, 0.3); letter-spacing: -1px; position: relative; overflow: hidden; }
    .avatar-circle img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .avatar-actions { display: flex; flex-direction: column; gap: 4px; }
    .btn-upload { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; background: var(--btn-bg); color: #fff; border: none; border-radius: 9px; font-size: 0.84rem; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: background 0.17s; }
    .btn-upload:hover { background: var(--btn-hover); }
    .avatar-hint { font-size: 0.76rem; color: var(--muted-c); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem 1.4rem; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 0.82rem; font-weight: 700; color: var(--label-c); }
    .form-input, .form-textarea { background: var(--input-bg); border: 1.5px solid var(--input-border); border-radius: 10px; padding: 10px 13px; font-family: 'Tajawal', sans-serif; font-size: 0.88rem; color: var(--ink); transition: border-color 0.17s, box-shadow 0.17s; outline: none; width: 100%; }
    .form-input:focus, .form-textarea:focus { border-color: var(--btn-bg); box-shadow: 0 0 0 3px var(--input-focus); }
    .form-input[readonly] { opacity: 0.55; cursor: not-allowed; }
    .contact-field-row { display: flex; align-items: center; gap: 8px; }
    .contact-field-row .form-input { flex: 1; }
    .btn-edit-contact { display: inline-flex; align-items: center; gap: 6px; padding: 10px 14px; background: transparent; color: var(--btn-bg); border: 1.5px solid var(--input-border); border-radius: 10px; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; white-space: nowrap; transition: all 0.17s; }
    .btn-edit-contact:hover { background: var(--btn-bg); color: #fff; border-color: var(--btn-bg); }
    .contact-modal-overlay { position: fixed; inset: 0; background: rgba(10, 25, 35, 0.55); backdrop-filter: blur(6px); z-index: 400; display: none; align-items: center; justify-content: center; padding: 20px; }
    .contact-modal-overlay.open { display: flex; }
    .contact-modal { background: var(--card-bg); border-radius: 20px; width: 100%; max-width: 420px; box-shadow: var(--shadow-lg); overflow: hidden; }
    .contact-modal-head { background: linear-gradient(135deg, var(--btn-bg), #0ea5c9); padding: 18px 22px; display: flex; align-items: center; justify-content: space-between; }
    .contact-modal-head h3 { color: #fff; font-size: 1.02rem; font-weight: 800; margin: 0; }
    .contact-modal-close { background: rgba(255,255,255,0.2); border: none; width: 30px; height: 30px; border-radius: 50%; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .contact-modal-body { padding: 22px; display: flex; flex-direction: column; gap: 14px; }
    .contact-modal-hint { font-size: 0.78rem; color: var(--muted-c); margin-top: -6px; }
    .contact-modal-error { font-size: 0.8rem; color: var(--danger-c); display: none; }
    .contact-modal-error.show { display: block; }
    .contact-modal-footer { padding: 16px 22px; border-top: 1px solid var(--border-c); display: flex; gap: 10px; }
    .btn-contact-cancel { flex: 1; background: #f1f5f9; color: #64748b; border: none; border-radius: 10px; padding: 11px; font-family: 'Tajawal', sans-serif; font-weight: 700; cursor: pointer; font-size: 0.88rem; }
    .btn-contact-save { flex: 1.4; background: var(--btn-bg); color: #fff; border: none; border-radius: 10px; padding: 11px; font-family: 'Tajawal', sans-serif; font-weight: 800; cursor: pointer; font-size: 0.88rem; }
    .btn-contact-save:disabled { opacity: 0.6; cursor: not-allowed; }
    .form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }
    .form-textarea { resize: vertical; min-height: 90px; }
    .toggle-row { display: flex; align-items: center; justify-content: space-between; background: var(--input-bg); border: 1.5px solid var(--input-border); border-radius: 12px; padding: 14px 16px; margin-bottom: 12px; transition: border-color 0.17s; }
    .toggle-row:hover { border-color: rgba(13, 127, 159, 0.3); }
    .toggle-info { display: flex; flex-direction: column; gap: 2px; }
    .toggle-label { font-size: 0.9rem; font-weight: 700; color: var(--ink); }
    .toggle-sub { font-size: 0.78rem; color: var(--muted-c); }
    .toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
    .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
    .toggle-track { position: absolute; inset: 0; border-radius: 100px; background: #d1d5db; cursor: pointer; transition: background 0.22s; }
    .toggle-track::after { content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%; background: #fff; top: 3px; right: 3px; transition: transform 0.22s; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
    .toggle input:checked + .toggle-track { background: var(--btn-bg); }
    .toggle input:checked + .toggle-track::after { transform: translateX(-20px); }
    .accent-row { margin: 1.2rem 0; }
    .accent-label { font-size: 0.82rem; font-weight: 700; color: var(--label-c); margin-bottom: 10px; }
    .accent-swatches { display: flex; gap: 10px; flex-wrap: wrap; }
    .swatch { width: 36px; height: 36px; border-radius: 50%; cursor: pointer; transition: transform 0.17s, box-shadow 0.17s; border: 3px solid transparent; outline: 2px solid transparent; outline-offset: 2px; }
    .swatch:hover { transform: scale(1.12); }
    .swatch.selected { outline: 2.5px solid var(--btn-bg); box-shadow: 0 0 0 3px rgba(13, 127, 159, 0.18); }
    .twofa-box { background: rgba(245, 158, 11, 0.07); border: 1.5px solid rgba(245, 158, 11, 0.28); border-radius: 13px; padding: 16px 18px; margin-top: 1.2rem; }
    .twofa-title { font-size: 0.92rem; font-weight: 800; color: #b45309; margin-bottom: 4px; }
    .twofa-sub { font-size: 0.8rem; color: #92400e; margin-bottom: 12px; }
    .btn-twofa { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; background: #f59e0b; color: #fff; border: none; border-radius: 9px; font-size: 0.84rem; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: background 0.17s; }
    .btn-twofa:hover { background: #d97706; }
    .notif-section-title { font-size: 0.78rem; font-weight: 800; color: var(--muted-c); text-transform: uppercase; letter-spacing: 1.5px; margin: 1.3rem 0 0.6rem; }
    .lang-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1.4rem; }
    .lang-card { display: flex; align-items: center; gap: 12px; padding: 13px 15px; border-radius: 12px; border: 2px solid var(--input-border); cursor: pointer; transition: all 0.18s; background: var(--input-bg); }
    .lang-card:hover { border-color: rgba(13, 127, 159, 0.3); }
    .lang-card.selected { border-color: var(--btn-bg); background: rgba(13, 127, 159, 0.06); box-shadow: 0 0 0 3px rgba(13, 127, 159, 0.1); }
    .lang-flag { font-size: 1.4rem; }
    .lang-info { display: flex; flex-direction: column; gap: 1px; }
    .lang-name { font-size: 0.88rem; font-weight: 700; color: var(--ink); }
    .lang-native { font-size: 0.76rem; color: var(--muted-c); }
    .lang-check { margin-right: auto; width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--input-border); display: flex; align-items: center; justify-content: center; transition: all 0.18s; flex-shrink: 0; }
    .lang-card.selected .lang-check { background: var(--btn-bg); border-color: var(--btn-bg); }
    .lang-card.selected .lang-check::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; }
    .panel-footer { display: flex; justify-content: flex-end; padding-top: 1.4rem; margin-top: 1.4rem; border-top: 1.5px solid var(--border-c); }
    .btn-save { display: inline-flex; align-items: center; gap: 8px; padding: 11px 26px; background: var(--btn-bg); color: #fff; border: none; border-radius: 12px; font-size: 0.92rem; font-weight: 800; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: background 0.17s, transform 0.15s, box-shadow 0.17s; box-shadow: 0 4px 14px rgba(13, 127, 159, 0.3); }
    .btn-save:hover { background: var(--btn-hover); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13, 127, 159, 0.38); }
    .btn-save:active { transform: translateY(0); }
    .toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(80px); background: var(--ink); color: white; padding: 11px 22px; border-radius: 13px; font-size: 0.86rem; font-weight: 600; box-shadow: 0 8px 30px rgba(0,0,0,0.25); z-index: 600; transition: transform 0.35s cubic-bezier(0.16,1,0.3,1); display: flex; align-items: center; gap: 8px; white-space: nowrap; pointer-events: none; }
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
<div class="layout">
@include('layouts.sidebar-user', ['activeNav' => 'settings'])

<div class="main">
  @include('layouts.topbar', [
    'title'    => 'الإعدادات',
    'crumb'    => 'الإعدادات',
    'showNotif' => true,
  ])
  @include('layouts.notif-panel-user')

  <div class="content settings-wrap">

    <div class="settings-header">
      <h1>الإعدادات</h1>
      <p>إدارة حسابك وتخصيص تجربتك في المنصة</p>
    </div>

    <div class="settings-grid">

      {{-- ── Tab sidebar ── --}}
      <nav class="settings-tabs" role="tablist">
        <button class="stab active" onclick="switchTab('profile')" id="tab-profile" aria-selected="true">
          <i class="fa-regular fa-user"></i> الملف الشخصي
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

@php
    $profileName = 'مستخدم';
    $profileRole = 'مستخدم';
    $profileEmail = '';
    $profilePhone = '';
    $profileBio = '';
    $profileAvatar = '';

    if (Auth::check()) {
        $user = Auth::user();
        $profileName = $user->full_name ?? 'مستخدم';
        $profileRole = 'مستخدم';
        $profileEmail = $user->email ?? '';
        $profilePhone = $user->phone ?? '';
        $profileBio = $user->bio ?? '';
        $profileAvatar = $user->avatar_path ? asset('storage/' . $user->avatar_path) : '';
    } elseif (session()->has('association')) {
        $assoc = \App\Models\Association::find(session('association')['id']);
        if ($assoc) {
            $profileName = $assoc->association_name;
            $profileRole = 'جمعية معتمدة';
            $profileEmail = $assoc->email ?? '';
            $profilePhone = $assoc->phone ?? '';
            $profileBio = '';
            $profileAvatar = $assoc->avatar ? asset('storage/' . $assoc->avatar) : '';
        }
    }
@endphp

      <div class="settings-panels">

        {{-- ── Profile ── --}}
        <section class="settings-panel active" id="panel-profile">
          <div class="panel-title">معلومات الملف الشخصي</div>

          <div class="avatar-row">
            <div class="avatar-circle" id="avatar-preview">
              @if($profileAvatar)
                <img src="{{ $profileAvatar }}" alt="avatar">
              @else
                {{ mb_substr($profileName, 0, 1) }}
              @endif
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
                     value="{{ $profileName }}" placeholder="أدخل الاسم الكامل">
            </div>
            <div class="form-group">
              <label class="form-label">البريد الإلكتروني</label>
              <div class="contact-field-row">
                <input type="email" class="form-input" id="email"
                       value="{{ $profileEmail }}" readonly>
                <button type="button" class="btn-edit-contact" onclick="openContactModal('email')">
                  <i class="fa-regular fa-pen-to-square"></i> تعديل
                </button>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">الدور</label>
              <input type="text" class="form-input" value="{{ $profileRole }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">رقم الهاتف</label>
              <div class="contact-field-row">
                <input type="tel" class="form-input" id="phone" value="{{ $profilePhone }}" readonly>
                <button type="button" class="btn-edit-contact" onclick="openContactModal('phone')">
                  <i class="fa-regular fa-pen-to-square"></i> تعديل
                </button>
              </div>
            </div>
            <div class="form-group full">
              <label class="form-label">نبذة تعريفية</label>
              <textarea class="form-textarea" id="bio" placeholder="أخبرنا عن نفسك...">{{ $profileBio }}</textarea>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('profile')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Security ── --}}
        <section class="settings-panel" id="panel-security">
          <div class="panel-title">إعدادات الأمان</div>

          <div class="form-grid">
            <div class="form-group full">
              <label class="form-label">كلمة المرور الحالية</label>
              <div style="position: relative;">
                <input type="password" class="form-input" id="old-pass" placeholder="أدخل كلمة المرور الحالية للتأكيد" autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-pass" onclick="togglePass('old-pass', this)" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af;"></i>
              </div>
            </div>
            <div class="form-group full">
              <label class="form-label">كلمة المرور الجديدة</label>
              <div style="position: relative;">
                <input type="password" class="form-input" id="new-pass" placeholder="أدخل كلمة المرور الجديدة" autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-pass" onclick="togglePass('new-pass', this)" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af;"></i>
              </div>
            </div>
            <div class="form-group full">
              <label class="form-label">تأكيد كلمة المرور الجديدة</label>
              <div style="position: relative;">
                <input type="password" class="form-input" id="confirm-pass" placeholder="أعد إدخال كلمة المرور" autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-pass" onclick="togglePass('confirm-pass', this)" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af;"></i>
              </div>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('security')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Appearance ── --}}
        <section class="settings-panel" id="panel-appearance">
          <div class="panel-title">المظهر</div>

          <div class="toggle-row">
            <div class="toggle-info">
              <span class="toggle-label">الوضع الداكن</span>
              <span class="toggle-sub">التبديل بين الثيم الفاتح والداكن</span>
            </div>
            <label class="toggle"><input type="checkbox" id="dark-mode-toggle"><span class="toggle-track"></span></label>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="saveChanges('appearance')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Language ── --}}
        <section class="settings-panel" id="panel-language">
          <div class="panel-title">اللغة والمنطقة الزمنية</div>

          <div class="accent-label" style="margin-bottom:10px">لغة الواجهة</div>
          <div class="lang-grid">
            <div class="lang-card selected" onclick="selectLang(this)">
              <span class="lang-flag">🇸🇦</span>
              <div class="lang-info"><span class="lang-name">العربية</span><span class="lang-native">Arabic</span></div>
              <div class="lang-check"></div>
            </div>
            <div class="lang-card" onclick="selectLang(this)">
              <span class="lang-flag">🇺🇸</span>
              <div class="lang-info"><span class="lang-name">الإنجليزية</span><span class="lang-native">English</span></div>
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

      </div>{{-- /panels --}}
    </div>{{-- /grid --}}
  </div>{{-- /content --}}
</div>{{-- /main --}}
</div>{{-- /layout --}}

<!-- ── Contact Edit Confirmation Modal ── -->
<div class="contact-modal-overlay" id="contact-modal-overlay">
  <div class="contact-modal" onclick="event.stopPropagation()">
    <div class="contact-modal-head">
      <h3 id="contact-modal-title">تعديل البريد الإلكتروني</h3>
      <button class="contact-modal-close" type="button" onclick="closeContactModal()">✕</button>
    </div>
    <div class="contact-modal-body">
      <div class="form-group">
        <label class="form-label" id="contact-modal-field-label">البريد الإلكتروني الجديد</label>
        <input type="text" class="form-input" id="contact-modal-value" placeholder="">
      </div>
      <div class="form-group">
        <label class="form-label">كلمة المرور الحالية</label>
        <input type="password" class="form-input" id="contact-modal-password" placeholder="أدخل كلمة المرور الحالية للتأكيد" autocomplete="current-password">
        <span class="contact-modal-hint">نطلب كلمة المرور للتأكد من هويتك قبل تعديل بيانات التواصل.</span>
      </div>
      <div class="contact-modal-error" id="contact-modal-error"></div>
    </div>
    <div class="contact-modal-footer">
      <button class="btn-contact-cancel" type="button" onclick="closeContactModal()">إلغاء</button>
      <button class="btn-contact-save" id="contact-modal-save-btn" type="button" onclick="saveContactChange()">
        <i class="fa-regular fa-floppy-disk"></i> حفظ
      </button>
    </div>
  </div>
</div>

<div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

<script src="{{ asset('js/menu.js') }}"></script>
<script>
  function switchTab(name) {
    document.querySelectorAll('.stab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    document.getElementById('panel-' + name).classList.add('active');
  }

  async function previewAvatar(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { alert('الحد الأقصى للحجم هو 2 ميجابايت'); return; }

    // عرض معاينة محلية فورية
    const reader = new FileReader();
    reader.onload = ev => {
      const el = document.getElementById('avatar-preview');
      el.innerHTML = `<img src="${ev.target.result}" alt="avatar">`;
    };
    reader.readAsDataURL(file);

    try {
      const fd = new FormData();
      fd.append('avatar', file);
      const res = await fetch('/api/user/settings/avatar', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: fd,
      });
      const data = await res.json();
      if (!res.ok || data.success === false) {
        showToast(data?.errors?.avatar?.[0] || data?.message || 'تعذر رفع الصورة', 'error');
        return;
      }

      // إضافة timestamp لكسر الـ cache في المتصفح
      const cacheBustedUrl = data.avatar_url + '?t=' + Date.now();

      // تحديث صورة المعاينة في صفحة الإعدادات بالـ URL الحقيقي
      const previewEl = document.getElementById('avatar-preview');
      if (previewEl) previewEl.innerHTML = `<img src="${cacheBustedUrl}" alt="avatar">`;

      // تحديث صورة الـ topbar
      const topbarAv = document.getElementById('tu-av');
      if (topbarAv) topbarAv.innerHTML = `<img src="${cacheBustedUrl}" alt="avatar">`;

      showToast(data.message || 'تم تحديث الصورة', 'success');
    } catch {
      showToast('تعذر الاتصال بالخادم', 'error');
    }
  }

  let _contactField = null; // 'email' or 'phone'

  function openContactModal(field) {
    _contactField = field;
    const isEmail = field === 'email';
    document.getElementById('contact-modal-title').textContent = isEmail ? 'تعديل البريد الإلكتروني' : 'تعديل رقم الهاتف';
    document.getElementById('contact-modal-field-label').textContent = isEmail ? 'البريد الإلكتروني الجديد' : 'رقم الهاتف الجديد';
    const valueInput = document.getElementById('contact-modal-value');
    valueInput.type = isEmail ? 'email' : 'tel';
    valueInput.placeholder = isEmail ? 'example@email.com' : '+966 5X XXX XXXX';
    valueInput.value = document.getElementById(field)?.value || '';
    document.getElementById('contact-modal-password').value = '';
    document.getElementById('contact-modal-error').classList.remove('show');
    document.getElementById('contact-modal-overlay').classList.add('open');
  }

  function closeContactModal() {
    document.getElementById('contact-modal-overlay').classList.remove('open');
    _contactField = null;
  }

  async function saveContactChange() {
    if (!_contactField) return;
    const newValue = document.getElementById('contact-modal-value')?.value.trim() || '';
    const password = document.getElementById('contact-modal-password')?.value || '';
    const errorEl = document.getElementById('contact-modal-error');
    errorEl.classList.remove('show');

    if (!newValue) { errorEl.textContent = 'يرجى إدخال القيمة الجديدة'; errorEl.classList.add('show'); return; }
    if (!password) { errorEl.textContent = 'يرجى إدخال كلمة المرور الحالية'; errorEl.classList.add('show'); return; }

    const payload = { current_password: password };
    payload[_contactField] = newValue;

    const btn = document.getElementById('contact-modal-save-btn');
    btn.disabled = true;
    try {
      const res = await fetch('/api/user/settings/contact', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (!res.ok || data.success === false) {
        errorEl.textContent = data?.errors?.current_password?.[0] || data?.errors?.email?.[0] || data?.errors?.phone?.[0] || data?.message || 'تعذر حفظ التغيير';
        errorEl.classList.add('show');
        return;
      }
      const field = document.getElementById(_contactField);
      if (field && data.user) field.value = data.user[_contactField] ?? newValue;
      showToast(data.message || 'تم تحديث بيانات التواصل بنجاح', 'success');
      closeContactModal();
    } catch {
      errorEl.textContent = 'تعذر الاتصال بالخادم';
      errorEl.classList.add('show');
    } finally {
      btn.disabled = false;
    }
  }

  function selectSwatch(el) {
    document.querySelectorAll('.swatch').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
  }

  function selectLang(el) {
    document.querySelectorAll('.lang-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
  }

  async function saveChanges(section) {
    const msgs = { profile: 'تم حفظ بيانات الملف الشخصي', security: 'تم تحديث كلمة المرور بنجاح', appearance: 'تم حفظ إعدادات المظهر', language: 'تم تحديث اللغة والمنطقة الزمنية' };
    try {
      if (section === 'profile') {
        const res = await fetch('/api/user/settings/profile', {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
          body: JSON.stringify({ full_name: document.getElementById('full-name')?.value || '', bio: document.getElementById('bio')?.value || '' }),
        });
        const data = await res.json();
        if (!res.ok || data.success === false) { showToast(data?.errors?.full_name?.[0] || data?.message || 'تعذر حفظ البيانات', 'error'); return; }
        if (data.user?.full_name) {
          document.getElementById('tu-name') && (document.getElementById('tu-name').textContent = data.user.full_name);
          if (data.user?.avatar_url) { document.getElementById('tu-av') && (document.getElementById('tu-av').innerHTML = `<img src="${data.user.avatar_url}" alt="avatar">`); }
          else { document.getElementById('tu-av') && (document.getElementById('tu-av').textContent = (data.user.full_name || 'م').trim().slice(0, 1)); }
        }
        showToast(data.message || msgs[section], 'success'); return;
      }
      if (section === 'security') {
        const res = await fetch('/api/user/settings/password', {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
          body: JSON.stringify({ current_password: document.getElementById('old-pass')?.value || '', new_password: document.getElementById('new-pass')?.value || '', confirm_password: document.getElementById('confirm-pass')?.value || '' }),
        });
        const data = await res.json();
        if (!res.ok || data.success === false) { showToast(data?.errors?.current_password?.[0] || data?.errors?.new_password?.[0] || data?.message || 'تعذر تحديث كلمة المرور', 'error'); return; }
        document.getElementById('old-pass') && (document.getElementById('old-pass').value = '');
        document.getElementById('new-pass') && (document.getElementById('new-pass').value = '');
        document.getElementById('confirm-pass') && (document.getElementById('confirm-pass').value = '');
        showToast(data.message || msgs[section], 'success'); return;
      }
      showToast(msgs[section] || 'تم حفظ التغييرات', 'success');
    } catch { showToast('تعذر الاتصال بالخادم', 'error'); }
  }

  let _toastTimer;
  function showToast(msg, type = 'success') {
    const el = document.getElementById('toast');
    document.getElementById('t-icon').textContent = type === 'error' ? '⚠️' : '✅';
    document.getElementById('t-msg').textContent = msg;
    el.classList.add('show');
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(() => el.classList.remove('show'), 3200);
  }

  function togglePass(id, iconEl) {
    const el = document.getElementById(id);
    if (el) {
      if (el.type === 'password') {
        el.type = 'text';
        iconEl.classList.remove('fa-eye');
        iconEl.classList.add('fa-eye-slash');
        iconEl.style.color = '#0ea5c9';
      } else {
        el.type = 'password';
        iconEl.classList.remove('fa-eye-slash');
        iconEl.classList.add('fa-eye');
        iconEl.style.color = '#9ca3af';
      }
    }
  }

  const validTabs = ['profile','security','appearance','language'];
  if (validTabs.includes(hash)) switchTab(hash);
</script>
</body>
</html>
