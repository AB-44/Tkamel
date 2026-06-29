        <div class="view active" id="view-admin">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">إدارة التصنيفات وإضافة فرص التطوع لكل تصنيف</div>
            </div>
            <div class="ph-actions">
              <button class="btn-primary" onclick="openAddOpp('global')">
                <div class="btn-icon-wrap" style="background:rgba(255,255,255,0.22);color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-weight:bold">+</div>
                نشر فرصة
              </button>
            </div>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)">
              <div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-star"></i></div>
              <div><span class="s-num" id="st-total">0</span><span class="s-lbl">إجمالي الفرص</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--green)">
              <div class="s-icon" style="background:rgba(46,170,120,0.1)"><i class="fa-solid fa-circle-check"></i></div>
              <div><span class="s-num" id="st-open">0</span><span class="s-lbl">فرص مفتوحة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--gold)">
              <div class="s-icon" style="background:rgba(245,158,11,0.1)"><i class="fa-solid fa-hourglass-half"></i></div>
              <div><span class="s-num" id="st-pending">0</span><span class="s-lbl">طلبات معلقة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--purple)">
              <div class="s-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-tag"></i></div>
              <div><span class="s-num" id="st-cats">6</span><span class="s-lbl">التصنيفات</span></div>
            </div>
          </div>

          {{-- ── Toggle pill ── --}}
          <div class="vt-pill-wrap">
            <button class="vt-pill active" id="pill-opps" onclick="switchAdminTab('opps')">
              <i class="fa-solid fa-list-ul"></i> الفرص
            </button>
            <button class="vt-pill" id="pill-cats" onclick="switchAdminTab('cats')">
              <i class="fa-solid fa-tag"></i> التصنيفات
              <span class="vt-pill-count" id="pill-cats-count"></span>
            </button>
          </div>

          {{-- ── All Opportunities Panel ── --}}
          <div id="panel-all-opps">
            <div class="vt-toolbar">
              <div class="vt-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="all-opp-search" placeholder="ابحث عن فرصة..." oninput="renderAllAdminOpps()">
              </div>
              <select class="vt-filter-sel" id="all-opp-cat-filter" onchange="renderAllAdminOpps()">
                <option value="">جميع التصنيفات</option>
              </select>
              <select class="vt-filter-sel" id="all-opp-status-filter" onchange="renderAllAdminOpps()">
                <option value="">كل الحالات</option>
                <option value="open">مفتوحة</option>
                <option value="closed">مغلقة</option>
              </select>
            </div>
            <div class="rich-opps-grid" id="all-admin-opps-grid"></div>
          </div>

          {{-- ── Categories Panel ── --}}
          <div id="panel-cats" style="display:none">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
              <div style="font-size:1rem;font-weight:800;color:var(--ink)">التصنيفات</div>
              <div style="font-size:0.82rem;color:var(--muted)">اختر تصنيفاً لعرض فرص التطوع الخاصة به أو إضافة فرصة جديدة</div>
            </div>
            <div class="cats-grid" id="cats-grid"></div>
          </div>
        </div>
