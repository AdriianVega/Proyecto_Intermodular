<?php
    // Iniciamos la sesión
    session_start();

    // Comprobamos si el usuario ha iniciado sesión
    if (!isset($_SESSION["nombre"]))
    {
        // Si no ha iniciado sesión, lo mandamos al index
        header("location: ../../index.php");
        die();
    }

    // Sacamos los datos de la sesión para mostrarlos en la cabecera
    $nombre = htmlspecialchars($_SESSION["nombre"]);
    $rol = $_SESSION["rol"];
    $icono = $_SESSION["icono"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo_menu.css">
    <title>Menú</title>
</head>
<body>
    <header>
        <img src="<?= $icono ?>" alt="Foto de perfil">
        <h1 class="my-3">Bienvenido <?= $nombre; ?> </h1>
            <?php
                // Metemos un badge diferente según si es admin o empleado
                if ($rol === 1) {
            ?>
                <h2 class="fs-4">
                    <span class="badge bg-danger">Administrador</span>
                </h2>
            <?php } else {
            ?>
                <h2 class="fs-4 mt-4">
                    <span class="badge bg-info"> Empleado </span>
                </h2>
            <?php }
            ?>

    </header>
    <main>
        </main>

    <script>
        // Guardamos el rol en un objeto global para que el script JS pueda leerlo
        const USER_CONFIG = {
            rol: <?= json_encode($_SESSION["rol"]) ?>,
        };
    </script>

    <script src="../js/secciones_menu.js"></script>
</body>
</html>
