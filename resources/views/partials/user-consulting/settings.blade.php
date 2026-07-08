{{--
  partials/user-consulting/settings.blade.php
  Settings section for the user SPA.
  Functions are prefixed with `uset` to avoid clashing with other
  section scripts (services.js / orders.js already define global
  switchTab/showToast with different signatures).
--}}
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
    } elseif (session('association')) {
        $assoc = session('association');
        $profileName = $assoc['name'] ?? 'الجمعية';
        $profileRole = 'جمعية';
        $profileEmail = $assoc['email'] ?? '';
        $profilePhone = $assoc['phone'] ?? '';
    }
@endphp

<style>
  .settings-wrap { padding: 0; }
  .settings-header { margin-bottom: 1.8rem; }
  .settings-header h1 { font-size: 1.55rem; font-weight: 900; color: var(--ink); letter-spacing: -0.4px; }
  .settings-header p { color: var(--muted-c, #6b7280); font-size: 0.88rem; margin-top: 3px; }
  .settings-grid { display: grid; grid-template-columns: 220px 1fr; gap: 1.5rem; align-items: start; }
  .settings-tabs { background: var(--card-bg, #fff); border-radius: 16px; padding: 10px; box-shadow: var(--shadow, 0 4px 24px rgba(13,127,159,.1)); border: 1px solid var(--border-c, rgba(13,127,159,.13)); position: sticky; top: 90px; }
  .stab { display: flex; align-items: center; gap: 10px; padding: 11px 13px; border-radius: 10px; font-size: 0.88rem; font-weight: 600; color: var(--muted-c, #6b7280); cursor: pointer; transition: all 0.18s ease; margin-bottom: 2px; border: none; background: transparent; width: 100%; text-align: right; }
  .stab i { width: 18px; text-align: center; font-size: 0.95rem; flex-shrink: 0; }
  .stab:hover { background: rgba(13,127,159,.07); color: var(--btn-bg, #0c6080); }
  .stab.active { background: var(--btn-bg, #0c6080); color: #fff; box-shadow: 0 4px 14px rgba(13, 127, 159, 0.3); }
  .settings-panel { background: var(--card-bg, #fff); border-radius: 20px; box-shadow: var(--shadow-lg, 0 8px 40px rgba(13,127,159,.14)); border: 1px solid var(--border-c, rgba(13,127,159,.13)); padding: 2rem 2.2rem; display: none; }
  .settings-panel.active { display: block; }
  .panel-title { font-size: 1.15rem; font-weight: 800; color: var(--ink); margin-bottom: 1.6rem; padding-bottom: 0.9rem; border-bottom: 1.5px solid var(--border-c, rgba(13,127,159,.13)); }
  .avatar-row { display: flex; align-items: center; gap: 1.3rem; margin-bottom: 1.8rem; }
  .avatar-circle { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--btn-bg, #0c6080) 0%, #0ea5c9 100%); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 900; color: #fff; flex-shrink: 0; box-shadow: 0 4px 18px rgba(13, 127, 159, 0.3); letter-spacing: -1px; position: relative; overflow: hidden; }
  .avatar-circle img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
  .avatar-actions { display: flex; flex-direction: column; gap: 4px; }
  .btn-upload { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; background: var(--btn-bg, #0c6080); color: #fff; border: none; border-radius: 9px; font-size: 0.84rem; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: background 0.17s; }
  .btn-upload:hover { background: var(--btn-hover, #0a6a87); }
  .avatar-hint { font-size: 0.76rem; color: var(--muted-c, #6b7280); }
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem 1.4rem; }
  .form-group { display: flex; flex-direction: column; gap: 6px; }
  .form-group.full { grid-column: 1 / -1; }
  .form-label { font-size: 0.82rem; font-weight: 700; color: var(--label-c, #374151); }
  .form-input, .form-textarea { background: var(--input-bg, #f4fafc); border: 1.5px solid var(--input-border, rgba(13,127,159,.18)); border-radius: 10px; padding: 10px 13px; font-family: 'Tajawal', sans-serif; font-size: 0.88rem; color: var(--ink); transition: border-color 0.17s, box-shadow 0.17s; outline: none; width: 100%; }
  .form-input:focus, .form-textarea:focus { border-color: var(--btn-bg, #0c6080); box-shadow: 0 0 0 3px rgba(13,127,159,.25); }
  .form-input[readonly] { opacity: 0.55; cursor: not-allowed; }
  .contact-field-row { display: flex; align-items: center; gap: 8px; }
  .contact-field-row .form-input { flex: 1; }
  .btn-edit-contact { display: inline-flex; align-items: center; gap: 6px; padding: 10px 14px; background: transparent; color: var(--btn-bg, #0c6080); border: 1.5px solid var(--input-border, rgba(13,127,159,.18)); border-radius: 10px; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; white-space: nowrap; transition: all 0.17s; }
  .btn-edit-contact:hover { background: var(--btn-bg, #0c6080); color: #fff; border-color: var(--btn-bg, #0c6080); }
  .contact-modal-overlay { position: fixed; inset: 0; background: rgba(10, 25, 35, 0.55); backdrop-filter: blur(6px); z-index: 400; display: none; align-items: center; justify-content: center; padding: 20px; }
  .contact-modal-overlay.open { display: flex; }
  .contact-modal { background: var(--card-bg, #fff); border-radius: 20px; width: 100%; max-width: 420px; box-shadow: var(--shadow-lg, 0 8px 40px rgba(13,127,159,.14)); overflow: hidden; }
  .contact-modal-head { background: linear-gradient(135deg, var(--btn-bg, #0c6080), #0ea5c9); padding: 18px 22px; display: flex; align-items: center; justify-content: space-between; }
  .contact-modal-head h3 { color: #fff; font-size: 1.02rem; font-weight: 800; margin: 0; }
  .contact-modal-close { background: rgba(255,255,255,0.2); border: none; width: 30px; height: 30px; border-radius: 50%; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; }
  .contact-modal-body { padding: 22px; display: flex; flex-direction: column; gap: 14px; }
  .contact-modal-hint { font-size: 0.78rem; color: var(--muted-c, #6b7280); margin-top: -6px; }
  .contact-modal-error { font-size: 0.8rem; color: var(--danger-c, #dc2626); display: none; }
  .contact-modal-error.show { display: block; }
  .contact-modal-footer { padding: 16px 22px; border-top: 1px solid var(--border-c, rgba(13,127,159,.13)); display: flex; gap: 10px; }
  .btn-contact-cancel { flex: 1; background: #f1f5f9; color: #64748b; border: none; border-radius: 10px; padding: 11px; font-family: 'Tajawal', sans-serif; font-weight: 700; cursor: pointer; font-size: 0.88rem; }
  .btn-contact-save { flex: 1.4; background: var(--btn-bg, #0c6080); color: #fff; border: none; border-radius: 10px; padding: 11px; font-family: 'Tajawal', sans-serif; font-weight: 800; cursor: pointer; font-size: 0.88rem; }
  .btn-contact-save:disabled { opacity: 0.6; cursor: not-allowed; }
  .form-input::placeholder, .form-textarea::placeholder { color: #9ca3af; }
  .form-textarea { resize: vertical; min-height: 90px; }
  .toggle-row { display: flex; align-items: center; justify-content: space-between; background: var(--input-bg, #f4fafc); border: 1.5px solid var(--input-border, rgba(13,127,159,.18)); border-radius: 12px; padding: 14px 16px; margin-bottom: 12px; transition: border-color 0.17s; }
  .toggle-row:hover { border-color: rgba(13, 127, 159, 0.3); }
  .toggle-info { display: flex; flex-direction: column; gap: 2px; }
  .toggle-label { font-size: 0.9rem; font-weight: 700; color: var(--ink); }
  .toggle-sub { font-size: 0.78rem; color: var(--muted-c, #6b7280); }
  .toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
  .toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
  .toggle-track { position: absolute; inset: 0; border-radius: 100px; background: #d1d5db; cursor: pointer; transition: background 0.22s; }
  .toggle-track::after { content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%; background: #fff; top: 3px; right: 3px; transition: transform 0.22s; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
  .toggle input:checked + .toggle-track { background: var(--btn-bg, #0c6080); }
  .toggle input:checked + .toggle-track::after { transform: translateX(-20px); }
  .toggle-row.toggle-locked { opacity: 0.5; }
  .toggle-row.toggle-locked .toggle-track { cursor: not-allowed; }
  .toggle-row.toggle-locked .toggle { cursor: not-allowed; }
  .toggle-sub-locked { font-size: 0.72rem; color: #c0392b; font-weight: 600; margin-top: 2px; }
  .accent-row { margin: 1.2rem 0; }
  .accent-label { font-size: 0.82rem; font-weight: 700; color: var(--label-c, #374151); margin-bottom: 10px; }
  .accent-swatches { display: flex; gap: 10px; flex-wrap: wrap; }
  .swatch { width: 36px; height: 36px; border-radius: 50%; cursor: pointer; transition: transform 0.17s, box-shadow 0.17s; border: 3px solid transparent; outline: 2px solid transparent; outline-offset: 2px; }
  .swatch:hover { transform: scale(1.12); }
  .swatch.selected { outline: 2.5px solid var(--btn-bg, #0c6080); box-shadow: 0 0 0 3px rgba(13, 127, 159, 0.18); }
  .twofa-box { background: rgba(245, 158, 11, 0.07); border: 1.5px solid rgba(245, 158, 11, 0.28); border-radius: 13px; padding: 16px 18px; margin-top: 1.2rem; }
  .twofa-title { font-size: 0.92rem; font-weight: 800; color: #b45309; margin-bottom: 4px; }
  .twofa-sub { font-size: 0.8rem; color: #92400e; margin-bottom: 12px; }
  .btn-twofa { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; background: #f59e0b; color: #fff; border: none; border-radius: 9px; font-size: 0.84rem; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: background 0.17s; }
  .btn-twofa:hover { background: #d97706; }
  .lang-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1.4rem; }
  .lang-card { display: flex; align-items: center; gap: 12px; padding: 13px 15px; border-radius: 12px; border: 2px solid var(--input-border, rgba(13,127,159,.18)); cursor: pointer; transition: all 0.18s; background: var(--input-bg, #f4fafc); }
  .lang-card:hover { border-color: rgba(13, 127, 159, 0.3); }
  .lang-card.selected { border-color: var(--btn-bg, #0c6080); background: rgba(13, 127, 159, 0.06); box-shadow: 0 0 0 3px rgba(13, 127, 159, 0.1); }
  .lang-card.lang-locked { opacity: 0.5; cursor: not-allowed; pointer-events: none; }
  .lang-flag { font-size: 1.4rem; }
  .lang-info { display: flex; flex-direction: column; gap: 1px; }
  .lang-name { font-size: 0.88rem; font-weight: 700; color: var(--ink); }
  .lang-native { font-size: 0.76rem; color: var(--muted-c, #6b7280); }
  .lang-check { margin-right: auto; width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--input-border, rgba(13,127,159,.18)); display: flex; align-items: center; justify-content: center; transition: all 0.18s; flex-shrink: 0; }
  .lang-card.selected .lang-check { background: var(--btn-bg, #0c6080); border-color: var(--btn-bg, #0c6080); }
  .lang-card.selected .lang-check::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; }
  .panel-footer { display: flex; justify-content: flex-end; padding-top: 1.4rem; margin-top: 1.4rem; border-top: 1.5px solid var(--border-c, rgba(13,127,159,.13)); }
  .btn-save { display: inline-flex; align-items: center; gap: 8px; padding: 11px 26px; background: var(--btn-bg, #0c6080); color: #fff; border: none; border-radius: 12px; font-size: 0.92rem; font-weight: 800; cursor: pointer; font-family: 'Tajawal', sans-serif; transition: background 0.17s, transform 0.15s, box-shadow 0.17s; box-shadow: 0 4px 14px rgba(13, 127, 159, 0.3); }
  .btn-save:hover { background: var(--btn-hover, #0a6a87); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13, 127, 159, 0.38); }
  .btn-save:active { transform: translateY(0); }
  .uset-toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(80px); background: var(--ink); color: white; padding: 11px 22px; border-radius: 13px; font-size: 0.86rem; font-weight: 600; box-shadow: 0 8px 30px rgba(0,0,0,0.25); z-index: 600; transition: transform 0.35s cubic-bezier(0.16,1,0.3,1); display: flex; align-items: center; gap: 8px; white-space: nowrap; pointer-events: none; }
  .uset-toast.show { transform: translateX(-50%) translateY(0); }
  @media (max-width: 900px) {
    .settings-grid { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
    .lang-grid { grid-template-columns: 1fr; }
    .settings-tabs { display: flex; flex-wrap: wrap; gap: 4px; position: static; }
    .stab { width: auto; flex: 1; }
  }
</style>

<div class="view" id="view-settings">
  <div class="settings-wrap">

    <div class="settings-header">
      <h1>الإعدادات</h1>
      <p>إدارة حسابك وتخصيص تجربتك في المنصة</p>
    </div>

    <div class="settings-grid">

      {{-- ── Tab sidebar ── --}}
      <nav class="settings-tabs" role="tablist">
        <button class="stab active" onclick="usetSwitchTab('profile')" id="utab-profile" aria-selected="true">
          <i class="fa-regular fa-user"></i> الملف الشخصي
        </button>
        <button class="stab" onclick="usetSwitchTab('security')" id="utab-security">
          <i class="fa-solid fa-lock" style="font-size:.8rem"></i> الأمان
        </button>
        <button class="stab" onclick="usetSwitchTab('appearance')" id="utab-appearance">
          <i class="fa-solid fa-palette" style="font-size:.8rem"></i> المظهر
        </button>
        <button class="stab" onclick="usetSwitchTab('language')" id="utab-language">
          <i class="fa-solid fa-globe" style="font-size:.8rem"></i> اللغة
        </button>
      </nav>

      <div class="settings-panels">

        {{-- ── Profile ── --}}
        <section class="settings-panel active" id="upanel-profile">
          <div class="panel-title">معلومات الملف الشخصي</div>

          <div class="avatar-row">
            <div class="avatar-circle" id="uset-avatar-preview">
              @if($profileAvatar)
                <img src="{{ $profileAvatar }}" alt="avatar">
              @else
                {{ mb_substr($profileName, 0, 1) }}
              @endif
            </div>
            <div class="avatar-actions">
              <button class="btn-upload" onclick="document.getElementById('uset-avatar-input').click()">
                <i class="fa-solid fa-camera"></i> تغيير الصورة
              </button>
              <span class="avatar-hint">JPG أو PNG أو GIF. الحد الأقصى 2 ميجابايت.</span>
              <input type="file" id="uset-avatar-input" accept="image/*" style="display:none" onchange="usetPreviewAvatar(event)">
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">الاسم الكامل</label>
              <input type="text" class="form-input" id="uset-full-name"
                     value="{{ $profileName }}" placeholder="أدخل الاسم الكامل">
            </div>
            <div class="form-group">
              <label class="form-label">البريد الإلكتروني</label>
              <div class="contact-field-row">
                <input type="email" class="form-input" id="uset-email"
                       value="{{ $profileEmail }}" readonly>
                <button type="button" class="btn-edit-contact" onclick="usetOpenContactModal('email')">
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
                <input type="tel" class="form-input" id="uset-phone" value="{{ $profilePhone }}" readonly>
                <button type="button" class="btn-edit-contact" onclick="usetOpenContactModal('phone')">
                  <i class="fa-regular fa-pen-to-square"></i> تعديل
                </button>
              </div>
            </div>
            <div class="form-group full">
              <label class="form-label">نبذة تعريفية</label>
              <textarea class="form-textarea" id="uset-bio" placeholder="أخبرنا عن نفسك...">{{ $profileBio }}</textarea>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="usetSaveChanges('profile')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Security ── --}}
        <section class="settings-panel" id="upanel-security">
          <div class="panel-title">إعدادات الأمان</div>

          <div class="form-grid">
            <div class="form-group full">
              <label class="form-label">كلمة المرور الحالية</label>
              <div style="position: relative;">
                <input type="password" class="form-input" id="uset-old-pass" placeholder="أدخل كلمة المرور الحالية للتأكيد" autocomplete="new-password">
                <i class="fa-regular fa-eye uset-toggle-pass" onclick="usetTogglePass('uset-old-pass', this)" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af;"></i>
              </div>
            </div>
            <div class="form-group full">
              <label class="form-label">كلمة المرور الجديدة</label>
              <div style="position: relative;">
                <input type="password" class="form-input" id="uset-new-pass" placeholder="أدخل كلمة المرور الجديدة" autocomplete="new-password">
                <i class="fa-regular fa-eye uset-toggle-pass" onclick="usetTogglePass('uset-new-pass', this)" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af;"></i>
              </div>
            </div>
            <div class="form-group full">
              <label class="form-label">تأكيد كلمة المرور الجديدة</label>
              <div style="position: relative;">
                <input type="password" class="form-input" id="uset-confirm-pass" placeholder="أعد إدخال كلمة المرور" autocomplete="new-password">
                <i class="fa-regular fa-eye uset-toggle-pass" onclick="usetTogglePass('uset-confirm-pass', this)" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af;"></i>
              </div>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="usetSaveChanges('security')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Appearance ── --}}
        <section class="settings-panel" id="upanel-appearance">
          <div class="panel-title">المظهر</div>

          {{--
            ملاحظة: زر الوضع الداكن مقفل مؤقتًا (معطل) بناءً على طلب صريح.
            لتفعيله لاحقًا: احذف class="toggle-row toggle-locked" وخليها class="toggle-row" فقط،
            واحذف كلمة disabled من الـ <input>، وأزل السطر الخاص بـ toggle-sub-locked إذا رغبت.
          --}}
          <div class="toggle-row toggle-locked">
            <div class="toggle-info">
              <span class="toggle-label">الوضع الداكن</span>
              <span class="toggle-sub">التبديل بين الثيم الفاتح والداكن</span>
              <span class="toggle-sub-locked">هذه الميزة غير متاحة حاليًا</span>
            </div>
            <label class="toggle"><input type="checkbox" id="uset-dark-mode-toggle" disabled><span class="toggle-track"></span></label>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="usetSaveChanges('appearance')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Language ── --}}
        <section class="settings-panel" id="upanel-language">
          <div class="panel-title">اللغة</div>

          <div class="accent-label" style="margin-bottom:10px">لغة الواجهة</div>
          <div class="lang-grid">
            <div class="lang-card selected" onclick="usetSelectLang(this)">
              <span class="lang-flag">🇸🇦</span>
              <div class="lang-info"><span class="lang-name">العربية</span><span class="lang-native">Arabic</span></div>
              <div class="lang-check"></div>
            </div>
            {{--
              ملاحظة: خيار الإنجليزية مقفل مؤقتًا (معطل) بناءً على طلب صريح.
              لتفعيله لاحقًا: أعد onclick="usetSelectLang(this)" وأزل class="lang-locked".
            --}}
            <div class="lang-card lang-locked">
              <span class="lang-flag">🇺🇸</span>
              <div class="lang-info"><span class="lang-name">الإنجليزية</span><span class="lang-native">English</span></div>
              <div class="lang-check"></div>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="usetSaveChanges('language')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

      </div>{{-- /panels --}}
    </div>{{-- /grid --}}
  </div>{{-- /settings-wrap --}}
</div><!-- /view-settings -->

<!-- ── Contact Edit Confirmation Modal ── -->
<div class="contact-modal-overlay" id="uset-contact-modal-overlay">
  <div class="contact-modal" onclick="event.stopPropagation()">
    <div class="contact-modal-head">
      <h3 id="uset-contact-modal-title">تعديل البريد الإلكتروني</h3>
      <button class="contact-modal-close" type="button" onclick="usetCloseContactModal()">✕</button>
    </div>
    <div class="contact-modal-body">
      <div class="form-group">
        <label class="form-label" id="uset-contact-modal-field-label">البريد الإلكتروني الجديد</label>
        <input type="text" class="form-input" id="uset-contact-modal-value" placeholder="">
      </div>
      <div class="form-group">
        <label class="form-label">كلمة المرور الحالية</label>
        <input type="password" class="form-input" id="uset-contact-modal-password" placeholder="أدخل كلمة المرور الحالية للتأكيد" autocomplete="current-password">
        <span class="contact-modal-hint">نطلب كلمة المرور للتأكد من هويتك قبل تعديل بيانات التواصل.</span>
      </div>
      <div class="contact-modal-error" id="uset-contact-modal-error"></div>
    </div>
    <div class="contact-modal-footer">
      <button class="btn-contact-cancel" type="button" onclick="usetCloseContactModal()">إلغاء</button>
      <button class="btn-contact-save" id="uset-contact-modal-save-btn" type="button" onclick="usetSaveContactChange()">
        <i class="fa-regular fa-floppy-disk"></i> حفظ
      </button>
    </div>
  </div>
</div>

<div class="uset-toast" id="uset-toast"><span id="uset-t-icon"></span><span id="uset-t-msg"></span></div>
