        {{-- ══ فرص التطوع — عرض مسطح بدون تصنيفات ══ --}}
        <div class="view" id="view-admin">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">تصفح الفرص المتاحة</div>
            </div>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)"><div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-star"></i></div><div><span class="s-num" id="st-total">0</span><span class="s-lbl">إجمالي الفرص</span></div></div>
            <div class="stat-card" style="--sc:var(--green)"><div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div><div><span class="s-num" id="st-open">0</span><span class="s-lbl">فرص مفتوحة</span></div></div>
            <div class="stat-card" style="--sc:var(--gold)"><div class="s-icon" style="background:rgba(245,158,11,0.1)">⏳</div><div><span class="s-num" id="st-pending">0</span><span class="s-lbl">طلباتي</span></div></div>
            <div class="stat-card" style="--sc:var(--purple)"><div class="s-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-tag"></i></div><div><span class="s-num" id="st-cats">0</span><span class="s-lbl">التصنيفات</span></div></div>
          </div>


          <div class="tabs" style="margin-bottom:16px;">
            <button class="tab on" data-opp-t="available" onclick="setOppTab('available')"><i class="fa-solid fa-star"></i>الفرص المتاحة<span class="n" id="n-opp-avail">0</span></button>
            <button class="tab" data-opp-t="active" onclick="setOppTab('active')"><i class="fa-solid fa-rocket"></i>مشاريع تطوعية نشطة<span class="n" id="n-opp-active">0</span></button>
            <button class="tab" data-opp-t="expired" onclick="setOppTab('expired')"><i class="fa-solid fa-clock-rotate-left"></i>الفرص المنتهية<span class="n" id="n-opp-expired">0</span></button>
          </div>

          <div class="toolbar" style="margin-bottom:16px">
            <div class="sw">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input class="s-inp" id="user-opp-search" type="text" placeholder="ابحث عن فرصة..." oninput="renderUserOpps()">
            </div>
          </div>

          <div class="all-opps-grid" id="user-all-opps-grid"></div>
        </div>

        {{-- ══ صفحات التصنيفات (مخفية في وضع المستخدم — تُستخدم للمنطق فقط) ══ --}}
        <div class="view" id="view-admin-opps" style="display:none"></div>
        <div class="view" id="view-admin-reqs" style="display:none"></div>
        <div class="view" id="view-assoc" style="display:none"></div>
        <div class="view" id="view-assoc-opps" style="display:none">
          <div id="assoc-opps-grid"></div>
        </div>
        {{-- Hidden elements required by JS --}}
        <div style="display:none">
          <div id="cats-grid"></div>
          <div id="assoc-cats-grid"></div>
          <div id="admin-opps-grid"></div>
          <div id="req-list"></div>
          <input id="admin-opp-search">
          <input id="assoc-opp-search">
          <span id="ao-cat-icon"></span><span id="ao-cat-name"></span>
          <span id="ao-title"></span><span id="ao-sub"></span>
          <span id="ao-cat-icon2"></span><span id="ao-cat-name2"></span>
          <span id="ao-title2"></span>
          <span id="ast-total">0</span><span id="ast-applied">0</span>
          <span id="ast-approved">0</span><span id="ast-cats">0</span>
          <span id="rc-pending">0</span><span id="rc-approved">0</span><span id="rc-rejected">0</span>
          <span id="nb-reqs"></span>
          <span id="all-opp-search"></span>
          <select id="all-opp-cat-filter"></select>
          <select id="all-opp-status-filter"></select>
          <div id="all-admin-opps-grid"></div>
          <div id="panel-all-opps"></div>
          <div id="panel-cats"></div>
          <span id="pill-opps"></span><span id="pill-cats"></span>
          <span id="pill-cats-count"></span>
        </div>
