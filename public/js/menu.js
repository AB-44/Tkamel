/* ══ SIDEBAR ══ */
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('show');
    }
}

document.addEventListener('click', function (e) {
    const sidebar = document.querySelector('.sidebar');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    if (sidebar && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && (!menuBtn || !menuBtn.contains(e.target))) {
            sidebar.classList.remove('show');
        }
    }
});

/* ══ NOTIFICATIONS ══ */
function toggleNotifs() {
    const dot = document.querySelector('.notif-dot') || document.getElementById('notif-dot');
    if (dot) dot.style.display = 'none';
}

function showNotifBanner(title, sub) {
    const b = document.getElementById('assoc-notif-banner');
    if (!b) return;
    document.getElementById('notif-banner-title').textContent = title;
    document.getElementById('notif-banner-sub').textContent = sub;
    b.style.display = 'flex';
}

/* ══ MEETINGS PANEL ══ */
function openMeetingsPage() {
    const frame = document.getElementById('meetings-frame');
    if (frame && (!frame.src || frame.src === window.location.href)) {
        frame.src = 'takamol-meetings.html';
    }
    document.getElementById('meetings-panel')?.classList.add('open');
    document.getElementById('meetings-overlay')?.classList.add('open');
    document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
    document.getElementById('nav-meetings')?.classList.add('active');
}

function backToVolunteer() {
    closeMeetingsPage();
}

function closeMeetingsPage() {
    document.getElementById('meetings-panel')?.classList.remove('open');
    document.getElementById('meetings-overlay')?.classList.remove('open');
    document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
    document.querySelector('[data-vol]')?.classList.add('active');
}

/* ══ SERVICES SUBMENU ══ */
function toggleServices() {
    const parent = document.getElementById('np-services');
    const submenu = document.getElementById('submenu-services');
    if (!parent || !submenu) return;
    const isOpen = submenu.classList.contains('open');
    if (isOpen) {
        submenu.classList.remove('open');
        parent.classList.remove('open');
    } else {
        submenu.classList.add('open');
        parent.classList.add('open');
    }
}

function showService(key) {
    document.getElementById('meetings-panel')?.classList.remove('open');
    document.getElementById('meetings-overlay')?.classList.remove('open');

    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    const target = document.getElementById('view-' + key);
    if (target) target.classList.add('active');

    const labels = {
        units: 'بناء وحدات',
        systems: 'بناء أنظمة',
        initiatives: 'تنسيق مبادرات',
        training: 'تدريب تطوعي',
        consulting: 'الاستشارات',
        contact: 'التواصل معنا',
    };

    const titleEl = document.querySelector('.topbar-title') || document.getElementById('topbar-title');
    const crumbEl = document.querySelector('.topbar-crumb span') || document.getElementById('topbar-crumb');

    if (titleEl && crumbEl && labels[key]) {
        titleEl.textContent = labels[key];
        crumbEl.textContent = labels[key];
    }

    document.querySelectorAll('.nav-item, .nav-sub-item').forEach(el => el.classList.remove('active'));
    const subEl = document.getElementById('sub-' + key);
    if (subEl) subEl.classList.add('active');

    const submenu = document.getElementById('submenu-services');
    const parent = document.getElementById('np-services');
    if (submenu) submenu.classList.add('open');
    if (parent) parent.classList.add('open');
}
