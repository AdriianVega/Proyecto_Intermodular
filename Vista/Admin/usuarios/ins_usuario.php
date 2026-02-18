<?php
    // Iniciamos la sesión
    session_start();

    // Comprobamos el rol para que solo entren administradores
    if (!isset($_SESSION["rol"]) || (int)$_SESSION["rol"] !== 1) {
        
        // Redirigimos al inicio si no es admin
        header("location:../index.php");
        die();
    }

    // Definimos el directorio donde guardaremos la foto e inicializamos la variable de error
    $directorio = "../img/usuarios/";
    $error = "";
    
    // Iniciamos la conexión a la base de datos
    include "../db/db.inc";

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $icono = $_SESSION["icono"];
    $pagina_activa = "usuarios";

    // Si recibimos el email, empezamos con el alta del usuario
    if (isset($_POST["email"])) {

        // Sacamos la ruta temporal y le damos un nombre único al archivo
        $archivo_temporal = $_FILES["imagen"]["tmp_name"];
        $archivo_original = uniqid().$_FILES["imagen"]["name"];

        // Miramos si se ha subido alguna imagen correctamente
        if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] != 0)
        {
            $archivo_original = "admin.jpg";
        }
        // Comprobamos que sea una imagen de verdad
        elseif (getimagesize($archivo_temporal))
        {
            // Colocamos la ruta donde vamos a guardar la foto
            $ruta_final = $directorio . $archivo_original;

            // Movemos la foto de la carpeta temporal a la final
            if(!move_uploaded_file($archivo_temporal, $ruta_final))
            {
                $error = "Se ha producido un error subiendo el icono";
            }
        }
        else
        {
            $error = "Sólo se permiten imagenes";
        }

        // Recogemos los datos del formulario, usamos mysqli_real_escape_string para evitar inyecciones SQL
        $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $rol = intval($_POST["rol"]);

        // Encriptamos la contraseña con el hash por defecto de PHP
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // Si no hay fallos durante la subida, seguimos con la inserción
        if (empty($error))
        {
            // Comprobamos si el email ya existe en la base de datos
            $check = mysqli_query($conn, "SELECT id FROM usuarios WHERE email = '$email'");

            if(mysqli_num_rows($check) > 0){
                // Si el mail ya existe, mandamos error
                header("location:gestion_usuarios.php?msg=error_mail");
                die();
            }

            // Preparamos la consulta para meter el nuevo usuario
            $sql = "INSERT INTO usuarios (nombre, email, password, rol, icono)
                    VALUES ('$nombre', '$email', '$password', '$rol', '$archivo_original')";
            
            // Ejecutamos y redirigimos según si ha funcionado o no
            if (mysqli_query($conn, $sql)) {
                header("location:gestion_usuarios.php?msg=0");
            } else {
                header("location:gestion_usuarios.php?msg=error");
            }
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tablas.css">
</head>
<body class="bg-light">
    <?php include "../php/panel_control.php"; ?>
    
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4>➕ Alta de Usuario</h4>
            </div>
            <div class="card-body">
                <?php
                    // Mostramos el aviso si hay algún error durante el proceso
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger' role='alert'>❌ Error: ". $error. "</div>";
                    } else {
                        // Si se ha envia el formulario con éxito, mandamos a la gestión
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            header("location:gestion_usuarios.php?msg=0");
                        }
                    }
                ?>
                <form method="POST" autocomplete="off" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email (Usuario de acceso)</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="form-text text-white">Se guardará cifrada en la base de datos.</div>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select name="rol" id="rol" class="form-select">
                            <option value="0">Empleado</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="form-label">Seleccione una imagen:</label>
                        <input type="file" name="imagen" id="imagen" class="form-control">
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success">Crear Usuario</button>
                        <a href="gestion_usuarios.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
