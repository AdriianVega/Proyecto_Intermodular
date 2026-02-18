<?php
    // Configuramos los errores para que se muestren por pantalla
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Iniciamos la sesión
    session_start();

    // Comprobamos el rol para que solo entren administradores
    if (!isset($_SESSION["rol"]) || (int)$_SESSION["rol"] !== 1) {

        // Redirigimos al inicio si no es admin
        header("location:../index.php");
        die();
    }

    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Definimos el directorio donde guardaremos la imagen e inicializamos la variable de error
    $directorio = "../img/usuarios/";
    $error = "";

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $icono = $_SESSION["icono"];
    $pagina_activa = "usuarios";

    // Si recibimos el email, empezamos con la edición
    if (isset($_POST["email"])) {

        // Recogemos el id del usuario
        $id = intval($_POST["id"]);

        // Sacamos el icono actual del usuario
        $sql = "SELECT icono FROM usuarios WHERE id = $id";

        // Ejecutamos la consulta
        $res_icono = mysqli_query($conn, $sql);

        // Obtenemos los datos
        $datos = mysqli_fetch_assoc($res_icono);

        // Guardamos la ruta por defecto por si no se cambia la imagen
        $ruta_final = $datos["icono"];

        // Sacamos la ruta temporal y le metemos un nombre único al archivo
        $archivo_temporal = $_FILES["imagen"]["tmp_name"];
        $archivo_original = uniqid().$_FILES["imagen"]["name"];

        // Miramos si se ha subido alguna imagen
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0)
        {
            // Comprobamos que sea una imagen de verdad
            if (getimagesize($archivo_temporal))
            {
                // Colocamos la ruta donde vamos a guardar la foto
                $ruta_final = $directorio . $archivo_original;

                // Movemos la foto de la carpeta temporal a la final
                if(move_uploaded_file($archivo_temporal, $ruta_final))
                {
                    echo "<h1> $archivo_original </h1>";
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
    }

    // Si no hay fallos, actualizamos los datos en la base de datos
    if (empty($error))
    {
        // Si es la acción de editar
        if (isset($_POST["accion"]) && $_POST["accion"] == "editar") {
        
            // Comprobamos si se ha mandado el formulario
            if(isset($_POST["id"]) && !empty($_POST["id"])) {

                // Recogemos los datos del formulario, usamos mysqli_real_escape_string para evitar inyecciones SQL
                $id = intval($_POST["id"]);
                $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
                $email = mysqli_real_escape_string($conn, $_POST["email"]);
                $rol = intval($_POST["rol"]);
                $icono = $ruta_final;

                // Actualizamos los datos del usuario con id = $id
                try {
                    $sql = "UPDATE usuarios SET
                            nombre = '$nombre',
                            email = '$email',
                            rol = $rol,
                            icono = '$icono'
                            $sql_pass
                        WHERE id = $id";

                    // Ejecutamos la consulta
                    mysqli_query($conn, $sql);

                    // Redirigimos a la gestión de usuarios con mensaje de éxito
                    header("location:gestion_usuarios.php?msg=0");
                }
                catch (mysqli_sql_exception $e) {
                    header("location:gestion_usuarios.php?msg=error");
                }

                die();
            }
        }
    }
    // Si no recibimos el id, redirigimos a la lista de usuarios
    if(!isset($_GET["id"])) {
        header("location:gestion_usuarios.php");
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
    <title>Editar Usuario</title>
</head>
<body class="bg-light">
    <?php include "../php/panel_control.php"; ?>

    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">✏️ Editar Usuario</h2>
            </div>
            <div class="card-body">

            <?php
                // Sacamos los datos del usuario para rellenar el formulario
                $id = intval($_GET["id"]);
                $sql = "SELECT * FROM usuarios WHERE id = $id";
                $res = mysqli_query($conn, $sql);
                
                // Si no existe el usuario, redirigimos a la lista
                if (mysqli_num_rows($res) > 0) {
                    $user = mysqli_fetch_assoc($res);
                } else {
                    header("location:gestion_usuarios.php");
                    die();
                }

                // Mostramos el aviso si hay algún error
                if (!empty($error))
                {
                    echo "<div class='alert alert-danger' role='alert'>❌ Error: ". $error. "</div>";
                }
                else
                {
                    // Si todo va bien tras el POST, mandamos a la lista
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $icono = $ruta_final;
                        header("location:gestion_usuarios.php?msg=0");
                    }
                }
            ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="accion" value="editar">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre de Usuario:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($user["nombre"]) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="rol" class="form-label">Rol:</label>
                            <select name="rol" id="rol" class="form-select" required>
                                <option value="1" <?= ($user['rol'] == 1) ? 'selected' : '' ?>>Administrador</option>
                                <option value="0" <?= ($user['rol'] == 0) ? 'selected' : '' ?>>Empleado</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($user["email"]) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="imagen">Seleccione una imagen:</label>
                            <input type="file" name="imagen" id="imagen" >
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                            <a href="gestion_usuarios.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
