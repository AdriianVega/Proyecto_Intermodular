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
    $pagina_activa = "clientes";

    // Si recibimos la acción de editar desde el formulario
    if (isset($_POST["accion"]) && $_POST["accion"] == "editar") {
        
        // Comprobamos si se ha mandado el formulario
        if(isset($_POST["id"]) && !empty($_POST["id"])) {

            // Recogemos los datos del formulario limpiando strings para evitar inyecciones SQL
            $id = intval($_POST["id"]);
            $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
            $apellidos = mysqli_real_escape_string($conn, $_POST["apellidos"]);
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $direccion = mysqli_real_escape_string($conn, $_POST["direccion"]);
            $genero = mysqli_real_escape_string($conn, $_POST["genero"]);
            $codpostal = mysqli_real_escape_string($conn, $_POST["codpostal"]);
            $poblacion = mysqli_real_escape_string($conn, $_POST["poblacion"]);
            $provincia = mysqli_real_escape_string($conn, $_POST["provincia"]);

            try {
                // Montamos la consulta para actualizar los datos del cliente
                $sql = "UPDATE clientes SET
                        nombre = '$nombre',
                        apellidos = '$apellidos',
                        email = '$email',
                        direccion = '$direccion',
                        genero = '$genero',
                        codpostal = '$codpostal',
                        poblacion = '$poblacion',
                        provincia = '$provincia'
                        $sql_pass
                    WHERE id = $id";

                // Ejecutamos la consulta
                mysqli_query($conn, $sql);

                // Redirigimos a la gestión de clientes con mensaje de éxito
                header("location:gestion_clientes.php?msg=0");
            }
            catch (mysqli_sql_exception $e) {
                header("location:gestion_clientes.php?msg=error");
            }

            die();
        }
    }

    // Si no recibimos el id, volvemos a la lista de clientes
    if(!isset($_GET["id"])) {
        header("location:gestion_clientes.php");
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
    <title>Editar Cliente</title>
</head>
<body class="bg-light">

    <?php include "../php/panel_control.php"; ?>

    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">✏️ Editar Cliente</h2>
            </div>
            <div class="card-body">

            <?php
                // Recogemos los datos del cliente para rellenar el formulario
                $id = intval($_GET["id"]);
                $sql = "SELECT * FROM clientes WHERE id = $id";
                $res = mysqli_query($conn, $sql);
                
                // Si existe el cliente, recogemos los datos, si no, redirigimos
                if (mysqli_num_rows($res) > 0) {
                    $cli = mysqli_fetch_assoc($res);
                } else {
                    header("location:gestion_clientes.php");
                    die();
                }
            ?>

                <form method="POST">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="accion" value="editar">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($cli["nombre"]) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos"
                                value="<?= htmlspecialchars($cli["apellidos"]) ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($cli["email"]) ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                value="<?= htmlspecialchars($cli["direccion"]) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="poblacion" class="form-label">Población:</label>
                            <input type="text" class="form-control" id="poblacion" name="poblacion"
                                value="<?= htmlspecialchars($cli["poblacion"]) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="provincia" class="form-label">Provincia:</label>
                            <input type="text" class="form-control" id="provincia" name="provincia"
                                value="<?= htmlspecialchars($cli["provincia"]) ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label for="codpostal" class="form-label">C.P.:</label>
                            <input type="text" class="form-control" id="codpostal" name="codpostal" maxlength="5"
                                value="<?= htmlspecialchars($cli["codpostal"]) ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label for="genero" class="form-label">Género:</label>
                            <select class="form-select" id="genero" name="genero">
                                <option value="M" <?= ($cli['genero']=='M')?'selected':'' ?> >Hombre</option>
                                <option value="F" <?= ($cli['genero']=='F')?'selected':'' ?> >Mujer</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                            <a href="gestion_clientes.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
