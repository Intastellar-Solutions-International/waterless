const mainHeader = document.querySelector('.main-header');
const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
const dropdownMenus = document.querySelectorAll('.dropdown-menu');
/* Fixed Header */
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        mainHeader.classList.add('--fixed');
    } else {
        mainHeader.classList.remove('--fixed');
    }
});

/* Dropdown Menu */
dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const menu = e.currentTarget.nextElementSibling;
        if (menu.classList.contains('show')) {
            menu.classList.remove('show');
        } else {
            dropdownMenus.forEach(m => m.classList.remove('show'));
            menu.classList.add('show');
        }
    });
});