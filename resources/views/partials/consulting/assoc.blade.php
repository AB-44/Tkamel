        <div class="view" id="view-assoc">
          <div class="page-hd">
            <div>
              <div class="ph-title">فرص التطوع</div>
              <div class="ph-sub">تصفح الفرص المتاحة وقدّم طلبك — سيتم مراجعة طلبك من قِبَل مبادرون</div>
            </div>
          </div>

          <div id="assoc-notif-banner" style="display:none" class="notif-banner">
            <div class="nb-icon"><i class="fa-solid fa-bell"></i></div>
            <div class="nb-text">
              <div class="nb-title" id="notif-banner-title">تم قبول طلبك</div>
              <div class="nb-sub" id="notif-banner-sub">تم قبول طلب تقديمك بنجاح من قِبَل الإدارة</div>
            </div>
            <button class="nb-close"
              onclick="document.getElementById('assoc-notif-banner').style.display='none'">إغلاق</button>
          </div>

          <div class="stats-row">
            <div class="stat-card" style="--sc:var(--teal-glow)">
              <div class="s-icon" style="background:rgba(42,184,208,0.1)"><i class="fa-solid fa-star"></i></div>
              <div><span class="s-num" id="ast-total">0</span><span class="s-lbl">فرص متاحة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--gold)">
              <div class="s-icon" style="background:rgba(245,158,11,0.1)"><i class="fa-regular fa-pen-to-square"></i></div>
              <div><span class="s-num" id="ast-applied">0</span><span class="s-lbl">طلباتي</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--green)">
              <div class="s-icon" style="background:rgba(46,170,120,0.1)">✅</div>
              <div><span class="s-num" id="ast-approved">0</span><span class="s-lbl">مقبولة</span></div>
            </div>
            <div class="stat-card" style="--sc:var(--purple)">
              <div class="s-icon" style="background:rgba(123,78,166,0.1)"><i class="fa-solid fa-tag"></i></div>
              <div><span class="s-num" id="ast-cats">6</span><span class="s-lbl">التصنيفات</span></div>
            </div>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div style="font-size:1rem;font-weight:800;color:var(--ink)">تصفح التصنيفات</div>
          </div>
          <div class="cats-grid" id="assoc-cats-grid"></div>
        </div>
