<?php
    // Iniciamos la sesión
    session_start();
    
    // Si no está logueado, redirigimos al inicio
    if(!isset($_SESSION["nombre"])) {
        header("location:../index.php");
        die();
    }

    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Función para limpiar el nombre de la imagen
    function nombreImagen($str) {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);
        return trim($str, '-');
    }

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol= $_SESSION["rol"];
    $pagina_activa = "productos";

    // Si recibimos los datos del producto
    if(isset($_POST["nombre"]) && !empty($_POST["nombre"])) {
        
        $directorio = "../img/productos/";

        // Sacamos la ruta temporal y preparamos un nombre único
        $archivo_temporal = $_FILES["imagen"]["tmp_name"];
        $archivo_original = uniqid().$_FILES["imagen"]["name"];
        $extension = pathinfo($archivo_original, PATHINFO_EXTENSION);

        // Generamos el nombre final basado en el producto y el timestamp
        $nuevo_nombre = nombreImagen($_POST["nombre"]) . "_" . time() . "." . $extension;

        // Comprobamos si se ha subido bien la imagen
        if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] != 0)
        {
            $nuevo_nombre = "fondo.jpg";
        }
        // Comprobamos que el archivo sea una imagen real
        elseif (getimagesize($archivo_temporal))
        {
            // Colocamos la ruta donde vamos a guardar la foto
            $ruta_final = $directorio . $nuevo_nombre;

            // Movemos el archivo de la carpeta temporal a la final
            if(move_uploaded_file($archivo_temporal, $directorio . $nuevo_nombre))
            {
                echo "<h1> $nuevo_nombre </h1>";
                echo "<img src='$ruta_final' alt='$archivo_original'>";
            }
            else
            {
                $error = "Se ha producido un error subiendo el icono";
            }
        }
        else
        {
            $error = "Sólo se permiten imagenes";
        }
        
        // Limpiamos los datos del formulario para evitar inyecciones SQL
        $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
        $descripcion = mysqli_real_escape_string($conn, $_POST["descripcion"]);
        $precio = floatval($_POST["precio"]);
        $stock = intval($_POST["stock"]);
        $categoria_id = intval($_POST["categoria_id"]);
        $estado = intval($_POST["estado"]);
        
        try {
                // Metemos el nuevo producto en la base de datos
                $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen, estado)
                VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$categoria_id', '$nuevo_nombre', '$estado');";

                // Ejecutamos la inserción
                mysqli_query($conn, $sql);

                // Redirigimos a la gestión con mensaje de éxito
                header("location:gestion_productos.php?msg=0");
            }
            catch (mysqli_sql_exception $e) {
                header("location:gestion_productos.php?msg=error");
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
    <title>Nuevo Producto</title>
</head>
<body class="bg-light">
    <?php include "../php/panel_control.php"; ?>
    
    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h2 class="h4 mb-0">📦 Añadir Producto</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">ID de Categoría</label>
                            <input type="number" class="form-control" id="categoria_id" name="categoria_id" required>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="precio" class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>

                        <div class="col-md-4">
                            <label for="stock" class="form-label">Stock (Unidades)</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="imagen" class="form-label">Nombre del archivo de imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen">
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Producto</button>
                            <a href="gestion_productos.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
