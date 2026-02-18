<?php
    function crearSesion($datos) {
        session_regenerate_id(true);

        $_SESSION["id"]     = $datos["id"];
        $_SESSION["nombre"] = $datos["nombre"];
        $_SESSION["email"]  = $datos["email"];
        $_SESSION["icono"] = "../../img/admin/usuarios/adriannataniel.jpg";
    }
    
    session_start();
    
    include "../Modelo/db/db.inc";

    define('MENU_INICIO_LOCATION', "location: ./Admin/menu/menu_inicio.php");

    if (isset($_SESSION["email"])) {
        header(MENU_INICIO_LOCATION);
        die();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin/estilo.css">
    <title>Formulario</title>
</head>
<body>
    <main class="container">

        <div class="col-4 mx-auto vh-100 d-flex align-items-center border-3">
            <div class="gradient-border-card bg-white w-100 p-5 rounded-2">
                <div class="text-center mb-4">
                    <img src="img/logo_tierra.png" width="100px" alt="Logo" class="img-fluid" style="max-width: 200px;">
                    <p style="color:white; font-family: 'Roboto'; font-weight: 900;">GobleNews</p>
                </div>

                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        
                        if (isset($_POST["email"]) && !empty($_POST["email"]) &&
                            filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                            if (isset($_POST["password"]) && !empty($_POST["password"])) {

                                $email = trim($_POST["email"]);
                                $password = $_POST["password"];

                                $check = $conn->prepare("SELECT * FROM administrador WHERE email = ?");

                                $check->bind_param("s", $email);
                                $check->execute();

                                $res = $check->get_result();
                                $datos = $res->fetch_assoc();

                                if ($datos && password_verify($password, $datos["password"])) {
                                    crearSesion($datos);

                                    header(MENU_INICIO_LOCATION);
                                    die();
                                } else {
                                    echo '<div class="alert alert-warning">⚠️ El email y/o la contraseña son incorrectos.</div>';
                                }
                            } else {
                                echo '<div class="alert alert-warning">⚠️ Error en el campo Password </div>';
                            }
                        } else {
                            echo '<div class="alert alert-warning">⚠️ Introduce un email válido.</div>';
                        }
                    }
                ?>
                <form method="post">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control mb-3" placeholder="admin@goblenews.com" data-bs-theme="dark" required>

                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Password" class="form-control" data-bs-theme="dark" required>

                    <input type="submit" value="Acceder" id="submit" name="submit" class="gradient-button btn btn-primary w-100 mt-4">
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
