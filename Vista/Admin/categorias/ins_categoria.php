<?php
    // Iniciamos la sesión
    session_start();

    // Comprobamos si el usuario está logueado
    if(!isset($_SESSION["nombre"])) {
        header("location:../index.php");
        die();
    }

    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $pagina_activa = "categorias";

    // Si recibimos el nombre de la categoría
    if(isset($_POST["nombre"]) && !empty($_POST["nombre"])) {
        
        // Recogemos el nombre limpiando el string para evitar inyecciones SQL
        $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
        $estado = intval($_POST["estado"]);
        
        // Comprobamos si ya existe una categoría con el mismo nombre
        $sql_check = "SELECT * FROM categorias WHERE nombre='$nombre'";
        $res = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($res) > 0)
        {
            // Si el nombre ya existe, mandamos error
            header("location:gestion_categorias.php?msg=error");
            die();
        }
        
        try {
                // Metemos la nueva categoría en la base de datos
                $sql = "INSERT INTO categorias (nombre, estado)
                VALUES ('$nombre', '$estado');";

                // Ejecutamos la consulta
                mysqli_query($conn, $sql);

                // Redirigimos a la gestión de categorías con mensaje de éxito
                header("location:gestion_categorias.php?msg=0");
            }
            catch (mysqli_sql_exception $e) {
                header("location:gestion_categorias.php?msg=error");
            }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tablas.css">
    <title>Nueva Categoría</title>
</head>
<body class="bg-light">

    <?php include "../php/panel_control.php"; ?>
    
    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h2 class="h4 mb-0">🏷️ Añadir Categoría</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        
                        <div class="col-md-8">
                            <label for="nombre" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Categoría</button>
                            <a href="gestion_categorias.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>