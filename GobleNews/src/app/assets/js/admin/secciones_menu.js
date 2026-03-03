// Sacamos el elemento main donde vamos a meter las secciones
const main = document.getElementsByTagName("main")[0];

// Definimos todas las secciones del menú con su ruta e icono
const secciones = [
    { titulo: "Noticias", imagen: "../../img/admin/menu/noticias.svg", ruta: "../noticias/gestion_noticias.php"},
    { titulo: "Paises", imagen: "../../img/admin/menu/pais.svg", ruta: "../paises/gestion_paises.php"},
    { titulo: "Medios", imagen: "../../img/admin/menu/medio.svg", ruta: "../medios/gestion_medios.php"},
    { titulo: "Categorías", imagen: "../../img/admin/menu/lista.svg", ruta: "../categorias/gestion_categorias.php"},
    { titulo: "Usuarios", imagen: "../../img/admin/menu/usuario.svg", ruta: "../usuarios/gestion_usuarios.php"},
    { titulo: "Configuración", imagen: "../../img/admin/menu/ajuste.svg", ruta: "../configuracion/configuracion.php"},
    { titulo: "Cerrar Sesión", imagen: "../../img/admin/menu/logout.svg", ruta: "../../php/admin/logout.php"},
]

// Recorremos el array para crear cada tarjeta del menú
for (let seccion of secciones) {

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