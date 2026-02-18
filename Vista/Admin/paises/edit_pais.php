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

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $pagina_activa = "pedidos";

    // Si recibimos la acción de editar desde el formulario
    if (isset($_POST["accion"]) && $_POST["accion"] == "editar") {
        
        // Comprobamos si se ha mandado el formulario
        if(isset($_POST["id"]) && !empty($_POST["id"])) {

            // Recogemos los datos del formulario
            $id = intval($_POST["id"]);
            $cliente_id = intval($_POST["cliente_id"]);
            $producto_id = intval($_POST["producto_id"]);

            try {
                // Actualizamos los datos del pedido
                $sql = "UPDATE pedidos SET
                        cliente_id = $cliente_id,
                        producto_id = $producto_id
                    WHERE id = $id";

                // Ejecutamos la consulta
                mysqli_query($conn, $sql);

                // Redirigimos a la gestión de pedidos con mensaje de éxito
                header("location:gestion_pedidos.php?msg=0");
            }
            catch (mysqli_sql_exception $e) {
                header("location:gestion_pedidos.php?msg=error");
            }

            die();
        }
    }

    // Si no recibimos el id, volvemos a la lista de pedidos
    if(!isset($_GET["id"])) {
        header("location:gestion_pedidos.php");
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
    <title>Editar Pedido</title>
</head>
<body class="bg-light">

    <?php include "../php/panel_control.php"; ?>

    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">✏️ Editar Pedido</h2>
            </div>
            <div class="card-body">

            <?php
                // Recogemos los datos del pedido para rellenar el formulario
                $id = intval($_GET["id"]);
                $sql = "SELECT * FROM pedidos WHERE id = $id";
                $res = mysqli_query($conn, $sql);
                
                // Si existe el pedido, recogemos los datos, si no, redirigimos
                if (mysqli_num_rows($res) > 0) {
                    $pedido = mysqli_fetch_assoc($res);
                } else {
                    header("location:gestion_pedidos.php");
                    die();
                }
            ?>

                <form method="POST">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="accion" value="editar">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="cliente_id" class="form-label">ID Cliente:</label>
                            <input type="number" class="form-control" id="cliente_id" name="cliente_id"
                                value="<?= $pedido["cliente_id"] ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="producto_id" class="form-label">ID Producto:</label>
                            <input type="number" class="form-control" id="producto_id" name="producto_id"
                                value="<?= $pedido["producto_id"] ?>" required>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                            <a href="gestion_pedidos.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
