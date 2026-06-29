  /* ── Tab switching ── */
  function settingsSwitchTab(name) {
    document.querySelectorAll('.stab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    document.getElementById('panel-' + name).classList.add('active');
  }

  /* ── Avatar preview ── */
  async function settingsPreviewAvatar(e) {
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
        settingsShowToast(msg, 'error');
        return;
      }
      // Update topbar avatar with the uploaded image
      const av = document.getElementById('tu-av');
      if (av && data.avatar_url) av.innerHTML = `<img src="${data.avatar_url}" alt="avatar">`;
      settingsShowToast(data.message || 'تم تحديث الصورة', 'success');
    } catch (err) {
      settingsShowToast('تعذر الاتصال بالخادم', 'error');
    }
  }

  /* ── Accent swatch selection ── */
  function settingsSelectSwatch(el) {
    document.querySelectorAll('.swatch').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
  }

  /* ── Language selection ── */
  function settingsSelectLang(el) {
    document.querySelectorAll('.lang-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
  }

  /* ── Save with toast ── */
  async function settingsSaveChanges(section) {
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
          settingsShowToast(msg, 'error');
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
        settingsShowToast(data.message || msgs[section], 'success');
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
          settingsShowToast(msg, 'error');
          return;
        }
        document.getElementById('new-pass') && (document.getElementById('new-pass').value = '');
        document.getElementById('confirm-pass') && (document.getElementById('confirm-pass').value = '');
        settingsShowToast(data.message || msgs[section], 'success');
        return;
      }

      // other tabs currently UI-only
      settingsShowToast(msgs[section] || 'تم حفظ التغييرات', 'success');
    } catch (e) {
      settingsShowToast('تعذر الاتصال بالخادم', 'error');
    }
  }

  let _settingsToastTimer;
  function settingsShowToast(msg, type = 'success') {
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

  /* ── Show/hide password fields ── */
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

  /* ── Called by spa-nav.js when the settings section is first shown ── */
  function settingsInit() {
    const hash = location.hash.replace('#', '');
    const validTabs = ['profile','security','appearance','language'];
    if (validTabs.includes(hash)) settingsSwitchTab(hash);
  }
