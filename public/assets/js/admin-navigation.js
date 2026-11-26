const adminMenu = document.getElementById('admin-navigation');
const adminMenuToggle = document.querySelector('.admin-nav-toggle');
const compactAdminNav = window.matchMedia('(max-width: 800px)');

function setAdminMenu(open) {
    adminMenu.hidden = !open;
    adminMenuToggle.setAttribute('aria-expanded', String(open));
}

function syncAdminMenu() {
    const compact = compactAdminNav.matches;
    adminMenuToggle.hidden = !compact;
    if (compact && adminMenu.contains(document.activeElement)) adminMenuToggle.focus();
    setAdminMenu(!compact);
}

adminMenuToggle.addEventListener('click', () => setAdminMenu(adminMenu.hidden));
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && compactAdminNav.matches && !adminMenu.hidden
        && (adminMenu.contains(document.activeElement) || document.activeElement === adminMenuToggle)) {
        setAdminMenu(false);
        adminMenuToggle.focus();
    }
});
compactAdminNav.addEventListener('change', syncAdminMenu);
syncAdminMenu();
