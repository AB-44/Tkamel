        {{-- ══ PROJECTS — عرض فقط (بدون زر مشروع جديد) ══ --}}
        <div class="view" id="view-projects">
          <main class="main-content">
            <div class="ph">
              <div>
                <h1>المشاريع المشتركة</h1>
                <p>عرض المشاريع المشتركة</p>
              </div>
              {{-- لا يوجد زر "مشروع جديد" --}}
            </div>
            <div class="stats" id="statsRow"></div>
            <div class="tabs" style="margin-bottom:16px;">
              <button class="tab on" data-t="tab-active"><i class="fa-solid fa-star"></i>المشاريع المتاحة<span class="n" id="n-active">0</span></button>
              <button class="tab" data-t="tab-approved"><i class="fa-solid fa-rocket"></i>مشاريع مشتركة نشطة<span class="n" id="n-approved">0</span></button>
              <button class="tab" data-t="tab-done"><i class="fa-solid fa-clock-rotate-left"></i>المشاريع المنتهية<span class="n" id="n-done">0</span></button>
            </div>
            <div class="filter-row">
              <div class="dd-wrap" id="ddWrap"><button class="dd-btn" id="ddBtn" type="button"><span class="dd-left"><span class="emoji"><i class="fa-solid fa-building"></i></span><span id="ddLabel">كل التصنيفات</span></span><i class="fa-solid fa-chevron-down chevron"></i></button><div class="dd-menu" id="ddMenu"></div></div>
              <div class="search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input type="text" class="sinput" id="searchQ" placeholder="ابحث باسم المشروع..."></div>
              <span class="res-badge" id="resBadge"><span id="resNum">0</span> مشروع</span>
            </div>
            <div id="tab-active" class="pane on grid"></div>
            <div id="tab-approved" class="pane grid"></div>
            <div id="tab-done" class="pane grid"></div>
          </main>
          {{-- عرض تفاصيل فقط — بدون نماذج تعديل أو إنشاء --}}
          <div class="ov" id="ovConfirm" style="display:none"></div>
        </div>{{-- /view-projects --}}
