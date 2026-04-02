<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تكامل — فرص التطوع</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/consulting.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/meeting-scoped.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/orders-scoped.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/jp-scoped.css')); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ── Read-only notice banner ── */
    .readonly-notice {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(245, 158, 11, 0.08);
      border: 1px solid rgba(245, 158, 11, 0.25);
      border-radius: 10px;
      padding: 10px 16px;
      font-size: 0.82rem;
      color: #92400e;
      font-weight: 600;
      margin-bottom: 18px;
    }
    .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(245, 158, 11, 0.12);
      color: #b45309;
      border: 1px solid rgba(245, 158, 11, 0.3);
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 0.72rem;
      font-weight: 700;
    }
  </style>
<style>#nb-reqs:empty{display:none!important}</style>
</head>

<body>
  <div class="layout">

    <!-- ══ SIDEBAR ══ -->
    <?php echo $__env->make('layouts.sidebar-user', ['activeNav' => 'volunteer'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <!-- ══ MAIN ══ -->
    <div class="main">

      <!-- TOPBAR -->
      <?php echo $__env->make('layouts.topbar', ['title' => 'فرص التطوع', 'userName' => Auth::user()->full_name, 'userAv' => mb_substr(Auth::user()->full_name, 0, 1), 'showNotif' => false, 'userRole' => '<span style="display:inline-flex;align-items:center;gap:4px;background:rgba(245,158,11,.12);color:#b45309;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700"><i class="fa-solid fa-eye" style="font-size:.6rem"></i> عرض فقط</span>'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


      <!-- CONTENT -->
      <div class="content">

        
        <div class="view active" id="view-admin">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">تصفح التصنيفات والفرص المتاحة — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
            </div>
            
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)"><div class="s-icon" style="background:rgba(42,184,208,0.1)">🌟</div><div><span class="s-num" id="st-total">0</span><span class="s-lbl">إجمالي الفرص</span></div></div>
            <div class="stat-card" style="--sc:var(--green)"><div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div><div><span class="s-num" id="st-open">0</span><span class="s-lbl">فرص مفتوحة</span></div></div>
            <div class="stat-card" style="--sc:var(--gold)"><div class="s-icon" style="background:rgba(245,158,11,0.1)">⏳</div><div><span class="s-num" id="st-pending">0</span><span class="s-lbl">طلبات معلقة</span></div></div>
            <div class="stat-card" style="--sc:var(--purple)"><div class="s-icon" style="background:rgba(123,78,166,0.1)">🏷️</div><div><span class="s-num" id="st-cats">6</span><span class="s-lbl">التصنيفات</span></div></div>
          </div>

          <div class="readonly-notice">
            <i class="fa-solid fa-circle-info"></i>
            أنت في وضع العرض فقط. لا يمكنك إضافة أو تعديل أو حذف الفرص.
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div style="font-size:1rem;font-weight:800;color:var(--ink)">التصنيفات</div>
            <div style="font-size:0.82rem;color:var(--muted)">اختر تصنيفاً لعرض فرص التطوع الخاصة به</div>
          </div>
          <div class="cats-grid" id="cats-grid"></div>
        </div>

        
        <div class="view" id="view-admin-opps">
          <div class="opp-view-header">
            <button class="back-btn" onclick="backToCategories()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M19 12H5M12 5l7 7-7 7"/></svg>
              التصنيفات
            </button>
            <div class="cat-title-badge">
              <span class="ctb-icon" id="ao-cat-icon"></span>
              <span class="ctb-name" id="ao-cat-name"></span>
            </div>
          </div>
          <div class="page-hd">
            <div>
              <div class="ph-title" id="ao-title">الفرص</div>
              <div class="ph-sub" id="ao-sub"></div>
            </div>
            
          </div>
          <div class="toolbar">
            <div class="sw">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input class="s-inp" id="admin-opp-search" type="text" placeholder="ابحث عن فرصة..." oninput="renderAdminOpps()">
            </div>
          </div>
          <div class="opps-grid" id="admin-opps-grid"></div>
        </div>

        
        <div class="view" id="view-admin-reqs">
          <div class="page-hd">
            <div>
              <div class="ph-title">طلبات التقديم</div>
              <div class="ph-sub">عرض طلبات الجمعيات — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
            </div>
            <button class="back-btn" onclick="showAdminMain()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M19 12H5M12 5l7 7-7 7"/></svg>
              فرص التطوع
            </button>
          </div>

          <div class="req-msg-card">
            <div class="req-msg-icon">👁️</div>
            <div>
              <div class="req-msg-title">وضع العرض</div>
              <div class="req-msg-sub">يمكنك عرض طلبات الجمعيات فقط. صلاحية القبول أو الرفض متاحة للمدير فقط.</div>
            </div>
          </div>

          <div class="req-tabs">
            <button class="req-tab active" id="rtab-pending" onclick="filterReqs('pending')">⏳ معلقة <span class="rc" id="rc-pending">0</span></button>
            <button class="req-tab" id="rtab-approved" onclick="filterReqs('approved')">✅ مقبولة <span class="rc" id="rc-approved">0</span></button>
            <button class="req-tab" id="rtab-rejected" onclick="filterReqs('rejected')">❌ مرفوضة <span class="rc" id="rc-rejected">0</span></button>
          </div>
          <div class="req-list" id="req-list"></div>
        </div>

        
        <div class="view" id="view-assoc">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">تصفح الفرص المتاحة — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
            </div>
          </div>
          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)"><div class="s-icon" style="background:rgba(42,184,208,0.1)">🌟</div><div><span class="s-num" id="ast-total">0</span><span class="s-lbl">فرص متاحة</span></div></div>
            <div class="stat-card" style="--sc:var(--gold)"><div class="s-icon" style="background:rgba(245,158,11,0.1)">📝</div><div><span class="s-num" id="ast-applied">0</span><span class="s-lbl">طلباتي</span></div></div>
            <div class="stat-card" style="--sc:var(--green)"><div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div><div><span class="s-num" id="ast-approved">0</span><span class="s-lbl">مقبولة</span></div></div>
            <div class="stat-card" style="--sc:var(--purple)"><div class="s-icon" style="background:rgba(123,78,166,0.1)">🏷️</div><div><span class="s-num" id="ast-cats">6</span><span class="s-lbl">التصنيفات</span></div></div>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div style="font-size:1rem;font-weight:800;color:var(--ink)">تصفح التصنيفات</div>
          </div>
          <div class="cats-grid" id="assoc-cats-grid"></div>
        </div>

        <div class="view" id="view-assoc-opps">
          <div class="opp-view-header">
            <button class="back-btn" onclick="backToAssocCats()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M19 12H5M12 5l7 7-7 7"/></svg>
              التصنيفات
            </button>
            <div class="cat-title-badge"><span class="ctb-icon" id="ao-cat-icon2"></span><span class="ctb-name" id="ao-cat-name2"></span></div>
          </div>
          <div class="page-hd">
            <div>
              <div class="ph-title" id="ao-title2">الفرص</div>
              <div class="ph-sub">عرض الفرص المتاحة فقط — لا يمكن التقديم في هذا الوضع</div>
            </div>
          </div>
          <div class="toolbar">
            <div class="sw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input class="s-inp" id="assoc-opp-search" type="text" placeholder="ابحث عن فرصة..." oninput="renderAssocOpps()"></div>
          </div>
          <div class="opps-grid" id="assoc-opps-grid"></div>
        </div>

        

        <div class="view" id="view-units">
          <div class="svc-hero"><span class="svc-hero-icon">🏗️</span><div class="svc-hero-title">بناء وحدات</div><div class="svc-hero-sub">نساعد الجمعيات في تأسيس وحداتها التنظيمية وتطوير هياكلها الإدارية لتحقيق الكفاءة والفاعلية.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🎯</span><div class="svc-card-title">تحليل الاحتياجات</div><div class="svc-card-desc">دراسة وضع الجمعية الحالي وتحديد الوحدات التنظيمية المطلوبة لتحقيق أهدافها الاستراتيجية.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">📐</span><div class="svc-card-title">تصميم الهيكل التنظيمي</div><div class="svc-card-desc">بناء هياكل تنظيمية واضحة تحدد الأدوار والمسؤوليات وخطوط الإبلاغ بشكل فعّال.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">⚙️</span><div class="svc-card-title">تفعيل الوحدات</div><div class="svc-card-desc">دعم الجمعية في إطلاق وحداتها الجديدة وتزويد فرقها بالأدوات والموارد اللازمة للعمل.</div></div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">📊</span><div class="svc-card-title">قياس الأداء</div><div class="svc-card-desc">وضع مؤشرات أداء واضحة لمتابعة تطور الوحدات وقياس مدى تحقيقها لأهدافها المرسومة.</div></div>
          </div>
        </div>

        <div class="view" id="view-systems">
          <div class="svc-hero" style="background:linear-gradient(135deg,#0d3d49,#1a6b7c,#3a72b8)"><span class="svc-hero-icon">⚙️</span><div class="svc-hero-title">بناء أنظمة</div><div class="svc-hero-sub">تطوير الأنظمة والإجراءات التشغيلية التي تضمن استدامة عمل الجمعيات وتحسين جودة خدماتها.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon">📋</span><div class="svc-card-title">أنظمة الحوكمة</div><div class="svc-card-desc">بناء أنظمة إدارية واضحة تضمن الشفافية والمساءلة وحسن اتخاذ القرار.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🔄</span><div class="svc-card-title">العمليات التشغيلية</div><div class="svc-card-desc">توثيق وتطوير الإجراءات التشغيلية لضمان الكفاءة وتقليل الأخطاء وتوحيد العمل.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">💾</span><div class="svc-card-title">أنظمة المعلومات</div><div class="svc-card-desc">تصميم أنظمة لإدارة البيانات والمعلومات وضمان سهولة الوصول واتخاذ القرار المبني على البيانات.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">🛡️</span><div class="svc-card-title">أنظمة الجودة</div><div class="svc-card-desc">تطبيق معايير الجودة ومراجعتها بشكل دوري لضمان الارتقاء المستمر بمستوى الخدمات.</div></div>
          </div>
        </div>

        <div class="view" id="view-initiatives">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1d3d1a,#2eaa78,#34d399)"><span class="svc-hero-icon">🤝</span><div class="svc-hero-title">تنسيق مبادرات</div><div class="svc-hero-sub">تيسير التعاون بين الجمعيات وتنسيق المبادرات المشتركة لتعظيم الأثر المجتمعي.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">🌐</span><div class="svc-card-title">الشبكات التعاونية</div><div class="svc-card-desc">ربط الجمعيات ذات الاهتمامات المشتركة لتبادل الموارد والخبرات وتنفيذ مشاريع مشتركة.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🗺️</span><div class="svc-card-title">خرائط المبادرات</div><div class="svc-card-desc">توثيق وتصنيف المبادرات القائمة وتجنب التكرار وضمان التكامل بين الجهود المختلفة.</div></div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">⚡</span><div class="svc-card-title">مبادرات الطوارئ</div><div class="svc-card-desc">التنسيق السريع بين الجمعيات في حالات الأزمات لتقديم استجابة فعّالة ومنظمة.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">📈</span><div class="svc-card-title">قياس الأثر المشترك</div><div class="svc-card-desc">تطوير أطر مشتركة لقياس الأثر الاجتماعي للمبادرات التعاونية وإعداد التقارير.</div></div>
          </div>
        </div>

        <div class="view" id="view-training">
          <div class="svc-hero" style="background:linear-gradient(135deg,#4a1942,#7b4ea6,#a78bfa)"><span class="svc-hero-icon">🎓</span><div class="svc-hero-title">تدريب تطوعي</div><div class="svc-hero-sub">برامج تدريبية متخصصة لتأهيل المتطوعين وبناء قدراتهم لأداء أعمال تطوعية ذات أثر حقيقي.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">🌱</span><div class="svc-card-title">تأهيل المتطوعين الجدد</div><div class="svc-card-desc">برامج استقبال وتوجيه تزود المتطوعين الجدد بالمعرفة والمهارات اللازمة للمشاركة الفعّالة.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">🏆</span><div class="svc-card-title">تطوير القيادات</div><div class="svc-card-desc">برامج قيادية متخصصة لإعداد جيل جديد من قادة العمل التطوعي في القطاع غير الربحي.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">🛠️</span><div class="svc-card-title">مهارات تقنية</div><div class="svc-card-desc">تدريب المتطوعين على الأدوات الرقمية وتقنيات إدارة المشاريع والتواصل الفعّال.</div></div>
            <div class="svc-card" style="--sc:#e65100"><span class="svc-card-icon">💡</span><div class="svc-card-title">الابتكار الاجتماعي</div><div class="svc-card-desc">ورش عمل تحفّز على التفكير الإبداعي وتطوير حلول مبتكرة للتحديات المجتمعية.</div></div>
          </div>
        </div>

        <div class="view" id="view-consulting">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1e3a5f,#3a72b8,#60a5fa)"><span class="svc-hero-icon">💡</span><div class="svc-hero-title">الاستشارات</div><div class="svc-hero-sub">خدمات استشارية متخصصة تدعم الجمعيات في اتخاذ قراراتها الاستراتيجية وتطوير قدراتها المؤسسية.</div></div>
          <div class="svc-cards-grid">
            <div class="svc-card" style="--sc:#3a72b8"><span class="svc-card-icon">🗺️</span><div class="svc-card-title">التخطيط الاستراتيجي</div><div class="svc-card-desc">مساعدة الجمعيات في صياغة رؤيتها ورسالتها وأهدافها الاستراتيجية للسنوات القادمة.</div></div>
            <div class="svc-card" style="--sc:#2ab8d0"><span class="svc-card-icon">📊</span><div class="svc-card-title">التقييم المؤسسي</div><div class="svc-card-desc">تشخيص شامل للوضع المؤسسي وتحديد نقاط القوة والضعف وفرص التطوير.</div></div>
            <div class="svc-card" style="--sc:#2eaa78"><span class="svc-card-icon">💰</span><div class="svc-card-title">الاستدامة المالية</div><div class="svc-card-desc">استشارات في تنويع مصادر التمويل وبناء نماذج عمل مستدامة للجمعيات.</div></div>
            <div class="svc-card" style="--sc:#7b4ea6"><span class="svc-card-icon">🔗</span><div class="svc-card-title">الشراكات الاستراتيجية</div><div class="svc-card-desc">تطوير استراتيجيات للشراكة مع القطاعات الحكومية والخاصة لتعزيز الأثر.</div></div>
          </div>
        </div>

        <div class="view" id="view-contact">
          <div class="svc-hero" style="background:linear-gradient(135deg,#1a2f1a,#2eaa78,#6ee7b7)"><span class="svc-hero-icon">📬</span><div class="svc-hero-title">التواصل معنا</div><div class="svc-hero-sub">نحن هنا لمساعدتك. تواصل معنا وسيردّ فريقنا خلال 24 ساعة.</div></div>
          <div class="contact-wrap">
            <div class="contact-info">
              <div class="contact-card"><div class="cc-icon" style="background:rgba(42,184,208,0.1)">📧</div><div><div class="cc-label">البريد الإلكتروني</div><div class="cc-value">info@mubadiroon.sa</div></div></div>
              <div class="contact-card"><div class="cc-icon" style="background:rgba(46,170,120,0.1)">📱</div><div><div class="cc-label">الجوال والواتساب</div><div class="cc-value">966-50-000-0000+</div></div></div>
              <div class="contact-card"><div class="cc-icon" style="background:rgba(123,78,166,0.1)">📍</div><div><div class="cc-label">الموقع</div><div class="cc-value">الرياض، المملكة العربية السعودية</div></div></div>
              <div class="contact-card"><div class="cc-icon" style="background:rgba(245,158,11,0.1)">🕐</div><div><div class="cc-label">أوقات العمل</div><div class="cc-value">الأحد – الخميس، ٨ص – ٥م</div></div></div>
            </div>
            <div class="contact-form-card">
              <div class="cfc-title">أرسل لنا رسالة</div>
              <div class="fg"><label>الاسم الكامل <span class="req-span">*</span></label><div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><input type="text" placeholder="أدخل اسمك الكامل"></div></div>
              <div class="fg"><label>البريد الإلكتروني <span class="req-span">*</span></label><div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span><input type="email" placeholder="example@mail.com" dir="ltr" style="text-align:right"></div></div>
              <div class="fg"><label>نوع الطلب</label><div class="fld"><span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span><select><option>استشارة</option><option>شراكة</option><option>تدريب</option><option>تنسيق مبادرة</option><option>استفسار عام</option></select></div></div>
              <div class="fg"><label>الرسالة <span class="req-span">*</span></label><div class="fld"><span class="fi top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span><textarea placeholder="اكتب رسالتك هنا..." style="min-height:100px"></textarea></div></div>
              <button class="btn-save" style="width:100%" onclick="sendContactMsg()">📨 إرسال الرسالة</button>
            </div>
          </div>
        </div>

        
        <div class="view" id="view-meetings">
          <div class="content">
            <div class="page-header">
              <div>
                <div class="ph-title">الاجتماعات</div>
                <div class="ph-sub">عرض الاجتماعات — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></div>
              </div>
              
            </div>

            <div class="stats-row">
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)">📅</div><div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div></div>
              <div class="stat-card" style="--sc:var(--green)"><div class="stat-icon" style="background:rgba(46,170,120,0.1)">🟢</div><div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">الحالية والقادمة</span></div></div>
              <div class="stat-card" style="--sc:var(--muted)"><div class="stat-icon" style="background:rgba(106,132,148,0.1)">📁</div><div><span class="stat-num" id="s-past">0</span><span class="stat-lbl">السابقة</span></div></div>
              <div class="stat-card" style="--sc:var(--red)"><div class="stat-icon" style="background:rgba(198,40,40,0.08)">🚫</div><div><span class="stat-num" id="s-canc">0</span><span class="stat-lbl">الملغاة</span></div></div>
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)">💻</div><div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div></div>
            </div>

            <div class="toolbar">
              <div class="search-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="renderAll()"></div>
              <div class="tb-div"></div>
              <select class="filter-select" id="catFilter" onchange="renderAll()">
                <option value="">🗂 كل التصنيفات</option>
                <option value="خيرية">🤝 خيرية واجتماعية</option>
                <option value="ثقافية">📚 ثقافية وتعليمية</option>
                <option value="صحية">🌿 صحية وبيئية</option>
                <option value="رياضية">⚽ رياضية وشبابية</option>
                <option value="تنموية">📈 تنموية واقتصادية</option>
                <option value="دينية">🕌 دينية ودعوية</option>
              </select>
              <div class="tb-div"></div>
              <div class="chips">
                <div class="chip on" id="chip-all" onclick="setTypeF('all')">الكل</div>
                <div class="chip" id="chip-online" onclick="setTypeF('online')">💻 عن بعد</div>
                <div class="chip" id="chip-onsite" onclick="setTypeF('onsite')">📍 حضوري</div>
              </div>
            </div>

            <div class="sec-wrap">
              <div class="sec-header"><div class="sec-icon" style="background:rgba(42,184,208,0.1)">🟢</div><div class="sec-title">الاجتماعات الحالية والقادمة</div><span class="sec-count sc-current" id="bc-cur">0</span></div>
              <div class="meetings-grid" id="grid-cur"></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('past')"><div class="sec-icon" style="background:rgba(106,132,148,0.1)">📁</div><div class="sec-title">الاجتماعات السابقة</div><span class="sec-count sc-past" id="bc-past">0</span><div class="sec-toggle" id="tog-past"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6"/></svg></div></div>
              <div id="sec-past"><div class="compact-list" id="list-past"></div></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="toggleSec('canc')"><div class="sec-icon" style="background:rgba(198,40,40,0.08)">🚫</div><div class="sec-title">الاجتماعات الملغاة</div><span class="sec-count sc-cancelled" id="bc-canc">0</span><div class="sec-toggle" id="tog-canc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6"/></svg></div></div>
              <div id="sec-canc"><div class="compact-list" id="list-canc"></div></div>
            </div>
          </div>

          
          <div class="overlay" id="ov-details" onclick="bgClose(event,'ov-details')">
            <div class="det-modal" onclick="event.stopPropagation()">
              <div class="det-banner"><div class="det-banner-bg" id="d-banner-bg"></div><div class="det-banner-pattern"></div><div class="det-banner-content"><div class="det-type-badge" id="d-type-badge"></div></div><button class="det-close" onclick="closeOv('ov-details')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
              <div class="det-body">
                <div class="det-title" id="d-title"></div>
                <div class="det-grid" id="d-grid"><div class="det-cell"><div class="det-cell-lbl">الفئة</div><div class="det-cell-val" id="d-cat"></div></div><div class="det-cell"><div class="det-cell-lbl">التاريخ</div><div class="det-cell-val" id="d-date"></div></div><div class="det-cell"><div class="det-cell-lbl">الوقت</div><div class="det-cell-val" id="d-time"></div></div><div class="det-cell" id="d-loc-cell"><div class="det-cell-lbl">المكان</div><div class="det-cell-val" id="d-loc"></div></div></div>
                <div class="det-presenter"><div class="dp-av" id="d-av"></div><div><div class="dp-name" id="d-pname"></div><div class="dp-role">مقدم الاجتماع</div></div></div>
                <div id="d-notes-wrap" style="display:none" class="det-block"><div class="det-block-lbl">ملاحظات</div><div class="det-notes" id="d-notes"></div></div>
                <div id="d-report-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--green)">📋 تقرير الاجتماع</div><div class="det-report" id="d-report-content"></div></div>
                <div id="d-cancel-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--red)">سبب الإلغاء</div><div class="det-cancel" id="d-cancel-reason"></div></div>
              </div>
              
              <div class="det-ft"><button class="btn-cancel" style="flex:1" onclick="closeOv('ov-details')">إغلاق</button></div>
            </div>
          </div>
        </div>



        
        <div class="view" id="view-projects">
          <main class="main-content">
            <div class="ph">
              <div>
                <h1>المشاريع المشتركة</h1>
                <p>عرض المشاريع — <span style="color:#b45309;font-weight:700">وضع العرض فقط</span></p>
              </div>
              
            </div>
            <div class="stats" id="statsRow"></div>
            <div class="filter-row">
              <div class="dd-wrap" id="ddWrap"><button class="dd-btn" id="ddBtn" type="button"><span class="dd-left"><span class="emoji">🏢</span><span id="ddLabel">كل التصنيفات</span></span><i class="fa-solid fa-chevron-down chevron"></i></button><div class="dd-menu" id="ddMenu"></div></div>
              <div class="search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input type="text" class="sinput" id="searchQ" placeholder="ابحث باسم المشروع..."></div>
              <span class="res-badge" id="resBadge"><span id="resNum">0</span> مشروع</span>
            </div>
            <div class="tabs">
              <button class="tab on" data-t="tab-active"><i class="fa-solid fa-rocket"></i>الحالية<span class="n" id="n-active">0</span></button>
              <button class="tab" data-t="tab-done"><i class="fa-solid fa-circle-check"></i>المنتهية<span class="n" id="n-done">0</span></button>
              <button class="tab" data-t="tab-canceled"><i class="fa-solid fa-ban"></i>الملغاة<span class="n" id="n-canceled">0</span></button>
            </div>
            <div id="tab-active" class="pane on grid"></div>
            <div id="tab-done" class="pane grid"></div>
            <div id="tab-canceled" class="pane grid"></div>
          </main>
          
          <div class="ov" id="ovConfirm" style="display:none"></div>
        </div>

      </div><!-- /content -->
    </div><!-- /main -->
  </div><!-- /layout -->

  <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

  <script src="<?php echo e(asset('js/consulting.js')); ?>"></script>
  <script src="<?php echo e(asset('js/meeting.js')); ?>"></script>
  <script src="<?php echo e(asset('js/orders.js')); ?>"></script>
  <script src="<?php echo e(asset('js/joint-projects.js')); ?>"></script>
  <script src="<?php echo e(asset('js/spa-nav.js')); ?>"></script>
  <script>
    /**
     * Override any action functions that would trigger add/edit/delete modals.
     * This ensures even if JS calls them, nothing happens for the user role.
     */
    window.openCreate    = () => showReadOnlyToast();
    window.openAddOpp    = () => showReadOnlyToast();
    window.editFromDet   = () => showReadOnlyToast();
    window.openNew       = null; // handled below via event

    function showReadOnlyToast() {
      const t   = document.getElementById('toast');
      const msg = document.getElementById('t-msg');
      const ico = document.getElementById('t-icon');
      if (!t) return;
      ico.textContent = '🔒';
      msg.textContent = 'أنت في وضع العرض فقط. هذه الصلاحية متاحة للمدير.';
      t.style.background = 'rgba(245,158,11,0.12)';
      t.style.borderRight = '4px solid #f59e0b';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 3500);
    }

    // Block the "new project" button by ID
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('openNew');
      if (btn) btn.addEventListener('click', e => { e.stopImmediatePropagation(); showReadOnlyToast(); });
    });
  </script>
</body>

</html>
<?php /**PATH /home/testuser/Downloads/Tkamel-main/resources/views/user/consulting.blade.php ENDPATH**/ ?>