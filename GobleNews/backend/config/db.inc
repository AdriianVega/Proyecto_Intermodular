<?php
    $host = "localhost"; //Dirección del servidor
    $nombreBD = "noticias"; //Nombre de la BD
    $usuario = "root"; //usuario con acceso a la BD
    $password = ""; //Contraseña del usuario
    // Crear conexión
    $conn = new mysqli($host, $usuario, $password, $nombreBD);
    $conn->set_charset("utf8mb4");
    // Verificar conexión

    if ($conn->connect_error)
    {
        die ("Conexión fallida - ERROR de conexión: " . $conn->
        connect_error);
    }
?>
