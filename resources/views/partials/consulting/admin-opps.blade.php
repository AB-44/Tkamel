        <div class="view" id="view-admin-opps">
          <div class="opp-view-header">
            <button class="back-btn" onclick="backToCategories()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M19 12H5M12 5l7 7-7 7" />
              </svg>
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
            <button class="btn-primary" onclick="openAddOpp()">
              <div class="btn-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                  width="14" height="14">
                  <line x1="12" y1="5" x2="12" y2="19" />
                  <line x1="5" y1="12" x2="19" y2="12" />
                </svg></div>
              إضافة فرصة
            </button>
          </div>
          <div class="toolbar">
            <div class="sw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15"
                height="15">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
              </svg><input class="s-inp" id="admin-opp-search" type="text" placeholder="ابحث عن فرصة..."
                oninput="renderAdminOpps()"></div>
          </div>
          <div class="opps-grid" id="admin-opps-grid"></div>
        </div>
