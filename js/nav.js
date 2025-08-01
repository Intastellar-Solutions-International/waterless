const mainHeader = document.querySelector('.main-header');
/* Fixed Header */
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        mainHeader.classList.add('--fixed');
    } else {
        mainHeader.classList.remove('--fixed');
    }
});