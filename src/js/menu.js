document.addEventListener('DOMContentLoaded', () =>{
   
});

//variables
const menuHamburguesa = document.querySelector('.header-hamburguesa');
const menuabrir = document.querySelector('.menu-perfil');


//evet 
menuHamburguesa.addEventListener('click', abrirMenu);

//funciones
function abrirMenu(){
    menuabrir.classList.toggle('menu-perfil__abrir');
}





