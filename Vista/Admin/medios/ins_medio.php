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

    // Definimos el directorio donde están las fotos
    $directorio = "../img/clientes/";

    // Sacamos los datos de la sesión
    $nombre_usuario = $_SESSION["nombre"];
    $rol = $_SESSION["rol"];
    $pagina_activa = "clientes";

    // Si recibimos los datos del cliente
    if(isset($_POST["nombre"]) && !empty($_POST["nombre"])) {
        
        // Recogemos los datos del formulario limpiando strings para evitar inyecciones SQL
        $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
        $apellidos = mysqli_real_escape_string($conn, $_POST["apellidos"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, password_hash($_POST["password"], PASSWORD_DEFAULT));
        $icono = mysqli_real_escape_string($conn, $_POST["icono"]);
        $direccion = mysqli_real_escape_string($conn, $_POST["direccion"]);
        $genero = mysqli_real_escape_string($conn, $_POST["genero"]);
        $codpostal = mysqli_real_escape_string($conn, $_POST["codpostal"]);
        $poblacion = mysqli_real_escape_string($conn, $_POST["poblacion"]);
        $provincia = mysqli_real_escape_string($conn, $_POST["provincia"]);

        // Comprobamos si el email ya existe en la base de datos
        $sql_check = "SELECT * FROM clientes WHERE email='$email'";
        $res = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($res) > 0)
        {
            // Si el email ya existe, mandamos error
            header("location:gestion_clientes.php?msg=error_gmail");
            die();
        }
        
        try {
                // Metemos el nuevo cliente en la base de datos
                $sql = "INSERT INTO clientes (nombre, apellidos, email, password, direccion, genero, codpostal, poblacion, provincia)
                VALUES ('$nombre', '$apellidos', '$email', '$password', '$direccion', '$genero', '$codpostal', '$poblacion', '$provincia');";

                // Ejecutamos la consulta
                mysqli_query($conn, $sql);

                // Redirigimos a la gestión de clientes con mensaje de éxito
                header("location:gestion_clientes.php?msg=0");
            }
            catch (mysqli_sql_exception $e) {
                header("location:gestion_clientes.php?msg=error");
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
    <title>Nuevo Cliente</title>
</head>
<body class="bg-light">
    <?php include "../php/panel_control.php"; ?>
    
    <main class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h2 class="h4 mb-0">👥 Añadir Cliente</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>

                        <div class="col-md-4">
                            <label for="poblacion" class="form-label">Población</label>
                            <input type="text" class="form-control" id="poblacion" name="poblacion" required>
                        </div>
                        <div class="col-md-4">
                            <label for="provincia" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                        <div class="col-md-2">
                            <label for="codpostal" class="form-label">C.P.</label>
                            <input type="text" class="form-control" id="codpostal" name="codpostal" maxlength="5" required>
                        </div>
                        <div class="col-md-2">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="genero" name="genero">
                                <option value="M">Hombre</option>
                                <option value="F">Mujer</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">Guardar Cliente</button>
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
