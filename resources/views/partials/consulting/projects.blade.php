        <div class="view" id="view-projects">
          <main class="main-content">
            <div class="ph">
              <div><h1>المشاريع المشتركة</h1><p>المشاريع تُوجَّه تلقائياً للجمعيات بناءً على تطابق التصنيف</p></div>
              <button class="btn-primary" id="openNew" style="margin-right:auto; padding: 10px 24px;">
                <div class="btn-icon-wrap" style="background:rgba(255,255,255,0.22);color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-weight:bold;margin-left:8px">+</div>
                مشروع جديد
              </button>
            </div>
            <div class="stats" id="statsRow"></div>
            <div class="filter-row">
              <div class="dd-wrap" id="ddWrap"><button class="dd-btn" id="ddBtn" type="button"><span class="dd-left"><span class="emoji"><i class="fa-solid fa-building"></i></span><span id="ddLabel">كل التصنيفات</span></span><i class="fa-solid fa-chevron-down chevron"></i></button><div class="dd-menu" id="ddMenu"></div></div>
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
          <!-- PROJECTS MODALS -->
          <div class="ov" id="ovNew"><div class="mbox"><div class="mhd"><h2><i class="fa-solid fa-plus" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>إنشاء مشروع مشترك</h2><button class="mcl" id="clNew"><i class="fa-solid fa-xmark"></i></button></div><form id="fNew"><div class="fg"><label>اسم المشروع</label><input id="nN" placeholder="أدخل اسم المشروع..." required></div><div class="fg"><label>تصنيف المشروع <span style="color:#e11d48">*</span></label><div id="nD-picker" style="display:flex;flex-wrap:wrap;gap:8px;padding:10px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;min-height:48px;"></div><input type="hidden" id="nD" required /></div><div class="fg"><label>هدف المشروع</label><textarea id="nG" placeholder="اشرح هدف المشروع بوضوح..." required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="nS" required></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="nE" required></div></div><div class="fg"><label>حالة المشروع</label><select id="nSt"><option value="قيد الإعداد">قيد الإعداد والتخطيط</option><option value="مستمر">بدء التنفيذ الفعلي</option><option value="فكرة">فكرة وعصف ذهني</option></select></div><button type="submit" class="bsub"><i class="fa-solid fa-paper-plane"></i> حفظ المشروع</button></form></div></div>

          <div class="ov" id="ovEdit"><div class="mbox"><div class="mhd"><h2><i class="fa-regular fa-pen-to-square" style="color:var(--teal);margin-left:7px;font-size:.9rem"></i>تعديل وإضافة تقدم</h2><button class="mcl" id="clEdit"><i class="fa-solid fa-xmark"></i></button></div><form id="fEdit"><input type="hidden" id="eId"><div class="fg"><label>اسم المشروع</label><input id="eN" required></div><div class="fg"><label>الهدف / الوصف</label><textarea id="eG" rows="2" required></textarea></div><div class="frow"><div class="fg"><label>تاريخ البدء</label><input type="date" id="eS"></div><div class="fg"><label>تاريخ النهاية</label><input type="date" id="eE"></div></div><div class="fg"><label>نسبة الإنجاز (%)</label><input type="number" id="eP" min="0" max="100"></div><div class="fg"><label>إضافة تقدم جديد للسجل</label><textarea id="eU" placeholder="اكتب آخر ما تم إنجازه..."></textarea></div><button type="submit" class="bsub">حفظ التحديث</button></form></div></div>
          <div class="ov" id="ovConfirm"><div class="mbox cbox"><div class="cico"><i class="fa-solid fa-triangle-exclamation"></i></div><h3 id="cTtl"></h3><p id="cMsg"></p><div class="cbtns"><button class="by" id="cY">تأكيد</button><button class="bn" id="cN">تراجع</button></div></div></div>
        </div>{{-- /view-projects --}}
