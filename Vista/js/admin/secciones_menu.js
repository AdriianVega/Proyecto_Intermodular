// Sacamos el elemento main donde vamos a meter las secciones
const main = document.getElementsByTagName("main")[0];

// Definimos todas las secciones del menú con su ruta e icono
const secciones = [
    { titulo: "Productos", imagen: "../img/menu/caja.svg", ruta: "../productos/gestion_productos.php", admin: false},
    { titulo: "Clientes", imagen: "../img/menu/personas.svg", ruta: "../clientes/gestion_clientes.php", admin: false},
    { titulo: "Pedidos", imagen: "../img/menu/entrega-de-pedidos.svg", ruta: "../pedidos/gestion_pedidos.php", admin: false},
    { titulo: "Categorías", imagen: "../img/menu/lista.svg", ruta: "../categorias/gestion_categorias.php", admin: false},
    { titulo: "Usuarios", imagen: "../img/menu/usuario.svg", ruta: "../usuarios/gestion_usuarios.php", admin: true},
    { titulo: "Configuración", imagen: "../img/menu/ajuste.svg", ruta: "../configuracion/configuracion.php", admin: false},
    { titulo: "Cerrar Sesión", imagen: "../img/menu/logout.svg", ruta: "../php/logout.php", admin: false},
]

// Recorremos el array para crear cada tarjeta del menú
for (let seccion of secciones) {

    // Comprobamos si la sección es solo para admin y si el usuario actual lo es
    if (seccion.admin && USER_CONFIG.rol != 1) {
        continue;
    }

    // Creamos el enlace que envuelve a la sección
    const enlace = document.createElement("a");
    enlace.href = seccion.ruta;

    // Creamos el contenedor de la sección
    const nuevaSeccion = document.createElement("article");

    // Metemos la imagen correspondiente
    const imagen = document.createElement("img");
    imagen.src = seccion.imagen

    // Colocamos el título
    const titulo = document.createElement("h3");
    titulo.textContent = seccion.titulo;

    // Metemos los elementos dentro del article
    nuevaSeccion.appendChild(imagen);
    nuevaSeccion.appendChild(titulo);

    // Metemos el article dentro del enlace
    enlace.appendChild(nuevaSeccion);

    // Añadimos todo al contenedor main
    main.appendChild(enlace)
}