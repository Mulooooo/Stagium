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

document.getElementById('cv')?.addEventListener('change', function() {
    document.getElementById('cv-label').textContent = this.files[0].name;
});

document.getElementById('lm')?.addEventListener('change', function() {
    document.getElementById('lm-label').textContent = this.files[0].name;
});

document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        const maxSize = 2 * 1024 * 1024;
        if (this.files[0] && this.files[0].size > maxSize) {
            alert('Fichier trop volumineux. Veuillez sélectionner un fichier de moins de 2Mo.');
            this.value = '';
        }
        if (this.files[0] && this.files[0].type !== 'application/pdf') {
            alert('Le fichier doit être un PDF.');
            this.value = '';
        }
    });
});