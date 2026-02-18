<?php
    // Iniciamos la conexión a la base de datos
    include "../../Modelo/db/db.inc";
    
    // Iniciamos la sesión
    session_start();

    // Limpiamos y destruimos la sesión actual del usuario
    session_unset();
    session_destroy();

    // Si existe la cookie de recordar sesión, procedemos a borrarla
    if (isset($_COOKIE["token"])) {
        
        // Sacamos el selector del token para eliminarlo de la base de datos
        $partes = explode(":", $_COOKIE["token"]);
        if (count($partes) == 2) {
            $selector = $partes[0];

            // Preparamos la consulta para borrar el token correspondiente
            $check = $conn->prepare("DELETE FROM tokens where selector = ?");

            // Mandamos el selector a la consulta
            $check->bind_param("s", $selector);
            
            // Ejecutamos el borrado en la base de datos
            $check->execute();
        }

        // Caducamos la cookie en el navegador mandándola atrás en el tiempo
        setcookie("token", "", time() - 3600, "/");
    }

    // Redirigimos al formulario de acceso principal
    header("location: ../../control.php");
    die();
?>
