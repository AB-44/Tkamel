<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تكامل | منصة جمعيات مبادرون</title>
    <meta name="description"
        content="منصة تكامل تجمع جمعيات مبادرون في مكان واحد لتعزيز التطوع والمشاريع المشتركة ودعم القرارات الاستراتيجية.">

    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS for Landing Page -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container nav-content">
            <div class="brand">
                <img class="brand-logo" src="{{ asset('images/logo1.png') }}" alt="شعار تكامل">
            </div>

            <div class="nav-links">
                <a href="#about" class="nav-link">عن المنصة</a>
                <a href="#features" class="nav-link">المميزات</a>
                <a href="{{ route('login') }}" class="btn-primary-outline">
                    <i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="container hero-container">
            <div class="hero-content">
                <div class="badge">بمبادرة من جمعية مبادرون</div>
                <h1 class="hero-title">منصة واحدة تجمعنا،<br>
                    <span class="gradient-text">لأثر مستدام وأعمال مشتركة.</span>
                </h1>
                <p class="hero-subtitle">منصتنا لتوحيد جهود الجمعيات، حيث نتعاون في صنع الفرص التطوعية،
                    وإطلاق المشاريع المشتركة، وتنظيم اللقاءات وصنع القرارات الاستراتيجية.</p>

                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn-primary get-started-btn" id="hero-cta-btn">
                        ابدأ الآن <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <a href="#about" class="btn-secondary" id="hero-learn-btn">
                        <i class="fa-regular fa-circle-play"></i> تعرف على الفكرة
                    </a>
                </div>

                <!-- Stats -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-num">+20</span>
                        <span class="stat-label">مشروع مشترك</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">+50</span>
                        <span class="stat-label">فرصة تطوعية</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">+10</span>
                        <span class="stat-label">جمعيات شريكة</span>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="dashboard-mockup">
                    <div class="mockup-header">
                        <span class="dot red"></span>
                        <span class="dot yellow"></span>
                        <span class="dot green"></span>
                        <div class="mockup-header-title"></div>
                    </div>
                    <!-- Simulated Dashboard -->
                    <div class="mockup-body">
                        <div class="m-sidebar">
                            <div class="m-side-dot"></div>
                            <div class="m-side-dot"></div>
                            <div class="m-side-dot"></div>
                            <div class="m-side-dot"></div>
                        </div>
                        <div class="m-main">
                            <div class="m-header-bar"></div>
                            <div class="m-cards">
                                <div class="m-card c-teal"></div>
                                <div class="m-card c-green"></div>
                                <div class="m-card c-purple"></div>
                            </div>
                            <div class="m-chart"></div>
                        </div>
                    </div>
                </div>

                <!-- Floating Cards -->
                <div class="floating-card fl-1">
                    <div class="icon-wrap teal"><i class="fa-solid fa-handshake-angle"></i></div>
                    <div class="f-info">
                        <strong>شراكات ناجحة</strong>
                        <span>+20 مشروع مشترك</span>
                        <span class="live-dot">مباشر</span>
                    </div>
                </div>

                <div class="floating-card fl-2">
                    <div class="icon-wrap green"><i class="fa-solid fa-users"></i></div>
                    <div class="f-info">
                        <strong>فرص تطوعية</strong>
                        <span>جاهزة للمشاركة</span>
                        <span class="live-dot">نشطة</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container stats-grid">
            <div class="stat-card fade-up">
                <span class="stat-card-num">+20</span>
                <span class="stat-card-label">مشروع مشترك منجز</span>
            </div>
            <div class="stat-card fade-up fade-up-delay-1">
                <span class="stat-card-num">+50</span>
                <span class="stat-card-label">فرصة تطوعية</span>
            </div>
            <div class="stat-card fade-up fade-up-delay-2">
                <span class="stat-card-num">+10</span>
                <span class="stat-card-label">جمعيات شريكة</span>
            </div>
            <div class="stat-card fade-up fade-up-delay-3">
                <span class="stat-card-num">100%</span>
                <span class="stat-card-label">رقمي وآمن</span>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container about-container">
            <div class="about-text">
                <div style="display:flex;justify-content:center;margin-bottom:0.5rem;">
                    <span class="section-tag"><i class="fa-solid fa-lightbulb"></i> الفكرة والهدف</span>
                </div>
                <h2 class="section-title fade-up">فكرة الموقع <span>والهدف منه</span></h2>
                <p class="section-subtitle fade-up fade-up-delay-1">نسعى لتوحيد جميع الجمعيات المرتبطة بمبادرون
                    تحت سقف رقمي واحد، لتعزيز التعاون وتحقيق أثر مجتمعي حقيقي.</p>

                <div class="concept-boxes">
                    <div class="c-box fade-up">
                        <div class="c-icon"><i class="fa-solid fa-building-circle-check"></i></div>
                        <div>
                            <h3>الجمع تحت سقف واحد</h3>
                            <p>يجمع الموقع جميع الجمعيات المرتبطة بجمعية "مبادرون" في منصة رقمية واحدة لتسهيل
                                التواصل وإدارة الموارد.</p>
                        </div>
                    </div>
                    <div class="c-box fade-up fade-up-delay-1">
                        <div class="c-icon"><i class="fa-solid fa-bullseye"></i></div>
                        <div>
                            <h3>اتخاذ القرارات الفعالة</h3>
                            <p>نهدف إلى توفير بيئة متكاملة تتيح للمسؤولين اتخاذ قرارات مبنية على رؤية موحدة
                                وواضحة لجميع الجمعيات.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div id="features" style="padding-top: 4rem;">
                <div style="display:flex;justify-content:center;margin-bottom:0.5rem;">
                    <span class="section-tag"><i class="fa-solid fa-star"></i> المميزات</span>
                </div>
                <h2 class="section-title fade-up">كل ما تحتاجه <span>في مكان واحد</span></h2>
                <p class="section-subtitle fade-up fade-up-delay-1">مميزات متكاملة صُممت لتلبية احتياجات الجمعيات
                    في كل مرحلة من مراحل عملها.</p>

                <div class="about-features-grid" style="margin-top: 2.5rem;">
                    <!-- Feature 1 -->
                    <div class="feat-card teal-card fade-up" id="feat-volunteer">
                        <div class="feat-card-accent"></div>
                        <div class="fc-icon"><i class="fa-solid fa-hands-holding-child"></i></div>
                        <h3>توليد فرص التطوع</h3>
                        <p>طرح الفرص التطوعية المشتركة وتنظيم جهود المتطوعين بين كافة الجمعيات لتعظيم الأثر
                            المجتمعي.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="feat-card purple-card fade-up fade-up-delay-1" id="feat-projects">
                        <div class="feat-card-accent"></div>
                        <div class="fc-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <h3>المشاريع المشتركة</h3>
                        <p>مساحة لمشاركة المشاريع الجديدة والتعاون في إنجازها عبر توحيد الجهود وتقاسم الموارد
                            المتاحة.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="feat-card blue-card fade-up fade-up-delay-2" id="feat-meetings">
                        <div class="feat-card-accent"></div>
                        <div class="fc-icon"><i class="fa-regular fa-calendar-check"></i></div>
                        <h3>تنظيم الاجتماعات</h3>
                        <p>إدارة مواعيد الاجتماعات ومناقشة أمور التطوع واتخاذ القرارات الاستراتيجية في غرف
                            افتراضية موحدة.</p>
                    </div>
                    <!-- Feature 4 -->
                    <div class="feat-card green-card fade-up fade-up-delay-3" id="feat-reports">
                        <div class="feat-card-accent"></div>
                        <div class="fc-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h3>متابعة وتقارير</h3>
                        <p>توليد تقارير شاملة عن أداء الجمعيات ومستوى إنجاز المشاريع ونتائج الاجتماعات السابقة.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container cta-container">
            <div class="cta-badge"><i class="fa-solid fa-rocket"></i> انضم الآن</div>
            <h2>ابدأ رحلتك نحو التعاون والتكامل الحقيقي</h2>
            <p>تكامل الجمعيات هو خطوتنا الأولى نحو مستقبل تطوعي وإداري أفضل مع جمعية مبادرون. انضم اليوم.</p>
            <a href="{{ route('login') }}" class="btn-primary get-started-btn large" id="cta-main-btn">
                ابدأ رحلتك الآن <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2026 تكامل &mdash; إحدى المبادرات المدعومة من جمعية مبادرون. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <!-- Scroll animations + Navbar shrink -->
    <script>
        // Navbar shrink on scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Scroll fade-up animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    </script>

</body>

</html>