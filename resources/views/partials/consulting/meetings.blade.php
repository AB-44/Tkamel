        <div class="view" id="view-meetings">
          <div class="content">

            <!-- PAGE HEADER -->
            <div class="page-header">
              <div>
                <div class="ph-title">إدارة الاجتماعات</div>
                <div class="ph-sub">تنظيم ومتابعة اجتماعات الجمعيات المجتمعية</div>
              </div>
              <button class="btn-create" onclick="mtgOpenCreate()">
                <div class="btn-create-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                    <line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
                  </svg>
                </div>
                إنشاء اجتماع
              </button>
            </div>

            <!-- STATS -->
            <div class="stats-row">
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-regular fa-calendar"></i></div><div><span class="stat-num" id="s-total">0</span><span class="stat-lbl">إجمالي الاجتماعات</span></div></div>
              <div class="stat-card" style="--sc:var(--green)"><div class="stat-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-circle"></i></div><div><span class="stat-num" id="s-cur">0</span><span class="stat-lbl">الحالية والقادمة</span></div></div>
              <div class="stat-card" style="--sc:var(--muted)"><div class="stat-icon" style="background:rgba(106,132,148,0.1)"><i class="fa-regular fa-folder"></i></div><div><span class="stat-num" id="s-past">0</span><span class="stat-lbl">السابقة</span></div></div>
              <div class="stat-card" style="--sc:var(--red)"><div class="stat-icon" style="background:rgba(198,40,40,0.08)"><i class="fa-solid fa-ban"></i></div><div><span class="stat-num" id="s-canc">0</span><span class="stat-lbl">الملغاة</span></div></div>
              <div class="stat-card" style="--sc:var(--teal-glow)"><div class="stat-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-laptop"></i></div><div><span class="stat-num" id="s-online">0</span><span class="stat-lbl">عن بعد</span></div></div>
            </div>

            <!-- TOOLBAR -->
            <div class="toolbar">
              <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>
                <input class="search-input" id="searchInput" type="text" placeholder="ابحث عن اجتماع أو مقدم..." oninput="mtgRenderAll()">
              </div>
              <div class="tb-div"></div>
              <select class="filter-select" id="catFilter" onchange="mtgRenderAll()">
                <option value="">كل التصنيفات</option>
              </select>
              <div class="tb-div"></div>
              <div class="chips">
                <div class="chip on" id="chip-all" onclick="mtgSetTypeF('all')">الكل</div>
                <div class="chip" id="chip-online" onclick="mtgSetTypeF('online')">عن بعد</div>
                <div class="chip" id="chip-onsite" onclick="mtgSetTypeF('onsite')">حضوري</div>
              </div>
            </div>

            <div class="sec-wrap">
              <div class="sec-header"><div class="sec-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-circle" style="font-size:.7rem"></i></div><div class="sec-title">الاجتماعات الحالية والقادمة</div><span class="sec-count sc-current" id="bc-cur">0</span></div>
              <div class="meetings-grid" id="grid-cur"></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="mtgToggleSec('past')"><div class="sec-icon" style="background:rgba(106,132,148,0.1)"><i class="fa-regular fa-folder"></i></div><div class="sec-title">الاجتماعات السابقة</div><span class="sec-count sc-past" id="bc-past">0</span><div class="sec-toggle" id="tog-past"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6" /></svg></div></div>
              <div id="sec-past"><div class="compact-list" id="list-past"></div></div>
            </div>
            <div class="sec-wrap">
              <div class="sec-header collapsible" onclick="mtgToggleSec('canc')"><div class="sec-icon" style="background:rgba(198,40,40,0.08)"><i class="fa-solid fa-ban"></i></div><div class="sec-title">الاجتماعات الملغاة</div><span class="sec-count sc-cancelled" id="bc-canc">0</span><div class="sec-toggle" id="tog-canc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="12" height="12"><path d="M6 9l6 6 6-6" /></svg></div></div>
              <div id="sec-canc"><div class="compact-list" id="list-canc"></div></div>
            </div>

          </div>{{-- /meetings content --}}

          <!-- ── MEETINGS MODALS ── -->
          <div class="overlay" id="ov-create" onclick="mtgBgClose(event,'ov-create')"><div class="modal" onclick="event.stopPropagation()"><div class="modal-hd"><div class="modal-hd-icon" id="mhd-icon"><i class="fa-regular fa-calendar"></i></div><div class="modal-hd-text"><div class="modal-hd-title" id="mhd-title">إنشاء اجتماع جديد</div><div class="modal-hd-sub" id="mhd-sub">أضف تفاصيل الاجتماع أدناه</div></div><button class="modal-close" onclick="mtgCloseOv('ov-create')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
          <div class="modal-body">
            <div class="fg"><label>عنوان الاجتماع <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span><input type="text" id="f-title" placeholder="مثال: اجتماع التخطيط الاستراتيجي"></div></div>
            <div class="row2">
              <div class="fg"><label>التصنيف <span class="req">*</span></label><div id="f-cat-picker"></div><input type="hidden" id="f-cat" /></div>
              <div class="fg"><label>اسم المقدم <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></span><input type="text" id="f-presenter" placeholder="اسم المقدم"></div></div>
            </div>
            <div class="row2">
              <div class="fg"><label>التاريخ <span class="req">*</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span><input type="date" id="f-date"></div></div>
              <div class="fg"><label>الوقت</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span><input type="time" id="f-time"></div></div>
            </div>
            <div class="row2">
              <div class="fg"><label>تاريخ الانتهاء <span style="font-size:.7rem;color:var(--muted)">(اختياري)</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span><input type="date" id="f-end-date"></div></div>
              <div class="fg"><label>وقت الانتهاء <span style="font-size:.7rem;color:var(--muted)">(اختياري)</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span><input type="time" id="f-end-time"></div></div>
            </div>
            <div class="form-divider">نوع الاجتماع</div>
            <div class="fg"><label>نوع الاجتماع <span class="req">*</span></label><div class="type-toggle"><button class="type-btn" id="tb-online" onclick="mtgSetMType('online')" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>عن بعد</button><button class="type-btn" id="tb-onsite" onclick="mtgSetMType('onsite')" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>حضوري</button></div></div>
            <div class="fg" id="fg-link"><label>رابط الاجتماع</label><div class="fld link-copy-wrap"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></span><input type="url" id="f-link" placeholder="https://meet.google.com/xxx-xxxx-xxx" dir="ltr" style="text-align:right;padding-left:76px"><button class="link-copy-btn" type="button" onclick="mtgCopyLink()">نسخ</button></div></div>
            <div class="fg" id="fg-location" style="display:none"><label>المكان</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span><input type="text" id="f-location" placeholder="مثال: قاعة الاجتماعات الرئيسية — الرياض"></div></div>
            <div class="fg" id="fg-location-url" style="display:none"><label>رابط الموقع <span style="font-size:.7rem;color:var(--muted)">(اختياري)</span></label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg></span><input type="url" id="f-location-url" placeholder="رابط الموقع على الخريطة" dir="ltr" style="text-align:right"></div></div>
            <div class="fg"><label>اتجاه الدعوة <span style="font-size:.7rem;color:var(--muted)">(اختياري)</span></label><select class="form-input" id="f-invitation" style="cursor:pointer"></select></div>
            <div class="fg"><label>ملاحظات</label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span><textarea id="f-notes" placeholder="أضف أي ملاحظات أو تفاصيل إضافية..."></textarea></div></div>
            <div class="form-section-hd"><i class="fa-solid fa-list-check"></i> محاور الاجتماع
              <button type="button" class="btn-add-agenda" onclick="mtgAddAgendaItem()"><i class="fa-solid fa-circle-plus"></i> إضافة محور</button>
            </div>
            <div id="agenda-list"></div>
            <div id="report-section" class="report-section" style="display:none">
              <div class="report-section-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>تقرير الاجتماع</div>
              <div class="fg" style="margin-bottom:12px"><label>ملخص ما تم مناقشته</label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span><textarea id="f-report-summary" placeholder="اكتب ملخصاً لما تمت مناقشته في الاجتماع..." style="min-height:90px"></textarea></div></div>
              <div class="fg" style="margin-bottom:12px"><label>القرارات المتخذة</label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></span><textarea id="f-report-decisions" placeholder="اذكر القرارات الرئيسية التي تم اتخاذها..." style="min-height:80px"></textarea></div></div>
              <div class="row2">
                <div class="fg" style="margin-bottom:0"><label>عدد الحضور</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg></span><input type="number" id="f-report-attendees" placeholder="مثال: 12" min="0"></div></div>
                <div class="fg" style="margin-bottom:0"><label>الإجراءات التالية</label><div class="fld"><span class="fld-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><polyline points="9 18 15 12 9 6"/></svg></span><input type="text" id="f-report-actions" placeholder="مثال: اجتماع متابعة في مارس"></div></div>
              </div>
            </div>
          </div>
          <div class="modal-ft"><button class="btn-cancel" onclick="mtgCloseOv('ov-create')">إلغاء</button><button class="btn-save" onclick="mtgSaveMeeting()"><span id="save-lbl"><i class="fa-solid fa-floppy-disk" style="margin-left:8px"></i> حفظ الاجتماع</span></button></div>
          </div></div>

          <div class="overlay" id="ov-details" onclick="mtgBgClose(event,'ov-details')"><div class="det-modal" onclick="event.stopPropagation()"><div class="det-banner"><div class="det-banner-bg" id="d-banner-bg"></div><div class="det-banner-pattern"></div><div class="det-banner-content"><div class="det-type-badge" id="d-type-badge"></div></div><button class="det-close" onclick="mtgCloseOv('ov-details')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div class="det-body"><div class="det-title" id="d-title"></div><div class="det-grid" id="d-grid"><div class="det-cell"><div class="det-cell-lbl">الفئة</div><div class="det-cell-val" id="d-cat"></div></div><div class="det-cell"><div class="det-cell-lbl">التاريخ</div><div class="det-cell-val" id="d-date"></div></div><div class="det-cell"><div class="det-cell-lbl">الوقت</div><div class="det-cell-val" id="d-time"></div></div><div class="det-cell" id="d-loc-cell"><div class="det-cell-lbl">المكان</div><div class="det-cell-val" id="d-loc"></div></div></div><div class="det-presenter"><div class="dp-av" id="d-av"></div><div><div class="dp-name" id="d-pname"></div><div class="dp-role">مقدم الاجتماع</div></div></div><div id="d-notes-wrap" style="display:none" class="det-block"><div class="det-block-lbl">ملاحظات</div><div class="det-notes" id="d-notes"></div></div><div id="d-report-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--green)"><i class="fa-solid fa-clipboard-list" style="margin-left:6px"></i> تقرير الاجتماع</div><div class="det-report" id="d-report-content"></div></div><div id="d-cancel-wrap" style="display:none" class="det-block"><div class="det-block-lbl" style="color:var(--red)">سبب الإلغاء</div><div class="det-cancel" id="d-cancel-reason"></div></div></div><div class="det-ft"><button class="btn-cancel" style="flex:1" onclick="mtgCloseOv('ov-details')">إغلاق</button><button class="btn-save" id="det-edit-btn" onclick="mtgEditFromDet()" style="flex:1"><i class="fa-regular fa-pen-to-square" style="margin-left:8px"></i> تعديل</button></div></div></div>

          <div class="overlay" id="ov-delete" onclick="mtgBgClose(event,'ov-delete')"><div class="confirm-box" onclick="event.stopPropagation()"><div class="confirm-icon-wrap" style="background:rgba(229,57,53,0.1)"><i class="fa-solid fa-trash-can" style="color:#e11d48"></i></div><div class="confirm-title">حذف الاجتماع نهائياً</div><div class="confirm-desc">هل أنت متأكد؟ سيتم حذف الاجتماع بشكل دائم<br>ولا يمكن التراجع عن هذا الإجراء.</div><div class="confirm-row"><button class="btn-cancel" style="flex:1" onclick="mtgCloseOv('ov-delete')">إلغاء</button><button class="btn-danger" onclick="mtgDoDelete()">حذف نهائياً</button></div></div></div>

          <div class="overlay" id="ov-cancel" onclick="mtgBgClose(event,'ov-cancel')"><div class="cancel-reason-box" onclick="event.stopPropagation()"><div class="modal-hd"><div class="modal-hd-icon" style="background:rgba(198,40,40,0.1);border-color:rgba(198,40,40,0.2)"><i class="fa-solid fa-ban" style="color:#e11d48"></i></div><div class="modal-hd-text"><div class="modal-hd-title">إلغاء الاجتماع</div><div class="modal-hd-sub">أدخل سبب الإلغاء</div></div><button class="modal-close" onclick="mtgCloseOv('ov-cancel')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div class="modal-body"><div class="fg"><label>سبب الإلغاء <span class="req">*</span></label><div class="fld"><span class="fld-icon top"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span><textarea id="f-cancel-reason" placeholder="مثال: تعارض المواعيد، ظروف طارئة..." style="border-color:rgba(198,40,40,0.25)"></textarea></div></div></div><div class="modal-ft"><button class="btn-cancel" onclick="mtgCloseOv('ov-cancel')">تراجع</button><button class="btn-danger" onclick="mtgDoCancel()"><i class="fa-solid fa-ban" style="margin-left:8px"></i> تأكيد الإلغاء</button></div></div></div>
          <div class="overlay" id="ov-attendees" onclick="mtgBgClose(event,'ov-attendees')">
            <div class="modal" style="max-width:500px; border-radius:16px; overflow:hidden; border:1px solid rgba(255,255,255,0.1); box-shadow:0 25px 50px -12px rgba(0,0,0,0.3); padding:0;" onclick="event.stopPropagation()">
              <div style="background:linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding:24px 20px; text-align:center; position:relative;">
                <div style="width:56px;height:56px;background:#22c55e;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 4px 14px rgba(34,197,94,0.35)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" width="28" height="28"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3 id="att-meeting-title" style="margin:0 0 6px;font-size:1.1rem;font-weight:800;color:#14532d;">الجمعيات المسجّلة للحضور</h3>
                <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.6);border-radius:20px;padding:4px 14px;">
                  <span style="font-size:.8rem;color:#166534;font-weight:700;">إجمالي الحاضرين:</span>
                  <span id="att-total-count" style="font-size:1rem;font-weight:900;color:#16a34a;">0</span>
                </div>
                <button onclick="mtgCloseAttendees()" style="position:absolute;top:16px;right:16px;background:rgba(255,255,255,0.6);border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;color:#22c55e;display:flex;align-items:center;justify-content:center;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
              <div id="att-list" style="max-height:380px;overflow-y:auto;background:var(--bg-card, #fff);">
                <div style="text-align:center;padding:2rem;color:var(--text-muted)">⏳ جاري التحميل...</div>
              </div>
              <div style="padding:16px 24px;background:rgba(249,250,251,0.6);border-top:1px solid rgba(0,0,0,0.05);display:flex;justify-content:flex-end;">
                <button onclick="mtgCloseAttendees()" style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none;border-radius:10px;padding:10px 24px;font-weight:800;font-family:Tajawal,sans-serif;font-size:.95rem;cursor:pointer;">إغلاق</button>
              </div>
            </div>
          </div>

          <div class="toast" id="toast"><span id="t-icon"></span><span id="t-msg"></span></div>

        </div>{{-- /view-meetings --}}
