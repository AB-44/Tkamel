/* ══════════════════════════════════════════════════
   user-settings-spa.js — Settings section (SPA)
   Namespaced with `uset` prefix to avoid clashing with
   generic global names (switchTab/showToast/togglePass)
   already defined by other section scripts (services.js, orders.js).
   Called by spa-nav.js: settingsUserInit() on first visit.
══════════════════════════════════════════════════ */

function settingsUserInit() {
  const hash = window.location.hash.replace('#', '');
  const validTabs = ['profile', 'security', 'appearance', 'language'];
  if (validTabs.includes(hash)) usetSwitchTab(hash);
}
function settingsUserRefresh() { /* static form, nothing to refetch */ }

function usetGetCsrf() { return document.querySelector('meta[name="csrf-token"]')?.content || ''; }

function usetSwitchTab(name) {
  document.querySelectorAll('#view-settings .stab').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('#view-settings .settings-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('utab-' + name)?.classList.add('active');
  document.getElementById('upanel-' + name)?.classList.add('active');
}

async function usetPreviewAvatar(e) {
  const file = e.target.files[0];
  if (!file) return;
  if (file.size > 2 * 1024 * 1024) { alert('الحد الأقصى للحجم هو 2 ميجابايت'); return; }

  const reader = new FileReader();
  reader.onload = ev => {
    const el = document.getElementById('uset-avatar-preview');
    el.innerHTML = `<img src="${ev.target.result}" alt="avatar">`;
  };
  reader.readAsDataURL(file);

  try {
    const fd = new FormData();
    fd.append('avatar', file);
    const res = await fetch('/api/user/settings/avatar', {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': usetGetCsrf() },
      body: fd,
    });
    const data = await res.json();
    if (!res.ok || data.success === false) {
      usetShowToast(data?.errors?.avatar?.[0] || data?.message || 'تعذر رفع الصورة', 'error');
      return;
    }

    const cacheBustedUrl = data.avatar_url + '?t=' + Date.now();

    const previewEl = document.getElementById('uset-avatar-preview');
    if (previewEl) previewEl.innerHTML = `<img src="${cacheBustedUrl}" alt="avatar">`;

    const topbarAv = document.getElementById('tu-av');
    if (topbarAv) topbarAv.innerHTML = `<img src="${cacheBustedUrl}" alt="avatar">`;

    usetShowToast(data.message || 'تم تحديث الصورة', 'success');
  } catch {
    usetShowToast('تعذر الاتصال بالخادم', 'error');
  }
}

let _usetContactField = null; // 'email' or 'phone'

function usetOpenContactModal(field) {
  _usetContactField = field;
  const isEmail = field === 'email';
  document.getElementById('uset-contact-modal-title').textContent = isEmail ? 'تعديل البريد الإلكتروني' : 'تعديل رقم الهاتف';
  document.getElementById('uset-contact-modal-field-label').textContent = isEmail ? 'البريد الإلكتروني الجديد' : 'رقم الهاتف الجديد';
  const valueInput = document.getElementById('uset-contact-modal-value');
  valueInput.type = isEmail ? 'email' : 'tel';
  valueInput.placeholder = isEmail ? 'example@email.com' : '+966 5X XXX XXXX';
  valueInput.value = document.getElementById(isEmail ? 'uset-email' : 'uset-phone')?.value || '';
  document.getElementById('uset-contact-modal-password').value = '';
  document.getElementById('uset-contact-modal-error').classList.remove('show');
  document.getElementById('uset-contact-modal-overlay').classList.add('open');
}

function usetCloseContactModal() {
  document.getElementById('uset-contact-modal-overlay').classList.remove('open');
  _usetContactField = null;
}

async function usetSaveContactChange() {
  if (!_usetContactField) return;
  const newValue = document.getElementById('uset-contact-modal-value')?.value.trim() || '';
  const password = document.getElementById('uset-contact-modal-password')?.value || '';
  const errorEl = document.getElementById('uset-contact-modal-error');
  errorEl.classList.remove('show');

  if (!newValue) { errorEl.textContent = 'يرجى إدخال القيمة الجديدة'; errorEl.classList.add('show'); return; }
  if (!password) { errorEl.textContent = 'يرجى إدخال كلمة المرور الحالية'; errorEl.classList.add('show'); return; }

  const payload = { current_password: password };
  payload[_usetContactField] = newValue;

  const btn = document.getElementById('uset-contact-modal-save-btn');
  btn.disabled = true;
  try {
    const res = await fetch('/api/user/settings/contact', {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': usetGetCsrf() },
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (!res.ok || data.success === false) {
      errorEl.textContent = data?.errors?.current_password?.[0] || data?.errors?.email?.[0] || data?.errors?.phone?.[0] || data?.message || 'تعذر حفظ التغيير';
      errorEl.classList.add('show');
      return;
    }
    const fieldId = _usetContactField === 'email' ? 'uset-email' : 'uset-phone';
    const field = document.getElementById(fieldId);
    if (field && data.user) field.value = data.user[_usetContactField] ?? newValue;
    usetShowToast(data.message || 'تم تحديث بيانات التواصل بنجاح', 'success');
    usetCloseContactModal();
  } catch {
    errorEl.textContent = 'تعذر الاتصال بالخادم';
    errorEl.classList.add('show');
  } finally {
    btn.disabled = false;
  }
}

function usetSelectLang(el) {
  document.querySelectorAll('#view-settings .lang-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
}

async function usetSaveChanges(section) {
  const msgs = { profile: 'تم حفظ بيانات الملف الشخصي', security: 'تم تحديث كلمة المرور بنجاح', appearance: 'تم حفظ إعدادات المظهر', language: 'تم تحديث اللغة والمنطقة الزمنية' };
  try {
    if (section === 'profile') {
      const res = await fetch('/api/user/settings/profile', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': usetGetCsrf() },
        body: JSON.stringify({ full_name: document.getElementById('uset-full-name')?.value || '', bio: document.getElementById('uset-bio')?.value || '' }),
      });
      const data = await res.json();
      if (!res.ok || data.success === false) { usetShowToast(data?.errors?.full_name?.[0] || data?.message || 'تعذر حفظ البيانات', 'error'); return; }
      if (data.user?.full_name) {
        document.getElementById('tu-name') && (document.getElementById('tu-name').textContent = data.user.full_name);
        if (data.user?.avatar_url) { document.getElementById('tu-av') && (document.getElementById('tu-av').innerHTML = `<img src="${data.user.avatar_url}" alt="avatar">`); }
        else { document.getElementById('tu-av') && (document.getElementById('tu-av').textContent = (data.user.full_name || 'م').trim().slice(0, 1)); }
      }
      usetShowToast(data.message || msgs[section], 'success'); return;
    }
    if (section === 'security') {
      const res = await fetch('/api/user/settings/password', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': usetGetCsrf() },
        body: JSON.stringify({ current_password: document.getElementById('uset-old-pass')?.value || '', new_password: document.getElementById('uset-new-pass')?.value || '', confirm_password: document.getElementById('uset-confirm-pass')?.value || '' }),
      });
      const data = await res.json();
      if (!res.ok || data.success === false) { usetShowToast(data?.errors?.current_password?.[0] || data?.errors?.new_password?.[0] || data?.message || 'تعذر تحديث كلمة المرور', 'error'); return; }
      document.getElementById('uset-old-pass') && (document.getElementById('uset-old-pass').value = '');
      document.getElementById('uset-new-pass') && (document.getElementById('uset-new-pass').value = '');
      document.getElementById('uset-confirm-pass') && (document.getElementById('uset-confirm-pass').value = '');
      usetShowToast(data.message || msgs[section], 'success'); return;
    }
    usetShowToast(msgs[section] || 'تم حفظ التغييرات', 'success');
  } catch { usetShowToast('تعذر الاتصال بالخادم', 'error'); }
}

let _usetToastTimer;
function usetShowToast(msg, type = 'success') {
  const el = document.getElementById('uset-toast');
  if (!el) return;
  document.getElementById('uset-t-icon').textContent = type === 'error' ? '⚠️' : '✅';
  document.getElementById('uset-t-msg').textContent = msg;
  el.classList.add('show');
  clearTimeout(_usetToastTimer);
  _usetToastTimer = setTimeout(() => el.classList.remove('show'), 3200);
}

function usetTogglePass(id, iconEl) {
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
