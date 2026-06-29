        <div class="view" id="view-dashboard">
  <div class="content">

    <!-- PAGE HEADER -->
    <div class="page-hd">
        <div>
            <div class="ph-title">لوحة التحكم</div>
            <div class="ph-sub">مرحباً بعودتك — إليك ملخص أهم النشاطات والإحصائيات</div>
        </div>
    </div>

    <!-- Stats row -->
    <div class="stats-row" style="margin-bottom:2rem">
        <div class="stat-card" style="--sc:var(--teal-glow)">
            <div class="s-icon" style="background:rgba(42,184,208,0.1)">🏢</div>
            <div><span class="s-num" id="dash-assoc-count">—</span><span class="s-lbl">إجمالي الجمعيات</span></div>
        </div>
        <div class="stat-card" style="--sc:var(--green)">
            <div class="s-icon" style="background:rgba(46,170,120,0.1)">💡</div>
            <div><span class="s-num" id="dash-opp-count">—</span><span class="s-lbl">الفرص المتاحة</span></div>
        </div>
        <div class="stat-card" style="--sc:var(--blue)">
            <div class="s-icon" style="background:rgba(29,111,164,0.1)">🤝</div>
            <div><span class="s-num" id="dash-proj-count">—</span><span class="s-lbl">المشاريع المشتركة</span></div>
        </div>
        <div class="stat-card" style="--sc:var(--purple)">
            <div class="s-icon" style="background:rgba(109,40,217,0.1)">✅</div>
            <div><span class="s-num" id="dash-completed-count">—</span><span class="s-lbl">إجمالي الطلبات المنجزة</span></div>
        </div>
    </div>

    <!-- Registration requests alert -->
    <div id="dash-pending-alert" style="display:none;align-items:center;gap:12px;background:rgba(245,158,11,0.08);border:1.5px solid rgba(245,158,11,0.22);border-radius:12px;padding:14px 18px;margin-bottom:1.5rem">
        <span style="font-size:1.3rem">📬</span>
        <div style="flex:1">
            <div style="font-size:.9rem;font-weight:800;color:#92400e">طلبات تسجيل جمعيات جديدة بانتظار المراجعة</div>
            <div id="dash-pending-text" style="font-size:.8rem;color:#b45309;margin-top:2px"></div>
        </div>
        <a href="#orders" onclick="if(typeof showSection==='function'){showSection('orders');return false;}" class="btn-primary" style="font-size:.82rem;padding:7px 14px;white-space:nowrap">مراجعة الطلبات</a>
    </div>

    <!-- Dashboard 2-col grid -->
    <div class="dash-grid">

        <!-- Meetings -->
        <div class="dash-widget">
            <div class="dw-header">
                <div class="dw-title"><i class="fa-regular fa-calendar-check"></i> الاجتماعات القادمة</div>
                <a href="#meetings" onclick="if(typeof showSection==='function'){showSection('meetings');return false;}" class="dw-link">عرض الكل</a>
            </div>
            <ul class="dw-list" id="dash-meetings-list">
                <p style="text-align:center; padding: 1rem; color: #888;">جاري التحميل...</p>
            </ul>
        </div>

        <!-- Projects -->
        <div class="dash-widget">
            <div class="dw-header">
                <div class="dw-title"><i class="fa-solid fa-chart-pie"></i> المشاريع المشتركة</div>
                <a href="#projects" onclick="if(typeof showSection==='function'){showSection('projects');return false;}" class="dw-link">عرض الكل</a>
            </div>
            <ul class="dw-list" id="dash-projects-list">
                <p style="text-align:center; padding: 1rem; color: #888;">جاري التحميل...</p>
            </ul>
        </div>

        <!-- Opportunities -->
        <div class="dash-widget">
            <div class="dw-header">
                <div class="dw-title"><i class="fa-solid fa-lightbulb"></i> فرص التطوع والدعم</div>
                <a href="#volunteer" onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}" class="dw-link">عرض الكل</a>
            </div>
            <ul class="dw-list" id="dash-opps-list">
                <p style="text-align:center; padding: 1rem; color: #888;">جاري التحميل...</p>
            </ul>
        </div>

        <!-- Requests — live from DB -->
        <div class="dash-widget">
            <div class="dw-header">
                <div class="dw-title"><i class="fa-solid fa-clipboard-list"></i> أحدث طلبات التسجيل</div>
                <a href="#orders" onclick="if(typeof showSection==='function'){showSection('orders');return false;}" class="dw-link">عرض الكل</a>
            </div>
            <ul class="dw-list" id="dash-recent-reqs"></ul>
        </div>

    </div><!-- /.dash-grid row 1 -->

    <!-- ══ ROW 2: Requests ══ -->
    <div class="dash-grid" style="margin-top:1.25rem">

        <!-- طلبات الفرص -->
        <div class="dash-widget">
            <div class="dw-header">
                <div class="dw-title"><i class="fa-solid fa-hand-holding-heart"></i> طلبات فرص التطوع</div>
                <a href="#volunteer" onclick="if(typeof showSection==='function'){showSection('volunteer');return false;}" class="dw-link">عرض الكل</a>
            </div>
            <ul class="dw-list" id="dash-opp-reqs-list">
                <p style="text-align:center; padding: 1rem; color: #888;">جاري التحميل...</p>
            </ul>
        </div>

        <!-- طلبات المشاريع -->
        <div class="dash-widget">
            <div class="dw-header">
                <div class="dw-title"><i class="fa-solid fa-diagram-project"></i> طلبات المشاريع المشتركة</div>
                <a href="#projects" onclick="if(typeof showSection==='function'){showSection('projects');return false;}" class="dw-link">عرض الكل</a>
            </div>
            <ul class="dw-list" id="dash-proj-apps-list">
                <p style="text-align:center; padding: 1rem; color: #888;">جاري التحميل...</p>
            </ul>
        </div>

    </div><!-- /.dash-grid row 2 -->

  </div>
        </div>{{-- /view-dashboard --}}
