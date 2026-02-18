<?php
    // Configuramos los errores para que se muestren por pantalla
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Iniciamos la sesión
    session_start();

    // Comprobamos si el usuario está logueado
    if(!isset($_SESSION["nombre"])) {
        header("location:../index.php");
        die();
    }

    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Función para limpiar el nombre de la imagen y evitar caracteres raros
    function nombreImagen($str) {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);
        return trim($str, '-');
    }

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $pagina_activa = "productos";

    // Si recibimos la acción de editar desde el formulario
    if (isset($_POST["accion"]) && $_POST["accion"] == "editar") {

        $directorio = "../img/productos/";

        // Recogemos el id del producto
        $id = intval($_POST["id"]);

        // Sacamos la imagen actual por si no se cambia
        $sql = "SELECT imagen FROM productos WHERE id = $id";
        $res_icono = mysqli_query($conn, $sql);
        $datos = mysqli_fetch_assoc($res_icono);

        // Guardamos la ruta por defecto
        $ruta_final = $datos["imagen"];

        // Miramos si se ha subido una imagen nueva
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0)
        {
            // Sacamos la ruta temporal y preparamos el nombre único
            $archivo_temporal = $_FILES["imagen"]["tmp_name"];
            $archivo_original = uniqid().$_FILES["imagen"]["name"];
            $extension = pathinfo($archivo_original, PATHINFO_EXTENSION);

            // Comprobamos que sea una imagen de verdad
            if (getimagesize($archivo_temporal))
            {
                // Colocamos el nuevo nombre basado en el producto y el tiempo
                $nuevo_nombre = nombreImagen($_POST["nombre"]) . "_" . time() . "." . $extension;
                $ruta_final = $directorio . $nuevo_nombre;

                // Movemos la foto de la carpeta temporal a la final
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
        }
        
        // Si recibimos el nombre, procedemos a actualizar
        if(isset($_POST["nombre"]) && !empty($_POST["nombre"])) {

            // Recogemos los datos del formulario limpiando strings para evitar inyecciones SQL
            $id = intval($_POST["id"]);
            $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
            $descripcion = mysqli_real_escape_string($conn, $_POST["descripcion"]);
            $precio = floatval($_POST["precio"]);
            $stock = intval($_POST["stock"]);
            $categoria_id = intval($_POST["categoria_id"]);
            $estado = intval($_POST["estado"]);

            try {
                // Montamos la consulta para actualizar los datos
                $sql = "UPDATE productos SET
                    nombre = '$nombre',
                    descripcion = '$descripcion',
                    precio = $precio,
                    stock = $stock,
                    categoria_id = $categoria_id, ";
                
                // Si hay una imagen nueva, la metemos en la consulta
                if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
                    $sql .= "imagen = '$nuevo_nombre', ";
                }

                $sql .= "estado = $estado WHERE id = $id";

                // Ejecutamos la actualización
                mysqli_query($conn, $sql);

                // Redirigimos a la gestión con mensaje de éxito
                header("location:gestion_productos.php?msg=0");
            }
            catch (mysqli_sql_exception $e) {
                header("location:gestion_productos.php?msg=error");
            }

            die();
        }
    }

    // Si no recibimos el id por GET, volvemos a la lista
    if(!isset($_GET["id"])) {
        header("location:gestion_productos.php");
        die();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tablas.css">
    <title>Editar Producto</title>
</head>
<body class="bg-light">

    <?php include "../php/panel_control.php"; ?>

    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">✏️ Editar Producto</h2>
            </div>
            <div class="card-body">

            <?php
                // Sacamos los datos del producto para rellenar el formulario
                $id = intval($_GET["id"]);
                $sql = "SELECT * FROM productos WHERE id = $id";
                $res = mysqli_query($conn, $sql);
                
                // Si existe el producto, recogemos los datos, si no, redirigimos
                if (mysqli_num_rows($res) > 0) {
                    $prod = mysqli_fetch_assoc($res);
                } else {
                    header("location:gestion_productos.php");
                    die();
                }
            ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="accion" value="editar">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Producto:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($prod["nombre"]) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">ID Categoría:</label>
                            <input type="number" class="form-control" id="categoria_id" name="categoria_id"
                                value="<?= $prod["categoria_id"] ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required><?= htmlspecialchars($prod["descripcion"]) ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="precio" class="form-label">Precio (€):</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio"
                                value="<?= $prod["precio"] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="stock" class="form-label">Stock (uds):</label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                value="<?= $prod["stock"] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado:</label>
                            <select name="estado" id="estado" class="form-select" required>
                                <?php
                                    // Comprobamos el estado actual para marcar el select
                                    $est = $prod['estado'];
                                    echo '<option value="1" ' . ($est == 1 ? 'selected' : '') . '>Activo</option>';
                                    echo '<option value="0" ' . ($est == 0 ? 'selected' : '') . '>Inactivo</option>';
                                ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="imagen" class="form-label">Nombre archivo imagen:</label>
                            <input type="file" class="form-control" id="imagen" name="imagen">
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
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
