<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تكامل | الجمعيات</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/consulting.css') }}">
    <style>
        /* Light Theme Card Styles */
        .assoc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .assoc-card {
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 20px;
            padding: 22px;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s;
            position: relative;
            overflow: hidden;
        }

        .assoc-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0284c7, #38bdf8);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .assoc-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.1);
        }

        .assoc-card:hover::before {
            opacity: 1;
        }

        .assoc-header {
            display: flex;
            gap: 14px;
            margin-bottom: 16px;
        }

        .assoc-logo {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            object-fit: cover;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        
        .assoc-logo-fallback {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #64748b;
            font-weight: 800;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
            font-family: Arial, sans-serif;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .assoc-title-wrap {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .assoc-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .assoc-desc {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-weight: 500;
        }

        .assoc-info {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 24px;
            background: #f8fafc;
            padding: 16px;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.95rem;
            color: #475569;
            font-weight: 500;
        }

        .info-row i {
            color: #94a3b8;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .info-row a, .info-row span {
            color: #475569;
            text-decoration: none;
            transition: color 0.2s;
            font-family: Arial, sans-serif; /* For english text alignment */
        }
        
        .info-row a[dir="rtl"] {
             font-family: inherit;
        }

        .info-row a:hover {
            color: #0284c7;
        }

        .assoc-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }

        .assoc-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #059669; /* Green to show active */
            font-weight: 700;
            background: rgba(16, 185, 129, 0.1);
            padding: 6px 12px;
            border-radius: 20px;
        }

        .assoc-actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background-color: #f8fafc;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 0.9rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-edit:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-delete {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 0.9rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-delete:hover {
            background-color: #ef4444;
            color: #fff;
        }
        
        /* Stats and Toolbar */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-num {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
        }

        .stat-lbl {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 600;
        }

        .toolbar {
            display: flex;
            gap: 16px;
            background: #fff;
            padding: 16px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            align-items: center;
            flex-wrap: wrap;
        }

        .search-wrap {
            position: relative;
            flex-grow: 1;
            min-width: 250px;
        }

        .search-wrap i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .search-input {
            width: 100%;
            padding: 14px 45px 14px 16px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-family: inherit;
            font-size: 0.95rem;
            color: #1e293b;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #38bdf8;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
        }

        .filter-select {
            padding: 14px 20px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-family: inherit;
            font-size: 0.95rem;
            color: #475569;
            font-weight: 600;
            cursor: pointer;
            min-width: 180px;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 16px center;
            background-size: 16px;
            padding-left: 40px;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 14px 28px;
            font-family: inherit;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.3);
        }

        .btn-clear {
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-clear:hover {
            background: #f1f5f9;
            color: #ef4444;
            border-color: #fca5a5;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar-admin', ['activeNav' => 'my-associations'])

    <!-- ══ MAIN ══ -->
    <div class="main">
        <!-- TOPBAR -->
        @include('layouts.topbar', ['title' => 'الجمعيات'])

        <div class="content">
            <!-- PAGE HEADER -->
            <div class="page-hd">
                <div>
                    <div class="ph-title">الجمعيات</div>
                    <div class="ph-sub" style="color: var(--muted); margin-top: 6px; font-size: 1rem;">إدارة كافة الجمعيات المضافة في النظام</div>
                </div>
            </div>

            @if(session('success'))
            <div style="background-color: rgba(16, 185, 129, 0.1); color: #059669; padding: 16px 24px; border-radius: 14px; margin-bottom: 24px; border: 1px solid rgba(16, 185, 129, 0.2); display: flex; align-items: center; gap: 12px; font-weight: 700;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i> {{ session('success') }}
            </div>
            @endif

            <!-- STATS -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </div>
                    <div>
                        <span class="stat-num">{{ $totalAssociations }}</span>
                        <span class="stat-lbl">إجمالي الجمعيات</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <span class="stat-num">{{ $recentAssociations }}</span>
                        <span class="stat-lbl">أُضيفت مؤخراً (30 يوم)</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <span class="stat-num">{{ count($categoriesCount) }}</span>
                        <span class="stat-lbl">تصنيفات نشطة</span>
                    </div>
                </div>
            </div>

            <!-- TOOLBAR -->
            <form class="toolbar" method="GET" action="{{ route('my-associations.index') }}">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="ابحث باسم الجمعية، البريد، الهاتف، التصنيف...">
                </div>
                
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">جميع التصنيفات</option>
                    @php
                        $availableCategories = [
                            'خيرية واجتماعية' => 'خيرية',
                            'ثقافية وتعليمية' => 'ثقافية',
                            'صحية وبيئية' => 'صحية',
                            'رياضية وشبابية' => 'رياضية',
                            'تنموية واقتصادية' => 'تنموية',
                            'دينية ودعوية' => 'دينية'
                        ];
                    @endphp
                    @foreach($availableCategories as $label => $val)
                        <option value="{{ $val }}" {{ request('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn-primary">
                    بحث
                </button>

                @if(request()->has('search') || request()->has('category'))
                <a href="{{ route('my-associations.index') }}" class="btn-clear" title="إلغاء التصفية">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </form>

            <!-- CARDS GRID -->
            <div class="assoc-grid">
                @forelse($associations as $assoc)
                <div class="assoc-card">
                    <div class="assoc-header">
                        @if(isset($assoc->logo_url) && $assoc->logo_url)
                            <img src="{{ $assoc->logo_url }}" alt="Logo" class="assoc-logo">
                        @else
                            <div class="assoc-logo-fallback">
                                {{ mb_substr($assoc->association_name, 0, 1) }}
                            </div>
                        @endif
                        <div class="assoc-title-wrap">
                            <div class="assoc-title">{{ $assoc->association_name }}</div>
                            <div class="assoc-desc">{{ $assoc->admin_notes ?: $assoc->category ?: 'جمعية مسجلة وموثقة ضمن شبكة تكامل التعاونية.' }}</div>
                        </div>
                    </div>

                    <div class="assoc-info">
                        @if($assoc->email)
                        <div class="info-row">
                            <i class="fa-regular fa-envelope"></i>
                            <a href="mailto:{{ $assoc->email }}" dir="ltr">{{ $assoc->email }}</a>
                        </div>
                        @endif
                        
                        @if($assoc->phone)
                        <div class="info-row">
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:{{ $assoc->phone }}" dir="ltr">{{ $assoc->phone }}</a>
                        </div>
                        @endif
                        
                        <div class="info-row">
                            <i class="fa-solid fa-globe"></i>
                            <a href="#" target="_blank" dir="ltr">https://{{ Str::slug($assoc->association_name ?: 'tkamel-assoc') }}.org</a>
                        </div>
                    </div>

                    <div class="assoc-footer">
                        <div class="assoc-status">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>تواصل متاح</span>
                        </div>
                        <div class="assoc-actions">
                            <button type="button" class="btn-edit" onclick="alert('تعديل الجمعية: {{ $assoc->association_name }}')">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <form action="{{ route('my-associations.destroy', $assoc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجمعية نهائياً؟');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fa-regular fa-trash-can"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 40px; background: #fff; border-radius: 24px; color: #94a3b8; border: 1px dashed #cbd5e1; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                    <i class="fa-solid fa-inbox" style="font-size: 4rem; margin-bottom: 20px; color: #cbd5e1;"></i>
                    <p style="font-size: 1.2rem; font-weight: 700; color: #475569; margin-bottom: 8px;">لا توجد جمعيات تطابق بحثك</p>
                    <p>حاول استخدام كلمات بحث مختلفة أو إلغاء التصفية</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    @include('layouts.notif-panel')

    <script src="{{ asset('js/menu.js') }}"></script>
</body>
</html>
