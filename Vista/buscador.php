<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/secundaria.css">
    <title>GobleNews - Inicio</title>
</head>

<body>
    <header>
        <button id="nav-menu">
            <svg aria-hidden="true">
                <use href="img/sprites.svg#icon-menu"></use>
            </svg>
        </button>
        <a href="index.html">
            <img src="img/logo_tierra.png" alt="Logo GobleNews" class="logo">
        </a>
    </header>

    <aside id="menu-lateral" class="menu-oculto">
        <button id="cerrar-menu" class="btn-cerrar">✕</button>
    
        <nav>
            <ul>
                <li class="buscador">
                    <form action="buscador.php?search=" method="get">
                        <label for="search">Buscar</label>
                        <input type="text" id="search" name="search" placeholder="Buscar" required>
                        <button type="submit" class="btn-buscar">
                            <svg>
                                <use href="img/sprites.svg#btn-buscador"></use>
                            </svg>
                        </button>
                    </form>
                </li>
                
                <li>
                    <span class="dropdown">Países</span>
    
                    <ul>
                        <li><a href="buscador.php?search=america">América</a></li>
                        <li><a href="buscador.php?search=europa">Europa</a></li>
                        <li><a href="buscador.php?search=asia">Asia</a></li>
                        <li><a href="buscador.php?search=africa">África</a></li>
                        <li><a href="buscador.php?search=oceania">Oceanía</a></li>
                    </ul>
                </li>
                <li>
                    <span class="dropdown">Categorías</span>
                    <ul>
                        <li><a href="buscador.php?search=tecnologia">Tecnología</a></li>
                        <li><a href="buscador.php?search=ciencia">Ciencia</a></li>
                        <li><a href="buscador.php?search=salud">Salud</a></li>
                        <li><a href="buscador.php?search=deportes">Deportes</a></li>
                        <li><a href="buscador.php?search=entretenimiento">Entretenimiento</a></li>
                    </ul>
                </li>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Cookies</a></li>
            </ul>
        </nav>
    </aside>

    <div id="overlay"></div>

    <div class="page-scroll">
        <main id="scroll-container">
            <section>
                <article>
                    <?php
                        $busqueda = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $busquedaSegura = htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8');

                        if ($busquedaSegura !== '') {
                            $busquedaSegura = mb_strtoupper(mb_substr($busquedaSegura, 0, 1, 'UTF-8'), 'UTF-8')
                                . mb_substr($busquedaSegura, 1, null, 'UTF-8');
                        }
                    ?>
                    <h1>Resultados para "<?= $busquedaSegura ?>"</h1>
                </article>

                <article>
                    <h3>Resultados: 0 de 0</h3>
                </article>

                <article class="navegador">
                    <button class="before" type="button">
                        <svg aria-hidden="true">
                            <use href="img/sprites.svg#icon-previous"></use>
                        </svg>
                    </button>

                    <span>Página 0 de 0</span>

                    <button class="next" type="button">
                        <svg aria-hidden="true">
                            <use href="img/sprites.svg#icon-next"></use>
                        </svg>
                    </button>
                </article>
            </section>
        </main>

        <footer class="fade-in-element">

            <section>
                <article>
                    <a href="index.html">
                        <img src="img/logo_tierra.png" alt="Logo GobleNews" class="logo">
                    </a>

                    <div>
                        <div>
                            <p>Países</p>

                            <ul>
                                <li><a href="buscador.php?pais=america">América</a></li>
                                <li><a href="buscador.php?pais=europa">Europa</a></li>
                                <li><a href="buscador.php?pais=asia">Asia</a></li>
                                <li><a href="buscador.php?pais=africa">África</a></li>
                                <li><a href="buscador.php?pais=oceania">Oceanía</a></li>
                            </ul>
                        </div>

                        <div>
                            <p>Categoría</p>

                            <ul>
                                <li><a href="buscador.php?categoria=politica">Política</a></li>
                                <li><a href="buscador.php?categoria=economia">Economía</a></li>
                                <li><a href="buscador.php?categoria=tecnologia">Tecnología</a></li>
                                <li><a href="buscador.php?categoria=deportes">Deportes</a></li>
                                <li><a href="buscador.php?categoria=cultura">Cultura</a></li>
                            </ul>
                        </div>

                        <div>
                            <p>Sobre Nosotros</p>

                            <ul>
                                <li><a href="#">Gmail</a></li>
                                <li><a href="#">Teléfono</a></li>
                            </ul>
                        </div>

                        <div>
                            <p>Últimas Noticias</p>
                        </div>

                        <div>
                            <p>Cookies</p>
                        </div>
                    </div>

                </article>

                <hr>

                <article>
                    <p>© 2026 GobleNews. Todos los derechos reservados.</p>

                    <div>
                        <a href="#">
                            <svg>
                                <use href="img/sprites.svg#icon-instagram"></use>
                            </svg>
                        </a>
                        <a href="#">
                            <svg>
                                <use href="img/sprites.svg#icon-facebook"></use>
                            </svg>
                        </a>
                        <a href="#">
                            <svg>
                                <use href="img/sprites.svg#icon-linkedin"></use>
                            </svg>
                        </a>
                        <a href="#">
                            <img src="img/icons8-x-50.png" alt="Twitter logo">
                        </a>
                    </div>
                </article>
            </section>
        </footer>
    </div>

    <script src="js/efecto-scroll.js"></script>
    <script src="js/menu-lateral.js"></script>
</body>

</html>
