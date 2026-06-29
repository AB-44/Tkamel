        <div class="view" id="view-assoc-opps">
          <div class="opp-view-header">
            <button class="back-btn" onclick="backToAssocCats()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M19 12H5M12 5l7 7-7 7" />
              </svg>
              التصنيفات
            </button>
            <div class="cat-title-badge">
              <span class="ctb-icon" id="ao-cat-icon2"></span>
              <span class="ctb-name" id="ao-cat-name2"></span>
            </div>
          </div>
          <div class="page-hd">
            <div>
              <div class="ph-title" id="ao-title2">الفرص</div>
              <div class="ph-sub">اضغط على "تقديم" للتقدم. سيُرسَل طلبك لمراجعة مبادرون.</div>
            </div>
          </div>
          <div class="toolbar">
            <div class="sw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15"
                height="15">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
              </svg><input class="s-inp" id="assoc-opp-search" type="text" placeholder="ابحث عن فرصة..."
                oninput="renderAssocOpps()"></div>
          </div>
          <div class="opps-grid" id="assoc-opps-grid"></div>
        </div>
