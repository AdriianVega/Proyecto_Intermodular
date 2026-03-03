<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador Contraseñas</title>
</head>
<body>
    <?php
        function generarContrasenya($contrasenya) {
            
            return password_hash($contrasenya, PASSWORD_DEFAULT);
        }
    ?>

    <form action="" method="get">
        <label for="password">Contraseña:</label> <br>
        <input type="text" id="password" name="password">
    </form>

    <?php
        if (isset($_GET["password"])) {
            $contrasenya = $_GET["password"];
            echo "Contraseña hasheada: " . generarContrasenya($contrasenya);
        }
    ?>
</body>
</html>