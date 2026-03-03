const navMenu = document.getElementById('nav-menu');
const menuLateral = document.getElementById('menu-lateral');
const overlay = document.getElementById('overlay');
const cerrarMenu = document.getElementById('cerrar-menu');

navMenu.addEventListener('click', (e) => {
    e.stopPropagation();
    menuLateral.classList.add('menu-visible');
    overlay.classList.add('overlay-visible');
});

overlay.addEventListener('click', () => {
    menuLateral.classList.remove('menu-visible');
    overlay.classList.remove('overlay-visible');
});

cerrarMenu.addEventListener('click', () => {
    menuLateral.classList.remove('menu-visible');
    overlay.classList.remove('overlay-visible');
});

menuLateral.addEventListener('click', (e) => {
    e.stopPropagation();
});

const dropdownItems = document.querySelectorAll('#menu-lateral nav > ul > li > span');

dropdownItems.forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const parentLi = this.parentElement;
        
        parentLi.classList.toggle('dropdown-abierto');
    });
});