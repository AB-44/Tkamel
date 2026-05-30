/* ════════════════════════════════════════════════════════
   services.js  —  خدمات مبادرون (User Side)
   دمج: تصميم المشروع القديم (foozy) + API المشروع الجديد (tkamel)
════════════════════════════════════════════════════════ */

'use strict';

/* ════════════════ SERVICES DATA ════════════════ */
const SERVICES = [
  {
    id: 'units',
    num: '01',
    title: 'بناء وحدات',
    icon: '🏗️',
    tag: 'تنظيمي',
    tagColor: '#0ea5c9',
    desc: 'تصميم وبناء وحدات تنظيمية متخصصة تُعزز الكفاءة وتُوزّع المهام باحترافية داخل الجمعية.',
    gradient: 'linear-gradient(135deg, #0c4a6e 0%, #0284c7 60%, #0ea5c9 100%)',
    accentColor: '#0ea5c9',
    bgLight: 'rgba(14,165,201,.1)',
    features: ['🏛️ هيكل تنظيمي', '👤 توزيع الأدوار', '📜 لوائح داخلية', '🔧 آليات العمل'],
    duration: '2-4 أسابيع',
    team: 'خبراء تنظيمية',
    fields: [
      { id:'u_name',    label:'اسم الوحدة المقترحة',         type:'text',      placeholder:'مثال: وحدة التطوع المجتمعي',          required:true },
      { id:'u_goal',    label:'الهدف الرئيسي',              type:'textarea',  placeholder:'اشرح الهدف والحاجة التي ستلبّيها...', required:true },
      { id:'u_domain',  label:'التخصص / المجال',            type:'select',    options:['خيري واجتماعي','تعليمي وتدريبي','بيئي وصحي','رياضي وشبابي','تنموي واقتصادي','ديني ودعوي'], required:true },
      { id:'u_size',    label:'العدد المتوقع للأعضاء',      type:'number',    placeholder:'مثال: 15', required:true },
      { id:'u_support', label:'نوع الدعم المطلوب',          type:'checkgroup',options:['دعم مالي','تدريب وتطوير','أدوات وأنظمة','شبكة شراكات','توجيه واستشارة'], required:true },
      { id:'u_notes',   label:'ملاحظات إضافية',             type:'textarea',  placeholder:'أي تفاصيل أخرى...', required:false },
    ],
  },
  {
    id: 'systems',
    num: '02',
    title: 'بناء أنظمة',
    icon: '⚙️',
    tag: 'تقني',
    tagColor: '#6366f1',
    desc: 'تصميم وبناء أنظمة عمل ومنظومات إدارية متكاملة تُحسّن أداء الجمعية وتُرسّخ ثقافة الاحترافية.',
    gradient: 'linear-gradient(135deg, #1e1b4b 0%, #4338ca 60%, #6366f1 100%)',
    accentColor: '#6366f1',
    bgLight: 'rgba(99,102,241,.1)',
    features: ['⚙️ منظومة متكاملة', '📊 مؤشرات الأداء', '🔄 آليات التحديث', '🛡️ حوكمة قوية'],
    duration: '3-6 أسابيع',
    team: 'مهندسو أنظمة',
    fields: [
      { id:'s_name',    label:'اسم النظام المقترح',          type:'text',      placeholder:'مثال: نظام إدارة المتطوعين',           required:true },
      { id:'s_goal',    label:'الهدف الرئيسي من النظام',     type:'textarea',  placeholder:'اشرح المشكلة التي سيحلّها النظام...', required:true },
      { id:'s_scope',   label:'نطاق النظام',                 type:'select',    options:['إدارة المتطوعين','إدارة المشاريع','المالية والميزانية','العلاقات الخارجية','التوثيق والأرشفة','متكامل شامل'], required:true },
      { id:'s_users',   label:'عدد المستخدمين المتوقع',      type:'number',    placeholder:'مثال: 20', required:true },
      { id:'s_support', label:'نوع الدعم المطلوب',           type:'checkgroup',options:['تصميم النظام','توثيق الإجراءات','تدريب الفريق','متابعة التطبيق','دعم فني'], required:true },
      { id:'s_notes',   label:'ملاحظات إضافية',              type:'textarea',  placeholder:'أي تفاصيل أو متطلبات أخرى...', required:false },
    ],
  },
  {
    id: 'training',
    num: '03',
    title: 'تدريب المتطوعين',
    icon: '🎓',
    tag: 'تطوير',
    tagColor: '#ea580c',
    desc: 'برامج تدريبية متخصصة لتطوير كفاءات المتطوعين وصقل مهاراتهم في مجالات التطوع المختلفة.',
    gradient: 'linear-gradient(135deg, #7c2d12 0%, #c2410c 60%, #ea580c 100%)',
    accentColor: '#ea580c',
    bgLight: 'rgba(234,88,12,.1)',
    features: ['📚 مناهج معتمدة', '🏆 مدربون متخصصون', '🎖️ شهادات رسمية', '📈 تقييم مستمر'],
    duration: '1-4 أسابيع',
    team: 'مدربون معتمدون',
    fields: [
      { id:'t_field',   label:'مجال التدريب',               type:'select',  options:['قيادة المجموعات التطوعية','إدارة المشاريع المجتمعية','التواصل والعلاقات العامة','الإسعافات الأولية والصحة','حماية البيئة والاستدامة','التوعية والتثقيف المجتمعي','إدارة الأزمات والطوارئ'], required:true },
      { id:'t_level',   label:'المستوى المستهدف',           type:'radio',   options:['مبتدئ — لا خبرة مسبقة','متوسط — خبرة 1-2 سنة','متقدم — خبرة أكثر من 3 سنوات'], required:true },
      { id:'t_count',   label:'عدد المتدربين المتوقع',      type:'number',  placeholder:'مثال: 30', required:true },
      { id:'t_mode',    label:'نوع التدريب',                type:'radio',   options:['حضوري — في مقر الجمعية','عن بعد — عبر الإنترنت','هجين — حضوري وعن بعد'], required:true },
      { id:'t_duration',label:'مدة البرنامج التدريبي',      type:'select',  options:['يوم واحد (8 ساعات)','يومان','3 أيام','أسبوع كامل','أسبوعان','شهر كامل'], required:true },
      { id:'t_outcomes',label:'مخرجات التدريب المتوقعة',   type:'textarea',placeholder:'ما الذي تتوقع أن يكتسبه المتدربون؟', required:true },
    ],
  },
  {
    id: 'initiatives',
    num: '04',
    title: 'تنسيق المبادرات',
    icon: '🤝',
    tag: 'مجتمعي',
    tagColor: '#059669',
    desc: 'تنسيق وإطلاق مبادرات مجتمعية مشتركة بين عدة جمعيات لتحقيق أثر أوسع وأعمق.',
    gradient: 'linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%)',
    accentColor: '#059669',
    bgLight: 'rgba(5,150,105,.1)',
    features: ['📋 خطة تنفيذية', '🔗 ربط الشركاء', '🗓️ إطار زمني', '📊 تقييم الأثر'],
    duration: '4-8 أسابيع',
    team: 'منسقو مبادرات',
    fields: [
      { id:'i_name',   label:'اسم المبادرة',               type:'text',      placeholder:'مثال: مبادرة وطن نظيف', required:true },
      { id:'i_goal',   label:'هدف المبادرة',               type:'textarea',  placeholder:'صِف الهدف والتأثير المنشود...', required:true },
      { id:'i_cat',    label:'تصنيف المبادرة',             type:'select',    options:['خيرية واجتماعية','بيئية وصحية','تعليمية وثقافية','اقتصادية وتنموية','دينية ودعوية'], required:true },
      { id:'i_target', label:'الفئة المستهدفة',            type:'text',      placeholder:'مثال: أسر محتاجة في منطقة الرياض', required:true },
      { id:'i_count',  label:'عدد المستفيدين المتوقع',     type:'number',    placeholder:'مثال: 500', required:true },
      { id:'i_region', label:'المنطقة الجغرافية',          type:'text',      placeholder:'مثال: منطقة القصيم', required:true },
      { id:'i_needs',  label:'الاحتياجات والموارد',        type:'checkgroup',options:['تمويل','متطوعون','أدوات وتجهيزات','تغطية إعلامية','دعم لوجستي'], required:true },
    ],
  },
  {
    id: 'consulting',
    num: '05',
    title: 'استشارات متخصصة',
    icon: '💬',
    tag: 'استشاري',
    tagColor: '#0d9488',
    desc: 'احصل على استشارة متخصصة من خبراء مبادرون في مجالات التطوير المؤسسي وإدارة الجمعيات.',
    gradient: 'linear-gradient(135deg, #134e4a 0%, #0f766e 60%, #0d9488 100%)',
    accentColor: '#0d9488',
    bgLight: 'rgba(13,148,136,.1)',
    features: ['🧠 خبراء متخصصون', '📌 حلول مخصصة', '⏱️ استجابة سريعة', '🔒 سرية تامة'],
    duration: '1-3 أيام',
    team: 'مستشارون معتمدون',
    fields: [
      { id:'c_area',    label:'مجال الاستشارة',               type:'select',  options:['الحوكمة والإدارة المؤسسية','التخطيط الاستراتيجي','إدارة المشاريع والبرامج','الشراكات والتمويل','التسويق والتواصل المجتمعي','الموارد البشرية والتطوع','الامتثال القانوني والتنظيمي'], required:true },
      { id:'c_mode',    label:'طريقة الاستشارة المفضلة',      type:'radio',   options:['حضوري — في مقر الجمعية','عن بعد — عبر الإنترنت','مختلطة'], required:true },
      { id:'c_problem', label:'وصف التحدي أو الإشكالية',     type:'textarea',placeholder:'اشرح بالتفصيل التحدي الذي تواجهه...', required:true },
      { id:'c_expect',  label:'ما الذي تتوقعه من الاستشارة', type:'textarea',placeholder:'ما النتائج أو المخرجات التي تريدها؟', required:true },
      { id:'c_when',    label:'متى تحتاج الاستشارة؟',         type:'select',  options:['خلال 3 أيام (عاجل)','هذا الأسبوع','خلال أسبوعين','خلال شهر','مرن وفق التوفر'], required:true },
      { id:'c_count',   label:'عدد المشاركين من الجمعية',     type:'number',  placeholder:'مثال: 5', required:true },
    ],
  },
  {
    id: 'other',
    num: '06',
    title: 'طلب آخر',
    icon: '💡',
    tag: 'مفتوح',
    tagColor: '#7c3aed',
    desc: 'هل لديك فكرة أو حاجة لا تندرج تحت الخدمات السابقة؟ أرسل طلبك وسيتواصل معك فريق مبادرون.',
    gradient: 'linear-gradient(135deg, #4c1d95 0%, #6d28d9 60%, #7c3aed 100%)',
    accentColor: '#7c3aed',
    bgLight: 'rgba(124,58,237,.1)',
    features: ['✨ فكرة مخصصة', '🆓 استشارة أولية', '🚀 حل مبتكر', '🤝 متابعة شاملة'],
    duration: 'حسب الطلب',
    team: 'فريق مبادرون',
    fields: [
      { id:'o_title',    label:'عنوان الطلب',         type:'text',    placeholder:'مثال: دعم في التخطيط الاستراتيجي', required:true },
      { id:'o_type',     label:'تصنيف الطلب',         type:'select',  options:['استشارة مؤسسية','دعم إداري','دعم مالي وتمويلي','تطوير برامج','توسعة النشاط','شراكات استراتيجية','أخرى'], required:true },
      { id:'o_desc',     label:'وصف مفصّل للطلب',    type:'textarea',placeholder:'اشرح طلبك بالتفصيل...', required:true },
      { id:'o_goal',     label:'الهدف من هذا الطلب', type:'textarea',placeholder:'ما النتيجة التي تأمل تحقيقها؟', required:true },
      { id:'o_priority', label:'أولوية الطلب',        type:'radio',   options:['عاجل — خلال أسبوعين','متوسطة — خلال شهر','عادية — لا يوجد ضغط زمني'], required:true },
    ],
  },
];

