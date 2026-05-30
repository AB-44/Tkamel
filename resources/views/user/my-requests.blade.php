<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تكامل — طلباتي</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/user-dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/user-my-requests.css') }}?v={{ time() }}">
</head>

<body>

    <!-- ══ SIDEBAR ══ -->
    @include('layouts.sidebar-user', ['activeNav' => 'orders'])

    <!-- ══ MAIN ══ -->
    <div class="main-wrapper">

        <!-- TOPBAR -->
        @include('layouts.topbar', ['title' => 'طلباتي', 'crumb' => 'الرئيسية / طلباتي'])

        <!-- CONTENT -->
        <div class="content mr-content">

            <!-- PAGE HEADER -->
            <div class="mr-header">
                <h1 class="mr-title">طلباتي</h1>
                <p class="mr-sub">جميع طلبات التقديم التي أرسلتها - فرص التطوع والمشاريع المشتركة</p>
            </div>

            <!-- STATS CARDS -->
            <div class="mr-stats-grid">
                <div class="mr-stat-card" style="border-right-color: #0ea5c9;">
                    <div class="mr-stat-icon" style="background: rgba(14, 165, 201, 0.1); border-radius: 12px; font-size: 1.6rem; color: #0ea5c9;">
                        📋
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['total_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">إجمالي الطلبات</div>
                    </div>
                </div>
                <div class="mr-stat-card" style="border-right-color: #f59e0b;">
                    <div class="mr-stat-icon" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; font-size: 1.6rem; color: #f59e0b;">
                        ⏳
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['pending_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">قيد المراجعة</div>
                    </div>
                </div>
                <div class="mr-stat-card" style="border-right-color: #10b981;">
                    <div class="mr-stat-icon" style="background: rgba(16, 185, 129, 0.1); border-radius: 12px; font-size: 1.6rem; color: #10b981;">
                        ✅
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['approved_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">مقبولة</div>
                    </div>
                </div>
                <div class="mr-stat-card" style="border-right-color: #ef4444;">
                    <div class="mr-stat-icon" style="background: rgba(239, 68, 68, 0.1); border-radius: 12px; font-size: 1.6rem; color: #ef4444;">
                        ❌
                    </div>
                    <div class="mr-stat-text-group">
                        <div class="mr-stat-val">{{ $stats['rejected_requests'] ?? 0 }}</div>
                        <div class="mr-stat-lbl">مرفوضة</div>
                    </div>
                </div>
            </div>

            <!-- TOOLBAR -->
            <div class="mr-toolbar">
                <div class="mr-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="mrSearchInput" placeholder="ابحث في طلباتك...">
                </div>
                <div class="mr-sep"></div>
                <div class="mr-chips">
                    <button class="mr-chip active" data-type="all">الكل</button>
                    <button class="mr-chip" data-type="opportunity"><i class="fa-solid fa-user"></i> فرص التطوع</button>
                    <button class="mr-chip" data-type="project"><i class="fa-regular fa-star"></i> المشاريع</button>
                    <button class="mr-chip" data-type="service"><i class="fa-solid fa-suitcase-medical"></i> خدمات مبادرون</button>
                </div>
                <div class="mr-filter-drop">
                    <select id="mrStatusFilter">
                        <option value="all">كل الحالات</option>
                        <option value="pending">قيد المراجعة</option>
                        <option value="approved">مقبولة</option>
                        <option value="rejected">مرفوضة</option>
                    </select>
                </div>
            </div>

            <!-- TABS -->
            <div class="mr-tabs">
                <button class="mr-tab active" data-tab="all">
                    <i class="fa-solid fa-layer-group"></i> جميع الطلبات
                    <span class="mr-tab-badge">{{ $stats['total_requests'] ?? 0 }}</span>
                </button>
                <button class="mr-tab" data-tab="opportunity">
                    <i class="fa-solid fa-user-tie"></i> فرص التطوع
                    <span class="mr-tab-badge">{{ $allRequests->where('type', 'opportunity')->count() }}</span>
                </button>
                <button class="mr-tab" data-tab="project">
                    <i class="fa-solid fa-diagram-project"></i> المشاريع المشتركة
                    <span class="mr-tab-badge">{{ $allRequests->where('type', 'project')->count() }}</span>
                </button>
                <button class="mr-tab" data-tab="service">
                    <i class="fa-solid fa-suitcase-medical"></i> خدمات مبادرون
                    <span class="mr-tab-badge">{{ $allRequests->where('type', 'service')->count() }}</span>
                </button>
            </div>

            <!-- REQUESTS LIST / EMPTY STATE -->
            <div class="mr-list-container" id="requests-list">
                @forelse($allRequests as $req)
                    @php
                        $statusClass = '';
                        $statusLabel = '';
                        if (in_array($req->status, ['pending', 'review'])) {
                            $statusClass = 'status-pending';
                            $statusLabel = 'قيد المراجعة';
                            $dataStatus = 'pending';
                        } elseif (in_array($req->status, ['approved', 'completed'])) {
                            $statusClass = 'status-approved';
                            $statusLabel = 'مقبول';
                            $dataStatus = 'approved';
                        } else {
                            $statusClass = 'status-rejected';
                            $statusLabel = 'مرفوض';
                            $dataStatus = 'rejected';
                        }
                    @endphp
                    <div class="mr-req-card" data-status="{{ $dataStatus }}" data-type="{{ $req->type }}">
                        <div class="mr-req-icon" style="background: {{ $req->color }}1a; color: {{ $req->color }};">
                            <i class="fa-solid {{ $req->icon }}"></i>
                        </div>
                        <div class="mr-req-info">
                            <div class="mr-req-title">{{ $req->title }}</div>
                            <div class="mr-req-sub">{{ $req->sub }}</div>
                        </div>
                        <div class="mr-req-date">{{ \Carbon\Carbon::parse($req->date)->translatedFormat('d M Y') }}</div>
                        <div class="mr-req-status">
                            <span class="mr-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                @empty
                    <div class="mr-empty">
                        <img src="{{ asset('images/mailbox.png') }}" alt="Empty Mailbox" style="width: 80px; margin-bottom: 1rem; opacity: 0.8;" onerror="this.outerHTML='<div style=\'font-size:4rem; margin-bottom: 1rem;\'>📫</div>'">
                        <h3>لا توجد طلبات</h3>
                        <p>لم تقدم أي طلبات تطابق الفلتر الحالي</p>
                    </div>
                @endforelse
                
                <!-- Client Side Empty State (Hidden initially) -->
                <div class="mr-empty" id="client-empty" style="display: none;">
                    <div style="font-size:4rem; margin-bottom: 1rem;">📫</div>
                    <h3>لا توجد طلبات</h3>
                    <p>لم تقدم أي طلبات تطابق الفلتر الحالي</p>
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    @include('layouts.notif-panel-user')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('mrSearchInput');
            const statusFilter = document.getElementById('mrStatusFilter');
            const chips = document.querySelectorAll('.mr-chip');
            const tabs = document.querySelectorAll('.mr-tab');
            const items = document.querySelectorAll('.mr-req-card');
            const emptyState = document.getElementById('client-empty');
            const serverEmpty = document.querySelector('.mr-empty:not(#client-empty)');

            let currentType = 'all';
            let currentStatus = 'all';
            let currentQuery = '';

            function filterItems() {
                let visibleCount = 0;

                items.forEach(item => {
                    const typeMatch = currentType === 'all' || item.dataset.type === currentType;
                    const statusMatch = currentStatus === 'all' || item.dataset.status === currentStatus;
                    
                    const title = item.querySelector('.mr-req-title').textContent.toLowerCase();
                    const sub = item.querySelector('.mr-req-sub').textContent.toLowerCase();
                    const queryMatch = currentQuery === '' || title.includes(currentQuery) || sub.includes(currentQuery);

                    if (typeMatch && statusMatch && queryMatch) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (serverEmpty) {
                    serverEmpty.style.display = 'none';
                }

                if (visibleCount === 0 && items.length > 0) {
                    emptyState.style.display = 'flex';
                } else {
                    emptyState.style.display = 'none';
                }
            }

            // Sync Chips and Tabs
            function setTypeFilter(type) {
                currentType = type;
                
                chips.forEach(c => c.classList.toggle('active', c.dataset.type === type));
                tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === type));
                
                filterItems();
            }

            chips.forEach(chip => {
                chip.addEventListener('click', () => {
                    setTypeFilter(chip.dataset.type);
                });
            });

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    setTypeFilter(tab.dataset.tab);
                });
            });

            statusFilter.addEventListener('change', (e) => {
                currentStatus = e.target.value;
                filterItems();
            });

            searchInput.addEventListener('input', (e) => {
                currentQuery = e.target.value.toLowerCase();
                filterItems();
            });
        });
    </script>
</body>

</html>
