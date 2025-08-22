const mainHeader = document.querySelector('.main-header');
const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
const dropdownMenus = document.querySelectorAll('.dropdown-menu');

const hamburgerMenu = document.getElementById('hamburger-menu');
const menu = document.querySelector('.menu');

hamburgerMenu.addEventListener('click', () => {
    menu.classList.toggle('show');
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