        <div class="view" id="view-admin-reqs">
          <div class="page-hd">
            <div>
              <div class="ph-title">طلبات التقديم</div>
              <div class="ph-sub">مراجعة طلبات الجمعيات والبت فيها</div>
            </div>
            <button class="back-btn" onclick="showAdminMain()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path d="M19 12H5M12 5l7 7-7 7" />
              </svg>
              فرص التطوع
            </button>
          </div>

          <div class="req-msg-card">
            <div class="req-msg-icon"><i class="fa-solid fa-inbox"></i></div>
            <div>
              <div class="req-msg-title">صلاحية الأدمن</div>
              <div class="req-msg-sub">أنت وحدك من يملك صلاحية قبول أو رفض طلبات الجمعيات. تأتي الطلبات تلقائياً عند
                تقديم جمعية طلب تطوع.</div>
            </div>
          </div>

          <div class="req-tabs">
            <button class="req-tab active" id="rtab-pending" onclick="filterReqs('pending')">⏳ معلقة <span class="rc"
                id="rc-pending">0</span></button>
            <button class="req-tab" id="rtab-approved" onclick="filterReqs('approved')">✅ مقبولة <span class="rc"
                id="rc-approved">0</span></button>
            <button class="req-tab" id="rtab-rejected" onclick="filterReqs('rejected')">❌ مرفوضة <span class="rc"
                id="rc-rejected">0</span></button>
          </div>

          <div class="req-list" id="req-list"></div>
        </div>
