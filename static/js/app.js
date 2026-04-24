const burgerBtn = document.getElementById('burgerBtn');
const sidebar   = document.getElementById('sidebar');
const overlay   = document.getElementById('navOverlay');
const closeBtn  = document.getElementById('sidebarClose');

function openSidebar() {
    sidebar.classList.add('is-open');
    overlay.classList.add('is-open');
}

function closeSidebar() {
    sidebar.classList.remove('is-open');
    overlay.classList.remove('is-open');
}

burgerBtn.addEventListener('click', openSidebar);
closeBtn.addEventListener('click', closeSidebar);
overlay.addEventListener('click', closeSidebar);