        <div class="view" id="view-settings">
  <div class="content settings-wrap">

    <div class="settings-header">
      <h1>الإعدادات</h1>
      <p>إدارة حسابك وتخصيص تجربتك في المنصة</p>
    </div>

    <div class="settings-grid">

      {{-- ── Tab sidebar ── --}}
      <nav class="settings-tabs" role="tablist">
        <button class="stab active" onclick="settingsSwitchTab('profile')" id="tab-profile" aria-selected="true">
          <i class="fa-regular fa-user"></i> الملف الشخصي
        </button>
        <button class="stab" onclick="settingsSwitchTab('security')" id="tab-security">
          <i class="fa-solid fa-lock" style="font-size:.8rem"></i> الأمان
        </button>
        <button class="stab" onclick="settingsSwitchTab('appearance')" id="tab-appearance">
          <i class="fa-solid fa-palette" style="font-size:.8rem"></i> المظهر
        </button>
        <button class="stab" onclick="settingsSwitchTab('language')" id="tab-language">
          <i class="fa-solid fa-globe" style="font-size:.8rem"></i> اللغة
        </button>
      </nav>

      {{-- ── Panels ── --}}
      <div class="settings-panels">

        {{-- ── Profile ── --}}
        <section class="settings-panel active" id="panel-profile">
          <div class="panel-title">معلومات الملف الشخصي</div>

          <div class="avatar-row">
            <div class="avatar-circle" id="avatar-preview">
              @if(!empty(Auth::user()->avatar_path))
                <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="avatar">
              @else
                {{ mb_substr(Auth::user()->full_name ?? 'م', 0, 1) }}
              @endif
            </div>
            <div class="avatar-actions">
              <button class="btn-upload" onclick="document.getElementById('avatar-input').click()">
                <i class="fa-solid fa-camera"></i> تغيير الصورة
              </button>
              <span class="avatar-hint">JPG أو PNG أو GIF. الحد الأقصى 2 ميجابايت.</span>
              <input type="file" id="avatar-input" accept="image/*" style="display:none" onchange="settingsPreviewAvatar(event)">
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">الاسم الكامل</label>
              <input type="text" class="form-input" id="full-name"
                     value="{{ Auth::user()->full_name ?? 'مدير النظام' }}" placeholder="أدخل الاسم الكامل">
            </div>
            <div class="form-group">
              <label class="form-label">البريد الإلكتروني</label>
              <input type="email" class="form-input" id="email"
                     value="{{ Auth::user()->email ?? 'admin@tkamel.sa' }}" placeholder="أدخل البريد الإلكتروني">
            </div>
            <div class="form-group">
              <label class="form-label">الدور</label>
              <input type="text" class="form-input" value="مدير النظام" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">رقم الهاتف</label>
              <input type="tel" class="form-input" id="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="+966 5X XXX XXXX">
            </div>
            <div class="form-group full">
              <label class="form-label">نبذة تعريفية</label>
              <textarea class="form-textarea" id="bio" placeholder="أخبرنا عن نفسك...">{{ Auth::user()->bio ?? '' }}</textarea>
            </div>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="settingsSaveChanges('profile')">
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
            <button class="btn-save" onclick="settingsSaveChanges('security')">
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
            <label class="toggle">
              <input type="checkbox" id="dark-mode-toggle">
              <span class="toggle-track"></span>
            </label>
          </div>

          <div class="panel-footer">
            <button class="btn-save" onclick="settingsSaveChanges('appearance')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

        {{-- ── Language ── --}}
        <section class="settings-panel" id="panel-language">
          <div class="panel-title">اللغة والمنطقة الزمنية</div>

          <div class="accent-label" style="margin-bottom:10px">لغة الواجهة</div>
          <div class="lang-grid">
            <div class="lang-card selected" onclick="settingsSelectLang(this)">
              <span class="lang-flag">🇸🇦</span>
              <div class="lang-info">
                <span class="lang-name">العربية</span>
                <span class="lang-native">Arabic</span>
              </div>
              <div class="lang-check"></div>
            </div>
            <div class="lang-card" onclick="settingsSelectLang(this)">
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
            <button class="btn-save" onclick="settingsSaveChanges('language')">
              <i class="fa-regular fa-floppy-disk"></i> حفظ التغييرات
            </button>
          </div>
        </section>

      </div>{{-- /panels --}}
    </div>{{-- /grid --}}
  </div>{{-- /content --}}

        </div>{{-- /view-settings --}}
