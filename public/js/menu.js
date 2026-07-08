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
    if (typeof window._realToggleNotifs === 'function') {
        window._realToggleNotifs();
        return;
    }
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

// backToVolunteer — defined in spa-nav.js / user-spa-nav.js for pages using the SPA nav engine.
// (Kept out of menu.js to avoid redefinition conflicts; legacy standalone pages that still
//  need the old iframe-based behavior can call closeMeetingsPage() directly instead.)

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

// showService — defined in spa-nav.js / user-spa-nav.js for pages that load the SPA nav engine.
// (Kept out of menu.js to avoid redefinition conflicts on pages loading both scripts.)