/* ════════════════ STATUS CONFIG ════════════════ */
const STATUS_CONFIG = {
    pending:  { label: '⏳ قيد المراجعة',   cls: 'st-pend',  stripe: '#f59e0b' },
    review:   { label: '🔄 جارٍ المعالجة',  cls: 'st-rev',   stripe: '#0ea5c9' },
    approved: { label: '✅ مقبول',            cls: 'st-app',   stripe: '#0d9488' },
    rejected: { label: '❌ مرفوض',            cls: 'st-rej',   stripe: '#ef5350' },
};

/* ════════════════ STATE ════════════════ */
let serviceRequests  = [];
let currentService   = null;
let currentDetailReq = null;

/* ════════════════ BOOTSTRAP ════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    renderServicesGrid();
    fetchServiceRequests();
});

/* ════════════════ CSRF ════════════════ */
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/* ════════════════ API: FETCH ════════════════ */
async function fetchServiceRequests() {
    try {
        const res = await fetch('/user/service-requests', {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        serviceRequests = await res.json();
        renderMyReqs();
        updateBadge();
        renderServicesGrid();
    } catch (err) {
        console.error('fetchServiceRequests:', err);
        showToast('❌', 'تعذّر تحميل الطلبات');
    }
}

/* ════════════════ RENDER SERVICES GRID ════════════════ */
function renderServicesGrid() {
    document.getElementById('services-grid').innerHTML = SERVICES.map((s, i) => {
        const myReqs   = serviceRequests.filter(r => (r.service_type || r.type) === s.id);
        const pending  = myReqs.filter(r => r.status === 'pending' || r.status === 'review').length;
        const approved = myReqs.filter(r => r.status === 'approved').length;

        const statusBadges = [
            pending  ? '<span class="cs-chip cs-pend">⏳ ' + pending + ' قيد المراجعة</span>' : '',
            approved ? '<span class="cs-chip cs-app">✅ ' + approved + ' مقبول</span>' : ''
        ].join('');

        const reqBanner = myReqs.length
            ? '<div class="card-req-banner" style="background:' + s.bgLight + ';border-color:' + s.accentColor + '20;color:' + s.accentColor + '">'
              + '<i class="fa-solid fa-paper-plane fa-xs"></i> لديك <strong>' + myReqs.length + '</strong> طلب مقدَّم على هذه الخدمة</div>'
            : '';

        const featuresHtml = s.features.map(f => '<span class="feat-pill">' + f + '</span>').join('');

        return '<div class="svc-card" style="--ac:' + s.accentColor + ';--bg:' + s.bgLight + ';animation-delay:' + (i * 0.07) + 's">'
            + '<div class="card-header" style="background:' + s.gradient + '">'
            + '<div class="ch-grid"></div><div class="ch-glow"></div>'
            + '<div class="ch-left"><div class="ch-num">' + s.num + '</div>'
            + '<div class="ch-meta"><div class="ch-tag">' + s.tag + '</div><div>' + s.duration + '</div></div></div>'
            + '<div class="ch-icon-wrap"><div class="ch-icon">' + s.icon + '</div></div>'
            + (statusBadges ? '<div class="ch-status">' + statusBadges + '</div>' : '')
            + '</div>'
            + '<div class="card-body">'
            + '<div class="cb-row1"><h3 class="card-title">' + s.title + '</h3></div>'
            + '<p class="card-desc">' + s.desc + '</p>'
            + reqBanner
            + '<div class="card-feats">' + featuresHtml + '</div>'
            + '<div class="card-footer">'
            + '<div class="cf-team"><i class="fa-solid fa-users" style="color:' + s.accentColor + '"></i><span>' + s.team + '</span></div>'
            + '<button class="cf-btn" style="background:' + s.gradient + '" onclick="openService(\'' + s.id + '\')">'
            + '<i class="fa-solid fa-paper-plane"></i> تقديم طلب</button>'
            + '</div></div></div>';
    }).join('');
}

/* ════════════════ OPEN SERVICE MODAL ════════════════ */
function openService(svcId) {
    const svc = SERVICES.find(s => s.id === svcId);
    if (!svc) return;
    currentService = svc;

    document.getElementById('sm-header-bg').style.background = svc.gradient;
    document.getElementById('sm-icon-wrap').textContent      = svc.icon;
    document.getElementById('sm-title').textContent          = svc.title;
    document.getElementById('sm-sub').textContent            = svc.desc;
    document.getElementById('sm-prog-fill').style.background = svc.accentColor;
    document.getElementById('sm-prog-fill').style.width      = '0%';

    const container = document.getElementById('sm-fields');

    let html = '<div class="sm-assoc-row"><div class="fg">'
        + '<label>اسم الجمعية <span class="req">*</span></label>'
        + '<div class="fi-wrap"><i class="fa-solid fa-building fi-ico"></i>'
        + '<input id="srv_assoc" value="" required readonly style="background:#f0f9ff;cursor:default">'
        + '</div></div></div>'
        + '<hr class="sm-divider">'
        + '<div class="sm-fields-title">تفاصيل الطلب</div>';

    svc.fields.forEach((f, idx) => {
        const reqMark = f.required ? '<span class="req">*</span>' : '';
        let inner = '';
        if (f.type === 'text' || f.type === 'number') {
            inner = '<div class="fi-wrap"><i class="fa-solid fa-pen fi-ico"></i>'
                + '<input id="' + f.id + '" type="' + f.type + '" placeholder="' + (f.placeholder || '') + '" ' + (f.required ? 'required' : '') + '></div>';
        } else if (f.type === 'textarea') {
            inner = '<textarea id="' + f.id + '" placeholder="' + (f.placeholder || '') + '" ' + (f.required ? 'required' : '') + '></textarea>';
        } else if (f.type === 'select') {
            inner = '<div class="fi-wrap"><i class="fa-solid fa-list fi-ico"></i>'
                + '<select id="' + f.id + '" ' + (f.required ? 'required' : '') + '><option value="">— اختر —</option>'
                + f.options.map(o => '<option value="' + o + '">' + o + '</option>').join('')
                + '</select></div>';
        } else if (f.type === 'radio') {
            inner = '<div class="radio-group">'
                + f.options.map(o => '<label class="radio-lbl"><input type="radio" name="' + f.id + '" value="' + o + '">'
                    + '<span class="radio-mark" style="--rc:' + svc.accentColor + '"></span><span>' + o + '</span></label>').join('')
                + '</div>';
        } else if (f.type === 'checkgroup') {
            inner = '<div class="check-group">'
                + f.options.map(o => '<label class="check-lbl"><input type="checkbox" name="' + f.id + '" value="' + o + '">'
                    + '<span class="check-mark" style="--cc:' + svc.accentColor + '"></span><span>' + o + '</span></label>').join('')
                + '</div>';
        }
        html += '<div class="fg" style="animation-delay:' + (idx * .04) + 's"><label>' + f.label + ' ' + reqMark + '</label>' + inner + '</div>';
    });

    container.innerHTML = html;

    // Try to fill association name from page data attribute or DOM
    const assocInput = document.getElementById('srv_assoc');
    const userNameEl = document.querySelector('.tu-name');
    if (assocInput && userNameEl) assocInput.value = userNameEl.textContent.trim();

    setTimeout(() => {
        container.querySelectorAll('input:not([type=radio]):not([type=checkbox]),textarea,select')
            .forEach(inp => inp.addEventListener('input', updateProgress));
        container.querySelectorAll('input[type=radio],input[type=checkbox]')
            .forEach(inp => inp.addEventListener('change', updateProgress));
    }, 100);

    openOv('ov-service');
}

/* ════════════════ PROGRESS BAR ════════════════ */
function updateProgress() {
    if (!currentService) return;
    const total  = currentService.fields.filter(f => f.required).length;
    let filled   = 0;
    currentService.fields.filter(f => f.required).forEach(f => {
        if (f.type === 'radio')           { if (document.querySelector('input[name="' + f.id + '"]:checked')) filled++; }
        else if (f.type === 'checkgroup') { if (document.querySelector('input[name="' + f.id + '"]:checked')) filled++; }
        else { const el = document.getElementById(f.id); if (el && el.value.trim()) filled++; }
    });
    document.getElementById('sm-prog-fill').style.width = total ? Math.round(filled / total * 100) + '%' : '0%';
}

/* ════════════════ COLLECT FORM ANSWERS ════════════════ */
function collectAnswers() {
    if (!currentService) return null;
    const svc     = currentService;
    let valid     = true;
    const answers = {};

    svc.fields.forEach(f => {
        if (f.type === 'radio') {
            const c = document.querySelector('input[name="' + f.id + '"]:checked');
            answers[f.id] = c ? c.value : '';
            if (f.required && !answers[f.id]) valid = false;
        } else if (f.type === 'checkgroup') {
            const c = [...document.querySelectorAll('input[name="' + f.id + '"]:checked')].map(x => x.value);
            answers[f.id] = c.join('، ');
            if (f.required && !c.length) valid = false;
        } else {
            const el = document.getElementById(f.id);
            answers[f.id] = el ? el.value.trim() : '';
            if (f.required && !answers[f.id]) { if (el) el.classList.add('invalid'); valid = false; }
        }
    });
    return valid ? answers : null;
}

/* ════════════════ BUILD TITLE + DETAILS ════════════════ */
function buildTitleAndDetails(svc, answers) {
    const firstTextField = svc.fields.find(f => f.type === 'text' && answers[f.id]);
    const title = firstTextField
        ? svc.title + ' — ' + answers[firstTextField.id]
        : svc.title;

    const lines = svc.fields.map(f => {
        const val = answers[f.id];
        return val ? f.label + ': ' + val : null;
    }).filter(Boolean);

    return { title, details: lines.join('\n') };
}

/* ════════════════ SUBMIT SERVICE ════════════════ */
async function submitService() {
    if (!currentService) return;
    const svc     = currentService;
    const answers = collectAnswers();
    if (!answers) { showToast('⚠️', 'يرجى تعبئة جميع الحقول المطلوبة'); return; }

    const { title, details } = buildTitleAndDetails(svc, answers);

    const btn    = document.getElementById('sm-submit');
    const oldTxt = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الإرسال...';
    btn.disabled  = true;

    try {
        const res = await fetch('/user/service-requests', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: JSON.stringify({
                service_type:   svc.id,
                title:          title,
                details:        details,
                preferred_date: '',
                budget:         0,
            }),
        });

        const data = await res.json();
        if (data.success) {
            closeOv('ov-service');
            showSuccessModal(data.message || 'تم إرسال طلبك بنجاح! سيتم مراجعته من قِبل الفريق المختص');
            fetchServiceRequests();
        } else {
            showToast('❌', data.message || 'حدث خطأ');
        }
    } catch (_) {
        showToast('❌', 'حدث خطأ في الاتصال');
    } finally {
        btn.innerHTML = oldTxt;
        btn.disabled  = false;
    }
}

/* ════════════════ RENDER MY REQUESTS ════════════════ */
function renderMyReqs() {
    const el = document.getElementById('my-reqs-list');
    if (!serviceRequests.length) {
        el.innerHTML = '<div class="empty-state">'
            + '<div class="es-icon">📭</div>'
            + '<h3>لم تقدّم أي طلبات بعد</h3>'
            + '<p>اختر إحدى الخدمات واملأ النموذج لإرسال طلبك</p>'
            + '<button class="es-btn" onclick="switchTab(\'tab-services\', document.querySelector(\'.tabx\'))">'
            + '<i class="fa-solid fa-grid-2"></i> استعرض الخدمات</button></div>';
        return;
    }

    el.innerHTML = serviceRequests.map((r, i) => {
        const svcType = r.service_type || r.type;
        const svc     = SERVICES.find(s => s.id === svcType);
        const cfg     = STATUS_CONFIG[r.status] || STATUS_CONFIG.pending;
        const icon    = svc ? svc.icon : '📄';
        const gradient = svc ? svc.gradient : '#334155';
        const accent   = svc ? svc.accentColor : '#64748b';
        const svcTitle = svc ? svc.title : svcType;
        const dateStr  = r.date || r.preferred_date || '—';

        return '<div class="req-card" style="animation-delay:' + (i * .05) + 's" onclick="openDetailModal(' + r.id + ')">'
            + '<div class="rc-stripe" style="background:' + cfg.stripe + '"></div>'
            + '<div class="rc-icon" style="background:' + gradient + '">' + icon + '</div>'
            + '<div class="rc-body">'
            + '<div class="rc-title">' + escHtml(r.title) + '</div>'
            + '<div class="rc-preview">' + escHtml(svcTitle) + '</div>'
            + '<div class="rc-meta">'
            + '<span><i class="fa-regular fa-calendar fa-xs"></i> ' + dateStr + '</span>'
            + '<span style="color:' + accent + '"><i class="fa-solid fa-circle fa-xs" style="font-size:6px"></i> ' + escHtml(svcTitle) + '</span>'
            + '</div></div>'
            + '<div class="rc-right">'
            + '<span class="st-badge ' + cfg.cls + '">' + cfg.label + '</span>'
            + '<div class="rc-arrow"><i class="fa-solid fa-chevron-left" style="font-size:.65rem"></i></div>'
            + '</div></div>';
    }).join('');
}

/* ════════════════ DETAIL MODAL ════════════════ */
function openDetailModal(id) {
    currentDetailReq = serviceRequests.find(r => r.id === id);
    if (!currentDetailReq) return;
    const r   = currentDetailReq;
    const svc = SERVICES.find(s => s.id === (r.service_type || r.type));
    const cfg = STATUS_CONFIG[r.status] || STATUS_CONFIG.pending;

    document.getElementById('det-hd-title').textContent = (svc ? svc.icon + ' ' : '') + escHtml(r.title);
    document.getElementById('det-hd-sub').textContent   = 'طلب مقدَّم في ' + (r.date || r.preferred_date || '—');
    document.getElementById('det-hd').style.borderBottom = '3px solid ' + (svc ? svc.accentColor : '#0ea5c9');

    const canEdit    = r.status === 'pending';
    const accentColor = svc ? svc.accentColor : '#0ea5c9';

    const statusNote = r.status === 'pending'
        ? 'طلبك قيد المراجعة من قِبَل فريق مبادرون. ستصلك إشعار بالقرار.'
        : r.status === 'approved'
        ? 'تهانينا! ✨ تم قبول طلبك. سيتواصل معك الفريق لاستكمال التفاصيل.'
        : r.status === 'review'
        ? 'طلبك تحت المعالجة الآن من قِبَل الفريق.'
        : 'نأسف، لم يتم قبول الطلب. يمكنك تقديم طلب جديد مع تعديل البيانات.';

    document.getElementById('det-body').innerHTML =
        '<div class="det-status-row">'
        + '<span class="st-badge ' + cfg.cls + '">' + cfg.label + '</span>'
        + '<span class="det-date"><i class="fa-regular fa-calendar fa-xs"></i> ' + (r.date || r.preferred_date || '—') + '</span>'
        + '</div>'
        + '<div class="det-section-title">تفاصيل الطلب</div>'
        + '<div class="det-ans-row"><span class="dar-lbl">نوع الخدمة</span><span class="dar-val">' + escHtml(svc ? svc.title : (r.service_type || r.type || '—')) + '</span></div>'
        + '<div class="det-ans-row"><span class="dar-lbl">العنوان</span><span class="dar-val">' + escHtml(r.title) + '</span></div>'
        + '<div class="det-ans-row"><span class="dar-lbl">التفاصيل</span><span class="dar-val" style="white-space:pre-wrap">' + escHtml(r.details || '—') + '</span></div>'
        + (r.budget ? '<div class="det-ans-row"><span class="dar-lbl">الميزانية</span><span class="dar-val">' + escHtml(String(r.budget)) + ' ر.س</span></div>' : '')
        + '<div class="det-note" style="border-color:' + accentColor + '20">'
        + '<i class="fa-solid fa-circle-info" style="color:' + accentColor + ';margin-top:2px;flex-shrink:0"></i>'
        + '<span>' + statusNote + '</span></div>';

    document.getElementById('det-footer').innerHTML = canEdit
        ? '<div style="display:flex;gap:10px;flex-wrap:wrap">'
          + '<button class="sm-btn-cancel" onclick="closeOv(\'ov-detail\')">إغلاق</button>'
          + '<button class="sm-btn-delete" onclick="confirmDeleteReq()"><i class="fa-solid fa-trash"></i> حذف</button>'
          + '</div>'
        : '<button class="sm-btn-cancel" onclick="closeOv(\'ov-detail\')">إغلاق</button>';

    openOv('ov-detail');
}

/* ════════════════ DELETE ════════════════ */
function confirmDeleteReq() {
    document.getElementById('delete-confirm-modal').classList.add('open');
}
function closeDeleteConfirm() {
    document.getElementById('delete-confirm-modal').classList.remove('open');
}
async function executeDelete() {
    if (!currentDetailReq) return;
    try {
        const res = await fetch('/user/service-requests/' + currentDetailReq.id, {
            method:  'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        });
        const data = await res.json();
        closeDeleteConfirm();
        closeOv('ov-detail');
        if (data.success) {
            showToast('🗑️', data.message || 'تم حذف الطلب');
            fetchServiceRequests();
        } else {
            showToast('❌', data.message || 'حدث خطأ');
        }
    } catch (_) {
        closeDeleteConfirm();
        showToast('❌', 'حدث خطأ في الاتصال');
    }
}

/* ════════════════ TABS ════════════════ */
function switchTab(tabId, btn) {
    document.querySelectorAll('.tabx').forEach(t => t.classList.remove('on'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active-pane'));
    if (btn) btn.classList.add('on');
    else document.querySelector('[data-tab="' + tabId + '"]')?.classList.add('on');
    document.getElementById(tabId).classList.add('active-pane');
}

/* ════════════════ BADGE ════════════════ */
function updateBadge() {
    const el = document.getElementById('tn-mine');
    if (el) el.textContent = serviceRequests.length;
}

/* ════════════════ OVERLAY HELPERS ════════════════ */
function openOv(id)     { document.getElementById(id).classList.add('open'); }
function closeOv(id)    { document.getElementById(id).classList.remove('open'); }
function bgClose(e, id) { if (e.target === document.getElementById(id)) closeOv(id); }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeOv('ov-service'); closeOv('ov-detail'); }
});

/* ════════════════ SUCCESS MODAL ════════════════ */
function showSuccessModal(msg) {
    const el = document.getElementById('success-modal');
    const t  = document.getElementById('success-title');
    if (t)  t.textContent = msg;
    if (el) el.classList.add('open');
}
function closeSuccessModal() {
    document.getElementById('success-modal')?.classList.remove('open');
}

/* ════════════════ TOAST ════════════════ */
let _toastTimer;
function showToast(icon, msg) {
    const el = document.getElementById('toast');
    if (!el) return;
    document.getElementById('t-icon').textContent = icon;
    document.getElementById('t-msg').textContent  = msg;
    el.classList.add('show');
    clearTimeout(_toastTimer);
    _toastTimer = setTimeout(() => el.classList.remove('show'), 3400);
}

/* ════════════════ UTIL ════════════════ */
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
